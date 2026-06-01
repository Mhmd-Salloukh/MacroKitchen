<x-layout>
    <h1> Manage Extras</h1>
    
    <ul>
        @foreach ($extras as $extra)
            <li>{{ $extra->name }} <a href="{{ route('admin.extraEdit', $extra->id) }}">Edit</a>
            <form action="{{ route('admin.extraDelete', $extra->id) }}" method="POST" style="display: inline;"  >
                @csrf
                @method('DELETE')
                <button  class="btn btn-danger" type="submit">Delete</button>
            </form></li>
        @endforeach
        
    </ul>

    <form action="{{ route('admin.extraCreate') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="New Extra Name" required>
        <input type="decimal" name="price" placeholder="Price" required>
        <input type="number" name="calories" placeholder="Calories" >
        <input type="number" name="proteins" placeholder="Proteins (g)" >
        <input type="number" name="carbs" placeholder="Carbs (g)">
        <input type="number" name="fats" placeholder="Fats (g)">
        
        <button type="submit">Add Extra</button>
    </form>




    @if (session('success'))
        <p style="color: green">{{ session('success') }}</p>
        
    @endif

</x-layout>
