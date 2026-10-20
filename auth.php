
<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function requireLogin(): void
{
    if (
        !isset($_SESSION['user_id']) ||
        !isset($_SESSION['role'])
    ) {
        header('Location: ../login.php');
        exit;
    }
}

function requireRole(int $requiredRole): void
{
    requireLogin();

    if ((int) $_SESSION['role'] !== $requiredRole) {
        http_response_code(403);
        exit('403 Forbidden: Anda tidak memiliki akses.');
    }
}
