
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
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="login-container">

        <!-- Header -->
        <header class="header">
            <div class="header-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 7.5 12 3l8 4.5V21H4V7.5Z"/>
                    <path d="M9 21v-5h6v5"/>
                    <path d="M8 10h8M8 13h8"/>
                </svg>
            </div>

            <div class="header-text">
                <h1>INVENTARIS GUDANG PT. XYZ</h1>
                <p>Sistem Manajemen Persediaan Barang</p>
            </div>
        </header>

        <!-- Konten utama -->
        <main class="main-content">

            <section class="login-card" aria-labelledby="login-title">

                <div class="login-heading">
                    <h2 id="login-title">Selamat Datang</h2>
                    <p>Silakan masuk ke akun Anda</p>
                </div>

                <?php if ($error === 'invalid'): ?>
                    <p class="error-message" role="alert">
                        Nomor telepon atau password salah.
                    </p>
                <?php elseif ($error === 'role'): ?>
                    <p class="error-message" role="alert">
                        Role akun tidak dikenali.
                    </p>
                <?php endif; ?>

                <form action="proses_login.php" method="POST">

                    <div class="form-group">
                        <label for="no_telp">Nomor Telepon</label>

                        <div class="input-wrapper">
                            <svg class="input-icon"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="1.8"
                                 stroke-linecap="round"
                                 stroke-linejoin="round"
                                 aria-hidden="true">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2
                                    19.8 19.8 0 0 1-8.63-3.07
                                    19.5 19.5 0 0 1-6-6
                                    19.8 19.8 0 0 1-3.07-8.67
                                    A2 2 0 0 1 4.11 2h3
                                    a2 2 0 0 1 2 1.72
                                    c.12.96.36 1.9.7 2.79
                                    a2 2 0 0 1-.45 2.11L8.09 9.91
                                    a16 16 0 0 0 6 6l1.27-1.27
                                    a2 2 0 0 1 2.11-.45
                                    c.89.34 1.83.58 2.79.7
                                    A2 2 0 0 1 22 16.92Z"/>
                            </svg>

                            <input
                                type="tel"
                                id="no_telp"
                                name="no_telp"
                                placeholder="Masukkan nomor telepon"
                                required
                                autocomplete="username"
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>

                        <div class="input-wrapper">
                            <svg class="input-icon"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="1.8"
                                 stroke-linecap="round"
                                 stroke-linejoin="round"
                                 aria-hidden="true">
                                <rect x="4" y="10" width="16" height="11" rx="2"/>
                                <path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                                <circle cx="12" cy="15" r="1"/>
                                <path d="M12 16v2"/>
                            </svg>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Masukkan password"
                                required
                                autocomplete="current-password"
                            >
                        </div>
                    </div>

                    <button type="submit" class="login-button">
                        Login
                    </button>

                </form>

                <footer class="card-footer">
                    Sistem Inventaris Gudang
                </footer>

            </section>

        </main>

    </div>

</body>
</html>
