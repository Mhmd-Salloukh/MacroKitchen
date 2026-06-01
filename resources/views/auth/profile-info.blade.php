<x-layout>
  <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">

  <main class="mk-profile-info">
    <section class="mk-card">
      @if(session('success'))
        <div class="alert-success">
          {{ session('success') }}
        </div>
      @endif

      <div class="mk-avatar" aria-hidden="true">
        {{ strtoupper(substr($user->name, 0, 1)) }}
      </div>

      @if(!request()->has('edit'))
        <a href="{{ route('profile.info', ['edit' => true]) }}" class="edit-link" aria-label="Edit profile">
          <svg class="edit-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" aria-hidden="true">
            <path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 
            26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 
            16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 
            85-28-29 57 57-29-28Z"/>
          </svg>
        </a>
      @endif

      <h2 class="mk-name">{{ $user->name }}</h2>

      @if(!request()->has('edit'))
        <div class="profile-details" role="region" aria-label="Profile details">
          <p><strong>Email:</strong> {{ $user->email }}</p>
          <p><strong>Phone Number:</strong> {{ $user->phone }}</p>
          <p><strong>Address:</strong> {{ $user->address }}</p>
        </div>

        <div class="mk-actions">
          <a href="{{ route('myorders') }}" class="orders-btn mk-btn">My Orders</a>
        </div>

        <form action="{{ route('logout') }}" method="get" class="logout-form">
          <button type="submit" class="logout-link">Logout</button>
        </form>
      @else
        <form class="mk-form" action="{{ route('profile.update') }}" method="post" novalidate>
          @csrf

          <div class="field">
            <label for="name">Full name</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required>
          </div>

          <div class="field">
            <label for="email">Email address</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>
          </div>

          <div class="field">
            <label for="phone">Phone number</label>
            <input id="phone" name="phone" type="tel"
                   placeholder="+961 78 12 34 56"
                   value="{{ old('phone', $user->phone) }}"
                   required
                   pattern="\+961 \d{2} \d{2} \d{2} \d{2}">
          </div>

          <div class="field">
            <label for="address">Address</label>
            <textarea id="address" name="address" required>{{ old('address', $user->address) }}</textarea>
          </div>

          <button class="mk-btn-save" type="submit">Save</button>

          @if ($errors->any())
            <div class="alert-error">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
        </form>
      @endif
    </section>
  </main>
</x-layout>