
<?php
require_once '../auth.php';

requireRole(1);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Gudang</title>
</head>
<body>
    <header>
        <h1>Dashboard Admin Gudang</h1>
        <p>Selamat datang, <?= htmlspecialchars($_SESSION['nama'], ENT_QUOTES, 'UTF-8') ?>!</p>
    </header>

    <main>
        <h2>Menu Admin</h2>
        <ul>
            <li>Data barang</li>
            <li>Data stok gudang</li>
            <li>Data pengajuan pengambilan barang</li>
            <li>Data pengguna</li>
        </ul>
    </main>

    <footer>
        <a href="../logout.php">Logout</a>
    </footer>
</body>
</html>
