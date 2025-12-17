<?php
session_start();
require_once 'db/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Basic input sanitization
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate: empty fields
    if (empty($username) || empty($password)) {
        echo "<script>alert('Both fields are required'); window.location.href='login.php';</script>";
        exit();
    }

    // Validate username (letters, numbers, underscore only)
    if (!preg_match('/^[a-zA-Z0-9_]{3,30}$/', $username)) {
        echo "<script>alert('Invalid username format'); window.location.href='login.php';</script>";
        exit();
    }

    // Validate password length
    if (strlen($password) < 6) {
        echo "<script>alert('Password must be at least 6 characters'); window.location.href='login.php';</script>";
        exit();
    }


    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Login success
            $_SESSION['username'] = $user['username'];
            header("Location: admin/dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid password'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('User not found'); window.location.href='login.php';</script>";
    }

    $stmt->close();
    $conn->close();
}

$cooldownRemaining = 0;

if (isset($_SESSION['cooldown'])) {
    $cooldownRemaining = $_SESSION['cooldown'] - time();
    if ($cooldownRemaining < 0) {
        $cooldownRemaining = 0;
        unset($_SESSION['cooldown']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login | Global Taste Exports</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="icon" type="image/png" sizes="96x96" href="favicon1.png">
  <link rel="stylesheet" href="login.css">
</head>

<body>
  <!-- LOADING SCREEN -->
  <div class="loader-wrapper">
    <div class="loader"></div>
  </div>

  <!-- HEADER -->
  <header id="header">
    <a href="index.php">
      <img src="assets/companylogo1.jpg" alt="Company Logo">
    </a>
    <nav id="nav">
      <a href="index.php">Home</a>
    </nav>
  </header>

  <!-- LOGIN SECTION -->
  <section class="login-section">
    <div class="login-container">
      <div class="login-image">
        <img src="assets/companylogo1.jpg" alt="Company Logo">
        <h2>Admin Portal</h2>
        <p>Access the Global Taste Exports administration panel to manage products, orders, and customer data.</p>
      </div>
      <div class="login-form-container">
        <h3>Admin Login</h3>
        <p>Enter your credentials to access the admin panel</p>
        
        <div class="security-notice">
          <i class="fas fa-shield-alt"></i>
          <span>This is a secure area. Unauthorized access is prohibited and will be logged.</span>
        </div>
        
        <div class="error-message" id="errorMessage">
          <i class="fas fa-exclamation-circle"></i>
          <span>Invalid username or password. Please try again.</span>
        </div>
        
        <div class="success-message" id="successMessage">
          <i class="fas fa-check-circle"></i>
          <span>Login successful! Redirecting to dashboard...</span>
        </div>
        
        <form method="post" id="loginForm">
          <div class="form-group">
            <label for="username">Username</label>
            <i class="fas fa-user"></i>
            <input type="text" id="username" name="username" placeholder="Enter your username" required autocomplete="username">
          </div>
          
          <div class="form-group">
            <label for="password">Password</label>
            <i class="fas fa-lock"></i>
            <input type="password" id="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
          </div>
          
          <div class="form-options">
            
           <a href="resetpass.php" class="forgot-password" id="forgotPasswordBtn">Forgot Password?</a>

          </div>
          
          <button type="submit" class="login-btn">
            Sign In <i class="fas fa-arrow-right"></i>
          </button>
        </form>
        
        <div class="back-to-home">
          <a href="index.php">
            <i class="fas fa-arrow-left"></i> Back to Home
          </a>
        </div>
      </div>
    </div>
  </section>

  
  <script src="login.js"></script>
  <script>
let timeLeft = <?php echo $cooldownRemaining; ?>;
let btn = document.getElementById("forgotPasswordBtn");

if (timeLeft > 0) {
    btn.style.pointerEvents = "none";
    btn.style.opacity = "0.6";
    btn.innerHTML = "Please wait (" + timeLeft + "s)";

    let timer = setInterval(() => {
        timeLeft--;
        btn.innerHTML = "Please wait (" + timeLeft + "s)";

        if (timeLeft <= 0) {
            clearInterval(timer);
            btn.style.pointerEvents = "auto";
            btn.style.opacity = "1";
            btn.innerHTML = "Forgot Password?";
        }
    }, 1000);
}
</script>


</body>
</html>