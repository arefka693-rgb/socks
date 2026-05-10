<?php

$conn = mysqli_connect("localhost", "root", "", "socksstore_db");


$id = isset($_GET["id"]) ? $_GET["id"] : 0;

$sql = "SELECT * FROM users WHERE user_id=$id";

$result = mysqli_query($conn,$sql);

$row = mysqli_fetch_assoc($result);

if(isset($_POST["update"])){

    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];

    $sql2 = "UPDATE users
             SET name='$name',
                 email='$email',
                 phone='$phone'
             WHERE user_id=$id";

    mysqli_query($conn,$sql2);

    header("Location: profile.php");
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Profile</title>

    <style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap');
  *{box-sizing:border-box;margin:0;padding:0;}
  body-like{font-family:'DM Sans',sans-serif;}
  .wrap{background:#f9f5f5;min-height:100vh;padding:2rem 1rem;font-family:'DM Sans',sans-serif;}
  .container{max-width:860px;margin:0 auto;}
  .topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;}
  .brand{font-family:'Playfair Display',serif;color:#8b1e1e;font-size:22px;font-weight:600;}
  .brand-sub{color:#999;font-size:11px;letter-spacing:1.5px;text-transform:uppercase;margin-top:2px;}
  .logout-btn{padding:8px 18px;background:transparent;color:#8b1e1e;border:1px solid #8b1e1e;border-radius:10px;font-family:'DM Sans',sans-serif;font-size:13px;font-weight:500;cursor:pointer;transition:background .2s,color .2s;}
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
  .card-title{font-family:'Playfair Display',serif;color:#1a1a1a;font-size:16px;font-weight:600;margin-bottom:1.25rem;display:flex;align-items:center;justify-content:space-between;}
  .edit-link{font-family:'DM Sans',sans-serif;font-size:12px;color:#8b1e1e;cursor:pointer;font-weight:500;background:none;border:none;padding:0;}
  .edit-link:hover{text-decoration:underline;}
  .info-row{display:flex;flex-direction:column;margin-bottom:1rem;}
  .info-row:last-child{margin-bottom:0;}
  .info-label{font-size:11px;font-weight:500;color:#aaa;letter-spacing:0.8px;text-transform:uppercase;margin-bottom:3px;}
  .info-value{font-size:14px;color:#1a1a1a;font-weight:400;}
  .field{margin-bottom:1rem;}
  .field label{display:block;font-size:11px;font-weight:500;color:#888;letter-spacing:0.8px;text-transform:uppercase;margin-bottom:5px;}
  .field input,.field select{width:100%;padding:10px 14px;font-family:'DM Sans',sans-serif;font-size:14px;background:#faf8f8;border:1px solid #e8dede;border-radius:10px;color:#1a1a1a;outline:none;transition:border-color .2s;appearance:none;}
  .field input:focus,.field select:focus{border-color:#8b1e1e;background:#fff;}
  .save-btn{width:100%;padding:11px;background:#8b1e1e;color:#fff;border:none;border-radius:10px;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:500;cursor:pointer;transition:background .2s;margin-top:.25rem;}
  .save-btn:hover{background:#6e1717;}
  .cancel-btn{width:100%;padding:10px;background:transparent;color:#8b1e1e;border:1px solid #8b1e1e;border-radius:10px;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:500;cursor:pointer;transition:background .2s,color .2s;margin-top:.5rem;}
  .cancel-btn:hover{background:#8b1e1e;color:#fff;}
  .perk-list{list-style:none;display:flex;flex-direction:column;gap:10px;}
  .perk-list li{display:flex;align-items:center;gap:10px;font-size:13px;color:#555;}
  .perk-dot{width:28px;height:28px;border-radius:50%;background:#fdf0f0;display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0;}
  .msg{font-size:13px;margin-top:12px;padding:10px 14px;border-radius:10px;display:none;}
  .msg.success{background:#e5f5f0;color:#0f6e56;display:block;}
  .msg.error{background:#fdf0f0;color:#c0392b;display:block;}
  .toggle-row{display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f5eded;}
  .toggle-row:last-child{border-bottom:none;}
  .toggle-label{font-size:13px;color:#444;}
  .toggle-sub{font-size:11px;color:#aaa;margin-top:2px;}
  .toggle{position:relative;width:36px;height:20px;flex-shrink:0;}
  .toggle input{opacity:0;width:0;height:0;}
  .slider{position:absolute;inset:0;background:#ddd;border-radius:20px;cursor:pointer;transition:background .2s;}
  .slider::before{content:'';position:absolute;width:14px;height:14px;left:3px;bottom:3px;background:#fff;border-radius:50%;transition:transform .2s;}
  .toggle input:checked+.slider{background:#8b1e1e;}
  .toggle input:checked+.slider::before{transform:translateX(16px);}
  .strength-bar{height:4px;border-radius:4px;background:#eee;margin-top:6px;overflow:hidden;}
  .strength-fill{height:100%;width:0%;border-radius:4px;transition:width .3s,background .3s;}
  .strength-label{font-size:11px;margin-top:4px;color:#aaa;}
  .no-data{text-align:center;padding:3rem 1rem;}
  .no-data h2{font-family:'Playfair Display',serif;color:#1a1a1a;font-size:20px;margin-bottom:8px;}
  .no-data p{color:#777;font-size:14px;margin-bottom:1.5rem;}
  .login-redirect{display:inline-block;padding:12px 28px;background:#8b1e1e;color:#fff;border-radius:10px;font-size:14px;font-weight:500;text-decoration:none;cursor:pointer;border:none;font-family:'DM Sans',sans-serif;}
  .login-redirect:hover{background:#6e1717;}
  @media(max-width:620px){.grid{grid-template-columns:1fr;}.profile-header{flex-direction:column;text-align:center;gap:1rem;}}
</style>
</head>
<body>

<div class="container">

    <h1>Update Profile</h1>

    <form method="POST">

        <input type="text" 
               name="name"
               value="<?php echo $row['name']; ?>">

        <input type="email" 
               name="email"
               value="<?php echo $row['email']; ?>">

        <input type="text" 
               name="phone"
               value="<?php echo $row['phone']; ?>">

        <button name="update">Update</button>

    </form>

</div>

</body>
</html>
