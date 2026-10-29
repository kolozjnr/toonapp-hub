<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<title>Create account · ToonApp Hub</title>
<meta name="description" content="Your ToonApp Hub account.">
<link rel="icon" type="image/png" href="assets/img/logo.png">
<link rel="apple-touch-icon" href="assets/img/logo.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Nunito:wght@500;700;800;900&display=swap">
<link rel="stylesheet" href="assets/css/shop.css">
</head>
<body data-root="" data-page="register">
<main class="auth-view">
<section class="auth-page"><div class="panel">
  <aside class="auth-side" aria-hidden="true">
    <span class="ring r1"></span><span class="ring r2"></span><span class="spark">✦</span>
    <div class="brand"><img class="auth-logo" src="assets/img/logo.png" alt="" width="44" height="44"><span>ToonApp Hub</span></div>
    <h3>Join the creator club</h3>
    <ul><li><span class="ck"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7"/></svg></span>Instant downloads of everything you buy</li><li><span class="ck"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7"/></svg></span>All your plugins saved in one place</li><li><span class="ck"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7"/></svg></span>Rate plugins and get updates first</li></ul>
  </aside>
  <div class="auth-main">
    <div class="auth-top"><div><h2 id="authTitle">Create your account</h2><p class="note" id="authNote">It takes less than a minute.</p></div><a class="x" href="index.html" aria-label="Back to the shop"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5l-7 7 7 7"/></svg></a></div>
    <form class="mbody form" id="signupForm" novalidate>
      <div class="two">
        <div class="field"><label for="rFirst">First name</label><input id="rFirst" autocomplete="given-name" maxlength="40"><span class="ferr" data-for="rFirst"></span></div>
        <div class="field"><label for="rLast">Last name</label><input id="rLast" autocomplete="family-name" maxlength="40"><span class="ferr" data-for="rLast"></span></div>
      </div>
      <div class="field"><label for="rEmail">Email</label><input id="rEmail" type="email" autocomplete="email" maxlength="120" placeholder="you@example.com"><span class="ferr" data-for="rEmail"></span></div>
      <div class="field"><label for="rPass">Password</label><div class="pw"><input id="rPass" type="password" autocomplete="new-password" placeholder="At least 8 characters, with a number"><button type="button" data-peek="rPass">Show</button></div><span class="ferr" data-for="rPass"></span></div>
      <div class="field"><label for="rPass2">Confirm password</label><div class="pw"><input id="rPass2" type="password" autocomplete="new-password"><button type="button" data-peek="rPass2">Show</button></div><span class="ferr" data-for="rPass2"></span></div>
      <div class="field"><label for="rCountry">Country</label><select id="rCountry"></select><span class="ferr" data-for="rCountry"></span></div>
      <label class="optin" for="rNews"><input type="checkbox" id="rNews" checked><span><b>Join the ToonApp Hub newsletter</b><small>Get an email when your plugins update and when new plugins drop. Unsubscribe any time in your dashboard.</small></span></label>
      <span class="err" id="signupErr" hidden></span>
      <button class="btn pink" id="signupBtn">Create account</button>
      <span class="note" style="text-align:center">Already have an account? <a class="link" id="toLogin" href="login.html">Log in</a></span>
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
<script src="assets/js/config.js"></script>
<script src="assets/js/core.js"></script>
<script src="assets/js/public.js"></script>
<script src="assets/js/toony.js"></script>
<script src="assets/js/pages/auth.js"></script>
</body>
</html>
