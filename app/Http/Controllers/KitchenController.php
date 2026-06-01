<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;


class KitchenController extends Controller
{
    public function KitchenHome()
    {
        $user = Auth::user();
        return view('kitchen.home', compact('user'));
    }

    public function ManageOrders()
    {
        $orders = Order::with('user')
            ->whereIn('status', ['pending', 'ready'])
            ->latest()
            ->get();

        return view('kitchen.orders', compact('orders'));
    }

 public function ShowOrder(int $id)
{
    $order = Order::with([
        'user',
        'orderItems.orderItemExtras'
    ])->findOrFail($id);

    foreach ($order->orderItems as $orderItem) {
        $unitCalories = $orderItem->subtotal_calories / max($orderItem->quantity, 1);
        $unitProteins = $orderItem->subtotal_proteins / max($orderItem->quantity, 1);
        $unitCarbs    = $orderItem->subtotal_carbs / max($orderItem->quantity, 1);
        $unitFats     = $orderItem->subtotal_fats / max($orderItem->quantity, 1);

        foreach ($orderItem->orderItemExtras as $extra) {
            $unitCalories += $extra->extra_calories / max($orderItem->quantity, 1);
            $unitProteins += $extra->extra_proteins / max($orderItem->quantity, 1);
            $unitCarbs    += $extra->extra_carbs / max($orderItem->quantity, 1);
            $unitFats     += $extra->extra_fats / max($orderItem->quantity, 1);
        }

        $orderItem->unit_calories = $unitCalories;
        $orderItem->unit_proteins = $unitProteins;
        $orderItem->unit_carbs    = $unitCarbs;
        $orderItem->unit_fats     = $unitFats;
    }

    return view('kitchen.order', compact('order'));
}

public function UpdateStatus(int $id, string $status)
{
    $order = Order::findOrFail($id);

    if (in_array($status, ['ready', 'delivered'])) {
        $order->status = $status;

        if ($status === 'delivered') {
            $order->delivered_at = now(); 
        }

        $order->save();
    }

    return redirect()->route('kitchen.order', $order->id)
        ->with('message', "Order status updated to {$status}!");
}
public function PreviousOrders()
{
    $orders = Order::with('user')
        ->whereIn('status', [ 'delivered'])
        ->latest()
        ->get();

    return view('kitchen.previous_orders', compact('orders'));
}
public function DeleteOrder(int $id)
{
    $order = Order::findOrFail($id);
    $order->delete();

    return redirect()->route('kitchen.orders')
        ->with('message', 'Order deleted successfully.');
}
}