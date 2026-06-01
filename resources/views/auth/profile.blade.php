<x-layout>
  <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">

  <main class="mk-profile">
    <section class="mk-card">
      <div class="mk-header">
        <h1>Complete Your Profile</h1>

        <form action="{{ route('logout') }}" method="get" class="logout-form">
          <button type="submit" class="logout-link">Logout</button>
        </form>
      </div>

      <p class="sub">We need a few more details before you can place orders.</p>

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
          <textarea id="address" name="address" placeholder="Street, City, Area" required>{{ old('address', $user->address) }}</textarea>
        </div>

        <button class="mk-btn" type="submit">Save</button>

        @if ($errors->any())
          <div class="mt-3 alert-error">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
      </form>
    </section>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const phoneInput = document.getElementById('phone');
      if (!phoneInput) return;

      phoneInput.addEventListener('input', function (e) {
        let digits = e.target.value.replace(/\D/g, '');

        if (!digits.startsWith('961')) {
          digits = '961' + digits.replace(/^961/, '');
        }

        let localDigits = digits.slice(3);

        let formatted = '+961 ';
        for (let i = 0; i < localDigits.length && i < 8; i++) {
          if (i > 0 && i % 2 === 0) {
            formatted += ' ';
          }
          formatted += localDigits[i];
        }

        e.target.value = formatted;
      });
    });
  </script>
</x-layout>