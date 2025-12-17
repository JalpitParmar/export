<?php
include 'db/db.php'; // DB connection

// ----------- GET PRODUCT ID FROM URL -----------
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Product ID not provided.");
}
 $product_id = intval($_GET['id']);

// ----------- FETCH PRODUCT FROM DATABASE -----------
 $sql = "SELECT id, product_name, category, packet_sizes, description, key_features, image_path 
        FROM products 
        WHERE id = $product_id LIMIT 1";

 $result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Product not found!");
}

 $product = $result->fetch_assoc();

// ----------- Fetch Admin Contact Info -----------
 $sql2 = "SELECT business_address, phone_number, email, business_hours FROM users WHERE id = 1";
 $result2 = $conn->query($sql2);
 $contact = $result2->fetch_assoc();


 $category = $product['category'];

 $related_sql = "SELECT id, product_name, category, image_path, description 
                FROM products 
                WHERE category = '$category' AND id != $product_id 
                LIMIT 4";

 $related_result = $conn->query($related_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($product['product_name']) ?> | Bhoomi Trade Line</title>
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
  
  /* Font size variables for consistency */
  --font-size-xs: 0.75rem;
  --font-size-sm: 0.875rem;
  --font-size-base: 1rem;
  --font-size-lg: 1.125rem;
  --font-size-xl: 1.25rem;
  --font-size-2xl: 1.5rem;
  --font-size-3xl: 1.875rem;
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
  font-size: 16px; /* Base font size */
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
  outline: none;
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
  font-size: var(--font-size-sm);
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

/* ===== PRODUCT HERO SECTION ===== */
.product-hero {
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

.product-hero h1 {
  font-size: var(--font-size-3xl);
  font-weight: 800;
  text-shadow: 2px 2px 10px rgba(0,0,0,0.3);
  margin-bottom: 20px;
  letter-spacing: -1px;
  padding: 0 20px;
}

.product-hero p {
  max-width: 700px;
  margin: 0 auto;
  font-size: var(--font-size-xl);
  line-height: 1.6;
  font-weight: 400;
  padding: 0 20px;
}

/* ===== PRODUCT DETAIL SECTION ===== */
.product-detail-section {
  padding: 50px 5%;
  background-color: var(--bg-white);
  width: 100%;
  max-width: 100%;
  box-sizing: border-box;
}

.product-detail-container {
  max-width: 1400px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  gap: 30px;
  margin-bottom: 40px;
  width: 100%;
  box-sizing: border-box;
}

.product-images {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.main-image {
  height: 400px;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: var(--shadow);
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #f9f9f9;
}

.main-image img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  transition: transform 0.5s ease;
}

.main-image:hover img {
  transform: scale(1.05);
}

.product-info {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.product-category {
  color: var(--text-light);
  font-size: var(--font-size-sm);
  margin-bottom: 10px;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.product-name {
  font-size: var(--font-size-2xl);
  font-weight: 700;
  color: var(--text-dark);
  margin-bottom: 5px;
  line-height: 1.2;
}

.product-quantity {
  color: var(--primary-color);
  font-size: var(--font-size-lg);
  font-weight: 600;
  margin-bottom: 15px;
  display: flex;
  align-items: center;
  gap: 8px;
  word-wrap: break-word;
  overflow-wrap: break-word;
  word-break: break-word;
  white-space: normal;
  max-width: 100%;
  box-sizing: border-box;
  flex-wrap: wrap;
}

.product-quantity i {
  font-size: 0.9em;
}

.product-description {
  color: var(--text-light);
  line-height: 1.6;
  margin-bottom: 20px;
  display: -webkit-box;
  -webkit-box-orient: vertical;
  overflow: hidden;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  text-overflow: ellipsis;
}

.product-features {
  margin-bottom: 20px;
}

.product-features h3 {
  font-size: var(--font-size-lg);
  margin-bottom: 12px;
  color: var(--text-dark);
}

.feature-list {
  list-style: none;
}

.feature-list li {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
  color: var(--text-light);
}

.feature-list li i {
  color: var(--primary-color);
  margin-right: 10px;
  font-size: 1em;
}

.product-actions {
  display: flex;
  gap: 10px;
  margin-top: auto;
}

/* ===== BUTTONS ===== */
.btn {
  display: inline-block;
  font-weight: 500;
  text-align: center;
  white-space: nowrap;
  vertical-align: middle;
  user-select: none;
  border: 1px solid transparent;
  padding: 0.5rem 1rem;
  font-size: var(--font-size-sm);
  line-height: 1.5;
  border-radius: 0.375rem;
  transition: all 0.15s ease-in-out;
  cursor: pointer;
  text-decoration: none;
  position: relative;
  overflow: hidden;
  flex: 1;
  justify-content: center;
}

.btn:focus {
  outline: 0;
  box-shadow: 0 0 0 0.2rem rgba(255, 123, 0, 0.25);
}

.btn:disabled {
  opacity: 0.65;
  cursor: not-allowed;
}

.btn-whatsapp {
  color: #fff;
  background-color: #25D366;
  border-color: #25D366;
}

.btn-whatsapp:hover {
  color: #fff;
  background-color: #128C7E;
  border-color: #128C7E;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.btn-whatsapp:focus {
  box-shadow: 0 0 0 0.2rem rgba(37, 211, 102, 0.5);
}

.btn-lg {
  padding: 0.75rem 1.5rem;
  font-size: var(--font-size-lg);
  border-radius: 0.5rem;
}

.btn i {
  margin-right: 0.5rem;
}

/* ===== TABS ===== */
.product-tabs {
  margin-top: 40px;
  width: 100%;
}

.tab-nav {
  display: flex;
  border-bottom: 2px solid #eee;
  margin-bottom: 20px;
  overflow-x: auto;
}

.tab-btn {
  background: none;
  border: none;
  padding: 12px 20px;
  font-size: var(--font-size-base);
  font-weight: 600;
  color: var(--text-light);
  cursor: pointer;
  position: relative;
  transition: all 0.3s ease;
  white-space: nowrap;
}

.tab-btn.active {
  color: var(--primary-color);
}

.tab-btn.active::after {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 0;
  width: 100%;
  height: 2px;
  background-color: var(--primary-color);
}

.tab-content {
  display: none;
  animation: fadeIn 0.5s ease;
}

.tab-content.active {
  display: block;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

/* ===== RELATED PRODUCTS ===== */
.related-products {
  padding: 50px 5%;
  background-color: var(--bg-white);
  width: 100%;
  max-width: 100%;
  box-sizing: border-box;
}

.related-products-container {
  max-width: 1400px;
  margin: 0 auto;
  width: 100%;
  box-sizing: border-box;
}
.product-link-wrapper{
   text-decoration : none;
}

.section-title {
  font-size: var(--font-size-2xl);
  font-weight: 700;
  color: var(--text-dark);
  margin-bottom: 30px;
  text-align: center;
  position: relative;
  padding-bottom: 15px;
}

.section-title::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 60px;
  height: 3px;
  background-color: var(--primary-color);
}

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
  font-size: var(--font-size-xs);
  font-weight: 600;
  z-index: 1;
}

.product-image {
  height: 280px;
  overflow: hidden;
  position: relative;
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #f9f9f9;
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
  display: flex;
  flex-direction: column;
  flex: 1;
}

.product-category {
  color: var(--text-light);
  font-size: var(--font-size-sm);
  margin-bottom: 5px;
}

.product-name {
  font-size: var(--font-size-lg);
  font-weight: 600;
  color: var(--text-dark);
  margin-bottom: 10px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  line-height: 1.3;
}

.product-description {
  color: var(--text-light);
  font-size: var(--font-size-sm);
  line-height: 1.4;
  margin-bottom: 15px;
  display: -webkit-box;
  -webkit-box-orient: vertical;
  overflow: hidden;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  text-overflow: ellipsis;
  flex: 1;
}

.product-link {
  color: var(--primary-color);
  font-weight: 600;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  transition: all 0.3s ease;
  margin-top: auto;
  font-size: var(--font-size-sm);
}

.product-link i {
  margin-left: 5px;
  transition: transform 0.3s ease;
}

.product-link:hover i {
  transform: translateX(5px);
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
  font-size: var(--font-size-xl);
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
  font-size: var(--font-size-sm);
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
  font-size: var(--font-size-sm);
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
@media (max-width: 992px) {
  .product-detail-container {
    flex-direction: column;
  }

  .main-image {
    height: 350px;
  }
}

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

  .product-hero h1 {
    font-size: 2.5em;
  }

  .product-hero p {
    font-size: 1.1em;
  }

  .product-name {
    font-size: 1.5em;
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
  
  /* ===== MAIN FIX FOR RELATED PRODUCTS ON MOBILE ===== */
  .product-card .product-name {
    font-size: 0.9em !important; /* Smaller font size for mobile */
    white-space: normal !important;
    overflow: visible !important;
    text-overflow: initial !important;
    display: -webkit-box !important;
    -webkit-line-clamp: 2 !important;
    -webkit-box-orient: vertical !important;
    line-height: 1.3 !important;
    height: auto !important;
    min-height: 2.4em !important;
    margin-bottom: 12px !important;
  }
  
  .product-card .product-description {
    font-size: 0.8em !important; /* Smaller description text */
    line-height: 1.3 !important;
    margin-bottom: 12px !important;
  }
  
  .product-card .product-category {
    font-size: 0.75em !important;
    margin-bottom: 4px !important;
  }
  
  .product-card .product-link {
    font-size: 0.8em !important;
  }
  
  .product-quantity {
    font-size: 0.9em;
    line-height: 1.4;
  }
  
  .product-quantity i {
    flex-shrink: 0;
    margin-bottom: 5px;
  }
}

@media (max-width: 576px) {
  .product-hero {
    height: 40vh;
  }

  .product-hero h1 {
    font-size: 2em;
  }

  .product-detail-section,
  .related-products {
    padding: 40px 15px;
  }

  .breadcrumb {
    padding: 15px 15px;
  }

  .main-image {
    height: 250px;
  }

  .product-actions {
    flex-direction: column;
  }

  .btn {
    width: 100%;
    justify-content: center;
  }

  .products-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
  }

  .tab-nav {
    overflow-x: auto;
  }
  
  /* Further adjustments for smaller mobile */
  .product-card .product-name {
    font-size: 0.85em !important;
    min-height: 2.2em !important;
    margin-bottom: 10px !important;
  }
  
  .product-card .product-description {
    font-size: 0.75em !important;
    -webkit-line-clamp: 2 !important;
  }
  
  .product-card .product-content {
    padding: 12px !important;
  }
  
  .product-image {
    height: 140px !important;
  }
}

/* Very small devices (320px and up) */
@media (max-width: 380px) {
  .products-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
  }

  .product-image {
    height: 120px !important;
  }

  .product-content {
    padding: 10px !important;
  }

  .product-card .product-name {
    font-size: 0.8em !important;
    line-height: 1.2 !important;
    min-height: 2.0em !important;
    margin-bottom: 8px !important;
  }

  .product-card .product-description {
    font-size: 0.7em !important;
    line-height: 1.2 !important;
    -webkit-line-clamp: 2 !important;
  }
  
  .product-card .product-category {
    font-size: 0.7em !important;
  }
  
  .product-card .product-link {
    font-size: 0.75em !important;
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
  
  .product-detail-section, .product-hero, .breadcrumb, header, footer, .related-products {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    padding-left: 15px !important;
    padding-right: 15px !important;
    box-sizing: border-box !important;
  }
  
  .product-detail-container, .related-products-container {
    width: 100% !important;
    max-width: 100% !important;
    padding: 0 !important;
    margin: 0 !important;
    box-sizing: border-box !important;
  }
  
  .products-grid {
    width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
    box-sizing: border-box !important;
  }
  
  .product-card {
    width: 100% !important;
    box-sizing: border-box !important;
  }
}

@media (max-width: 380px) {
  .product-detail-section, .product-hero, .breadcrumb, header, footer, .related-products {
    padding-left: 10px !important;
    padding-right: 10px !important;
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

  <!-- MOBILE MENU OVERLAY -->
  <div class="menu-overlay" id="menuOverlay"></div>
<?php include 'header.php'; ?>

  <!-- HEADER -->
  <header id="header">
    <a href="index.php">
      <img src="assets/companylogo1.jpg" alt="Company Logo">
    </a>
    <nav id="nav">
      <a href="index.php">Home</a>
      <a href="aboutus.php">About</a>
      <a href="products.php" class="active">Products</a>
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
    <a href="product.php">Products</a>
    <span>></span>
    <a href="product.php?category=<?= htmlspecialchars($product['category']) ?>"><?= htmlspecialchars(ucfirst($product['category'])) ?></a>
    <span>></span>
    <span class="current"><?= htmlspecialchars($product['product_name']) ?></span>
  </div>

 

  <!-- PRODUCT DETAIL SECTION -->
  <section class="product-detail-section">
    <div class="product-detail-container">
      <div class="product-images">
        <div class="main-image">
          <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>">
        </div>
      </div>
      <div class="product-info">
        <div class="product-category">
          <?php 
          if ($product['category']=='dry-bhakhri')
          {
            echo("dry-bhakhri & khakhra");

          }
          else{
            htmlspecialchars($product['category']);
          }
         ?></div>
        <h1 class="product-name"><?= htmlspecialchars($product['product_name']) ?></h1>
        <div class="product-quantity">
          <i class="fas fa-box"></i>
         <?= htmlspecialchars($product['packet_sizes']) ?>
        </div>
        
        <!-- PRODUCT TABS -->
    <div class="product-tabs">
      <div class="tab-nav">
        <button class="tab-btn active" data-tab="description">Description</button>
      </div>
      
      <div class="tab-content active" id="description">
        <h3>Product Description</h3>
       <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
      </div>
    </div>
        
             <!-- KEY FEATURES -->
        <div class="product-features">
          <h3><br>Key Features</h3>
          <ul class="feature-list">
            <?php
             $features = explode(";", $product['key_features']);

              foreach ($features as $f) {
                echo '<li><i class="fas fa-check-circle"></i> '.htmlspecialchars($f).'</li>';
              }
            ?>
          </ul>
        </div>
          </ul>
        </div>
        <div class="product-actions">
          <a href="https://wa.me/+919979755356?text=Hi%20Bhoomi%20Trade%20Line%2C%20I'm%20interested%20in%20your%20<?= urlencode($product['product_name']) ?>.%20Category:%20<?= urlencode($product['category']) ?>."
             class="btn btn-whatsapp btn-lg" 
             target="_blank" 
             title="Enquire on WhatsApp">
            <i class="fab fa-whatsapp"></i>
            Enquire on WhatsApp
          </a>
        </div>
      </div>
    </div>
  </section>

 <!-- RELATED PRODUCTS SECTION -->
<section class="related-products">
  <div class="related-products-container">
    <h2 class="section-title">Related Products</h2>

    <div class="products-grid">
      <?php if ($related_result->num_rows > 0): ?>
        <?php while ($r = $related_result->fetch_assoc()): ?>
          <a href="productdetail.php?id=<?= $r['id'] ?>" class="product-link-wrapper">
            <div class="product-card">
              <div class="product-image">
                <img src="<?= htmlspecialchars($r['image_path']) ?>" 
                     alt="<?= htmlspecialchars($r['product_name']) ?>">
              </div>
              <div class="product-content">
                <div class="product-category">
                  <?= htmlspecialchars($r['category']) ?>
                </div>
                <h3 class="product-name">
                  <?= htmlspecialchars($r['product_name']) ?>
                </h3>
                <p class="product-description">
                  <?= htmlspecialchars(substr($r['description'], 0, 80)) ?>...
                </p>
                <div class="product-link">
                  View Details <i class="fas fa-arrow-right"></i>
                </div>
              </div>
            </div>
          </a>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No related products found.</p>
      <?php endif; ?>
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