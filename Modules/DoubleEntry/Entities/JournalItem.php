<?php

namespace Modules\DoubleEntry\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JournalItem extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\DoubleEntry\Database\factories\JournalItemFactory::new();
    }

    public function accounts()
    {
        return $this->hasOne('Modules\DoubleEntry\Entities\ChartOfAccount', 'id', 'account');
    }
}
