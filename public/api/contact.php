<?php

require __DIR__ . '/../../app/bootstrap.php';

$baseUrl = rtrim($config['site']['base_url'] ?? '', '/');
$homeUrl = $baseUrl === '' ? '/' : $baseUrl . '/';
$form = $_POST['form'] ?? 'contact';
$form = in_array($form, ['contact', 'investor'], true) ? $form : 'contact';
$sentUrl = $homeUrl . '?sent=1&form=' . $form;
$errorUrl = $homeUrl . '?error=1&form=' . $form;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

if (!verify_csrf($_POST['csrf_token'] ?? '')) {
    header('Location: ' . $errorUrl);
    exit;
}

$honeypot = trim($_POST['website'] ?? '');
if ($honeypot !== '') {
    header('Location: ' . $sentUrl);
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$inquiry = trim($_POST['inquiry'] ?? '');
$message = trim($_POST['message'] ?? '');
$investorType = trim($_POST['investor_type'] ?? '');

if ($name === '' || $email === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ' . $errorUrl);
    exit;
}

if ($inquiry === '' && $form === 'investor') {
    $inquiry = 'Investment';
}

$storedMessage = $message;
if ($investorType !== '') {
    $storedMessage = "Investor type: {$investorType}\n" . $storedMessage;
}

if ($pdo) {
    try {
        $stmt = $pdo->prepare(
            'INSERT INTO inquiries (name, email, inquiry, message, ip) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$name, $email, $inquiry, $storedMessage, $_SERVER['REMOTE_ADDR'] ?? '']);
    } catch (Throwable $e) {
        // DB save failure is non-fatal; still attempt email
    }
}

$to = $config['mail']['to'] ?? 'connect@truelaunch.space';
if ($form === 'investor') {
    $to = $config['mail']['to_investor'] ?? $to;
} else {
    $to = $config['mail']['to_contact'] ?? $to;
}
$from = $config['mail']['from'] ?? $to;
$subject = 'True Launch inquiry: ' . ($inquiry !== '' ? $inquiry : 'General');
$body = "Name: {$name}\nEmail: {$email}\n";
if ($investorType !== '') {
    $body .= "Investor type: {$investorType}\n";
}
$body .= "Inquiry: {$inquiry}\n\n{$message}\n";
$headers = "From: {$from}\r\nReply-To: {$email}\r\n";

mail($to, $subject, $body, $headers);

header('Location: ' . $sentUrl);
exit;
