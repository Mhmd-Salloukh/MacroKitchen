<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'total_calories',
        'total_proteins',
        'total_carbs',
        'total_fats',
        'delivered_at', 
        'note',
    ];

    protected $casts = [
        'delivered_at' => 'datetime', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}