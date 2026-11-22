<?php
declare(strict_types=1);

/** Escapes text for RFC 5545 iCalendar values. */
function ics_escape(string $s): string
{
    $s = str_replace(['\\', ';', ',', "\r\n", "\n", "\r"], ['\\\\', '\;', '\,', '\n', '\n', '\n'], $s);
    return $s;
}
