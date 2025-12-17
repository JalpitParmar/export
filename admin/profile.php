<?php
session_start();
require_once '../db/db.php';

// Simulate login for testing
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (isset($_POST['update_profile'])) {

    // Sanitize inputs
    $new_username = trim($_POST['username']);
    $new_email = trim($_POST['email']);
    $new_phone = trim($_POST['phone_number']);
    $new_bio = trim($_POST['bio'] ?? '');
    $new_address = trim($_POST['business_address'] ?? '');
    $new_hours = trim($_POST['business_hours'] ?? '');

    // -------------------------------
    // VALIDATION
    // -------------------------------

    // Username validation: required + alphanumeric + underscores only
    if (empty($new_username) || !preg_match('/^[a-zA-Z0-9_]{3,30}$/', $new_username)) {
        echo "<script>alert('Invalid username. Use 3-30 letters, numbers, or underscore.'); window.history.back();</script>";
        exit();
    }

    // Email validation
    if (empty($new_email) || !filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email address.'); window.history.back();</script>";
        exit();
    }

    // Phone validation (10–15 digits)
    if (!empty($new_phone) && !preg_match('/^[0-9]{10,15}$/', $new_phone)) {
        echo "<script>alert('Invalid phone number. Only digits allowed (10–15 digits).'); window.history.back();</script>";
        exit();
    }

    // Optional fields: max length check
    if (strlen($new_bio) > 300) {
        echo "<script>alert('Bio cannot exceed 300 characters.'); window.history.back();</script>";
        exit();
    }

    if (strlen($new_address) > 200) {
        echo "<script>alert('Address cannot exceed 200 characters.'); window.history.back();</script>";
        exit();
    }

    if (strlen($new_hours) > 100) {
        echo "<script>alert('Business hours cannot exceed 100 characters.'); window.history.back();</script>";
        exit();
    }

    $stmt = $conn->prepare("UPDATE users 
        SET username=?, email=?, phone_number=?, bio=?, business_address=?, business_hours=? 
        WHERE username=?");
    $stmt->bind_param("sssssss", $new_username, $new_email, $new_phone, $new_bio, $new_address, $new_hours, $username);
    
    if ($stmt->execute()) {
        echo "<script>alert('✅ Profile updated successfully!'); window.location='profile.php';</script>";
        $_SESSION['username'] = $new_username; // update session if username changed
    } else {
        echo "<script>alert('❌ Error updating profile');</script>";
    }
    $stmt->close();
}

// --- CHANGE PASSWORD ---
if (isset($_POST['change_password'])) {
    $current = trim($_POST['current_password'] ?? '');
    $newpass = trim($_POST['new_password'] ?? '');
    $confirm = trim($_POST['confirm_password'] ?? '');

    // -------------------------------
    // VALIDATION
    // -------------------------------

    // Required fields
    if (empty($current) || empty($newpass) || empty($confirm)) {
        echo "<script>alert('All password fields are required.'); window.history.back();</script>";
        exit();
    }

    // New password must match confirmation
    if ($newpass !== $confirm) {
        echo "<script>alert('New password and confirmation do not match.'); window.history.back();</script>";
        exit();
    }

    // New password length check (minimum 6)
    if (strlen($newpass) < 6) {
        echo "<script>alert('Password must be at least 6 characters long.'); window.history.back();</script>";
        exit();
    }

    // Optional: Strong password rule (uppercase + lowercase + number)
    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{6,}$/', $newpass)) {
        echo "<script>alert('Password must contain upper, lower, and number.'); window.history.back();</script>";
        exit();
    }

    // Prevent user from using the same old password
    if ($current === $newpass) {
        echo "<script>alert('New password cannot be the same as current password.'); window.history.back();</script>";
        exit();
    }

    if (empty($current) || empty($newpass) || empty($confirm)) {
        echo "<script>alert('Please fill all fields');</script>";
    } else {
        $stmt = $conn->prepare("SELECT password FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$result) {
            echo "<script>alert('User not found for $username');</script>";
        } elseif (!password_verify($current, $result['password'])) {
            echo "<script>alert('❌ Current password incorrect');</script>";
        } elseif ($newpass !== $confirm) {
            echo "<script>alert('❌ New passwords do not match');</script>";
        } else {
            $hashed = password_hash($newpass, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password=? WHERE username=?");
            $stmt->bind_param("ss", $hashed, $username);
            $stmt->execute();
            $stmt->close();

            echo "<script>alert('✅ Password changed successfully!'); window.location='profile.php';</script>";
        }
    }
}
// Count total products
 $totalProductsQuery = $conn->query("SELECT COUNT(*) AS total FROM products");
 $totalProducts = $totalProductsQuery->fetch_assoc()['total'];


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile | Global Taste Exports</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!--<link rel="icon" type="image/x-icon" href="../assets/companylogo1.jpg">-->
  <link rel="icon" type="image/png" sizes="96x96" href="favicon1.png">
  <link rel="stylesheet" href="css/admin-profile.css">
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
        
        <a href="settings.php" class="menu-item">
          <i class="fas fa-cog"></i>
          <span>Settings</span>
        </a>
        <a href="profile.php" class="menu-item active">
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
        <h1 class="page-title">My Profile</h1>
        
        <!-- SUCCESS MESSAGE -->
        <div class="success-message" id="successMessage">
          <i class="fas fa-check-circle"></i>
          <span>Profile updated successfully!</span>
        </div>
        
        <!-- PROFILE HEADER -->
        <div class="profile-header">
          <div class="profile-avatar-container">
            <!-- <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Profile Avatar" class="profile-avatar"> -->
            <!-- <div class="change-avatar-btn">
              <i class="fas fa-camera"></i>
            </div> -->
          </div>
          <div class="profile-info">
            <h2><?php echo htmlspecialchars($user['username']); ?></h2>
            <p><?php echo htmlspecialchars($user['email']); ?></p>
            <div class="profile-stats">
              <!-- <div class="stat-item">
                <span class="stat-value">5</span>
                <span class="stat-label">Years</span>
              </div> -->
              <div class="stat-item">
                <span class="stat-value"><?= $totalProducts ?></span>
                <span class="stat-label">Products Added</span>
              </div>
              <!-- <div class="stat-item">
                <span class="stat-value">98%</span>
                <span class="stat-label">Completion</span>
              </div> -->
            </div>
          </div>
        </div>
        
        <!-- PROFILE TABS -->
        <div class="profile-tabs">
          <div class="profile-tab active" data-tab="personal">
            <i class="fas fa-user"></i>
            Personal Information
          </div>
          <div class="profile-tab" data-tab="account">
            <i class="fas fa-lock"></i>
            Change Password
          </div>
        </div>
        <form method="POST">
        <!-- PERSONAL INFORMATION PANEL -->
        <div class="profile-panel active" id="personal-panel">
          <div class="profile-section">
            <h3 class="section-title">
              <i class="fas fa-user"></i>
              Personal Information
            </h3>
            <p class="section-description">
              Update your personal information and contact details.
            </p>
            <div class="form-group">
              <label for="fullName">Full Name</label>
              <input type="text" class="form-control" id="fullName"  name="username" value="<?php echo htmlspecialchars($user['username']); ?>"><br>
            </div>
            
            <div class="form-group">
              <label for="email">Email Address</label>
              <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"><br>
            </div>
            
            <div class="form-group">
              <label for="phone">Phone Number</label>
              <input type="text" class="form-control" id="phone" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>"><br>
            </div>
          </div>
           <!-- SAVE BUTTONS -->
        <div class="btn-group">
          <button  type="submit" name="update_profile" class="btn btn-primary" id="saveProfile">
            <i class="fas fa-save"></i>
            Save Changes
          </button>
          <button type="reset" class="btn btn-secondary" id="cancelChanges">
            <i class="fas fa-times"></i>
            Cancel
          </button>
        </div>
        </div>
        </form>
        <form method="POST" >
        <!-- CHANGE PASSWORD PANEL -->
        <div class="profile-panel" id="account-panel">
          <div class="profile-section">
            <h3 class="section-title">
              <i class="fas fa-lock"></i>
              Change Password
            </h3>
            <p class="section-description">
              Update your password to keep your account secure.
            </p>
            
            <div class="form-group">
              <label for="currentPassword">Current Password</label>
              <input type="password" name="current_password" class="form-control" id="currentPassword">
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label for="newPassword">New Password</label>
                <input type="password" name="new_password" class="form-control" id="newPassword">
              </div>
              
              <div class="form-group">
                <label for="confirmPassword">Confirm New Password</label>
                <input type="password" name="confirm_password" class="form-control" id="confirmPassword">
              </div>
               <!-- SAVE BUTTONS -->
        <div class="btn-group">
          <button  type="submit" name="change_password"  class="btn btn-primary" id="saveProfile">
            <i class="fas fa-save"></i>
            Save Changes
          </button>
          <button class="btn btn-secondary" id="cancelChanges">
            <i class="fas fa-times"></i>
            Cancel
          </button>
        </div>
            </div>
          </div>
        </div>
        </form>
       
      </div>
    </main>
  </div>
</form>

  <script src="js/admin-profile.js"></script>
</body>
</html>