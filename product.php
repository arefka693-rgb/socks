<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coozy Socks - product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="sideNav" id="sideNav">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="product.php">product</a></li>
            <li><a href="login.php">Login</a></li>
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
    <span id="cart" onclick="openCart()"><img src="cart.svg" alt="Cart"></span>

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

        <div class="search-wrapper">
            <div class="search-box">
                <input type="text" placeholder="Search for your favorite socks...">
            </div>
        </div>

        <?php
        $conn = mysqli_connect("localhost", "root", "", "socksstore_db");
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Create products table if it doesn't exist
        $createTableSQL = "CREATE TABLE IF NOT EXISTS `products` (
            `product_id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(255) NOT NULL,
            `price` DECIMAL(10, 2) NOT NULL,
            `image` VARCHAR(255) NOT NULL
        )";
        mysqli_query($conn, $createTableSQL);

        $stmt = $conn->prepare("SELECT product_id, name, price, image FROM products");
        if (!$stmt) {
            echo "Error preparing statement: " . $conn->error;
        } else {
            $stmt->execute();
            $result = $stmt->get_result();

            echo '<div class="cardsContainer">';
            while ($row = $result->fetch_assoc()) {
                echo '
                <div class="card" onclick="openProductModal(\''.$row['product_id'].'\', \''.$row['name'].'\', '.$row['price'].', \''.$row['image'].'\', \'Nice comfy socks for everyday use.\')">
                    <img src="'.$row['image'].'" alt="">
                    <div class="cardDetails">
                        <h2>'.$row['name'].'</h2>
                        <p>Nice comfy socks for everyday use.</p>
                        <h2>$'.$row['price'].'</h2>
                        <div class="btnContainer">
                            <button class="btn" onclick="event.stopPropagation(); addToCart(\''.$row['product_id'].'\', \''.$row['name'].'\', \''.$row['image'].'\', '.$row['price'].')">Add to Cart</button>
                        </div>
                    </div>
                </div>';
            }
            echo '</div>';
            $stmt->close();
        }
        $conn->close();
        ?>

        <div class="footer">
            <div class="footer-grid">
                <div class="footer-col">
                    <h3 class="subscribe-title">Subscribe Now</h3>
                    <div class="newsletter-box">
                        <div class="input-container">
                            <span class="mail-icon">✉</span>
                            <input type="email" placeholder="Enter your Email">
                        </div>
                        <button class="subscribe-btn">Subscribe</button>
                    </div>
                </div>
                <div class="footer-col">
                    <h3>Explore</h3>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="product.php">product</a></li>
                        <li><a href="best sellers.php">Best Sellers</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3>Customer Support</h3>
                    <ul>
                        <li><a href="javascript:void(0)" onclick="openSizeModal()">Size Guide</a></li>
                        <li><a href="javascript:void(0)" onclick="openShippingModal()">Shipping & Returns</a></li>
                        <li><a href="javascript:void(0)" onclick="openPrivacyModal()">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="footer-col contact-col">
                    <h3>Contact Us</h3>
                    <ul class="wrapper">
                        <li class="icon facebook">
                            <span class="tooltip">Facebook</span>
                            <svg viewBox="0 0 320 512" height="1.2em" fill="currentColor"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>
                        </li>
                        <li class="icon twitter">
                            <span class="tooltip">Twitter</span>
                            <svg viewBox="0 0 512 512" height="1.2em" fill="currentColor"><path d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"/></svg>
                        </li>
                        <li class="icon instagram">
                            <span class="tooltip">Instagram</span>
                            <svg height="1.2em" fill="currentColor" viewBox="0 0 16 16"><path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/></svg>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 Coozy Socks. All rights reserved.</p>
            </div>
        </div>
    </div>

    <!-- ========== CART MODAL ========== -->
    <div id="cartModal">
        <div class="cart-header">
            <h3>Your Cart</h3>
            <span onclick="closeCart()">✕</span>
        </div>
        <div id="cartItemsList"></div>
        <div class="cart-footer">
            <p>Total: $<span id="cartTotal">0</span></p>
            <button class="btn" onclick="goToCheckout()">Checkout</button>
        </div>
    </div>

    <!-- ========== PRODUCT DETAIL MODAL ========== -->
    <div id="productDetailModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; justify-content:center; align-items:center;">
        <div style="background:#faf8f5; padding:30px; border-radius:12px; width:400px; position:relative;">
            <span onclick="closeProductModal()" style="position:absolute; top:15px; right:20px; font-size:22px; cursor:pointer; color:#8b1a1a;">✕</span>
            <img id="modalImg" src="" alt="" style="width:100%; border-radius:8px;">
            <h2 id="modalTitle"></h2>
            <p id="modalDesc"></p>
            <h3 id="modalPrice"></h3>
            <input type="number" id="modalQty" value="1" min="1" style="width:60px; padding:5px;">
            <button class="btn" onclick="addFromModal()">Add to Cart</button>
        </div>
    </div>

    <!-- ========== SIZE / SHIPPING / PRIVACY MODALS ========== -->
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
                <p class="small-note">* One size fits most thanks to our cozy stretch technology.</p>
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
                    <p>We care about your data. We only use your information (Name, Address, Phone) to deliver your cozy orders safely.</p>
                    <p>Your data is encrypted and will never be shared with anyone else.</p>
                </div>
            </div>
            <button class="coozy-btn" onclick="closePrivacyModal()">I AGREE</button>
        </div>
    </div>

    <script>
        let cartItems = [];

        // ===== PRODUCT MODAL =====
        function openProductModal(id, title, price, img, desc) {
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalPrice').innerText = price;
            document.getElementById('modalImg').src = img;
            document.getElementById('modalDesc').innerText = desc;
            document.getElementById('modalQty').value = 1;
            // Store product_id for later use
            document.getElementById('productDetailModal').setAttribute('data-product-id', id);
            document.getElementById('productDetailModal').style.display = 'flex';
        }
        function closeProductModal() {
            document.getElementById('productDetailModal').style.display = 'none';
        }

        // ===== NAV =====
        function openNav() {
            document.getElementById('sideNav').style.left = '0px';
            document.getElementById('main').style.paddingLeft = '300px';
        }
        function closeNav() {
            document.getElementById('sideNav').style.left = '-320px';
            document.getElementById('main').style.paddingLeft = '0';
            document.getElementById('cartModal').style.right = '-400px';
            document.getElementById('main').style.paddingRight = '0px';
        }

        // ===== CART =====
        function openCart() {
            document.getElementById('cartModal').style.right = '0px';
            document.getElementById('main').style.paddingRight = '400px';
        }
        function closeCart() {
            document.getElementById('cartModal').style.right = '-400px';
            document.getElementById('main').style.paddingRight = '0px';
        }
        function addToCart(product_id, name, pic, price) {
            let numPrice = parseFloat(price);
            let existingItem = cartItems.find(item => item.product_id === product_id);
            if (existingItem) { existingItem.qty++; } else { cartItems.push({ product_id, name, pic, price: numPrice, qty: 1 }); }
            // Save to localStorage
            localStorage.setItem('coozyCart', JSON.stringify(cartItems));
            renderCart();
            openCart();
        }
        function addFromModal() {
            let product_id = document.getElementById('productDetailModal').getAttribute('data-product-id');
            let title    = document.getElementById('modalTitle').innerText;
            let img      = document.getElementById('modalImg').src;
            let priceStr = document.getElementById('modalPrice').innerText;
            let qty      = parseInt(document.getElementById('modalQty').value);
            let price    = parseFloat(priceStr.replace('$', ''));
            let existingItem = cartItems.find(item => item.product_id === product_id);
            if (existingItem) { existingItem.qty += qty; } else { cartItems.push({ product_id, name: title, pic: img, price, qty }); }
            // Save to localStorage
            localStorage.setItem('coozyCart', JSON.stringify(cartItems));
            renderCart();
            closeProductModal();
            openCart();
        }
        function renderCart() {
            let list      = document.getElementById('cartItemsList');
            let totalSpan = document.getElementById('cartTotal');
            let total     = 0;
            list.innerHTML = "";
            cartItems.forEach((item, index) => {
                total += item.price * item.qty;
                list.innerHTML += `
                    <div class="cartItem">
                        <img src="${item.pic}" width="80">
                        <div class="cartDetails">
                            <h4>${item.name}</h4>
                            <p>$${item.price}</p>
                            <div class="qty-controls">
                                <button class="qty-btn" onclick="changeQty(${index}, -1)">-</button>
                                <span>${item.qty}</span>
                                <button class="qty-btn" onclick="changeQty(${index}, 1)">+</button>
                            </div>
                        </div>
                    </div>`;
            });
            totalSpan.innerText = total.toFixed(2);
        }
        function changeQty(index, delta) {
            cartItems[index].qty += delta;
            if (cartItems[index].qty <= 0) cartItems.splice(index, 1);
            // Save to localStorage
            localStorage.setItem('coozyCart', JSON.stringify(cartItems));
            renderCart();
        }
        function goToCheckout() {
            if (cartItems.length === 0) {
                alert('Your cart is empty!');
                return;
            }
            window.location.href = 'checkout.php';
        }
        // Load cart from localStorage on page load
        window.addEventListener('DOMContentLoaded', function() {
            const savedCart = localStorage.getItem('coozyCart');
            if (savedCart) {
                cartItems = JSON.parse(savedCart);
                renderCart();
            }
        });

        // ===== INFO MODALS =====
        function openSizeModal()      { document.getElementById('sizeGuideModal').style.display = 'flex'; }
        function closeSizeModal()     { document.getElementById('sizeGuideModal').style.display = 'none'; }
        function openShippingModal()  { document.getElementById('shippingModal').style.display = 'flex'; }
        function closeShippingModal() { document.getElementById('shippingModal').style.display = 'none'; }
        function openPrivacyModal()   { document.getElementById('privacyModal').style.display = 'flex'; }
        function closePrivacyModal()  { document.getElementById('privacyModal').style.display = 'none'; }

        // ===== CLOSE ON OUTSIDE CLICK =====
        window.onclick = function(event) {
            const modals = ['productDetailModal', 'sizeGuideModal', 'shippingModal', 'privacyModal'];
            modals.forEach(id => {
                let modal = document.getElementById(id);
                if (event.target == modal) modal.style.display = 'none';
            });
        }
    </script>
</body>
</html>