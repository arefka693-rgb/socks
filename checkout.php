<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coozy Socks - Checkout</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="sideNav" id="sideNav">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="product.php">product</a></li>
            <li><a href="best sellers.php">Best Sellers</a></li>
            <li><a href="javascript:void(0)" onclick="openSizeModal()">Size Guide</a></li>
            <li><a href="javascript:void(0)" onclick="openShippingModal()">Shipping & Returns</a></li>
            <li><a href="javascript:void(0)" onclick="openPrivacyModal()">Privacy Policy</a></li>
            <li><a href="contact us.php">Contact us</a></li>
            <li><a href="signup.php">Sign Up</a></li>
            <li><a href="profile.php">
  <img src="profile.jpg" alt="Profile Icon" style="width:30px; cursor:pointer;">
</a></li>
        </ul>
    </div>
    <span id="burgerMenu" onclick="openNav()">&#9776;</span>
    <div id="main">
        <div class="nav">
            <h1>Coozy Socks</h1>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="product.php">product</a></li>
                <li><a href="contact us.php">Contact Us</a></li>
                  <li><a href="profile.php">
  <img src="profile.jpg" alt="Profile Icon" style="width:30px; cursor:pointer;">
</a></li>
            </ul>
        </div>
        <div id="checkout-container" onclick="closeNav()">
            <div class="checkout-main-container">
                <div class="left-side">
                    <h2>Checkout</h2>
                    <p class="section-title">Shipping Information</p>
                    <form id="checkoutForm" method="POST">
                        <div class="input-group">
                            <label>Full Name</label>
                            <input type="text" id="custName" name="fullname" placeholder="Enter your full name" required>
                        </div>
                        <div class="input-group">
                            <label>Email Address</label>
                            <input type="email" id="custEmail" name="email" placeholder="Enter your email" required>
                        </div>
                        <div class="input-group">
                            <label>Phone Number</label>
                            <input type="tel" name="phone" placeholder="+20 123 456 7890" required>
                        </div>
                        <div class="input-group">
                            <label>Address</label>
                            <input type="text" name="address" placeholder="123 Main St, Nasr City" required>
                        </div>
                        <div class="input-group">
                            <label>Payment Method</label>
                            <select name="payment" required>
                                <option value="" disabled selected>Select payment method</option>
                                <option value="Credit Card">Credit Card</option>
                                <option value="Cash on Delivery">Cash on Delivery</option>
                                <option value="PayPal">PayPal</option>
                            </select>
                        </div>
                        <div class="row-inputs">
                            <div class="input-group">
                                <label>City</label>
                                <input type="text" name="city" placeholder="Cairo" required>
                            </div>
                            <div class="input-group">
                                <label>ZIP Code</label>
                                <input type="text" name="zipcode" placeholder="12345">
                            </div>
                        </div>
                        <label style="display: flex; align-items: center; gap: 10px; font-size: 13px; color: #666; cursor: pointer; margin-top: 10px;">
                            <input type="checkbox" name="terms" required style="width: auto;"> I agree to the terms and conditions
                        </label>
                        <input type="hidden" id="cartItemsInput" name="cart_items" value="">
                    </form>
                </div>
                <div class="right-side">
                    <h3>Review your cart</h3>
                    <div id="checkout-cart-container"></div>
                    <div style="margin-top: 30px;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:10px; color:#555;">
                            <span>Subtotal</span><span>$<span id="sub-val">0</span></span>
                        </div>
                        <div style="display:flex; justify-content:space-between; color:#555;">
                            <span>Shipping</span><span>$50</span>
                        </div>
                        <div class="final-total">
                            <span>Total</span><span>$<span id="total-val">0</span></span>
                        </div>
                    </div>
                    <button class="pay-now-btn" onclick="processOrder(event)">Pay Now</button>
                    <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data safely
    $name = isset($_POST["fullname"]) ? trim($_POST["fullname"]) : '';
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : '';
    $phone = isset($_POST["phone"]) ? trim($_POST["phone"]) : '';
    $address = isset($_POST["address"]) ? trim($_POST["address"]) : '';
    $city = isset($_POST["city"]) ? trim($_POST["city"]) : '';
    $payment = isset($_POST["payment"]) ? trim($_POST["payment"]) : '';
    $cartItemsJson = isset($_POST["cart_items"]) ? trim($_POST["cart_items"]) : '[]';

    $cartItems = json_decode($cartItemsJson, true);
    $totalPrice = 0.00;
    if (is_array($cartItems)) {
        foreach ($cartItems as $cartItem) {
            $price = isset($cartItem['price']) ? floatval($cartItem['price']) : 0;
            $qty = isset($cartItem['qty']) ? intval($cartItem['qty']) : 1;
            $totalPrice += $price * $qty;
        }
    }

    // Validate required fields
    if (!empty($name) && !empty($phone) && !empty($address) && !empty($payment) && $totalPrice > 0) {

        $conn = mysqli_connect("localhost", "root", "", "socksstore_db");

        if ($conn) {
            $existingColumns = [];
            $tableExists = mysqli_query($conn, "SHOW TABLES LIKE 'orders'");
            if (mysqli_num_rows($tableExists) > 0) {
                $columnsResult = mysqli_query($conn, "SHOW COLUMNS FROM `orders`");
                while ($col = mysqli_fetch_assoc($columnsResult)) {
                    $existingColumns[] = $col['Field'];
                }
            }

            if (empty($existingColumns)) {
                $createTableSQL = "CREATE TABLE IF NOT EXISTS `orders` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `name` VARCHAR(255) NOT NULL,
                    `email` VARCHAR(255),
                    `phone` VARCHAR(50) NOT NULL,
                    `address` TEXT NOT NULL,
                    `city` VARCHAR(100),
                    `payment_method` VARCHAR(50),
                    `cart_items` LONGTEXT,
                    `total_price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )";
                mysqli_query($conn, $createTableSQL);
                $existingColumns = ['id','name','email','phone','address','city','payment_method','cart_items','total_price','created_at'];
            }

            if (in_array('name', $existingColumns) && in_array('total_price', $existingColumns)) {
                $stmt = $conn->prepare("INSERT INTO `orders` (`name`, `email`, `phone`, `address`, `city`, `payment_method`, `cart_items`, `total_price`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param("sssssssd", $name, $email, $phone, $address, $city, $payment, $cartItemsJson, $totalPrice);
                    $result = $stmt->execute();
                    if (!$result) {
                        error_log("Insert error: " . $stmt->error);
                    }
                    $stmt->close();
                }
            } elseif (in_array('total_price', $existingColumns)) {
                $userId = 0;
                $stmt = $conn->prepare("INSERT INTO `orders` (`user_id`, `total_price`) VALUES (?, ?)");
                if ($stmt) {
                    $stmt->bind_param("id", $userId, $totalPrice);
                    $result = $stmt->execute();
                    if (!$result) {
                        error_log("Insert error: " . $stmt->error);
                    }
                    $stmt->close();
                }
            } else {
                error_log("Orders table does not contain expected columns.");
            }

            $conn->close();
        }

        echo "Order placed successfully!";
    } else {
        echo "Please fill all required fields and make sure your cart has at least one item.";
    }
}
?>
                </div>
            </div>
        </div>
    </div>
    <div id="sizeGuideModal" class="modal">
        <div class="coozy-custom-modal animate">
            <span class="close-x" onclick="closeSizeModal()">&times;</span>
            <div class="modal-header"><div class="header-icon">📏</div><h2>Size Guide</h2></div>
            <div class="modal-body">
                <p>Our socks are made from premium elastic cotton, designed to fit perfectly!</p>
                <div class="info-card">
                    <div class="size-row"><span>EU Size:</span> <strong>36 - 42</strong></div>
                    <div class="size-row"><span>UK Size:</span> <strong>4 - 8</strong></div>
                    <div class="size-row"><span>US Size:</span> <strong>6 - 10</strong></div>
                </div>
            </div>
            <button class="coozy-btn" onclick="closeSizeModal()">GOT IT</button>
        </div>
    </div>
    <div id="shippingModal" class="modal">
        <div class="coozy-custom-modal animate">
            <span class="close-x" onclick="closeShippingModal()">&times;</span>
            <div class="modal-header"><div class="header-icon">🚚</div><h2>Shipping & Returns</h2></div>
            <div class="modal-body">
                <div class="policy-item"><h3>Delivery Time</h3><p>Fast delivery within 2 to 5 business days all over Egypt.</p></div>
                <div class="policy-item"><h3>Return Policy</h3><p>14-day easy returns if the product is in its original condition.</p></div>
            </div>
            <button class="coozy-btn" onclick="closeShippingModal()">UNDERSTOOD</button>
        </div>
    </div>
    <div id="privacyModal" class="modal">
        <div class="coozy-custom-modal animate">
            <span class="close-x" onclick="closePrivacyModal()">&times;</span>
            <div class="modal-header"><div class="header-icon">🔒</div><h2>Privacy Policy</h2></div>
            <div class="modal-body">
                <div class="privacy-box">
                    <p>We care about your data. We only use your information to deliver your cozy orders safely.</p>
                </div>
            </div>
            <button class="coozy-btn" onclick="closePrivacyModal()">I AGREE</button>

        </div>
    </div>
    <script>
        function addToCart(product) {
    let cart = JSON.parse(localStorage.getItem('coozyCart')) || [];

    cart.push({
        product_id: product.id,   // ✅ THIS IS THE KEY FIX
        name: product.name,
        price: product.price,
        qty: 1,
        pic: product.image
    });

    localStorage.setItem('coozyCart', JSON.stringify(cart));
}
        function openNav() {
            document.getElementById("sideNav").style.left = "0";
            document.getElementById("main").style.marginLeft = "300px";
        }
        function closeNav() {
            document.getElementById("sideNav").style.left = "-320px";
            document.getElementById("main").style.marginLeft = "0";
        }
        function openSizeModal() { document.getElementById('sizeGuideModal').style.display = 'flex'; }
        function closeSizeModal() { document.getElementById('sizeGuideModal').style.display = 'none'; }
        function openShippingModal() { document.getElementById('shippingModal').style.display = 'flex'; }
        function closeShippingModal() { document.getElementById('shippingModal').style.display = 'none'; }
        function openPrivacyModal() { document.getElementById('privacyModal').style.display = 'flex'; }
        function closePrivacyModal() { document.getElementById('privacyModal').style.display = 'none'; }
        window.onclick = function(event) {
            let modals = ['sizeGuideModal', 'shippingModal', 'privacyModal'];
            modals.forEach(id => {
                let modal = document.getElementById(id);
                if (event.target == modal) { modal.style.display = "none"; }
            });
        }
        function displayCart() {
            const savedCart = JSON.parse(localStorage.getItem('coozyCart')) || [];
            const container = document.getElementById('checkout-cart-container');
            let subtotal = 0;
            if (savedCart.length === 0) {
                container.innerHTML = "<p style='text-align:center; color:#999;'>Your cart is empty!</p>";
                return;
            }
            container.innerHTML = ""; 
            savedCart.forEach(item => {
                subtotal += item.price * item.qty;
                container.innerHTML += `
                <div class="cart-item-row">
                    <div class="item-info">
                        <img src="${item.pic}">
                        <div>
                            <h4 style="margin:0; font-size:14px;">${item.name}</h4>
                            <p style="margin:0; font-size:12px; color:#777;">Qty: ${item.qty}</p>
                        </div>
                    </div>
                    <span style="font-weight: bold;">$${item.price * item.qty}</span>
                </div>`;
            });
            document.getElementById('sub-val').innerText = subtotal;
            document.getElementById('total-val').innerText = subtotal + 50;
        }
        function processOrder(event) {
            event.preventDefault();
            const form = document.getElementById('checkoutForm');
            if (form.checkValidity()) {
                const savedCart = JSON.parse(localStorage.getItem('coozyCart')) || [];
                if (savedCart.length === 0) {
                    alert('Your cart is empty!');
                    return;
                }

                document.getElementById('cartItemsInput').value = JSON.stringify(savedCart);
                const formData = new FormData(form);

                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    const container = document.getElementById('checkout-container');
                    container.innerHTML = `
                    <div class="success-wrapper">
                        <div class="check-circle">✓</div>
                        <h1>Congratulations!</h1>
                        <p>Order has been placed successfully.</p>
                        <button onclick="window.location.href='index.php'" class="pay-now-btn" style="max-width: 250px; margin: 20px auto;">
                            Back to Home
                        </button>
                    </div>`;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    localStorage.removeItem('coozyCart');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('There was an error processing your order.');
                });
            } else {
                form.reportValidity();
            }
        }
        window.onload = displayCart;
    </script>
    
</body>
</html>