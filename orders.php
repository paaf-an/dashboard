
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Orders</title>
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

    @media (max-width:767px)
     {

     .sidebar { min-height:auto; } 
     .content { padding:18px; } }
    </style>
</head>

<body>

<div class="container-fluid">
  <div class="row">
            <aside class="col-md-3 col-lg-2 sidebar p-3">

            <div class="text-uppercase text-secondary small fw-bold mb-2">
                Menu utama
            </div>

                <nav class="nav flex-column">

                    <a class="nav-link" href="index.php">
                        <i class="bi bi-speedometer2 me-2"></i>
                        Dashboard
                    </a>

                    <a class="nav-link" href="products.php">
                        <i class="bi bi-box-seam me-2"></i>
                        Products
                    </a>

                    <a class="nav-link active" href="orders.php">
                        <i class="bi bi-cart3 me-2"></i>
                        Orders
                    </a>

                    <a class="nav-link" href="customers.php">
                        <i class="bi bi-people me-2"></i>
                        Customers
                    </a>

                    <a class="nav-link" href="#">
                        <i class="bi bi-bar-chart me-2"></i>
                        Reports
                    </a>

                    <a class="nav-link" href="#">
                        <i class="bi bi-plug me-2"></i>
                        Integrations
                    </a>

                </nav>

            <hr>

                    <a class="nav-link text-danger" href="#">
                        <i class="bi bi-box-arrow-left me-2"></i>
                        Sign out
                    </a>

        </aside>
            <main class="col-md-9 col-lg-10 content">

                <h2 class="fw-bold mb-1">Orders</h2>

                <p class="text-secondary">
                    Selamat datang di halaman orders
                </p>

                <hr>

            </main>

</div>
    </div>

</body>
</html>
