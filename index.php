<?php
include 'db/db.php'; // include your DB connection file

// Fetch contact info for admin (assuming id=1 or use WHERE username='admin')
 $sql = "SELECT business_address, phone_number, email, business_hours FROM users WHERE id = 1";
 $result = $conn->query($sql);
 $contact = $result->fetch_assoc();

// Fetch products
 $products = $conn->query("SELECT * FROM products");

// Count total product categories (distinct categories)
 $totalCategoriesQuery = $conn->query("SELECT COUNT(DISTINCT category) AS total FROM products");
 $totalCategories = $totalCategoriesQuery->fetch_assoc()['total'];

// Count total product varieties (all products)
 $totalProductsQuery = $conn->query("SELECT COUNT(*) AS total FROM products");
 $totalProducts = $totalProductsQuery->fetch_assoc()['total'];
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

    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--bg-light);
      color: var(--text-dark);
      overflow-x: hidden;
      scroll-behavior: smooth;
      
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
    }

    header.scrolled {
      padding: 8px 50px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    header img {
      height: 70px;
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

    /* ===== HERO SECTION ===== */
    .hero {
  background-color: var(--primary-color); /* Add a fallback background color */
  background-image: 
    linear-gradient(135deg, rgba(255,123,0,0.7), rgba(255,149,0,0.5)),
    url('https://images.unsplash.com/photo-1590080875832-6dbe3d7a9b16?auto=format&fit=crop&w=1600&q=80');
  background-size: cover;
  background-position: center;
  height: 90vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  color: white;
  position: relative;
  overflow: hidden;
}

    .hero::before {
      content: "";
      position: absolute;
      top: 0;
      left: -100%;
      width: 200%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      animation: shine 3s infinite;
    }

    @keyframes shine {
      0% { left: -100%; }
      20% { left: 100%; }
      100% { left: 100%; }
    }

    .hero-video {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  z-index: 0;
  opacity: 0.85;
}

    .hero-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, rgba(255,123,0,0.35), rgba(255,149,0,0.25));
  z-index: 1;
}
    .hero-content {
      position: relative;
      z-index: 2;
      padding: 0 20px;
      max-width: 800px;
      animation: fadeInUp 1s ease;
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .hero h2 {
      font-size: 3.5em;
      font-weight: 800;
      text-shadow: 2px 2px 10px rgba(0,0,0,0.3);
      margin-bottom: 20px;
      letter-spacing: -1px;
    }

    .hero p {
      max-width: 700px;
      margin: 0 auto 30px;
      font-size: 1.2em;
      line-height: 1.6;
      font-weight: 400;
    }

    .btn {
      background-color: var(--primary-color);
      color: white;
      padding: 15px 35px;
      border-radius: 50px;
      text-decoration: none;
      font-weight: 600;
      font-size: 1.1em;
      display: inline-block;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(139, 67, 0, 0.4);
      position: relative;
      overflow: hidden;
    }

    .btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
      transition: left 0.5s;
    }

    .btn:hover::before {
      left: 100%;
    }

    .btn:hover {
      background-color: var(--secondary-color);
      transform: translateY(-3px);
      box-shadow: 0 6px 20px #e47a1680;
    }

    .btn-secondary {
      background-color: transparent;
      border: 2px solid white;
      margin-left: 15px;
    }

    .btn-secondary:hover {
      background-color: white;
      color: var(--primary-color);
    }

    /* ===== STATS SECTION ===== */
    .stats {
      padding: 80px 50px;
      color:darkorange ;
      text-align: center;
    }

    .stats-container {
      display: flex;
      justify-content: space-around;
      flex-wrap: wrap;
      max-width: 1200px;
      margin: 0 auto;
    }

    .stat-item {
      flex-basis: 200px;
      margin: 20px;
      transition: transform 0.3s ease;
    }

    .stat-item:hover {
      transform: translateY(-10px);
    }

    .stat-number {
      font-size: 3em;
      font-weight: 800;
      margin-bottom: 10px;
    }

    .stat-label {
      font-size: 1.2em;
      font-weight: 500;
    }

    /* ===== ABOUT SECTION ===== */
    .about {
      text-align: center;
      padding: 80px 50px;
      background-color: var(--bg-white);
      position: relative;
    }

    .about::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 5px;
      background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
    }

    .section-title {
      color: gray;
      font-size: 2.5em;
      font-weight: 700;
      margin-bottom: 20px;
      position: relative;
      display: inline-block;
    }

    .section-title::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 3px;
      background-color: var(--primary-color);
    }

    .about-content {
      display: flex;
      align-items: center;
      justify-content: center;
      max-width: 1200px;
      margin: 50px auto 0;
      gap: 50px;
    }

    .about-text {
      flex: 1;
      text-align: left;
    }

    .about-text p {
      color: var(--text-light);
      line-height: 1.8;
      font-size: 1.1em;
      margin-bottom: 20px;
    }

    .about-image {
      flex: 1;
      position: relative;
    }

    .about-image img {
      width: 100%;
      height: 400px; /* Set a fixed height */
      object-fit: cover; /* This ensures the image covers the area without distortion */
      border-radius: 15px;
      box-shadow: var(--shadow);
      transition: transform 0.5s ease;
    }

    .about-image:hover img {
      transform: scale(1.03);
    }

    .about-image::before {
      content: '';
      position: absolute;
      top: -20px;
      left: -20px;
      right: 20px;
      bottom: 20px;
      border: 3px solid var(--primary-color);
      border-radius: 15px;
      z-index: -1;
    }

    /* ===== CATEGORIES ===== */
    .categories {
      padding: 80px 15px;
      text-align: center;
      background-color: var(--bg-light);
      position: relative;
    }

    .categories-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
      max-width: 1200px;
      margin: 50px auto 0;
    }

    .category {
      background: var(--bg-white);
      border-radius: 15px;
      overflow: hidden;
      box-shadow: var(--shadow);
      transition: all 0.4s ease;
      position: relative;
      height: 100%;
      display: flex;
      flex-direction: column;
      
    }

    .category::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 5px;
      background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
      transform: scaleX(0);
      transform-origin: left;
      transition: transform 0.3s ease;
    }

    .category:hover::before {
      transform: scaleX(1);
    }

    .category:hover {
      transform: translateY(-10px);
      box-shadow: var(--shadow-hover);
    }

    .category-image {
      height: 200px;
      overflow: hidden;
      position: relative;
      background-color: #f5f5f5;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .category-image {
  height: 200px;
  overflow: hidden;
  position: relative;
  background-color: #f5f5f5;
  display: flex;
  justify-content: center;
  align-items: center;
}

