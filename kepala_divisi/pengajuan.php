
<?php
require_once '../auth.php';

requireRole(2);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Pengambilan Barang</title>
</head>
<body>
    <header>
        <h1>Pengajuan Pengambilan Barang</h1>
        <p>
            Selamat datang,
            <?= htmlspecialchars($_SESSION['nama'], ENT_QUOTES, 'UTF-8') ?>!
        </p>
        <p>
            Divisi:
            <?= htmlspecialchars($_SESSION['divisi'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
        </p>
    </header>

    <main>
        <h2>Menu Kepala Divisi</h2>
        <p>
            Di halaman ini, kepala divisi nantinya dapat
            mengajukan pengambilan barang dari gudang.
        </p>

        <button type="button" disabled>
            Form Pengajuan (belum dibuat)
        </button>
    </main>

    <footer>
        <a href="../logout.php">Logout</a>
    </footer>
</body>
</html>
