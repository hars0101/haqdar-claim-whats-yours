<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>HaqDar · Scholarship finder · స్కాలర్‌షిప్ సహాయం</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
  .chip { display:inline-block; border:1px solid #94a3b8; border-radius:9999px; padding:.4rem .9rem; font-size:.9rem; background:#fff; }
  .chip:active { background:#ecfdf5; }
  .chip-skip { border-style:dashed; color:#64748b; }
  .btn { border-radius:.75rem; padding:.6rem 1rem; font-weight:600; background:#059669; color:#fff; }
  .btn:disabled { opacity:.6; }
  .btn-outline { background:#fff; color:#047857; border:1px solid #059669; }
  .tab-active { border-bottom:3px solid #059669; color:#047857; font-weight:700; }
  .tab-idle { color:#64748b; border-bottom:3px solid transparent; }
</style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col">

<header class="bg-emerald-700 text-white">
  <div class="max-w-2xl mx-auto px-4 py-3 flex items-center justify-between">
    <span class="text-lg font-bold">HaqDar</span>
    <span class="text-xs opacity-90">Free · ఉచితం</span>
  </div>
</header>

<nav class="bg-white border-b sticky top-0 z-10">
  <div class="max-w-2xl mx-auto grid grid-cols-3 text-center text-sm">
    <button type="button" data-tab="find" class="py-3">Find<br><span class="text-xs font-normal">వెతకండి</span></button>
    <button type="button" data-tab="results" class="py-3">Results<br><span class="text-xs font-normal">ఫలితాలు</span></button>
    <button type="button" data-tab="apps" class="py-3">My Applications<br><span class="text-xs font-normal">నా దరఖాస్తులు</span></button>
  </div>
</nav>

<main class="flex-1 w-full max-w-2xl mx-auto p-4 pb-10">

  <!-- FIND -->
  <section id="tab-find">
    <h1 class="text-2xl font-bold leading-snug">Free help to find scholarships you're likely eligible for — no middlemen</h1>
    <p class="text-sm text-slate-600 mt-1">ఉచితంగా, మధ్యవర్తులు లేకుండా మీకు అర్హత ఉండే స్కాలర్‌షిప్‌లను కనుగొనండి.</p>

    <label for="text" class="block font-semibold mt-5">Tell us about yourself</label>
    <p class="text-sm text-slate-500">English or తెలుగు — type or speak</p>
    <textarea id="text" rows="5" class="mt-2 w-full rounded-xl border border-slate-300 p-3 text-base focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="e.g. I'm 19, BCom first year, SC, income 1.2 lakh · ఉదా: నా వయస్సు 19, బీకామ్, SC"></textarea>

    <div class="mt-2 flex flex-wrap items-center gap-2">
      <button type="button" id="mic" class="btn btn-outline">🎙 Speak · మాట్లాడండి</button>
      <button type="button" id="lang" class="chip">Language: English</button>
      <span id="mic-status" class="text-xs text-slate-500"></span>
    </div>

    <p class="font-semibold mt-5">Try an example · ఉదాహరణ</p>
    <div id="personas" class="grid gap-2 mt-2"></div>

    <button type="button" id="submit" class="btn w-full mt-5 text-base">Find my schemes · నా పథకాలు వెతకండి</button>

    <div id="warn" class="hidden mt-4 rounded-xl border border-yellow-400 bg-yellow-100 p-3 text-sm">
      ⚠️ Never share Aadhaar numbers — we removed it.<br>
      <span class="text-xs">ఆధార్ నంబర్లు ఎప్పుడూ షేర్ చేయవద్దు — మేము వాటిని తీసివేశాము.</span>
    </div>
    <div id="error" class="hidden mt-4 rounded-xl border border-red-300 bg-red-50 p-3 text-sm text-red-800"></div>

    <div id="qbox" class="hidden mt-4 rounded-xl border border-emerald-300 bg-white p-4">
      <p id="qprog" class="text-xs text-slate-500"></p>
      <p id="qtext" class="font-semibold mt-1"></p>
      <div id="qchips" class="flex flex-wrap gap-2 mt-3"></div>
    </div>

    <div id="busy" class="hidden mt-4 text-sm text-slate-600">⏳ <span id="busy-text"></span></div>
  </section>

  <!-- RESULTS -->
  <section id="tab-results" class="hidden">
    <h1 id="headline" class="text-2xl font-bold">Run a search first</h1>
    <p id="subhead" class="text-sm text-slate-600 mt-1">Go to the Find tab and describe yourself.</p>

    <details id="sec-likely" open class="mt-4">
      <summary class="font-bold cursor-pointer">✅ Likely eligible (<span id="n-likely">0</span>)</summary>
      <div id="g-likely" class="mt-3"></div>
    </details>
    <details id="sec-maybe" open class="mt-4">
      <summary class="font-bold cursor-pointer">❓ Maybe (<span id="n-maybe">0</span>)</summary>
      <div id="g-maybe" class="mt-3"></div>
    </details>
    <details id="sec-no" class="mt-4">
      <summary class="font-bold cursor-pointer text-slate-500">Not a match (<span id="n-no">0</span>)</summary>
      <div id="g-no" class="mt-3"></div>
    </details>
  </section>

  <!-- MY APPLICATIONS -->
  <section id="tab-apps" class="hidden">
    <h1 class="text-2xl font-bold">My Applications</h1>
    <p class="text-sm text-slate-600 mt-1">Track where each scheme stands · ప్రతి పథకం స్థితిని ఇక్కడ చూడండి.</p>
    <div id="apps-list" class="mt-4"></div>
  </section>

</main>

<footer class="bg-white border-t">
  <div class="max-w-2xl mx-auto p-4 text-center text-sm text-slate-600">
    <p class="font-semibold">Free service. No middlemen needed. Final decision is by the scheme authority.</p>
    <p class="text-xs mt-1">ఇది ఉచిత సేవ. మధ్యవర్తులు అవసరం లేదు. తుది నిర్ణయం పథక అధికారిదే.</p>
    <button type="button" id="open-modal" class="underline text-emerald-700 mt-2">How we decide · మేము ఎలా నిర్ణయిస్తాం</button>
  </div>
</footer>

<div id="modal" class="hidden fixed inset-0 z-20 bg-black/50 flex items-end sm:items-center justify-center p-4">
  <div class="bg-white rounded-2xl p-5 w-full max-w-md">
    <div class="flex justify-between items-center">
      <h2 class="font-bold text-lg">How we decide · మేము ఎలా నిర్ణయిస్తాం</h2>
      <button type="button" id="close-modal" class="text-2xl leading-none">&times;</button>
    </div>
    <ul class="mt-3 space-y-3 text-sm list-disc pl-5">
      <li>AI only understands your words. It turns what you wrote into fields like age and category. · AI మీ మాటలను మాత్రమే అర్థం చేసుకుంటుంది.</li>
      <li>Fixed rules decide eligibility. The same answers always give the same result. · అర్హతను స్థిరమైన నియమాలే నిర్ణయిస్తాయి.</li>
      <li>Official sources with a verified date. Each scheme shows its official link and when we last checked it. · అధికారిక మూలాలు, ధృవీకరించిన తేదీతో.</li>
      <li>We never store Aadhaar. We remove any Aadhaar number before anything else happens. · ఆధార్‌ను మేము ఎప్పుడూ నిల్వ చేయము.</li>
    </ul>
  </div>
</div>

<div id="toast" class="hidden fixed bottom-4 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-sm px-4 py-2 rounded-lg z-30"></div>

<script>
const CATS = ['SC', 'ST', 'BC', 'EWS', 'Minority', 'OC'];

const PERSONAS = [
  { label: 'BCom 1st year · SC · Warangal', te: 'బీకామ్ మొదటి సంవత్సరం · SC · వరంగల్',
    text: "I'm 19, BCom first year in a govt degree college in Warangal, SC, father daily wage, income 1.2 lakh" },
  { label: 'Inter student · BC · farmer family', te: 'ఇంటర్ విద్యార్థి · BC · రైతు కుటుంబం',
    text: 'నా వయస్సు 17, ఇంటర్ చదువుతున్నాను, BC, మా నాన్న రైతు, ఆదాయం 80 వేలు' },
  { label: 'BTech girl · private college · OC', te: 'బీటెక్ విద్యార్థిని · ప్రైవేట్ కాలేజీ · OC',
    text: 'I am a girl doing BTech in a private AICTE college in Telangana, family income 6 lakh, OC' }
];

// Option values match the exact keys extract.php and rules use.
const QUESTIONS = {
  category: { q: 'Which category are you in?', qt: 'మీ కేటగిరీ ఏమిటి?',
    opts: CATS.map(c => [c, c]) },
  family_income: { q: 'Roughly what is your annual family income?', qt: 'కుటుంబ వార్షిక ఆదాయం ఎంత?',
    opts: [['80000', 'Under ₹1 lakh'], ['200000', '₹1–3 lakh'], ['500000', '₹3–8 lakh'], ['1000000', 'Above ₹8 lakh']] },
  education_level: { q: 'What are you studying now?', qt: 'మీరు ఇప్పుడు ఏమి చదువుతున్నారు?',
    opts: [['class_8', 'Class 8'], ['class_9_10', 'Class 9–10'], ['intermediate', 'Intermediate (11–12)'],
           ['diploma', 'Diploma'], ['ug', 'Degree (UG)'], ['pg', 'Postgraduate'], ['phd', 'PhD']] }
};

const STATUS_LABEL = {
  not_started: 'Not started', documents_ready: 'Documents ready', applied: 'Applied',
  under_verification: 'Under verification', received: 'Received'
};
const ICON = { pass: '✅', fail: '❌', unknown: '❓' };

let profile = {};
let pending = [];
let qIndex = 0;
let results = [];
let lang = 'en-IN';
let rec = null;
let toastTimer;

const $ = id => document.getElementById(id);
const esc = s => String(s ?? '').replace(/[&<>"']/g, c =>
  ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

function fmtVal(field, v) {
  if (v === null || v === undefined || v === '') return 'not given';
  if (typeof v === 'boolean') return v ? 'yes' : 'no';
  if (field === 'family_income') return '₹' + Number(v).toLocaleString('en-IN');
  return String(v);
}

function daysLeft(iso) {
  const t = new Date(iso + 'T00:00:00');
  const now = new Date();
  now.setHours(0, 0, 0, 0);
  const n = Math.round((t - now) / 86400000);
  return n < 0 ? 'passed' : n === 0 ? 'last day today' : n + ' days left';
}

async function api(url, opts = {}) {
  const res = await fetch(url, { ...opts, headers: { 'Content-Type': 'application/json' } });
  const data = await res.json().catch(() => ({}));
  if (!res.ok) throw new Error(data.error || ('Request failed (' + res.status + ')'));
  return data;
}

function setBusy(on, msg = '') {
  $('busy').classList.toggle('hidden', !on);
  $('busy-text').textContent = msg;
  $('submit').disabled = on;
}

function showErr(msg) {
  const e = $('error');
  e.textContent = msg;
  e.classList.remove('hidden');
}

function clearErr() {
  $('error').classList.add('hidden');
}

function toast(msg) {
  const t = $('toast');
  t.textContent = msg;
  t.classList.remove('hidden');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => t.classList.add('hidden'), 3000);
}

function showTab(name) {
  ['find', 'results', 'apps'].forEach(t => $('tab-' + t).classList.toggle('hidden', t !== name));
  document.querySelectorAll('nav [data-tab]').forEach(b => {
    const on = b.dataset.tab === name;
    b.classList.toggle('tab-active', on);
    b.classList.toggle('tab-idle', !on);
  });
  if (name === 'apps') loadApps();
  window.scrollTo(0, 0);
}

// ---- Persona buttons ----
$('personas').innerHTML = PERSONAS.map((p, i) =>
  `<button type="button" data-p="${i}" class="text-left rounded-xl border border-slate-300 bg-white p-3">
     <span class="block font-semibold text-sm">${esc(p.label)}</span>
     <span class="block text-xs text-slate-500">${esc(p.te)}</span>
   </button>`).join('');
$('personas').addEventListener('click', e => {
  const b = e.target.closest('[data-p]');
  if (!b) return;
  $('text').value = PERSONAS[Number(b.dataset.p)].text;
});

// ---- Voice input (Web Speech API) ----
const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
$('lang').addEventListener('click', () => {
  lang = lang === 'en-IN' ? 'te-IN' : 'en-IN';
  $('lang').textContent = lang === 'en-IN' ? 'Language: English' : 'భాష: తెలుగు';
});
$('mic').addEventListener('click', () => {
  if (!SR) return toast('Voice input is not supported in this browser. Please type instead.');
  if (rec) return rec.stop();
  try {
    rec = new SR();
    rec.lang = lang;
    rec.interimResults = false;
    rec.continuous = false;
    rec.onstart = () => {
      $('mic-status').textContent = lang === 'en-IN' ? 'Listening…' : 'వింటున్నాం…';
      $('mic').textContent = '⏹ Stop · ఆపండి';
    };
    rec.onresult = e => {
      const t = e.results[0][0].transcript;
      const box = $('text');
      box.value = box.value ? box.value + ' ' + t : t;
    };
    rec.onerror = e => { $('mic-status').textContent = 'Mic error: ' + e.error; };
    rec.onend = () => {
      rec = null;
      $('mic').textContent = '🎙 Speak · మాట్లాడండి';
      $('mic-status').textContent = '';
    };
    rec.start();
  } catch (err) {
    rec = null;
    toast('Could not start the microphone. Please type instead.');
  }
});

// ---- Extract -> (optional chip questions) -> match ----
$('submit').addEventListener('click', async () => {
  const text = $('text').value.trim();
  clearErr();
  if (!text) return showErr('Please write or speak something about yourself. · దయచేసి మీ గురించి కొంత రాయండి.');
  setBusy(true, 'Reading your details…');
  try {
    const d = await api('api/extract.php', { method: 'POST', body: JSON.stringify({ text }) });
    $('warn').classList.toggle('hidden', !d.blocked);
    profile = d.profile || {};
    pending = ['category', 'family_income', 'education_level'].filter(f => profile[f] == null).slice(0, 2);
    if (pending.length) {
      qIndex = 0;
      setBusy(false);
      showQuestion();
    } else {
      await runMatch();
    }
  } catch (e) {
    showErr(e.message);
  } finally {
    setBusy(false);
  }
});

function showQuestion() {
  const f = pending[qIndex];
  const Q = QUESTIONS[f];
  $('qbox').classList.remove('hidden');
  $('qprog').textContent = `Question ${qIndex + 1} of ${pending.length}`;
  $('qtext').innerHTML = `${esc(Q.q)} <span class="text-slate-500 font-normal">${esc(Q.qt)}</span>`;
  $('qchips').innerHTML = Q.opts.map(([v, l]) =>
    `<button type="button" class="chip" data-v="${esc(v)}">${esc(l)}</button>`).join('') +
    `<button type="button" class="chip chip-skip" data-v="">Skip · దాటవేయండి</button>`;
}

$('qchips').addEventListener('click', async e => {
  const b = e.target.closest('button[data-v]');
  if (!b) return;
  const f = pending[qIndex];
  const v = b.dataset.v;
  if (v !== '') profile[f] = f === 'family_income' ? Number(v) : v;
  if (++qIndex < pending.length) return showQuestion();
  $('qbox').classList.add('hidden');
  await runMatch();
});

async function runMatch() {
  clearErr();
  setBusy(true, 'Checking schemes against fixed rules…');
  try {
    const d = await api('api/match.php', { method: 'POST', body: JSON.stringify({ profile }) });
    results = d.results || [];
    profile = d.profile || profile;
    renderResults();
    showTab('results');
  } catch (e) {
    showErr(e.message);
  } finally {
    setBusy(false);
  }
}

// ---- Results ----
function ruleChips(rules) {
  if (!rules || !rules.length) return '<p class="text-xs text-slate-500">No rules to check.</p>';
  return rules.map(x =>
    `<span class="inline-block rounded-full border border-slate-300 px-2 py-1 text-xs mr-1 mb-1">
       ${ICON[x.result] || '❓'} ${esc(x.label)}
       <span class="text-slate-500">· you: ${esc(fmtVal(x.field, x.user_value))}</span>
     </span>`).join('');
}

function card(r) {
  const docs = (r.documents || []).length
    ? r.documents.map(d =>
        `<label class="flex gap-2 items-start py-1 text-sm">
           <input type="checkbox" class="mt-1">
           <span>${esc(d.name)}${d.notes ? `<span class="block text-xs text-slate-500">${esc(d.notes)}</span>` : ''}</span>
         </label>`).join('')
    : '<p class="text-xs text-slate-500">No documents listed.</p>';
  const steps = (r.steps || []).map(s => `<li>${esc(s.body)}</li>`).join('');
  const dl = r.deadline
    ? `<p>📅 Deadline: <b>${esc(r.deadline.deadline_date)}</b> — ${esc(r.deadline.label)}
         <span class="font-semibold">(${esc(daysLeft(r.deadline.deadline_date))})</span></p>`
    : '<p>📅 No open deadline listed right now.</p>';
  const cal = r.deadline
    ? `<a href="api/ics.php?scheme_id=${Number(r.id)}" class="btn btn-outline text-sm">Add to calendar</a>`
    : '';
  return `
    <article class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-3">
      <h3 class="font-bold text-lg leading-snug">${esc(r.name)}</h3>
      <p class="text-sm text-slate-600">${esc(r.provider)} · ${esc(r.level)}</p>
      <p class="mt-1 text-sm">${esc(r.benefit)}</p>

      <p class="text-xs font-semibold mt-3 mb-1">Rule check · నియమాల తనిఖీ</p>
      <div>${ruleChips(r.rules)}</div>

      <p class="text-xs font-semibold mt-3">Documents checklist · పత్రాలు</p>
      <div class="mt-1">${docs}</div>

      <details class="mt-3">
        <summary class="text-sm font-semibold cursor-pointer">Steps · దశలు</summary>
        <ol class="list-decimal pl-5 text-sm space-y-1 mt-2">${steps || '<li>No steps listed.</li>'}</ol>
      </details>

      <div class="mt-3 text-sm space-y-1">
        ${dl}
        <p>🔗 <a href="${esc(r.source_url)}" target="_blank" rel="noopener" class="text-emerald-700 underline">Official website</a></p>
        <p class="text-xs text-slate-500">Last verified: ${esc(r.last_verified)}</p>
      </div>

      <div class="mt-3 flex flex-wrap gap-2">
        <button type="button" data-track="${Number(r.id)}" class="btn text-sm">Track this</button>
        ${cal}
      </div>
    </article>`;
}

function renderResults() {
  const likely = results.filter(r => r.status === 'likely');
  const maybe = results.filter(r => r.status === 'maybe');
  const no = results.filter(r => r.status === 'no');
  $('headline').textContent = `You are likely eligible for ${likely.length} schemes`;
  $('subhead').textContent = 'These are likely matches based on what you told us, not a guarantee. Final decision is by the scheme authority. · ఇవి అర్హత సూచనలు మాత్రమే.';
  const empty = '<p class="text-sm text-slate-500">None in this group.</p>';
  $('g-likely').innerHTML = likely.map(card).join('') || empty;
  $('g-maybe').innerHTML = maybe.map(card).join('') || empty;
  $('g-no').innerHTML = no.map(card).join('') || empty;
  $('n-likely').textContent = likely.length;
  $('n-maybe').textContent = maybe.length;
  $('n-no').textContent = no.length;
}

$('tab-results').addEventListener('click', async e => {
  const t = e.target.closest('[data-track]');
  if (!t) return;
  t.disabled = true;
  t.textContent = 'Saving…';
  try {
    await api('api/track.php', { method: 'POST', body: JSON.stringify({ scheme_id: Number(t.dataset.track) }) });
    t.textContent = 'Tracked ✓';
  } catch (err) {
    t.disabled = false;
    t.textContent = 'Track this';
    toast(err.message);
  }
});

// ---- My Applications ----
async function loadApps() {
  $('apps-list').innerHTML = '<p class="text-sm text-slate-500">Loading…</p>';
  try {
    const d = await api('api/track.php');
    const statuses = d.statuses || Object.keys(STATUS_LABEL);
    const apps = d.applications || [];
    $('apps-list').innerHTML = apps.length
      ? apps.map(a => `
          <article class="bg-white rounded-xl border border-slate-200 p-4 mb-3">
            <h3 class="font-bold">${esc(a.name)}</h3>
            <p class="text-sm text-slate-600">${esc(a.benefit_text)}</p>
            <label class="block mt-2 text-sm font-semibold">Status
              <select data-app="${Number(a.scheme_id)}" class="mt-1 block w-full rounded-lg border border-slate-300 p-2 font-normal">
                ${statuses.map(s => `<option value="${esc(s)}" ${s === a.status ? 'selected' : ''}>${esc(STATUS_LABEL[s] || s)}</option>`).join('')}
              </select>
            </label>
            <p class="text-xs text-slate-500 mt-1">Updated: ${esc(a.updated_at || a.created_at)}</p>
            ${a.official_url ? `<a href="${esc(a.official_url)}" target="_blank" rel="noopener" class="text-xs text-emerald-700 underline">Official website</a>` : ''}
          </article>`).join('')
      : '<p class="text-sm text-slate-600">No applications tracked yet. Use "Track this" on a result.</p>';
  } catch (e) {
    $('apps-list').innerHTML = `<p class="text-sm text-red-700">${esc(e.message)}</p>`;
  }
}

$('apps-list').addEventListener('change', async e => {
  const sel = e.target.closest('select[data-app]');
  if (!sel) return;
  try {
    await api('api/track.php', { method: 'PUT', body: JSON.stringify({ scheme_id: Number(sel.dataset.app), status: sel.value }) });
    toast('Status saved · స్థితి సేవ్ అయింది');
  } catch (err) {
    toast(err.message);
    loadApps();
  }
});

// ---- Navigation and modal ----
document.querySelectorAll('nav [data-tab]').forEach(b =>
  b.addEventListener('click', () => showTab(b.dataset.tab)));
$('open-modal').addEventListener('click', () => $('modal').classList.remove('hidden'));
$('close-modal').addEventListener('click', () => $('modal').classList.add('hidden'));
$('modal').addEventListener('click', e => { if (e.target.id === 'modal') $('modal').classList.add('hidden'); });

showTab('find');
</script>
</body>
</html>