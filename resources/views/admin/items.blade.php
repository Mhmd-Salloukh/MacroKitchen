<x-layout>
    <h1> Manage Items</h1>

    <ul>
        @foreach ($items as $item)
            <li>{{ $item->name }} <a href="{{ route('admin.itemEdit', $item->id) }}">Edit</a> <a href="{{ route('admin.itemDelete', $item->id) }}">Delete</a></li>
            
        @endforeach
       
    </ul>

<form action="{{route('admin.itemCreate')}}" method="POST" enctype="multipart/form-data">
    @csrf

    <input type="text" name="name" placeholder="Item Name" required>
    <input type="number" name="base_price" placeholder="base_price" required>
    <input type="number" name="calories" placeholder="Calories" required>
     <input type="number" name="carbs" placeholder="Carbs" required>
      <input type="number" name="proteins" placeholder="Protein" required>
       <input type="number" name="fats" placeholder="Fat" required>
       <input type="text" name="description" placeholder="Description" required>
       <input type="file" name="image" required>

    <button type="submit">Create Item</button>

</form>



     <h2>Deleted Items</h2>
     
<ul>
 @foreach ($deletedItems as $item)
    <li>{{ $item->name }} (Deleted at: {{ $item->deleted_at }})</li>
    <form action="{{route('admin.itemRestore', $item->id)}}" method="POST" style="display: inline;">
        @csrf
        <button class="btn btn-success" type="submit">Restore</button>
    </form>
    <form action="{{route('admin.itemPDelete', $item->id)}}" method="POST" style="display: inline;">
        @csrf
        <button class="btn btn-danger" type="submit">Permanently Delete</button>
    </form>
@endforeach


</ul>



@if (session('success'))
        <p style="color: green">{{ session('success') }}</p>
        
    @endif
    
     @error('general')
         <p style="color: red">{{ $message }}</p>
     @enderror



</x-layout>