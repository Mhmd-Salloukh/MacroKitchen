<x-layout>
    <h1> Edit {{ $category->name }}</h1>
   
    <form action="{{ route('admin.CatUpdate', $category->id) }}" method="POST">
        @csrf
        <input type="text" name="name" value="{{ old('name', $category->name) }}" required>
        <button type="submit">Update Category</button>
    </form>

    @if (session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

</x-layout>