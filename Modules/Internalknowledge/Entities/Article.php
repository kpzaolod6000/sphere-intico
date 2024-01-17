<?php

namespace Modules\Internalknowledge\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Internalknowledge\Entities\Book;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'book',
        'title',
        'description',
        'type',
        'content',
        'created_by',
        'workspace',
        'post_id',

    ];

    protected static function newFactory()
    {
        return \Modules\Internalknowledge\Database\factories\ArticleFactory::new();
    }

    public function book_name()
    {
        return $this->hasOne(Book::class, 'id', 'book');
    }
}
