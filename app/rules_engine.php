<?php
declare(strict_types=1);

/**
 * Evaluates one rule against one profile value. Deterministic, no LLM.
 * Returns 'pass', 'fail' or 'unknown'.
 */
function eval_rule(string $operator, string $ruleValue, mixed $userValue): string
{
    if ($userValue === null || $userValue === '') {
        return 'unknown';
    }
    $u = is_bool($userValue) ? ($userValue ? 'true' : 'false') : trim((string)$userValue);
    $r = trim($ruleValue);
    $list = array_map('trim', explode(',', $r));
    $lower = strtolower($u);

    switch ($operator) {
        case '=':
            return strcasecmp($u, $r) === 0 ? 'pass' : 'fail';
        case '!=':
            return strcasecmp($u, $r) !== 0 ? 'pass' : 'fail';
        case '<':
        case '<=':
        case '>':
        case '>=':
            if (!is_numeric($u) || !is_numeric($r)) {
                return 'unknown';
            }
            $a = (float)$u;
            $b = (float)$r;
            $ok = match ($operator) {
                '<' => $a < $b,
                '<=' => $a <= $b,
                '>' => $a > $b,
                default => $a >= $b,
            };
            return $ok ? 'pass' : 'fail';
        case 'in':
            foreach ($list as $item) {
                if (strcasecmp($u, $item) === 0) {
                    return 'pass';
                }
            }
            return 'fail';
        case 'contains_any':
            foreach ($list as $item) {
                if ($item !== '' && str_contains($lower, strtolower($item))) {
                    return 'pass';
                }
            }
            return 'fail';
        default:
            error_log('rules_engine.php: unknown operator ' . $operator);
            return 'unknown';
    }
}

/**
 * Evaluates all rules of one scheme.
 * Status: any fail => 'no'; any unknown (or no rules) => 'maybe'; otherwise 'likely'.
 */
function evaluate_scheme(array $rules, array $profile): array
{
    $results = [];
    $hasFail = false;
    $hasUnknown = count($rules) === 0;
    foreach ($rules as $rule) {
        $userValue = $profile[$rule['field']] ?? null;
        $res = eval_rule((string)$rule['operator'], (string)$rule['value'], $userValue);
        if ($res === 'fail') {
            $hasFail = true;
        } elseif ($res === 'unknown') {
            $hasUnknown = true;
        }
        $results[] = [
            'field' => $rule['field'],
            'label' => $rule['label'],
            'operator' => $rule['operator'],
            'required_value' => $rule['value'],
            'user_value' => $userValue,
            'result' => $res,
        ];
    }
    $status = $hasFail ? 'no' : ($hasUnknown ? 'maybe' : 'likely');
    return ['status' => $status, 'rules' => $results];
}
