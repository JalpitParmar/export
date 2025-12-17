<?php
session_start();
require_once "../db/db.php";
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch user data from database
 $user_sql = "SELECT `username`, `password`, `email`, `phone_number`, `business_address` FROM `users` WHERE username = '".$_SESSION['username']."'";
 $user_result = $conn->query($user_sql);
 $user = $user_result->fetch_assoc();

// Get category and sort parameters from URL
 $selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';
 $selectedSort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Build products query with category filter
 $products_query = "SELECT id, product_name, category, packet_sizes, description, key_features, image_path, created_at FROM products";

// --- FIX IS HERE ---
// Only add a WHERE clause if a specific category (not 'all') is selected.
if (!empty($selectedCategory) && $selectedCategory !== 'all') {
    $products_query .= " WHERE category = '" . $conn->real_escape_string($selectedCategory) . "'";
}

// Apply sorting based on selected sort option
switch ($selectedSort) {
    case 'name':
        $products_query .= " ORDER BY product_name ASC";
        break;
    case 'newest':
    default:
        $products_query .= " ORDER BY created_at DESC";
        break;
}

 $products_result = $conn->query($products_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Products | Global Taste Exports</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!--<link rel="icon" type="image/x-icon" href="../assets/companylogo1.jpg">-->
  <link rel="icon" type="image/png" sizes="96x96" href="favicon1.png">
<style> /* ===== FONTS ===== */
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
      --sidebar-width: 250px;
      --header-height: 60px;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f7fa;
      color: var(--text-dark);
      overflow-x: hidden;
    }

    /* ===== DASHBOARD LAYOUT ===== */
    .dashboard-container {
      display: flex;
      min-height: 100vh;
    }

    /* ===== SIDEBAR ===== */
    .sidebar {
      width: var(--sidebar-width);
      background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
      color: white;
      position: fixed;
      height: 100%;
      overflow-y: auto;
      z-index: 100;
      transition: transform 0.3s ease;
    }

    .sidebar-header {
      padding: 15px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-header img {
      height: 35px;
    }

    .sidebar-header h3 {
      color: white !important;
      font-size: 1.2rem !important;
      font-weight: 600;
      margin-left: 30px;
      z-index: 101;
      position: relative;
    }

    .close-sidebar-btn {
      display: none;
      background: none;
      border: none;
      color: white;
      font-size: 1.5rem;
      cursor: pointer;
      padding: 5px;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    .close-sidebar-btn:hover {
      background-color: rgba(255, 255, 255, 0.1);
    }

    .sidebar-menu {
      padding: 15px 0;
    }

    .menu-item {
      padding: 12px 20px;
      display: flex;
      align-items: center;
      color: rgba(255, 255, 255, 0.8);
      text-decoration: none;
      transition: all 0.3s ease;
      position: relative;
    }

    .menu-item i {
      margin-right: 15px;
      font-size: 1rem;
      width: 20px;
      text-align: center;
    }

    .menu-item:hover, .menu-item.active {
      background-color: rgba(255, 255, 255, 0.1);
      color: white;
    }

    .menu-item.active::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      height: 100%;
      width: 4px;
      background-color: white;
    }

    /* ===== MAIN CONTENT ===== */
    .main-content {
      flex: 1;
      margin-left: var(--sidebar-width);
      display: flex;
      flex-direction: column;
    }

    /* ===== TOP HEADER ===== */
    .top-header {
      height: var(--header-height);
      background-color: var(--bg-white);
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 20px;
      box-shadow: var(--shadow);
      position: sticky;
      top: 0;
      z-index: 50;
    }

    .search-bar {
      display: flex;
      align-items: center;
      background-color: #ffffffff;
      border-radius: 50px;
      padding: 8px 15px;
      width: 350px;
    }

    .search-bar input {
      border: none;
      background: none;
      outline: none;
      width: 100%;
      margin-left: 10px;
      font-size: 0.9rem;
    }

    .header-actions {
      display: flex;
      align-items: center;
    }

    .notification-icon {
      position: relative;
      margin-right: 15px;
      font-size: 1.1rem;
      color: var(--text-light);
      cursor: pointer;
    }

    .notification-badge {
      position: absolute;
      top: -5px;
      right: -5px;
      background-color: var(--primary-color);
      color: white;
      width: 18px;
      height: 18px;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 0.7rem;
    }

    .user-profile {
      display: flex;
      align-items: center;
      position: relative;
    }

    

    .user-name {
      font-weight: 600;
      margin-right: 8px;
      font-size: 0.9rem;
    }

    .profile-dropdown {
      position: absolute;
      top: 100%;
      right: 0;
      background-color: white;
      border-radius: 8px;
      box-shadow: var(--shadow);
      width: 200px;
      padding: 10px 0;
      z-index: 100;
      opacity: 0;
      visibility: hidden;
      transform: translateY(10px);
      transition: all 0.3s ease;
    }

    .profile-dropdown.active {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }

    .profile-dropdown-item {
      display: flex;
      align-items: center;
      padding: 10px 20px;
      color: var(--text-dark);
      text-decoration: none;
      transition: background-color 0.3s;
    }

    .profile-dropdown-item i {
      margin-right: 10px;
      width: 20px;
      text-align: center;
    }

    .profile-dropdown-item:hover {
      background-color: #f5f7fa;
    }

    .profile-dropdown-divider {
      height: 1px;
      background-color: #f0f0f0;
      margin: 10px 0;
    }

    /* ===== PRODUCTS PAGE ===== */
    .products-content {
      padding: 20px;
    }

    .page-title {
      font-size: 1.6rem;
      font-weight: 700;
      margin-bottom: 25px;
      color: var(--text-dark);
    }

    /* ===== FILTERS ===== */
    .filters-container {
      background-color: var(--bg-white);
      border-radius: 15px;
      padding: 15px;
      box-shadow: var(--shadow);
      margin-bottom: 25px;
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      align-items: center;
    }

    .filter-group {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .filter-label {
      font-weight: 500;
      color: var(--text-light);
    }

    .filter-select {
      padding: 8px 12px;
      border: 1px solid #e1e5eb;
      border-radius: 8px;
      background-color: white;
      font-family: inherit;
      min-width: 150px;
      font-size: 0.9rem;
    }

    .search-filter {
      flex: 1;
      min-width: 200px;
      position: relative;
    }

    .search-filter input {
      width: 100%;
      padding: 8px 12px 8px 35px;
      border: 1px solid #e1e5eb;
      border-radius: 8px;
      font-family: inherit;
      font-size: 0.9rem;
    }

    .search-filter i {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-light);
    }

    .btn {
      padding: 8px 15px;
      border-radius: 8px;
      font-weight: 500;
      font-size: 0.85rem;
      cursor: pointer;
      transition: all 0.3s ease;
      border: none;
      display: inline-flex;
      align-items: center;
    }

    .btn i {
      margin-right: 6px;
    }

    .btn-primary {
      background-color: var(--primary-color);
      color: white;
    }

    .btn-primary:hover {
      background-color: var(--secondary-color);
    }

    /* ===== PRODUCTS GRID ===== */
    .products-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 20px;
      margin-bottom: 25px;
    }

    .product-card {
      background-color: var(--bg-white);
      border-radius: 15px;
      overflow: hidden;
      box-shadow: var(--shadow);
      transition: all 0.3s ease;
      display: flex;
      flex-direction: column;
      cursor: pointer;
    }

    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: var(--shadow-hover);
    }

    .product-image-container {
      min-height: 200px;
      max-height: 250px;
      overflow: hidden;
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #f9f9f9;
    }

    .product-image {
      width: 100%;
      height: auto;
      max-height: 250px;
      object-fit: contain;
      transition: transform 0.3s ease;
    }

    .product-card:hover .product-image {
      transform: scale(1.05);
    }

    .product-badge {
      position: absolute;
      top: 12px;
      right: 12px;
      padding: 4px 8px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 500;
    }

    .badge-new {
      background-color: rgba(76, 175, 80, 0.9);
      color: white;
    }

    .badge-sale {
      background-color: rgba(244, 67, 54, 0.9);
      color: white;
    }

    .product-details {
      padding: 15px;
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .product-category {
      display: inline-block;
      padding: 4px 8px;
      border-radius: 20px;
      font-size: 0.75rem;
      background-color: rgba(255, 123, 0, 0.1);
      color: var(--primary-color);
      margin-bottom: 8px;
    }

    .product-name {
      font-size: 1rem;
      font-weight: 600;
      margin-bottom: 8px;
    }

    .product-description {
      color: var(--text-light);
      font-size: 0.85rem;
      margin-bottom: 12px;
      flex: 1;
      /* Add these properties to limit to 2 lines with ellipsis */
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
    }

    .product-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .product-price {
      font-size: 1.1rem;
      font-weight: 700;
      color: var(--primary-color);
      /* Add these properties to limit to 1-2 lines with ellipsis */
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 100%;
    }

    .product-actions {
      display: flex;
      gap: 5px;
    }

    .action-btn {
      width: 30px;
      height: 30px;
      border-radius: 8px;
      display: flex;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .action-btn.edit {
      background-color: rgba(33, 150, 243, 0.1);
      color: #2196F3;
    }

    .action-btn.delete {
      background-color: rgba(244, 67, 54, 0.1);
      color: #F44336;
    }

    .action-btn:hover {
      transform: translateY(-2px);
    }

    /* ===== PRODUCT DETAIL MODAL ===== */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 200;
      justify-content: center;
      align-items: center;
    }

    .modal.active {
      display: flex;
    }

    .modal-content {
      background-color: var(--bg-white);
      border-radius: 15px;
      width: 90%;
      max-width: 800px;
      max-height: 90vh;
      overflow-y: auto;
      animation: fadeInUp 0.3s ease;
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .modal-header {
      padding: 15px 20px;
      border-bottom: 1px solid #f0f0f0;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .modal-title {
      font-size: 1.2rem;
      font-weight: 600;
    }

    .modal-close {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      background-color: #f5f7fa;
      color: var(--text-light);
      transition: all 0.3s ease;
    }

    .modal-close:hover {
      background-color: #e1e5eb;
    }

    .modal-body {
      padding: 20px;
    }

    .product-detail-container {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 25px;
    }

    .product-detail-image {
      width: 100%;
      border-radius: 10px;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #f9f9f9;
      min-height: 300px;
    }

    .product-detail-image img {
      width: 100%;
      height: auto;
      max-height: 400px;
      object-fit: contain;
    }

    .product-detail-info h3 {
      font-size: 1.4rem;
      margin-bottom: 12px;
    }

    .product-detail-info .product-category {
      margin-bottom: 12px;
    }

    .product-detail-info p {
      margin-bottom: 12px;
      color: var(--text-light);
    }

    .product-detail-info .product-price {
      font-size: 1.4rem;
      margin-bottom: 15px;
    }

    .product-detail-actions {
      display: flex;
      gap: 10px;
    }

    .modal-footer {
      padding: 12px 20px;
      border-top: 1px solid #f0f0f0;
      display: flex;
      justify-content: flex-end;
      gap: 10px;
    }

    /* ===== CONFIRMATION MODAL ===== */
    .confirm-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 300;
      justify-content: center;
      align-items: center;
    }

    .confirm-modal.active {
      display: flex;
    }

    .confirm-content {
      background-color: var(--bg-white);
      border-radius: 15px;
      width: 90%;
      max-width: 400px;
      padding: 25px;
      text-align: center;
      animation: fadeInUp 0.3s ease;
    }

    .confirm-icon {
      font-size: 3rem;
      color: #F44336;
      margin-bottom: 15px;
    }

    .confirm-title {
      font-size: 1.3rem;
      font-weight: 600;
      margin-bottom: 10px;
    }

    .confirm-message {
      color: var(--text-light);
      margin-bottom: 20px;
    }

    .confirm-buttons {
      display: flex;
      justify-content: center;
      gap: 10px;
    }

    .btn-danger {
      background-color: #F44336;
      color: white;
    }

    .btn-danger:hover {
      background-color: #d32f2f;
    }

    .btn-secondary {
      background-color: #f5f7fa;
      color: var(--text-dark);
    }

    .btn-secondary:hover {
      background-color: #e1e5eb;
    }

    /* ===== MOBILE RESPONSIVE ===== */
    .mobile-menu-btn {
      display: none;
      background: none;
      border: none;
      font-size: 20px;
      color: var(--primary-color);
      cursor: pointer;
      padding: 5px;
    }

    /* ===== OVERLAY FOR MOBILE MENU ===== */
    .overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 99;
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.3s, visibility 0.3s;
    }

    .overlay.active {
      display: block;
      opacity: 1;
      visibility: visible;
    }

    @media (max-width: 992px) {
      .products-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
        width: 250px;
      }

      .sidebar.active {
        transform: translateX(0);
      }

      .close-sidebar-btn {
        display: block;
      }

      .main-content {
        margin-left: 0;
      }

      .mobile-menu-btn {
        display: block;
      }

      .search-bar {
        width: auto;
        flex: 1;
        margin-right: 10px;
      }

      .products-grid {
        grid-template-columns: 1fr;
      }

      .filters-container {
        flex-direction: column;
        align-items: stretch;
      }

      .filter-group {
        width: 100%;
      }

      .filter-select {
        width: 100%;
      }

      .product-detail-container {
        grid-template-columns: 1fr;
      }
      
      .top-header {
        height: 50px;
        padding: 0 10px;
      }
      
      .search-bar {
        padding: 6px 10px;
      }
      
      .notification-icon {
        margin-right: 10px;
      }
      
      
      
      .products-content {
        padding: 15px;
      }
      
      .page-title {
        font-size: 1.4rem;
      }
      
      .filters-container {
        padding: 12px;
      }
      
      .product-card {
        margin-bottom: 15px;
      }
      
      .product-image-container {
        min-height: 150px;
        max-height: 200px;
      }
    }

    @media (max-width: 576px) {
      .top-header {
        padding: 0 10px;
      }

      .btn {
        padding: 6px 10px;
        font-size: 0.75rem;
      }

      .action-btn {
        width: 25px;
        height: 25px;
      }
      
      .user-name {
        font-size: 0.8rem;
      }
    }#modalProductDescription {
  display: -webkit-box;
  -webkit-line-clamp: 5; /* Limit to 5 lines */
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  margin-bottom: 12px;
  color: var(--text-light);
}</style>
</head>
<body>
  <div class="dashboard-container">
    <!-- OVERLAY FOR MOBILE MENU -->
    <div class="overlay" id="overlay"></div>

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
      <div class="sidebar-header">
        <img src="../assets/companylogo1.jpg" alt="Company Logo">
        <h3>Admin Panel</h3>
        <button class="close-sidebar-btn" id="closeSidebarBtn">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <nav class="sidebar-menu">
        <a href="dashboard.php" class="menu-item">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
        <a href="adminproduct.php" class="menu-item active">
          <i class="fas fa-box"></i>
          <span>Products</span>
        </a>
       
        <a href="settings.php" class="menu-item">
          <i class="fas fa-cog"></i>
          <span>Settings</span>
        </a>
        <a href="profile.php" class="menu-item">
          <i class="fas fa-user"></i>
          <span>Profile</span>
        </a>
      </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
      <!-- TOP HEADER -->
       <header class="top-header">
        <button class="mobile-menu-btn" id="mobileMenuBtn">
          <i class="fas fa-bars"></i>
        </button>
        
        <div class="search-bar">
         
        </div>
        
        <div class="header-actions">
          <div class="user-profile">
            <span class="user-name"><?= htmlspecialchars($user['username']) ?></span>
            <i class="fas fa-chevron-down" id="profileDropdownToggle"></i>
            
            <!-- Profile Dropdown -->
            <div class="profile-dropdown" id="profileDropdown">
              <a href="profile.php" class="profile-dropdown-item">
                <i class="fas fa-user"></i>
                <span>My Profile</span>
              </a>
              <a href="settings.php" class="profile-dropdown-item">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
              </a>
              <div class="profile-dropdown-divider"></div>
              <a href="logout.php" class="profile-dropdown-item" >
                <i class="fas fa-sign-out-alt" id="logoutBtn"></i>
                <span>Logout</span>
              </a>
            </div>
          </div>
        </div>
      </header>

      <!-- PRODUCTS CONTENT -->
      <div class="products-content">
        <h1 class="page-title">Products</h1>
        
        <!-- FILTERS -->
        <div class="filters-container">
          <div class="filter-group">
            <label class="filter-label">Category:</label>
            <select class="filter-select" id="categoryFilter">
              <option value="all" <?php echo ($selectedCategory == 'all' || $selectedCategory == '') ? 'selected' : ''; ?>>All Categories</option>
            <option value="a2-cow-ghee" <?php echo ($selectedCategory == 'a2-cow-ghee') ? 'selected' : ''; ?>>A2 Cow Ghee</option>
            <option value="beverages" <?php echo ($selectedCategory == 'beverages') ? 'selected' : ''; ?>>Beverages</option>
            <option value="cooking-paste-chutney" <?php echo ($selectedCategory == 'cooking-paste-chutney') ? 'selected' : ''; ?>>Cooking Paste & Chutney</option>
            <option value="dry-bhakhri" <?php echo ($selectedCategory == 'dry-bhakhri') ? 'selected' : ''; ?>>Dry Bhakhri</option>
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
              <option value="newest" <?php echo ($selectedSort == 'newest') ? 'selected' : ''; ?>>Newest First</option>
              <option value="name" <?php echo ($selectedSort == 'name') ? 'selected' : ''; ?>>Name</option>
            </select>
          </div>
          
          <a href="addproduct.php">
          <button class="btn btn-primary">
            
                <i class="fas fa-plus"></i>
            
                Add Product
            
          </button></a>
        </div>
        
        <!-- PRODUCTS GRID -->
        <div class="products-grid">
          <?php if ($products_result->num_rows > 0): ?>
            <?php while($product = $products_result->fetch_assoc()): ?>
              <div class="product-card" data-id="<?= $product['id'] ?>" 
                   data-name="<?= htmlspecialchars($product['product_name']) ?>" 
                   data-category="<?= htmlspecialchars($product['category']) ?>" 
                   data-description="<?= htmlspecialchars($product['description']) ?>" 
                   data-price="<?= htmlspecialchars($product['packet_sizes']) ?>" 
                   data-image="<?= htmlspecialchars($product['image_path']) ?>">
                <div class="product-image-container">
                  <?php if (!empty($product['image_path'])): ?>
                    <img src="../<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>" class="product-image">
                  <?php else: ?>
                    <img src="https://picsum.photos/seed/<?= $product['id'] ?>/400/300.jpg" alt="<?= htmlspecialchars($product['product_name']) ?>" class="product-image">
                  <?php endif; ?>
                </div>
                <div class="product-details">
                  <span class="product-category"><?= htmlspecialchars($product['category']) ?></span>
                  <h3 class="product-name"><?= htmlspecialchars($product['product_name']) ?></h3>
                  <p class="product-description"><?= htmlspecialchars($product['description']) ?></p>
                  <div class="product-footer">
                    <span class="product-price"><?= htmlspecialchars($product['packet_sizes']) ?></span>
                    <div class="product-actions">
                      <div class="action-btn edit">
                        <a href="updateproduct.php?id=<?= $product['id'] ?>" style="color: inherit; text-decoration: none;">
                          <i class="fas fa-edit"></i>
                        </a>
                      </div>
                      <div class="action-btn delete" data-id="<?= $product['id'] ?>" data-name="<?= htmlspecialchars($product['product_name']) ?>">
                        <i class="fas fa-trash"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 40px;">
              <p>No products found in database.</p>
              <a href="addproduct.php" class="btn btn-primary" style="margin-top: 15px;text-decoration:none">
                <i class="fas fa-plus"></i> Add Your First Product
              </a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </main>
  </div>

  <!-- PRODUCT DETAIL MODAL -->
  <div class="modal" id="productModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Product Details</h3>
        <div class="modal-close" id="closeModal">
          <i class="fas fa-times"></i>
        </div>
      </div>
      
      <div class="modal-body">
        <div class="product-detail-container">
          <div class="product-detail-image">
            <img src="" alt="Product" id="modalProductImage">
          </div>
          
          <div class="product-detail-info">
            <h3 id="modalProductName"></h3>
            <span class="product-category" id="modalProductCategory"></span>
            <p id="modalProductDescription"></p>
            <div class="product-price" id="modalProductPrice"></div>
            
            <div class="product-detail-actions">
              <a href="#" class="btn btn-primary" id="modalEditBtn">
                <i class="fas fa-edit"></i>
                Edit Product
              </a>
              <a href="#" class="btn btn-secondary" id="modalDeleteBtn" style="background-color: #f5f7fa; color: var(--text-dark);">
                <i class="fas fa-trash"></i>
                Delete Product
              </a>
            </div>
          </div>
        </div>
      </div>
      
      <div class="modal-footer">
        <button class="btn btn-secondary" id="cancelBtn">Close</button>
      </div>
    </div>
  </div>

  <!-- DELETE CONFIRMATION MODAL -->
  <div class="confirm-modal" id="confirmModal">
    <div class="confirm-content">
      <div class="confirm-icon">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
      <h3 class="confirm-title">Are you sure?</h3>
      <p class="confirm-message">Do you really want to delete this product? This action cannot be undone.</p>
      <div class="confirm-buttons">
        <button class="btn btn-danger" id="confirmDelete">Delete</button>
        <button class="btn btn-secondary" id="cancelDelete">Cancel</button>
      </div>
    </div>
  </div>

  <script>
    // MOBILE MENU TOGGLE
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const sidebar = document.getElementById('sidebar');
    const closeSidebarBtn = document.getElementById('closeSidebarBtn');
    const overlay = document.getElementById('overlay');
    
    // Function to open mobile menu
    function openMobileMenu() {
      sidebar.classList.add('active');
      overlay.classList.add('active');
      document.body.style.overflow = 'hidden'; // Prevent scrolling when menu is open
    }
    
    // Function to close mobile menu
    function closeMobileMenu() {
      sidebar.classList.remove('active');
      overlay.classList.remove('active');
      document.body.style.overflow = ''; // Restore scrolling
    }
    
    // Event listeners for menu toggle
    mobileMenuBtn.addEventListener('click', openMobileMenu);
    closeSidebarBtn.addEventListener('click', closeMobileMenu);
    overlay.addEventListener('click', closeMobileMenu);
    
    // Close mobile menu when clicking on a menu item
    document.querySelectorAll('.menu-item').forEach(item => {
      item.addEventListener('click', function() {
        if (window.innerWidth <= 768) {
          closeMobileMenu();
        }
      });
    });
    
    // Close mobile menu when pressing Escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && sidebar.classList.contains('active')) {
        closeMobileMenu();
      }
    });
    
    // PROFILE DROPDOWN TOGGLE
    const profileDropdownToggle = document.getElementById('profileDropdownToggle');
    const profileDropdown = document.getElementById('profileDropdown');
    
    profileDropdownToggle.addEventListener('click', function(e) {
      e.stopPropagation();
      profileDropdown.classList.toggle('active');
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function() {
      if (profileDropdown.classList.contains('active')) {
        profileDropdown.classList.remove('active');
      }
    });
    
    // CATEGORY FILTER FUNCTIONALITY
    const categoryFilter = document.getElementById('categoryFilter');
    const sortFilter = document.getElementById('sortFilter');
    
    // --- FIX IS HERE ---
    // Function to build URL with current filters and sort options
    function buildUrl(category, sort) {
      let url = 'adminproduct.php';
      const params = [];
      
      // Only add category to URL if it's a specific one, not 'all'
      if (category && category !== 'all') {
        params.push('category=' + category);
      }
      
      if (sort && sort !== 'newest') {
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
    
    // PRODUCT CARD CLICK
    const productCards = document.querySelectorAll('.product-card');
    const productModal = document.getElementById('productModal');
    const closeModal = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const modalEditBtn = document.getElementById('modalEditBtn');
    const modalDeleteBtn = document.getElementById('modalDeleteBtn');
    let currentProductId = null;
    
    productCards.forEach(card => {
      card.addEventListener('click', function(e) {
        // Don't open modal if clicking on action buttons
        if (!e.target.closest('.product-actions')) {
          // Get product details from data attributes
          const productImage = this.dataset.image;
          const productName = this.dataset.name;
          const productCategory = this.dataset.category;
          const productDescription = this.dataset.description;
          const productPrice = this.dataset.price;
          currentProductId = this.dataset.id;
          
          // Update modal content
          if (productImage) {
            document.getElementById('modalProductImage').src = '../' + productImage;
          } else {
            document.getElementById('modalProductImage').src = 'https://picsum.photos/seed/' + currentProductId + '/400/300.jpg';
          }
          document.getElementById('modalProductName').textContent = productName;
          document.getElementById('modalProductCategory').textContent = productCategory;
          document.getElementById('modalProductDescription').textContent = productDescription;
          document.getElementById('modalProductPrice').textContent = productPrice;
          
          // Update edit and delete button links
          modalEditBtn.href = 'updateproduct.php?id=' + currentProductId;
          modalDeleteBtn.setAttribute('data-id', currentProductId);
          
          // Open modal
          productModal.classList.add('active');
        }
      });
    });
    
    // Close modal
    closeModal.addEventListener('click', function() {
      productModal.classList.remove('active');
    });
    
    cancelBtn.addEventListener('click', function() {
      productModal.classList.remove('active');
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
      if (event.target === productModal) {
        productModal.classList.remove('active');
      }
    });
    
    // EDIT BUTTONS
    const editButtons = document.querySelectorAll('.action-btn.edit');
    
    editButtons.forEach(button => {
      button.addEventListener('click', function(e) {
        e.stopPropagation(); // Prevent card click event
      });
    });
      
    // DELETE BUTTONS
    const deleteButtons = document.querySelectorAll('.action-btn.delete');
    const confirmModal = document.getElementById('confirmModal');
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    const cancelDeleteBtn = document.getElementById('cancelDelete');
    let deleteProductId = null;
    
    // Add click event to delete buttons in product cards
    deleteButtons.forEach(button => {
      button.addEventListener('click', function(e) {
        e.stopPropagation(); // Prevent card click event
        deleteProductId = this.getAttribute('data-id');
        confirmModal.classList.add('active');
      });
    });
    
    // Add click event to delete button in modal
    modalDeleteBtn.addEventListener('click', function(e) {
      e.preventDefault(); // Prevent default link behavior
      deleteProductId = this.getAttribute('data-id');
      productModal.classList.remove('active');
      confirmModal.classList.add('active');
    });
    
    // Confirm delete
    confirmDeleteBtn.addEventListener('click', function() {
      if (deleteProductId) {
        window.location.href = 'deleteproduct.php?id=' + deleteProductId;
      }
    });
    
    // Cancel delete
    cancelDeleteBtn.addEventListener('click', function() {
      confirmModal.classList.remove('active');
      deleteProductId = null;
    });
    
    // Close confirmation modal when clicking outside
    window.addEventListener('click', function(event) {
      if (event.target === confirmModal) {
        confirmModal.classList.remove('active');
        deleteProductId = null;
      }
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
      if (window.innerWidth > 768 && sidebar.classList.contains('active')) {
        closeMobileMenu();
      }
    });
  </script>
</body>
</html>