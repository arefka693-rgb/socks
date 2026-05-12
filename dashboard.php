<!DOCTYPE html>
<?php

$conn = mysqli_connect("localhost", "root", "", "socksstore_db");

if (! $conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');

$sql = "SELECT * FROM products";

$result = mysqli_query($conn, $sql);

if (! $result) {
    die('Database query failed: ' . mysqli_error($conn));
}

$totalProducts = mysqli_num_rows($result);

?>
<html>
<head>
    <title>Coozy Socks</title>
    </head>
<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Mono:wght@400;500&family=DM+Sans:wght@300;400;500&display=swap');

/* ─── Reset & Base ─────────────────────────────────────────── */
*, *::before, *::after {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

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

body {
  background-color: var(--cream);
  font-family: var(--font-body);
  color: var(--charcoal);
  min-height: 100vh;
}

/* ─── Navbar ────────────────────────────────────────────────── */
.navbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background-color: var(--wine);
  padding: 0 40px;
  height: 64px;
  border-bottom: 3px solid var(--rose);
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: 0 2px 16px var(--shadow);
}

.navbar .logo img {
  height: 38px;
  display: block;
  filter: brightness(0) invert(1);
}

.navbar .menu {
  display: flex;
  gap: 4px;
}

.navbar .menu a {
  font-family: var(--font-mono);
  font-size: 0.72rem;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--blush);
  text-decoration: none;
  padding: 6px 14px;
  border: 1px solid transparent;
  border-radius: calc(var(--radius) / 2);
  transition: color var(--transition), border-color var(--transition), background var(--transition);
}

.navbar .menu a:hover {
  color: var(--white);
  border-color: var(--rose);
  background: rgba(201, 115, 106, 0.18);
}

/* ─── Hero ──────────────────────────────────────────────────── */
.hero {
  background-image:
    repeating-linear-gradient(
      45deg,
      transparent,
      transparent 24px,
      rgba(255,255,255,0.03) 24px,
      rgba(255,255,255,0.03) 25px
    ),
    linear-gradient(135deg, var(--wine) 0%, var(--deep-rose) 60%, var(--rose) 100%);
  padding: 72px 40px;
  text-align: center;
  border-bottom: 3px solid var(--rose);
  animation: fadeDown 0.6s ease both;
}

.hero h1 {
  font-family: var(--font-display);
  font-size: clamp(2.4rem, 5vw, 4rem);
  color: var(--white);
  letter-spacing: -0.02em;
  line-height: 1.1;
  margin-bottom: 12px;
}

.hero p {
  font-family: var(--font-mono);
  font-size: 0.76rem;
  letter-spacing: 0.22em;
  text-transform: uppercase;
  color: rgba(245, 230, 218, 0.75);
  margin-bottom: 30px;
}

.hero a {
  display: inline-block;
  font-family: var(--font-mono);
  font-size: 0.72rem;
  letter-spacing: 0.15em;
  text-transform: uppercase;
  color: var(--white);
  background: transparent;
  padding: 12px 32px;
  text-decoration: none;
  border-radius: var(--radius);
  border: 2px solid rgba(255,255,255,0.6);
  transition: background var(--transition), border-color var(--transition), transform var(--transition), box-shadow var(--transition);
}

.hero a:hover {
  background: rgba(255,255,255,0.12);
  border-color: var(--white);
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(90,16,16,0.3);
}

/* ─── Cards section wrapper ──────────────────────────────────── */
.cards {
  max-width: 1100px;
  margin: 0 auto;
  padding: 48px 40px 60px;
  animation: fadeUp 0.5s 0.15s ease both;
}

/* ─── Stat cards ─────────────────────────────────────────────── */
.cards > .card {
  display: inline-flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: var(--white);
  border: 1.5px solid var(--blush);
  box-shadow: 0 4px 20px var(--shadow);
  border-radius: var(--radius);
  padding: 28px 36px;
  min-width: 180px;
  margin: 0 14px 32px 0;
  vertical-align: top;
  transition: transform var(--transition), box-shadow var(--transition);
}

.cards > .card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 32px var(--shadow);
}

.cards > .card h2 {
  font-family: var(--font-display);
  font-size: 2.6rem;
  color: var(--deep-rose);
  line-height: 1;
  margin-bottom: 6px;
}

.cards > .card p {
  font-family: var(--font-mono);
  font-size: 0.67rem;
  letter-spacing: 0.16em;
  text-transform: uppercase;
  color: var(--warm-gray);
}

/* ─── Action Buttons ─────────────────────────────────────────── */
.cards > a[href],
a[href="addproduct.php"],
a[href="viewcart.php"] {
  display: inline-block !important;
  width: auto !important;
  font-family: var(--font-mono) !important;
  font-size: 0.70rem !important;
  letter-spacing: 0.14em !important;
  text-transform: uppercase !important;
  color: var(--white) !important;
  background: var(--deep-rose) !important;
  padding: 11px 26px !important;
  text-decoration: none !important;
  border: 2px solid var(--deep-rose) !important;
  border-radius: var(--radius) !important;
  box-shadow: 0 4px 14px var(--shadow) !important;
  margin: 0 10px 24px 0 !important;
  transition: background var(--transition), transform var(--transition), box-shadow var(--transition) !important;
}

.cards > a[href]:hover,
a[href="addproduct.php"]:hover,
a[href="viewcart.php"]:hover {
  background: var(--wine) !important;
  border-color: var(--wine) !important;
  transform: translateY(-2px) !important;
  box-shadow: 0 8px 22px var(--shadow) !important;
}

