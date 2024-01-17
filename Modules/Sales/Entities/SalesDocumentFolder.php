<?php

namespace Modules\Sales\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesDocumentFolder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'workspace',
        'created_by',
    ];

    public function parents()
    {
        return $this->hasOne('Modules\Sales\Entities\SalesDocumentFolder', 'id', 'parent');
    }
    protected static function newFactory()
    {
        return \Modules\Sales\Database\factories\SalesDocumentFolderFactory::new();
    }
}
