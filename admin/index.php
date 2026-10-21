
<?php
session_start();

// Proteksi halaman admin
if (!isset($_SESSION['user_id'], $_SESSION['role'])) {
    header('Location: ../login.php');
    exit;
}

if ((int) $_SESSION['role'] !== 1) {
    header('Location: ../login.php');
    exit;
}
?>


<?php
require_once __DIR__ . '/../koneksi.php';

try {
    // Hitung total stok seluruh barang
    $query_stok = $pdo->query(
        "SELECT COALESCE(SUM(stok), 0) AS total_stok
         FROM barang"
    );

    $data_stok = $query_stok->fetch();
    $total_stok = (int) $data_stok['total_stok'];

} catch (PDOException $e) {
    error_log($e->getMessage());
    $total_stok = 0;
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin - Inventaris Gudang</title>

    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="admin-container">

    <!-- HEADER -->
    <header class="admin-header">

        <div class="admin-brand">
            <div class="brand-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 7.5 12 3l8 4.5V21H4V7.5Z"/>
                    <path d="M8 10h8M8 14h8M8 18h8"/>
                </svg>
            </div>

            <div class="brand-text">
                <h1>INVENTARIS GUDANG PT. XYZ</h1>
                <p>Manajemen Data Barang</p>
            </div>
        </div>

        <button type="button"
                class="notification-button"
                aria-label="Notifikasi">

            <svg viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.8"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
                <path d="M10 21h4"/>
            </svg>

            <span class="notification-dot"></span>
        </button>

    </header>


<!-- LAYOUT DASHBOARD -->
<div class="admin-layout">

    <!-- SIDEBAR -->
    <aside class="admin-sidebar">

        <!-- MENU SIDEBAR -->
        <nav class="sidebar-nav" aria-label="Menu admin">

            <!-- DASHBOARD -->
            <a href="index.php"
               class="nav-link active"
               aria-current="page">

                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7" rx="1"/>
                    <rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                    <rect x="14" y="14" width="7" height="7" rx="1"/>
                </svg>

                <span>Dashboard</span>
            </a>

            <!-- DATA BARANG -->
            <a href="data_barang.php" class="nav-link">

                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 3 9 5-9 5-9-5 9-5Z"/>
                    <path d="m3 8 9 5 9-5"/>
                    <path d="M3 8v9l9 5 9-5V8"/>
                    <path d="M12 13v9"/>
                </svg>

                <span>Data Barang</span>
            </a>

            <!-- DATA PENGAJUAN BARANG -->
            <a href="pengajuan.php" class="nav-link">

                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round">
                    <rect x="5" y="4" width="14" height="18" rx="2"/>
                    <path d="M9 4V2h6v2"/>
                    <path d="M9 10h6M9 14h6M9 18h3"/>
                </svg>

                <span>Pengajuan Pengambilan Barang</span>
            </a>

            <!-- DATA PENGGUNA -->
            <a href="data_pengguna.php" class="nav-link">

                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="8" r="4"/>
                    <path d="M2 21v-2a7 7 0 0 1 14 0v2"/>
                    <path d="M16 4a4 4 0 0 1 0 8"/>
                    <path d="M22 21v-2a7 7 0 0 0-5-6.7"/>
                </svg>

                <span>Data Pengguna</span>
            </a>

            <!-- PEMISAH MENU BAWAH -->
            <div class="sidebar-divider"></div>

            <!-- RIWAYAT -->
            <a href="riwayat.php" class="nav-link">

                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/>
                    <path d="M3 3v5h5"/>
                    <path d="M12 7v5l3 2"/>
                </svg>

                <span>Riwayat</span>
            </a>

            <!-- LOGOUT -->
            <a href="../logout.php" class="nav-link logout-link">

                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10 17l5-5-5-5"/>
                    <path d="M15 12H3"/>
                    <path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"/>
                </svg>

                <span>Logout</span>
            </a>

        </nav>

    </aside>

    <!-- KONTEN UTAMA -->
    <main class="admin-main">

        <!-- SAPAAN ADMIN -->
        <section class="welcome-section">
            <h2>Dashboard Admin</h2>
            <p>Selamat datang, Admin Gudang!</p>
        </section>
            
<!-- KARTU RINGKASAN -->
<section class="summary-grid" aria-label="Ringkasan inventaris">

    <!-- KARTU STOK GUDANG -->
<article class="summary-card">

    <div class="summary-icon">
        <svg viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="1.8"
             stroke-linecap="round" stroke-linejoin="round">
            <path d="m12 3 9 5-9 5-9-5 9-5Z"/>
            <path d="m3 8 9 5 9-5"/>
            <path d="M3 8v9l9 5 9-5V8"/>
            <path d="M12 13v9"/>
        </svg>
    </div>

    <h3>Stok Gudang</h3>

    <p class="summary-value">
        <?= number_format((float) $total_stok, 0, ',', '.') ?>
    </p>

</article>

    <!-- KARTU DATA PENGAJUAN BARANG -->
    <article class="summary-card">
        <div class="summary-icon">
            <svg viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.8"
                 stroke-linecap="round" stroke-linejoin="round">
                <rect x="5" y="4" width="14" height="18" rx="2"/>
                <path d="M9 4V2h6v2"/>
                <path d="M9 10h6M9 14h6M9 18h4"/>
            </svg>
        </div>
        <h3>Data Pengajuan Barang</h3>
        <p class="summary-value">—</p>
    </article>

    <!-- KARTU DATA PENGGUNA -->
    <article class="summary-card">
        <div class="summary-icon">
            <svg viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.8"
                 stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="8" r="4"/>
                <path d="M2 21v-2a7 7 0 0 1 14 0v2"/>
                <path d="M16 4a4 4 0 0 1 0 8"/>
                <path d="M22 21v-2a7 7 0 0 0-5-6.7"/>
            </svg>
        </div>
        <h3>Data Pengguna</h3>
        <p class="summary-value">—</p>
    </article>

    <!-- KARTU HISTORY -->
    <article class="summary-card">
        <div class="summary-icon">
            <svg viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.8"
                 stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="9"/>
                <path d="M12 7v5l3 2"/>
                <path d="M3 4v5h5"/>
            </svg>
        </div>
        <h3>History</h3>
        <p class="summary-value">—</p>
    </article>

</section>


        <!-- MENU ADMIN -->
        <section class="admin-menu-card">

            <div class="menu-heading">
                <h2>Menu Admin</h2>
                <p>Pilih menu untuk mengelola inventaris gudang.</p>
            </div>

            <div class="menu-list">

                <!-- KELOLA BARANG -->
                <a href="data_barang.php" class="menu-item">

                    <span class="menu-item-icon">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="m12 3 9 5-9 5-9-5 9-5Z"/>
                            <path d="m3 8 9 5 9-5"/>
                            <path d="M3 8v9l9 5 9-5V8"/>
                            <path d="M12 13v9"/>
                        </svg>
                    </span>

                    <span class="menu-item-text">
                        Kelola data barang
                    </span>

                    <svg class="menu-chevron"
                         viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>

                </a>

                <!-- PERIKSA PENGAJUAN -->
                <a href="pengajuan.php" class="menu-item">

                    <span class="menu-item-icon">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <rect x="5" y="4" width="14" height="18" rx="2"/>
                            <path d="M9 4V2h6v2"/>
                            <path d="m9 13 2 2 4-4"/>
                        </svg>
                    </span>

                    <span class="menu-item-text">
                        Periksa pengajuan pengambilan barang
                    </span>

                    <svg class="menu-chevron"
                         viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>

                </a>

            </div>

        </section>

    </main>

</div>


</div>

</body>
</html>
