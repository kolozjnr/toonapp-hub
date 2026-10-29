<x-guest-layout>


<main class="auth-view">
<section class="auth-page"><div class="panel">
  <aside class="auth-side" aria-hidden="true">
    <span class="ring r1"></span><span class="ring r2"></span><span class="spark">✦</span>
    <div class="brand"><img class="auth-logo" src="assets/img/logo.png" alt="" width="44" height="44"><span>ToonApp Hub</span></div>
    <h3>Join the creator club</h3>
    <ul><li><span class="ck"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7"/></svg></span>Instant downloads of everything you buy</li><li><span class="ck"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7"/></svg></span>All your plugins saved in one place</li><li><span class="ck"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7"/></svg></span>Rate plugins and get updates first</li></ul>
  </aside>
  <div class="auth-main">
    <div class="auth-top"><div><h2 id="authTitle">Create your account</h2><p class="note" id="authNote">It takes less than a minute.</p></div><a class="x" href="/" aria-label="Back to the shop"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5l-7 7 7 7"/></svg></a></div>
    <form class="mbody form" id="signupForm" novalidate method="POST" action="{{ route('register') }}">
        @csrf
      <div class="two">
        <div class="field"><label for="rFirst">First name</label><input id="rFirst" autocomplete="given-name" maxlength="40" name="fname" value="{{ old('fname') }}"><span class="ferr" data-for="rFirst"></span>
             <x-input-error :messages="$errors->get('fname')" class="mt-2" />
        </div>
        <div class="field"><label for="rLast">Last name</label><input id="rLast" autocomplete="family-name" maxlength="40" name="lname" value="{{ old('lname') }}"><span class="ferr" data-for="rLast"></span>
             <x-input-error :messages="$errors->get('lname')" class="mt-2" />
        </div>
      </div>
      <div class="field"><label for="rEmail">Email</label><input id="rEmail" type="email" autocomplete="email" maxlength="120" name="email" placeholder="you@example.com" value="{{ old('email') }}"><span class="ferr" data-for="rEmail"></span>
         <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>
      <div class="field"><label for="rPass">Password</label><div class="pw"><input id="rPass" type="password" name="password" autocomplete="new-password" placeholder="At least 8 characters, with a number"><button type="button" data-peek="rPass">Show</button></div><span class="ferr" data-for="rPass"></span>
       <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>
      <div class="field"><label for="rPass2">Confirm password</label><div class="pw"><input id="rPass2" type="password" name="password_confirmation" autocomplete="new-password"><button type="button" data-peek="rPass2">Show</button></div><span class="ferr" data-for="rPass2"></span>
       <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
    </div>
      <div class="field"><label for="rCountry">Country</label><select name="country" id="rCountry">
          <option value="">Choose your country</option>
          @foreach (config('countries') as $country)
              <option value="{{ $country }}" @selected(old('country') === $country)>{{ $country }}</option>
          @endforeach
        </select><span class="ferr" data-for="rCountry"></span>
         <x-input-error :messages="$errors->get('country')" class="mt-2" />
    </div>
      <label class="optin" for="rNews"><input type="hidden" name="news" value="0"><input type="checkbox" name="news" id="rNews" value="1" @checked(old('news', true))><span><b>Join the ToonApp Hub newsletter</b><small>Get an email when your plugins update and when new plugins drop. Unsubscribe any time in your dashboard.</small></span></label>
      <span class="err" id="signupErr" hidden></span>
      <button class="btn pink" id="signupBtn">Create account</button>
      <span class="note" style="text-align:center">Already have an account? <a class="link" id="toLogin" href="{{ route('login') }}">Log in</a></span>
    </form>
    <div class="mbody form" id="verifyBox" hidden>
      <div class="verify-ic" aria-hidden="true"><svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="m4 7 8 6 8-6"/></svg></div>
      <p class="verify-text" id="vSentTo"></p>
      <div class="demo-code" id="vDemo" hidden></div>
      <div class="field"><label for="vCode">6-digit code</label><input id="vCode" class="code-in" inputmode="numeric" maxlength="6" autocomplete="one-time-code" placeholder="••••••"><span class="ferr" data-for="vCode"></span></div>
      <span class="err" id="vErr" hidden></span>
      <button class="btn pink" id="vBtn" type="button">Confirm email and create account</button>
      <div class="code-links"><button type="button" class="link" id="vResend">Resend code</button><button type="button" class="link" id="vBack">Change email</button></div>
    </div>
  </div>
</div></section>
</main>






    {{-- <form method="POST" action="{{ route('register') }}">
        @csrf --}}

        <!-- Name -->
        {{-- <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div> --}}

        <!-- Email Address -->
        {{-- <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div> --}}

        <!-- Password -->
        {{-- <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div> --}}

        <!-- Confirm Password -->
        {{-- <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form> --}}
</x-guest-layout>
