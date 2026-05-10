<?php

$conn = mysqli_connect("localhost", "root", "", "socksstore_db");

$id = 1;

$sql = "SELECT * FROM users WHERE user_id=$id";

$result = mysqli_query($conn,$sql);

$row = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>

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
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Coozy Socks</title>
<link rel="stylesheet" href="style.css">
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
<div class="wrap" id="app">
  <div class="container">
    <div class="topbar">
      <div>
        <div class="brand">Coozy Socks</div>
        <div class="brand-sub">My Account</div>
      </div>
      <button class="logout-btn" onclick="logout()">Sign Out</button>
    </div>
    <div id="content"></div>
  </div>
</div>

<script>
const CITIES=['Cairo','Alexandria','Giza','Sharm El-Sheikh','Hurghada','Luxor','Aswan','Other'];

function getUser(){
  try{return JSON.parse(localStorage.getItem('coozyUser'));}catch{return null;}
}
function saveUser(data){
  localStorage.setItem('coozyUser',JSON.stringify({...data,updatedAt:new Date().toISOString()}));
}
function initials(u){
  return ((u.firstName||'?')[0]+(u.lastName||'?')[0]).toUpperCase();
}
function formatDate(iso){
  if(!iso)return'—';
  try{return new Date(iso).toLocaleDateString('en-GB',{day:'numeric',month:'long',year:'numeric'});}catch{return'—';}
}

function logout(){
  if(confirm('Sign out of Coozy Socks?')){
    localStorage.removeItem('coozyUser');
    render();
  }
}

function render(){
  const u=getUser();
  const el=document.getElementById('content');
  if(!u){
    el.innerHTML=`<div class="no-data">
      <div style="font-size:48px;margin-bottom:1rem;">🧦</div>
      <h2>You're not signed in</h2>
      <p>Sign in to access your Coozy Socks profile.</p>
      <button class="login-redirect" onclick="simulateLogin()">Demo: Sign In</button>
    </div>`;
    return;
  }
  el.innerHTML=`
    <div class="profile-header">
      <div class="avatar" id="avatarEl">${initials(u)}</div>
      <div class="header-info">
        <div class="header-name" id="headerName">${u.firstName||''} ${u.lastName||''}</div>
        <div class="header-email">${u.email||''}</div>
        <div class="header-badge">🧦 Member since ${formatDate(u.createdAt)}</div>
      </div>
    </div>
    <div class="grid">
      <div class="card" id="personalCard">
        <div class="card-title">Personal info <button class="edit-link" onclick="toggleEdit('personal')">Edit</button></div>
        <div id="personalView">
          <div class="info-row"><span class="info-label">First name</span><span class="info-value">${u.firstName||'—'}</span></div>
          <div class="info-row"><span class="info-label">Last name</span><span class="info-value">${u.lastName||'—'}</span></div>
          <div class="info-row"><span class="info-label">Phone</span><span class="info-value">${u.phone||'—'}</span></div>
          <div class="info-row"><span class="info-label">City</span><span class="info-value">${u.city||'—'}</span></div>
        </div>
        <div id="personalEdit" style="display:none;">
          <div class="field"><label>First name</label><input type="text" id="ed-firstName" value="${u.firstName||''}"></div>
          <div class="field"><label>Last name</label><input type="text" id="ed-lastName" value="${u.lastName||''}"></div>
          <div class="field"><label>Phone</label><input type="tel" id="ed-phone" value="${u.phone||''}"></div>
          <div class="field"><label>City</label>
            <select id="ed-city">${CITIES.map(c=>`<option value="${c}"${u.city===c?' selected':''}>${c}</option>`).join('')}</select>
          </div>
          <button class="save-btn" onclick="savePersonal()">Save changes</button>
          <button class="cancel-btn" onclick="toggleEdit('personal')">Cancel</button>
          <div id="personal-msg" class="msg"></div>
        </div>
      </div>

      <div class="card">
        <div class="card-title">Account details <button class="edit-link" onclick="toggleEdit('account')">Edit</button></div>
        <div id="accountView">
          <div class="info-row"><span class="info-label">Email</span><span class="info-value">${u.email||'—'}</span></div>
          <div class="info-row"><span class="info-label">Password</span><span class="info-value">••••••••</span></div>
          <div class="info-row"><span class="info-label">Account created</span><span class="info-value">${formatDate(u.createdAt)}</span></div>
          ${u.updatedAt?`<div class="info-row"><span class="info-label">Last updated</span><span class="info-value">${formatDate(u.updatedAt)}</span></div>`:''}
        </div>
        <div id="accountEdit" style="display:none;">
          <div class="field"><label>Email</label><input type="email" id="ed-email" value="${u.email||''}"></div>
          <div class="field"><label>New password <span style="color:#aaa;font-size:10px;">(leave blank to keep)</span></label>
            <input type="password" id="ed-pass" placeholder="New password" oninput="checkPassStrength()">
            <div class="strength-bar"><div class="strength-fill" id="sFill"></div></div>
            <div class="strength-label" id="sLabel">Enter a new password</div>
          </div>
          <div class="field"><label>Confirm password</label><input type="password" id="ed-pass2" placeholder="Repeat password"></div>
          <button class="save-btn" onclick="saveAccount()">Save changes</button>
          <button class="cancel-btn" onclick="toggleEdit('account')">Cancel</button>
          <div id="account-msg" class="msg"></div>
        </div>
      </div>

      <div class="card">
        <div class="card-title">Preferences</div>
        <div class="toggle-row">
          <div><div class="toggle-label">Newsletter</div><div class="toggle-sub">New arrivals & exclusive offers</div></div>
          <label class="toggle"><input type="checkbox" id="pref-newsletter" ${u.newsletter?'checked':''} onchange="savePref()"><span class="slider"></span></label>
        </div>
        <div class="toggle-row">
          <div><div class="toggle-label">Order updates</div><div class="toggle-sub">SMS & email notifications</div></div>
          <label class="toggle"><input type="checkbox" id="pref-orders" ${u.prefOrders?'checked':''} onchange="savePref()"><span class="slider"></span></label>
        </div>
        <div class="toggle-row">
          <div><div class="toggle-label">Promotions</div><div class="toggle-sub">Flash sales & discount codes</div></div>
          <label class="toggle"><input type="checkbox" id="pref-promos" ${u.prefPromos!==false?'checked':''} onchange="savePref()"><span class="slider"></span></label>
        </div>
        <div id="pref-saved" style="font-size:12px;color:#0f6e56;margin-top:10px;display:none;">Preferences saved!</div>
      </div>

      <div class="card">
        <div class="card-title">Member perks</div>
        <ul class="perk-list">
          <li><div class="perk-dot">🎁</div> Exclusive member discounts</li>
          <li><div class="perk-dot">🚚</div> Free shipping on first order</li>
          <li><div class="perk-dot">⭐</div> Early access to new styles</li>
          <li><div class="perk-dot">💌</div> Cozy newsletter & tips</li>
        </ul>
        <div style="margin-top:1.25rem;padding-top:1rem;border-top:1px solid #f5eded;">
          <div style="font-size:12px;color:#aaa;margin-bottom:6px;text-transform:uppercase;letter-spacing:.8px;">City</div>
          <div style="font-size:14px;color:#1a1a1a;" id="cityDisplay">${u.city||'—'}</div>
        </div>
      </div>
    </div>
    <div style="text-align:center;margin-top:2rem;font-size:12px;color:#bbb;padding-bottom:2rem;">
      Coozy Socks — Premium Comfort &nbsp;•&nbsp; Egypt 🧦
    </div>
  `;
}

