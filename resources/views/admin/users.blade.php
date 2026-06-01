<x-layout>
<h1>All Users</h1>
@foreach ($users as $user)
    <p>{{ $user->name }} - {{ $user->role }}</p>

    
@endforeach
<h1> Manage Users</h1>
@if (session('success'))
    <p style="color: green">{{ session('success') }}</p>
@endif
<ul>
@foreach ($users as $user)
    <li>{{ $user->name }}
        <form action="{{route('admin.UpdateRole', $user->id)}}" method="POST" style="display: inline;">
            @csrf
            
            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"> 
                Change Role
            </button>
            <ul class="dropdown-menu">
                @if ($user->role == 'user')
                    <button class="dropdown-item" type="submit" name="role" value="admin">Make Admin</button>
                    <button class="dropdown-item" type="submit" name="role" value="kitchen">Make Kitchen</button>
                    @elseif ($user->role == 'admin')
                    <button class="dropdown-item" type="submit" name="role" value="user">Make User</button>
                    <button class="dropdown-item" type="submit" name="role" value="kitchen">Make Kitchen</button>
                    @elseif ($user->role == 'kitchen')
                    <button class="dropdown-item" type="submit" name="role" value="user">Make User</button>
                    <button class="dropdown-item" type="submit" name="role" value="admin">Make Admin</button>
                @endif
            </ul>
        </form>
        <form action="{{route('admin.userDelete', $user->id)}}" method="POST" style="display: inline;">
            @csrf
            <button class="btn btn-danger" type="submit">Delete</button>
        </form>
    </li>
@endforeach
</ul>

<h2>Deleted Users</h2>
<ul>
@foreach ($deletedUsers as $user)
    <li>{{ $user->name }} (Deleted at: {{ $user->deleted_at }})</li>
    <form action="{{route('admin.userRestore', $user->id)}}" method="POST" style="display: inline;">
        @csrf
        <button class="btn btn-success" type="submit">Restore</button>
    </form>
    <form action="{{route('admin.userPDelete', $user->id)}}" method="POST" style="display: inline;">
        @csrf
        <button class="btn btn-danger" type="submit">Permanently Delete</button>
    </form>
@endforeach
</ul>


</x-layout>