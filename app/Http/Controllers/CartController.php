<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Extra;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemExtra;

class CartController extends Controller
{
    public function AddToCart(Request $request, Item $item)
    {
        $fields = $request->validate([
            'qty' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$item->id])) {
            return response()->json(['status' => 'error', 'message' => 'Item is already in the cart!']);
        }

        $cart[$item->id] = [
            'id' => $item->id,
            'qty' => $fields['qty'],
            'name' => $item->name,
            'price' => $item->base_price, // unit price
            'calories' => $item->calories,
            'proteins' => $item->proteins,
            'carbs' => $item->carbs,
            'fats' => $item->fats,
            'image' => $item->image,
            // new item always starts with empty extras
            'extras' => []
        ];

        session()->put('cart', $cart);
        return response()->json(['status' => 'success', 'message' => 'Item added to cart successfully!']);
    }

    public function Cart()
    {
        $cart = session()->get('cart', []);

        $itemsIds = array_keys($cart);
        $items = Item::whereIn('id', $itemsIds)->get();

        foreach ($items as $item) {
            if (isset($cart[$item->id])) {
                // keep session cart prices and basic metadata in sync with authoritative DB values
                $cart[$item->id]['name'] = $item->name;
                $cart[$item->id]['price'] = $item->base_price;
                $cart[$item->id]['calories'] = $item->calories;
                $cart[$item->id]['proteins'] = $item->proteins;
                $cart[$item->id]['carbs'] = $item->carbs;
                $cart[$item->id]['fats'] = $item->fats;
                $cart[$item->id]['image'] = $item->image;
            }
        }

        $itemExtras = [];
        foreach ($items as $item) {
            if (method_exists($item, 'extras')) {
                $itemExtras[$item->id] = $item->extras->map(function($e) {
                    return [
                        'id' => $e->id,
                        'name' => $e->name,
                        'price' => $e->price,
                        'calories' => $e->calories ?? 0,
                        'proteins' => $e->proteins ?? 0,
                        'carbs' => $e->carbs ?? 0,
                        'fats' => $e->fats ?? 0,
                    ];
                })->toArray();
            } else {
                $itemExtras[$item->id] = [];
            }
        }

        $subtotal = 0.0;    
        $extras_total = 0.0;

        $subtotal_calories = 0;
        $subtotal_proteins = 0;
        $subtotal_carbs = 0;
        $subtotal_fats = 0;

        $extras_calories = 0;
        $extras_proteins = 0;
        $extras_carbs = 0;
        $extras_fats = 0;

        foreach ($cart as $cartItem) {
            $unitPrice = isset($cartItem['price']) ? (float) $cartItem['price'] : 0.0;
            $qty = isset($cartItem['qty']) ? (int) $cartItem['qty'] : 1;
            $subtotal += $unitPrice * $qty;

            $itemModel = Item::find($cartItem['id']);
            if ($itemModel) {
                $subtotal_calories += (int)($itemModel->calories ?? 0) * $qty;
                $subtotal_proteins += (int)($itemModel->proteins ?? 0) * $qty;
                $subtotal_carbs += (int)($itemModel->carbs ?? 0) * $qty;
                $subtotal_fats += (int)($itemModel->fats ?? 0) * $qty;
            } else {
                $subtotal_calories += (isset($cartItem['calories']) ? (int)$cartItem['calories'] : 0) * $qty;
                $subtotal_proteins += (isset($cartItem['proteins']) ? (int)$cartItem['proteins'] : 0) * $qty;
                $subtotal_carbs += (isset($cartItem['carbs']) ? (int)$cartItem['carbs'] : 0) * $qty;
                $subtotal_fats += (isset($cartItem['fats']) ? (int)$cartItem['fats'] : 0) * $qty;
            }

            $extrasForThisItem = $itemExtras[$cartItem['id']] ?? [];
            if (!empty($cartItem['extras']) && is_array($cartItem['extras'])) {
                foreach ($cartItem['extras'] as $extraId) {
                    $extraModel = collect($extrasForThisItem)->firstWhere('id', $extraId);
                    if ($extraModel) {
                        $extraPrice = isset($extraModel['price']) ? (float) $extraModel['price'] : 0.0;
                        $extras_total += $extraPrice * $qty;

                        $extras_calories += (isset($extraModel['calories']) ? (int)$extraModel['calories'] : 0) * $qty;
                        $extras_proteins += (isset($extraModel['proteins']) ? (int)$extraModel['proteins'] : 0) * $qty;
                        $extras_carbs += (isset($extraModel['carbs']) ? (int)$extraModel['carbs'] : 0) * $qty;
                        $extras_fats += (isset($extraModel['fats']) ? (int)$extraModel['fats'] : 0) * $qty;
                    }
                }
            }
        }

        $total = $subtotal + $extras_total;
        $total_calories = $subtotal_calories + $extras_calories;
        $total_proteins = $subtotal_proteins + $extras_proteins;
        $total_carbs = $subtotal_carbs + $extras_carbs;
        $total_fats = $subtotal_fats + $extras_fats;

        session()->put('cart', $cart);

        return view('main.cart', [
            'cart' => $cart,
            'items' => $items,
            'itemExtras' => $itemExtras,
            'subtotal' => $subtotal,
            'extras_total' => $extras_total,
            'total' => $total,
            // Macros
            'subtotal_calories' => $subtotal_calories,
            'subtotal_proteins' => $subtotal_proteins,
            'subtotal_carbs' => $subtotal_carbs,
            'subtotal_fats' => $subtotal_fats,
            'extras_calories' => $extras_calories,
            'extras_proteins' => $extras_proteins,
            'extras_carbs' => $extras_carbs,
            'extras_fats' => $extras_fats,
            'total_calories' => $total_calories,
            'total_proteins' => $total_proteins,
            'total_carbs' => $total_carbs,
            'total_fats' => $total_fats,
        ]);
    }

    public function IncrementCart(Item $item)
    {
        $cart = session()->get('cart', []);
        if (!isset($cart[$item->id])) {
            return response()->json(['status' => 'error', 'message' => 'Item not in cart!']);
        }

        $cart[$item->id]['qty']++;
        session()->put('cart', $cart);

        return $this->UpdateCart('Item quantity increased!');
    }

    public function DecrementCart(Item $item)
    {
        $cart = session()->get('cart', []);

        if (!isset($cart[$item->id])) {
            return response()->json(['status' => 'error', 'message' => 'Item not in cart!']);
        }

        if ($cart[$item->id]['qty'] > 1) {
            $cart[$item->id]['qty']--;
            session()->put('cart', $cart);
            return $this->UpdateCart('Item quantity decreased!');
        }

        return response()->json(['status' => 'error', 'message' => 'Quantity cannot be less than 1!']);
    }

    public function RemoveFromCart(Item $item)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$item->id])) {
            unset($cart[$item->id]);
            session()->put('cart', $cart);
            return $this->UpdateCart('Item removed!');
        }

        return response()->json(['status' => 'error', 'message' => 'Item not found in cart!']);
    }

    public function UpdateExtras(Request $request)
    {
        $data = $request->validate([
            'item_id'  => 'required|integer|exists:items,id',
            'extra_id' => 'required|integer|exists:extras,id',
            'checked'  => 'required|boolean',
        ]);

        $cart = session()->get('cart', []);
        $itemId = (int) $data['item_id'];
        $extraId = (int) $data['extra_id'];

        if (!isset($cart[$itemId])) {
            return response()->json(['status' => 'error', 'message' => 'Item not in cart!']);
        }

        if ($data['checked']) {
            if (!in_array($extraId, $cart[$itemId]['extras'])) {
                $cart[$itemId]['extras'][] = $extraId;
            }
        } else {
            $cart[$itemId]['extras'] = array_values(array_diff($cart[$itemId]['extras'], [$extraId]));
        }

        session()->put('cart', $cart);

        return $this->UpdateCart('Extras updated!');
    }

    private function UpdateCart($message = 'Cart updated!')
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'empty' => true,
                'newSubtotal' => 0,
                'extras_total' => 0,
                'total' => 0,
                'subtotal_calories' => 0,
                'subtotal_proteins' => 0,
                'subtotal_carbs' => 0,
                'subtotal_fats' => 0,
                'extras_calories' => 0,
                'extras_proteins' => 0,
                'extras_carbs' => 0,
                'extras_fats' => 0,
                'total_calories' => 0,
                'total_proteins' => 0,
                'total_carbs' => 0,
                'total_fats' => 0,
            ]);
        }

        $subtotal = 0.0; $extras_total = 0.0;
        $subtotal_calories = 0; $subtotal_proteins = 0; $subtotal_carbs = 0; $subtotal_fats = 0;
        $extras_calories = 0; $extras_proteins = 0; $extras_carbs = 0; $extras_fats = 0;

        foreach ($cart as $cartItem) {
            $qty = isset($cartItem['qty']) ? (int)$cartItem['qty'] : 1;
            $unitPrice = isset($cartItem['price']) ? (float)$cartItem['price'] : 0.0;
            $subtotal += $unitPrice * $qty;

            $itemModel = Item::find($cartItem['id']);
            if ($itemModel) {
                $subtotal_calories += (int)($itemModel->calories ?? 0) * $qty;
                $subtotal_proteins += (int)($itemModel->proteins ?? 0) * $qty;
                $subtotal_carbs += (int)($itemModel->carbs ?? 0) * $qty;
                $subtotal_fats += (int)($itemModel->fats ?? 0) * $qty;

                $extrasForThisItem = method_exists($itemModel, 'extras') ? $itemModel->extras : collect();
            } else {
                $subtotal_calories += (isset($cartItem['calories']) ? (int)$cartItem['calories'] : 0) * $qty;
                $subtotal_proteins += (isset($cartItem['proteins']) ? (int)$cartItem['proteins'] : 0) * $qty;
                $subtotal_carbs += (isset($cartItem['carbs']) ? (int)$cartItem['carbs'] : 0) * $qty;
                $subtotal_fats += (isset($cartItem['fats']) ? (int)$cartItem['fats'] : 0) * $qty;

                $extrasForThisItem = collect();
            }

            if (!empty($cartItem['extras']) && is_array($cartItem['extras'])) {
                foreach ($cartItem['extras'] as $extraId) {
                    $extraModel = $extrasForThisItem->firstWhere('id', $extraId);
                    if ($extraModel) {
                        $extraUnitPrice = isset($extraModel->price) ? (float)$extraModel->price : 0.0;
                        $extras_total += $extraUnitPrice * $qty;

                        $extras_calories += (int)($extraModel->calories ?? 0) * $qty;
                        $extras_proteins += (int)($extraModel->proteins ?? 0) * $qty;
                        $extras_carbs += (int)($extraModel->carbs ?? 0) * $qty;
                        $extras_fats += (int)($extraModel->fats ?? 0) * $qty;
                    }
                }
            }
        }

        $total = $subtotal + $extras_total;
        $total_calories = $subtotal_calories + $extras_calories;
        $total_proteins = $subtotal_proteins + $extras_proteins;
        $total_carbs = $subtotal_carbs + $extras_carbs;
        $total_fats = $subtotal_fats + $extras_fats;

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'newSubtotal' => $subtotal,
            'extras_total' => $extras_total,
            'total' => $total,
            'subtotal_calories' => $subtotal_calories,
            'subtotal_proteins' => $subtotal_proteins,
            'subtotal_carbs' => $subtotal_carbs,
            'subtotal_fats' => $subtotal_fats,
            'extras_calories' => $extras_calories,
            'extras_proteins' => $extras_proteins,
            'extras_carbs' => $extras_carbs,
            'extras_fats' => $extras_fats,
            'total_calories' => $total_calories,
            'total_proteins' => $total_proteins,
            'total_carbs' => $total_carbs,
            'total_fats' => $total_fats,
        ]);
    }

  public function Order(Request $request)
{
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login')->with('error', 'Please login to place an order.');
    }

    if (empty($user->phone) || empty($user->address)) {
        session()->put('resume_order', true);

        return redirect()->route('profile')
            ->with('error', 'Please complete your profile (phone & address) before placing an order.');
    }

    $cart = session()->get('cart', []);
    if (empty($cart)) {
        return redirect()->back()->with('error', 'Your cart is empty!');
    }

    $items = Item::whereIn('id', array_keys($cart))->get();
    foreach ($items as $item) {
        if (isset($cart[$item->id])) {
            $cart[$item->id]['price'] = $item->base_price;
            $cart[$item->id]['calories'] = $item->calories;
            $cart[$item->id]['proteins'] = $item->proteins;
            $cart[$item->id]['carbs'] = $item->carbs;
            $cart[$item->id]['fats'] = $item->fats;
        }
    }

    $extras = collect();
    foreach ($items as $item) {
        if (method_exists($item, 'extras')) {
            $extras = $extras->merge($item->extras);
        }
    }
    $extras = $extras->unique('id')->values();

    $subtotal = 0.0;
    $extras_total = 0.0;

    $total_calories = 0;
    $total_proteins = 0;
    $total_carbs = 0;
    $total_fats = 0;

    $orderItemsData = [];

    foreach ($cart as $cartItem) {
        $unitPrice = (float)($cartItem['price'] ?? 0.0);
        $qty = (int)($cartItem['qty'] ?? 1);

        $subtotal += $unitPrice * $qty;

        $itemCalories = ($cartItem['calories'] ?? 0) * $qty;
        $itemProteins = ($cartItem['proteins'] ?? 0) * $qty;
        $itemCarbs    = ($cartItem['carbs'] ?? 0) * $qty;
        $itemFats     = ($cartItem['fats'] ?? 0) * $qty;

        $total_calories += $itemCalories;
        $total_proteins += $itemProteins;
        $total_carbs    += $itemCarbs;
        $total_fats     += $itemFats;

        $orderItemsData[] = [
            'item_id'            => $cartItem['id'],
            'meal_name'          => $cartItem['name'],
            'quantity'           => $qty,
            'unit_price'         => $unitPrice,
            'total_price'        => $unitPrice * $qty,
            'subtotal_calories'  => $itemCalories,
            'subtotal_proteins'  => $itemProteins,
            'subtotal_carbs'     => $itemCarbs,
            'subtotal_fats'      => $itemFats,
            'extras'             => $cartItem['extras'] ?? [],
        ];
    }

    foreach ($orderItemsData as $itemData) {
        foreach ($itemData['extras'] as $extraId) {
            $extraModel = $extras->firstWhere('id', $extraId);
            if ($extraModel) {
                $extraUnitPrice = (float)($extraModel->price ?? 0.0);
                $extraLineTotal = $extraUnitPrice * $itemData['quantity'];

                $extraCalories = ($extraModel->calories ?? 0) * $itemData['quantity'];
                $extraProteins = ($extraModel->proteins ?? 0) * $itemData['quantity'];
                $extraCarbs    = ($extraModel->carbs ?? 0) * $itemData['quantity'];
                $extraFats     = ($extraModel->fats ?? 0) * $itemData['quantity'];

                $total_calories += $extraCalories;
                $total_proteins += $extraProteins;
                $total_carbs    += $extraCarbs;
                $total_fats     += $extraFats;

                $extras_total += $extraLineTotal;
            }
        }
    }

    $order = Order::create([
        'user_id'        => Auth::id(),
        'total_price'    => $subtotal + $extras_total,
        'status'         => 'pending',
        'total_calories' => $total_calories,
        'total_proteins' => $total_proteins,
        'total_carbs'    => $total_carbs,
        'total_fats'     => $total_fats,
        'note'           => $request->input('note'),
    ]);

    foreach ($orderItemsData as $itemData) {
        $orderItem = $order->orderItems()->create($itemData);

        foreach ($itemData['extras'] as $extraId) {
            $extraModel = $extras->firstWhere('id', $extraId);
            if ($extraModel) {
                $extraUnitPrice = (float)($extraModel->price ?? 0.0);
                $extraLineTotal = $extraUnitPrice * $itemData['quantity'];

                $extraCalories = ($extraModel->calories ?? 0) * $itemData['quantity'];
                $extraProteins = ($extraModel->proteins ?? 0) * $itemData['quantity'];
                $extraCarbs    = ($extraModel->carbs ?? 0) * $itemData['quantity'];
                $extraFats     = ($extraModel->fats ?? 0) * $itemData['quantity'];

                $orderItem->orderItemExtras()->create([
                    'extra_id'         => $extraModel->id,
                    'extra_name'       => $extraModel->name,
                    'extra_unit_price' => $extraUnitPrice,
                    'extra_line_total' => $extraLineTotal,
                    'extra_calories'   => $extraCalories,
                    'extra_proteins'   => $extraProteins,
                    'extra_carbs'      => $extraCarbs,
                    'extra_fats'       => $extraFats,
                ]);
            }
        }

        $itemCalories = $orderItem->subtotal_calories;
        $itemProteins = $orderItem->subtotal_proteins;
        $itemCarbs    = $orderItem->subtotal_carbs;
        $itemFats     = $orderItem->subtotal_fats;

        foreach ($orderItem->orderItemExtras as $extra) {
            $itemCalories += $extra->extra_calories;
            $itemProteins += $extra->extra_proteins;
            $itemCarbs    += $extra->extra_carbs;
            $itemFats     += $extra->extra_fats;
        }

        $orderItem->computed_calories = $itemCalories;
        $orderItem->computed_proteins = $itemProteins;
        $orderItem->computed_carbs    = $itemCarbs;
        $orderItem->computed_fats     = $itemFats;
    }

    session()->forget('cart');
    session()->forget('resume_order');

    return redirect()->route('myorders')->with('message', 'Your order has been placed successfully!');
}

public function Reorder(Order $order)
{
    $cart = [];

    foreach ($order->orderItems as $orderItem) {
        $cart[$orderItem->item_id] = [
            'id' => $orderItem->item_id,
            'qty' => $orderItem->quantity,
            'name' => $orderItem->meal_name,
            'price' => $orderItem->unit_price,
            'calories' => $orderItem->subtotal_calories,
            'proteins' => $orderItem->subtotal_proteins,
            'carbs' => $orderItem->subtotal_carbs,
            'fats' => $orderItem->subtotal_fats,
            'image' => $orderItem->item->image ?? null,
            'extras' => $orderItem->orderItemExtras->pluck('extra_id')->toArray(),
        ];
    }

    session()->put('cart', $cart);

    return redirect()->route('cart')->with('message', 'Order has been added back to your cart!');
}
}