
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
            color: #253047;
        }

        .sidebar {
            min-height: 100vh;
            background: #fff;
            border-right: 1px solid #e8edf4;
        }

        .nav-link {
            color: #667085;
            border-radius: 10px;
            padding: .75rem .9rem;
            margin-bottom: .25rem;
        }

        .nav-link:hover,
        .nav-link.active {
            background: #edf2ff;
            color: #315efb;
        }

        .content {
            padding: 28px;
        }

        @media (max-width: 767px) {
            .sidebar {
                min-height: auto;
            }

            .content {
                padding: 18px;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <aside class="col-md-3 col-lg-2 sidebar p-3">

            <div class="text-uppercase text-secondary small fw-bold mb-2">
                Menu Utama
            </div>

            <nav class="nav flex-column">

                <a class="nav-link active" href="index.php">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Dashboard
                </a>

                <a class="nav-link" href="products.php">
                    <i class="bi bi-box-seam me-2"></i>
                    Products
                </a>

                <a class="nav-link" href="orders.php">
                    <i class="bi bi-cart3 me-2"></i>
                    Orders
                </a>

                <a class="nav-link" href="customers.php">
                    <i class="bi bi-people me-2"></i>
                    Customers
                </a>

                <hr>

                <a class="nav-link" href="settings.php">
                    <i class="bi bi-gear me-2"></i>
                    Settings
                </a>

                <a class="nav-link text-danger" href="#">
                    <i class="bi bi-box-arrow-left me-2"></i>
                    Sign Out
                </a>

            </nav>

        </aside>

        <!-- KONTEN DASHBOARD -->
        <main class="col-md-9 col-lg-10 content">

            <h2 class="fw-bold mb-1">
                Dashboard
            </h2>

            <p class="text-secondary">
                Selamat datang di halaman dashboard
            </p>

            <hr>

        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
