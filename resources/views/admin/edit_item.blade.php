<x-layout>
    <h1> Edit {{ $item->name }}</h1>
   
    <form action="{{ route('admin.itemUpdate', $item->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="text" name="name" value="{{ old('name', $item->name) }}" required>
        <input type="number" name="base_price" value="{{ old('base_price', $item->base_price) }}" required>
        <input type="number" name="calories" value="{{ old('calories', $item->calories) }}" required>
        <input type="number" name="proteins" value="{{ old('proteins', $item->proteins) }}" required>
        <input type="number" name="carbs" value="{{ old('carbs', $item->carbs) }}" required>
        <input type="number" name="fats" value="{{ old('fats', $item->fats) }}" required>
        <textarea name="description">{{ old('description', $item->description) }}</textarea>
        <input type="file" name="image">
        <button type="submit">Update Item</button>
    </form>

<hr>



    @if (session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    @error('general')
        <p style="color: red">{{ $message }}</p>
    @enderror

    @if (session('error'))
        <p style="color: red">{{ session('error') }}</p>
    @endif


    <hr>

 <h2>Add Category</h2>

    <form action="{{ route('admin.itemAddCategory', $item->id) }}" method="POST">
        @csrf
        <select name="category_id" required>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <button type="submit">Add Category</button>
    </form>

    <h3>Current Categories:</h3>
    <ul>
        @foreach ($item->categories as $category)
            <li>{{ $category->name }}
                <form action="{{ route('admin.itemRemoveCategory', [$item->id, $category->id]) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Remove</button>
                </form>
            </li>
        @endforeach
    </ul>
    
     <h2>Add Extras</h2>

    <form action="{{ route('admin.itemAddExtra', $item->id) }}" method="POST">
        @csrf
        <select name="extra_id" required>
            @foreach ($extras as $extra)
                <option value="{{ $extra->id }}">{{ $extra->name }}</option>
            @endforeach
        </select>
        <button type="submit">Add Extra</button>
    </form>

    <h3>Current Extras:</h3>
    <ul>
        @foreach ($item->extras as $extra)
            <li>{{ $extra->name }}
                <form action="{{ route('admin.itemRemoveExtra', [$item->id, $extra->id]) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Remove</button>
                </form>
            </li>
        @endforeach
    </ul>

</x-layout>