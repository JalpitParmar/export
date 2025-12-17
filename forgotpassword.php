<?php
session_start();
require_once 'db/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $newPassword = trim($_POST['newPassword'] ?? '');
    $confirmPassword = trim($_POST['confirmPassword'] ?? '');

    // ---------------------------------
    // VALIDATION
    // ---------------------------------

    // Required fields
    if (empty($newPassword) || empty($confirmPassword)) {
        echo "<script>alert('All password fields are required.'); window.history.back();</script>";
        exit();
    }

    // Match check
    if ($newPassword !== $confirmPassword) {
        echo "<script>alert('Password and confirm password do not match.'); window.history.back();</script>";
        exit();
    }

    // Minimum length
    if (strlen($newPassword) < 6) {
        echo "<script>alert('Password must be at least 6 characters long.'); window.history.back();</script>";
        exit();
    }

    // Strong password check (recommended)
    // At least: 1 uppercase, 1 lowercase, 1 number
    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{6,}$/', $newPassword)) {
        echo "<script>alert('Password must contain uppercase, lowercase, and number.'); window.history.back();</script>";
        exit();
    }

    if ($newPassword !== $confirmPassword) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        // Hash Password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update DB (using session stored username from forgot password flow)
        $result = $conn->query("SELECT `username` FROM `users` LIMIT 1");
$row = $result->fetch_assoc();
$username = $row['username'];
 // username stored earlier

        $stmt = $conn->prepare("UPDATE users SET password=? WHERE username=?");
        $stmt->bind_param("ss", $hashedPassword, $username);

        if ($stmt->execute()) {
            echo "<script>alert('Password Updated Successfully! Please login.');</script>";
            echo "<script>window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Error updating password.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password | Global Taste Exports</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="icon"sizes="96x96"  type="image/x-icon" href="companylogo1.jpg">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f5f5f5;
      line-height: 1.6;
    }
    
    /* Header Styles */
    #header {
      background-color: white;
      padding: 15px 30px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    #header img {
      height: 50px;
    }
    
    #nav a {
      color: #ff6b35;
      text-decoration: none;
      font-weight: 600;
      font-size: 16px;
      transition: color 0.3s ease;
    }
    
    #nav a:hover {
      color: #e85a2b;
    }
    
    /* Login Section */
    .login-section {
      padding: 40px 0;
      min-height: calc(100vh - 80px);
      display: flex;
      align-items: center;
    }
    
    .forgot-password-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 40px 20px;
    }
    
    .forgot-password-content {
      display: flex;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }
    
    .forgot-password-image {
      flex: 1;
      background: linear-gradient(135deg, #ff6b35, #ff8c42);
      color: white;
      padding: 60px 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
    }
    
    .forgot-password-image img {
      height: 100px;
      margin-bottom: 30px;
      border-radius: 50%;
      background-color: white;
      padding: 10px;
    }
    
    .forgot-password-image h2 {
      font-size: 28px;
      margin-bottom: 20px;
    }
    
    .forgot-password-image p {
      font-size: 16px;
      line-height: 1.6;
      opacity: 0.9;
    }
    
    .forgot-password-form-container {
      flex: 1;
      padding: 60px 40px;
    }
    
    .forgot-password-form-container h3 {
      color: #333;
      font-size: 24px;
      margin-bottom: 10px;
    }
    
    .forgot-password-form-container > p {
      color: #666;
      margin-bottom: 30px;
    }
    
    .instructions {
      background-color: #fff4f0;
      border-left: 4px solid #ff6b35;
      padding: 15px;
      margin-bottom: 30px;
      border-radius: 0 5px 5px 0;
    }
    
    .instructions p {
      margin: 0;
      color: #555;
      display: flex;
      align-items: center;
    }
    
    .instructions i {
      margin-right: 10px;
      color: #ff6b35;
    }
    
    .back-to-login {
      text-align: center;
      margin-top: 30px;
    }
    
    .back-to-login a {
      color: #ff6b35;
      text-decoration: none;
      font-weight: 500;
      display: inline-flex;
      align-items: center;
      transition: all 0.3s ease;
    }
    
    .back-to-login a:hover {
      color: #e85a2b;
      transform: translateX(-5px);
    }
    
    .back-to-login i {
      margin-right: 8px;
    }
    
    .form-group {
      margin-bottom: 25px;
      position: relative;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      color: #333;
    }
    
    .form-group i {
      position: absolute;
      left: 15px;
      top: 42px;
      color: #777;
    }
    
    .form-group input {
      width: 100%;
      padding: 12px 15px 12px 45px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 16px;
      transition: all 0.3s ease;
    }
    
    .form-group input:focus {
      border-color: #ff6b35;
      outline: none;
      box-shadow: 0 0 0 2px rgba(255, 107, 53, 0.2);
    }
    
    .save-btn {
      width: 100%;
      padding: 15px;
      background: linear-gradient(135deg, #ff6b35, #ff8c42);
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .save-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
    }
    
    .save-btn i {
      margin-left: 10px;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
      .forgot-password-content {
        flex-direction: column;
      }
      
      .forgot-password-image {
        padding: 40px 20px;
      }
      
      .forgot-password-form-container {
        padding: 40px 20px;
      }
      
      #header {
        padding: 10px 20px;
      }
      
      #header img {
        height: 40px;
      }
    }
  </style>
</head>

<body>
  <!-- HEADER -->
  <header id="header">
    <img src="assets/companylogo1.jpg" alt="Company Logo">
    <nav id="nav">
      <a href="index.php">Home</a>
    </nav>
  </header>

  <!-- RESET PASSWORD SECTION -->
  <section class="login-section">
    <div class="forgot-password-container">
      <div class="forgot-password-content">
        <div class="forgot-password-image">
          <img src="assets/companylogo1.jpg" alt="Company Logo">
          <h2>Reset Your Password</h2>
          <p>Create a strong password for your account. Make sure it's unique and secure to protect your information.</p>
        </div>
        
        <div class="forgot-password-form-container">
          <h3>Create New Password</h3>
          <p>Enter your new password below to secure your account</p>
          
          <div class="instructions">
            <p><i class="fas fa-info-circle"></i> Password should be at least 8 characters long with a mix of letters, numbers, and symbols.</p>
          </div>
          
          <form method="post" id="resetPasswordForm">
            <div class="form-group">
              <label for="newPassword">New Password</label>
              <i class="fas fa-lock"></i>
              <input type="password" id="newPassword" name="newPassword" placeholder="Enter your new password" required>
            </div>
            
            <div class="form-group">
              <label for="confirmPassword">Confirm Password</label>
              <i class="fas fa-lock"></i>
              <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm your new password" required>
            </div>
            
            <button type="submit" class="save-btn">
              Save Password <i class="fas fa-save"></i>
            </button>
          </form>
          
          <div class="back-to-login">
            <a href="login.php">
              <i class="fas fa-arrow-left"></i> Back to Login
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>
</html>