<?php

require __DIR__ . '/../../app/bootstrap.php';

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid session token.';
    } else {
        $user = trim($_POST['user'] ?? '');
        $pass = $_POST['pass'] ?? '';

        if (login_admin($user, $pass, $config)) {
            header('Location: dashboard.php');
            exit;
        }
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in — True Launch</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;600&family=Sora:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="login-wrap">
        <div class="login-box">
            <div class="login-brand">
                <img src="../assets/images/logo-white.png" alt="True Launch">
            </div>
            <div class="login-card">
                <div class="login-title">Admin Sign In</div>
                <?php if ($error) : ?>
                    <div class="status-bar error" style="margin-bottom:20px;"><?php echo e($error); ?></div>
                <?php endif; ?>
                <form class="login-form" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
                    <div class="field">
                        <label for="user">Username</label>
                        <input id="user" name="user" type="text" autocomplete="username" required autofocus>
                    </div>
                    <div class="field">
                        <label for="pass">Password</label>
                        <input id="pass" name="pass" type="password" autocomplete="current-password" required>
                    </div>
                    <button class="btn-save" type="submit" style="width:100%;margin-top:4px;">Sign In</button>
                </form>
            </div>
            <div class="login-footer">True Launch &mdash; Admin Panel</div>
        </div>
    </div>
</body>
</html>
