<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemExtra extends Model
{
    protected $fillable = [
        'order_item_id',
        'extra_id',
        'extra_name',
        'extra_unit_price',
        'extra_line_total',
        'extra_calories',
        'extra_proteins',
        'extra_carbs',
        'extra_fats',
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function extra()
    {
        return $this->belongsTo(Extra::class);
    }
}