(function () {
  'use strict';

  var THEME_KEY = 'ds-theme';
  var SCALE_KEY = 'ds-fontscale';
  var CONFETTI_SRC = 'https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js';
  var SCALE_STEP = 10;   // percent per click
  var SCALE_MIN = 100;   // keeps body text at or above 16px
  var SCALE_MAX = 130;
  var URGENT_DAYS = 30;

  var confettiReady = null;

  function safeGet(key) {
    try { return localStorage.getItem(key); } catch (e) { return null; }
  }

  function safeSet(key, value) {
    try { localStorage.setItem(key, value); } catch (e) { /* storage unavailable */ }
  }

  /* ---------- Dark mode ---------- */

  function applyTheme(dark) {
    document.documentElement.classList.toggle('dark', dark);
    return dark;
  }

  function themeLabel(dark) {
    return dark ? '\u2600\uFE0F' : '\uD83C\uDF19';
  }

  /* ---------- Text size ---------- */

  function applyScale(percent) {
    var p = Math.min(SCALE_MAX, Math.max(SCALE_MIN, percent));
    document.documentElement.style.fontSize = p + '%';
    safeSet(SCALE_KEY, String(p));
    return p;
  }

  /* ---------- Confetti ---------- */

  function loadConfetti() {
    if (window.confetti) return Promise.resolve(window.confetti);
    if (confettiReady) return confettiReady;
    confettiReady = new Promise(function (resolve, reject) {
      var s = document.createElement('script');
      s.src = CONFETTI_SRC;
      s.async = true;
      s.onload = function () { resolve(window.confetti); };
      s.onerror = function () { confettiReady = null; reject(new Error('confetti load failed')); };
      document.head.appendChild(s);
    });
    return confettiReady;
  }

  function fireConfetti() {
    loadConfetti().then(function (confetti) {
      if (typeof confetti === 'function') {
        confetti({ particleCount: 90, spread: 70, origin: { y: 0.7 } });
      }
    }).catch(function () { /* confetti is decorative; ignore failures */ });
  }

  /* ---------- Days-left urgency ---------- */

  var URGENT_SELECTOR = '[class*="days"], [class*="badge"], [class*="left"]';

  function updateUrgency() {
    var nodes = document.querySelectorAll(URGENT_SELECTOR);
    for (var i = 0; i < nodes.length; i++) {
      var node = nodes[i];
      if (node.children.length > 0) continue;
      var text = node.textContent || '';
      if (text.length === 0 || text.length > 60) continue;
      var match = text.match(/\d+/);
      if (!match) continue;
      var urgent = parseInt(match[0], 10) < URGENT_DAYS;
      node.classList.toggle('urgent', urgent);
    }
  }

  var observing = false;
  var observer = new MutationObserver(function () {
    if (observing) return;
    observing = true;
    try { updateUrgency(); } finally { observing = false; }
  });

  /* ---------- Controls in the header ---------- */

  function buildControls() {
    var header = document.querySelector('header');
    if (!header) return;

    var wrap = document.createElement('div');
    wrap.className = 'ds-controls flex items-center gap-2';

    var dark = applyTheme(safeGet(THEME_KEY) === 'dark');
    var toggle = document.createElement('button');
    toggle.type = 'button';
    toggle.className = 'ds-theme-toggle min-h-[44px] min-w-[44px] rounded-full bg-white/20 px-2';
    toggle.setAttribute('aria-label', 'Toggle dark mode');
    toggle.textContent = themeLabel(dark);
    toggle.addEventListener('click', function () {
      var nowDark = applyTheme(!document.documentElement.classList.contains('dark'));
      safeSet(THEME_KEY, nowDark ? 'dark' : 'light');
      toggle.textContent = themeLabel(nowDark);
    });

    var current = parseInt(safeGet(SCALE_KEY), 10);
    var scale = applyScale(isNaN(current) ? SCALE_MIN : current);

    function sizeButton(label, delta, aria) {
      var b = document.createElement('button');
      b.type = 'button';
      b.className = 'ds-size min-h-[44px] min-w-[44px] rounded-full bg-white/20 px-2 font-bold';
      b.setAttribute('aria-label', aria);
      b.textContent = label;
      b.addEventListener('click', function () {
        scale = applyScale(scale + delta);
      });
      return b;
    }

    wrap.appendChild(toggle);
    wrap.appendChild(sizeButton('A-', -SCALE_STEP, 'Decrease text size'));
    wrap.appendChild(sizeButton('A+', SCALE_STEP, 'Increase text size'));
    header.appendChild(wrap);
  }

  /* ---------- Init ---------- */

  function init() {
    buildControls();

    document.addEventListener('click', function (event) {
      var target = event.target;
      if (!target || !target.closest) return;
      if (target.closest('[data-track]')) fireConfetti();
    });

    observer.observe(document.body, { subtree: true, childList: true, characterData: true });
    updateUrgency();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
