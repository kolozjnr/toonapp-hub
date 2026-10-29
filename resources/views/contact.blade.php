<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<title>Contact · ToonApp Hub</title>
<meta name="description" content="Get in touch with ToonApp Hub.">
<link rel="icon" type="image/png" href="assets/img/logo.png">
<link rel="apple-touch-icon" href="assets/img/logo.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Nunito:wght@500;700;800;900&display=swap">
<link rel="stylesheet" href="assets/css/shop.css">
</head>
<body data-root="" data-page="contact">
<main class="wrap">
  <div class="contact-head">
    <a class="btn sm white" href="index.html"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5l-7 7 7 7"/></svg>Back to shop</a>
    <h1>Contact us</h1>
    <p>Questions about a plugin, a license or a download? Send a message and we'll reply by email, usually within a day.</p>
  </div>
  <section class="contact" id="contact">
    <div class="contact-info">
      <h2 style="font-size:28px">Reach us directly</h2>
      <div class="ccards" id="contactCards"></div>
    </div>
    <form class="panel2 form" id="contactForm" novalidate>
      <div class="two">
        <div class="field"><label for="cName">Name</label><input id="cName" autocomplete="name" maxlength="80"></div>
        <div class="field"><label for="cEmail">Email</label><input id="cEmail" type="email" autocomplete="email" maxlength="120" placeholder="you@example.com"></div>
      </div>
      <div class="field"><label for="cTopic">Topic</label><select id="cTopic"><option>Question about a plugin</option><option>Help with a purchase or download</option><option>Feature request</option><option>Something else</option></select></div>
      <div class="field"><label for="cMsg">Message</label><textarea id="cMsg" maxlength="2000" style="min-height:140px" placeholder="How can we help?"></textarea></div>
      <span class="err" id="cErr" hidden></span>
      <button class="btn pink" id="cSend">Send message</button>
    </form>
  </section>
</main>
<script src="assets/js/config.js"></script>
<script src="assets/js/core.js"></script>
<script src="assets/js/public.js"></script>
<script src="assets/js/toony.js"></script>
<script src="assets/js/pages/contact.js"></script>
</body>
</html>
