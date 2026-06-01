<x-layout>
<h1> Kitchen Home</h1>
<p> Welcome, {{ $user->name }}! You have kitchen access.</p>

<ul>
<li><a href="{{ route('kitchen.orders') }}">Manage Incoming Orders</a></li>
<li><a href="{{ route('kitchen.previousOrders') }}">Manage Previous Orders</a></li>
</ul>

<form action="{{ route('logout') }}" method="get">
        
        <button type="submit">Logout</button>
        
    </form>

</x-layout>