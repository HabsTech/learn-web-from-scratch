
<?php
session_start();
require_once 'koneksi.php';

// Pastikan request berasal dari form POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$no_telp = trim($_POST['no_telp'] ?? '');
$password = $_POST['password'] ?? '';

if ($no_telp === '' || $password === '') {
    header('Location: login.php?error=invalid');
    exit;
}

// Cari user berdasarkan nomor telepon
$sql = "SELECT id, role, nama, divisi, no_telp, password
        FROM users
        WHERE no_telp = :no_telp
        LIMIT 1";

$stmt = $pdo->prepare($sql);
$stmt->execute(['no_telp' => $no_telp]);

$user = $stmt->fetch();

// Verifikasi akun dan password user
if (!$user || !password_verify($password, $user['password'])) {
    header('Location: login.php?error=invalid');
    exit;
}

$role = (int) $user['role'];

// Hanya role yang dikenal yang boleh masuk
if (!in_array($role, [1, 2], true)) {
    header('Location: login.php?error=role');
    exit;
}

// Buat session login baru
session_regenerate_id(true);

$_SESSION['user_id'] = (int) $user['id'];
$_SESSION['role'] = $role;
$_SESSION['nama'] = $user['nama'];
$_SESSION['divisi'] = $user['divisi'];

// Redirect berdasarkan role
if ($role === 1) {
    header('Location: admin/index.php');
    exit;
}

if ($role === 2) {
    header('Location: kepala_divisi/pengajuan.php');
    exit;
}
