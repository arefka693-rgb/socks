<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'socksstore_db');
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = 'Please enter both email and password.';
    } else {
        $email_safe = mysqli_real_escape_string($conn, $email);
        $query = "SELECT user_id, name, email, password FROM users WHERE email='$email_safe' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if ($result && $user = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['name'];
                header('Location: profile.php');
                exit();
            }
        }

        $error = 'Invalid email or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login – Coozy Socks</title>
<link rel="stylesheet" href="style.css">
<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap');

  * { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f9f5f5;
    font-family: 'DM Sans', sans-serif;
  }

  .login-wrap {
    display: grid;
    grid-template-columns: 1fr 1fr;
    width: 860px;
    max-width: 95vw;
    min-height: 520px;
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 8px 40px rgba(139,30,30,0.10);
  }

  /* Left panel */
  .login-left {
    background: #8b1e1e;
    padding: 3rem 2.5rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
  }
  .login-left::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 200px; height: 200px;
    border-radius: 50%;
    border: 40px solid rgba(255,255,255,0.06);
  }
  .login-left::after {
    content: '';
    position: absolute;
    bottom: -40px; left: -40px;
    width: 150px; height: 150px;
    border-radius: 50%;
    border: 30px solid rgba(255,255,255,0.06);
  }

  .brand { font-family: 'Playfair Display', serif; color: #fff; font-size: 28px; font-weight: 600; letter-spacing: 0.5px; }
  .brand-sub { color: rgba(255,255,255,0.65); font-size: 13px; font-weight: 300; margin-top: 4px; letter-spacing: 1.5px; text-transform: uppercase; }
  .left-tagline { font-family: 'Playfair Display', serif; font-style: italic; color: rgba(255,255,255,0.9); font-size: 20px; line-height: 1.6; }
  .sock-icon { font-size: 56px; opacity: 0.2; user-select: none; text-decoration: none; cursor: pointer; transition: opacity 0.2s, transform 0.2s; display: inline-block; }
  .sock-icon:hover { opacity: 0.5; transform: scale(1.15) rotate(-10deg); }

  /* Right panel */
  .login-right {
    padding: 3rem 2.5rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .login-title { font-family: 'Playfair Display', serif; color: #1a1a1a; font-size: 26px; font-weight: 600; margin-bottom: 6px; }
  .login-sub { color: #777; font-size: 14px; font-weight: 300; margin-bottom: 2rem; }

  .field { margin-bottom: 1.25rem; }
  .field label {
    display: block;
    font-size: 12px;
    font-weight: 500;
    color: #888;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    margin-bottom: 6px;
  }
  .field input {
    width: 100%;
    padding: 11px 14px;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    background: #faf8f8;
    border: 1px solid #e8dede;
    border-radius: 10px;
    color: #1a1a1a;
    outline: none;
    transition: border-color 0.2s;
  }
  .field input:focus { border-color: #8b1e1e; background: #fff; }

  .forgot { text-align: right; margin-top: 4px; }
  .forgot a { font-size: 12px; color: #8b1e1e; text-decoration: none; }
  .forgot a:hover { text-decoration: underline; }

  .login-btn {
    width: 100%;
    padding: 12px;
    background: #8b1e1e;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    font-weight: 500;
    letter-spacing: 0.5px;
    cursor: pointer;
    margin-top: 0.75rem;
    transition: background 0.2s;
  }
  .login-btn:hover { background: #6e1717; }

  .or-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 1.2rem 0;
    font-size: 12px;
    color: #aaa;
  }
  .or-row::before, .or-row::after { content: ''; flex: 1; height: 1px; background: #ecdede; }

  .guest-btn {
    width: 100%;
    padding: 11px;
    background: transparent;
    color: #8b1e1e;
    border: 1px solid #8b1e1e;
    border-radius: 10px;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
  }
  .guest-btn:hover { background: #8b1e1e; color: #fff; }

  .msg {
    font-size: 13px;
    margin-top: 14px;
    padding: 10px 14px;
    border-radius: 10px;
    display: none;
  }
  .msg.success { background: #e5f5f0; color: #0f6e56; display: block; }
  .msg.error { background: #fdf0f0; color: #c0392b; display: block; }

  @media (max-width: 640px) {
    .login-wrap { grid-template-columns: 1fr; }
    .login-left { display: none; }
  }
</style>
</head>
<body>
  
  

<div class="login-wrap">

  <!-- Left decorative panel -->
  <div class="login-left">
    <div>
      <div class="brand">Coozy Socks</div>
      <div class="brand-sub">Premium Comfort</div>
    </div>
    <div class="left-tagline">"Step into ultimate comfort — one pair at a time."</div>
    <a href="login.php" class="sock-icon" title="Go to Login">🧦</a>
  </div>

  <!-- Right login form -->
  <div class="login-right">
    <div class="login-title">Welcome back</div>
    <div class="login-sub">Sign in to your account</div>

    <form method="post" action="login.php">
      <div class="field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="you@example.com" required>
      </div>

      <div class="field">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="••••••••" required>
        <div class="forgot"><a href="#">Forgot password?</a></div>
      </div>

      <button type="submit" class="login-btn">Sign In</button>
    </form>

    <div class="or-row">or</div>

    <button class="guest-btn" onclick="window.location.href='index.php'">Continue as Guest</button>

    <?php if ($error): ?>
      <div class="msg error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
  </div>

</div>
 

</body>
</html>