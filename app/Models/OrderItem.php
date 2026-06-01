<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'item_id',
        'meal_name',
        'quantity',
        'unit_price',
        'total_price',
        'subtotal_calories',
        'subtotal_proteins',
        'subtotal_carbs',
        'subtotal_fats',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItemExtras()
    {
        return $this->hasMany(OrderItemExtra::class);
    }
}