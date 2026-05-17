<?php

declare(strict_types=1);

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function url_for(string $path, string $baseUrl = ''): string
{
    $base = rtrim($baseUrl, '/');
    $path = ltrim($path, '/');

    if ($base === '') {
        return $path;
    }

    return $base . '/' . $path;
}

function clean_list(string $value): array
{
    $items = array_map('trim', explode(',', $value));
    return array_values(array_filter($items, static fn($item) => $item !== ''));
}
