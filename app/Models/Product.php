<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = ['name', 'slug', 'desc', 'qty', 'thumbnail', 'first_image', 'second_image', 'third_image', 'price', 'category_id', 'brand_id', 'status', 'brand_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class,  'color_product');
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_size');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)
        ->with('user')
        ->where('approved', 1)
        ->latest();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public static function removeProductImageFromStorage($imagePath)
    {
        $relativePath = str_replace('images/', '', $imagePath);

        if ($relativePath && file_exists(public_path('images/'.$relativePath))) {
            unlink(public_path('images/'.$relativePath));
        }
    }

}
