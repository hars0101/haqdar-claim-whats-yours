<?php
declare(strict_types=1);
require_once __DIR__ . '/../app/db.php';

header('Content-Type: text/html; charset=utf-8');
header('X-Content-Type-Options: nosniff');

function h($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

$STATUS = [
    'not_started' => 'Not started',
    'documents_ready' => 'Documents ready',
    'applied' => 'Applied',
    'under_verification' => 'Under verification',
    'received' => 'Money received',
];

$error = null;
$total = 0; $schemesCount = 0; $perScheme = []; $funnel = []; $deadlines = []; $stale = [];
try {
    $pdo = db();
    $schemesCount = (int)$pdo->query('SELECT COUNT(*) FROM schemes WHERE is_active = 1')->fetchColumn();
    $total = (int)$pdo->query('SELECT COUNT(*) FROM applications')->fetchColumn();
    $perScheme = $pdo->query(
        'SELECT s.name, COUNT(a.id) AS n FROM schemes s
         LEFT JOIN applications a ON a.scheme_id = s.id
         WHERE s.is_active = 1 GROUP BY s.id, s.name ORDER BY n DESC, s.name'
    )->fetchAll();
    foreach ($pdo->query('SELECT status, COUNT(*) AS n FROM applications GROUP BY status')->fetchAll() as $r) {
        $funnel[$r['status']] = (int)$r['n'];
    }
    $deadlines = $pdo->query(
        'SELECT s.name, d.deadline_date, d.label, DATEDIFF(d.deadline_date, CURDATE()) AS days_left
         FROM scheme_deadlines d JOIN schemes s ON s.id = d.scheme_id
         WHERE s.is_active = 1 AND d.deadline_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 180 DAY)
         ORDER BY d.deadline_date'
    )->fetchAll();
    $stale = $pdo->query(
        'SELECT name, last_verified FROM schemes
         WHERE is_active = 1 AND (last_verified IS NULL OR last_verified < DATE_SUB(CURDATE(), INTERVAL 90 DAY))
         ORDER BY last_verified'
    )->fetchAll();
} catch (Throwable $e) {
    error_log('insights.php: ' . $e->getMessage());
    $error = 'Live numbers are unavailable right now. Please try again shortly.';
}
$maxScheme = max(1, ...array_map(fn($r) => (int)$r['n'], $perScheme ?: [['n' => 1]]));
$maxFunnel = max(1, ...(array_values($funnel) ?: [1]));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>HaqDar · Partner dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
<style>
  body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; background: #f3f2ee; }
  .panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 16px;
           box-shadow: 0 1px 2px rgba(15,23,42,.04), 0 12px 28px -18px rgba(11,31,58,.35); }
  .bar { height: .6rem; border-radius: 999px; background: #2b5cff; }
  .kpi { font-size: 2.25rem; font-weight: 800; letter-spacing: -.03em; }
</style>
</head>
<body class="min-h-screen text-slate-800">
<header style="background: linear-gradient(90deg,#0b0b0c,#0b0b0c); border-bottom: 3px solid #2b5cff;" class="text-white">
  <div class="max-w-6xl mx-auto px-4 py-5 flex flex-wrap items-center justify-between gap-3">
    <div class="flex items-center gap-3">
      <span class="inline-grid place-items-center w-10 h-10 rounded-xl font-bold" style="background: #2b5cff">₹</span>
      <div>
        <p class="text-xl font-extrabold tracking-tight">HaqDar · Partner dashboard</p>
        <p class="text-sm text-white/75">For NGOs, Common Service Centres &amp; State Governments</p>
      </div>
    </div>
    <a href="index.php" class="rounded-full bg-white/10 border border-white/25 px-4 py-2 text-sm font-semibold hover:bg-white/20">← Back to app</a>
  </div>
</header>

<main class="max-w-6xl mx-auto px-4 py-8 space-y-6">
  <p class="text-sm text-slate-500">Anonymous totals only. No names, emails, Aadhaar or session IDs are shown or stored here.</p>

  <?php if ($error): ?>
    <div class="panel p-4 text-amber-800"><?= h($error) ?></div>
  <?php endif; ?>

  <section class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="panel p-5"><p class="text-xs uppercase tracking-wider text-slate-500">Schemes covered</p><p class="kpi text-slate-900"><?= $schemesCount ?></p></div>
    <div class="panel p-5"><p class="text-xs uppercase tracking-wider text-slate-500">Applications tracked</p><p class="kpi text-blue-600"><?= $total ?></p></div>
    <div class="panel p-5"><p class="text-xs uppercase tracking-wider text-slate-500">Deadlines · 180 days</p><p class="kpi text-amber-600"><?= count($deadlines) ?></p></div>
    <div class="panel p-5"><p class="text-xs uppercase tracking-wider text-slate-500">Need re-verification</p><p class="kpi <?= $stale ? 'text-red-600' : 'text-blue-600' ?>"><?= count($stale) ?></p></div>
  </section>

  <section class="grid lg:grid-cols-2 gap-6">
    <div class="panel p-6">
      <h2 class="font-bold text-lg mb-4">Interest by scheme</h2>
      <?php foreach ($perScheme as $r): ?>
        <div class="mb-3">
          <div class="flex justify-between text-sm"><span><?= h($r['name']) ?></span><span class="font-semibold"><?= (int)$r['n'] ?></span></div>
          <div class="bg-slate-100 rounded-full mt-1"><div class="bar" style="width: <?= max(2, (int)round(100 * (int)$r['n'] / $maxScheme)) ?>%"></div></div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="space-y-6">
      <div class="panel p-6">
        <h2 class="font-bold text-lg mb-4">Application funnel</h2>
        <?php foreach ($STATUS as $key => $label): $n = $funnel[$key] ?? 0; ?>
          <div class="mb-3">
            <div class="flex justify-between text-sm"><span><?= h($label) ?></span><span class="font-semibold"><?= $n ?></span></div>
            <div class="bg-slate-100 rounded-full mt-1"><div class="bar" style="width: <?= max(2, (int)round(100 * $n / $maxFunnel)) ?>%"></div></div>
          </div>
        <?php endforeach; ?>
        <p class="text-xs text-slate-500 mt-2">Shows where families get stuck, so field workers know where to help.</p>
      </div>
      <div class="panel p-6">
        <h2 class="font-bold text-lg mb-1">Data freshness</h2>
        <p class="text-sm text-slate-500 mb-3">Rules not checked against the official source in 90 days are flagged.</p>
        <?php if (!$stale): ?>
          <p class="text-sm text-blue-600 font-semibold">✅ All schemes verified within the last 90 days.</p>
        <?php else: foreach ($stale as $s): ?>
          <p class="text-sm">⚠️ <?= h($s['name']) ?> · last verified <?= h($s['last_verified'] ?? 'never') ?></p>
        <?php endforeach; endif; ?>
      </div>
    </div>
  </section>

  <section class="panel p-6">
    <h2 class="font-bold text-lg mb-3">Upcoming deadlines</h2>
    <?php if (!$deadlines): ?><p class="text-sm text-slate-500">No deadlines in the next 180 days.</p><?php endif; ?>
    <ul class="divide-y divide-slate-100">
      <?php foreach ($deadlines as $d): $left = (int)$d['days_left']; ?>
        <li class="py-3 flex flex-wrap justify-between gap-2 text-sm">
          <span><b><?= h($d['name']) ?></b> · <?= h($d['label']) ?></span>
          <span class="rounded-full px-3 py-0.5 font-semibold <?= $left < 30 ? 'bg-red-100 text-red-700' : 'bg-blue-50 text-emerald-800' ?>"><?= h($d['deadline_date']) ?> · <?= $left ?> days</span>
        </li>
      <?php endforeach; ?>
    </ul>
  </section>

  <section class="panel p-6">
    <h2 class="font-bold text-lg mb-4">Who pays &amp; why</h2>
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
      <div class="rounded-2xl bg-blue-50 p-4"><p class="font-bold mb-1">Citizens · always free</p><p>No fees and no middlemen. Families keep the full benefit.</p></div>
      <div class="rounded-2xl bg-sky-50 p-4"><p class="font-bold mb-1">CSCs &amp; NGOs</p><p>License an assisted mode so one operator can help many families quickly.</p></div>
      <div class="rounded-2xl bg-amber-50 p-4"><p class="font-bold mb-1">CSR funds</p><p>Sponsor a district and receive an impact report of families helped.</p></div>
      <div class="rounded-2xl bg-indigo-50 p-4"><p class="font-bold mb-1">State departments</p><p>See which benefits go unclaimed, and where, to target outreach.</p></div>
    </div>
  </section>
</main>

<footer style="background:#0b0b0c" class="text-center text-xs text-white/70 py-6">
  HaqDar · Free service · Likely eligibility, never guaranteed · Final decision is by the scheme authority.
</footer>
</body>
</html>
