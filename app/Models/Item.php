<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'image',
        'description',
        'base_price',
        'calories',
        'proteins',
        'carbs',
        'fats',
    ];
    
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function extras()
    {
        return $this->belongsToMany(Extra::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}