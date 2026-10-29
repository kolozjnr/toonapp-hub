<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />


        <main class="auth-view">
<section class="auth-page"><div class="panel">
  <aside class="auth-side" aria-hidden="true">
    <span class="ring r1"></span><span class="ring r2"></span><span class="spark">✦</span>
    <div class="brand"><img class="auth-logo" src="assets/img/logo.png" alt="" width="44" height="44"><span>ToonApp Hub</span></div>
    <h3>Good to see you again!</h3>
    <ul><li><span class="ck"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7"/></svg></span>Your plugins are waiting in your dashboard</li><li><span class="ck"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7"/></svg></span>Pick up new updates and downloads</li><li><span class="ck"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7"/></svg></span>Check out faster with your saved details</li></ul>
  </aside>
  <div class="auth-main">
    <div class="auth-top"><div><h2 id="authTitle">Welcome back</h2><p class="note" id="authNote">Log in with your email and password.</p></div><a class="x" href="/" aria-label="Back to the shop"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5l-7 7 7 7"/></svg></a></div>
    <form class="mbody form" id="loginForm" novalidate  method="POST" action="{{ route('login') }}">
        @csrf
      <div class="field"><label for="lEmail">Email</label><input id="lEmail" type="email" autocomplete="email" placeholder="you@example.com" name="email" value="{{ old('email') }}" required autofocus><span class="ferr" data-for="lEmail"></span>
          <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>
      <div class="field"><div class="lab-row"><label for="lPass">Password</label><a class="link" id="forgotLink" href="{{ route('password.request') }}">Forgot password?</a></div><div class="pw"><input id="lPass" type="password" autocomplete="current-password"
                            name="password"
                            required autocomplete="current-password"><button type="button" data-peek="lPass">Show</button></div><span class="ferr" data-for="lPass"></span>
                              <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
      <span class="err" id="loginErr" hidden></span>
      <button class="btn pink" id="loginBtn">Log in</button>
      <span class="note" style="text-align:center">New to ToonApp Hub? <a class="link" id="toRegister" href="{{ route('register') }}">Create an account</a></span>
    </form>
  </div>
</div></section>
</main>

        <!-- Email Address -->
        {{-- <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div> --}}

        <!-- Password -->
        {{-- <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div> --}}

        <!-- Remember Me -->
        {{-- <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form> --}}
</x-guest-layout>
