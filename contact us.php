<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coozy Socks - Contact Us</title>
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
            <li><a href="login.php">Login</a></li>
            <li><a href="signup.php">Sign Up</a></li>
            <li><a href="profile.php">
  <img src="profile.jpg" alt="Profile Icon" style="width:30px; cursor:pointer;">
</a></li>
        </ul>
    </div>
    <span id="burgerMenu" onclick="openNav()">&#9776;</span>
    <span id="viewMessages" onclick="toggleMessagesView()" style="position: fixed; top: 20px; right: 80px; cursor: pointer; font-size: 24px; z-index: 100;">💬</span>
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
        <div class="contact-page-wrapper"> 
            <div class="contact-container">
              <h3>Contact Us</h3>
              <form id="contactForm" method="POST" action="" class="contact-form" onsubmit="handleContactSubmit(event)">
                <label for="fname">Name</label>
                <input type="text" id="fname" name="firstname" placeholder="Enter your name.." required>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your Email.." required>
                <label for="country">Country</label>
                <select id="country" name="country" class="contact-select">
                  <option value="egypt">Egypt</option>
                  <option value="canada">Canada</option>
                  <option value="usa">USA</option>
                </select>
                <label for="subject">Subject</label>
                <textarea id="subject" name="subject" placeholder="How can we help you?" style="height:150px" required></textarea>
                <input type="submit" value="Send Message" class="contact-submit">
              </form>
            </div>
        </div>
    </div>
    <div id="sizeGuideModal" class="modal">
        <div class="coozy-custom-modal animate">
            <span class="close-x" onclick="closeSizeModal()">&times;</span>
            <div class="modal-header">
                <div class="header-icon">📏</div>
                <h2>Size Guide</h2>
            </div>
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
            <div class="modal-header">
                <div class="header-icon">🚚</div>
                <h2>Shipping & Returns</h2>
            </div>
            <div class="modal-body">
                <div class="policy-item">
                    <h3>Delivery Time</h3>
                    <p>Fast delivery within 2 to 5 business days all over Egypt.</p>
                </div>
                <div class="policy-item">
                    <h3>Return Policy</h3>
                    <p>14-day easy returns if the product is in its original condition.</p>
                </div>
            </div>
            <button class="coozy-btn" onclick="closeShippingModal()">UNDERSTOOD</button>
        </div>
    </div>
    <div id="privacyModal" class="modal">
        <div class="coozy-custom-modal animate">
            <span class="close-x" onclick="closePrivacyModal()">&times;</span>
            <div class="modal-header">
                <div class="header-icon">🔒</div>
                <h2>Privacy Policy</h2>
            </div>
            <div class="modal-body">
                <div class="privacy-box">
                    <p>We care about your data. We only use your information (Name, Address, Phone) to deliver your cozy orders safely.</p>
                    <p>Your data is encrypted and will never be shared with anyone else.</p>
                </div>
                <button class="coozy-btn" onclick="closePrivacyModal()">I AGREE</button>
            </div>
        </div>
    </div>
    <div id="successModal" class="modal" style="display:none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); justify-content: center; align-items: center;">
        <div class="coozy-custom-modal animate" style="background: white; padding: 30px; border-radius: 20px; text-align: center; max-width: 400px; width: 90%; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
            <div style="font-size: 50px; margin-bottom: 15px;">✅</div>
            <h2 style="color: #8b1e1e; margin-bottom: 10px;">Message Sent!</h2>
            <p style="color: #555; margin-bottom: 20px;">Your message has been sent successfully. We will get back to you soon!</p>
            <button onclick="closeSuccessModal()" style="background-color: #8b1e1e; color: white; border: none; padding: 12px 30px; border-radius: 8px; font-weight: bold; cursor: pointer; width: 100%;">Close</button>
        </div>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve POST data safely using the null-coalescing operator
        $name = isset($_POST["firstname"]) ? trim($_POST["firstname"]) : '';
        $email = isset($_POST["email"]) ? trim($_POST["email"]) : '';
        $country = isset($_POST["country"]) ? trim($_POST["country"]) : '';
        $message = isset($_POST["subject"]) ? trim($_POST["subject"]) : '';

        // Validate data
        if (!empty($name) && !empty($email) && !empty($message)) {
            $conn = mysqli_connect("localhost", "root", "", "socksstore_db");

            if ($conn) {
                // Create table if it doesn't exist
                $createTableSQL = "CREATE TABLE IF NOT EXISTS `contact_messages` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `name` VARCHAR(255) NOT NULL,
                    `email` VARCHAR(255) NOT NULL,
                    `country` VARCHAR(100),
                    `message` LONGTEXT NOT NULL,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )";
                mysqli_query($conn, $createTableSQL);

                // Use prepared statements for security (prevents SQL injection)
                $stmt = $conn->prepare("INSERT INTO `contact_messages` (`name`, `email`, `country`, `message`) VALUES (?, ?, ?, ?)");

                if ($stmt) {
                    $stmt->bind_param("ssss", $name, $email, $country, $message);
                    $result = $stmt->execute();

                    if ($result == FALSE) {
                        error_log("Database error: " . $stmt->error);
                    } else {
                        // Success - message saved to database
                    }
                    $stmt->close();
                }
                $conn->close();
            }
        }
    }
    ?>

    <script>
        function openNav() { 
            document.getElementById('sideNav').style.left = '0px'; 
            document.getElementById('main').style.paddingLeft = '300px'; 
        }
        function closeNav() { 
            document.getElementById('sideNav').style.left = '-320px'; 
            document.getElementById('main').style.paddingLeft = '0'; 
        }
        function openSizeModal() { document.getElementById('sizeGuideModal').style.display = 'flex'; }
        function closeSizeModal() { document.getElementById('sizeGuideModal').style.display = 'none'; }
        function openShippingModal() { document.getElementById('shippingModal').style.display = 'flex'; }
        function closeShippingModal() { document.getElementById('shippingModal').style.display = 'none'; }
        function openPrivacyModal() { document.getElementById('privacyModal').style.display = 'flex'; }
        function closePrivacyModal() { document.getElementById('privacyModal').style.display = 'none'; }
        function handleContactSubmit(event) {
            event.preventDefault();
            const form = document.getElementById('contactForm');
            if (form.checkValidity()) {
                // Submit form directly instead of fetch
                const formData = new FormData(form);
                
                // Create a simple POST request using fetch
                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    // Show success modal after submission
                    setTimeout(() => {
                        document.getElementById('successModal').style.display = 'flex';
                        form.reset();
                    }, 500);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('There was an error sending your message.');
                });
            } else {
                form.reportValidity();
            }
        }
        function closeSuccessModal() { document.getElementById('successModal').style.display = 'none'; }
        function toggleMessagesView() {
            const modal = document.getElementById('messagesModal');
            modal.style.display = modal.style.display === 'none' ? 'block' : 'none';
        }
        window.onclick = function(event) {
            let modals = ['successModal', 'sizeGuideModal', 'shippingModal', 'privacyModal'];
            modals.forEach(id => {
                let modal = document.getElementById(id);
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            });
            const messagesModal = document.getElementById('messagesModal');
            if (event.target === messagesModal) {
                messagesModal.style.display = 'none';
            }
        }
    </script>
</body>
</html>