<x-layout>
    <h1>Kitchen Orders</h1>
    <p>Here you can see all incoming orders.</p>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Date & Time</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>
                        <span class="badge 
                            @if($order->status === 'ready') bg-success 
                            @elseif($order->status === 'pending') bg-warning
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                    <td>
                        <a href="{{ route('kitchen.order', $order->id) }}" class="btn btn-sm btn-primary">
                            View
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No orders yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-layout>