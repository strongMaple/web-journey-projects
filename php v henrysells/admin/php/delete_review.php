<?php
require_once __DIR__ . '/../../config/strongMaple_config.php';
require_once __DIR__ . '/auth.php';

/* POST only */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

/* Admin only */
if (!is_admin_logged_in()) {
    http_response_code(403);
    exit('Unauthorized');
}

/* CSRF check */
if (
    empty($_POST['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    http_response_code(403);
    exit('Invalid CSRF token');
}

/* Validate ID */
$reviewId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$reviewId) {
    http_response_code(400);
    exit('Invalid review ID');
}

/* Delete */
$stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ? LIMIT 1");
$stmt->execute([$reviewId]);

/* Back to dashboard */
header("Location: dashboard.php");
exit;
