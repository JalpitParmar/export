<?php
session_start();
// ================= BACKEND =================

// Include DB connection
require_once "../db/db.php";
//is admin or not 
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}
// If form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $productName      = mysqli_real_escape_string($conn, $_POST['productName']);
    $productCategory  = mysqli_real_escape_string($conn, $_POST['productCategory']);
    $productDesc      = mysqli_real_escape_string($conn, $_POST['productDescription']);
    $keyFeatures      = mysqli_real_escape_string($conn, $_POST['keyFeatures']);

    // Packet sizes (array)
    $packetSizes = isset($_POST['packet']) ? $_POST['packet'] : [];
    
    // Ensure it's an array before imploding for robustness
    if (!is_array($packetSizes)) {
        $packetSizes = [$packetSizes];
    }
    
    $packetSizesStr = implode(",", $packetSizes);

    // Image Upload
    $imagePath = ""; // Default to empty if no image is uploaded

    if (!empty($_FILES['productImage']['name'])) {
        $targetDir = "../uploads/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES["productImage"]["name"]);
        $targetFile = $targetDir . $fileName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Basic validation (you can add more)
        $check = getimagesize($_FILES["productImage"]["tmp_name"]);
        if($check !== false) {
            if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $targetFile)) {
                $imagePath = "uploads/" . $fileName;
            } else {
                // Optional: Handle upload error
                echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
            }
        } else {
            // Optional: Handle invalid file type error
            echo "<script>alert('File is not an image.');</script>";
        }
    }

    // Insert Query
    $sql = "INSERT INTO products (product_name, category, packet_sizes, description, key_features, image_path)
            VALUES ('$productName', '$productCategory', '$packetSizesStr', '$productDesc', '$keyFeatures', '$imagePath')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Product added successfully!'); window.location='adminproduct.php';</script>";
    } else {
        // Display the actual MySQL error for debugging
        echo "<script>alert('Error: Could not save product. " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Product | Global Taste Exports</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!--<link rel="icon" type="image/x-icon" href="../assets/companylogo1.jpg">-->
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
      background-color: #ffffff;
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

    /* ===== ADD PRODUCT PAGE ===== */
    .add-product-content {
      padding: 20px;
    }

    .page-title {
      font-size: 1.6rem;
      font-weight: 700;
      margin-bottom: 25px;
      color: var(--text-dark);
    }

    .breadcrumb {
      display: flex;
      align-items: center;
      margin-bottom: 25px;
      color: var(--text-light);
      font-size: 0.9rem;
    }

    .breadcrumb a {
      color: var(--text-light);
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .breadcrumb a:hover {
      color: var(--primary-color);
    }

    .breadcrumb span {
      margin: 0 10px;
    }

    /* ===== FORM CONTAINER ===== */
    .form-container {
      background-color: var(--bg-white);
      border-radius: 15px;
      padding: 20px;
      box-shadow: var(--shadow);
    }

    .form-header {
      margin-bottom: 25px;
      padding-bottom: 12px;
      border-bottom: 1px solid #f0f0f0;
    }

    .form-title {
      font-size: 1.2rem;
      font-weight: 600;
    }

    .form-footer {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-top: 25px;
      padding-top: 20px;
      border-top: 1px solid #f0f0f0;
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

    .btn-secondary {
      background-color: #f5f7fa;
      color: var(--text-dark);
    }

    .btn-secondary:hover {
      background-color: #e1e5eb;
    }

    .btn-sm {
      padding: 5px 10px;
      font-size: 0.8rem;
    }

    .btn-danger {
      background-color: #f44336;
      color: white;
    }

    .btn-danger:hover {
      background-color: #d32f2f;
    }

    /* ===== FORM SECTIONS ===== */
    .form-section {
      margin-bottom: 25px;
    }

    .section-title {
      font-size: 1rem;
      font-weight: 600;
      margin-bottom: 15px;
      color: var(--text-dark);
      display: flex;
      align-items: center;
    }

    .section-title i {
      margin-right: 10px;
      color: var(--primary-color);
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      margin-bottom: 6px;
      font-weight: 500;
    }

    .form-control {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #e1e5eb;
      border-radius: 8px;
      font-family: inherit;
      font-size: 0.9rem;
      transition: border-color 0.3s ease;
    }

    .form-control:focus {
      outline: none;
      border-color: var(--primary-color);
    }

    textarea.form-control {
      resize: vertical;
      min-height: 100px;
    }

    /* ===== DYNAMIC PACKET SIZES ===== */
    .packet-sizes-container {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .packet-size-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px 12px;
      background-color: #f8f9fa;
      border-radius: 8px;
      border: 1px solid #e1e5eb;
    }

    .packet-size-item input[type="checkbox"] {
      width: 18px;
      height: 18px;
      cursor: pointer;
    }

    .packet-size-item input[type="text"] {
      flex: 1;
      padding: 5px 8px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 0.9rem;
    }

    .packet-size-item .remove-btn {
      background: none;
      border: none;
      color: #f44336;
      cursor: pointer;
      font-size: 1rem;
      padding: 5px;
      border-radius: 4px;
      transition: background-color 0.3s;
    }

    .packet-size-item .remove-btn:hover {
      background-color: rgba(244, 67, 54, 0.1);
    }

    .add-packet-size-btn {
      margin-top: 10px;
      width: auto;
    }

    /* ===== IMAGE UPLOAD ===== */
    .image-upload-container {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .image-upload-box {
      width: 130px;
      height: 130px;
      border: 2px dashed #e1e5eb;
      border-radius: 8px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .image-upload-box:hover {
      border-color: var(--primary-color);
      background-color: rgba(255, 123, 0, 0.05);
    }

    .image-upload-box i {
      font-size: 1.8rem;
      color: var(--text-light);
      margin-bottom: 8px;
    }

    .image-upload-box span {
      font-size: 0.85rem;
      color: var(--text-light);
      text-align: center;
      word-break: break-all;
    }
    
    /* Style for the preview image */
    .image-upload-box img {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 6px;
    }

    /* NEW: Style for image name display */
    .image-name-container {
      flex: 1;
      padding: 10px;
      background-color: #f8f9fa;
      border-radius: 8px;
      border: 1px solid #e1e5eb;
    }

    .image-name-label {
      font-size: 0.85rem;
      color: var(--text-light);
      margin-bottom: 5px;
    }

    .image-name {
      font-size: 0.9rem;
      color: var(--text-dark);
      font-weight: 500;
      word-break: break-all;
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

      .form-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
      }

      .form-footer {
        flex-direction: column;
        gap: 10px;
      }

      .btn {
        width: 100%;
        justify-content: center;
      }

      .image-upload-container {
        flex-direction: column;
        align-items: flex-start;
      }
      
      .top-header {
        height: 50px;
        padding: 0 10px;
      }
      
      .search-bar {
        padding: 6px 10px;
      }
      
      .add-product-content {
        padding: 15px;
      }
      
      .page-title {
        font-size: 1.4rem;
      }
      
      .form-container {
        padding: 15px;
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
    }
  </style>
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
          <input type="text" placeholder="">
        </div>
        
        <div class="header-actions">
          <div class="user-profile">
            <span class="user-name">Admin</span>
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
              <a href="logout.php" class="profile-dropdown-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
              </a>
            </div>
          </div>
        </div>
      </header>

      <!-- ADD PRODUCT CONTENT -->
      <div class="add-product-content">
        <div class="breadcrumb">
          <a href="dashboard.php">Dashboard</a>
          <span>/</span>
          <a href="adminproduct.php">Products</a>
          <span>/</span>
          <span>Add Product</span>
        </div>
        
        <h1 class="page-title">Add New Product</h1>
        
        <!-- FORM CONTAINER -->
        <div class="form-container">
          <div class="form-header">
            <h2 class="form-title">Product Information</h2>
          </div>
          
          <form id="productForm" method="POST" enctype="multipart/form-data">
            <!-- BASIC INFORMATION -->
            <div class="form-section">
              <h3 class="section-title">
                <i class="fas fa-info-circle"></i>
                Basic Information
              </h3>
              
              <div class="form-group">
                <label for="productName">Product Name *</label>
                <input type="text" class="form-control" id="productName" name="productName" required>
              </div>
              
              <div class="form-group">
                <label for="productCategory">Category *</label>
                <select class="form-control" id="productCategory" name="productCategory" required>
                    <option value="">Select Category</option>
                    <option value="a2-cow-ghee">A2 Cow Ghee</option>
                    <option value="beverages">Beverages</option>
                    <option value="cooking-paste-chutney">Cooking Paste & Chutney</option>
                    <option value="dry-bhakhri">Dry Bhakhri</option>
                    <option value="indian-sweets">Indian Sweets</option>
                    <option value="jam">Jam</option>
                    <option value="namkeen">Namkeen</option>
                    <option value="pickles">Pickles</option>
                    <option value="ready-to-eat">Ready to Eat</option>
                    <option value="sauces">Sauces</option>
                    <option value="spices-powder">Spices Powder</option>
                    <option value="whole-spices">Whole Spices</option>
                </select>
              </div>
              
              <div class="form-group">
                <label>Available in: *</label>
                <div class="packet-sizes-container" id="packetSizesContainer">
                  <!-- Packet sizes will be dynamically added here -->
                </div>
                <button type="button" class="btn btn-secondary btn-sm add-packet-size-btn" id="addPacketSizeBtn">
                  <i class="fas fa-plus"></i>
                  Add Packet Size
                </button>
              </div>
              
              <div class="form-group">
                <label for="productDescription">Description *</label>
                <textarea class="form-control" id="productDescription" name="productDescription" rows="4" required></textarea>
              </div>
              
              <div class="form-group">
                <label for="keyFeatures">Key Features *</label>
                <textarea class="form-control" id="keyFeatures" name="keyFeatures" rows="3" required></textarea>
              </div>
            </div>
            
            <!-- PRODUCT IMAGE -->
            <div class="form-section">
              <h3 class="section-title">
                <i class="fas fa-image"></i>
                Product Image
              </h3>
              
              <div class="image-upload-container">
                <div class="image-upload-box" id="imageUploadBox">
                  <i class="fas fa-cloud-upload-alt"></i>
                  <span>Upload Image</span>
                  <input type="file" id="productImage" name="productImage" accept="image/*" required style="display: none;">
                </div>
                
                <!-- NEW: Image name display container -->
                <div class="image-name-container">
                  <div class="image-name-label">Selected File:</div>
                  <div class="image-name" id="imageName">No file selected</div>
                </div>
              </div>
            </div>
          </form>
          
          <!-- FORM FOOTER WITH BUTTONS -->
          <div class="form-footer">
            <button type="button" class="btn btn-secondary" id="cancelBtn">
              <i class="fas fa-times"></i>
              Cancel
            </button>
            <button type="submit" form="productForm" class="btn btn-primary" id="saveProductBtn">
              <i class="fas fa-save"></i>
              Save Product
            </button>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script>
    // ==================== MOBILE MENU FUNCTIONALITY ====================
    const MobileMenu = {
      elements: {
        mobileMenuBtn: document.getElementById('mobileMenuBtn'),
        sidebar: document.getElementById('sidebar'),
        closeSidebarBtn: document.getElementById('closeSidebarBtn'),
        overlay: document.getElementById('overlay'),
        menuItems: document.querySelectorAll('.menu-item')
      },

      init() {
        this.bindEvents();
      },

      bindEvents() {
        this.elements.mobileMenuBtn.addEventListener('click', this.openMenu);
        this.elements.closeSidebarBtn.addEventListener('click', this.closeMenu);
        this.elements.overlay.addEventListener('click', this.closeMenu);
        
        this.elements.menuItems.forEach(item => {
          item.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
              this.closeMenu();
            }
          });
        });

        document.addEventListener('keydown', (e) => {
          if (e.key === 'Escape' && this.elements.sidebar.classList.contains('active')) {
            this.closeMenu();
          }
        });

        window.addEventListener('resize', () => {
          if (window.innerWidth > 768 && this.elements.sidebar.classList.contains('active')) {
            this.closeMenu();
          }
        });
      },

      openMenu() {
        MobileMenu.elements.sidebar.classList.add('active');
        MobileMenu.elements.overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
      },

      closeMenu() {
        MobileMenu.elements.sidebar.classList.remove('active');
        MobileMenu.elements.overlay.classList.remove('active');
        document.body.style.overflow = '';
      }
    };

    // ==================== PROFILE DROPDOWN FUNCTIONALITY ====================
    const ProfileDropdown = {
      elements: {
        toggle: document.getElementById('profileDropdownToggle'),
        dropdown: document.getElementById('profileDropdown')
      },

      init() {
        this.bindEvents();
      },

      bindEvents() {
        this.elements.toggle.addEventListener('click', (e) => {
          e.stopPropagation();
          this.elements.dropdown.classList.toggle('active');
        });

        document.addEventListener('click', (e) => {
          if (!this.elements.dropdown.contains(e.target) && e.target !== this.elements.toggle) {
            this.elements.dropdown.classList.remove('active');
          }
        });
      }
    };

    // ==================== IMAGE UPLOAD FUNCTIONALITY ====================
    const ImageUpload = {
      elements: {
        imageUploadBox: document.getElementById('imageUploadBox'),
        fileInput: document.getElementById('productImage'),
        imageName: document.getElementById('imageName')
      },

      init() {
        this.bindEvents();
      },

      bindEvents() {
        this.elements.imageUploadBox.addEventListener('click', () => {
          this.elements.fileInput.click();
        });

        // NEW: Add event listener for file selection
        this.elements.fileInput.addEventListener('change', (e) => this.handleImageSelect(e));
      },

      // NEW: Function to handle image selection and preview
      handleImageSelect(e) {
        const input = e.target;
        if (input.files && input.files[0]) {
          const file = input.files[0];
          
          // Update the image name display
          this.elements.imageName.textContent = file.name;

          // Use FileReader to show a preview
          const reader = new FileReader();
          reader.onload = (e) => {
            // Create a new div for the preview instead of replacing the entire content
            // This ensures the file input remains intact
            const previewContainer = document.createElement('div');
            previewContainer.className = 'image-preview';
            previewContainer.style.position = 'absolute';
            previewContainer.style.top = '0';
            previewContainer.style.left = '0';
            previewContainer.style.width = '100%';
            previewContainer.style.height = '100%';
            previewContainer.style.borderRadius = '6px';
            previewContainer.style.overflow = 'hidden';
            
            const previewImg = document.createElement('img');
            previewImg.src = e.target.result;
            previewImg.alt = 'Image Preview';
            previewImg.style.width = '100%';
            previewImg.style.height = '100%';
            previewImg.style.objectFit = 'cover';
            
            previewContainer.appendChild(previewImg);
            
            // Clear any existing preview
            const existingPreview = this.elements.imageUploadBox.querySelector('.image-preview');
            if (existingPreview) {
              existingPreview.remove();
            }
            
            // Add the new preview
            this.elements.imageUploadBox.appendChild(previewContainer);
            this.elements.imageUploadBox.style.borderStyle = 'solid';
          };
          reader.readAsDataURL(file);
        } else {
          // Reset the image name display when no file is selected
          this.elements.imageName.textContent = 'No file selected';
          
          // Remove any existing preview
          const existingPreview = this.elements.imageUploadBox.querySelector('.image-preview');
          if (existingPreview) {
            existingPreview.remove();
          }
          this.elements.imageUploadBox.style.borderStyle = 'dashed';
        }
      }
    };

    // ==================== PACKET SIZES FUNCTIONALITY ====================
    const PacketSizes = {
      elements: {
        container: document.getElementById('packetSizesContainer'),
        addBtn: document.getElementById('addPacketSizeBtn')
      },

      defaultSizes: ['100g', '250g', '500g', '1kg'],
      idCounter: 0,

      init() {
        this.bindEvents();
        this.initializeDefaultSizes();
      },

      bindEvents() {
        this.elements.addBtn.addEventListener('click', () => this.addPacketSize());
      },

      initializeDefaultSizes() {
        this.defaultSizes.forEach(size => this.addPacketSize(size));
      },

      addPacketSize(value = '') {
        const id = `packet_${this.idCounter++}`;
        const packetSizeItem = document.createElement('div');
        packetSizeItem.className = 'packet-size-item';
        packetSizeItem.innerHTML = `
          <input type="checkbox" id="${id}" name="packet[]" value="${value}">
          <input type="text" class="form-control" placeholder="Enter packet size" value="${value}" data-packet-id="${id}">
          <button type="button" class="remove-btn" data-packet-id="${id}">
            <i class="fas fa-trash"></i>
          </button>
        `;
        
        this.elements.container.appendChild(packetSizeItem);
        
        const textInput = packetSizeItem.querySelector('input[type="text"]');
        const checkbox = packetSizeItem.querySelector('input[type="checkbox"]');
        const removeBtn = packetSizeItem.querySelector('.remove-btn');
        
        textInput.addEventListener('input', () => {
          checkbox.value = textInput.value;
        });
        
        removeBtn.addEventListener('click', () => {
          if (this.elements.container.children.length > 1) {
            packetSizeItem.remove();
          } else {
            alert('At least one packet size is required');
          }
        });
      }
    };

    // ==================== FORM FUNCTIONALITY ====================
    const ProductForm = {
      elements: {
        form: document.getElementById('productForm'),
        saveBtn: document.getElementById('saveProductBtn'),
        cancelBtn: document.getElementById('cancelBtn'),
        fileInput: document.getElementById('productImage')
      },

      init() {
        this.bindEvents();
      },

      bindEvents() {
        this.elements.form.addEventListener('submit', (e) => this.handleSubmit(e));
        this.elements.cancelBtn.addEventListener('click', () => this.handleCancel());
      },

      handleSubmit(e) {
        // Let the form submit naturally to the server
        // No need to prevent default as we want the form to submit to the PHP backend
      },

      handleCancel() {
        if (confirm('Are you sure you want to clear all data?')) {
          this.elements.form.reset();
          // Reset image upload preview
          ImageUpload.elements.imageUploadBox.style.borderStyle = 'dashed';
          const existingPreview = ImageUpload.elements.imageUploadBox.querySelector('.image-preview');
          if (existingPreview) {
            existingPreview.remove();
          }
          // Reset image name display
          ImageUpload.elements.imageName.textContent = 'No file selected';
          
          PacketSizes.elements.container.innerHTML = '';
          PacketSizes.idCounter = 0;
          PacketSizes.initializeDefaultSizes();
        }
      }
    };

    // ==================== INITIALIZE ALL MODULES ====================
    document.addEventListener('DOMContentLoaded', () => {
      MobileMenu.init();
      ProfileDropdown.init(); // Initialize the profile dropdown
      ImageUpload.init();
      PacketSizes.init();
      ProductForm.init();
    });
  </script>
</body>
</html>