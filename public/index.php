<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=utf-8');
header('X-Content-Type-Options: nosniff');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>HaqDar · Scholarships</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
  @keyframes haqFadeUp { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: none; } }
  .bubble { animation: haqFadeUp .25s ease-out; }
  .card { background:#fff; border-radius:1rem; padding:1rem; border:1px solid #d1fae5; box-shadow:0 4px 14px rgba(6,95,70,.08); animation: haqFadeUp .3s ease-out; }
  .chip { display:inline-flex; align-items:center; min-height:44px; padding:.5rem 1rem; border-radius:9999px; border:2px solid #059669; background:#fff; color:#065f46; font-weight:600; transition:transform .1s, background .2s; }
  .chip:active { transform:scale(.96); background:#ecfdf5; }
  .chip:disabled { opacity:.5; }
  .chip-skip { border-style:dashed; border-color:#94a3b8; color:#475569; }
  .btn { display:inline-flex; align-items:center; justify-content:center; min-height:44px; padding:.5rem 1rem; border-radius:.75rem; background:#059669; color:#fff; font-weight:600; transition:transform .1s; }
  .btn:active { transform:scale(.97); }
  .btn:disabled { opacity:.6; }
  .btn-outline { background:#fff; color:#047857; border:2px solid #059669; }
  .lang-btn { min-height:72px; border-radius:1rem; background:#fff; border:2px solid #059669; color:#065f46; font-size:1.35rem; font-weight:700; box-shadow:0 4px 14px rgba(6,95,70,.1); }
  .lang-btn:active { background:#ecfdf5; }
  .stepper { min-height:44px; width:100%; border-radius:.75rem; border:2px solid #a7f3d0; background:#fff; color:#065f46; font-size:.85rem; font-weight:600; }
  .stepper.done { background:#d1fae5; }
  .stepper[aria-current="step"] { background:#059669; color:#fff; border-color:#059669; }
  .tab-active { border-bottom:3px solid #059669; color:#047857; font-weight:700; }
  .tab-idle { color:#64748b; border-bottom:3px solid transparent; }
  .print-title { display:none; }
  #printArea { display:none; }
  @media print {
    body * { visibility:hidden !important; }
    #printArea, #printArea * { visibility:visible !important; }
    #printArea { display:block !important; position:absolute; left:0; top:0; width:100%; padding:16px; }
    .print-title { display:block !important; font-size:20px; font-weight:700; margin-bottom:8px; }
    .no-print { display:none !important; }
    .card { box-shadow:none; break-inside:avoid; }
  }
</style>
</head>
<body class="bg-emerald-50 text-slate-800 min-h-screen flex flex-col font-sans">

<!-- First screen: big language picker -->
<section id="langScreen" class="hidden min-h-screen flex flex-col items-center justify-center gap-5 p-6">
  <h1 class="text-3xl font-bold text-emerald-800" data-i18n="app_name"></h1>
  <p class="text-lg text-slate-600" data-i18n="lang_title"></p>
  <div class="grid gap-4 w-full max-w-sm">
    <button type="button" class="lang-btn" data-pick="en" data-lang-name="en"></button>
    <button type="button" class="lang-btn" data-pick="hi" data-lang-name="hi"></button>
    <button type="button" class="lang-btn" data-pick="te" data-lang-name="te"></button>
  </div>
</section>

<div id="app" class="hidden flex flex-col flex-1">
  <header class="bg-emerald-700 text-white">
    <div class="max-w-2xl mx-auto px-4 py-3 flex items-center justify-between gap-3">
      <span class="text-xl font-bold" data-i18n="app_name"></span>
      <label class="flex items-center gap-2 text-sm">
        <span class="sr-only" data-i18n="lang_label"></span>
        <select id="langSelect" class="rounded-lg text-slate-800 px-2 py-2 min-h-[44px]">
          <option value="en" data-lang-name="en"></option>
          <option value="hi" data-lang-name="hi"></option>
          <option value="te" data-lang-name="te"></option>
        </select>
      </label>
    </div>
  </header>

  <!-- Trust strip -->
  <div class="bg-white border-b border-emerald-100">
    <p id="trust" class="max-w-2xl mx-auto px-4 py-2 text-center text-xs font-semibold text-emerald-800"></p>
  </div>

  <nav class="bg-white border-b sticky top-0 z-10 shadow-sm">
    <div class="max-w-2xl mx-auto grid grid-cols-2 text-center">
      <button type="button" data-tab="chat" class="py-3 min-h-[44px] font-semibold" data-i18n="tab_chat"></button>
      <button type="button" data-tab="apps" class="py-3 min-h-[44px] font-semibold" data-i18n="tab_apps"></button>
    </div>
  </nav>

  <main class="flex-1 w-full max-w-2xl mx-auto p-4 pb-10">
    <!-- CHAT + RESULTS -->
    <section id="viewChat" class="space-y-4">
      <div id="chat" aria-live="polite" class="space-y-3"></div>

      <form id="composer" class="no-print flex gap-2 items-center sticky bottom-2">
        <input id="input" type="text" autocomplete="off" data-i18n-placeholder="placeholder_input"
               class="flex-1 min-h-[44px] rounded-xl border border-slate-300 bg-white px-3 text-base focus:outline-none focus:ring-2 focus:ring-emerald-500">
        <button type="button" id="mic" class="btn btn-outline" data-i18n="btn_mic"></button>
        <button type="submit" class="btn" data-i18n="btn_send"></button>
      </form>

      <div id="results" class="space-y-4"></div>
    </section>

    <!-- MY APPLICATIONS -->
    <section id="viewApps" class="hidden space-y-4">
      <h1 class="text-2xl font-bold" data-i18n="apps_title"></h1>
      <p class="text-sm text-slate-600" data-i18n="apps_sub"></p>
      <p id="appsMsg" class="hidden rounded-xl border border-red-300 bg-red-50 p-3 text-sm text-red-800"></p>
      <div id="apps" class="space-y-4"></div>
    </section>
  </main>

  <footer class="bg-white border-t">
    <div class="max-w-2xl mx-auto p-4 text-center text-sm text-slate-600 space-y-1">
      <p id="footTrust" class="font-semibold text-emerald-800 text-xs"></p>
      <p class="text-xs" data-i18n="footer_note"></p>
      <div class="flex justify-center gap-4 pt-2">
        <button type="button" id="openModal" class="underline text-emerald-700 min-h-[44px]" data-i18n="btn_how"></button>
        <a href="insights.php" class="underline text-emerald-700 min-h-[44px] inline-flex items-center" data-i18n="link_partners"></a>
      </div>
    </div>
  </footer>
</div>

<!-- How we decide modal -->
<div id="modal" class="hidden fixed inset-0 z-20 bg-black/50 flex items-end sm:items-center justify-center p-4">
  <div class="bg-white rounded-2xl p-5 w-full max-w-md shadow-xl">
    <div class="flex justify-between items-center">
      <h2 class="font-bold text-lg" data-i18n="modal_title"></h2>
      <button type="button" id="closeModal" class="text-2xl leading-none min-h-[44px] min-w-[44px]" aria-label="close">&times;</button>
    </div>
    <ul class="mt-3 space-y-3 text-sm list-disc pl-5">
      <li data-i18n="modal_1"></li>
      <li data-i18n="modal_2"></li>
      <li data-i18n="modal_3"></li>
      <li data-i18n="modal_4"></li>
    </ul>
    <button type="button" id="closeModal2" class="btn w-full mt-4" data-i18n="btn_close"></button>
  </div>
</div>

<!-- Print area: only likely + maybe cards, built by script -->
<div id="printArea"></div>

<script src="i18n.js"></script>
<script>
(function () {
  'use strict';

  var API = { extract: 'api/extract.php', match: 'api/match.php', track: 'api/track.php', ics: 'api/ics.php' };
  var TRUST_COUNT = 12;
  var TRUST_DATE = '22 Nov 2026';
  var CHIP_FIELDS = ['category', 'family_income', 'education_level', 'gender', 'institution_type'];
  var CHIP_VALUES = {
    category: ['SC', 'ST', 'BC', 'EWS', 'Minority', 'OC'],
    family_income: [80000, 200000, 500000, 1000000],
    education_level: ['class_8', 'class_9_10', 'intermediate', 'diploma', 'ug', 'pg', 'phd'],
    gender: ['male', 'female'],
    institution_type: ['government', 'aided', 'private']
  };
  var CHIP_KEY = {
    category: function (v) { return 'cat_' + v; },
    family_income: function (v) { return 'inc_' + v; },
    education_level: function (v) { return 'edu_' + v; },
    gender: function (v) { return 'gen_' + v; },
    institution_type: function (v) { return 'inst_' + v; }
  };
  var PROFILE_KEYS = ['age', 'gender', 'category', 'family_income', 'education_level', 'course', 'institution_type', 'state', 'disability', 'marks_percent'];
  var STATUSES = ['not_started', 'documents_ready', 'applied', 'under_verification', 'received'];
  var ICON = { pass: '✅', fail: '❌', unknown: '❓' };
  var STATUS_CLASS = {
    likely: 'bg-emerald-100 text-emerald-800',
    maybe: 'bg-amber-100 text-amber-800',
    no: 'bg-slate-200 text-slate-600'
  };
  var PILL_CLASS = {
    pass: 'bg-emerald-50 border-emerald-300 text-emerald-900',
    fail: 'bg-red-50 border-red-300 text-red-800',
    unknown: 'bg-slate-50 border-slate-300 text-slate-700'
  };

  var S = {
    log: [],          // chat entries: {who, build, done}
    profile: blankProfile(),
    queue: [],        // chip fields still to ask
    results: null,    // match.php results, or null before first match
    apps: null,       // track.php applications, or null before first load
    tracked: {},      // scheme ids tracked in this session
    recog: null,
    speakBtn: null
  };

  function $(id) { return document.getElementById(id); }

  /** HTML-escapes a value for string templating. DOM building uses textContent, so this is a safety net. */
  function esc(s) {
    return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
  }

  /** Builds an element. Strings and numbers become text nodes; nothing is parsed as HTML. */
  function el(tag, props) {
    var n = document.createElement(tag);
    if (props) {
      Object.keys(props).forEach(function (k) {
        var v = props[k];
        if (v === false || v === null || v === undefined) return;
        if (k === 'class') n.className = v;
        else if (k === 'on') Object.keys(v).forEach(function (ev) { n.addEventListener(ev, v[ev]); });
        else if (v === true) n.setAttribute(k, '');
        else n.setAttribute(k, String(v));
      });
    }
    var kids = Array.prototype.slice.call(arguments, 2).flat(Infinity);
    kids.forEach(function (c) {
      if (c === null || c === undefined || c === false) return;
      n.appendChild(c instanceof Node ? c : document.createTextNode(String(c)));
    });
    return n;
  }

  function chip(label, onClick, disabled, skip) {
    return el('button', {
      type: 'button',
      class: 'chip' + (skip ? ' chip-skip' : ''),
      disabled: !!disabled,
      on: { click: onClick }
    }, label);
  }

  function blankProfile() {
    var o = {};
    PROFILE_KEYS.forEach(function (k) { o[k] = null; });
    return o;
  }

  function normalizeProfile(p) {
    var o = blankProfile();
    if (p && typeof p === 'object') {
      PROFILE_KEYS.forEach(function (k) { if (p[k] !== undefined) o[k] = p[k]; });
    }
    return o;
  }

  /** Returns the dictionary string for key, or null when the current language lacks it. */
  function tryT(key) {
    var d = T[getLang()];
    return d && d[key] ? d[key] : null;
  }

  function fmtVal(field, v) {
    if (v === null || v === undefined || v === '') return t('not_given');
    if (typeof v === 'boolean') return v ? t('yes') : t('no');
    if (field === 'family_income') return '₹' + Number(v).toLocaleString('en-IN');
    if (CHIP_KEY[field]) {
      var label = tryT(CHIP_KEY[field](v));
      if (label) return label;
    }
    return String(v);
  }

  /** Whole days from today to an ISO date (negative when passed). */
  function daysTo(iso) {
    var target = new Date(iso + 'T00:00:00');
    var today = new Date();
    today.setHours(0, 0, 0, 0);
    return Math.round((target - today) / 86400000);
  }

  function daysText(n) {
    return n <= 0 ? t('days_red') : t('days_left', { n: n });
  }

  /** Calls an API endpoint. Returns parsed JSON; throws Error with a readable message otherwise. */
  async function api(url, method, body) {
    var opts = { method: method || 'GET', credentials: 'same-origin', headers: { 'Content-Type': 'application/json' } };
    if (body !== undefined) opts.body = JSON.stringify(body);
    var res;
    try {
      res = await fetch(url, opts);
    } catch (e) {
      throw new Error(t('err_network'));
    }
    var data = {};
    try { data = await res.json(); } catch (e) { data = {}; }
    if (!res.ok) throw new Error((data && data.error) || t('err_network'));
    return data;
  }

  // ---------- Chat log ----------
  function scrollDown() {
    window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
  }

  function bubble(e) {
    var mine = e.who === 'user';
    return el('div', { class: 'bubble flex ' + (mine ? 'justify-end' : 'justify-start') },
      el('div', {
        class: mine
          ? 'max-w-[85%] rounded-2xl rounded-br-sm bg-emerald-600 text-white px-4 py-3 shadow-md'
          : 'max-w-[95%] w-full rounded-2xl rounded-bl-sm bg-white border border-emerald-100 px-4 py-3 shadow-sm space-y-3'
      }, e.build(e)));
  }

  function renderChat() {
    var box = $('chat');
    box.textContent = '';
    S.log.forEach(function (e) { box.appendChild(bubble(e)); });
  }

  function say(who, build) {
    var e = { who: who, build: build, done: false };
    S.log.push(e);
    $('chat').appendChild(bubble(e));
    scrollDown();
    return e;
  }

  function userSay(text) {
    return say('user', function () { return [text]; });
  }

  function botSay(make, warn) {
    return say('bot', function () {
      return [el('p', { class: warn ? 'font-medium text-amber-800' : 'leading-relaxed' }, make())];
    });
  }

  function botError(err) {
    var msg = err && err.message ? err.message : t('err_network');
    botSay(function () { return t('err_prefix') + msg; }, true);
  }

  function removeEntry(e) {
    S.log = S.log.filter(function (x) { return x !== e; });
    renderChat();
  }

  function openingBubble() {
    say('bot', function () {
      return [
        el('p', { class: 'leading-relaxed' }, t('opening_msg')),
        el('div', { class: 'flex flex-col gap-2' },
          ['persona_en', 'persona_hi', 'persona_te'].map(function (k) {
            return chip(t(k), function () { sendText(t(k)); });
          }))
      ];
    });
  }

  // ---------- Conversation flow ----------
  async function sendText(text) {
    text = String(text || '').trim();
    if (!text) return;
    userSay(text);
    var busy = botSay(function () { return t('thinking'); });
    try {
      var data = await api(API.extract, 'POST', { text: text });
      removeEntry(busy);
      if (data.blocked === true) {
        botSay(function () { return t('blocked_aadhaar'); }, true);
      }
      S.profile = normalizeProfile(data.profile);
      var missing = Array.isArray(data.missing_fields) ? data.missing_fields : [];
      S.queue = CHIP_FIELDS.filter(function (f) {
        return S.profile[f] === null || missing.indexOf(f) !== -1;
      });
      S.results = null;
      nextStep();
    } catch (err) {
      removeEntry(busy);
      botError(err);
    }
  }

  function nextStep() {
    if (S.queue.length) {
      askField(S.queue.shift());
      return;
    }
    finish();
  }

  function askField(field) {
    say('bot', function (e) {
      return [
        el('p', { class: 'font-semibold' }, t('q_' + field)),
        el('div', { class: 'flex flex-wrap gap-2' },
          CHIP_VALUES[field].map(function (v) {
            return chip(t(CHIP_KEY[field](v)), function () { answer(e, field, v); }, e.done);
          }),
          chip(t('btn_skip'), function () { answer(e, field, null); }, e.done, true))
      ];
    });
  }

  function answer(e, field, value) {
    if (e.done) return;
    e.done = true;
    S.profile[field] = value;
    renderChat();
    userSay(value === null ? t('btn_skip') : t(CHIP_KEY[field](value)));
    nextStep();
  }

  function finish() {
    profileCard();
    runMatch();
  }

  function profileCard() {
    say('bot', function () {
      return [
        el('p', { class: 'font-bold' }, t('profile_title')),
        el('dl', { class: 'grid grid-cols-2 gap-x-3 gap-y-1 text-sm' },
          PROFILE_KEYS.map(function (k) {
            return [
              el('dt', { class: 'text-slate-500' }, t('f_' + k)),
              el('dd', { class: 'font-medium' }, fmtVal(k, S.profile[k]))
            ];
          })),
        el('div', { class: 'flex flex-wrap gap-2' },
          CHIP_FIELDS.map(function (f) {
            return chip(t('edit_chip_prefix') + ': ' + t('f_' + f), function () {
              S.queue = [f];
              nextStep();
            });
          }))
      ];
    });
  }

  function askAgain(field) {
    showTab('chat');
    S.queue = [field];
    nextStep();
  }

  async function runMatch() {
    var busy = botSay(function () { return t('thinking'); });
    try {
      var profile = {};
      PROFILE_KEYS.forEach(function (k) { profile[k] = S.profile[k] === undefined ? null : S.profile[k]; });
      var data = await api(API.match, 'POST', { profile: profile });
      S.results = Array.isArray(data.results) ? data.results : [];
      removeEntry(busy);
      renderResults();
    } catch (err) {
      removeEntry(busy);
      botError(err);
    }
  }

  // ---------- Results ----------
  function likelyList() {
    return (S.results || []).filter(function (r) { return r.status === 'likely'; });
  }

  function nextDeadlineDays() {
    var dates = (S.results || [])
      .map(function (r) { return r.deadline && r.deadline.deadline_date; })
      .filter(Boolean)
      .sort();
    return dates.length ? daysTo(dates[0]) : null;
  }

  function summaryText() {
    var x = likelyList().length;
    var n = nextDeadlineDays();
    return n === null ? t('summary_nodl', { x: x }) : t('summary_full', { x: x, n: n });
  }

  function shareText() {
    var lines = [t('wa_intro'), summaryText()];
    likelyList().forEach(function (r) {
      var line = '• ' + r.name;
      if (r.deadline) line += ' — ' + r.deadline.label + ' ' + r.deadline.deadline_date;
      if (r.source_url) line += ' ' + r.source_url;
      lines.push(line);
    });
    lines.push(t('wa_tagline'));
    return lines.join('\n');
  }

  function readAloud(btn) {
    if (!('speechSynthesis' in window)) return;
    if (window.speechSynthesis.speaking) {
      window.speechSynthesis.cancel();
      btn.textContent = t('btn_read');
      return;
    }
    var names = likelyList().map(function (r) { return r.name; }).join(', ');
    var u = new SpeechSynthesisUtterance(summaryText() + '. ' + names);
    u.lang = speechLocale();
    u.onend = function () { btn.textContent = t('btn_read'); };
    u.onerror = function () { btn.textContent = t('btn_read'); };
    btn.textContent = t('btn_stop_read');
    window.speechSynthesis.speak(u);
  }

  function badge(status) {
    return el('span', { class: 'shrink-0 rounded-full px-3 py-1 text-xs font-bold ' + STATUS_CLASS[status] },
      t('status_' + status));
  }

  function daysBadge(iso) {
    var n = daysTo(iso);
    return el('span', { class: 'text-sm font-semibold ' + (n < 30 ? 'text-red-600' : 'text-slate-600') },
      daysText(n));
  }

  function summaryCard() {
    var readBtn = null;
    if ('speechSynthesis' in window) {
      readBtn = el('button', { type: 'button', class: 'btn btn-outline' }, t('btn_read'));
      readBtn.addEventListener('click', function () { readAloud(readBtn); });
    }
    return el('div', { class: 'card space-y-3 no-print' },
      el('p', { class: 'font-semibold text-lg leading-snug' }, summaryText()),
      el('div', { class: 'flex flex-wrap gap-2' },
        readBtn,
        el('button', {
          type: 'button', class: 'btn btn-outline',
          on: { click: function () { window.open('https://wa.me/?text=' + encodeURIComponent(shareText()), '_blank', 'noopener'); } }
        }, t('btn_whatsapp')),
        el('button', { type: 'button', class: 'btn btn-outline', on: { click: function () { window.print(); } } }, t('btn_print'))));
  }

  function ruleChip(rule) {
    var res = rule.result === 'pass' || rule.result === 'fail' ? rule.result : 'unknown';
    var value = fmtVal(rule.field, rule.user_value);
    return el('li', { class: 'rounded-full border px-3 py-1 text-xs ' + PILL_CLASS[res], title: t('rule_' + res) },
      ICON[res] + ' ' + t('rule_line', { label: rule.label, value: value }));
  }

  function documentsList(r) {
    var key = 'haqdar_docs_' + r.id;
    var checked = [];
    try { checked = JSON.parse(localStorage.getItem(key) || '[]'); } catch (e) { checked = []; }
    if (!Array.isArray(checked)) checked = [];
    return el('div', { class: 'space-y-1' },
      el('p', { class: 'font-semibold text-sm' }, t('docs_title')),
      (r.documents || []).map(function (d, i) {
        var cb = el('input', { type: 'checkbox', class: 'mt-1 h-5 w-5 shrink-0' });
        cb.checked = checked.indexOf(i) !== -1;
        cb.addEventListener('change', function () {
          var list = [];
          try { list = JSON.parse(localStorage.getItem(key) || '[]'); } catch (e) { list = []; }
          if (!Array.isArray(list)) list = [];
          list = list.filter(function (x) { return x !== i; });
          if (cb.checked) list.push(i);
          try { localStorage.setItem(key, JSON.stringify(list)); } catch (e) { /* storage full or blocked */ }
        });
        return el('label', { class: 'flex gap-3 items-start min-h-[44px] text-sm' },
          cb,
          el('span', null,
            el('span', { class: 'font-medium' }, d.name),
            d.notes ? el('span', { class: 'block text-xs text-slate-500' }, d.notes) : null));
      }));
  }

  function whatIfBox(r) {
    var items = [];
    (r.rules || []).forEach(function (rule) {
      if (rule.result === 'fail') {
        items.push(el('li', null, t('needs_line', { label: rule.label })));
      } else if (rule.result === 'unknown') {
        items.push(el('li', { class: 'flex flex-wrap items-center gap-2' },
          t('tellus_line', { label: rule.label }),
          CHIP_FIELDS.indexOf(rule.field) !== -1
            ? el('button', { type: 'button', class: 'btn btn-outline', on: { click: function () { askAgain(rule.field); } } }, t('recheck_btn'))
            : null));
      }
    });
    if (!items.length) return null;
    return el('div', { class: 'rounded-xl bg-amber-50 border border-amber-200 p-3 text-sm space-y-2' },
      el('p', { class: 'font-semibold' }, t('whatif_title')),
      el('ul', { class: 'list-disc pl-5 space-y-1' }, items));
  }

  function trackButton(r) {
    var done = !!S.tracked[r.id];
    var btn = el('button', { type: 'button', class: 'btn', disabled: done }, done ? t('btn_tracked') : t('btn_track'));
    btn.addEventListener('click', async function () {
      btn.disabled = true;
      try {
        await api(API.track, 'POST', { scheme_id: r.id });
        S.tracked[r.id] = true;
        btn.textContent = t('btn_tracked');
      } catch (err) {
        btn.disabled = false;
        botError(err);
      }
    });
    return btn;
  }

  /** One scheme card. mode 'print' omits interactive buttons and what-if boxes. */
  function schemeCard(r, mode) {
    var printing = mode === 'print';
    var dl = r.deadline;
    return el('article', { class: 'card space-y-3' },
      el('div', { class: 'flex items-start justify-between gap-2' },
        el('div', null,
          el('h3', { class: 'font-bold text-lg leading-snug' }, r.name),
          el('p', { class: 'text-sm text-slate-600' }, t('provider_label') + ': ' + (r.provider || '—'))),
        badge(r.status)),
      el('p', { class: 'text-sm' },
        el('span', { class: 'font-semibold' }, t('level_label') + ': '), r.level || '—'),
      el('p', { class: 'text-sm' },
        el('span', { class: 'font-semibold' }, t('benefit_label') + ': '), r.benefit || '—'),
      (r.rules && r.rules.length)
        ? el('ul', { class: 'flex flex-wrap gap-2' }, r.rules.map(ruleChip))
        : null,
      (r.documents && r.documents.length) ? documentsList(r) : null,
      (r.steps && r.steps.length)
        ? el('details', { class: 'text-sm' },
            el('summary', { class: 'cursor-pointer font-semibold min-h-[44px] flex items-center' }, t('steps_title')),
            el('ol', { class: 'list-decimal pl-5 space-y-1' },
              r.steps.map(function (s) { return el('li', { value: s.step_no }, s.body); })))
        : null,
      dl
        ? el('p', { class: 'text-sm' },
            el('span', { class: 'font-semibold' }, dl.label + ' · ' + dl.deadline_date + ' · '),
            daysBadge(dl.deadline_date))
        : el('p', { class: 'text-sm text-slate-500' }, t('deadline_none')),
      el('p', { class: 'text-xs text-slate-500' },
        t('last_verified') + ': ' + (r.last_verified || t('not_verified'))),
      printing ? null : el('div', { class: 'flex flex-wrap gap-2 no-print' },
        r.source_url ? el('a', { href: r.source_url, target: '_blank', rel: 'noopener noreferrer', class: 'btn btn-outline' }, t('btn_official')) : null,
        trackButton(r),
        el('a', { href: API.ics + '?scheme_id=' + encodeURIComponent(r.id), class: 'btn btn-outline' }, t('btn_calendar'))),
      (!printing && r.status !== 'likely') ? whatIfBox(r) : null
    );
  }

  function renderResults() {
    var box = $('results');
    box.textContent = '';
    if (!S.results) return;
    if (!S.results.length) {
      box.appendChild(el('div', { class: 'card text-sm' }, t('no_results')));
      renderPrint();
      return;
    }
    box.appendChild(summaryCard());
    ['likely', 'maybe', 'no'].forEach(function (status) {
      var list = S.results.filter(function (r) { return r.status === status; });
      var title = status === 'likely' ? 'sec_likely_label' : status === 'maybe' ? 'sec_maybe_label' : 'sec_no_label';
      box.appendChild(el('details', { open: status !== 'no', class: 'space-y-3' },
        el('summary', { class: 'font-bold cursor-pointer min-h-[44px] flex items-center' },
          t(title) + ' (' + list.length + ')'),
        list.map(function (r) { return schemeCard(r, 'screen'); })));
    });
    renderPrint();
  }

  /** Print area: likely + maybe cards with documents and steps, plus the print-only title. */
  function renderPrint() {
    var box = $('printArea');
    box.textContent = '';
    if (!S.results) return;
    box.appendChild(el('h2', { class: 'print-title' }, t('print_title')));
    S.results
      .filter(function (r) { return r.status === 'likely' || r.status === 'maybe'; })
      .forEach(function (r) { box.appendChild(schemeCard(r, 'print')); });
  }

  // ---------- My Applications ----------
  function appsMsg(msg) {
    var m = $('appsMsg');
    m.textContent = msg;
    m.classList.remove('hidden');
  }

  function clearAppsMsg() {
    $('appsMsg').classList.add('hidden');
  }

  async function loadApps() {
    clearAppsMsg();
    try {
      var data = await api(API.track, 'GET');
      S.apps = Array.isArray(data.applications) ? data.applications : [];
      S.apps.forEach(function (a) { S.tracked[a.scheme_id] = true; });
    } catch (err) {
      appsMsg(t('err_prefix') + (err.message || t('err_network')));
      return;
    }
    renderApps();
  }

  async function setStatus(a, status) {
    clearAppsMsg();
    try {
      await api(API.track, 'PUT', { scheme_id: a.scheme_id, status: status });
      a.status = status;
      renderApps();
    } catch (err) {
      appsMsg(t('err_prefix') + (err.message || t('err_network')));
    }
  }

  function appCard(a) {
    var cur = STATUSES.indexOf(a.status);
    var match = (S.results || []).filter(function (r) { return r.id === a.scheme_id; })[0];
    var dlDate = match && match.deadline ? match.deadline.deadline_date : null;
    return el('article', { class: 'card space-y-3' },
      el('h3', { class: 'font-bold text-lg leading-snug' }, a.name),
      a.benefit_text ? el('p', { class: 'text-sm text-slate-600' }, a.benefit_text) : null,
      a.official_url ? el('a', { href: a.official_url, target: '_blank', rel: 'noopener noreferrer', class: 'text-sm underline text-emerald-700 min-h-[44px] inline-flex items-center' }, t('btn_official')) : null,
      dlDate ? daysBadge(dlDate) : null,
      el('p', { class: 'text-sm font-semibold' }, t('apps_stage') + ': ' + t('stat_' + a.status)),
      el('ol', { class: 'grid grid-cols-1 sm:grid-cols-5 gap-2' },
        STATUSES.map(function (st, i) {
          return el('li', null, el('button', {
            type: 'button',
            class: 'stepper' + (i <= cur ? ' done' : ''),
            'aria-current': st === a.status ? 'step' : false,
            on: { click: function () { setStatus(a, st); } }
          }, t('stat_' + st)));
        })));
  }

  function renderApps() {
    var box = $('apps');
    box.textContent = '';
    if (!S.apps) return;
    if (!S.apps.length) {
      box.appendChild(el('div', { class: 'card text-sm' }, t('apps_empty')));
      return;
    }
    S.apps.forEach(function (a) { box.appendChild(appCard(a)); });
  }

  // ---------- Voice ----------
  function setMicLabel(listening) {
    $('mic').textContent = listening ? t('mic_listening') : t('btn_mic');
  }

  function setupMic() {
    var SR = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SR) {
      $('mic').classList.add('hidden');
      return;
    }
    $('mic').addEventListener('click', function () {
      if (S.recog) { S.recog.stop(); return; }
      var rec = new SR();
      rec.lang = speechLocale();
      rec.interimResults = false;
      rec.maxAlternatives = 1;
      rec.onresult = function (ev) {
        var txt = ev.results[0][0].transcript;
        $('input').value = '';
        sendText(txt);
      };
      rec.onend = function () { S.recog = null; setMicLabel(false); };
      rec.onerror = function () { S.recog = null; setMicLabel(false); };
      S.recog = rec;
      rec.start();
      setMicLabel(true);
    });
  }

  // ---------- Language, tabs, trust, modal ----------
  function renderTrust() {
    var txt = t('trust_line', { n: TRUST_COUNT, date: TRUST_DATE });
    $('trust').textContent = txt;
    $('footTrust').textContent = txt;
  }

  function applyLang() {
    applyI18n(document);
    $('langSelect').value = getLang();
    renderTrust();
    setMicLabel(!!S.recog);
    renderChat();
    renderResults();
    renderApps();
    if (S.recog) setMicLabel(true);
  }

  function showTab(name) {
    $('viewChat').classList.toggle('hidden', name !== 'chat');
    $('viewApps').classList.toggle('hidden', name !== 'apps');
    document.querySelectorAll('nav [data-tab]').forEach(function (b) {
      var on = b.getAttribute('data-tab') === name;
      b.classList.toggle('tab-active', on);
      b.classList.toggle('tab-idle', !on);
    });
    if (name === 'apps') loadApps();
    window.scrollTo(0, 0);
  }

  function showApp() {
    $('langScreen').classList.add('hidden');
    $('app').classList.remove('hidden');
  }

  function init() {
    document.querySelectorAll('[data-pick]').forEach(function (b) {
      b.addEventListener('click', function () {
        setLang(b.getAttribute('data-pick'));
        showApp();
        applyLang();
        if (!S.log.length) openingBubble();
      });
    });

    $('langSelect').addEventListener('change', function (ev) {
      setLang(ev.target.value);
      applyLang();
    });

    document.querySelectorAll('nav [data-tab]').forEach(function (b) {
      b.addEventListener('click', function () { showTab(b.getAttribute('data-tab')); });
    });

    $('composer').addEventListener('submit', function (ev) {
      ev.preventDefault();
      var v = $('input').value;
      $('input').value = '';
      sendText(v);
    });

    $('openModal').addEventListener('click', function () { $('modal').classList.remove('hidden'); });
    $('closeModal').addEventListener('click', function () { $('modal').classList.add('hidden'); });
    $('closeModal2').addEventListener('click', function () { $('modal').classList.add('hidden'); });

    setupMic();

    applyI18n(document);
    $('langSelect').value = getLang();
    renderTrust();
    if (hasSavedLang()) {
      showApp();
      openingBubble();
    } else {
      $('langScreen').classList.remove('hidden');
    }
  }

  init();
})();
</script>
</body>
</html>
