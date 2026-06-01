<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Extra extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'price',
        'calories',
        'proteins',
        'carbs',
        'fats',
    ];

    public function items()
    {
        return $this->belongsToMany(Item::class);
    }

    public function orderItemExtras()
    {
        return $this->hasMany(OrderItemExtra::class);
    }
}