function toggleEdit(section){
  const view=document.getElementById(section+'View');
  const edit=document.getElementById(section+'Edit');
  const showing=edit.style.display==='block';
  view.style.display=showing?'block':'none';
  edit.style.display=showing?'none':'block';
}

function savePersonal(){
  const u=getUser()||{};
  const fn=document.getElementById('ed-firstName').value.trim();
  const ln=document.getElementById('ed-lastName').value.trim();
  const phone=document.getElementById('ed-phone').value.trim();
  const city=document.getElementById('ed-city').value;
  const msg=document.getElementById('personal-msg');
  if(!fn||!ln){msg.textContent='Please enter your full name.';msg.className='msg error';return;}
  if(!phone){msg.textContent='Please enter a phone number.';msg.className='msg error';return;}
  if(!city){msg.textContent='Please select a city.';msg.className='msg error';return;}
  Object.assign(u,{firstName:fn,lastName:ln,phone,city});
  saveUser(u);
  render();
}

function saveAccount(){
  const u=getUser()||{};
  const email=document.getElementById('ed-email').value.trim();
  const pass=document.getElementById('ed-pass').value;
  const pass2=document.getElementById('ed-pass2').value;
  const msg=document.getElementById('account-msg');
  if(!email||!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){msg.textContent='Please enter a valid email.';msg.className='msg error';return;}
  if(pass&&pass.length<8){msg.textContent='Password must be at least 8 characters.';msg.className='msg error';return;}
  if(pass&&pass!==pass2){msg.textContent='Passwords do not match.';msg.className='msg error';return;}
  u.email=email;
  if(pass)u.password=pass;
  saveUser(u);
  render();
}

function savePref(){
  const u=getUser()||{};
  u.newsletter=document.getElementById('pref-newsletter').checked;
  u.prefOrders=document.getElementById('pref-orders').checked;
  u.prefPromos=document.getElementById('pref-promos').checked;
  saveUser(u);
  const el=document.getElementById('pref-saved');
  if(el){el.style.display='block';setTimeout(()=>el.style.display='none',2000);}
}

function checkPassStrength(){
  const pass=document.getElementById('ed-pass').value;
  const fill=document.getElementById('sFill');
  const label=document.getElementById('sLabel');
  let s=0;
  if(pass.length>=8)s++;
  if(/[A-Z]/.test(pass))s++;
  if(/[0-9]/.test(pass))s++;
  if(/[^A-Za-z0-9]/.test(pass))s++;
  const lvls=[
    {w:'0%',bg:'#eee',t:'Enter a new password'},
    {w:'25%',bg:'#e24b4a',t:'Weak'},
    {w:'50%',bg:'#ef9f27',t:'Fair'},
    {w:'75%',bg:'#639922',t:'Good'},
    {w:'100%',bg:'#1d9e75',t:'Strong 💪'},
  ];
  const l=pass.length===0?lvls[0]:lvls[Math.min(s,4)];
  fill.style.width=l.w;fill.style.background=l.bg;
  label.textContent=l.t;label.style.color=l.bg;
}

function simulateLogin(){
  const demo={
    firstName:'Nour',lastName:'Ahmed',
    phone:'+20 100 000 0000',city:'Cairo',
    email:'hello@coozysocks.com',
    newsletter:true,prefOrders:true,prefPromos:true,
    createdAt:new Date().toISOString()
  };
  localStorage.setItem('coozyUser',JSON.stringify(demo));
  render();
}

render();
</script>
