<?php
declare(strict_types=1);
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/config_loader.php';

session_start();
header('Content-Type: text/html; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');

function h($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

const OPERATORS = ['<=', '>=', '<', '>', '=', '!=', 'in', 'contains_any'];
const FIELDS = ['age', 'gender', 'category', 'family_income', 'education_level', 'course', 'institution_type', 'state', 'disability', 'marks_percent'];

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf'];
$password = (string)(app_config()['admin_password'] ?? '');
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($csrf, (string)($_POST['csrf'] ?? ''))) {
        http_response_code(400);
        exit('Invalid request.');
    }
    $action = (string)($_POST['action'] ?? '');
    if ($action === 'login') {
        if ($password !== '' && hash_equals($password, (string)($_POST['password'] ?? ''))) {
            session_regenerate_id(true);
            $_SESSION['admin'] = true;
        } else {
            $msg = 'Wrong password.';
        }
    } elseif ($action === 'logout') {
        $_SESSION = [];
        session_destroy();
        header('Location: admin.php');
        exit;
    } elseif (!empty($_SESSION['admin'])) {
        try {
            $pdo = db();
            $sid = (int)($_POST['scheme_id'] ?? 0);
            if ($action === 'verify') {
                $pdo->prepare('UPDATE schemes SET last_verified = CURDATE() WHERE id = ?')->execute([$sid]);
                $msg = 'Marked as verified today.';
            } elseif ($action === 'save_rule' || $action === 'add_rule') {
                $field = (string)($_POST['field'] ?? '');
                $op = (string)($_POST['operator'] ?? '');
                $value = trim((string)($_POST['value'] ?? ''));
                $label = trim((string)($_POST['label'] ?? ''));
                if (!in_array($field, FIELDS, true) || !in_array($op, OPERATORS, true) || $value === '' || $label === '') {
                    $msg = 'Please fill every rule field.';
                } elseif ($action === 'save_rule') {
                    $pdo->prepare('UPDATE scheme_rules SET field = ?, operator = ?, value = ?, label = ? WHERE id = ? AND scheme_id = ?')
                        ->execute([$field, $op, $value, $label, (int)($_POST['rule_id'] ?? 0), $sid]);
                    $msg = 'Rule saved. Results change immediately.';
                } else {
                    $pdo->prepare('INSERT INTO scheme_rules (scheme_id, field, operator, value, label, sort_order) VALUES (?, ?, ?, ?, ?, 99)')
                        ->execute([$sid, $field, $op, $value, $label]);
                    $msg = 'Rule added.';
                }
            } elseif ($action === 'delete_rule') {
                $pdo->prepare('DELETE FROM scheme_rules WHERE id = ? AND scheme_id = ?')->execute([(int)($_POST['rule_id'] ?? 0), $sid]);
                $msg = 'Rule deleted.';
            }
        } catch (Throwable $e) {
            error_log('admin.php: ' . $e->getMessage());
            $msg = 'Could not save. Please try again.';
        }
    }
}

