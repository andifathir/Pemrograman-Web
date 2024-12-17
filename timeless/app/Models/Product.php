<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'description',
        'price',
        'quantity_in_stock',
        'image_url'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }
}
