<?php
include 'db/db.php'; // include your DB connection file

// Fetch contact info for admin (assuming id=1 or use WHERE username='admin')
 $sql = "SELECT business_address, phone_number, email, business_hours FROM users WHERE id = 1";
 $result = $conn->query($sql);
 $contact = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>International Orders & Shipping Policy | Bhoomi Trade Line</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" sizes="96x96" href="favicon1.png">
  <style>
    /* ===== FONTS ===== */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');
    
    /* ===== BASIC RESET ===== */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    :root {
      --primary-color: #ff7b00;
      --secondary-color: #cc6300;
      --accent-color: #ff9500;
      --text-dark: #333;
      --text-light: #666;
      --bg-light: #fffaf3;
      --bg-white: #ffffff;
      --shadow: 0 5px 15px rgba(0,0,0,0.1);
      --shadow-hover: 0 8px 25px rgba(0,0,0,0.15);
    }

    body {
      font-family: 'Poppins', sans-serif;
      font-weight: 400; /* Default to regular weight */
      font-size: 16px; /* Base font size for better readability */
      line-height: 1.6; /* Better line spacing */
      background-color: var(--bg-light);
      color: var(--text-dark);
      overflow-x: hidden;
      scroll-behavior: smooth;
    }

    /* ===== HEADER ===== */
    header {
      background-color: var(--bg-white);
      color: var(--primary-color);
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 50px;
      position: sticky;
      top: 0;
      z-index: 100;
      box-shadow: var(--shadow);
      transition: all 0.3s ease;
    }

    header.scrolled {
      padding: 8px 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    header img {
      height: 60px;
      width: auto;
      object-fit: contain;
      transition: transform 0.3s ease;
    }

    header img:hover {
      transform: scale(1.05);
    }

    nav {
      display: flex;
      align-items: center;
    }

    nav a {
      color: var(--primary-color);
      text-decoration: none;
      margin: 0 15px;
      font-weight: 600;
      position: relative;
      transition: color 0.3s;
      font-size: 15px;
    }

    nav a::after {
      content: '';
      position: absolute;
      bottom: -5px;
      left: 0;
      width: 0;
      height: 2px;
      background-color: var(--primary-color);
      transition: width 0.3s;
    }

    nav a:hover::after {
      width: 100%;
    }

    nav a:hover {
      color: var(--secondary-color);
    }

    .mobile-menu-btn {
      display: none;
      background: none;
      border: none;
      font-size: 24px;
      color: var(--primary-color);
      cursor: pointer;
      z-index: 101;
      transition: transform 0.3s ease;
    }

    .mobile-menu-btn:hover {
      transform: scale(1.1);
    }

    /* ===== PAGE BANNER ===== */
    .page-banner {
      background: linear-gradient(135deg, rgba(255,123,0,0.8), rgba(255,149,0,0.7)), url('https://images.unsplash.com/photo-1586201375761-83865001e31c?auto=format&fit=crop&w=1600&q=80');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      height: 35vh;
      min-height: 250px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      color: white;
      position: relative;
    }

    .page-banner::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.3);
      z-index: 1;
    }

    .page-banner h1 {
      font-size: clamp(1.8rem, 6vw, 2.5rem);
      font-weight: 800;
      text-shadow: 2px 2px 10px rgba(0,0,0,0.3);
      margin-bottom: 15px;
      letter-spacing: -1px;
      position: relative;
      z-index: 2;
    }

    .breadcrumb {
      font-size: clamp(0.85rem, 2vw, 1rem);
      opacity: 0.9;
      position: relative;
      z-index: 2;
    }

    .breadcrumb a {
      color: white;
      text-decoration: none;
      margin: 0 5px;
      transition: opacity 0.3s;
    }

    .breadcrumb a:hover {
      opacity: 0.8;
    }

    /* ===== POLICY CONTENT ===== */
    .policy-content {
      padding: 40px 20px;
      background-color: var(--bg-white);
      max-width: 900px;
      margin: 0 auto;
    }

    .policy-section {
      margin-bottom: 35px;
    }

    .policy-section h2 {
      color: var(--primary-color);
      font-size: clamp(1.4rem, 4vw, 1.8rem);
      margin-bottom: 15px;
      border-bottom: 2px solid var(--primary-color);
      padding-bottom: 8px;
    }

    .policy-section h3 {
      color: var(--primary-color);
      font-size: clamp(1.1rem, 3.5vw, 1.3rem);
      margin: 20px 0 15px;
    }

    .policy-section p {
      color: var(--text-light);
      line-height: 1.7;
      font-size: clamp(0.9rem, 2.5vw, 1rem);
      margin-bottom: 15px;
    }

    .policy-section ul {
      color: var(--text-light);
      line-height: 1.7;
      font-size: clamp(0.9rem, 2.5vw, 1rem);
      margin-bottom: 15px;
      padding-left: 20px;
    }

    .policy-section li {
      margin-bottom: 12px;
      position: relative;
      padding-left: 25px;
    }

    .policy-section li::before {
      content: '•';
      position: absolute;
      left: 0;
      color: var(--primary-color);
      font-weight: bold;
    }

    .highlight-box {
      background-color: var(--bg-light);
      border-left: 4px solid var(--primary-color);
      padding: 20px;
      margin: 25px 0;
      border-radius: 8px;
    }

    .highlight-box h3 {
      color: var(--primary-color);
      font-size: clamp(1.1rem, 3.5vw, 1.3rem);
      margin-bottom: 15px;
    }

    .highlight-box p {
      margin-bottom: 0;
      font-size: clamp(0.9rem, 2.5vw, 1rem);
    }

    /* ===== FOOTER ===== */
    footer {
      background-color: #333;
      color: white;
      padding: 50px 20px 15px;
    }

    .footer-content {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      max-width: 1000px;
      margin: 0 auto 30px;
    }

    .footer-section {
      flex: 1;
      min-width: 200px;
      margin-bottom: 25px;
      padding-right: 20px;
    }

    .footer-section h3 {
      color: var(--primary-color);
      font-size: 1.2rem;
      margin-bottom: 15px;
      position: relative;
      padding-bottom: 8px;
    }

    .footer-section h3::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 40px;
      height: 2px;
      background-color: var(--primary-color);
    }

    .footer-section p,
    .footer-section ul {
      color: #ccc;
      line-height: 1.6;
      font-size: 0.9rem;
    }

    .footer-section ul {
      list-style: none;
    }

    .footer-section ul li {
      margin-bottom: 8px;
      transition: transform 0.3s ease;
    }

    .footer-section ul li:hover {
      transform: translateX(5px);
    }

    .footer-section a {
      color: #ccc;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .footer-section a:hover {
      color: var(--primary-color);
    }

    .footer-section a.admin-btn {
      background: white;
      color: var(--primary-color);
      border: 2px solid var(--primary-color);
      padding: 10px 20px;
      border-radius: 50px;
      font-weight: 600;
      font-size: 0.9rem;
      cursor: pointer;
      transition: all 0.3s ease;
      display: inline-block;
      text-decoration: none;
      margin-top: 10px;
    }

    .footer-section a.admin-btn:hover {
      transform: translateY(-3px);
      color: var(--secondary-color);
      box-shadow: 0 5px 15px rgba(255, 123, 0, 0.3);
    }

    .social-links {
      display: flex;
      gap: 12px;
      margin-top: 15px;
    }

    .social-link {
      width: 35px;
      height: 35px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      color: white;
      transition: all 0.3s ease;
    }

    .social-link:hover {
      background: var(--primary-color);
      transform: translateY(-3px);
    }

    .footer-bottom {
      text-align: center;
      padding-top: 15px;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      color: #aaa;
      font-size: 0.8rem;
    }

    /* ===== BACK TO TOP BUTTON ===== */
    .back-to-top {
      position: fixed;
      bottom: 30px;
      right: 20px;
      width: 45px;
      height: 45px;
      background: var(--primary-color);
      color: white;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 1rem;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
      z-index: 99;
      cursor: pointer;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .back-to-top.active {
      opacity: 1;
      visibility: visible;
    }

    .back-to-top:hover {
      background: var(--secondary-color);
      transform: translateY(-5px);
    }

    /* ===== WHATSAPP BUTTON ===== */
    .whatsapp-btn {
      position: fixed;
      bottom: 90px;
      right: 20px;
      width: 50px;
      height: 50px;
      background-color: #25D366;
      color: white;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 1.5rem;
      z-index: 100;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
      transition: all 0.3s ease;
      text-decoration: none;
      opacity: 0;
      visibility: hidden;
    }

    .whatsapp-btn.active {
      opacity: 1;
      visibility: visible;
    }

    .whatsapp-btn:hover {
      transform: scale(1.1);
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4);
      background-color: #128C7E;
    }

    .whatsapp-btn i {
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% {
        transform: scale(1);
      }
      50% {
        transform: scale(1.1);
      }
      100% {
        transform: scale(1);
      }
    }

    /* ===== MOBILE MENU OVERLAY ===== */
    .menu-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 98;
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    .menu-overlay.active {
      opacity: 1;
      visibility: visible;
    }

    /* ===== RESPONSIVE DESIGN ===== */
    @media (max-width: 768px) {
      header {
        padding: 10px 20px;
      }

      .mobile-menu-btn {
        display: block;
      }

      nav {
        position: fixed;
        top: 0;
        right: -100%;
        width: 80%;
        max-width: 300px;
        height: 100vh;
        background-color: var(--bg-white);
        flex-direction: column;
        justify-content: flex-start;
        align-items: flex-start;
        padding: 70px 20px;
        box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
        transition: right 0.3s ease;
        z-index: 99;
        overflow-y: auto;
      }

      nav.active {
        right: 0;
      }

      nav a {
        margin: 15px 0;
        font-size: 1.1rem;
        width: 100%;
      }

      .page-banner {
        height: 25vh;
        min-height: 200px;
      }

      .page-banner h1 {
        font-size: clamp(1.5rem, 7vw, 2rem);
      }

      .policy-content {
        padding: 30px 15px;
      }
      
      .policy-section h2 {
        font-size: clamp(1.2rem, 4vw, 1.5rem);
      }
      
      .policy-section h3 {
        font-size: clamp(1rem, 3vw, 1.2rem);
        margin: 15px 0 10px;
      }
      
      .policy-section p {
        font-size: clamp(0.85rem, 2.5vw, 0.95rem);
        margin-bottom: 12px;
      }
      
      .policy-section ul {
        font-size: clamp(0.85rem, 2.5vw, 0.95rem);
        margin-bottom: 12px;
        padding-left: 15px;
      }
      
      .policy-section li {
        margin-bottom: 10px;
        padding-left: 20px;
      }

      .highlight-box {
        margin: 20px 0;
        padding: 15px;
      }
      
      .highlight-box h3 {
        font-size: clamp(1rem, 3vw, 1.2rem);
      }
      
      .highlight-box p {
        font-size: clamp(0.85rem, 2.5vw, 0.95rem);
      }
      
      .footer-content {
        flex-direction: column;
        gap: 25px;
      }
      
      /* WhatsApp button adjustments for mobile */
      .whatsapp-btn {
        bottom: 20px;
        right: 20px;
        width: 45px;
        height: 45px;
        font-size: 1.3rem;
      }
      
      .back-to-top {
        bottom: 75px;
        right: 20px;
        width: 40px;
        height: 40px;
        font-size: 0.9rem;
      }
    }

    @media (max-width: 576px) {
      .page-banner h1 {
        font-size: clamp(1.3rem, 8vw, 1.7rem);
      }

      .policy-section h2 {
        font-size: clamp(1.1rem, 4.5vw, 1.4rem);
      }
      
      .policy-section h3 {
        font-size: clamp(0.9rem, 3.5vw, 1.1rem);
        margin: 12px 0 8px;
      }
      
      .policy-section p {
        font-size: clamp(0.8rem, 2.5vw, 0.9rem);
        margin-bottom: 10px;
      }
      
      .policy-section ul {
        font-size: clamp(0.8rem, 2.5vw, 0.9rem);
        margin-bottom: 10px;
        padding-left: 12px;
      }
      
      .policy-section li {
        margin-bottom: 8px;
        padding-left: 15px;
      }

      .highlight-box {
        margin: 15px 0;
        padding: 12px;
      }
      
      .highlight-box h3 {
        font-size: clamp(0.9rem, 3.5vw, 1.1rem);
      }
      
      .highlight-box p {
        font-size: clamp(0.8rem, 2.5vw, 0.9rem);
      }
    }
  </style>
  <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-N325FL6EWX"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-N325FL6EWX');
