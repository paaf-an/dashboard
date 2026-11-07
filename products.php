<?php
require_once 'koneksi.php';

$result = mysqli_query($conn, "SELECT id, nama_barang, harga, stok, foto FROM produk ORDER BY id DESC");
if (!$result) {
    die("Gagal mengambil data produk: " . mysqli_error($conn));
}

function rupiah($angka) {
    return 'Rp ' . number_format((float)$angka, 0, ',', '.');
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    body { 
        background:#f5f7fb;
        color:#253047; 
    }

    .sidebar {
         min-height:100vh; 
         background:#fff; 
         border-right:1px solid #e8edf4;
         }

    .nav-link {
         color:#667085;
          border-radius:10px;
           padding:.75rem .9rem;
            margin-bottom:.25rem; 
        }

    .nav-link:hover,.nav-link.active { 
        background:#edf2ff; 
        color:#315efb;
     }

    .content { 
        padding:28px; 
    }

    .panel { 
        background:#fff;
        border:1px solid #e9edf5;
        border-radius:16px;
        box-shadow:0 5px 20px rgba(31,45,61,.04); 
        }

    .table thead th {
         color:#667085; 
         font-size:.82rem; 
         font-weight:700; 
         background:#f8faff; 
         border-bottom:0; 
        }

    .table td { 
        vertical-align:middle; 
    }

    .stock { 
        background:#e8f8ef;
         color:#16834a;
         }
    @media (max-width:767px) { 
        .sidebar { min-height:auto; }
         .content { padding:18px; } }
  </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <aside class="col-md-3 col-lg-2 sidebar p-3">
      <div class="text-uppercase text-secondary small fw-bold mb-2">Menu utama</div>
      <nav class="nav flex-column">
        <a class="nav-link active" href="index.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
        <a class="nav-link" href="products.php"><i class="bi bi-box-seam me-2"></i>Products</a>
        <a class="nav-link" href="orders.php"><i class="bi bi-cart3 me-2"></i>Orders</a>
        <a class="nav-link" href="#"><i class="bi bi-people me-2"></i>Customers</a>
        <a class="nav-link" href="#"><i class="bi bi-bar-chart me-2"></i>Reports</a>
        <a class="nav-link" href="#"><i class="bi bi-plug me-2"></i>Integrations</a>
      </nav>
      <hr>
      <a class="nav-link text-danger" href="#"><i class="bi bi-box-arrow-left me-2"></i>Sign out</a>
    </aside>

    <main class="col-md-9 col-lg-10 content">
      <div class="mb-4">
        <h2 class="fw-bold mb-0">Product</h2>
      </div>

      <section class="panel p-3 p-md-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
          <h4 class="fw-bold mb-1">Data Produk</h4>
          <a href="tambah.php" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add Product
          </a>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
              <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td>
                  <?php if (!empty($row['foto']) && is_file(__DIR__ . '/uploads/' . $row['foto'])): ?>
                    <img src="uploads/<?= rawurlencode($row['foto']) ?>" alt="Foto produk" style="width:58px;height:58px;object-fit:cover;border-radius:10px">
                  <?php else: ?>
                    <span class="text-secondary small">Tidak ada foto</span>
                  <?php endif; ?>
                </td>
                <td class="fw-semibold"><?= htmlspecialchars($row['nama_barang']) ?></td>
                <td><?= rupiah($row['harga']) ?></td>
                <td>
                  <span class="badge stock"><?= (int)$row['stok'] ?> unit</span>
                </td>
                <td class="text-nowrap">
                  <a href="edit.php?id=<?= (int)$row['id'] ?>"
                     class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-pencil-square"></i> Edit
                  </a>

                  <form action="hapus.php" method="post" class="d-inline"
                        onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                    <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                      <i class="bi bi-trash"></i> Hapus
                    </button>
                  </form>
                </td>
              </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-center text-secondary py-5">
                  Belum ada produk. Klik Add Product untuk menambahkan data.
                </td>
              </tr>
            <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>
    </main>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
