<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'category_id',
        'type',
        'status',
        'short_description',
        'custom_html',
        'sale_price',
        'image1_path',
        'image2_path',
        'image3_path',
        'image4_path',
        'fields',
    ];

    protected $casts = [
        'fields' => 'json',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function files()
    {
        return $this->hasMany(ProductFile::class);
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class);
    }
}
