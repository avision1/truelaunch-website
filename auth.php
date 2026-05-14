<?php

declare(strict_types=1);

function is_logged_in(): bool
{
    return !empty($_SESSION['admin_logged_in']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: index.php');
        exit;
    }
}

function login_admin(string $user, string $pass, array $config): bool
{
    $admin = $config['admin'] ?? [];
    $validUser = isset($admin['user']) && hash_equals((string)$admin['user'], $user);
    $validPass = isset($admin['pass_hash']) && password_verify($pass, (string)$admin['pass_hash']);

    if ($validUser && $validPass) {
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        return true;
    }

    return false;
}

function logout_admin(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
    session_destroy();
}