/* ─── Table ─────────────────────────────────────────────────── */
table {
  width: 100% !important;
  margin: 16px 0 0 !important;
  border-collapse: collapse !important;
  background: var(--white) !important;
  border: 1.5px solid var(--blush) !important;
  box-shadow: 0 4px 24px var(--shadow) !important;
  border-radius: var(--radius) !important;
  overflow: hidden !important;
  font-family: var(--font-body) !important;
}

table th {
  background: var(--wine) !important;
  color: var(--blush) !important;
  font-family: var(--font-mono) !important;
  font-size: 0.65rem !important;
  letter-spacing: 0.16em !important;
  text-transform: uppercase !important;
  padding: 15px 20px !important;
  text-align: left !important;
  border: none !important;
  border-bottom: 2px solid var(--rose) !important;
}

table td {
  padding: 13px 20px !important;
  font-size: 0.88rem !important;
  color: var(--charcoal) !important;
  border: none !important;
  border-bottom: 1px solid var(--blush) !important;
  vertical-align: middle !important;
  text-align: left !important;
}

table tr:last-child td {
  border-bottom: none !important;
}

table tr:nth-child(even) td {
  background: var(--cream) !important;
}

table tr:hover td {
  background: rgba(201, 115, 106, 0.07) !important;
}

table td img {
  display: block !important;
  width: 72px !important;
  height: 72px !important;
  object-fit: cover !important;
  border: 2px solid var(--blush) !important;
  border-radius: calc(var(--radius) / 2) !important;
}

/* Action links inside table */
table td a {
  font-family: var(--font-mono) !important;
  font-size: 0.67rem !important;
  letter-spacing: 0.1em !important;
  text-transform: uppercase !important;
  text-decoration: none !important;
  padding: 5px 12px !important;
  border-radius: 8px !important;
  border: 1.5px solid !important;
  transition: background var(--transition), color var(--transition) !important;
}

table td a[href*="update"] {
  color: var(--deep-rose) !important;
  border-color: var(--deep-rose) !important;
}

table td a[href*="update"]:hover {
  background: var(--deep-rose) !important;
  color: var(--white) !important;
}

table td a[href*="delete"] {
  color: var(--warm-gray) !important;
  border-color: var(--warm-gray) !important;
}

table td a[href*="delete"]:hover {
  background: var(--charcoal) !important;
  border-color: var(--charcoal) !important;
  color: var(--white) !important;
}

/* ─── Animations ─────────────────────────────────────────────── */
@keyframes fadeDown {
  from { opacity: 0; transform: translateY(-16px); }
  to   { opacity: 1; transform: translateY(0); }
}

@keyframes fadeUp {
  from { opacity: 0; transform: translateY(20px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ─── Responsive ─────────────────────────────────────────────── */
@media (max-width: 720px) {
  .navbar { padding: 0 20px; }
  .navbar .menu a { padding: 6px 8px; font-size: 0.65rem; }

  .hero { padding: 48px 20px; }

  .cards { padding: 32px 20px; }
  .cards > .card { min-width: 130px; padding: 20px; margin: 0 8px 16px 0; }
  .cards > .card h2 { font-size: 2rem; }

  table th, table td { padding: 10px 12px !important; font-size: 0.8rem !important; }
}
</style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
    
<title>Coozy Socks</title>
        <div class="logo">
           
        </div>

        <div class="menu">
            <a href="#">Dashboard</a>
            <a href="product.php">Products</a>
            <a href="#">Orders</a>
            <a href="#">Users</a>
            <a href="#">Logout</a>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="hero">

        <h1>Coozy Socks Admin Dashboard</h1>

        <p>Manage Coozy Socks Products</p>

        <a href="product.php">Manage Products</a>

    </div>

    <!-- Cards -->
    <div class="cards">

        <div class="card">
            <h2><?php echo $totalProducts; ?></h2>
            <p>Total Products</p>
        </div>

        <div class="card">
            <h2>35</h2>
            <p>Orders</p>
        </div>

        <div class="card">
            <h2>89</h2>
            <p>Users</p>
        </div>


<a href="addproduct.php" 
style="display:block; width:200px; margin:20px auto; text-align:center; background:black; color:white; padding:10px; text-decoration:none;">
Add Product
</a>
<table border="1" width="80%" style="margin:auto; text-align:center; background:white;">

<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Price</th>
    <th>Image</th>
    <th>Actions</th>
</tr>

<?php

while($row = mysqli_fetch_assoc($result)){

?>

<tr>
    <td><?php echo $row['product_id']; ?></td>
    
    <td><?php echo $row['name']; ?></td>
    
    <td><?php echo $row['price']; ?></td>
    
    <td>
        <img src="<?php echo htmlspecialchars($row['image'], ENT_QUOTES, 'UTF-8'); ?>" width="100" alt="<?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?>">
    </td>

    <td>
        <a href="updateproduct.php?id=<?php echo $row['product_id']; ?>">
            Edit
        </a>

        |

        <a href="deleteproduct.php?id=<?php echo $row['product_id']; ?>">
            Delete
        </a>
         
    </td>
</tr>

<?php
}

if ($totalProducts === 0) {
    echo '<tr><td colspan="5" style="padding:20px; color: var(--wine);">No products found.</td></tr>';
}
?>

</table>
    </div>

</body>
</html>
