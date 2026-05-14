<?php

declare(strict_types=1);

function merge_content(array $base, array $override): array
{
    foreach ($override as $key => $value) {
        if (is_array($value) && isset($base[$key]) && is_array($base[$key]) && array_is_list($value)) {
            $base[$key] = $value;
        } elseif (is_array($value) && isset($base[$key]) && is_array($base[$key])) {
            $base[$key] = merge_content($base[$key], $value);
        } else {
            $base[$key] = $value;
        }
    }
    return $base;
}

function get_site_content(PDO $pdo, array $defaults): array
{
    $content = $defaults;
    $stmt = $pdo->query('SELECT content_key, content_json FROM site_content');

    foreach ($stmt->fetchAll() as $row) {
        $data = json_decode($row['content_json'], true);
        if (is_array($data)) {
            $existing = $content[$row['content_key']] ?? [];
            $content[$row['content_key']] = merge_content($existing, $data);
        }
    }

    return $content;
}

function save_site_content(PDO $pdo, string $key, array $data): void
{
    $json = json_encode($data, JSON_UNESCAPED_SLASHES);

    $stmt = $pdo->prepare(
        'INSERT INTO site_content (content_key, content_json)
         VALUES (:key, :json)
         ON DUPLICATE KEY UPDATE content_json = VALUES(content_json)'
    );

    $stmt->execute([
        ':key' => $key,
        ':json' => $json,
    ]);
}
