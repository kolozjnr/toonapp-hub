<x-guest-layout>
{{-- <body data-root="" data-page="home"> --}}
<main class="wrap">
  <section class="hero">
    <div>
      <h1>Plugins that make your <span class="hl">animation</span> and <span class="hl">video editing</span> <span class="hl2">pop!</span></h1>
      <p>Premium tools for animators and video editors. Discover professionally crafted effects, scripts, and presets designed to streamline your workflow. Learn with step-by-step tutorials, pay once, and download instantly. 😊</p>
      <div class="cta"><a class="btn" href="#shop">Browse plugins</a><a class="btn white" href="#how">How it works</a></div>
      <div class="apps" id="heroApps"></div>
    </div>
    <div class="mascot-box" id="heroMascot" aria-hidden="true"></div>
  </section>

  <section id="shop">
    <div class="shop-head"><h2>The plugin shelf</h2><span class="note" id="countNote"></span></div>
    <div class="tools">
      <div style="display:flex;gap:8px;flex-wrap:wrap" id="cats" role="group" aria-label="Category"></div>
      <label class="search"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg><input id="q" type="search" placeholder="Search plugins" aria-label="Search plugins"></label>
      <select class="sort" id="sort" aria-label="Sort"><option value="new">Newest</option><option value="top">Top rated</option><option value="low">Price: low to high</option><option value="high">Price: high to low</option></select>
    </div>
    <div class="grid" id="grid"></div>
  </section>

  <section class="how" id="how">
    <div class="step"><div class="n" style="background:var(--yellow)">1</div><h3>Watch the tutorial</h3><p>Every plugin has a video walkthrough on YouTube so you know what you're getting.</p></div>
    <div class="step"><div class="n" style="background:var(--pink)">2</div><h3>Create an account and pay</h3><p>Sign up once, then pay by card. No subscription.</p></div>
    <div class="step"><div class="n" style="background:var(--mint)">3</div><h3>Download and rate</h3><p>Your download link arrives by email and stays in your dashboard. Leave a rating to help other animators.</p></div>
  </section>

  <section class="faq" id="faq">
    <h2 style="font-size:40px;margin-bottom:6px">Questions</h2>
    <details><summary>Which apps do the plugins work with?</summary><p>Each plugin page lists its host apps and the versions it supports.</p></details>
    <details><summary>Do I get updates?</summary><p>Yes. Updates within the same major version are free and appear in your dashboard and inbox.</p></details>
    <details><summary>Can I use them on client work?</summary><p>Yes. Every license covers personal and commercial projects for one person.</p></details>
    <details><summary>What if it doesn't work for me?</summary><p>Send us a message from the Contact page and we'll help you get it working.</p></details>
  </section>
</main>

</x-guest-layout>

