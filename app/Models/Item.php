<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'condition_id',
        'category_id',
        'brand',
        'price',
        'shipping',
        'description',
    ];
}