$isAdmin = !empty($_SESSION['admin']);
$schemes = [];
$rulesBy = [];
if ($isAdmin) {
    try {
        $pdo = db();
        $schemes = $pdo->query('SELECT id, name, level, official_url, last_verified,
                                       (last_verified IS NULL OR last_verified < DATE_SUB(CURDATE(), INTERVAL 90 DAY)) AS stale
                                FROM schemes ORDER BY name')->fetchAll();
        foreach ($pdo->query('SELECT id, scheme_id, field, operator, value, label FROM scheme_rules ORDER BY scheme_id, sort_order, id')->fetchAll() as $r) {
            $rulesBy[(int)$r['scheme_id']][] = $r;
        }
    } catch (Throwable $e) {
        error_log('admin.php: ' . $e->getMessage());
        $msg = 'Database unavailable.';
    }
}

function rule_inputs(array $r = []): string
{
    $f = '<select name="field" class="inp">';
    foreach (FIELDS as $x) { $f .= '<option' . (($r['field'] ?? '') === $x ? ' selected' : '') . '>' . h($x) . '</option>'; }
    $o = '<select name="operator" class="inp">';
    foreach (OPERATORS as $x) { $o .= '<option value="' . h($x) . '"' . (($r['operator'] ?? '') === $x ? ' selected' : '') . '>' . h($x) . '</option>'; }
    return $f . '</select>' . $o . '</select>'
        . '<input name="value" class="inp" placeholder="value" value="' . h($r['value'] ?? '') . '">'
        . '<input name="label" class="inp flex-1 min-w-[12rem]" placeholder="Label shown to students" value="' . h($r['label'] ?? '') . '">';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<title>HaqDar · Rules admin</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="theme.css">
<style>
  body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
  .glass { background: rgba(255,255,255,.85); border: 1px solid #e2e8f0; border-radius: 1.25rem; box-shadow: 0 8px 24px -12px rgba(5,150,105,.3); }
  .inp { border: 1px solid #cbd5e1; border-radius: .6rem; padding: .45rem .6rem; font-size: .9rem; background: #fff; }
  .btn { border-radius: .6rem; padding: .45rem .9rem; font-weight: 600; font-size: .9rem; }
</style>
</head>
<body class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-cyan-50 text-slate-800">
<header class="bg-gradient-to-r from-emerald-700 via-teal-600 to-cyan-600 text-white">
  <div class="max-w-5xl mx-auto px-4 py-4 flex items-center justify-between gap-3">
    <div><p class="text-xl font-extrabold">HaqDar · Rules admin</p>
      <p class="text-sm opacity-90">Scheme rules are data — edit them here when governments change them.</p></div>
    <?php if ($isAdmin): ?>
      <form method="post"><input type="hidden" name="csrf" value="<?= h($csrf) ?>"><input type="hidden" name="action" value="logout">
        <button class="btn bg-white/20 hover:bg-white/30">Log out</button></form>
    <?php endif; ?>
  </div>
</header>

<main class="max-w-5xl mx-auto px-4 py-6 space-y-5">
  <?php if ($msg): ?><div class="glass p-3 text-sm font-semibold text-emerald-800"><?= h($msg) ?></div><?php endif; ?>

  <?php if (!$isAdmin): ?>
    <form method="post" class="glass p-6 max-w-sm mx-auto space-y-3">
      <input type="hidden" name="csrf" value="<?= h($csrf) ?>"><input type="hidden" name="action" value="login">
      <h1 class="font-bold text-lg">Admin login</h1>
      <?php if ($password === ''): ?><p class="text-sm text-amber-700">Set 'admin_password' in config.php to enable login.</p><?php endif; ?>
      <input type="password" name="password" class="inp w-full" placeholder="Password" required>
      <button class="btn w-full bg-emerald-600 text-white">Log in</button>
      <a href="index.php" class="block text-center text-sm text-emerald-700 underline">Back to app</a>
    </form>
  <?php else: foreach ($schemes as $s): $id = (int)$s['id']; ?>
    <section class="glass p-5">
      <div class="flex flex-wrap items-center justify-between gap-2">
        <div>
          <h2 class="font-bold"><?= h($s['name']) ?> <span class="text-xs text-slate-500">(<?= h($s['level']) ?>)</span></h2>
          <p class="text-xs text-slate-500">Last verified: <?= h($s['last_verified'] ?? 'never') ?>
            <?php if ((int)$s['stale']): ?><span class="ml-1 rounded-full bg-red-100 text-red-700 px-2 py-0.5 font-semibold">Needs re-verification</span><?php endif; ?>
            <?php if ($s['official_url']): ?> · <a class="text-emerald-700 underline" target="_blank" rel="noopener" href="<?= h($s['official_url']) ?>">Official source</a><?php endif; ?></p>
        </div>
        <form method="post"><input type="hidden" name="csrf" value="<?= h($csrf) ?>"><input type="hidden" name="action" value="verify"><input type="hidden" name="scheme_id" value="<?= $id ?>">
          <button class="btn bg-emerald-600 text-white">✓ Mark verified today</button></form>
      </div>
      <div class="mt-3 space-y-2">
        <?php foreach ($rulesBy[$id] ?? [] as $r): ?>
          <form method="post" class="flex flex-wrap gap-2 items-center">
            <input type="hidden" name="csrf" value="<?= h($csrf) ?>"><input type="hidden" name="scheme_id" value="<?= $id ?>"><input type="hidden" name="rule_id" value="<?= (int)$r['id'] ?>">
            <?= rule_inputs($r) ?>
            <button name="action" value="save_rule" class="btn bg-slate-800 text-white">Save</button>
            <button name="action" value="delete_rule" class="btn bg-red-50 text-red-700" onclick="return confirm('Delete this rule?')">Delete</button>
          </form>
        <?php endforeach; ?>
        <form method="post" class="flex flex-wrap gap-2 items-center border-t border-dashed border-slate-300 pt-2">
          <input type="hidden" name="csrf" value="<?= h($csrf) ?>"><input type="hidden" name="scheme_id" value="<?= $id ?>">
          <?= rule_inputs() ?>
          <button name="action" value="add_rule" class="btn bg-emerald-100 text-emerald-800">+ Add rule</button>
        </form>
      </div>
    </section>
  <?php endforeach; endif; ?>
</main>
</body>
</html>
