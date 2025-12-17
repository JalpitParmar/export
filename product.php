<?php
include 'db/db.php'; // include your DB connection file

// Fetch contact info for admin (assuming id=1 or use WHERE username='admin')
 $sql = "SELECT business_address, phone_number, email, business_hours FROM users WHERE id = 1";
 $result = $conn->query($sql);
 $contact = $result->fetch_assoc();

// Get category and sort parameters from URL
 $selectedCategory = isset($_GET['category']) ? $_GET['category'] : 'all';
 $selectedSort = isset($_GET['sort']) ? $_GET['sort'] : 'featured';

// Fetch products from database - FILTER BY CATEGORY if specified
// Using your exact column names for clarity and safety
 $products_sql = "SELECT `id`, `product_name`, `category`, `packet_sizes`, `description`, `key_features`, `image_path`, `created_at` FROM `products` WHERE 1";

if ($selectedCategory !== 'all') {
    // Use real_escape_string to prevent SQL injection
    $products_sql .= " AND category = '" . $conn->real_escape_string($selectedCategory) . "'";
}

// Apply sorting based on selected sort option
switch ($selectedSort) {
    case 'name':
        $products_sql .= " ORDER BY product_name ASC";
        break;
    case 'featured':
        // CORRECTED: We use 'created_at' to show newest products as "Featured"
        $products_sql .= " ORDER BY created_at ASC"; // Changed to DESC to show newest first
        break;
    default:
        // Default sort is also by newest first
        $products_sql .= " ORDER BY created_at DESC";
}

 $products_result = $conn->query($products_sql);
 $products = [];
