<x-layout>
<link rel="stylesheet" href="{{ asset('assets/css/registerpage.css') }}">

  <main class="mk-register" aria-labelledby="registerTitle">
    <section class="mk-card" aria-hidden="false">
      <div class="mk-intro">
        <h1 id="registerTitle" class="section-heading">Create your account</h1>
        <p class="sub">Sign up to access personalized meal plans and tracking.</p>
      </div>

      <form class="mk-form" action="{{ route('register.action') }}" method="post" novalidate>
        @csrf

        <div class="field">
          <label for="name">Full name</label>
          <input id="name" name="name" type="text" placeholder="Jane Doe" required value="{{ old('name') }}">
        </div>

        <div class="field">
          <label for="email">Email address</label>
          <input id="email" name="email" type="email" placeholder="you@example.com" required value="{{ old('email') }}">
        </div>

        <div class="row">
          <div class="field half">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" placeholder="Minimum 8 characters" required>
          </div>

          <div class="field half">
            <label for="confirm">Confirm</label>
            <input id="confirm" name="password_confirmation" type="password" placeholder="Repeat password" required>
          </div>
        </div>

        <button class="mk-btn btn-cta" type="submit">Create account</button>

        @if ($errors->any())
          <div class="alert error-alert" role="alert">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
      </form>

      <div class="mk-footer">
        <span class="muted">Already registered?</span>
        <a class="signin" href="{{ route('login') }}">Sign in</a>
      </div>
    </section>
  </main>
</x-layout>