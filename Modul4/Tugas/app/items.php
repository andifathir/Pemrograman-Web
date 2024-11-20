<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Watches - Items</title>
    <link rel="stylesheet" href="style.css" />
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css"
      rel="stylesheet"
    />
</head>
<body>
    <!-- Header Section -->
    <div class="navbar">
        <div class="logo">
            <i class="ri-hourglass-2-line"></i>
            <span>TIMELESS</span>
        </div>
        <nav class="navbar-items h-class">
            <ul class="nav v-class">
                <li><a href="index.html">HOME</a></li>
                <li><a href="#ABOUT">ABOUT</a></li>
                <li><a href="#CATEGORY">CATEGORY</a></li>
                <li><a href="items.php">ITEMS</a></li>
            </ul>
        </nav>
        <div class="burger"><i class="ri-menu-4-line"></i></div>
    </div>

    <!-- Items Section -->
    <section class="items" id="ITEMS">
        <div class="items-content">
            <h1>All Watch Products</h1>
            <div class="items-container">
                <?php
                // Connect to the database
                $host = "localhost";
                $dbname = "timeless";
                $username = "root"; // Replace with your database username
                $password = ""; // Replace with your database password

                try {
                    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Fetch all products
                    $stmt = $conn->prepare("SELECT * FROM products");
                    $stmt->execute();
                    $products = $stmt->fetchAll();

                    // Display products
                    foreach ($products as $product) {
                        echo "
                        <div class='item-card'>
                            <img src='{$product['image_url']}' alt='{$product['name']}' />
                            <h2>{$product['name']}</h2>
                            <p>\${$product['price']}</p>
                            <button class='btn'>View Details</button>
                        </div>
                        ";
                    }
                } catch (PDOException $e) {
                    echo "Connection failed: " . $e->getMessage();
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="social-media">
                <h3>Follow Us</h3>
                <a href="#"
                  ><img src="assets/icon/facebook-line.svg" alt="Facebook"
                /></a>
                <a href="#"
                  ><img src="assets/icon/instagram-line.svg" alt="Instagram"
                /></a>
                <a href="#"
                  ><img src="assets/icon/twitter-x-line.svg" alt="Twitter"
                /></a>
                <a href="#"><img src="assets/icon/mail-line.svg" alt="Mail" /></a>
            </div>

            <div class="contact-info">
                <h4>Contact Us</h4>
                <p>Email: support@timeless.com</p>
                <p>Phone: (123) 456-7890</p>
            </div>

            <div class="copyright">
                <a href="#">TimeLess</a>
                <p>&copy; 2024 TimeLess. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="index.js"></script>
</body>
</html>
