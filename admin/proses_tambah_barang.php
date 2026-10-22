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

function kembali(string $status): void
{
    header('Location: data_barang.php?status=' . urlencode($status));
    exit;
}

$namaBarang = trim($_POST['nama_barang'] ?? '');
$jenisBarang = trim($_POST['jenis_barang'] ?? '');
$brand = trim($_POST['brand'] ?? '');
$nomorSeri = trim($_POST['nomor_seri'] ?? '');
$stokInput = $_POST['stok'] ?? '';
$tanggalMasuk = trim($_POST['tanggal_masuk'] ?? '');
$lokasi = trim($_POST['lokasi'] ?? '');

if (
    $namaBarang === '' ||
    $jenisBarang === '' ||
    $brand === '' ||
    $stokInput === '' ||
    filter_var($stokInput, FILTER_VALIDATE_INT) === false ||
    (int) $stokInput < 0 ||
    $tanggalMasuk === '' ||
    $lokasi === ''
) {
    kembali('input_tidak_valid');
}

$date = DateTime::createFromFormat('Y-m-d', $tanggalMasuk);
if (!$date || $date->format('Y-m-d') !== $tanggalMasuk) {
    kembali('tanggal_tidak_valid');
}

$namaFileGambar = null;
$uploadDir = __DIR__ . '/../uploads/barang/';

if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
    kembali('gagal_folder');
}

if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
        kembali('upload_gagal');
    }

    // Maksimum 3 MB dan hanya format gambar yang didukung.
    if ($_FILES['gambar']['size'] > 3 * 1024 * 1024) {
        kembali('gambar_terlalu_besar');
    }

    $tmpPath = $_FILES['gambar']['tmp_name'];
    $imageInfo = @getimagesize($tmpPath);

    if ($imageInfo === false) {
        kembali('format_gambar_tidak_valid');
    }

    $mime = $imageInfo['mime'] ?? '';
    $allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];

    if (!isset($allowedTypes[$mime])) {
        kembali('format_gambar_tidak_valid');
    }

    $namaFileGambar = bin2hex(random_bytes(16)) . '.' . $allowedTypes[$mime];

    if (!move_uploaded_file($tmpPath, $uploadDir . $namaFileGambar)) {
        kembali('upload_gagal');
    }
}

try {
    $sql = "INSERT INTO barang
            (gambar, nama_barang, jenis_barang, brand, nomor_seri, stok, satuan, tanggal_masuk, lokasi)
            VALUES
            (:gambar, :nama_barang, :jenis_barang, :brand, :nomor_seri, :stok, :satuan, :tanggal_masuk, :lokasi)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'gambar' => $namaFileGambar,
        'nama_barang' => $namaBarang,
        'jenis_barang' => $jenisBarang,
        'brand' => $brand,
        'nomor_seri' => $nomorSeri !== '' ? $nomorSeri : null,
        'stok' => (int) $stokInput,
        'satuan' => trim($_POST['satuan'] ?? '') !== '' ? trim($_POST['satuan']) : 'unit',
        'tanggal_masuk' => $tanggalMasuk,
        'lokasi' => $lokasi,
    ]);
} catch (PDOException $e) {
    // Jika penyimpanan gagal, hapus gambar yang baru diunggah.
    if ($namaFileGambar !== null && is_file($uploadDir . $namaFileGambar)) {
        unlink($uploadDir . $namaFileGambar);
    }
    error_log('Gagal menambahkan barang: ' . $e->getMessage());
    kembali('gagal_simpan');
}

kembali('berhasil');
