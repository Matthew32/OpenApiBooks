<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

final class Book extends Model
{
    protected $table = 'books';

    protected $fillable = [
        'title',
        'author_name',
        'sku',
        'price'
    ];

    protected $casts = [
        'price' => 'double',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    public static function boot() {
        parent::boot();

        self::creating(function (Book $model) {
            $model->sku = Crypt::encrypt(rand(1,9999). time());
            $model->price = rand(50, 100);
            return $model;
        });
    }
}
