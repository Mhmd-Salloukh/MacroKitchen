<x-layout>
  <link rel="stylesheet" href="{{ asset('assets/css/registerpage.css') }}">

  <main class="mk-register" aria-labelledby="loginTitle">
    <section class="mk-card" aria-hidden="false">
      <div class="mk-intro">
        <h1 id="loginTitle" class="section-heading">Sign in</h1>
        <p class="sub">Sign in to access personalized meal plans and tracking.</p>
      </div>

      <form class="mk-form" action="{{ route('login.action') }}" method="post" novalidate>
        @csrf

        <div class="field">
          <label for="email">Email address</label>
          <input id="email" name="email" type="email" placeholder="you@example.com" required value="{{ old('email') }}">
          @error('email')
            <div class="field-error">{{ $message }}</div>
          @enderror
        </div>

        <div class="field">
          <label for="password">Password</label>
          <input id="password" name="password" type="password" placeholder="Enter your password" required>
          @error('password')
            <div class="field-error">{{ $message }}</div>
          @enderror
        </div>

        <button class="mk-btn btn-cta" type="submit">Sign in</button>
      </form>

      <div class="mk-footer">
        <span class="muted">Don't have an account?</span>
        <a class="signin" href="{{ route('register') }}">Sign up</a>
      </div>
    </section>
  </main>
</x-layout>