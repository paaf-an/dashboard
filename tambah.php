<?php
require_once 'koneksi.php';
$error = '';
$nama = '';
$harga = '';
$stok = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_barang'] ?? '');
    $harga = trim($_POST['harga'] ?? '');
    $stok = trim($_POST['stok'] ?? '');
    $namaFoto = null;

    if ($nama === '' || $harga === '' || $stok === '') {
        $error = 'Semua kolom wajib diisi.';
    } elseif (!is_numeric($harga) || (float)$harga < 0 || filter_var($stok, FILTER_VALIDATE_INT) === false || (int)$stok < 0) {
        $error = 'Harga harus angka positif dan stok harus bilangan bulat positif.';
    } elseif (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Foto produk wajib dipilih.';
    } elseif ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
        $error = 'Ukuran foto maksimal 2 MB.';
    } else {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($_FILES['foto']['tmp_name']);
        $jenisFoto = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

        if (!isset($jenisFoto[$mime])) {
            $error = 'Format foto harus JPG, PNG, atau WEBP.';
        } else {
            $folderUpload = __DIR__ . '/uploads/';
            if (!is_dir($folderUpload)) { mkdir($folderUpload, 0755, true); }
            $namaFoto = bin2hex(random_bytes(16)) . '.' . $jenisFoto[$mime];
            if (!move_uploaded_file($_FILES['foto']['tmp_name'], $folderUpload . $namaFoto)) {
                $error = 'Foto gagal diunggah. Periksa folder uploads.';
            }
        }
    }

    if ($error === '') {
        $stmt = mysqli_prepare($conn, "INSERT INTO produk (nama_barang, harga, stok, foto) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sdis", $nama, $harga, $stok, $namaFoto);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header('Location: index.php');
            exit;
        }
        if ($namaFoto && is_file(__DIR__ . '/uploads/' . $namaFoto)) { unlink(__DIR__ . '/uploads/' . $namaFoto); }
        $error = 'Data gagal disimpan: ' . mysqli_error($conn);
        mysqli_stmt_close($stmt);
    }
}
?>
<!doctype html><html lang="id"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Tambah Produk</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="bg-light"><div class="container py-5" style="max-width:650px">
<div class="card border-0 shadow-sm rounded-4"><div class="card-body p-4">
<h3 class="fw-bold mb-1">Tambah Produk</h3><p class="text-secondary">Masukkan informasi produk baru.</p>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data">
<div class="mb-3"><label class="form-label">Nama Barang</label><input class="form-control" name="nama_barang" value="<?= htmlspecialchars($nama) ?>" required></div>
<div class="mb-3"><label class="form-label">Harga (Rp)</label><input class="form-control" type="number" min="0" step="0.01" name="harga" value="<?= htmlspecialchars($harga) ?>" required></div>
<div class="mb-4"><label class="form-label">Stok</label><input class="form-control" type="number" min="0" step="1" name="stok" value="<?= htmlspecialchars($stok) ?>" required></div>
<div class="mb-4"><label class="form-label">Foto Produk</label><input class="form-control" type="file" name="foto" accept=".jpg,.jpeg,.png,.webp" required><div class="form-text">Format JPG, PNG, atau WEBP. Maksimal 2 MB.</div></div>
<button class="btn btn-primary" type="submit">Simpan Produk</button> <a class="btn btn-outline-secondary" href="index.php">Kembali</a>
</form></div></div></div></body></html>
