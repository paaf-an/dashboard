
<?php
require_once 'koneksi.php';

$error = '';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    die('ID produk tidak valid. <a href="products.php">Kembali</a>');
}

// Mengambil data produk berdasarkan ID
$stmt = mysqli_prepare($conn, "
    SELECT nama_barang, harga, stok, foto
    FROM produk
    WHERE id = ?
");

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$produk = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$produk) {
    die('Produk tidak ditemukan. <a href="products.php">Kembali</a>');
}

$nama = $produk['nama_barang'];
$harga = $produk['harga'];
$stok = $produk['stok'];
$foto_lama = $produk['foto'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama_barang'] ?? '');
    $harga = trim($_POST['harga'] ?? '');
    $stok = trim($_POST['stok'] ?? '');

    // Foto lama tetap digunakan jika tidak upload foto baru
    $foto = $foto_lama;
    $foto_baru = null;

    if ($nama === '' || $harga === '' || $stok === '') {

        $error = 'Semua kolom wajib diisi.';

    } elseif (
        !is_numeric($harga) ||
        (float)$harga < 0 ||
        filter_var($stok, FILTER_VALIDATE_INT) === false ||
        (int)$stok < 0
    ) {

        $error = 'Harga harus angka positif dan stok harus bilangan bulat positif.';

    } else {

        // Memeriksa apakah pengguna memilih foto baru
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {

            if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {

                $error = 'Foto gagal diunggah.';

            } elseif ($_FILES['foto']['size'] > 2 * 1024 * 1024) {

                $error = 'Ukuran foto maksimal 2 MB.';

            } else {

                // Memeriksa jenis file foto
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $_FILES['foto']['tmp_name']);
                finfo_close($finfo);

                $jenis_foto = [
                    'image/jpeg' => 'jpg',
                    'image/png'  => 'png',
                    'image/webp' => 'webp'
                ];

                if (!isset($jenis_foto[$mime])) {

                    $error = 'Format foto harus JPG, PNG, atau WEBP.';

                } else {

                    // Membuat nama foto baru
                    $foto_baru = uniqid('produk_', true) . '.' . $jenis_foto[$mime];

                    $folder = __DIR__ . '/uploads/';

                    if (!is_dir($folder)) {
                        mkdir($folder, 0755, true);
                    }

                    // Memindahkan foto ke folder uploads
                    if (move_uploaded_file(
                        $_FILES['foto']['tmp_name'],
                        $folder . $foto_baru
                    )) {

                        $foto = $foto_baru;

                    } else {

                        $error = 'Foto gagal disimpan ke folder uploads.';
                    }
                }
            }
        }

        if ($error === '') {

            // Memperbarui data produk
            $stmt = mysqli_prepare($conn, "
                UPDATE produk
                SET nama_barang = ?, harga = ?, stok = ?, foto = ?
                WHERE id = ?
            ");

            mysqli_stmt_bind_param(
                $stmt,
                "sdisi",
                $nama,
                $harga,
                $stok,
                $foto,
                $id
            );

            if (mysqli_stmt_execute($stmt)) {

                mysqli_stmt_close($stmt);

                // Menghapus foto lama jika foto berhasil diganti
                if (
                    $foto_baru !== null &&
                    $foto_lama !== '' &&
                    is_file(__DIR__ . '/uploads/' . $foto_lama)
                ) {
                    unlink(__DIR__ . '/uploads/' . $foto_lama);
                }

                header('Location: products.php');
                exit;

            } else {

                $error = 'Data gagal diperbarui: ' . mysqli_error($conn);

                // Membersihkan foto baru jika proses update gagal
                if (
                    $foto_baru !== null &&
                    is_file(__DIR__ . '/uploads/' . $foto_baru)
                ) {
                    unlink(__DIR__ . '/uploads/' . $foto_baru);
                }

                mysqli_stmt_close($stmt);
            }
        }
    }
}
?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Edit Produk</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5" style="max-width:650px">

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <h3 class="fw-bold mb-1">Edit Produk</h3>

            <p class="text-secondary">
                Ubah informasi produk yang dipilih.
            </p>

            <?php if ($error !== ''): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="post"
                  action="edit.php?id=<?= (int)$id ?>"
                  enctype="multipart/form-data">

                <div class="mb-3">
                    <label class="form-label">Nama Barang</label>

                    <input
                        class="form-control"
                        name="nama_barang"
                        value="<?= htmlspecialchars($nama) ?>"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Harga (Rp)</label>

                    <input
                        class="form-control"
                        type="number"
                        min="0"
                        step="0.01"
                        name="harga"
                        value="<?= htmlspecialchars((string)$harga) ?>"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Stok</label>

                    <input
                        class="form-control"
                        type="number"
                        min="0"
                        step="1"
                        name="stok"
                        value="<?= htmlspecialchars((string)$stok) ?>"
                        required
                    >
                </div>

                <div class="mb-3">

                    <label class="form-label">Foto Produk</label>

                    <?php if ($foto_lama !== '' &&
                        is_file(__DIR__ . '/uploads/' . $foto_lama)): ?>

                        <div class="mb-2">
                            <p class="small text-secondary mb-1">
                                Foto saat ini:
                            </p>

                            <img
                                src="uploads/<?= htmlspecialchars($foto_lama) ?>"
                                width="120"
                                height="120"
                                style="object-fit:cover; border-radius:10px;"
                            >
                        </div>

                    <?php else: ?>

                        <p class="text-secondary small">
                            Belum ada foto produk.
                        </p>

                    <?php endif; ?>

                    <input
                        class="form-control"
                        type="file"
                        name="foto"
                        accept=".jpg,.jpeg,.png,.webp"
                    >

                    <small class="text-secondary">
                        Pilih foto baru jika ingin mengganti foto lama.
                        Maksimal 2 MB.
                    </small>

                </div>

                <button class="btn btn-primary" type="submit">
                    Simpan Perubahan
                </button>

                <a class="btn btn-outline-secondary"
                   href="products.php">
                    Kembali
                </a>

            </form>

        </div>
    </div>
</div>

</body>
</html>
