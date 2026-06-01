<x-layout>
    <h1> Edit {{ $extra->name }}</h1>
   
    <form action="{{ route('admin.extraUpdate', $extra->id) }}" method="POST">
        @csrf

        <input type="text" name="name" value="{{ old('name', $extra->name) }}" required>
        <input type="decimal" name="price" value="{{ old('price', $extra->price) }}" placeholder="price" required>
        <input type="number" name="calories" value="{{ old('calories', $extra->calories) }}" placeholder="Calories (g)">
        <input type="number" name="proteins" value="{{ old('proteins', $extra->proteins) }}" placeholder="Proteins (g)">
        <input type="number" name="carbs" value="{{ old('carbs', $extra->carbs) }}" placeholder="Carbs (g)">
        <input type="number" name="fats" value="{{ old('fats', $extra->fats) }}" placeholder="Fats (g)">

        <button type="submit">Update Extra</button>
    </form>

            

    @if (session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

  

</x-layout>