<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemExtra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function RegisterPage()
    {
        return view('auth.register');
    }

    public function Register(Request $request)
    {
        $fields = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique' => 'The email address has already been taken.',
            'phone.unique' => 'The phone number has already been taken.',
        ]);

        $user = User::create($fields);
        Auth::login($user);

        return redirect()->route('index');
    }

    public function LoginPage()
    {
        return view('auth.login');
    }

    public function Login(Request $request)
    {
        $fields = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($fields)) {
            return redirect()->route('index');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function Logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('index');
    }

    public function Profile()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!empty($user->phone) && !empty($user->address)) {
            return view('auth.profile-info', compact('user'));
        }

        return view('auth.profile', compact('user'));
    }

   public function UpdateProfile(Request $request)
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    $fields = $request->validate([
        'name'    => 'required|string|max:255',
        'email'   => 'required|email|max:255|unique:users,email,' . $user->id,
        'phone'   => [
            'required',
            'regex:/^\+961 \d{2} \d{2} \d{2} \d{2}$/',
            'unique:users,phone,' . $user->id,
        ],
        'address' => 'required|string|max:255',
    ], [
        'email.unique' => 'The email address has already been taken.',
        'phone.unique' => 'The phone number has already been taken.',
        'phone.regex'  => 'The phone number format must be like +961 XX XX XX XX.',
    ]);

    if ($user->update($fields)) {
        // If there was a pending order intent, resume it immediately
        if (session()->pull('resume_order', false)) {
            return app(\App\Http\Controllers\CartController::class)->Order($request);
        }

        return redirect()->route('profile.info')->with('success', 'Profile updated successfully!');
    }

    return back()->withErrors([
        'update' => 'Failed to update profile. Please try again.',
    ])->withInput();
}

    public function ProfileInfo()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return view('auth.profile-info', compact('user'));
    }

  public function MyOrders()
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    // Load orders with items and extras
    $orders = $user->orders()
        ->with(['orderItems.orderItemExtras'])
        ->orderBy('created_at', 'desc')
        ->get();

    // Transform into a clean, view-friendly structure
    $ordersData = $orders->map(function ($order) {
        $itemsData = $order->orderItems->map(function ($item) {
            $qty = $item->quantity ?? 1;
            $name = $item->meal_name ?? $item->name ?? '';

            // Build a clean array of extras (strings) for the view
            $extrasList = [];
            $extrasRelation = $item->orderItemExtras ?? collect();
            foreach ($extrasRelation as $extra) {
                $label = $extra->extra_name ?? $extra->name ?? '';
                if (!empty($extra->weight)) {
                    $label .= ' (' . $extra->weight . ')';
                }
                $label = trim($label);
                if ($label !== '') {
                    $extrasList[] = $label;
                }
            }

            return [
                'qty' => $qty,
                'name' => $name,
                'extras' => $extrasList,
            ];
        });

        return [
            'id' => $order->id,
            'status' => $order->status,
            'total_price' => $order->total_price,
            'created_at' => $order->created_at,
            'items' => $itemsData->toArray(),
        ];
    })->toArray();

    return view('auth.myorders', ['ordersData' => $ordersData]);
}
}