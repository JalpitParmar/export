<?php
session_start();
include '../db/db.php'; // your database connection file

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// Get the username from session
 $username = $_SESSION['username'];

// Handle form submission
 $message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $businessAddress = trim($_POST['businessAddress'] ?? '');
    $primaryPhone    = trim($_POST['primaryPhone'] ?? '');
    $generalEmail    = trim($_POST['generalEmail'] ?? '');
    $exportEmail     = trim($_POST['exportEmail'] ?? '');

    // -----------------------------------
    // VALIDATION
    // -----------------------------------

    // Business Address (optional but limit length)
    if (!empty($businessAddress) && strlen($businessAddress) > 200) {
        echo "<script>alert('Business address cannot exceed 200 characters.'); window.history.back();</script>";
        exit();
    }

    // Primary Phone validation (digits only, 10–15 digits)
    if (empty($primaryPhone) || !preg_match('/^[0-9]{10,15}$/', $primaryPhone)) {
        echo "<script>alert('Invalid primary phone number. Only 10–15 digits allowed.'); window.history.back();</script>";
        exit();
    }

    // General Email validation
    if (empty($generalEmail) || !filter_var($generalEmail, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid general email address.'); window.history.back();</script>";
        exit();
    }

    // Export Email validation (optional)
    if (!empty($exportEmail) && !filter_var($exportEmail, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid export email address.'); window.history.back();</script>";
        exit();
    }

    if (empty($businessAddress) || empty($primaryPhone) || empty($generalEmail)) {
        $message = '<div class="error-message">⚠️ Please fill all required fields.</div>';
    } else {
        $stmt = $conn->prepare("UPDATE users SET business_address=?, phone_number=?, email=? WHERE username=?");
        $stmt->bind_param("ssss", $businessAddress, $primaryPhone, $generalEmail, $username);
        if ($stmt->execute()) {
            // Set session variable to indicate successful update
            $_SESSION['update_success'] = true;
            // Redirect to the same page to prevent form resubmission
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $message = '<div class="error-message">❌ Failed to update settings. Please try again.</div>';
        }
        $stmt->close();
    }
}

// Check if there was a successful update in the previous request
if (isset($_SESSION['update_success']) && $_SESSION['update_success']) {
    $message = '<div class="success-message">✅ Settings updated successfully!</div>';
    // Clear the session variable so the message doesn't appear again
    unset($_SESSION['update_success']);
}

// Load existing settings from database
 $stmt = $conn->prepare("SELECT business_address, phone_number, email FROM users WHERE username=?");
 $stmt->bind_param("s", $username);
 $stmt->execute();
 $result = $stmt->get_result()->fetch_assoc();
 $stmt->close();
 $conn->close();

// Default values if nothing in DB
 $businessAddress = $result['business_address'] ?? '';
 $primaryPhone = $result['phone_number'] ?? '';
 $generalEmail = $result['email'] ?? '';
 $exportEmail = ''; // optional field
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Settings | Global Taste Exports</title>
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
      --header-height: 60px; /* Reduced from 70px */
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
      padding: 15px; /* Reduced from 20px */
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-header img {
      height: 35px; /* Reduced from 40px */
    }

    .sidebar-header h3 {
  color: white !important;
  font-size: 1.2rem !important;
  font-weight: 600;
  margin-left: 65px;
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
      padding: 15px 0; /* Reduced from 20px */
    }

    .menu-item {
      padding: 12px 20px; /* Reduced from 15px */
      display: flex;
      align-items: center;
      color: rgba(255, 255, 255, 0.8);
      text-decoration: none;
      transition: all 0.3s ease;
      position: relative;
    }

    .menu-item i {
      margin-right: 15px;
      font-size: 1rem; /* Reduced from 1.1rem */
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
      padding: 0 20px; /* Reduced from 30px */
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
      padding: 8px 15px; /* Reduced from 10px 20px */
      width: 350px; /* Reduced from 400px */
    }

    .search-bar input {
      border: none;
      background: none;
      outline: none;
      width: 100%;
      margin-left: 10px;
      font-size: 0.9rem; /* Added smaller font size */
    }

    .header-actions {
      display: flex;
      align-items: center;
    }

    .notification-icon {
      position: relative;
      margin-right: 15px; /* Reduced from 20px */
      font-size: 1.1rem; /* Reduced from 1.2rem */
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

    .user-avatar {
      width: 35px; /* Reduced from 40px */
      height: 35px; /* Reduced from 40px */
      border-radius: 50%;
      margin-right: 8px; /* Reduced from 10px */
      object-fit: cover;
    }

    .user-name {
      font-weight: 600;
      margin-right: 8px; /* Reduced from 10px */
      font-size: 0.9rem; /* Added smaller font size */
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
      padding: 20px; /* Reduced from 30px */
    }

    .page-title {
      font-size: 1.6rem; /* Reduced from 1.8rem */
      font-weight: 700;
      margin-bottom: 25px; /* Reduced from 30px */
      color: var(--text-dark);
    }

    /* ===== SETTINGS TABS ===== */
    .settings-tabs {
      display: flex;
      border-bottom: 1px solid #e1e5eb;
      margin-bottom: 25px; /* Reduced from 30px */
      overflow-x: auto;
    }

    .settings-tab {
      padding: 12px 20px; /* Reduced from 15px 25px */
      font-weight: 500;
      color: var(--text-light);
      cursor: pointer;
      position: relative;
      white-space: nowrap;
      transition: all 0.3s ease;
      font-size: 0.9rem; /* Added smaller font size */
    }

    .settings-tab:hover {
      color: var(--primary-color);
    }

    .settings-tab.active {
      color: var(--primary-color);
    }

    .settings-tab.active::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 3px;
      background-color: var(--primary-color);
    }

    .settings-tab i {
      margin-right: 8px; /* Reduced from 10px */
    }

    /* ===== SETTINGS PANELS ===== */
    .settings-panel {
      display: none;
      animation: fadeIn 0.3s ease;
    }

    .settings-panel.active {
      display: block;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    .settings-section {
      background-color: var(--bg-white);
      border-radius: 15px;
      padding: 20px; /* Reduced from 30px */
      box-shadow: var(--shadow);
      margin-bottom: 20px; /* Reduced from 25px */
    }

    .section-title {
      font-size: 1.2rem; /* Reduced from 1.3rem */
      font-weight: 600;
      margin-bottom: 15px; /* Reduced from 20px */
      color: var(--text-dark);
      display: flex;
      align-items: center;
    }

    .section-title i {
      margin-right: 8px; /* Reduced from 10px */
      color: var(--primary-color);
    }

    .section-description {
      color: var(--text-light);
      margin-bottom: 20px; /* Reduced from 25px */
      line-height: 1.6;
      font-size: 0.9rem; /* Added smaller font size */
    }

    /* ===== FORM ELEMENTS ===== */
    .form-group {
      margin-bottom: 20px; /* Reduced from 25px */
    }

    .form-group label {
      display: block;
      margin-bottom: 6px; /* Reduced from 8px */
      font-weight: 500;
      color: var(--text-dark);
    }

    .form-control {
      width: 100%;
      padding: 10px 12px; /* Reduced from 12px 15px */
      border: 1px solid #e1e5eb;
      border-radius: 8px;
      font-family: inherit;
      font-size: 0.9rem; /* Reduced from 1rem */
      transition: all 0.3s ease;
    }

    .form-control:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(255, 123, 0, 0.1);
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px; /* Reduced from 20px */
    }

    .form-row-3 {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 15px; /* Reduced from 20px */
    }

    textarea.form-control {
      resize: vertical;
      
      min-height: 80px; /* Reduced from 100px */
    }

    /* ===== LOGO UPLOAD ===== */
    .logo-upload {
      display: flex;
      align-items: center;
      gap: 15px; /* Reduced from 20px */
    }

    .logo-preview {
      width: 100px; /* Reduced from 120px */
      height: 100px; /* Reduced from 120px */
      border-radius: 10px;
      background-color: #f5f7fa;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
      border: 2px dashed #e1e5eb;
    }

    .logo-preview img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }

    .upload-btn {
      padding: 8px 15px; /* Reduced from 10px 20px */
      background-color: #f5f7fa;
      border-radius: 8px;
      font-size: 0.85rem; /* Reduced from 0.9rem */
      cursor: pointer;
      transition: all 0.3s ease;
      border: 1px solid #e1e5eb;
    }

    .upload-btn:hover {
      background-color: #e1e5eb;
    }

    /* ===== COLOR PICKER ===== */
    .color-picker-group {
      display: flex;
      align-items: center;
      gap: 12px; /* Reduced from 15px */
    }

    .color-picker {
      width: 50px; /* Reduced from 60px */
      height: 35px; /* Reduced from 40px */
      border: 1px solid #e1e5eb;
      border-radius: 8px;
      cursor: pointer;
    }

    .color-code {
      flex: 1;
    }

    /* ===== SWITCH TOGGLE ===== */
    .switch-group {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 12px; /* Reduced from 15px */
    }

    .switch-label {
      font-weight: 500;
    }

    .switch {
      position: relative;
      display: inline-block;
      width: 45px; /* Reduced from 50px */
      height: 24px; /* Reduced from 26px */
    }

    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      transition: .4s;
      border-radius: 34px;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 16px; /* Reduced from 18px */
      width: 16px; /* Reduced from 18px */
      left: 4px;
      bottom: 4px;
      background-color: white;
      transition: .4s;
      border-radius: 50%;
    }

    input:checked + .slider {
      background-color: var(--primary-color);
    }

    input:checked + .slider:before {
      transform: translateX(21px); /* Reduced from 24px */
    }

    /* ===== BUTTONS ===== */
    .btn {
      padding: 8px 15px; /* Reduced from 12px 25px */
      border-radius: 8px;
      font-weight: 500;
      font-size: 0.85rem; /* Reduced from 1rem */
      cursor: pointer;
      transition: all 0.3s ease;
      border: none;
      display: inline-flex;
      align-items: center;
    }

    .btn i {
      margin-right: 6px; /* Reduced from 8px */
    }

    .btn-primary {
      background-color: var(--primary-color);
      color: white;
    }

    .btn-primary:hover {
      background-color: var(--secondary-color);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(255, 123, 0, 0.3);
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

    .btn-group {
      display: flex;
      gap: 12px; /* Reduced from 15px */
      margin-top: 25px; /* Reduced from 30px */
    }

    /* ===== SUCCESS MESSAGE ===== */
    .success-message {
      background-color: rgba(76, 175, 80, 0.1);
      color: #4CAF50;
      padding: 12px 15px; /* Reduced from 15px 20px */
      border-radius: 8px;
      margin-bottom: 15px; /* Reduced from 20px */
      display: flex;
      align-items: center;
      animation: fadeIn 0.3s ease;
    }

    .success-message i {
      margin-right: 8px; /* Reduced from 10px */
    }
    
    /* ===== ERROR MESSAGE ===== */
    .error-message {
      background-color: rgba(244, 67, 54, 0.1);
      color: #F44336;
      padding: 12px 15px;
      border-radius: 8px;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      animation: fadeIn 0.3s ease;
    }

    .error-message i {
      margin-right: 8px;
    }

    /* ===== AVATAR UPLOAD ===== */
    .avatar-upload {
      display: flex;
      align-items: center;
      gap: 15px; /* Reduced from 20px */
    }

    .avatar-preview {
      width: 80px; /* Reduced from 100px */
      height: 80px; /* Reduced from 100px */
      border-radius: 50%;
      background-color: #f5f7fa;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
      border: 3px solid #e1e5eb;
    }

    .avatar-preview img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    /* ===== MOBILE RESPONSIVE ===== */
    .mobile-menu-btn {
      display: none;
      background: none;
      border: none;
      font-size: 20px; /* Reduced from 24px */
      color: var(--primary-color);
      cursor: pointer;
      padding: 5px; /* Added padding */
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
        width: 250px; /* Reduced from 280px */
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
        margin-right: 10px; /* Reduced from 15px */
      }

      .user-name {
        display: none;
      }

      .form-row, .form-row-3 {
        grid-template-columns: 1fr;
      }

      .settings-tabs {
        overflow-x: scroll;
      }

      .btn-group {
        flex-direction: column;
      }

      .dashboard-content {
        padding: 15px; /* Reduced from 20px */
      }

      .settings-section {
        padding: 15px; /* Reduced from 20px */
      }

      .logo-upload {
        flex-direction: column;
        align-items: flex-start;
      }

      .logo-preview {
        width: 80px; /* Further reduced for mobile */
        height: 80px; /* Further reduced for mobile */
      }
      
      .top-header {
        height: 50px; /* Further reduced for mobile */
        padding: 0 10px; /* Further reduced for mobile */
      }
      
      .search-bar {
        padding: 6px 10px; /* Further reduced for mobile */
      }
      
      .notification-icon {
        margin-right: 10px; /* Further reduced for mobile */
      }
      
      .user-avatar {
        width: 30px; /* Further reduced for mobile */
        height: 30px; /* Further reduced for mobile */
      }
    }

    @media (max-width: 576px) {
      .top-header {
        padding: 0 10px; /* Reduced from 15px */
      }

      .btn {
        padding: 6px 10px; /* Further reduced for mobile */
        font-size: 0.75rem; /* Further reduced for mobile */
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
        <div style="display: flex; align-items: center;">
          <img src="../assets/companylogo1.jpg" alt="Company Logo">
          <h3>Admin Panel</h3>
        </div>
        <button class="close-sidebar-btn" id="closeSidebarBtn">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <nav class="sidebar-menu">
        <a href="dashboard.php" class="menu-item">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
        <a href="adminproduct.php" class="menu-item">
          <i class="fas fa-box"></i>
          <span>Products</span>
        </a>
        <a href="settings.php" class="menu-item active">
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
          <!-- <div class="notification-icon">
            <i class="fas fa-bell"></i>
            <span class="notification-badge">3</span>
          </div> -->
          
          <div class="user-profile">
            <!-- <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User" class="user-avatar"> -->
            <span class="user-name"><?= htmlspecialchars($username) ?></span>
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
              <!-- <a href="#" class="profile-dropdown-item">
                <i class="fas fa-bell"></i>
                <span>Notifications</span>
              </a> -->
              <div class="profile-dropdown-divider"></div>
              <a href="logout.php" class="profile-dropdown-item" >
                <i class="fas fa-sign-out-alt" id="logoutBtn"></i>
                <span>Logout</span>
              </a>
            </div>
          </div>
        </div>
      </header>

      <!-- DASHBOARD CONTENT -->
      <div class="dashboard-content">
        <h1 class="page-title">Settings</h1>
        
        <!-- Display PHP message here -->
        <?php if (!empty($message)) echo $message; ?>
        
        <!-- SETTINGS TABS -->
        <div class="settings-tabs">
          <div class="settings-tab active" data-tab="contact">
            <i class="fas fa-map-marker-alt"></i>
            Contact Details
          </div>
        </div>
        
        <!-- CONTACT DETAILS PANEL -->
        <div class="settings-panel active" id="contact-panel">
          <div class="settings-section">
            <h3 class="section-title">
              <i class="fas fa-map-marker-alt"></i>
              Contact Details
            </h3>
            <p class="section-description">
              Update your business contact information.
            </p>
            
           <form method="POST" action="" id="settingsForm">
          <div class="settings-section">
            

            <div class="form-group">
              <label for="businessAddress">Business Address</label>
              <textarea class="form-control" name="businessAddress" id="businessAddress" rows="3"><?= htmlspecialchars($businessAddress) ?></textarea>
            </div>

            <div class="form-group">
              <label for="primaryPhone">Primary Phone</label>
              <input type="tel" class="form-control" name="primaryPhone" id="primaryPhone" value="<?= htmlspecialchars($primaryPhone) ?>">
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="generalEmail">General Email</label>
                <input type="email" class="form-control" name="generalEmail" id="generalEmail" value="<?= htmlspecialchars($generalEmail) ?>">
              </div>
              
              
            </div>
          </div>

          <div class="btn-group">
            <button type="submit" class="btn btn-primary" id="saveSettings"><i class="fas fa-save"></i> Save Changes</button>
            <button type="reset" class="btn btn-secondary" id="resetSettings"><i class="fas fa-undo"></i> Reset</button>
          </div>
        </form>
            </div>
          </div>
        </div>
        
       
      </div>
    </main>
  </div>
  <script>// MOBILE MENU TOGGLE
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const closeSidebarBtn = document.getElementById('closeSidebarBtn');
    const sidebar = document.getElementById('sidebar');
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
    
    // TAB FUNCTIONALITY
    const settingsTabs = document.querySelectorAll('.settings-tab');
    const settingsPanels = document.querySelectorAll('.settings-panel');
    
    settingsTabs.forEach(tab => {
      tab.addEventListener('click', function() {
        const targetTab = this.getAttribute('data-tab');
        
        // Remove active class from all tabs and panels
        settingsTabs.forEach(t => t.classList.remove('active'));
        settingsPanels.forEach(p => p.classList.remove('active'));
        
        // Add active class to clicked tab and corresponding panel
        this.classList.add('active');
        document.getElementById(`${targetTab}-panel`).classList.add('active');
      });
    });
    
    // Hide success message after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
      const successMessage = document.querySelector('.success-message');
      if (successMessage) {
        setTimeout(function() {
          successMessage.style.display = 'none';
        }, 5000);
      }
      
      const errorMessage = document.querySelector('.error-message');
      if (errorMessage) {
        setTimeout(function() {
          errorMessage.style.display = 'none';
        }, 5000);
      }
    });
    
    // RESET SETTINGS FUNCTIONALITY
    const resetSettingsBtn = document.getElementById('resetSettings');
    
    resetSettingsBtn.addEventListener('click', function() {
      if (confirm('Are you sure you want to reset all settings to default values?')) {
        // Reset form values to defaults
        document.getElementById('businessAddress').value = '123 Export Street, Mumbai, India 400001';
        document.getElementById('primaryPhone').value = '+91 98765 43210';
        document.getElementById('generalEmail').value = 'info@globaltasteexports.com';
        document.getElementById('exportEmail').value = 'export@globaltasteexports.com';
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