<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'socksstore_db');

// Create users table if it doesn't exist
$createTableSQL = "CREATE TABLE IF NOT EXISTS `users` (
    `user_id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20),
    `city` VARCHAR(100),
    `newsletter` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $createTableSQL);

$error   = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT user_id FROM users WHERE email='$email'");

    if (mysqli_num_rows($check) > 0) {
        $error = "This email is already registered.";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt   = "INSERT INTO `users`(`name`, `email`, `password`) VALUES ('$name','$email','$hashedPassword')";
        $result = mysqli_query($conn, $stmt);

        if ($result) {
            $success = true;
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign Up - Coozy Socks</title>
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
    padding: 2rem 1rem;
  }

  .signup-wrap {
    display: grid;
    grid-template-columns: 1fr 1fr;
    width: 900px;
    max-width: 95vw;
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 8px 40px rgba(139,30,30,0.10);
  }

  .signup-left {
    background: #8b1e1e;
    padding: 3rem 2.5rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
  }
  .signup-left::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 200px; height: 200px;
    border-radius: 50%;
    border: 40px solid rgba(255,255,255,0.06);
  }
  .signup-left::after {
    content: '';
    position: absolute;
    bottom: -40px; left: -40px;
    width: 150px; height: 150px;
    border-radius: 50%;
    border: 30px solid rgba(255,255,255,0.06);
  }

  .brand { font-family: 'Playfair Display', serif; color: #fff; font-size: 28px; font-weight: 600; letter-spacing: 0.5px; }
  .brand-sub { color: rgba(255,255,255,0.65); font-size: 13px; font-weight: 300; margin-top: 4px; letter-spacing: 1.5px; text-transform: uppercase; }
  .perks { list-style: none; margin-top: 2rem; display: flex; flex-direction: column; gap: 14px; }
  .perks li { display: flex; align-items: center; gap: 12px; color: rgba(255,255,255,0.85); font-size: 14px; font-weight: 300; }
  .perk-dot { width: 28px; height: 28px; border-radius: 50%; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }
  .left-tagline { font-family: 'Playfair Display', serif; font-style: italic; color: rgba(255,255,255,0.9); font-size: 18px; line-height: 1.6; }
  .sock-icon { font-size: 52px; opacity: 0.2; user-select: none; text-decoration: none; cursor: pointer; transition: opacity 0.2s, transform 0.2s; display: inline-block; }
  .sock-icon:hover { opacity: 0.5; transform: scale(1.15) rotate(-10deg); }

  .signup-right {
    padding: 2.5rem 2.5rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    overflow-y: auto;
  }

  .signup-title { font-family: 'Playfair Display', serif; color: #1a1a1a; font-size: 26px; font-weight: 600; margin-bottom: 4px; }
  .signup-sub { color: #777; font-size: 14px; font-weight: 300; margin-bottom: 1.75rem; }
  .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

  .field { margin-bottom: 1.1rem; }
  .field label { display: block; font-size: 11px; font-weight: 500; color: #888; letter-spacing: 0.8px; text-transform: uppercase; margin-bottom: 5px; }
  .field input, .field select {
    width: 100%; padding: 10px 14px;
    font-family: 'DM Sans', sans-serif; font-size: 14px;
    background: #faf8f8; border: 1px solid #e8dede;
    border-radius: 10px; color: #1a1a1a; outline: none;
    transition: border-color 0.2s; appearance: none;
  }
  .field input:focus, .field select:focus { border-color: #8b1e1e; background: #fff; }
  .field input.invalid { border-color: #c0392b; background: #fdf8f8; }
  .field input.valid   { border-color: #1d9e75; }

  .strength-bar { height: 4px; border-radius: 4px; background: #eee; margin-top: 6px; overflow: hidden; }
  .strength-fill { height: 100%; width: 0%; border-radius: 4px; transition: width 0.3s, background 0.3s; }
  .strength-label { font-size: 11px; margin-top: 4px; color: #aaa; }

  .checkbox-row { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 1.1rem; }
  .checkbox-row input[type="checkbox"] { margin-top: 2px; accent-color: #8b1e1e; cursor: pointer; flex-shrink: 0; }
  .checkbox-row label { font-size: 13px; color: #555; cursor: pointer; line-height: 1.5; }
  .checkbox-row label a { color: #8b1e1e; text-decoration: none; }
  .checkbox-row label a:hover { text-decoration: underline; }

  .signup-btn {
    width: 100%; padding: 12px;
    background: #8b1e1e; color: #fff; border: none;
    border-radius: 10px; font-family: 'DM Sans', sans-serif;
    font-size: 14px; font-weight: 500; letter-spacing: 0.5px;
    cursor: pointer; transition: background 0.2s;
  }
  .signup-btn:hover { background: #6e1717; }
  .signup-btn:disabled { background: #c9a0a0; cursor: not-allowed; }

  .login-link { text-align: center; margin-top: 14px; font-size: 13px; color: #888; }
  .login-link a { color: #8b1e1e; text-decoration: none; font-weight: 500; }
  .login-link a:hover { text-decoration: underline; }

  .msg { font-size: 13px; margin-top: 12px; padding: 10px 14px; border-radius: 10px; display: none; text-align: center; }
  .msg.success { background: #e5f5f0; color: #0f6e56; display: block; }
  .msg.error   { background: #fdf0f0; color: #c0392b; display: block; }

  .steps { display: flex; align-items: center; gap: 0; margin-bottom: 1.75rem; }
  .step { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #bbb; font-weight: 400; }
  .step.active { color: #8b1e1e; font-weight: 500; }
  .step.done { color: #1d9e75; }
  .step-num { width: 22px; height: 22px; border-radius: 50%; border: 1.5px solid #ddd; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 500; background: #fff; }
  .step.active .step-num { border-color: #8b1e1e; color: #8b1e1e; }
  .step.done .step-num { border-color: #1d9e75; background: #1d9e75; color: #fff; }
  .step-line { flex: 1; height: 1px; background: #eee; margin: 0 6px; }
  .step-line.done { background: #1d9e75; }

  .page { display: none; }
  .page.active { display: block; }

  @media (max-width: 640px) {
    .signup-wrap { grid-template-columns: 1fr; }
    .signup-left { display: none; }
    .row-2 { grid-template-columns: 1fr; }
  }
</style>
</head>
<body>

<div class="signup-wrap">

  <!-- Left panel -->
  <div class="signup-left">
    <div>
      <div class="brand">Coozy Socks</div>
      <div class="brand-sub">Premium Comfort</div>
      <ul class="perks">
        <li><div class="perk-dot">🎁</div> Exclusive member discounts</li>
        <li><div class="perk-dot">🚚</div> Free shipping on first order</li>
        <li><div class="perk-dot">⭐</div> Early access to new styles</li>
        <li><div class="perk-dot">💌</div> Cozy newsletter & tips</li>
      </ul>
    </div>
    <div class="left-tagline">"Join thousands of happy feet around Egypt."</div>
    <a href="login.php" class="sock-icon" title="Back to Login">🧦</a>
  </div>

  <!-- Right panel -->
  <div class="signup-right">

    <?php if ($success): ?>

      <!-- Page 3: Success (shown after PHP INSERT succeeds) -->
      <div class="page active" id="page3" style="text-align:center; padding: 1rem 0;">
        <div style="font-size:56px; margin-bottom:1rem;">🎉</div>
        <h2 style="font-family:'Playfair Display',serif; color:#1a1a1a; font-size:22px; margin-bottom:8px;">You're in!</h2>
        <p style="color:#777; font-size:14px; margin-bottom:1.5rem; line-height:1.6;">Welcome to the Coozy Socks family.<br>Your account has been created successfully.</p>
        <a href="index.php">
          <button class="signup-btn" style="max-width:260px; margin:0 auto;">shop now 🧦</button>
        </a>
        <div class="login-link" style="margin-top:14px;">
          Already done? <a href="login.php">Sign in instead</a>
        </div>
      </div>

    <?php else: ?>

      <div class="signup-title">Create account</div>
      <div class="signup-sub">It's free and only takes a minute</div>

      <!-- Step indicators -->
      <div class="steps">
        <div class="step active" id="step-ind-1"><div class="step-num" id="snum1">1</div> Personal</div>
        <div class="step-line" id="line-1"></div>
        <div class="step" id="step-ind-2"><div class="step-num" id="snum2">2</div> Account</div>
        <div class="step-line" id="line-2"></div>
        <div class="step" id="step-ind-3"><div class="step-num" id="snum3">3</div> Done</div>
      </div>

      <!-- Page 1: Personal info (JS only, no form submit yet) -->
      <div class="page active" id="page1">
        <div class="row-2">
          <div class="field">
            <label>First Name</label>
            <input type="text" id="firstName" placeholder="Nour">
          </div>
          <div class="field">
            <label>Last Name</label>
            <input type="text" id="lastName" placeholder="Ahmed">
          </div>
        </div>
        <div class="field">
          <label>Phone Number</label>
          <input type="tel" id="phone" placeholder="+20 100 000 0000">
        </div>
        <div class="field">
          <label>City</label>
          <select id="city">
            <option value="">Select your city</option>
            <option>Cairo</option>
            <option>Alexandria</option>
            <option>Giza</option>
            <option>Sharm El-Sheikh</option>
            <option>Hurghada</option>
            <option>Luxor</option>
            <option>Aswan</option>
            <option>Other</option>
          </select>
        </div>
        <button class="signup-btn" onclick="goToPage2()">Continue &rarr;</button>
      </div>

      <!-- Page 2: Account info — this form POSTs to PHP -->
      <div class="page" id="page2">
        <form method="POST" action="signup.php" id="signupForm">

          <!-- Hidden fields: carry page 1 data into POST -->
          <input type="hidden" name="name"  id="h-name">

          <div class="field">
            <label>Email Address</label>
            <input type="email" id="email" name="email" placeholder="you@example.com" oninput="validateEmail()">
          </div>
          <div class="field">
            <label>Password</label>
            <input type="password" id="password" name="password" placeholder="At least 8 characters" oninput="checkStrength()">
            <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
            <div class="strength-label" id="strengthLabel">Enter a password</div>
          </div>
          <div class="field">
            <label>Confirm Password</label>
            <input type="password" id="confirmPass" placeholder="Repeat your password" oninput="validateConfirm()">
          </div>
          <div class="checkbox-row">
            <input type="checkbox" id="terms">
            <label for="terms">I agree to the <a href="#">Terms &amp; Conditions</a> and <a href="#">Privacy Policy</a></label>
          </div>
          <div class="checkbox-row">
            <input type="checkbox" id="newsletter" checked>
            <label for="newsletter">Send me new arrivals &amp; exclusive offers</label>
          </div>

          <!-- This button triggers JS validation first, then submits the form -->
          <button type="button" class="signup-btn" onclick="submitSignup()">Create My Account</button>

          <div style="text-align:center; margin-top:10px;">
            <a href="#" onclick="goToPage1(); return false;" style="font-size:13px; color:#8b1e1e; text-decoration:none;">&larr; Back</a>
          </div>

        </form>
      </div>

      <!-- PHP error message -->
      <?php if (!empty($error)): ?>
        <div class="msg error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <div id="msg" class="msg"></div>
      <div class="login-link" id="loginHint">Already have an account? <a href="login.php">Sign in</a></div>

    <?php endif; ?>

  </div>
</div>

<script>
  /* ── Helpers ── */
  function showMsg(text, type) {
    const el = document.getElementById('msg');
    el.textContent = text;
    el.className = 'msg ' + type;
  }
  function clearMsg() {
    const el = document.getElementById('msg');
    el.className = 'msg';
    el.textContent = '';
  }

  /* ── Step indicators ── */
  function setStep(n) {
    for (let i = 1; i <= 3; i++) {
      const ind = document.getElementById('step-ind-' + i);
      const num = document.getElementById('snum' + i);
      ind.className = 'step';
      if (i < n)      { ind.classList.add('done');   num.textContent = '✓'; }
      else if (i===n) { ind.classList.add('active'); num.textContent = i; }
      else            { num.textContent = i; }
    }
    for (let i = 1; i <= 2; i++) {
      document.getElementById('line-' + i).className = 'step-line' + (i < n ? ' done' : '');
    }
  }

  /* ── Page navigation ── */
  function showPage(n) {
    document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
    document.getElementById('page' + n).classList.add('active');
    document.getElementById('loginHint').style.display = n === 3 ? 'none' : 'block';
    setStep(n);
    clearMsg();
  }

  function goToPage1() { showPage(1); }

  function goToPage2() {
    const firstName = document.getElementById('firstName').value.trim();
    const lastName  = document.getElementById('lastName').value.trim();
    const phone     = document.getElementById('phone').value.trim();
    const city      = document.getElementById('city').value;

    if (!firstName || !lastName) { showMsg('Please enter your full name.', 'error'); return; }
    if (!phone) { showMsg('Please enter your phone number.', 'error'); return; }
    if (!city)  { showMsg('Please select your city.', 'error'); return; }

    showPage(2);
  }

  /* ── Validation ── */
  function validateEmail() {
    const input = document.getElementById('email');
    const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value.trim());
    input.className = input.value ? (valid ? 'valid' : 'invalid') : '';
  }

  function checkStrength() {
    const pass  = document.getElementById('password').value;
    const fill  = document.getElementById('strengthFill');
    const label = document.getElementById('strengthLabel');
    let score = 0;
    if (pass.length >= 8)          score++;
    if (/[A-Z]/.test(pass))        score++;
    if (/[0-9]/.test(pass))        score++;
    if (/[^A-Za-z0-9]/.test(pass)) score++;
    const levels = [
      { w: '0%',   bg: '#eee',    txt: 'Enter a password' },
      { w: '25%',  bg: '#e24b4a', txt: 'Weak' },
      { w: '50%',  bg: '#ef9f27', txt: 'Fair' },
      { w: '75%',  bg: '#639922', txt: 'Good' },
      { w: '100%', bg: '#1d9e75', txt: 'Strong 💪' },
    ];
    const lvl = pass.length === 0 ? levels[0] : levels[Math.min(score, 4)];
    fill.style.width      = lvl.w;
    fill.style.background = lvl.bg;
    label.textContent     = lvl.txt;
    label.style.color     = lvl.bg;
  }

  function validateConfirm() {
    const pass    = document.getElementById('password').value;
    const confirm = document.getElementById('confirmPass');
    confirm.className = confirm.value ? (confirm.value === pass ? 'valid' : 'invalid') : '';
  }

  /* ── Final submit: JS validates → fills hidden fields → PHP inserts ── */
  function submitSignup() {
    const email       = document.getElementById('email').value.trim();
    const password    = document.getElementById('password').value;
    const confirmPass = document.getElementById('confirmPass').value;
    const terms       = document.getElementById('terms').checked;

    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      showMsg('Please enter a valid email address.', 'error'); return;
    }
    if (password.length < 8) {
      showMsg('Password must be at least 8 characters.', 'error'); return;
    }
    if (password !== confirmPass) {
      showMsg('Passwords do not match.', 'error'); return;
    }
    if (!terms) {
      showMsg('Please agree to the Terms & Conditions.', 'error'); return;
    }

    // Fill hidden field: combine first + last name for the DB `name` column
    const firstName = document.getElementById('firstName').value.trim();
    const lastName  = document.getElementById('lastName').value.trim();
    document.getElementById('h-name').value = firstName + ' ' + lastName;

    // Submit the form → PHP runs the INSERT
    document.getElementById('signupForm').submit();
  }
</script>

</body>
</html>