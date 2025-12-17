<?php
session_start();
include '../db/db.php'; 
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch contact info for admin
 $sql = "SELECT business_address, phone_number, email, business_hours FROM users WHERE id = 1";
 $result = $conn->query($sql);
 $contact = $result->fetch_assoc();

// Fetch user data from database
 $user_sql = "SELECT `username`, `password`, `email`, `phone_number`, `business_address` FROM `users` WHERE username = '".$_SESSION['username']."'";
 $user_result = $conn->query($user_sql);
 $user = $user_result->fetch_assoc();

// Fetch products
 $products_result = $conn->query("SELECT * FROM products");
 $products = [];
if ($products_result && $products_result->num_rows > 0) {
    while($row = $products_result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Count total products
 $totalProductsQuery = $conn->query("SELECT COUNT(*) AS total FROM products");
 $totalProducts = $totalProductsQuery->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | Global Taste Exports</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!--<link rel="icon" type="image/x-icon" href="../assets/companylogo1.jpg">-->
  <link rel="icon" type="image/png" sizes="96x96" href="favicon1.png">
<style>/* ===== FONTS ===== */
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
  background-color: #f5f7fa;
  border-radius: 50px;
  padding: 8px 15px;
  width: 350px;
  position: relative;
}

.search-bar input {
  border: none;
  background: none;
  outline: none;
  width: 100%;
  margin-left: 10px;
  font-size: 0.9rem;
}

.search-suggestions {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  background-color: white;
  border-radius: 8px;
  box-shadow: var(--shadow);
  max-height: 300px;
  overflow-y: auto;
  z-index: 100;
  display: none;
  margin-top: 5px;
}

.search-suggestions.active {
  display: block;
}

.suggestion-item {
  padding: 10px 15px;
  cursor: pointer;
  transition: background-color 0.2s;
  display: flex;
  align-items: center;
}

.suggestion-item:hover {
  background-color: #f5f7fa;
}

.suggestion-item img {
  width: 30px;
  height: 30px;
  object-fit: cover;
  border-radius: 4px;
  margin-right: 10px;
}

.suggestion-item .product-info {
  flex: 1;
}

.suggestion-item .product-name {
  font-weight: 600;
  font-size: 0.9rem;
}

.suggestion-item .product-category {
  font-size: 0.8rem;
  color: var(--text-light);
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

/* ===== DASHBOARD CONTENT ===== */
.dashboard-content {
  padding: 20px;
}

.page-title {
  font-size: 1.6rem;
  font-weight: 700;
  margin-bottom: 25px;
  color: var(--text-dark);
}

/* ===== STATS CARDS ===== */
.stats-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  margin-bottom: 25px;
}

.stat-card {
  background-color: var(--bg-white);
  border-radius: 15px;
  padding: 20px;
  box-shadow: var(--shadow);
  display: flex;
  align-items: center;
  transition: all 0.3s ease;
}

.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: var(--shadow-hover);
}

.stat-icon {
  width: 50px;
  height: 50px;
  border-radius: 10px;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-right: 15px;
  font-size: 1.3rem;
}

.stat-icon.primary {
  background-color: rgba(255, 123, 0, 0.1);
  color: var(--primary-color);
}

.stat-icon.success {
  background-color: rgba(76, 175, 80, 0.1);
  color: #4CAF50;
}

.stat-icon.warning {
  background-color: rgba(255, 193, 7, 0.1);
  color: #FFC107;
}

.stat-icon.danger {
  background-color: rgba(244, 67, 54, 0.1);
  color: #F44336;
}

.stat-details h3 {
  font-size: 1.6rem;
  font-weight: 700;
  margin-bottom: 5px;
}

.stat-details p {
  color: var(--text-light);
  font-size: 0.9rem;
}

/* ===== DATA TABLE ===== */
.data-table-container {
  background-color: var(--bg-white);
  border-radius: 15px;
  padding: 20px;
  box-shadow: var(--shadow);
  margin-bottom: 25px;
  overflow-x: auto;
}

.table-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.table-title {
  font-size: 1.1rem;
  font-weight: 600;
}

.table-actions {
  display: flex;
  gap: 10px;
}

/* ===== BUTTON STYLES ===== */
.btn {
  padding: 8px 15px;
  border-radius: 8px;
  font-weight: 500;
  font-size: 0.85rem;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  text-decoration: none;
  color: inherit;
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
  color: white;
}

.btn-secondary {
  background-color: #f5f7fa;
  color: var(--text-dark);
}

.btn-secondary:hover {
  background-color: #e1e5eb;
}

.btn-danger {
  background-color: #F44336;
  color: white;
}

.btn-danger:hover {
  background-color: #d32f2f;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table th {
  text-align: left;
  padding: 12px;
  border-bottom: 1px solid #f0f0f0;
  font-weight: 600;
  color: var(--text-light);
  font-size: 0.85rem;
}

.data-table td {
  padding: 12px;
  border-bottom: 1px solid #f0f0f0;
}

