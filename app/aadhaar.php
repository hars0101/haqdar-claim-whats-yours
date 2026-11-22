<?php
declare(strict_types=1);

/**
 * Finds 12-digit Aadhaar numbers (optionally grouped by spaces or dashes)
 * and removes them. Returns [cleanText, wasBlocked].
 */
function strip_aadhaar(string $text): array
{
    $pattern = '/(?<!\d)\d{4}[\s-]?\d{4}[\s-]?\d{4}(?!\d)/u';
    $clean = preg_replace($pattern, '[AADHAAR REMOVED]', $text, -1, $count);
    if ($clean === null) {
        // Regex failure: be safe and block the whole text.
        error_log('aadhaar.php: preg_replace failed');
        return ['[AADHAAR REMOVED]', true];
    }
    return [$clean, $count > 0];
}
