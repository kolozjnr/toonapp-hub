<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<title>Log in · ToonApp Hub</title>
<meta name="description" content="Your ToonApp Hub account.">
<link rel="icon" type="image/png" href="assets/img/logo.png">
<link rel="apple-touch-icon" href="assets/img/logo.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Nunito:wght@500;700;800;900&display=swap">
<link rel="stylesheet" href="assets/css/shop.css">
</head>
<body data-root="" data-page="login">
<main class="auth-view">
<section class="auth-page"><div class="panel">
  <aside class="auth-side" aria-hidden="true">
    <span class="ring r1"></span><span class="ring r2"></span><span class="spark">✦</span>
    <div class="brand"><img class="auth-logo" src="assets/img/logo.png" alt="" width="44" height="44"><span>ToonApp Hub</span></div>
    <h3>Good to see you again!</h3>
    <ul><li><span class="ck"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7"/></svg></span>Your plugins are waiting in your dashboard</li><li><span class="ck"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7"/></svg></span>Pick up new updates and downloads</li><li><span class="ck"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7"/></svg></span>Check out faster with your saved details</li></ul>
  </aside>
  <div class="auth-main">
    <div class="auth-top"><div><h2 id="authTitle">Welcome back</h2><p class="note" id="authNote">Log in with your email and password.</p></div><a class="x" href="index.html" aria-label="Back to the shop"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5l-7 7 7 7"/></svg></a></div>
    <form class="mbody form" id="loginForm" novalidate>
      <div class="field"><label for="lEmail">Email</label><input id="lEmail" type="email" autocomplete="email" placeholder="you@example.com"><span class="ferr" data-for="lEmail"></span></div>
      <div class="field"><div class="lab-row"><label for="lPass">Password</label><a class="link" id="forgotLink" href="forgot-password.html">Forgot password?</a></div><div class="pw"><input id="lPass" type="password" autocomplete="current-password"><button type="button" data-peek="lPass">Show</button></div><span class="ferr" data-for="lPass"></span></div>
      <span class="err" id="loginErr" hidden></span>
      <button class="btn pink" id="loginBtn">Log in</button>
      <span class="note" style="text-align:center">New to ToonApp Hub? <a class="link" id="toRegister" href="register.html">Create an account</a></span>
    </form>
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
