<x-layout>
    <h1>Previous Orders</h1>

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    @if($orders->isEmpty())
        <p>No previous orders found.</p>
    @else
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Total Price</th>
                    <th>Placed At</th>
                    <th>Delivered At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td>
                            <span class="badge bg-primary">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td>${{ number_format($order->total_price, 2) }}</td>
                        <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            @if($order->delivered_at)
                                {{ $order->delivered_at->format('d M Y, H:i') }}
                            @else
                                <em>Not recorded</em>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('kitchen.order', $order->id) }}" class="btn btn-sm btn-info">
                                View Details
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('kitchen.home') }}" class="btn btn-secondary mt-3">Back to Kitchen Home</a>
</x-layout>