<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChartOfAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'sub_type',
        'is_enabled',
        'description',
        'workspace',
        'created_by',
    ];



    protected static function newFactory()
    {
        return \Modules\Account\Database\factories\ChartOfAccountFactory::new();
    }

    public function types()
    {
        return $this->hasOne('Modules\Account\Entities\ChartOfAccountType', 'id', 'type');
    }

    public function subType()
    {
        return $this->hasOne('Modules\Account\Entities\ChartOfAccountSubType', 'id', 'sub_type');
    }

//    public function balance()
//    {
//        $journalItem         = JournalItem::select(\DB::raw('sum(credit) as totalCredit'),
//            \DB::raw('sum(debit) as totalDebit'),
//            \DB::raw('sum(credit) - sum(debit) as netAmount'))->where('account', $this->id);
//        $journalItem         = $journalItem->first();
//        $data['totalCredit'] = $journalItem->totalCredit;
//        $data['totalDebit']  = $journalItem->totalDebit;
//        $data['netAmount']   = $journalItem->netAmount;
//
//        return $data;
//    }


}
