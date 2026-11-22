<?php
declare(strict_types=1);
require_once __DIR__ . '/../app/db.php';

/** Escape any value for safe HTML output. */
function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/** Render one horizontal bar row. Width is a percentage of $max (min 2% when value > 0). */
function render_bar(string $label, int $value, int $max): void
{
    $pct = $max > 0 ? (int)round($value / $max * 100) : 0;
    if ($value > 0 && $pct < 2) {
        $pct = 2;
    }
    echo '<div class="mb-2">';
    echo '<div class="flex justify-between text-sm mb-1"><span>' . e($label) . '</span><span class="font-semibold">' . e($value) . '</span></div>';
    echo '<div class="w-full bg-gray-200 rounded h-4"><div class="bg-teal-600 h-4 rounded" style="width: ' . $pct . '%"></div></div>';
    echo '</div>';
}

$total = 0;
$perScheme = [];
$funnel = [];
$deadlines = [];
$stale = [];
$statuses = ['not_started', 'documents_ready', 'applied', 'under_verification', 'received'];
$ok = true;

try {
    $pdo = db();

    $stmt = $pdo->prepare('SELECT COUNT(*) AS c FROM applications');
    $stmt->execute();
    $row = $stmt->fetch();
    $total = (int)($row['c'] ?? 0);

    $stmt = $pdo->prepare(
        'SELECT s.name, COUNT(a.id) AS n FROM schemes s
         LEFT JOIN applications a ON a.scheme_id = s.id
         GROUP BY s.id, s.name ORDER BY n DESC'
    );
    $stmt->execute();
    $perScheme = $stmt->fetchAll();

    $stmt = $pdo->prepare('SELECT status, COUNT(*) AS n FROM applications GROUP BY status');
    $stmt->execute();
    $counts = [];
    foreach ($stmt->fetchAll() as $r) {
        $counts[(string)$r['status']] = (int)$r['n'];
    }
    foreach ($statuses as $st) {
        $funnel[$st] = $counts[$st] ?? 0;
    }

    $stmt = $pdo->prepare(
        'SELECT s.name, d.label, d.deadline_date FROM scheme_deadlines d
         JOIN schemes s ON s.id = d.scheme_id
         WHERE d.deadline_date BETWEEN CURDATE() AND CURDATE() + INTERVAL 90 DAY
         ORDER BY d.deadline_date'
    );
    $stmt->execute();
    $deadlines = $stmt->fetchAll();

    $stmt = $pdo->prepare(
        'SELECT name, last_verified FROM schemes
         WHERE last_verified IS NULL OR last_verified < CURDATE() - INTERVAL 90 DAY
         ORDER BY last_verified'
    );
    $stmt->execute();
    $stale = $stmt->fetchAll();
} catch (Throwable $e) {
    error_log('insights.php: ' . $e->getMessage());
    $ok = false;
}

$maxScheme = 0;
foreach ($perScheme as $r) {
    $maxScheme = max($maxScheme, (int)$r['n']);
}
$maxFunnel = $funnel ? max($funnel) : 0;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Partner dashboard — HaqDar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="theme.css">
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen">
<main class="max-w-3xl mx-auto p-6 space-y-8">

    <header class="flex flex-wrap items-center justify-between gap-2">
        <h1 class="text-2xl font-bold">Partner dashboard — for NGOs, CSCs &amp; State Govts</h1>
        <a href="index.php" class="text-teal-700 underline">Back to HaqDar</a>
    </header>
    <p class="text-sm text-gray-600">Anonymous, aggregated figures only. No personal details are shown on this page.</p>

<?php if (!$ok): ?>
    <section class="bg-yellow-50 border border-yellow-300 rounded p-4">
        <p>Insights are temporarily unavailable. Please try again later.</p>
    </section>
<?php else: ?>

    <section class="bg-white rounded shadow p-5">
        <h2 class="text-lg font-semibold mb-2">Total tracked applications</h2>
        <p class="text-4xl font-bold text-teal-700"><?= e($total) ?></p>
    </section>

    <section class="bg-white rounded shadow p-5">
        <h2 class="text-lg font-semibold mb-3">Applications per scheme</h2>
        <?php if (!$perScheme): ?>
            <p class="text-sm text-gray-600">No schemes found.</p>
        <?php endif; ?>
        <?php foreach ($perScheme as $r): ?>
            <?php render_bar((string)$r['name'], (int)$r['n'], $maxScheme); ?>
        <?php endforeach; ?>
    </section>

    <section class="bg-white rounded shadow p-5">
        <h2 class="text-lg font-semibold mb-3">Application funnel by status</h2>
        <?php foreach ($funnel as $st => $n): ?>
            <?php render_bar(str_replace('_', ' ', $st), $n, $maxFunnel); ?>
        <?php endforeach; ?>
    </section>

    <section class="bg-white rounded shadow p-5">
        <h2 class="text-lg font-semibold mb-3">Deadlines in the next 90 days</h2>
        <?php if (!$deadlines): ?>
            <p class="text-sm text-gray-600">No deadlines in the next 90 days.</p>
        <?php else: ?>
            <ul class="divide-y">
            <?php foreach ($deadlines as $d): ?>
                <li class="py-2 flex justify-between gap-4 text-sm">
                    <span><?= e($d['name']) ?> — <?= e($d['label']) ?></span>
                    <span class="font-semibold whitespace-nowrap"><?= e($d['deadline_date']) ?></span>
                </li>
            <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>

    <section class="bg-white rounded shadow p-5">
        <h2 class="text-lg font-semibold mb-1">Schemes needing re-verification</h2>
        <p class="text-xs text-gray-600 mb-3">Schemes not verified in the last 90 days. A scheme with no verification date (NULL) is treated as needing re-verification.</p>
        <?php if (!$stale): ?>
            <p class="text-sm text-gray-600">All schemes were verified recently.</p>
        <?php else: ?>
            <ul class="divide-y">
            <?php foreach ($stale as $s): ?>
                <li class="py-2 flex justify-between gap-4 text-sm">
                    <span><?= e($s['name']) ?></span>
                    <span class="whitespace-nowrap"><?= $s['last_verified'] === null ? 'never verified' : e($s['last_verified']) ?></span>
                </li>
            <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>

<?php endif; ?>

    <section class="bg-white rounded shadow p-5">
        <h2 class="text-lg font-semibold mb-3">Who pays &amp; why</h2>
        <ul class="list-disc pl-5 space-y-1 text-sm">
            <li>Free for citizens: checking eligibility and tracking applications costs nothing.</li>
            <li>CSCs, MeeSeva centres &amp; NGOs license an assisted mode to serve applicants.</li>
            <li>CSR funds sponsor districts, covering outreach and assisted application support.</li>
            <li>State departments get &quot;unclaimed benefits&quot; insights to target enrolment.</li>
        </ul>
    </section>

</main>
</body>
</html>
