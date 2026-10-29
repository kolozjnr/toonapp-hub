<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<title>Reset password · ToonApp Hub</title>
<meta name="description" content="Your ToonApp Hub account.">
<link rel="icon" type="image/png" href="assets/img/logo.png">
<link rel="apple-touch-icon" href="assets/img/logo.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Nunito:wght@500;700;800;900&display=swap">
<link rel="stylesheet" href="assets/css/shop.css">
</head>
<body data-root="" data-page="reset">
<main class="auth-view">
<section class="auth-page"><div class="panel">
  <aside class="auth-side" aria-hidden="true">
    <span class="ring r1"></span><span class="ring r2"></span><span class="spark">✦</span>
    <div class="brand"><img class="auth-logo" src="assets/img/logo.png" alt="" width="44" height="44"><span>ToonApp Hub</span></div>
    <h3>Almost there!</h3>
    <ul><li><span class="ck"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7"/></svg></span>Enter the code from your email</li><li><span class="ck"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7"/></svg></span>Choose a new password</li><li><span class="ck"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7"/></svg></span>You'll be logged straight in</li></ul>
  </aside>
  <div class="auth-main">
    <div class="auth-top"><div><h2 id="authTitle">Reset your password</h2><p class="note" id="authNote">Enter the code and choose a new password.</p></div><a class="x" href="index.html" aria-label="Back to the shop"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5l-7 7 7 7"/></svg></a></div>
    <form class="mbody form" id="resetForm" novalidate>
      <p class="verify-text" id="rsSentTo"></p>
      <div class="demo-code" id="rsDemo" hidden></div>
      <div class="field"><label for="rsCode">6-digit code</label><input id="rsCode" class="code-in" inputmode="numeric" maxlength="6" autocomplete="one-time-code" placeholder="••••••"><span class="ferr" data-for="rsCode"></span></div>
      <div class="field"><label for="rsPass">New password</label><div class="pw"><input id="rsPass" type="password" autocomplete="new-password" placeholder="At least 8 characters, with a number"><button type="button" data-peek="rsPass">Show</button></div><span class="ferr" data-for="rsPass"></span></div>
      <div class="field"><label for="rsPass2">Confirm new password</label><div class="pw"><input id="rsPass2" type="password" autocomplete="new-password"><button type="button" data-peek="rsPass2">Show</button></div><span class="ferr" data-for="rsPass2"></span></div>
      <span class="err" id="rsErr" hidden></span>
      <button class="btn pink" id="rsBtn">Save new password</button>
      <div class="code-links"><button type="button" class="link" id="rsResend">Resend code</button><a class="link" href="forgot-password.html">Use a different email</a></div>
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
