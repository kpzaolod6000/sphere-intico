<?php

namespace Modules\DoubleEntry\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JournalEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'reference',
        'description',
        'journal_id',
        'workspace',
        'created_by',
    ];


    protected static function newFactory()
    {
        return \Modules\DoubleEntry\Database\factories\JournalEntryFactory::new();
    }


    public static function journalNumberFormat($number,$company_id = null,$workspace_id = null)
    {
        if(!empty($company_id)&& empty($workspace_id)){
            $data = !empty(company_setting('journal_prefix',$company_id)) ? company_setting('journal_prefix',$company_id) : '#JUR0000';

        }elseif(!empty($company_id)&& !empty($workspace_id)){
            $data = !empty(company_setting('journal_prefix',$company_id,$workspace_id)) ? company_setting('journal_prefix',$company_id,$workspace_id) : '#JUR0000';
        }else{
            $data = !empty(company_setting('journal_prefix')) ? company_setting('journal_prefix') : '#JUR0000';
        }
        return $data. sprintf("%05d", $number);
    }

    public function accounts()
    {
        return $this->hasmany('Modules\DoubleEntry\Entities\JournalItem', 'journal', 'id');
    }

    public function totalCredit()
    {
        $total = 0;
        foreach($this->accounts as $account)
        {
            $total += $account->credit;
        }

        return $total;
    }

    public function totalDebit()
    {
        $total = 0;
        foreach($this->accounts as $account)
        {
            $total += $account->debit;
        }

        return $total;
    }

}
