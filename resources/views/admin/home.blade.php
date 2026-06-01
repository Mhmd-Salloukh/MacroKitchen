<x-layout>
<h1> Admin Home</h1>
<p> Welcome, {{ $user->name }}! You have admin access.</p>

<ul>
<li><a href="{{ route('admin.users') }}">Manage Users</a></li>
<li><a href="{{ route('admin.items') }}">Manage Items</a></li>
<li><a href="{{ route('admin.categories') }}">Manage Categories</a></li>
<li><a href="{{ route('admin.extras') }}">Manage Extras</a></li>

</ul>

<form action="{{ route('logout') }}" method="get">
        
        <button type="submit">Logout</button>
        
    </form>

</x-layout>