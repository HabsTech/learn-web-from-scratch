
<?php
session_start();

if (!isset($_SESSION['user_id'], $_SESSION['role'])) {
    header('Location: ../login.php');
    exit;
}

if ((int) $_SESSION['role'] !== 1) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../koneksi.php';

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// Pencarian dan filter
$keyword = trim($_GET['keyword'] ?? '');
$jenis = trim($_GET['jenis'] ?? '');
$brand = trim($_GET['brand'] ?? '');

$sql = "SELECT * FROM barang WHERE 1=1";
$params = [];

if ($keyword !== '') {
    $sql .= " AND (
        nama_barang LIKE :keyword
        OR jenis_barang LIKE :keyword
        OR brand LIKE :keyword
        OR nomor_seri LIKE :keyword
        OR lokasi LIKE :keyword
    )";
    $params['keyword'] = '%' . $keyword . '%';
}

if ($jenis !== '') {
    $sql .= " AND jenis_barang = :jenis";
    $params['jenis'] = $jenis;
}

if ($brand !== '') {
    $sql .= " AND brand = :brand";
    $params['brand'] = $brand;
}

$sql .= " ORDER BY id_barang DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$barangList = $stmt->fetchAll();

// Pilihan filter dari database
$jenisList = $pdo->query(
    "SELECT DISTINCT jenis_barang
     FROM barang ORDER BY jenis_barang"
)->fetchAll(PDO::FETCH_COLUMN);

$brandList = $pdo->query(
    "SELECT DISTINCT brand
     FROM barang ORDER BY brand"
)->fetchAll(PDO::FETCH_COLUMN);

// Total hasil dan halaman
$totalBarang = count($barangList);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Barang - Inventaris Gudang</title>

    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="barang.css">
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

    <div class="admin-layout">

        <!-- SIDEBAR -->
    <aside class="admin-sidebar">

        <!-- MENU SIDEBAR -->
        <nav class="sidebar-nav" aria-label="Menu admin">

            <!-- DASHBOARD -->
            <a href="index.php"
               class="nav-link"
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
            <a href="data_barang.php" class="nav-link active">

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

        <!-- KONTEN DATA BARANG -->
        <main class="admin-main">

            <section class="page-heading">
                <div>
                    <h2>Data Barang</h2>
                    <p>Kelola informasi detail setiap barang dalam inventaris gudang.</p>
                </div>

                <a href="tambah_barang.php" class="add-button">
                    <span aria-hidden="true">＋</span>
                    Tambah Barang
                </a>
            </section>

            <!-- PENCARIAN DAN FILTER -->
            <form method="GET" action="data_barang.php"
                  class="filter-form">

                <div class="search-box">
                    <label for="keyword" class="sr-only">
                        Cari barang
                    </label>
                    <input
                        type="search"
                        id="keyword"
                        name="keyword"
                        placeholder="Cari nama barang, jenis, brand, nomor seri..."
                        value="<?= e($keyword) ?>"
                    >
                </div>

                <div class="filter-control">
                    <label for="jenis" class="sr-only">
                        Filter jenis barang
                    </label>
                    <select name="jenis" id="jenis">
                        <option value="">Semua Jenis</option>
                        <?php foreach ($jenisList as $item): ?>
                            <option
                                value="<?= e($item) ?>"
                                <?= $jenis === $item ? 'selected' : '' ?>
                            >
                                <?= e($item) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-control">
                    <label for="brand" class="sr-only">
                        Filter brand
                    </label>
                    <select name="brand" id="brand">
                        <option value="">Semua Brand</option>
                        <?php foreach ($brandList as $item): ?>
                            <option
                                value="<?= e($item) ?>"
                                <?= $brand === $item ? 'selected' : '' ?>
                            >
                                <?= e($item) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="search-button">
                    Cari
                </button>

                <?php if ($keyword !== '' || $jenis !== '' || $brand !== ''): ?>
                    <a href="data_barang.php" class="reset-button">
                        Reset
                    </a>
                <?php endif; ?>

            </form>

            <!-- TABEL BARANG -->
            <section class="table-card"
                     aria-label="Daftar data barang">

                <div class="table-scroll">
                    <table class="barang-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Gambar</th>
                                <th>Nama Barang</th>
                                <th>Jenis Barang</th>
                                <th>Brand</th>
                                <th>Nomor Seri</th>
                                <th>Stok</th>
                                <th>Tanggal Masuk</th>
                                <th>Lokasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (empty($barangList)): ?>
                                <tr>
                                    <td colspan="10" class="empty-state">
                                        Data barang tidak ditemukan.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($barangList as $i => $barang): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>

                                        <td>
                                            <?php if (!empty($barang['gambar'])): ?>
                                                <img
                                                    class="product-image"
                                                    src="../uploads/barang/<?= e(basename($barang['gambar'])) ?>"
                                                    alt="<?= e($barang['nama_barang']) ?>"
                                                    loading="lazy"
                                                >
                                            <?php else: ?>
                                                <div class="image-placeholder"
                                                     aria-label="Belum ada gambar">
                                                    📦
                                                </div>
                                            <?php endif; ?>
                                        </td>

                                        <td class="product-name">
                                            <?= e($barang['nama_barang']) ?>
                                        </td>

                                        <td><?= e($barang['jenis_barang']) ?></td>
                                        <td><?= e($barang['brand']) ?></td>
                                        <td><?= e($barang['nomor_seri'] ?? '-') ?></td>

                                        <td>
                                            <span class="stock-badge">
                                                <?= number_format((int) $barang['stok'], 0, ',', '.') ?>
                                                <?= e($barang['satuan']) ?>
                                            </span>
                                        </td>

                                        <td>
                                            <?= date(
                                                'd M Y',
                                                strtotime($barang['tanggal_masuk'])
                                            ) ?>
                                        </td>

                                        <td><?= e($barang['lokasi']) ?></td>

                                        <td>
                                            <div class="action-buttons">
                                                <a
                                                    href="detail_barang.php?id=<?= (int) $barang['id_barang'] ?>"
                                                    class="action-button view-button"
                                                    aria-label="Lihat <?= e($barang['nama_barang']) ?>"
                                                    title="Lihat detail"
                                                >◉</a>

                                                <a
                                                    href="edit_barang.php?id=<?= (int) $barang['id_barang'] ?>"
                                                    class="action-button edit-button"
                                                    aria-label="Edit <?= e($barang['nama_barang']) ?>"
                                                    title="Edit barang"
                                                >✎</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <p>
                        Menampilkan <?= $totalBarang ?> data barang
                    </p>
                </div>

            </section>

        </main>
    </div>
</div>

</body>
</html>
