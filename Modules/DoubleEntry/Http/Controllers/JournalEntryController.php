<?php

namespace Modules\DoubleEntry\Http\Controllers;

use Illuminate\DoubleEntry\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Account\Entities\BankAccount;
use Modules\Account\Entities\ChartOfAccount;
use Modules\DoubleEntry\Entities\JournalEntry;
use Modules\DoubleEntry\Entities\JournalItem;
use Modules\DoubleEntry\Events\CreateJournalAccount;
use Modules\DoubleEntry\Events\DestroyJournalAccount;
use Modules\DoubleEntry\Events\UpdateJournalAccount;
use Rawilk\Settings\Support\Context;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class JournalEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    public function index()
    {
        if(\Auth::user()->can('journal entry manage'))
        {
            $journalEntries = JournalEntry::where('created_by', '=', creatorId())->get();

            return view('doubleentry::journalEntry.index', compact('journalEntries'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */


    function journalNumber()
    {
        $latest = JournalEntry::where('created_by', '=', creatorId())->latest()->first();
        if(!$latest)
        {
            return 1;
        }

        return $latest->journal_id + 1;
    }


    public function create()
    {
        if(\Auth::user()->can('journal entry create'))
        {
            $accounts = ChartOfAccount::select(\DB::raw('CONCAT(code, " - ", name) AS code_name, id'))
                ->where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()
                ->pluck('code_name', 'id');

            $journalId = $this->journalNumber();

            return view('doubleentry::journalEntry.create', compact('accounts', 'journalId'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }



    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {


        if(\Auth::user()->can('journal entry create'))
        {
            $validator = \Validator::make(
                $request->all(), [
                    'date' => 'required',
                    'accounts' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $accounts = $request->accounts;


            $totalDebit  = 0;
            $totalCredit = 0;
            for($i = 0; $i < count($accounts); $i++)
            {
                $debit       = isset($accounts[$i]['debit']) ? $accounts[$i]['debit'] : 0;
                $credit      = isset($accounts[$i]['credit']) ? $accounts[$i]['credit'] : 0;
                $totalDebit  += $debit;
                $totalCredit += $credit;
            }

            if($totalCredit != $totalDebit)
            {
                return redirect()->back()->with('error', __('Debit and Credit must be Equal.'));
            }

            $journal              = new JournalEntry();
            $journal->journal_id  = $this->journalNumber();
            $journal->date        = $request->date;
            $journal->reference   = $request->reference;
            $journal->description = $request->description;
            $journal->workspace   = getActiveWorkSpace();
            $journal->created_by  = creatorId();
            $journal->save();

            for($i = 0; $i < count($accounts); $i++)

            {
                $journalItem              = new JournalItem();
                $journalItem->journal     = $journal->id;
                $journalItem->account     = $accounts[$i]['account'];
                $journalItem->description = $accounts[$i]['description'];
                $journalItem->debit       = isset($accounts[$i]['debit']) ? $accounts[$i]['debit'] : 0;
                $journalItem->credit      = isset($accounts[$i]['credit']) ? $accounts[$i]['credit'] : 0;
                $journalItem->workspace   = getActiveWorkSpace();
                $journalItem->created_by  = creatorId();
                $journalItem->save();

                $bankAccounts =[];
                if(module_is_active('Account'))
                {
                    $bankAccounts = \Modules\Account\Entities\BankAccount::where('chart_account_id','=',$accounts[$i]['account'])->get();
                }

                if(!empty($bankAccounts))
                {
                    foreach ($bankAccounts as $bankAccount)
                    {
                        $old_balance = $bankAccount->opening_balance;
                        if ($journalItem->debit > 0) {
                            $new_balance = $old_balance - $journalItem->debit;
                        }
                        if ($journalItem->credit > 0) {
                            $new_balance = $old_balance + $journalItem->credit;
                        }
                        if (isset($new_balance)) {
                            $bankAccount->opening_balance = $new_balance;
                            $bankAccount->save();
                        }
                    }
                }

            }

            event(new CreateJournalAccount($request,$journal));



            return redirect()->route('journal-entry.index')->with('success', __('Journal entry successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(JournalEntry $journalEntry)
    {

        if(\Auth::user()->can('journal entry show'))
        {
            if($journalEntry->created_by == creatorId())
            {
                $accounts = $journalEntry->accounts;

                $settings['company_name'] = company_setting('company_name');
                $settings['company_telephone'] = company_setting('company_telephone');
                $settings['company_address'] = company_setting('company_address');
                $settings['company_city'] = company_setting('company_city');
                $settings['company_state'] = company_setting('company_state');
                $settings['company_country'] = company_setting('company_country');


                return view('doubleentry::journalEntry.view', compact('journalEntry', 'accounts','settings'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */


    public function edit(JournalEntry $journalEntry)
    {
        if(\Auth::user()->can('journal entry edit'))
        {
            $accounts = ChartOfAccount::select(\DB::raw('CONCAT(code, " - ", name) AS code_name, id'))
                ->where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()
                ->pluck('code_name', 'id');

            return view('doubleentry::journalEntry.edit', compact('accounts', 'journalEntry'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */

    public function update(Request $request, JournalEntry $journalEntry)
    {
        if(\Auth::user()->can('journal entry edit'))
        {
            if($journalEntry->created_by == creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                        'date' => 'required',
                        'accounts' => 'required',
                    ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $accounts = $request->accounts;

                $totalDebit  = 0;
                $totalCredit = 0;
                for($i = 0; $i < count($accounts); $i++)
                {
                    $debit       = isset($accounts[$i]['debit']) ? $accounts[$i]['debit'] : 0;
                    $credit      = isset($accounts[$i]['credit']) ? $accounts[$i]['credit'] : 0;
                    $totalDebit  += $debit;
                    $totalCredit += $credit;
                }

                if($totalCredit != $totalDebit)
                {
                    return redirect()->back()->with('error', __('Debit and Credit must be Equal.'));
                }

                $journalEntry->date        = $request->date;
                $journalEntry->reference   = $request->reference;
                $journalEntry->description = $request->description;
                $journalEntry->created_by  = creatorId();
                $journalEntry->save();

                for($i = 0; $i < count($accounts); $i++)
                {
                    $journalItem = JournalItem::find($accounts[$i]['id']);

                    if($journalItem == null)
                    {
                        $journalItem          = new JournalItem();
                        $journalItem->journal = $journalEntry->id;
                    }

                    if(isset($accounts[$i]['account']))
                    {
                        $journalItem->account = $accounts[$i]['account'];
                    }

                    $journalItem->description = $accounts[$i]['description'];
                    $journalItem->debit  = isset($accounts[$i]['debit']) ? $accounts[$i]['debit'] : 0;
                    $journalItem->credit = isset($accounts[$i]['credit']) ? $accounts[$i]['credit'] : 0;
                    $journalItem->save();

                    $bankAccounts =[];
                    if(module_is_active('Account'))
                    {
                        $bankAccounts =  \Modules\Account\Entities\BankAccount::where('chart_account_id','=',$accounts[$i]['account'])->get();

                    }
                    if(!empty($bankAccounts))
                    {
                        foreach ($bankAccounts as $bankAccount)
                        {
                            $old_balance = $bankAccount->opening_balance;
                            if ($journalItem->debit > 0) {
                                $new_balance = $old_balance - $journalItem->debit;
                            }
                            if ($journalItem->credit > 0) {
                                $new_balance = $old_balance + $journalItem->credit;
                            }
                            if (isset($new_balance)) {
                                $bankAccount->opening_balance = $new_balance;
                                $bankAccount->save();
                            }
                        }
                    }
                }

                event(new UpdateJournalAccount($request,$journalEntry));

                return redirect()->route('journal-entry.index')->with('success', __('Journal entry successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */


    public function destroy(JournalEntry $journalEntry)
    {
        if (\Auth::user()->can('journal entry delete')) {
            if ($journalEntry->created_by == creatorId()) {
                $journalItems = JournalItem::where('journal', '=', $journalEntry->id)->get();
                foreach ($journalItems as $journalItem) {
                    $account = $journalItem->account;

                    if (module_is_active('Account')) {
                        $bankAccounts = \Modules\Account\Entities\BankAccount::where('chart_account_id', '=', $account)->get();

                        foreach ($bankAccounts as $bankAccount) {
                            $old_balance = $bankAccount->opening_balance;

                            if ($journalItem->debit > 0) {
                                $new_balance = $old_balance - $journalItem->debit;
                            }
                            if ($journalItem->credit > 0) {
                                $new_balance = $old_balance + $journalItem->credit;
                            }

                            if (isset($new_balance)) {
                                $bankAccount->opening_balance = $new_balance;
                                $bankAccount->save();
                            }
                        }
                    }

                    // Delete the individual JournalItem record
                    $journalItem->delete();
                }

                // Finally, delete the JournalEntry record
                $journalEntry->delete();


                event(new DestroyJournalAccount($journalEntry));

                return redirect()->route('journal-entry.index')->with('success', __('Journal entry successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }



    public function journalDestroy($item_id)
    {
        if(\Auth::user()->can('journal entry delete'))
        {
            $journal = JournalItem::find($item_id);
            $journal->delete();

            return redirect()->back()->with('success', __('Journal account successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function setting(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'journal_prefix' => 'required',
            ]);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        else
        {
            $userContext = new Context(['user_id' => Auth::user()->id,'workspace_id'=>getActiveWorkSpace()]);
            \Settings::context($userContext)->set('journal_prefix', $request->journal_prefix);
            return redirect()->back()->with('success','Journal setting save sucessfully.');
        }
    }
}
