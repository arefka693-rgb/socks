<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "socksstore_db");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Use session user_id — never trust user input for identity
$id = $_SESSION['user_id'];

// Prepared statement — no SQL injection possible
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE user_id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$row) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Profile — Coozy Socks</title>
<link rel="stylesheet" href="style.css">
<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap');
  *{box-sizing:border-box;margin:0;padding:0;}
  body{font-family:'DM Sans',sans-serif;background:#f9f5f5;min-height:100vh;}
  .wrap{padding:2rem 1rem;}
  .container{max-width:860px;margin:0 auto;}
  .topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;}
  .brand{font-family:'Playfair Display',serif;color:#8b1e1e;font-size:22px;font-weight:600;}
  .brand-sub{color:#999;font-size:11px;letter-spacing:1.5px;text-transform:uppercase;margin-top:2px;}
  .logout-btn{padding:8px 18px;background:transparent;color:#8b1e1e;border:1px solid #8b1e1e;border-radius:10px;font-size:13px;font-weight:500;cursor:pointer;transition:background .2s,color .2s;text-decoration:none;}
  .logout-btn:hover{background:#8b1e1e;color:#fff;}
  .profile-header{background:#8b1e1e;border-radius:18px;padding:2.5rem 2rem;display:flex;align-items:center;gap:2rem;margin-bottom:1.5rem;position:relative;overflow:hidden;}
  .profile-header::before{content:'';position:absolute;top:-50px;right:-50px;width:180px;height:180px;border-radius:50%;border:40px solid rgba(255,255,255,0.06);}
  .profile-header::after{content:'';position:absolute;bottom:-30px;left:-30px;width:130px;height:130px;border-radius:50%;border:28px solid rgba(255,255,255,0.06);}
  .avatar{width:76px;height:76px;border-radius:50%;background:rgba(255,255,255,0.18);display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-size:26px;color:#fff;font-weight:600;flex-shrink:0;border:2px solid rgba(255,255,255,0.3);z-index:1;}
  .header-info{z-index:1;}
  .header-name{font-family:'Playfair Display',serif;color:#fff;font-size:22px;font-weight:600;}
  .header-email{color:rgba(255,255,255,0.65);font-size:13px;margin-top:4px;}
  .header-badge{display:inline-flex;align-items:center;gap:6px;margin-top:10px;background:rgba(255,255,255,0.15);color:rgba(255,255,255,0.9);font-size:12px;padding:4px 12px;border-radius:20px;}
  .grid{display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;}
  .card{background:#fff;border-radius:16px;padding:1.75rem;box-shadow:0 4px 20px rgba(139,30,30,0.06);}
  .card-title{font-family:'Playfair Display',serif;color:#1a1a1a;font-size:16px;font-weight:600;margin-bottom:1.25rem;}
  .info-row{display:flex;flex-direction:column;margin-bottom:1rem;}
  .info-row:last-child{margin-bottom:0;}
  .info-label{font-size:11px;font-weight:500;color:#aaa;letter-spacing:0.8px;text-transform:uppercase;margin-bottom:3px;}
  .info-value{font-size:14px;color:#1a1a1a;}
  .edit-link{display:inline-block;margin-top:1.25rem;padding:9px 20px;background:#8b1e1e;color:#fff;border-radius:10px;font-size:13px;font-weight:500;text-decoration:none;transition:background .2s;}
  .edit-link:hover{background:#6e1717;}
  .perk-list{list-style:none;display:flex;flex-direction:column;gap:10px;}
  .perk-list li{display:flex;align-items:center;gap:10px;font-size:13px;color:#555;}
  .perk-dot{width:28px;height:28px;border-radius:50%;background:#fdf0f0;display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0;}
  .msg-success{background:#e5f5f0;color:#0f6e56;padding:10px 14px;border-radius:10px;font-size:13px;margin-bottom:1rem;}
  .msg-error{background:#fdf0f0;color:#c0392b;padding:10px 14px;border-radius:10px;font-size:13px;margin-bottom:1rem;}
  @media(max-width:620px){.grid{grid-template-columns:1fr;}.profile-header{flex-direction:column;text-align:center;gap:1rem;}}
</style>
</head>
<body>

<div id="main" onclick="closeNav()">
<div class="nav">
<h1>Coozy Socks</h1>
<ul>
<li><a href="index.php">Home</a></li>
<li><a href="product.php">product</a></li>
<li><a href="contact us.php">Contact Us</a></li>
<li><a href="login.php">Login</a></li>
<li><a href="signup.php">Sign Up</a></li>
 <li><a href="profile.php">
  <img src="profile.jpg" alt="Profile Icon" style="width:30px; cursor:pointer;">
</a></li>
</ul>
</div>

<div class="wrap">
  <div class="container">

    <div class="topbar">
      <div>
        <div class="brand">Coozy Socks</div>
        <div class="brand-sub">My Account</div>
      </div>
      <a href="logout.php" class="logout-btn">Sign Out</a>
    </div>

    <?php if (isset($_GET['updated'])): ?>
      <div class="msg-success">✓ Profile updated successfully.</div>
    <?php endif; ?>

    <!-- Profile header -->
    <?php
      $initials = strtoupper(
        substr($row['name'] ?? 'U', 0, 1)
      );
    ?>
    <div class="profile-header">
      <div class="avatar"><?= htmlspecialchars($initials) ?></div>
      <div class="header-info">
        <div class="header-name"><?= htmlspecialchars($row['name'] ?? '') ?></div>
        <div class="header-email"><?= htmlspecialchars($row['email'] ?? '') ?></div>
        <div class="header-badge">🧦 Coozy Member</div>
      </div>
    </div>

    <div class="grid">

      <!-- Personal info -->
      <div class="card">
        <div class="card-title">Personal Info</div>
        <div class="info-row">
          <span class="info-label">Full Name</span>
          <span class="info-value"><?= htmlspecialchars($row['name'] ?? '—') ?></span>
        </div>
        
      </div>

      <!-- Account details -->
      <div class="card">
        <div class="card-title">Account Details</div>
        <div class="info-row">
          <span class="info-label">Email</span>
          <span class="info-value"><?= htmlspecialchars($row['email'] ?? '—') ?></span>
        </div>
        <div class="info-row">
          <span class="info-label">Password</span>
          <span class="info-value">••••••••</span>
        </div>
      </div>

      <!-- Member perks -->
      <div class="card">
        <div class="card-title">Member Perks</div>
        <ul class="perk-list">
          <li><div class="perk-dot">🎁</div> Exclusive member discounts</li>
          <li><div class="perk-dot">🚚</div> Free shipping on first order</li>
          <li><div class="perk-dot">⭐</div> Early access to new styles</li>
          <li><div class="perk-dot">💌</div> Cozy newsletter & tips</li>
        </ul>
      </div>

    </div>

    <div style="text-align:center;margin-top:2rem;font-size:12px;color:#bbb;padding-bottom:2rem;">
      Coozy Socks — Premium Comfort &nbsp;•&nbsp; Egypt 🧦
    </div>

  </div>
</div>

</body>
</html>
