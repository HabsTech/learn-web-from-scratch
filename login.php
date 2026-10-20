
<?php
session_start();

// Jika sudah login, arahkan sesuai role
if (isset($_SESSION['user_id'], $_SESSION['role'])) {
    if ($_SESSION['role'] === 1) {
        header('Location: admin/index.php');
        exit;
    }

    if ($_SESSION['role'] === 2) {
        header('Location: kepala_divisi/pengajuan.php');
        exit;
    }
}

$error = $_GET['error'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventaris Gudang</title>
</head>
<body>
    <main>
        <h1>Inventaris Gudang</h1>
        <h2>Login</h2>

        <?php if ($error === 'invalid'): ?>
            <p style="color: red;">
                Nomor telepon atau password salah.
            </p>
        <?php elseif ($error === 'role'): ?>
            <p style="color: red;">
                Role akun tidak dikenali.
            </p>
        <?php endif; ?>

        <form action="proses_login.php" method="POST">
            <div>
                <label for="no_telp">Nomor Telepon</label>
                <input
                    type="tel"
                    id="no_telp"
                    name="no_telp"
                    required
                    autocomplete="username"
                >
            </div>

            <div>
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    autocomplete="current-password"
                >
            </div>

            <button type="submit">Login</button>
        </form>
    </main>
</body>
</html>
