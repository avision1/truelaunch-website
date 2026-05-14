<?php
require __DIR__ . '/../../app/bootstrap.php';
require_login();

if ($pdo) {
    $pdo->prepare('DELETE FROM site_content WHERE content_key = ?')->execute(['roadmap']);
    echo 'Roadmap DB entry cleared. <a href="/admin/dashboard.php">Back to dashboard</a>';
} else {
    echo 'No DB connection.';
}
