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

// Use session user_id — never take it from $_GET
$id = $_SESSION['user_id'];

// Fetch current user data
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

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize & validate inputs
    $name  = trim($_POST['name']  ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if (empty($name)) {
        $errors[] = "Full name is required.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email address is required.";
    }
    if (empty($phone)) {
        $errors[] = "Phone number is required.";
    }

    if (empty($errors)) {
        // Prepared statement — no SQL injection possible
        $upd = mysqli_prepare($conn,
            "UPDATE users SET name = ?, email = ?, phone = ? WHERE user_id = ?"
        );
        mysqli_stmt_bind_param($upd, "sssi", $name, $email, $phone, $id);

        if (mysqli_stmt_execute($upd)) {
            mysqli_stmt_close($upd);
            header("Location: profile.php?updated=1");
            exit();
        } else {
            $errors[] = "Update failed. Please try again.";
        }
        mysqli_stmt_close($upd);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Update Profile — Coozy Socks</title>
<link rel="stylesheet" href="style.css">
<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap');
  *{box-sizing:border-box;margin:0;padding:0;}
  body{font-family:'DM Sans',sans-serif;background:#f9f5f5;min-height:100vh;padding:2rem 1rem;}
  .container{max-width:520px;margin:0 auto;}
  .topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;}
  .brand{font-family:'Playfair Display',serif;color:#8b1e1e;font-size:22px;font-weight:600;}
  .brand-sub{color:#999;font-size:11px;letter-spacing:1.5px;text-transform:uppercase;margin-top:2px;}
  .card{background:#fff;border-radius:16px;padding:2rem;box-shadow:0 4px 20px rgba(139,30,30,0.07);}
  .card-title{font-family:'Playfair Display',serif;color:#1a1a1a;font-size:20px;font-weight:600;margin-bottom:1.5rem;}
  .field{margin-bottom:1.1rem;}
  .field label{display:block;font-size:11px;font-weight:500;color:#888;letter-spacing:0.8px;text-transform:uppercase;margin-bottom:5px;}
  .field input{width:100%;padding:10px 14px;font-family:'DM Sans',sans-serif;font-size:14px;background:#faf8f8;border:1px solid #e8dede;border-radius:10px;color:#1a1a1a;outline:none;transition:border-color .2s;}
  .field input:focus{border-color:#8b1e1e;background:#fff;}
  .save-btn{width:100%;padding:11px;background:#8b1e1e;color:#fff;border:none;border-radius:10px;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:500;cursor:pointer;transition:background .2s;margin-top:.25rem;}
  .save-btn:hover{background:#6e1717;}
  .cancel-btn{display:block;width:100%;padding:10px;text-align:center;background:transparent;color:#8b1e1e;border:1px solid #8b1e1e;border-radius:10px;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:500;cursor:pointer;transition:background .2s,color .2s;margin-top:.6rem;text-decoration:none;}
  .cancel-btn:hover{background:#8b1e1e;color:#fff;}
  .msg-error{background:#fdf0f0;color:#c0392b;padding:10px 14px;border-radius:10px;font-size:13px;margin-bottom:1rem;}
  .msg-error ul{padding-left:1.2rem;margin-top:4px;}
</style>
</head>
<body>

<!-- Nav -->
<div class="nav">
  <h1>Coozy Socks</h1>
  <ul>
    <li><a href="index.php">Home</a></li>
    <li><a href="product.php">Products</a></li>
    <li><a href="contact us.php">Contact Us</a></li>
    <li><a href="profile.php">Profile</a></li>
  </ul>
</div>

<div class="container">

  <div class="topbar">
    <div>
      <div class="brand">Coozy Socks</div>
      <div class="brand-sub">Edit Profile</div>
    </div>
  </div>

  <div class="card">
    <div class="card-title">Update Your Info</div>

    <?php if (!empty($errors)): ?>
      <div class="msg-error">
        Please fix the following:
        <ul>
          <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="POST" action="update_profile.php">

      <div class="field">
        <label for="name">Full Name</label>
        <input type="text"
               id="name"
               name="name"
               required
               value="<?= htmlspecialchars($_POST['name'] ?? $row['name'] ?? '') ?>">
      </div>

      <div class="field">
        <label for="email">Email Address</label>
        <input type="email"
               id="email"
               name="email"
               required
               value="<?= htmlspecialchars($_POST['email'] ?? $row['email'] ?? '') ?>">
      </div>

      <div class="field">
        <label for="phone">Phone Number</label>
        <input type="tel"
               id="phone"
               name="phone"
               required
               value="<?= htmlspecialchars($_POST['phone'] ?? $row['phone'] ?? '') ?>">
      </div>

      <button type="submit" class="save-btn">Save Changes</button>
      <a href="profile.php" class="cancel-btn">Cancel</a>

    </form>
  </div>

</div>
</body>
</html>