</script>
</head>

<body>
  <!-- MOBILE MENU OVERLAY -->
  <div class="menu-overlay" id="menuOverlay"></div>
<?php include 'header.php'; ?>

  <!-- HEADER -->
  <header id="header">
    <a href="index.php"><img src="assets/companylogo1.jpg" alt="Company Logo"></a>
    <nav id="nav">
      <a href="index.php">Home</a>
      <a href="about.php">About</a>
      <a href="index.php#products">Products</a>
      <a href="index.php#process">Process</a>
      <a href="index.php#mission-values">Mission & Values</a>
      <a href="index.php#certifications">Certifications</a>
      <a href="index.php#contact">Contact</a>
    </nav>
    <button class="mobile-menu-btn" id="mobileMenuBtn">
      <i class="fas fa-bars"></i>
    </button>
  </header>

  <!-- PAGE BANNER -->
  <section class="page-banner">
    <h1>International Orders & Shipping Policy</h1>
    <div class="breadcrumb">
      <a href="index.php">Home</a> / <span>International Orders & Shipping</span>
    </div>
  </section>

  <!-- POLICY CONTENT -->
  <section class="policy-content">
    <div class="policy-section">
      <h2>Order Processing</h2>
      
      <h3>Order Confirmation</h3>
      <p>Once you place an international order, our team will review your order details and confirm availability of the requested products. You will receive an order confirmation email within 24-48 hours.</p>
      
      <h3>Processing Time</h3>
      <p>The processing time for international orders can vary depending on specific product(s) ordered and quantity. For larger orders, additional processing time may be required. We will inform you of any potential delays.</p>
      
      
    </div>

    <div class="policy-section">
      <h2>Shipping Information</h2>
      
      <h3>Shipping Costs</h3>
      <p>For international orders, our team will calculate the total shipping cost, including any customs duties, taxes, or other fees. We will contact you via email and/or phone to confirm the final shipping cost before proceeding with your order.</p>
      
      <h3>Delivery Time</h3>
      <p>International shipping times can vary significantly depending on the destination country and chosen shipping method. Typically, international orders take 25-30 days to be dispatched. Once dispatched, delivery time will depend on the specific shipping carrier and customs clearance procedures.</p>
      
      
    </div>

    <div class="policy-section">
      <h2>Tracking Your Order</h2>
      <p>Once your order is shipped, you will receive a tracking number via email. You can use this tracking number to monitor the progress of your shipment on the courier's website.</p>
    </div>

    <div class="policy-section">
      <h2>Important Information</h2>
      
      <div class="highlight-box">
        <h3>Customs Duties and Taxes</h3>
        <p>International orders may be subject to customs duties, taxes, or other fees imposed by the destination country. These charges are the sole responsibility of the customer.</p>
      </div>
      
      <div class="highlight-box">
        <h3>Shipping Restrictions</h3>
        <p>Some products may have shipping restrictions to certain countries due to import regulations or product-specific requirements. We will inform you of any restrictions that apply to your order.</p>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer>
    <div class="footer-content">
      <div class="footer-section">
        <img src="assets/footercompanylogo1.jpg" alt="Company Logo" style="height: 140px;width:210px; margin-bottom: 20px;">
        <p>Bhoomi Tradeline is a new venture dedicated to bringing authentic Indian flavors to the world. Through our flagship brand "The Kesari" and carefully selected partner brands, we're committed to delivering quality Indian snacks globally.</p>
        <div class="social-links">
          <a href="https://www.facebook.com/share/17hewC2ecM/" class="social-link">
              <i class="fab fa-facebook-f"></i>
          </a>
          <a href="https://www.instagram.com/thekesrinamkeen" class="social-link">
              <i class="fab fa-instagram"></i>
          </a>
        </div>
      </div>
      <div class="footer-section">
        <h3>Quick Links</h3>
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="aboutus.php">About Us</a></li>
          <li><a href="shipping-policy.php">Shipping Policy</a></li>
          <li><a href="index.php#products">Products</a></li>
          <li><a href="index.php#process">Our Process</a></li>
          <li><a href="index.php#mission-values">Mission & Values</a></li>
          <li><a href="index.php#certifications">Certifications</a></li>
          <li><a href="index.php#contact">Contact Us</a></li>
        </ul>
      </div>
      <div class="footer-section">
        <h3>Products</h3>
        <ul>
         <li><a href="product.php?category=namkeen">Namkeen</a></li>
          <li><a href="product.php?category=Spices-Powder">Spices Powder</a></li>
          <li><a href="product.php?category=Whole-Spices">Whole Spices</a></li>
          <li><a href="product.php?category=Jam">Jam</a></li>
          <li><a href="product.php?category=pickles">Pickles</a></li>
          <li><a href="product.php?category=Ready-to-Eat">Ready to Eat</a></li>
          <li><a href="product.php?category=Sauces">Sauces</a></li>
          <li><a href="product.php?category=Beverages">Beverages</a></li>
          <li><a href="product.php?category=Dry-Bhakhri">Dry Bhakhri</a></li>
          <li><a href="product.php?category=Indian-Sweets">Indian Sweets</a></li>
        </ul>
      </div>
      <div class="footer-section">
         <h3>Contact Info</h3>
  <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($contact['business_address']) ?></p>
  <p><i class="fas fa-phone"></i>+91 <?= htmlspecialchars($contact['phone_number']) ?></p>
  <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($contact['email']) ?> </p>
  <p><i class="fas fa-globe"></i> www.globaltasteexports101.com</p><br/>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2025 Bhoomi Tradeline | All Rights Reserved | Privacy Policy | Terms of Service</p>
    </div>
  </footer>

  <!-- WHATSAPP BUTTON -->
  <a href="https://wa.me/+919979755356?text=Hi%20Bhoomi%20Trade%20Line%2C%20I'm%20interested%20in%20your%20products."
     target="_blank" 
     class="whatsapp-btn" 
     id="whatsappBtn">
    <i class="fab fa-whatsapp"></i>
  </a>

  <!-- BACK TO TOP BUTTON -->
  <div class="back-to-top" id="backToTop">
    <i class="fas fa-arrow-up"></i>
  </div>

  <script>
    // MOBILE MENU
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const nav = document.getElementById('nav');
    const menuOverlay = document.getElementById('menuOverlay');

    // Function to open menu
    function openMenu() {
      nav.classList.add('active');
      menuOverlay.classList.add('active');
      document.body.style.overflow = 'hidden'; // Prevent scrolling when menu is open
      
      // Change hamburger icon to close icon
      mobileMenuBtn.innerHTML = '<i class="fas fa-times"></i>';
    }

    // Function to close menu
    function closeMenu() {
      nav.classList.remove('active');
      menuOverlay.classList.remove('active');
      document.body.style.overflow = ''; // Restore scrolling
      
      // Change close icon back to hamburger icon
      mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
    }

    // Toggle menu when clicking the button
    mobileMenuBtn.addEventListener('click', function() {
      if (nav.classList.contains('active')) {
        closeMenu();
      } else {
        openMenu();
      }
    });

    // Close menu when clicking on overlay
    menuOverlay.addEventListener('click', closeMenu);

    // CLOSE MOBILE MENU WHEN CLICKING ON A LINK
    document.querySelectorAll('#nav a').forEach(link => {
      link.addEventListener('click', closeMenu);
    });

    // STICKY HEADER
    window.addEventListener('scroll', function() {
      const header = document.getElementById('header');
      if (window.scrollY > 100) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    });

    // BACK TO TOP BUTTON
    const backToTop = document.getElementById('backToTop');

    window.addEventListener('scroll', function() {
      if (window.scrollY > 500) {
        backToTop.classList.add('active');
      } else {
        backToTop.classList.remove('active');
      }
    });

    backToTop.addEventListener('click', function() {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });

    // WHATSAPP BUTTON VISIBILITY
    const whatsappBtn = document.getElementById('whatsappBtn');

    window.addEventListener('scroll', function() {
      if (window.scrollY > 300) {
        whatsappBtn.classList.add('active');
      } else {
        whatsappBtn.classList.remove('active');
      }
    });
  </script>
</body>
</html>