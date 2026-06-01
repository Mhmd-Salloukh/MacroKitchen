<x-layout>
    <h1> Manage Categories</h1>
    
    <ul>
        @foreach ($categories as $category)
            <li>{{ $category->name }} <a href="{{ route('admin.CatEdit', $category->id) }}">Edit</a></li>
        @endforeach
        
    </ul>

    <form action="{{ route('admin.CatCreate') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="New Category Name" required>
        <button type="submit">Add Category</button>
    </form>




    @if (session('success'))
        <p style="color: green">{{ session('success') }}</p>
        
    @endif

</x-layout>
