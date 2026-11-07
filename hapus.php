<?php
require_once 'koneksi.php';

// Hapus hanya dijalankan melalui form POST dari tombol Hapus
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    die('ID produk tidak valid. <a href="index.php">Kembali</a>');
}

// Menghapus satu produk berdasarkan ID
$stmt = mysqli_prepare($conn, "DELETE FROM produk WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header('Location: index.php');
    exit;
}

$error = mysqli_error($conn);
mysqli_stmt_close($stmt);
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hapus Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="alert alert-danger">
    Data gagal dihapus: <?= htmlspecialchars($error) ?>
  </div>
  <a href="index.php" class="btn btn-primary">Kembali ke Dashboard</a>
</div>
</body>
</html>