.data-table tr:hover {
  background-color: #f9f9f9;
}

.product-image {
  width: 45px;
  height: 45px;
  border-radius: 8px;
  object-fit: cover;
}

.product-name {
  font-weight: 600;
}

.product-category {
  display: inline-block;
  padding: 4px 8px;
  border-radius: 20px;
  font-size: 0.75rem;
  background-color: rgba(255, 123, 0, 0.1);
  color: var(--primary-color);
}

.status {
  display: inline-block;
  padding: 4px 8px;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 500;
}

.status.active {
  background-color: rgba(76, 175, 80, 0.1);
  color: #4CAF50;
}

.status.inactive {
  background-color: rgba(244, 67, 54, 0.1);
  color: #F44336;
}

.table-actions-btns {
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

/* ===== PAGINATION ===== */
.pagination {
  display: flex;
  justify-content: flex-end;
  margin-top: 15px;
}

.pagination-btn {
  width: 30px;
  height: 30px;
  border-radius: 8px;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-left: 5px;
  cursor: pointer;
  transition: all 0.3s ease;
  background-color: #f5f7fa;
  color: var(--text-light);
}

.pagination-btn.active {
  background-color: var(--primary-color);
  color: white;
}

.pagination-btn:hover {
  background-color: #e1e5eb;
}

.pagination-btn.active:hover {
  background-color: var(--secondary-color);
}

/* ===== NO RESULTS MESSAGE ===== */
.no-results {
  text-align: center;
  padding: 30px;
  color: var(--text-light);
  display: none;
}

.no-results.show {
  display: block;
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

@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
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

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 992px) {
  .stats-cards {
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

  /* Keep username visible in mobile view */
  .user-name {
    display: inline-block;
    font-size: 0.8rem;
    max-width: 80px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  

  .stats-cards {
    grid-template-columns: 1fr;
  }

  .table-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }

  .data-table {
    font-size: 0.85rem;
  }

  .data-table th, .data-table td {
    padding: 8px;
  }

  .product-image {
    width: 35px;
    height: 35px;
  }

  .dashboard-content {
    padding: 15px;
  }

  .page-title {
    font-size: 1.4rem;
  }

  .stat-card {
    padding: 15px;
  }

  .stat-icon {
    width: 45px;
    height: 45px;
    font-size: 1.2rem;
    margin-right: 12px;
  }

  .stat-details h3 {
    font-size: 1.3rem;
  }

  .data-table-container {
    padding: 15px;
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
}

@media (max-width: 576px) {
  .top-header {
    padding: 0 10px;
  }

  .table-actions {
    width: 100%;
    justify-content: space-between;
  }

  .btn {
    padding: 6px 10px;
    font-size: 0.75rem;
  }

  .action-btn {
    width: 25px;
    height: 25px;
  }

  .pagination-btn {
    width: 25px;
    height: 25px;
    font-size: 0.75rem;
  }
  
  .user-name {
    max-width: 60px;
    font-size: 0.75rem;
  }
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
        <a href="dashboard.php" class="menu-item active">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
        <a href="adminproduct.php" class="menu-item">
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
          <i class="fas fa-search"></i>
          <input type="text" id="searchInput" placeholder="Search products...">
          <div class="search-suggestions" id="searchSuggestions"></div>
        </div>
        
        <div class="header-actions">
          <div class="notification-icon">
            
          </div>
          
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
              <a href="logout.php" class="profile-dropdown-item" id="logoutBtn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
              </a>
            </div>
          </div>
        </div>
      </header>

      <!-- DASHBOARD CONTENT -->
      <div class="dashboard-content">
        <h1 class="page-title">Dashboard</h1>
        
        <!-- STATS CARDS -->
        <div class="stats-cards">
          <div class="stat-card">
            <div class="stat-icon primary">
              <i class="fas fa-box"></i>
            </div>
            <div class="stat-details">
              <h3><?= $totalProducts ?>+</h3>
              <p>Total Products</p>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon success">
              <i class="fas fa-tags"></i>
            </div>
            <div class="stat-details">
              <h3>12+</h3>
              <p>Categories</p>
            </div>
          </div>
        </div>
        
        <!-- PRODUCTS TABLE -->
        <div class="data-table-container">
          <div class="table-header">
            <h3 class="table-title">Recent Products</h3>
            <div class="table-actions">
              
              <!-- Changed button to anchor tag -->
              <a href="addproduct.php" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Add Product
              </a>
            </div>
          </div>
          
          <div class="no-results" id="noResults">
            <i class="fas fa-search" style="font-size: 2rem; margin-bottom: 10px;"></i>
            <p>No products found matching your search.</p>
          </div>
          
          <table class="data-table" id="productsTable">
    <thead>
        <tr>
            <th>Image</th>
            <th>Product Name</th>
            <th>Category</th>
            <th>Packet Sizes</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody id="productsTableBody">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
            
                <tr data-product-id="<?= $product['id'] ?>">
                    <!-- Product Image -->
                    <td>
                        <img src="../<?= htmlspecialchars($product['image_path']) ?>" 
                             alt="<?= htmlspecialchars($product['product_name']) ?>" 
                             class="product-image" width="50" height="50">
                    </td>

                    <!-- Product Name -->
                    <td class="product-name">
                        <?= htmlspecialchars($product['product_name']) ?>
                    </td>

                    <!-- Category -->
                    <td>
                        <span class="product-category">
                            <?= htmlspecialchars(ucfirst($product['category'])) ?>
                        </span>
                    </td>

                    <!-- Packet Sizes -->
                    <td>
                        <?= htmlspecialchars($product['packet_sizes']) ?>
                    </td>

                    <!-- Action Buttons -->
                    <td>
                        <div class="table-actions-btns">
                            
                            <!-- Edit -->
                            <div class="action-btn edit">
                                <a href="updateproduct.php?id=<?= $product['id'] ?>" 
                                   style="color: inherit; text-decoration:none;">
                                   <i class="fas fa-edit"></i>
                                </a>
                            </div>
                            
                            <!-- Delete -->
                            <div class="action-btn delete" data-id="<?= $product['id'] ?>" data-name="<?= htmlspecialchars($product['product_name']) ?>">
                                <i class="fas fa-trash"></i>
                            </div>
                        </div>
                    </td>
                </tr>

            <?php endforeach; ?>
        <?php else: ?>

            <!-- No Product Found -->
            <tr>
                <td colspan="6" style="text-align:center; padding:20px;">
                    No products found.
                </td>
            </tr>

        <?php endif; ?>
    </tbody>
</table>

        </div>
      </div>
    </main>
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
    // Store products data in JavaScript
    const productsData = <?php echo json_encode($products); ?>;
    
    // DOM elements
    const searchInput = document.getElementById('searchInput');
    const searchSuggestions = document.getElementById('searchSuggestions');
    const productsTableBody = document.getElementById('productsTableBody');
    const noResults = document.getElementById('noResults');
    
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
    
    // DELETE CONFIRMATION MODAL
    const deleteButtons = document.querySelectorAll('.action-btn.delete');
    const confirmModal = document.getElementById('confirmModal');
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    const cancelDeleteBtn = document.getElementById('cancelDelete');
    let deleteProductId = null;
    
    // Add click event to delete buttons
    deleteButtons.forEach(button => {
      button.addEventListener('click', function(e) {
        e.stopPropagation();
        deleteProductId = this.getAttribute('data-id');
        confirmModal.classList.add('active');
      });
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
    
    // SEARCH FUNCTIONALITY
    searchInput.addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase().trim();
      
      // Clear previous suggestions
      searchSuggestions.innerHTML = '';
      
      if (searchTerm.length === 0) {
        searchSuggestions.classList.remove('active');
        // Reset table to show all products
        filterProductsTable('');
        return;
      }
      
      // Filter products for suggestions
      const matchingProducts = productsData.filter(product => {
        return product.product_name.toLowerCase().includes(searchTerm) || 
               product.category.toLowerCase().includes(searchTerm);
      });
      
      // Show suggestions if there are matches
      if (matchingProducts.length > 0) {
        // Limit to 5 suggestions
        const limitedMatches = matchingProducts.slice(0, 5);
        
        limitedMatches.forEach(product => {
          const suggestionItem = document.createElement('div');
          suggestionItem.className = 'suggestion-item';
          
          const productImage = product.image_path ? 
            `../${product.image_path}` : 
            `https://picsum.photos/seed/${product.id}/60/60.jpg`;
          
          suggestionItem.innerHTML = `
            <img src="${productImage}" alt="${product.product_name}">
            <div class="product-info">
              <div class="product-name">${product.product_name}</div>
              <div class="product-category">${product.category}</div>
            </div>
          `;
          
          suggestionItem.addEventListener('click', function() {
            searchInput.value = product.product_name;
            searchSuggestions.classList.remove('active');
            filterProductsTable(product.product_name);
          });
          
          searchSuggestions.appendChild(suggestionItem);
        });
        
        searchSuggestions.classList.add('active');
      } else {
        searchSuggestions.classList.remove('active');
      }
      
      // Filter the products table
      filterProductsTable(searchTerm);
    });
    
    // Function to filter the products table
    function filterProductsTable(searchTerm) {
      const rows = productsTableBody.getElementsByTagName('tr');
      let hasResults = false;
      
      for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const productName = row.querySelector('.product-name').textContent.toLowerCase();
        const productCategory = row.querySelector('.product-category').textContent.toLowerCase();
        
        if (productName.includes(searchTerm) || productCategory.includes(searchTerm)) {
          row.style.display = '';
          hasResults = true;
        } else {
          row.style.display = 'none';
        }
      }
      
      // Show/hide no results message
      if (hasResults || searchTerm === '') {
        noResults.classList.remove('show');
      } else {
        noResults.classList.add('show');
      }
    }
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
      if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
        searchSuggestions.classList.remove('active');
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