if ($products_result && $products_result->num_rows > 0) {
    while ($row = $products_result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bhoomi Trade Line | Indian Food Exporter</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

    html, body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--bg-light);
      color: var(--text-dark);
      overflow-x: hidden !important;
      scroll-behavior: smooth;
      width: 100%;
      max-width: 100%;
      position: relative;
    }

    /* ===== LOADING SCREEN ===== */
    .loader-wrapper {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: var(--bg-white);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
      transition: opacity 0.5s, visibility 0.5s;
    }

    .loader {
      width: 80px;
      height: 80px;
      border: 8px solid rgba(255, 123, 0, 0.2);
      border-top-color: var(--primary-color);
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    /* ===== HEADER ===== */
    header {
      background-color: var(--bg-white);
      color: var(--primary-color);
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 50px;
      position: sticky;
      top: 0;
      z-index: 100;
      box-shadow: var(--shadow);
      transition: all 0.3s ease;
      width: 100%;
      max-width: 100%;
      box-sizing: border-box;
    }

    header.scrolled {
      padding: 8px 5%;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    header img {
       height: 70px;
      width: auto;
      object-fit: contain;
      transition: transform 0.3s ease;
      max-width: 150px;
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
      white-space: nowrap;
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

    /* ===== BREADCRUMB ===== */
    .breadcrumb {
      padding: 20px 5%;
      background-color: var(--bg-white);
      font-size: 0.9em;
      width: 100%;
      max-width: 100%;
      box-sizing: border-box;
    }

    .breadcrumb a {
      color: var(--text-light);
      text-decoration: none;
      transition: color 0.3s;
    }

    .breadcrumb a:hover {
      color: var(--primary-color);
    }

    .breadcrumb span {
      margin: 0 8px;
      color: var(--text-light);
    }

    .breadcrumb .current {
      color: var(--primary-color);
      font-weight: 600;
    }

    /* ===== PRODUCTS HERO ===== */
    .products-hero {
      background-image: 
        linear-gradient(135deg, rgba(255,123,0,0.7), rgba(255,149,0,0.5)),
        url('https://images.unsplash.com/photo-1585032226651-759b368d7246?auto=format&fit=crop&w=1600&q=80');
      background-size: cover;
      background-position: center;
      height: 60vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      color: white;
      position: relative;
      overflow: hidden;
      width: 100%;
      max-width: 100%;
    }

    .products-hero h2 {
      font-size: 3em;
      font-weight: 800;
      text-shadow: 2px 2px 10px rgba(0,0,0,0.3);
      margin-bottom: 20px;
      letter-spacing: -1px;
      padding: 0 20px;
    }

    .products-hero p {
      max-width: 700px;
      margin: 0 auto;
      font-size: 1.2em;
      line-height: 1.6;
      font-weight: 400;
      padding: 0 20px;
    }

    /* ===== PRODUCTS SECTION ===== */
    .products-section {
      padding: 50px 5%;
      background-color: var(--bg-white);
      width: 100%;
      max-width: 100%;
      box-sizing: border-box;
    }

    .products-container {
      max-width: 1400px;
      margin: 0 auto;
      width: 100%;
      box-sizing: border-box;
    }

    /* ===== FILTERS ===== */
    .filters-container {
      display: flex;
      justify-content: space-between;
      margin-bottom: 40px;
      flex-wrap: wrap;
      gap: 20px;
      width: 100%;
      box-sizing: border-box;
    }

    .filter-group {
      display: flex;
      align-items: center;
      gap: 15px;
      flex-wrap: wrap;
    }

    .filter-label {
      font-weight: 600;
      color: var(--text-dark);
      white-space: nowrap;
    }

    .filter-select {
      padding: 10px 15px;
      border: 1px solid #ddd;
      border-radius: 8px;
      background-color: var(--bg-white);
      font-family: inherit;
      font-size: 0.95em;
      cursor: pointer;
      transition: all 0.3s ease;
      min-width: 150px;
    }

    .filter-select:hover {
      border-color: var(--primary-color);
    }

    .filter-select:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 2px rgba(255, 123, 0, 0.2);
    }

    .search-box {
      position: relative;
      width: 300px;
      flex-shrink: 0;
    }

    .search-box input {
      width: 100%;
      padding: 10px 15px 10px 40px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-family: inherit;
      font-size: 0.95em;
      transition: all 0.3s ease;
      box-sizing: border-box;
    }

    .search-box input:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 2px rgba(255, 123, 0, 0.2);
    }

    .search-box i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-light);
    }

    /* ===== PRODUCTS GRID ===== */
    .products-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 20px;
      margin-bottom: 40px;
      width: 100%;
      box-sizing: border-box;
    }

    .product-card {
      background-color: var(--bg-white);
      border-radius: 15px;
      overflow: hidden;
      box-shadow: var(--shadow);
      transition: all 0.3s ease;
      position: relative;
      width: 100%;
      box-sizing: border-box;
      display: flex;
      flex-direction: column;
      height: 100%;
    }

    .product-card:hover {
      transform: translateY(-10px);
      box-shadow: var(--shadow-hover);
    }

    .product-badge {
      position: absolute;
      top: 15px;
      right: 15px;
      background-color: var(--primary-color);
      color: white;
      padding: 5px 10px;
      border-radius: 20px;
      font-size: 0.8em;
      font-weight: 600;
      z-index: 1;
    }

    .product-image {
      height: 280px;
      overflow: hidden;
      position: relative;
      width: 100%;
      flex-shrink: 0;
    }

    .product-image img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      transition: transform 0.5s ease;
    }

    .product-card:hover .product-image img {
      transform: scale(1.1);
    }

    .product-content {
      padding: 15px;
      width: 100%;
      box-sizing: border-box;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }

    .product-category {
      color: var(--text-light);
      font-size: 0.9em;
      margin-bottom: 5px;
    }

    .product-name {
      font-size: 1.1em;
      font-weight: 600;
      color: var(--text-dark);
      margin-bottom: 10px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      line-height: 1.2;
    }

    .product-description {
      color: var(--text-light);
      font-size: 0.85em;
      line-height: 1.4;
      margin-bottom: 15px;
      display: -webkit-box;
      -webkit-box-orient: vertical;
      overflow: hidden;
      -webkit-line-clamp: 2;
      line-clamp: 2;
      text-overflow: ellipsis;
      flex-grow: 1;
    }

    .product-link {
      color: var(--primary-color);
      font-weight: 600;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      transition: all 0.3s ease;
      margin-top: auto;
    }

    .product-link i {
      margin-left: 5px;
      transition: transform 0.3s ease;
    }

    .product-link:hover i {
      transform: translateX(5px);
    }

    /* ===== NO RESULTS MESSAGE ===== */
    .no-results {
      grid-column: 1 / -1;
      text-align: center;
      padding: 40px;
      background-color: var(--bg-light);
      border-radius: 10px;
      margin: 20px 0;
    }

    .no-results h3 {
      color: var(--text-dark);
      margin-bottom: 10px;
    }

    .no-results p {
      color: var(--text-light);
    }

    /* ===== FOOTER ===== */
    footer {
      background-color: #333;
      color: white;
      padding: 60px 5% 20px;
      width: 100%;
      max-width: 100%;
      box-sizing: border-box;
    }

    .footer-content {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      max-width: 1200px;
      margin: 0 auto 40px;
      width: 100%;
      box-sizing: border-box;
    }

    .footer-section {
      flex: 1;
      min-width: 250px;
      margin-bottom: 30px;
      padding-right: 30px;
      box-sizing: border-box;
    }

    .footer-section h3 {
      color: var(--primary-color);
      font-size: 1.3em;
      margin-bottom: 20px;
      position: relative;
      padding-bottom: 10px;
    }

    .footer-section h3::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 50px;
      height: 2px;
      background-color: var(--primary-color);
    }

    .footer-section p,
    .footer-section ul {
      color: #ccc;
      line-height: 1.8;
    }

    .footer-section ul {
      list-style: none;
    }

    .footer-section ul li {
      margin-bottom: 10px;
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

    .social-links {
      display: flex;
      gap: 15px;
      margin-top: 20px;
    }

    .social-link {
      width: 40px;
      height: 40px;
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
      transform: translateY(-5px);
    }

    .footer-bottom {
      text-align: center;
      padding-top: 20px;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      color: #aaa;
      font-size: 0.9em;
      width: 100%;
      box-sizing: border-box;
    }

    /* ===== BACK TO TOP BUTTON ===== */
    .back-to-top {
      position: fixed;
      bottom: 100px;
      right: 30px;
      width: 50px;
      height: 50px;
      background: var(--primary-color);
      color: white;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 1.2em;
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
      bottom: 30px;
      right: 30px;
      width: 60px;
      height: 60px;
      background-color: #25D366;
      color: white;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 1.8em;
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

    /* ===== RESPONSIVE DESIGN ===== */
    @media (max-width: 768px) {
      header {
        padding: 10px 15px;
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
        padding: 80px 30px;
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
        font-size: 1.2em;
        width: 100%;
      }

      .products-hero h2 {
        font-size: 2.5em;
      }

      .products-hero p {
        font-size: 1.1em;
      }

      .filters-container {
        flex-direction: column;
        align-items: stretch;
        gap: 15px;
      }

      .filter-group {
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
      }

      .search-box {
        width: 100%;
      }

      /* Mobile grid layout - 2 columns */
      .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        padding: 0 5px;
      }

      .product-card {
        border-radius: 10px;
        height: 100%;
        display: flex;
        flex-direction: column;
        width: 100%;
        min-width: 0;
        box-sizing: border-box;
      }

      .product-image {
        height: 160px;
      }

      .product-content {
        padding: 10px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
      }

      .product-name {
        font-size: 0.9em;
        margin-bottom: 6px;
        line-height: 1.1;
      }

      .product-description {
        font-size: 0.75em;
        line-height: 1.3;
        margin-bottom: 8px;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        flex-grow: 1;
      }

      .product-link {
        font-size: 0.75em;
        margin-top: auto;
      }

      .footer-content {
        flex-direction: column;
      }

      /* WhatsApp button adjustments for mobile */
      .whatsapp-btn {
        bottom: 20px;
        right: 20px;
        width: 55px;
        height: 55px;
        font-size: 1.6em;
      }
      
      .back-to-top {
        bottom: 85px;
        right: 20px;
      }
    }

    @media (max-width: 576px) {
      .products-hero h2 {
        font-size: 2em;
      }

      .products-section {
        padding: 40px 15px;
      }

      .breadcrumb {
        padding: 15px 15px;
      }

      /* Mobile grid layout - 2 columns for small screens */
      .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        padding: 0 2px;
      }

      .product-card {
        border-radius: 8px;
      }

      .product-image {
        height: 140px;
      }

      .product-content {
        padding: 8px;
      }

      .product-name {
        font-size: 0.85em;
        margin-bottom: 4px;
        line-height: 1.1;
      }

      .product-description {
        font-size: 0.7em;
        line-height: 1.2;
        margin-bottom: 6px;
        -webkit-line-clamp: 2;
        line-clamp: 2;
      }

      .product-link {
        font-size: 0.7em;
      }
    }

    /* Very small devices (320px and up) */
    @media (max-width: 380px) {
      .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
        padding: 0 1px;
      }

      .product-image {
        height: 120px;
      }

      .product-content {
        padding: 6px;
      }

      .product-name {
        font-size: 0.8em;
        line-height: 1.1;
      }

      .product-description {
        font-size: 0.65em;
        line-height: 1.2;
      }

      .product-link {
        font-size: 0.65em;
      }
    }

    /* Comprehensive mobile fix for white space */
    @media (max-width: 768px) {
      * {
        box-sizing: border-box;
      }
      
      html, body {
        overflow-x: hidden !important;
        width: 100% !important;
        max-width: 100% !important;
        position: relative;
      }
      
      .products-section, .products-hero, .breadcrumb, header, footer {
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        padding-left: 15px !important;
        padding-right: 15px !important;
        box-sizing: border-box !important;
      }
      .footer-section{
        margin-left:10px;
      }
      .products-container {
        width: 100% !important;
        max-width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
        box-sizing: border-box !important;
      }
      
      .products-grid {
        width: 100% !important;
        margin: 0 auto !important;
        padding: 0 5px !important;
        box-sizing: border-box !important;
        display: grid !important;
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 15px !important;
      }
      
      .product-card {
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        box-sizing: border-box !important;
      }
      
      .filters-container {
        width: 100% !important;
        box-sizing: border-box !important;
      }
      
      .filter-group {
        width: 100% !important;
        box-sizing: border-box !important;
      }
      
      .search-box {
        width: 100% !important;
        box-sizing: border-box !important;
      }
    }

    @media (max-width: 576px) {
      .products-section, .products-hero, .breadcrumb, header, footer {
        padding-left: 10px !important;
        padding-right: 10px !important;
      }
      
      .products-grid {
        padding: 0 2px !important;
        gap: 10px !important;
      }
    }

    @media (max-width: 380px) {
      .products-section, .products-hero, .breadcrumb, header, footer {
        padding-left: 5px !important;
        padding-right: 5px !important;
      }
      
      .products-grid {
        padding: 0 1px !important;
        gap: 8px !important;
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
  <!-- LOADING SCREEN -->
  <div class="loader-wrapper">
    <div class="loader"></div>
  </div>
  <?php include 'header.php'; ?>

  <!-- MOBILE MENU OVERLAY -->
  <div class="menu-overlay" id="menuOverlay"></div>

  <!-- HEADER -->
  <header id="header">
    <a href="index.php">
      <img src="assets/companylogo1.jpg" alt="Company Logo">
    </a>
    <nav id="nav">
      <a href="index.php">Home</a>
      <a href="aboutus.php">About</a>
      <a href="product.php" class="active">Products</a>
      <a href="index.php#process">Process</a>
      <a href="index.php#testimonials">Testimonials</a>
      <a href="index.php#certifications">Certifications</a>
      <a href="index.php#contact">Contact</a>
    </nav>
    <button class="mobile-menu-btn" id="mobileMenuBtn">
      <i class="fas fa-bars"></i>
    </button>
  </header>

  <!-- BREADCRUMB -->
  <div class="breadcrumb">
    <a href="index.php#products">Home</a>
    <span>></span>
    <span class="current">Products</span>
  </div>

  <!-- PRODUCTS HERO -->
  <section class="products-hero">
    <h2>Our Premium Products</h2>
    <p>Discover our wide range of authentic Indian snacks, spices, and pickles crafted with tradition and exported globally with excellence.</p>
  </section>

  <!-- PRODUCTS SECTION -->
  <section class="products-section">
    <div class="products-container">
      <!-- FILTERS -->
      <div class="filters-container">
        <div class="filter-group">
          <label class="filter-label">Category:</label>
          <select class="filter-select" id="categoryFilter">
            <option value="all" <?php echo ($selectedCategory == 'all') ? 'selected' : ''; ?>>All Categories</option>
            <option value="a2-cow-ghee" <?php echo ($selectedCategory == 'a2-cow-ghee') ? 'selected' : ''; ?>>A2 Cow Ghee</option>
            <option value="beverages" <?php echo ($selectedCategory == 'beverages') ? 'selected' : ''; ?>>Beverages</option>
            <option value="cooking-paste-chutney" <?php echo ($selectedCategory == 'cooking-paste-chutney') ? 'selected' : ''; ?>>Cooking Paste & Chutney</option>
            <option value="dry-bhakhri" <?php echo ($selectedCategory == 'dry-bhakhri') ? 'selected' : ''; ?>>Dry Bhakhri & Khakhra</option>
            <option value="indian-sweets" <?php echo ($selectedCategory == 'indian-sweets') ? 'selected' : ''; ?>>Indian Sweets</option>
            <option value="jam" <?php echo ($selectedCategory == 'jam') ? 'selected' : ''; ?>>Jam</option>
            <option value="namkeen" <?php echo ($selectedCategory == 'namkeen') ? 'selected' : ''; ?>>Namkeen</option>
            <option value="pickles" <?php echo ($selectedCategory == 'pickles') ? 'selected' : ''; ?>>Pickles</option>
            <option value="ready-to-eat" <?php echo ($selectedCategory == 'ready-to-eat') ? 'selected' : ''; ?>>Ready to Eat</option>
            <option value="sauces" <?php echo ($selectedCategory == 'sauces') ? 'selected' : ''; ?>>Sauces</option>
            <option value="spices-powder" <?php echo ($selectedCategory == 'spices-powder') ? 'selected' : ''; ?>>Spices Powder</option>
            <option value="whole-spices" <?php echo ($selectedCategory == 'whole-spices') ? 'selected' : ''; ?>>Whole Spices</option>
          </select>
        </div>
        <div class="filter-group">
          <label class="filter-label">Sort By:</label>
          <select class="filter-select" id="sortFilter">
            <option value="featured" <?php echo ($selectedSort == 'featured') ? 'selected' : ''; ?>>Featured</option>
            <option value="name" <?php echo ($selectedSort == 'name') ? 'selected' : ''; ?>>Name</option>
          </select>
        </div>
        <div class="search-box">
          <i class="fas fa-search"></i>
          <input type="text" placeholder="Search products..." id="searchInput">
        </div>
      </div>

      <!-- PRODUCTS GRID -->
      <div class="products-grid" id="productsGrid">
        <?php if (empty($products)): ?>
          <div class="no-results">
            <h3>No products found</h3>
            <p>Please check back later for new products.</p>
          </div>
        <?php else: ?>
          <?php foreach ($products as $product): ?>
            
            <div class="product-card" data-category="<?= htmlspecialchars($product['category']) ?>" data-id="<?= $product['id'] ?>">
              <?php if (!empty($product['badge'])): ?>
                <div class="product-badge"><?= htmlspecialchars($product['badge']) ?></div>
              <?php endif; ?>
              <a href="productdetail.php?id=<?= $product['id'] ?>" style="text-decoration:none;">
              <div class="product-image">
                <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>">
              </div>
              <div class="product-content">
                <div class="product-category"><?= ucfirst(htmlspecialchars($product['category'])) ?></div>
                <h3 class="product-name">
                  <?php
                  $productName = htmlspecialchars($product['product_name']);
                  $lastSpace = strrpos($productName, ' ');
                  if ($lastSpace !== false && strlen($productName) > 15) {
                    $firstPart = substr($productName, 0, $lastSpace);
                    $secondPart = substr($productName, $lastSpace + 1);
                    echo $firstPart . '<br>' . $secondPart;
                  } else {
                    echo $productName;
                  }
                  ?>
                </h3>
                <p class="product-description"><?= htmlspecialchars(substr($product['description'], 0, 100)) ?>...</p>
                <p class="product-link">View Details <i class="fas fa-arrow-right"></i></p>
              </div></a>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      
      <!-- NO RESULTS MESSAGE (INITIALLY HIDDEN) -->
      <div class="no-results" id="noResults" style="display: none;">
        <h3>No products found</h3>
        <p>Try adjusting your filters or search terms.</p>
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
  <p><i class="fas fa-globe"></i> www.bhoomitradeline.com</p><br/>
  <!--<a href="login.php" class="admin-btn">admin login</a>-->
</div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2025 Bhoomi Trade Line | All Rights Reserved | Privacy Policy | Terms of Service</p>
    </div>
  </footer>

  <!-- WHATSAPP BUTTON -->
  <a href="https://wa.me/919979755356?text=Hi%20Bhoomi%20Trade%20Line%2C%20I'm%20interested%20in%20your%20products."
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
    // LOADING SCREEN
    window.addEventListener('load', function() {
      setTimeout(function() {
        document.querySelector('.loader-wrapper').style.opacity = '0';
        document.querySelector('.loader-wrapper').style.visibility = 'hidden';
      }, 1000);
    });

    // MOBILE MENU
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const nav = document.getElementById('nav');
    const menuOverlay = document.getElementById('menuOverlay');

    function openMenu() {
      nav.classList.add('active');
      menuOverlay.classList.add('active');
      document.body.style.overflow = 'hidden';
      mobileMenuBtn.innerHTML = '<i class="fas fa-times"></i>';
    }

    function closeMenu() {
      nav.classList.remove('active');
      menuOverlay.classList.remove('active');
      document.body.style.overflow = '';
      mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
    }

    mobileMenuBtn.addEventListener('click', function() {
      if (nav.classList.contains('active')) {
        closeMenu();
      } else {
        openMenu();
      }
    });

    menuOverlay.addEventListener('click', closeMenu);
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
      window.scrollTo({ top: 0, behavior: 'smooth' });
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

    // FILTER AND SORT FUNCTIONALITY
    const categoryFilter = document.getElementById('categoryFilter');
    const sortFilter = document.getElementById('sortFilter');
    const searchInput = document.getElementById('searchInput');
    const productsGrid = document.getElementById('productsGrid');
    const noResults = document.getElementById('noResults');

    // Function to build URL with current filters and sort options
    function buildUrl(category, sort) {
      let url = 'product.php';
      const params = [];
      
      if (category && category !== 'all') {
        params.push('category=' + category);
      }
      
      if (sort && sort !== 'featured') {
        params.push('sort=' + sort);
      }
      
      if (params.length > 0) {
        url += '?' + params.join('&');
      }
      
      return url;
    }

    // Category filter change event
    categoryFilter.addEventListener('change', function() {
      const selectedCategory = this.value;
      const currentSort = sortFilter.value;
      
      // Navigate to new page with selected category and current sort
      window.location.href = buildUrl(selectedCategory, currentSort);
    });

    // Sort filter change event
    sortFilter.addEventListener('change', function() {
      const selectedSort = this.value;
      const currentCategory = categoryFilter.value;
      
      // Navigate to new page with current category and selected sort
      window.location.href = buildUrl(currentCategory, selectedSort);
    });

    // Search functionality (client-side)
    searchInput.addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase();
      let visibleCount = 0;
      
      const currentProducts = Array.from(document.querySelectorAll('.product-card'));
      
      currentProducts.forEach(card => {
        const name = card.querySelector('.product-name').textContent.toLowerCase();
        const description = card.querySelector('.product-description').textContent.toLowerCase();
        
        const searchMatch = searchTerm === '' || name.includes(searchTerm) || description.includes(searchTerm);
        
        if (searchMatch) {
          card.style.display = 'block';
          visibleCount++;
        } else {
          card.style.display = 'none';
        }
      });
      
      // Show/hide no results message
      if (visibleCount === 0) {
        noResults.style.display = 'block';
      } else {
        noResults.style.display = 'none';
      }
    });
  </script>
</body>
</html>