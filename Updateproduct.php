<!DOCTYPE html>
<?php

$conn = mysqli_connect("localhost", "root", "", "socksstore_db");
if (!$conn) die('Database connection failed: ' . mysqli_connect_error());
mysqli_set_charset($conn, 'utf8mb4');

$success = '';
$error   = '';

// Get product ID from URL
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: product.php');
    exit;
}

// Fetch existing product
$stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE product_id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result  = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$product) {
    header('Location: product.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']  ?? '');
    $price = trim($_POST['price'] ?? '');
    $image = trim($_POST['image'] ?? '');

    if ($name === '' || $price === '' || $image === '') {
        $error = 'All fields are required.';
    } elseif (!is_numeric($price) || $price < 0) {
        $error = 'Price must be a positive number.';
    } else {
        $stmt2 = mysqli_prepare($conn, "UPDATE products SET name=?, price=?, image=? WHERE product_id=?");
        mysqli_stmt_bind_param($stmt2, 'sdsi', $name, $price, $image, $id);
        if (mysqli_stmt_execute($stmt2)) {
            $success = 'Product updated successfully!';
            // Refresh product data
            $product['name']  = $name;
            $product['price'] = $price;
            $product['image'] = $image;
        } else {
            $error = 'Failed to update product: ' . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt2);
    }
}
?>
<html>
<head>
    <title>Edit Product – Coozy Socks</title>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Mono:wght@400;500&family=DM+Sans:wght@300;400;500&display=swap');

    *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

    :root {
      --cream:      #fdf6ef;
      --blush:      #f5e6da;
      --rose:       #c9736a;
      --deep-rose:  #8b1e1e;
      --wine:       #5a1010;
      --warm-gray:  #8a7d76;
      --charcoal:   #2c2321;
      --white:      #ffffff;
      --shadow:     rgba(90,16,16,.10);
      --radius:     14px;
      --transition: .25s cubic-bezier(.4,0,.2,1);
      --font-display: 'Playfair Display', serif;
      --font-mono:    'DM Mono', monospace;
      --font-body:    'DM Sans', sans-serif;
    }

    html { scroll-behavior: smooth; }
    body { background-color: var(--cream); font-family: var(--font-body); color: var(--charcoal); min-height: 100vh; }

    .navbar {
      display: flex; align-items: center; justify-content: space-between;
      background-color: var(--wine); padding: 0 40px; height: 64px;
      border-bottom: 3px solid var(--rose); position: sticky; top: 0;
      z-index: 100; box-shadow: 0 2px 16px var(--shadow);
    }
    .navbar .logo span { font-family: var(--font-display); font-size: 1.3rem; color: var(--white); letter-spacing: 0.04em; }
    .navbar .menu { display: flex; gap: 4px; }
    .navbar .menu a {
      font-family: var(--font-mono); font-size: 0.72rem; letter-spacing: 0.12em;
      text-transform: uppercase; color: var(--blush); text-decoration: none;
      padding: 6px 14px; border: 1px solid transparent;
      border-radius: calc(var(--radius)/2);
      transition: color var(--transition), border-color var(--transition), background var(--transition);
    }
    .navbar .menu a:hover { color: var(--white); border-color: var(--rose); background: rgba(201,115,106,.18); }

    .page { max-width: 600px; margin: 60px auto; padding: 0 20px; animation: fadeUp .5s ease both; }
    .page-title { font-family: var(--font-display); font-size: 2.2rem; color: var(--wine); margin-bottom: 8px; letter-spacing: -.02em; }
    .page-sub { font-family: var(--font-mono); font-size: 0.68rem; letter-spacing: .18em; text-transform: uppercase; color: var(--warm-gray); margin-bottom: 36px; }

    .form-card {
      background: var(--white); border: 1.5px solid var(--blush);
      border-radius: var(--radius); box-shadow: 0 4px 24px var(--shadow); padding: 40px;
    }

    /* Badge showing current product ID */
    .id-badge {
      display: inline-block; font-family: var(--font-mono); font-size: 0.65rem;
      letter-spacing: .12em; text-transform: uppercase; padding: 4px 10px;
      background: var(--blush); color: var(--deep-rose); border-radius: 20px;
      margin-bottom: 28px;
    }

    .form-group { margin-bottom: 24px; }
    .form-group label { display: block; font-family: var(--font-mono); font-size: 0.67rem; letter-spacing: .14em; text-transform: uppercase; color: var(--warm-gray); margin-bottom: 8px; }
    .form-group input {
      width: 100%; padding: 12px 16px; border: 1.5px solid var(--blush);
      border-radius: calc(var(--radius)/2); font-family: var(--font-body);
      font-size: 0.95rem; color: var(--charcoal); background: var(--cream);
      outline: none; transition: border-color var(--transition), box-shadow var(--transition);
    }
    .form-group input:focus { border-color: var(--rose); box-shadow: 0 0 0 3px rgba(201,115,106,.15); background: var(--white); }

    .img-row { display: flex; align-items: center; gap: 16px; margin-top: 12px; }
    #img-preview { width: 80px; height: 80px; object-fit: cover; border: 2px solid var(--blush); border-radius: calc(var(--radius)/2); flex-shrink: 0; }

    .btn-row { display: flex; gap: 12px; margin-top: 8px; }
    .btn { flex: 1; font-family: var(--font-mono); font-size: 0.70rem; letter-spacing: .14em; text-transform: uppercase; padding: 13px 20px; border-radius: var(--radius); border: 2px solid; cursor: pointer; text-align: center; text-decoration: none; display: inline-block; transition: background var(--transition), transform var(--transition), box-shadow var(--transition); }
    .btn-primary { background: var(--deep-rose); color: var(--white); border-color: var(--deep-rose); box-shadow: 0 4px 14px var(--shadow); }
    .btn-primary:hover { background: var(--wine); border-color: var(--wine); transform: translateY(-2px); }
    .btn-secondary { background: transparent; color: var(--warm-gray); border-color: var(--blush); }
    .btn-secondary:hover { background: var(--blush); color: var(--charcoal); transform: translateY(-2px); }

    .alert { padding: 14px 18px; border-radius: calc(var(--radius)/2); font-family: var(--font-mono); font-size: 0.75rem; letter-spacing: .06em; margin-bottom: 24px; }
    .alert-success { background: rgba(90,160,90,.1); border: 1.5px solid #5aa05a; color: #2d6a2d; }
    .alert-error   { background: rgba(139,30,30,.08); border: 1.5px solid var(--deep-rose); color: var(--wine); }

    @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
    @media(max-width:600px) { .navbar { padding: 0 20px; } .form-card { padding: 24px; } .btn-row { flex-direction: column; } }
    </style>
</head>
<body>

<div class="navbar">
  <div class="logo"><span>Coozy Socks</span></div>
  <div class="menu">
    <a href="index.php">Dashboard</a>
    <a href="product.php">Products</a>
    <a href="#">Orders</a>
    <a href="#">Users</a>
    <a href="#">Logout</a>
  </div>
</div>

<div class="page">
  <div class="page-title">Edit Product</div>
  <div class="page-sub">Update product details</div>

  <?php if ($success): ?>
    <div class="alert alert-success">✓ <?= htmlspecialchars($success) ?> <a href="product.php" style="color:inherit; text-decoration:underline;">Back to products →</a></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="alert alert-error">✗ <?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <div class="form-card">
    <div class="id-badge">Product #<?= $id ?></div>

    <form method="POST" action="updateproduct.php?id=<?= $id ?>">

      <div class="form-group">
        <label for="name">Product Name</label>
        <input type="text" id="name" name="name" required
               value="<?= htmlspecialchars($product['name']) ?>">
      </div>

      <div class="form-group">
        <label for="price">Price (EGP)</label>
        <input type="number" id="price" name="price" step="0.01" min="0" required
               value="<?= htmlspecialchars($product['price']) ?>">
      </div>

      <div class="form-group">
        <label for="image">Image URL</label>
        <input type="url" id="image" name="image" oninput="previewImage(this.value)"
               value="<?= htmlspecialchars($product['image']) ?>">
        <div class="img-row">
          <img id="img-preview" src="<?= htmlspecialchars($product['image']) ?>"
               alt="Preview" onerror="this.style.display='none'">
        </div>
      </div>

      <div class="btn-row">
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="product.php" class="btn btn-secondary">Cancel</a>
      </div>

    </form>
  </div>
</div>

<script>
function previewImage(url) {
  const img = document.getElementById('img-preview');
  img.src = url;
  img.style.display = url ? 'block' : 'none';
  img.onerror = () => { img.style.display = 'none'; };
}
</script>

</body>
</html>