.category img {
  width: 100%;
  height: 100%;
  object-fit: contain; /* Changed from 'cover' to 'contain' */
  transition: transform 0.5s ease;
  display: block;
  max-width: 100%;
}

    .category-content {
      padding: 20px;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      
    }

    .category h4 {
      color: var(--primary-color);
      font-size: 1.3em;
      margin-bottom: 12px;
    }

    .category p {
      color: var(--text-light);
      line-height: 1.5;
      margin-bottom: 15px;
      font-size: 0.95em;
      flex-grow: 1;
      
    }

    .category-link {
      color: var(--primary-color);
      font-weight: 600;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      transition: all 0.3s ease;
      align-self: flex-start;
    }

    .category-link i {
      margin-left: 5px;
      transition: transform 0.3s ease;
    }

    .category-link:hover i {
      transform: translateX(5px);
    }

    /* ===== PROCESS SECTION ===== */
    .process {
      padding: 80px 50px;
      background-color: var(--bg-white);
      text-align: center;
    }

    .process-container {
      display: flex;
      justify-content: space-between;
      max-width: 1200px;
      margin: 50px auto 0;
      position: relative;
    }

    .process-container::before {
      content: '';
      position: absolute;
      top: 50px;
      left: 50px;
      right: 50px;
      height: 3px;
      background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
      z-index: 0;
    }

    .process-step {
      flex: 1;
      position: relative;
      z-index: 1;
      padding: 0 15px;
    }

    .step-icon {
      width: 100px;
      height: 100px;
      background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0 auto 20px;
      color: white;
      font-size: 2em;
      box-shadow: 0 5px 15px rgba(255, 123, 0, 0.3);
      transition: transform 0.3s ease;
    }

    .process-step:hover .step-icon {
      transform: scale(1.1);
    }

    .step-title {
      font-size: 1.3em;
      font-weight: 600;
      color: var(--primary-color);
      margin-bottom: 10px;
    }

    .step-description {
      color: var(--text-light);
      line-height: 1.6;
    }

    /* ===== MISSION & VALUES SECTION ===== */
    .mission-values {
      padding: 80px 50px;
      background-color: var(--bg-light);
      text-align: center;
    }

    .mission-container {
      max-width: 800px;
      margin: 0 auto 60px;
    }

    .mission-statement {
      font-size: 1.4em;
      line-height: 1.6;
      color: var(--text-dark);
      font-weight: 500;
      font-style: italic;
      position: relative;
      padding: 0 30px;
    }

    .mission-statement::before,
    .mission-statement::after {
      content: '"';
      font-size: 3em;
      color: var(--primary-color);
      position: absolute;
      opacity: 0.5;
    }

    .mission-statement::before {
      top: -20px;
      left: 0;
    }

    .mission-statement::after {
      bottom: -40px;
      right: 0;
    }

    .values-container {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 25px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .value-item {
      flex-basis: 220px;
      background: var(--bg-white);
      border-radius: 15px;
      padding: 30px 20px;
      box-shadow: var(--shadow);
      transition: all 0.3s ease;
      text-align: center;
    }

    .value-item:hover {
      transform: translateY(-10px);
      box-shadow: var(--shadow-hover);
    }

    .value-icon {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0 auto 20px;
      color: white;
      font-size: 1.8em;
      box-shadow: 0 5px 15px rgba(255, 123, 0, 0.3);
      transition: transform 0.3s ease;
    }

    .value-item:hover .value-icon {
      transform: scale(1.1);
    }

    .value-title {
      font-size: 1.2em;
      font-weight: 600;
      color: var(--primary-color);
      margin-bottom: 10px;
    }

    .value-description {
      color: var(--text-light);
      line-height: 1.5;
      font-size: 0.95em;
    }

    /* ===== CERTIFICATIONS ===== */
.certifications {
  background-color: var(--bg-white);
  padding: 80px 50px;
  text-align: center;
}

.certifications p {
  max-width: 800px;
  margin: 20px auto 50px;
  color: var(--text-light);
  line-height: 1.7;
  font-size: 1.1em;
}

.certification-logos {
  display: flex;
  justify-content: center;
  gap: 60px;
  flex-wrap: wrap;
}

.certification-item {
  transition: all 0.3s ease;
}

.certification-item:hover {
  transform: scale(1.1);
}

/* Removed grayscale */
.certification-logos img {
  width: 120px;
  height: 120px;
  object-fit: contain;
  transition: transform 0.3s ease;
}

/* No hover filter */
.certification-item:hover img {
  transform: scale(1.1);
}


    /* ===== CONTACT SECTION ===== */
    .contact {
      padding: 80px 50px;
      background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
      color: white;
      text-align: center;
    }

    .contact-container {
      display: flex;
      justify-content: space-between;
      max-width: 1200px;
      margin: 50px auto 0;
      gap: 50px;
    }

    .contact-info, .contact-form {
      flex: 1;
      background: rgba(255, 255, 255, 0.1);
      padding: 40px;
      border-radius: 15px;
      backdrop-filter: blur(10px);
    }

    .contact-item {
      display: flex;
      align-items: center;
      margin-bottom: 30px;
      text-align: left;
    }

    .contact-icon {
      width: 50px;
      height: 50px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      margin-right: 20px;
      font-size: 1.2em;
    }

    .contact-details h4 {
      font-size: 1.2em;
      margin-bottom: 5px;
    }

    .contact-details p {
      opacity: 0.9;
    }

    /* ===== FOOTER ===== */
    footer {
      background-color: #333;
      color: white;
      padding: 60px 50px 20px;
    }

    .footer-content {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      max-width: 1200px;
      margin: 0 auto 40px;
    }

    .footer-section {
      flex: 1;
      min-width: 250px;
      margin-bottom: 30px;
      padding-right: 30px;
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
    .footer-section a.admin-btn {
  background: white;
  color: #ff9500;
  border: none;
  padding: 12px 30px;
  border-radius: 50px;
  font-weight: 600;
  font-size: 1em;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-block;
  text-decoration: none;
  margin-top: 10px;
}

.footer-section a.admin-btn:hover {
  transform: translateY(-3px);
  color: #cc6300;
  box-shadow: 0 5px 15px rgba(255, 170, 0, 0.79);
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
    
/* ===== CONTACT FORM STYLING ===== */

/* Style for each form group (input container) */
.contact-form .form-group {
  margin-bottom: 20px;
}

/* Style for all input fields and textarea */
.contact-form input[type="text"],
.contact-form input[type="email"],
.contact-form textarea {
  width: 100%;
  padding: 15px;
  border: 1px solid rgba(255, 255, 255, 0.3); /* Semi-transparent border */
  border-radius: 8px;
  background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white background */
  color: var(--text-dark);
  font-family: 'Poppins', sans-serif;
  font-size: 1em;
  transition: all 0.3s ease;
}

/* Style for the placeholder text */
.contact-form input::placeholder,
.contact-form textarea::placeholder {
  color: #888; /* A medium gray for placeholders */
}

/* Focus state for input fields and textarea */
.contact-form input:focus,
.contact-form textarea:focus {
  outline: none;
  border-color: var(--bg-white); /* Solid white border on focus */
  background-color: var(--bg-white); /* Solid white background on focus */
  box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
}

/* Ensure the submit button takes full width and has consistent styling */
.contact-form .btn {
  width: 100%;
  padding: 15px;
  border: none;
  margin-top: 10px;
  font-size: 1.1em;
  cursor: pointer;
  transition: all 0.3s ease;
}

/* Hover effect for the submit button */
.contact-form .btn:hover {
  background-color: var(--secondary-color);
  transform: translateY(-3px);
  box-shadow: 0 6px 20px rgba(228, 122, 22, 0.5);
}
.category-link-wrapper{text-decoration:none;}
/* Style for the form message feedback div */
#formMessage {
  margin-top: 15px;
  padding: 15px;
  border-radius: 8px;
  font-weight: 500;
  display: none; /* Hidden by default */
  text-align: center;
}

/* Style for a success message (to be toggled with JavaScript) */
#formMessage.success {
  background-color: rgba(40, 167, 69, 0.9); /* Green with transparency */
  color: white;
  display: block;
  border: 1px solid rgba(40, 167, 69, 0.5);
}

/* Style for an error message (to be toggled with JavaScript) */
#formMessage.error {
  background-color: rgba(220, 53, 69, 0.9); /* Red with transparency */
  color: white;
  display: block;
  border: 1px solid rgba(220, 53, 69, 0.5);
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

    /* ===== ANIMATIONS ===== */
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    .fade-in {
      opacity: 0;
      transform: translateY(30px);
      transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .fade-in.active {
      opacity: 1;
      transform: translateY(0);
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
    @media (max-width: 992px) {
      .about-content {
        flex-direction: column;
      }
      .about-image {
        width:90%;
      }
      .contact-container {
        flex-direction: column;
      }

      .process-container::before {
        display: none;
      }

    }

    @media (max-width: 768px) {
      header {
        padding: 10px 20px;
      }
      .about-image {
        min-height: 200px;
        width:100%;
      }
  
      .about-image img {
        height: 200px;
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

      .hero h2 {
        font-size: 2.5em;
      }

      .hero p {
        font-size: 1.1em;
      }
      .hero {
    height: 80vh; /* Full viewport height on mobile */
  }
  
  .hero-video {
    object-fit: contain; /* Show entire video without cropping */
    object-position: center;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
  }
  
  .hero-overlay {
    background: linear-gradient(135deg, rgba(250, 157, 70, 0.7), rgba(255,149,0,0.5)); /* Darker overlay for better text visibility */
  }

      .btn-secondary {
        display: none;
      }

      .stats-container {
        flex-direction: column;
      }
        
      /* 3x3 Grid for Mobile Categories */
      .categories {
        padding: 60px 10px;
      }

      .categories-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        margin: 40px auto 0;
        max-width: 100%;
      }

      .category {
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
      }

      .category:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
      }

      .category-image {
        height: 100px; /* Increased height for better image display */
        background-color: #f5f5f5;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        padding: 5px; /* Added padding to prevent edge cropping */
      }

      .category img {
        width: 100%;
        height: 100%;
        object-fit: contain; /* Changed from cover to contain */
        display: block;
        max-width: 100%;
        transition: transform 0.5s ease;
        border-radius: 5px; /* Added slight border radius */
      }

      .category:hover img {
        transform: scale(1.05); /* Reduced scale effect */
      }

      .category-content {
        padding: 12px 8px;
      }

      .category h4 {
        font-size: 0.95em;
        margin-bottom: 6px;
        line-height: 1.2;
      }

      .category p {
        font-size: 0.75em;
        line-height: 1.3;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        max-height: 3em; /* line-height * number of lines */
        overflow: hidden;
      }

      .category-link {
        font-size: 0.8em;
        padding: 4px 8px;
        border-radius: 15px;
        background: rgba(255, 123, 0, 0.1);
      }

      .category-link:hover {
        background: var(--primary-color);
        color: white;
      }

      .process-container {
        flex-direction: column;
        gap: 40px;
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
      .hero h2 {
        font-size: 2em;
      }

      .section-title {
        font-size: 2em;
      }

      /* Further adjustments for very small screens */
      .categories {
        padding: 40px 8px;
      }

      .categories-grid {
        gap: 6px;
      }

      .category {
        border-radius: 8px;
      }

      .category-image {
        height: 90px; /* Adjusted height */
        background-color: #f5f5f5;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        padding: 4px; /* Adjusted padding */
      }

      .category img {
        width: 100%;
        height: 100%;
        object-fit: contain; /* Changed from cover to contain */
        display: block;
        max-width: 100%;
        transition: transform 0.5s ease;
        border-radius: 4px; /* Adjusted border radius */
      }

      .category:hover img {
        transform: scale(1.05); /* Reduced scale effect */
      }

      .category-content {
        padding: 10px 6px;
      }

      .category h4 {
        font-size: 0.9em;
      }

      .category p {
        font-size: 0.7em;
      }

      .category-link {
        font-size: 0.75em;
        padding: 3px 6px;
      }
    }

    @media (max-width: 480px) {
      /* For very small phones - keep 3 columns but make them even smaller */
      .categories-grid {
        gap: 5px;
      }

      .category-image {
        height: 80px; /* Adjusted height */
        background-color: #f5f5f5;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        padding: 3px; /* Adjusted padding */
      }

      .category img {
        width: 100%;
        height: 100%;
        object-fit: contain; /* Changed from cover to contain */
        display: block;
        max-width: 100%;
        transition: transform 0.5s ease;
        border-radius: 3px; /* Adjusted border radius */
      }

      .category:hover img {
        transform: scale(1.05); /* Reduced scale effect */
      }

      .category-content {
        padding: 8px 5px;
      }

      .category h4 {
        font-size: 0.85em;
      }

      .category p {
        font-size: 0.65em;
        max-height: 5em; /* line-height * number of lines */
        overflow: hidden;
      }
    }

    @media (max-width: 380px) {
      /* For extremely small phones - switch to 2 columns */
      .categories-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
      }

      .category-image {
        height: 95px; /* Adjusted height for 2-column layout */
        background-color: #f5f5f5;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        padding: 4px; /* Adjusted padding */
      }

      .category img {
        width: 100%;
        height: 100%;
        object-fit: contain; /* Changed from cover to contain */
        display: block;
        max-width: 100%;
        transition: transform 0.5s ease;
        border-radius: 4px; /* Adjusted border radius */
      }

      .category:hover img {
        transform: scale(1.05); /* Reduced scale effect */
      }

      .category-content {
        padding: 10px 8px;
      }

      .category h4 {
        font-size: 0.95em;
      }

      .category p {
        font-size: 0.75em;
        max-height: 2.6em; /* line-height * number of lines */
        overflow: hidden;
      }
    }

    /* ===== CONTACT SECTION MOBILE FIXES ===== */
    @media (max-width: 768px) {
      .contact {
        padding: 60px 20px;
      }
      
      .contact-container {
        flex-direction: column;
        gap: 30px;
      }
      
      .contact-info, .contact-form {
        padding: 30px 20px;
      }
      
      .contact-item {
        margin-bottom: 20px;
      }
      
      .contact-icon {
        width: 45px;
        height: 45px;
        margin-right: 15px;
      }
      
      .contact-details h4 {
        font-size: 1.1em;
      }
      
      .contact-details p {
        font-size: 0.95em;
      }
    }

    @media (max-width: 576px) {
      /* Further adjustments for very small screens */
      .contact {
        padding: 50px 15px;
      }
      
      .contact-info, .contact-form {
        padding: 25px 15px;
      }
      
      .contact-item {
        flex-direction: column;
        text-align: center;
        margin-bottom: 25px;
      }
      
      .contact-icon {
        margin-right: 0;
        margin-bottom: 15px;
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
  <div class="loader-wrapper" id="loaderWrapper">
    <div class="loader"></div>
  </div>

  <!-- MOBILE MENU OVERLAY -->
  <div class="menu-overlay" id="menuOverlay"></div>

  <?php include 'header.php'; ?>



  <!-- HEADER -->
  <header id="header">
    <img src="assets/companylogo1.jpg" alt="Company Logo">
    <nav id="nav">
      <a href="#home">Home</a>
      <a href="aboutus.php">About Us</a>
      <a href="#products">Products</a>
      <a href="#process">Process</a>
      <a href="#mission-values">Mission & Values</a>
      <a href="#certifications">Certifications</a>
      <a href="#contact">Contact</a>
    </nav>
    <button class="mobile-menu-btn" id="mobileMenuBtn">
      <i class="fas fa-bars"></i>
    </button>
  </header>

  <!-- HERO SECTION -->
  <section class="hero" id="home">
    <video autoplay muted loop playsinline class="hero-video">
    <source src="assets/video.mp4" type="video/mp4">
    Your browser does not support the video tag.
  </video>
  <div class="hero-overlay"></div>
    <div class="hero-content">
      <h2>Delivering India's Taste to the World</h2>
      <p>Your trusted global source for premium Indian food products, crafted with authenticity, purity, and unmatched quality.</p>
      <div>
        <a href="#products" class="btn">Explore Products</a>
        <a href="#contact" class="btn btn-secondary">Contact Us</a>
      </div>
    </div>
  </section>

  <!-- STATS SECTION -->
<section class="stats">
  <div class="stats-container">
    <div class="stat-item">
      <div class="stat-number"><?= $totalCategories ?>+</div>
      <div class="stat-label">Product Categories</div>
    </div>
    <div class="stat-item">
      <div class="stat-number"><?= $totalProducts ?>+</div>
      <div class="stat-label">Product Varieties</div>
    </div>
    <div class="stat-item">
      <div class="stat-number"><?php echo "9"; ?>+</div>
      <div class="stat-label">Global Presence</div>
    </div>
  </div>
</section>
  <!-- ABOUT SECTION -->
  <section class="about fade-in" id="about">
    <h3 class="section-title">About Us</h3>
    <div class="about-content">
      <div class="about-text">
        <p>At bhoomi trade line, we are dedicated to crafting and exporting authentic Indian snacks, including our signature brand the kesari, while also bringing a variety of other premium Indian products to customers around the world.</p>
        <p>Each product is made with utmost care, blending traditional recipes with modern hygiene and quality standards to ensure consistency and satisfaction. Our commitment to excellence ensures that every snack meets international food safety benchmarks.</p>
        <p>With a focus on quality, taste, and authenticity, bhoomi trade line is your trusted partner for premium Indian snacks and other Indian specialties that delight customers across the globe.</p>
      </div>
      <div class="about-image">
        <img src="assets/about.jpg" alt="Our Factory">
      </div>
    </div>
  </section>

<!-- CATEGORIES SECTION -->
<section class="categories fade-in" id="products">
  <h3 class="section-title">Our Product Categories</h3>
  <div class="categories-grid">

    <!-- Namkeen -->
    <a href="product.php?category=namkeen" class="category-link-wrapper"  >
      <div class="category">
        <div class="category-image">
          <img src="assets/namkeens.jpg" alt="Namkeen" >
        </div>
        <div class="category-content">
          <h4>Namkeen</h4>
          <p>Authentic Indian savory snacks with traditional recipes.</p>
          <div class="category-link">Explore <i class="fas fa-arrow-right"></i></div>
        </div>
      </div>
    </a>

    <!-- Spices Powder -->
    <a href="product.php?category=spices-powder" class="category-link-wrapper">
      <div class="category">
        <div class="category-image">
          <img src="assets/spicespowder.jpg" alt="Spices Powder" onerror="this.src='https://picsum.photos/seed/spices/300/180.jpg'">
        </div>
        <div class="category-content">
          <h4>Spices Powder</h4>
          <p>Finely ground spices for rich aroma and bold flavors.</p>
          <div class="category-link">Explore <i class="fas fa-arrow-right"></i></div>
        </div>
      </div>
    </a>

    <!-- Whole Spices -->
    <a href="product.php?category=whole-spices" class="category-link-wrapper">
      <div class="category">
        <div class="category-image">
          <img src="assets/wholespices.png" alt="Whole Spices" onerror="this.src='https://picsum.photos/seed/wholespices/300/180.jpg'">
        </div>
        <div class="category-content">
          <h4>Whole Spices</h4>
          <p>Pure, hand-picked whole spices for authentic cooking.</p>
          <div class="category-link">Explore <i class="fas fa-arrow-right"></i></div>
        </div>
      </div>
    </a>

    <!-- Cooking Paste & Chutney -->
    <a href="product.php?category=cooking-paste-chutney" class="category-link-wrapper">
      <div class="category">
        <div class="category-image">
          <img src="assets/cooking-paste1.jpg" alt="Cooking Paste & Chutney" onerror="this.src='https://picsum.photos/seed/chutney/300/180.jpg'">
        </div>
        <div class="category-content">
          <h4>Cooking Paste & Chutney</h4>
          <p>Quick, flavorful recipe bases and delicious traditional chutneys.</p>
          <div class="category-link">Explore <i class="fas fa-arrow-right"></i></div>
        </div>
      </div>
    </a>

    <!-- Sauces -->
    <a href="product.php?category=sauces" class="category-link-wrapper">
      <div class="category">
        <div class="category-image">
          <img src="assets/sauce1.jpg" alt="Sauce" onerror="this.src='https://picsum.photos/seed/sauce/300/180.jpg'">
        </div>
        <div class="category-content">
          <h4>Sauces</h4>
          <p>Tangy, spicy, and flavour-packed sauces for every taste.</p>
          <div class="category-link">Explore <i class="fas fa-arrow-right"></i></div>
        </div>
      </div>
    </a>

    <!-- Pickles -->
    <a href="product.php?category=pickles" class="category-link-wrapper">
      <div class="category">
        <div class="category-image">
          <img src="assets/pickles.jpg" alt="Pickles" onerror="this.src='https://picsum.photos/seed/pickles/300/180.jpg'">
        </div>
        <div class="category-content">
          <h4>Pickles</h4>
          <p>Traditional Indian pickles bursting with bold flavours.</p>
          <div class="category-link">Explore <i class="fas fa-arrow-right"></i></div>
        </div>
      </div>
    </a>

    <!-- Jam -->
    <a href="product.php?category=jam" class="category-link-wrapper">
      <div class="category">
        <div class="category-image">
          <img src="assets/jam.jpg" alt="Jam" onerror="this.src='https://picsum.photos/seed/jam/300/180.jpg'">
        </div>
        <div class="category-content">
          <h4>Jam</h4>
          <p>Sweet and fruity spreads made from real fruits.</p>
          <div class="category-link">Explore <i class="fas fa-arrow-right"></i></div>
        </div>
      </div>
    </a>

    <!-- Ready to Eat -->
    <a href="product.php?category=ready-to-eat" class="category-link-wrapper">
      <div class="category">
        <div class="category-image">
          <img src="assets/ready-to-eat.jpg" alt="Ready to Eat" onerror="this.src='https://picsum.photos/seed/readytoeat/300/180.jpg'">
        </div>
        <div class="category-content">
          <h4>Ready to Eat</h4>
          <p>Instant meals crafted for convenience without compromise.</p>
          <div class="category-link">Explore <i class="fas fa-arrow-right"></i></div>
        </div>
      </div>
    </a>

    <!-- Dry Bhakhri -->
    <a href="product.php?category=dry-bhakhri" class="category-link-wrapper">
      <div class="category">
        <div class="category-image">
          <img src="assets/dry-bhakhri.jpg" alt="Dry Bhakhri" onerror="this.src='https://picsum.photos/seed/bhakhri/300/180.jpg'">
        </div>
        <div class="category-content">
          <h4>Dry Bhakhri & Khakhra</h4>
          <p>Crispy and wholesome Gujarati-style dry bhakhri.</p>
          <div class="category-link">Explore <i class="fas fa-arrow-right"></i></div>
        </div>
      </div>
    </a>

    <!-- Beverages -->
    <a href="product.php?category=beverages" class="category-link-wrapper">
      <div class="category">
        <div class="category-image">
          <img src="assets/beverages.png" alt="Beverages" onerror="this.src='https://picsum.photos/seed/beverages/300/180.jpg'">
        </div>
        <div class="category-content">
          <h4>Beverages</h4>
          <p>Refreshing drinks crafted to energize and delight.</p>
          <div class="category-link">Explore <i class="fas fa-arrow-right"></i></div>
        </div>
      </div>
    </a>

    <!-- Indian Sweets -->
    <a href="product.php?category=indian-sweets" class="category-link-wrapper">
      <div class="category">
        <div class="category-image">
          <img src="assets/indian-sweets.jpg" alt="Indian Sweets" onerror="this.src='https://picsum.photos/seed/sweets/300/180.jpg'">
        </div>
        <div class="category-content">
          <h4>Indian Sweets</h4>
          <p>Traditional mithai made with pure ingredients and love.</p>
          <div class="category-link">Explore <i class="fas fa-arrow-right"></i></div>
        </div>
      </div>
    </a>

    <!-- A2 Cow Ghee -->
    <a href="product.php?category=a2-cow-ghee" class="category-link-wrapper">
      <div class="category">
        <div class="category-image">
          <img src="assets/a2-ghee.jpg" alt="A2 Cow Ghee" onerror="this.src='https://picsum.photos/seed/ghee/300/180.jpg'">
        </div>
        <div class="category-content">
          <h4>A2 Cow Ghee</h4>
          <p>Pure and aromatic A2 ghee crafted from indigenous cow milk.</p>
          <div class="category-link">Explore <i class="fas fa-arrow-right"></i></div>
        </div>
      </div>
    </a>

  </div>
</section>
  <!-- PROCESS SECTION -->
  <section class="process fade-in" id="process">
    <h3 class="section-title">Our Process</h3>
    <div class="process-container">
      <div class="process-step">
        <div class="step-icon">
          <i class="fas fa-seedling"></i>
        </div>
        <h4 class="step-title">Sourcing</h4>
        <p class="step-description">We source the finest ingredients directly from trusted farmers and suppliers across India.</p>
      </div>
      <div class="process-step">
        <div class="step-icon">
          <i class="fas fa-mortar-pestle"></i>
        </div>
        <h4 class="step-title">Production</h4>
        <p class="step-description">Our state-of-the-art facility combines traditional recipes with modern technology.</p>
      </div>
      <div class="process-step">
        <div class="step-icon">
          <i class="fas fa-vial"></i>
        </div>
        <h4 class="step-title">Quality Testing</h4>
        <p class="step-description">Every batch undergoes rigorous quality testing to ensure it meets our high standards.</p>
      </div>
      <div class="process-step">
        <div class="step-icon">
          <i class="fas fa-shipping-fast"></i>
        </div>
        <h4 class="step-title">Export</h4>
        <p class="step-description">We ensure safe and timely delivery to our global partners with proper packaging.</p>
      </div>
    </div>
  </section>

  <!-- MISSION & VALUES SECTION -->
  <section class="mission-values fade-in" id="mission-values">
    <h3 class="section-title">Our Mission & Values</h3>
    <div class="mission-container">
      <p class="mission-statement">To deliver authentic Indian flavors to the world, crafted with tradition, quality, and care.</p>
    </div>
    <div class="values-container">
      <div class="value-item">
        <div class="value-icon">
          <i class="fas fa-award"></i>
        </div>
        <h4 class="value-title">Quality First</h4>
        <p class="value-description">Every product meets the highest safety and taste standards.</p>
      </div>
      <div class="value-item">
        <div class="value-icon">
          <i class="fas fa-handshake"></i>
        </div>
        <h4 class="value-title">Customer Commitment</h4>
        <p class="value-description">We build long-term relationships through trust and satisfaction.</p>
      </div>
      <div class="value-item">
        <div class="value-icon">
          <i class="fas fa-balance-scale"></i>
        </div>
        <h4 class="value-title">Integrity</h4>
        <p class="value-description">We operate with honesty and transparency in every step.</p>
      </div>
      <div class="value-item">
        <div class="value-icon">
          <i class="fas fa-lightbulb"></i>
        </div>
        <h4 class="value-title">Innovation</h4>
        <p class="value-description">Blending traditional recipes with modern excellence.</p>
      </div>
      <div class="value-item">
        <div class="value-icon">
          <i class="fas fa-leaf"></i>
        </div>
        <h4 class="value-title">Sustainability</h4>
        <p class="value-description">Committed to eco-friendly practices in production and packaging.</p>
      </div>
    </div>
  </section>

  <!-- CERTIFICATIONS SECTION -->
  <section class="certifications fade-in" id="certifications">
    <h3 class="section-title">Quality & Certifications</h3>
    <p>Our production facilities are globally certified for safety and hygiene. We comply with major food standards, ensuring our customers receive products that meet international export quality.</p>
    <div class="certification-logos">
      <div class="certification-item">
        <img src="assets/iso.png" alt="ISO">
      </div>
      <div class="certification-item">
        <img src="assets/fssai.png" alt="FSSAI">
      </div>
      <div class="certification-item">
        <img src="assets/apeda.png" alt="APEDA">
      </div>
      <div class="certification-item">
        <img src="assets/spiceboard.png" alt="SPICEBOARD">
      </div>
      <div class="certification-item">
        <img src="assets/ce.jpg" alt="CE">
      </div>
    </div>
  </section>

  <!-- CONTACT SECTION -->
<section class="contact fade-in" id="contact">
  <h3 class="section-title" style="color:white;">Get In Touch</h3>
  <div class="contact-container">
    <div class="contact-info">
      <div class="contact-item">
        <div class="contact-icon">
          <i class="fas fa-map-marker-alt"></i>
        </div>
        <div class="contact-details">
          <h4>Our Address</h4>
          <p><?= htmlspecialchars($contact['business_address']) ?></p>
        </div>
      </div>
      <div class="contact-item">
        <div class="contact-icon">
          <i class="fas fa-phone"></i>
        </div>
        <div class="contact-details">
          <h4>Phone</h4>
          <p>+91<?= htmlspecialchars($contact['phone_number']) ?></p>
        </div>
      </div>
      <div class="contact-item">
        <div class="contact-icon">
          <i class="fas fa-envelope"></i>
        </div>
        <div class="contact-details">
          <h4>Email</h4>
          <p><?= htmlspecialchars($contact['email']) ?></p>
        </div>
      </div>
      <div class="contact-item">
        <div class="contact-icon">
          <i class="fas fa-clock"></i>
        </div>
        <div class="contact-details">
          <h4>Business Hours</h4>
          <p>Mon - Sat: 9:00 AM - 7:00 PM</p>
        </div>
      </div>
    </div>
    
    <!-- CONTACT FORM -->
    <div class="contact-form">
      <h4 style="margin-bottom: 20px; font-size: 1.4em;">Send Us a Message</h4>
      <form id="contactForm" action="send_email.php" method="post">
        <div class="form-group">
          <input type="text" name="name" id="name" placeholder="Your Name" required>
        </div>
        <div class="form-group">
          <input type="email" name="email" id="email" placeholder="Your Email" required>
        </div>
        <div class="form-group">
          <textarea name="message" id="message" rows="5" placeholder="Your Message" required></textarea>
        </div>
        <button type="submit" class="btn" style="width: 100%; padding: 15px; border: none; margin-top: 10px;">Send Message</button>
      </form>
      <div id="formMessage" style="margin-top: 15px; padding: 10px; border-radius: 5px; display: none;"></div>
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
  <p style="font-size:15px;"><i class="fas fa-envelope"></i> <?= htmlspecialchars($contact['email']) ?> </p>
  <p><i class="fas fa-globe"></i> www.bhoomitradeline.com</p><br/>
  <!--<a href="login.php" class="admin-btn">admin login</a>-->
</div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2025 Bhoomi Trade Line | All Rights Reserved | Privacy Policy | Terms of Service</p>
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

  <script src="index.js"></script>
</body>
</html> 