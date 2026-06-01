<x-layout>
    <h1>Order #{{ $order->id }}</h1>

    <div class="card mb-3 p-3">
        <p><strong>Customer:</strong> {{ $order->user->name }}</p>
        <p><strong>Total Price:</strong> ${{ number_format($order->total_price, 2) }}</p>
        <p>
            <strong>Status:</strong> 
            <span class="badge 
                @if($order->status === 'pending') bg-warning 
                @elseif($order->status === 'ready') bg-success 
                @elseif($order->status === 'delivered') bg-primary
                @endif">
                {{ ucfirst($order->status) }}
            </span>

            @if($order->status === 'pending')
                <form action="{{ route('kitchen.updateStatus', ['id' => $order->id, 'status' => 'ready']) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success ms-2">Mark Ready</button>
                </form>
            @endif

            @if(in_array($order->status, ['pending','ready']))
                <form action="{{ route('kitchen.updateStatus', ['id' => $order->id, 'status' => 'delivered']) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-primary ms-2">Mark Delivered</button>
                </form>
            @endif
        </p>

        <p><strong>Placed at:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>

        @if($order->delivered_at)
            <p><strong>Delivered at:</strong> {{ $order->delivered_at->format('d M Y, H:i') }}</p>
        @endif


        <p><strong>Total Macros (whole order):</strong></p>
        <ul>
            <li>Protein: {{ $order->total_proteins ?? 0 }} g</li>
            <li>Carbs: {{ $order->total_carbs ?? 0 }} g</li>
            <li>Fat: {{ $order->total_fats ?? 0 }} g</li>
            <li>Calories: {{ $order->total_calories ?? 0 }} kcal</li>
        </ul>
    </div>

    <h3>Items</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Meal</th>
                <th>Quantity</th>
                <th>Extras</th>
                <th>Customer Note</th> <!-- ✅ Added here -->
                <th>Total Price</th>
                <th>Unit Macros (incl. extras)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->orderItems as $item)
                <tr>
                    <td>{{ $item->meal_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>
                        @if($item->orderItemExtras->count())
                            <ul>
                                @foreach($item->orderItemExtras as $extra)
                                    <li>{{ $extra->extra_name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <em>No extras</em>
                        @endif
                    </td>
                    <td>
                        @if($order->note)
                            {{ $order->note }}
                        @else
                            <em>No note</em>
                        @endif
                    </td>
                    <td>${{ number_format($order->total_price, 2) }}</td>
                    <td>
                        <ul>
                            <li>Calories: {{ $item->unit_calories ?? 0 }} kcal</li>
                            <li>Protein: {{ $item->unit_proteins ?? 0 }} g</li>
                            <li>Carbs: {{ $item->unit_carbs ?? 0 }} g</li>
                            <li>Fat: {{ $item->unit_fats ?? 0 }} g</li>
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('kitchen.home') }}" class="btn btn-secondary mt-3">Back to Kitchen Home</a>
   <form action="{{ route('kitchen.deleteOrder', $order->id) }}" method="POST" class="d-inline-block mt-3" onsubmit="return confirm('Are you sure you want to delete this order? This action cannot be undone.')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete Order</button>
    </form>
</x-layout>