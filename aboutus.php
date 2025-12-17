<?php
include 'db/db.php'; // include your DB connection file

// Fetch contact info for admin (assuming id=1 or use WHERE username='admin')
 $sql = "SELECT business_address, phone_number, email, business_hours FROM users WHERE id = 1";
 $result = $conn->query($sql);
 $contact = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us | Bhoomi Tradeline</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
  font-weight: 400;
  font-size: 16px;
  line-height: 1.6;
  background-color: var(--bg-light);
  color: var(--text-dark);
  overflow-x: hidden;
  scroll-behavior: smooth;
}

/* ===== HEADER ===== */
header {
  background-color: var(--bg-white);
  color: var(--primary-color);
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 50px;
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: var(--shadow);
  transition: all 0.3s ease;
}

header.scrolled {
  padding: 10px 50px;
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
  font-size: 15px;
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

/* ===== PAGE BANNER ===== */
.page-banner {
  background: linear-gradient(135deg, rgba(255,123,0,0.85), rgba(255,149,0,0.75)), url('https://images.unsplash.com/photo-1586201375761-83865001e31c?auto=format&fit=crop&w=1600&q=80');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  height: 45vh;
  min-height: 350px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  color: white;
  position: relative;
}

.page-banner::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.3);
  z-index: 1;
}

.page-banner h1 {
  font-size: clamp(2rem, 5vw, 3.5rem);
  font-weight: 800;
  text-shadow: 2px 2px 10px rgba(0,0,0,0.3);
  margin-bottom: 20px;
  letter-spacing: -1px;
  position: relative;
  z-index: 2;
}

.breadcrumb {
  font-size: clamp(0.9rem, 2vw, 1.1rem);
  opacity: 0.9;
  position: relative;
  z-index: 2;
}

.breadcrumb a {
  color: white;
  text-decoration: none;
  margin: 0 5px;
  transition: opacity 0.3s;
}

.breadcrumb a:hover {
  opacity: 0.8;
}

/* ===== SECTION TITLES ===== */
.section-title {
  color: var(--text-dark);
  font-size: clamp(1.8rem, 4vw, 2.5rem);
  font-weight: 700;
  margin-bottom: 30px;
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

/* ===== STATS SECTION ===== */
.stats-section {
  padding: 60px 20px;
  background-color: var(--bg-white);
  text-align: center;
}

.stats-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 25px;
  max-width: 1000px;
  margin: 0 auto;
}

.stat-item {
  background: linear-gradient(135deg, var(--bg-light), var(--bg-white));
  border-radius: 20px;
  padding: 30px 20px;
  box-shadow: var(--shadow);
  transition: all 0.4s ease;
  position: relative;
  overflow: hidden;
}

.stat-item::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
}

.stat-item:hover {
  transform: translateY(-10px);
  box-shadow: var(--shadow-hover);
}

.stat-number {
  font-size: clamp(2rem, 5vw, 3rem);
  font-weight: 800;
  background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  margin-bottom: 10px;
}

.stat-label {
  font-size: clamp(0.9rem, 2vw, 1.1rem);
  color: var(--text-dark);
  font-weight: 600;
}

/* ===== ABOUT STORY SECTION ===== */
.about-story {
  padding: 80px 20px;
  background-color: var(--bg-white);
  text-align: center;
}

.story-content {
  max-width: 900px;
  margin: 50px auto 0;
  font-size: clamp(1rem, 2.5vw, 1.1rem);
  line-height: 1.8;
  color: var(--text-dark);
}

.story-content p {
  margin-bottom: 25px;
  text-align: left;
}

/* ===== BRAND SECTION ===== */
.brand-section {
  padding: 80px 20px;
  background-color: var(--bg-light);
  text-align: center;
}

.brand-container {
  display: flex;
  align-items: center;
  justify-content: center;
  max-width: 1200px;
  margin: 50px auto 0;
  gap: 50px;
}

.brand-image {
  flex: 1;
  position: relative;
  max-width: 400px;
}

.brand-image img {
  width: 100%;
  border-radius: 20px;
  box-shadow: var(--shadow);
  transition: transform 0.5s ease;
}

.brand-image:hover img {
  transform: scale(1.03);
}

.brand-image::before {
  content: '';
  position: absolute;
  top: -20px;
  left: -20px;
  right: 20px;
  bottom: 20px;
  border: 3px solid var(--primary-color);
  border-radius: 20px;
  z-index: -1;
}

.brand-text {
  flex: 1;
  text-align: left;
  max-width: 600px;
}

.brand-text h3 {
  color: var(--primary-color);
  font-size: clamp(1.5rem, 4vw, 2rem);
  font-weight: 700;
  margin-bottom: 20px;
}

.brand-text p {
  color: var(--text-dark);
  line-height: 1.8;
  font-size: clamp(0.95rem, 2.5vw, 1.05rem);
  margin-bottom: 20px;
  text-align: left;
}

/* ===== GLOBAL REACH SECTION ===== */
.global-reach {
  padding: 80px 20px;
  background-color: var(--bg-white);
  text-align: center;
}

.reach-container {
  max-width: 1200px;
  margin: 0 auto;
}

.world-map {
  position: relative;
  height: 400px;
  background: url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=1200&q=80') center/cover;
  border-radius: 20px;
  margin-bottom: 50px;
  box-shadow: var(--shadow);
}

.countries-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
  gap: 20px;
  margin-top: 40px;
}

.country-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 18px;
  background: linear-gradient(135deg, var(--bg-light), var(--bg-white));
  border-radius: 15px;
  box-shadow: 0 3px 10px rgba(0,0,0,0.08);
  transition: all 0.3s ease;
  border: 1px solid rgba(255, 123, 0, 0.1);
}

.country-item:hover {
  transform: translateY(-5px);
  box-shadow: var(--shadow);
  border-color: var(--primary-color);
}

.country-flag {
  width: 45px;
  height: 45px;
  border-radius: 50%;
  overflow: hidden;
  box-shadow: 0 3px 8px rgba(0,0,0,0.15);
  border: 2px solid white;
  flex-shrink: 0;
}

.country-flag img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.country-name {
  font-weight: 600;
  color: var(--text-dark);
  font-size: clamp(0.9rem, 2.5vw, 1rem);
}

/* ===== MISSION VISION SECTION ===== */
.mission-vision {
  padding: 80px 20px;
  background-color: var(--bg-light);
  text-align: center;
}

.mission-vision-container {
  display: flex;
  justify-content: space-between;
  max-width: 1000px;
  margin: 50px auto 0;
  gap: 40px;
}

.mission-vision-item {
  flex: 1;
  background: var(--bg-white);
  padding: 40px 25px;
  border-radius: 20px;
  box-shadow: var(--shadow);
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
}

.mission-vision-item::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 4px;
  background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
}

.mission-vision-item:hover {
  transform: translateY(-10px);
  box-shadow: var(--shadow-hover);
}

.mission-vision-icon {
  width: 80px;
  height: 80px;
  background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 0 auto 25px;
  color: white;
  font-size: 2em;
  box-shadow: 0 5px 15px rgba(255, 123, 0, 0.3);
}

.mission-vision-title {
  color: var(--primary-color);
  font-size: clamp(1.3rem, 3.5vw, 1.6rem);
  font-weight: 700;
  margin-bottom: 15px;
}

.mission-vision-description {
  color: var(--text-dark);
  line-height: 1.6;
  font-size: clamp(0.9rem, 2.5vw, 1rem);
}

/* ===== VALUES SECTION ===== */
.values {
  padding: 80px 20px;
  background-color: var(--bg-white);
  text-align: center;
}

.values-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 25px;
  max-width: 1200px;
  margin: 50px auto 0;
}

.value-item {
  background: linear-gradient(135deg, var(--bg-light), var(--bg-white));
  border-radius: 20px;
  padding: 35px 20px;
  box-shadow: var(--shadow);
  transition: all 0.3s ease;
  text-align: center;
  border: 1px solid rgba(255, 123, 0, 0.1);
}

.value-item:hover {
  transform: translateY(-10px);
  box-shadow: var(--shadow-hover);
  border-color: var(--primary-color);
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
  font-size: clamp(1rem, 3vw, 1.2rem);
  font-weight: 700;
  color: var(--primary-color);
  margin-bottom: 12px;
}

.value-description {
  color: var(--text-dark);
  line-height: 1.5;
  font-size: clamp(0.85rem, 2.2vw, 0.95rem);
}

/* ===== QUALITY ASSURANCE SECTION ===== */
.quality-assurance {
  padding: 80px 20px;
  background-color: var(--bg-light);
  text-align: center;
}

.quality-intro {
  max-width: 900px;
  margin: 0 auto 50px;
  font-size: clamp(1rem, 2.5vw, 1.05rem);
  line-height: 1.8;
  color: var(--text-dark);
}

.product-categories {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
  gap: 15px;
  margin-bottom: 60px;
}

.product-category {
  background: linear-gradient(135deg, var(--bg-light), var(--bg-white));
  padding: 18px 25px;
  border-radius: 50px;
  font-weight: 600;
  color: var(--primary-color);
  box-shadow: var(--shadow);
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 10px;
  border: 1px solid rgba(255, 123, 0, 0.1);
  font-size: clamp(0.9rem, 2.2vw, 1rem);
}

.product-category:hover {
  transform: translateY(-5px);
  box-shadow: var(--shadow-hover);
  background: var(--primary-color);
  color: white;
}

.product-category i {
  font-size: clamp(1rem, 2.5vw, 1.2rem);
}

.quality-process {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 30px;
  max-width: 1200px;
  margin: 0 auto;
}

.quality-step {
  background: var(--bg-white);
  border-radius: 20px;
  padding: 35px 25px;
  box-shadow: var(--shadow);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
  border: 1px solid rgba(255, 123, 0, 0.1);
}

.quality-step::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 4px;
  background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
}

.quality-step:hover {
  transform: translateY(-10px);
  box-shadow: var(--shadow-hover);
}

.quality-icon {
  width: 70px;
  height: 70px;
  background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 0 auto 20px;
  color: white;
  font-size: 1.8em;
  box-shadow: 0 5px 15px rgba(255, 123, 0, 0.3);
}

.quality-title {
  color: var(--primary-color);
  font-size: clamp(1.1rem, 3vw, 1.3rem);
  font-weight: 700;
  margin-bottom: 15px;
}

.quality-description {
  color: var(--text-dark);
  line-height: 1.6;
  font-size: clamp(0.85rem, 2.2vw, 0.95rem);
}

.quality-standards {
  margin-top: 60px;
  background: var(--bg-white);
  padding: 40px;
  border-radius: 20px;
  max-width: 1000px;
  margin-left: auto;
  margin-right: auto;
  box-shadow: var(--shadow);
}

.standards-title {
  color: var(--primary-color);
  font-size: clamp(1.2rem, 3.5vw, 1.5rem);
  font-weight: 700;
  margin-bottom: 20px;
}

.standards-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
}

.standard-item {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 18px;
  background: linear-gradient(135deg, var(--bg-light), var(--bg-white));
  border-radius: 15px;
  box-shadow: 0 3px 10px rgba(0,0,0,0.05);
  transition: all 0.3s ease;
  border: 1px solid rgba(255, 123, 0, 0.1);
}

.standard-item:hover {
  transform: translateY(-5px);
  box-shadow: var(--shadow);
}

.standard-icon {
  width: 50px;
  height: 50px;
  background: var(--primary-color);
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  color: white;
  font-size: 1.2em;
  flex-shrink: 0;
}

.standard-text {
  font-weight: 600;
  color: var(--text-dark);
  font-size: clamp(0.9rem, 2.2vw, 1rem);
}

/* ===== CERTIFICATIONS ===== */
.certifications {
  background-color: var(--bg-white);
  padding: 80px 20px;
  text-align: center;
}

.certifications p {
  max-width: 800px;
  margin: 20px auto 50px;
  color: var(--text-dark);
  line-height: 1.7;
  font-size: clamp(1rem, 2.5vw, 1.05rem);
}

.certification-logos {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
  gap: 40px;
  justify-items: center;
}

.certification-item {
  transition: all 0.3s ease;
  padding: 20px;
  background: linear-gradient(135deg, var(--bg-light), var(--bg-white));
  border-radius: 15px;
  box-shadow: var(--shadow);
  border: 1px solid rgba(255, 123, 0, 0.1);
}

.certification-item:hover {
  transform: scale(1.05);
  box-shadow: var(--shadow-hover);
}

.certification-logos img {
  width: 120px;
  height: 120px;
  object-fit: contain;
  transition: transform 0.3s ease;
}

/* ===== FOOTER ===== */
footer {
  background-color: #333;
  color: white;
  padding: 60px 20px 20px;
}

.footer-content {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 30px;
  max-width: 1200px;
  margin: 0 auto 40px;
}

.footer-section {
  margin-bottom: 30px;
}

.footer-section h3 {
  color: var(--primary-color);
  font-size: clamp(1.1rem, 3vw, 1.3rem);
  font-weight: 700;
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
  font-size: clamp(0.9rem, 2.2vw, 1rem);
}

.footer-section ul {
  list-style: none;
}

.footer-section ul li {
  margin-bottom: 12px;
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
  color: var(--primary-color);
  border: 2px solid var(--primary-color);
  padding: 12px 30px;
  border-radius: 50px;
  font-weight: 600;
  font-size: clamp(0.9rem, 2.2vw, 1rem);
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-block;
  text-decoration: none;
  margin-top: 15px;
}

.footer-section a.admin-btn:hover {
  transform: translateY(-3px);
  color: var(--secondary-color);
  box-shadow: 0 5px 15px rgba(255, 123, 0, 0.3);
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
  font-size: clamp(0.8rem, 2vw, 0.9rem);
}

/* ===== BACK TO TOP BUTTON ===== */
.back-to-top {
  position: fixed;
  bottom: 100px;
  right: 20px;
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
  right: 20px;
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
/* Add this media query for laptop view */
@media (min-width: 992px) {
  .countries-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}

@media (max-width: 992px) {
  .mission-vision-container {
    grid-template-columns: 1fr;
    gap: 30px;
  }

  .brand-container {
    flex-direction: column;
    gap: 30px;
  }

  .quality-process {
    grid-template-columns: 1fr;
  }

  .values-container {
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
  }

  .countries-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
  }
}

@media (max-width: 768px) {
  header {
    padding: 10px 20px;
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

  .page-banner {
    height: 35vh;
    min-height: 300px;
  }

  .page-banner h1 {
    font-size: clamp(1.8rem, 6vw, 2.5rem);
  }

  .stats-container {
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
  }

  .world-map {
    height: 250px;
  }

  .footer-content {
    grid-template-columns: 1fr;
    gap: 20px;
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
  .page-banner h1 {
    font-size: clamp(1.5rem, 7vw, 2rem);
  }

  .section-title {
    font-size: clamp(1.5rem, 5vw, 2rem);
    margin-bottom: 25px;
  }

  .stats-container {
    grid-template-columns: 1fr;
    gap: 15px;
  }

  .stat-item {
    padding: 25px 15px;
  }

  .countries-grid {
    grid-template-columns: 1fr;
    gap: 12px;
  }

  .country-item {
    padding: 15px 12px;
    gap: 12px;
  }

  .country-flag {
    width: 35px;
    height: 35px;
  }

  .values-container {
    grid-template-columns: 1fr;
    gap: 15px;
  }

  .value-item {
    padding: 25px 15px;
  }

  .value-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 15px;
    font-size: 1.5em;
  }

  .mission-vision-container {
    gap: 20px;
  }

  .mission-vision-item {
    padding: 30px 20px;
  }

  .mission-vision-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 15px;
    font-size: 1.5em;
  }

  .product-categories {
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
  }

  .product-category {
    padding: 15px 20px;
    font-size: clamp(0.8rem, 2.5vw, 0.9rem);
  }

  .quality-step {
    padding: 25px 20px;
  }

  .quality-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 15px;
    font-size: 1.5em;
  }

  .certification-logos {
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
  }

  .certification-item {
    padding: 15px;
  }

  .certification-logos img {
    width: 80px;
    height: 80px;
  }
}

@media (max-width: 380px) {
  .country-item {
    flex-direction: column;
    text-align: center;
    gap: 8px;
    padding: 12px;
  }

  .country-flag {
    width: 30px;
    height: 30px;
  }

  .country-name {
    font-size: 0.85rem;
  }

  .stat-number {
    font-size: clamp(1.5rem, 6vw, 2rem);
  }

  .stat-label {
    font-size: 0.85rem;
  }

  .value-icon {
    width: 50px;
    height: 50px;
    font-size: 1.3em;
  }

  .mission-vision-icon {
    width: 50px;
    height: 50px;
    font-size: 1.3em;
  }

  .quality-icon {
    width: 50px;
    height: 50px;
    font-size: 1.3em;
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
  <!-- MOBILE MENU OVERLAY -->
  <div class="menu-overlay" id="menuOverlay"></div>
<?php include 'header.php'; ?>

  <!-- HEADER -->
  <header id="header">
    <a href="index.php"><img src="assets/companylogo1.jpg" alt="Company Logo"></a>
    <nav id="nav">
      <a href="index.php">Home</a>
      <a href="about.php">About</a>
      <a href="index.php#products">Products</a>
      <a href="index.php#process">Process</a>
      <a href="index.php#mission-values">Mission & Values</a>
      <a href="index.php#certifications">Certifications</a>
      <a href="index.php#contact">Contact</a>
    </nav>
    <button class="mobile-menu-btn" id="mobileMenuBtn">
      <i class="fas fa-bars"></i>
    </button>
  </header>

  <!-- PAGE BANNER -->
  <section class="page-banner">
    <h1>About Us</h1>
    <div class="breadcrumb">
      <a href="index.php">Home</a> / <span>About Us</span>
    </div>
  </section>

  <!-- STATS SECTION -->
  <section class="stats-section">
    <h3 class="section-title">Our Global Impact</h3>
    <div class="stats-container">
      <div class="stat-item">
        <div class="stat-number">150+</div>
        <div class="stat-label">Premium Products</div>
      </div>
      <div class="stat-item">
        <div class="stat-number">13+</div>
        <div class="stat-label">Product Categories</div>
      </div>
      <div class="stat-item">
        <div class="stat-number">9+</div>
        <div class="stat-label">Export Countries</div>
      </div>
      
    </div>
  </section>

  <!-- ABOUT STORY SECTION -->
  <section class="about-story">
    <h3 class="section-title">Our Story</h3>
    <div class="story-content">
      <p>Bhoomi Tradeline is a new venture born from a passion for authentic Indian flavors and a vision to share the rich culinary heritage of India with the world. Founded by food enthusiasts who believe in the power of traditional recipes and quality ingredients, we're embarking on an exciting journey to bring the finest Indian snacks to global markets.</p>
      <p>While we may be new to the export business, our team brings together decades of combined experience in food production, quality control, and international trade. We understand the importance of maintaining authentic taste profiles while meeting international quality standards.</p>
      <p>Our commitment to excellence drives us to source the finest ingredients, follow time-tested recipes, and implement rigorous quality control measures. We're not just building a business; we're creating a bridge between Indian culinary traditions and global food enthusiasts.</p>
    </div>
  </section>

  <!-- BRAND SECTION -->
  <section class="brand-section">
    <h3 class="section-title">Our Flagship Brand - The Kesari</h3>
    <div class="brand-container">
      <div class="brand-image">
        <img src="assets/brand-logo.jpg" alt="The Kesari Product">
      </div>
      <div class="brand-text">
        <h3>The Kesari - A taste of it's own</h3>
        <p>Our flagship brand, "The Kesari," embodies the essence of Indian snacking traditions. Inspired by the royal kitchens of India, The Kesari brings you authentic namkeen that captures the perfect balance of spices, crunch, and flavor.</p>
        <p>Each product under The Kesari brand is carefully crafted using traditional recipes passed down through generations. Our signature Chaat Chevdo is just the beginning of our journey to introduce the world to the diverse and delightful world of Indian snacks.</p>
        <p>The vibrant yellow, black, and orange packaging reflects the energy and richness of Indian culture, while the quality seals inside ensure that every bite delivers the authentic taste of India, no matter where in the world you are.</p>
      </div>
    </div>
  </section>

  <!-- GLOBAL REACH SECTION -->
  <section class="global-reach">
    <h3 class="section-title">Our Global Reach</h3>
    <div class="reach-container">
      <div class="world-map"></div>
      <div class="countries-grid">
        <div class="country-item">
          <div class="country-flag">
            <img src="https://flagcdn.com/w40/au.png" alt="Australia">
          </div>
          <div class="country-name">Australia</div>
        </div>
        <div class="country-item">
          <div class="country-flag">
            <img src="https://flagcdn.com/w40/nz.png" alt="New Zealand">
          </div>
          <div class="country-name">New Zealand</div>
        </div>
        <div class="country-item">
          <div class="country-flag">
            <img src="https://flagcdn.com/w40/ca.png" alt="Canada">
          </div>
          <div class="country-name">Canada</div>
        </div>
        <div class="country-item">
          <div class="country-flag">
            <img src="https://flagcdn.com/w40/ae.png" alt="Dubai">
          </div>
          <div class="country-name">Dubai</div>
        </div>
        <div class="country-item">
          <div class="country-flag">
            <img src="https://flagcdn.com/w40/bh.png" alt="Bahrain">
          </div>
          <div class="country-name">Bahrain</div>
        </div>
        <div class="country-item">
          <div class="country-flag">
            <img src="https://flagcdn.com/w40/za.png" alt="South Africa">
          </div>
          <div class="country-name">South Africa</div>
        </div>
        <div class="country-item">
          <div class="country-flag">
            <img src="https://flagcdn.com/w40/sg.png" alt="Singapore">
          </div>
          <div class="country-name">Singapore</div>
        </div>
        <div class="country-item">
          <div class="country-flag">
            <img src="https://flagcdn.com/w40/us.png" alt="USA">
          </div>
          <div class="country-name">USA</div>
        </div>
      </div>
    </div>
  </section>

  <!-- MISSION VISION SECTION -->
  <section class="mission-vision">
    <h3 class="section-title">Mission & Vision</h3>
    <div class="mission-vision-container">
      <div class="mission-vision-item">
        <div class="mission-vision-icon">
          <i class="fas fa-bullseye"></i>
        </div>
        <h4 class="mission-vision-title">Our Mission</h4>
        <p class="mission-vision-description">To deliver authentic Indian flavors to the world, crafted with tradition, quality, and care. We strive to become a trusted name in the global food market by consistently providing products that exceed customer expectations.</p>
      </div>
      <div class="mission-vision-item">
        <div class="mission-vision-icon">
          <i class="fas fa-eye"></i>
        </div>
        <h4 class="mission-vision-title">Our Vision</h4>
        <p class="mission-vision-description">To be the global ambassador of Indian culinary heritage, making authentic Indian snacks a beloved part of every culture worldwide. We aim to grow from a new venture to a recognized leader in the Indian snack export industry.</p>
      </div>
    </div>
  </section>

  <!-- VALUES SECTION -->
  <section class="values">
    <h3 class="section-title">Our Core Values</h3>
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

  <!-- QUALITY ASSURANCE SECTION -->
  <section class="quality-assurance">
    <h3 class="section-title">Quality Assurance</h3>
    <div class="quality-intro">
      <p>At Bhoomi Tradeline, quality is not just a promise – it's the foundation of everything we do. We bring the authentic taste of India to the world through our diverse range of 150+ products across 13+ categories, ensuring each item meets the highest standards of safety, freshness, and flavor.</p>
    </div>
    
    <div class="product-categories">
  <div class="product-category">
    <i class="fas fa-cookie-bite"></i>
    <span>Namkeen</span>
  </div>
  <div class="product-category">
    <i class="fas fa-mortar-pestle"></i>
    <span>Spices Powder</span>
  </div>
  <div class="product-category">
    <i class="fas fa-seedling"></i>
    <span>Whole Spices</span>
  </div>
  <div class="product-category">
    <i class="fas fa-pepper-hot"></i>
    <span>Cooking Paste & Chutney</span>
  </div>
  <div class="product-category">
    <i class="fas fa-jar"></i>
    <span>Sauces</span>
  </div>
  <div class="product-category">
    <i class="fas fa-vial"></i>
    <span>Pickles</span>
  </div>
  <div class="product-category">
    <i class="fas fa-apple-alt"></i>
    <span>Jam</span>
  </div>
  <div class="product-category">
    <i class="fas fa-utensils"></i>
    <span>Ready to Eat</span>
  </div>
  <div class="product-category">
    <i class="fas fa-bread-slice"></i>
    <span>Dry Bhakhri & Khakhra</span>
  </div>
  <div class="product-category">
    <i class="fas fa-circle-notch"></i>
    <span>Khakhra</span>
  </div>
  <div class="product-category">
    <i class="fas fa-cookie"></i>
    <span>Dry Kachori</span>
  </div>
  <div class="product-category">
    <i class="fas fa-coffee"></i>
    <span>Beverages</span>
  </div>
  <div class="product-category">
    <i class="fas fa-candy-cane"></i>
    <span>Indian Sweets</span>
  </div>
  <div class="product-category">
    <i class="fas fa-tint"></i>
    <span>A2 Cow Ghee</span>
  </div>
</div>

    <div class="quality-process">
      <div class="quality-step">
        <div class="quality-icon">
          <i class="fas fa-search"></i>
        </div>
        <h4 class="quality-title">Ingredient Sourcing</h4>
        <p class="quality-description">We meticulously source the finest ingredients from trusted suppliers across India. Each ingredient undergoes strict quality checks before entering our production facility, ensuring only the best reaches our customers.</p>
      </div>
      <div class="quality-step">
        <div class="quality-icon">
          <i class="fas fa-microscope"></i>
        </div>
        <h4 class="quality-title">Laboratory Testing</h4>
        <p class="quality-description">Our in-house laboratory conducts comprehensive testing at every stage – from raw materials to finished products. We test for purity, freshness, and adherence to international food safety standards.</p>
      </div>
      <div class="quality-step">
        <div class="quality-icon">
          <i class="fas fa-shield-alt"></i>
        </div>
        <h4 class="quality-title">Production Standards</h4>
        <p class="quality-description">Our production facility follows GMP (Good Manufacturing Practices) with strict hygiene protocols. Each product category has specialized handling procedures to maintain authenticity and quality.</p>
      </div>
      <div class="quality-step">
        <div class="quality-icon">
          <i class="fas fa-box"></i>
        </div>
        <h4 class="quality-title">Packaging & Storage</h4>
        <p class="quality-description">We use food-grade packaging materials that preserve freshness and extend shelf life. Our climate-controlled storage facilities maintain optimal conditions for different product categories until shipment.</p>
      </div>
    </div>

    <div class="quality-standards">
      <h4 class="standards-title">Our Quality Standards</h4>
      <div class="standards-grid">
        <div class="standard-item">
          <div class="standard-icon">
            <i class="fas fa-check"></i>
          </div>
          <div class="standard-text">FSSAI Compliant</div>
        </div>
        <div class="standard-item">
          <div class="standard-icon">
            <i class="fas fa-check"></i>
          </div>
          <div class="standard-text">ISO Standards</div>
        </div>
      </div>
    </div>
  </section>

  <!-- CERTIFICATIONS SECTION -->
  <section class="certifications">
    <h3 class="section-title">Quality & Certifications</h3>
    <p>As a new company, we are in the process of obtaining international certifications. Our production processes are designed to meet global food safety standards, and we're committed to achieving certifications including ISO, FSSAI, HACCP, and BRC to ensure our products meet the highest quality benchmarks.</p>
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

  <!-- FOOTER -->
  <footer>
    <div class="footer-content">
      <div class="footer-section">
        <img src="assets/footercompanylogo1.jpg" alt="Company Logo" style="height: 140px;width:210px; margin-bottom: 20px;">
        <p>Bhoomi Tradeline is a new venture dedicated to bringing authentic Indian flavors to the world. Through our flagship brand "The Kesari" and carefully selected partner brands, we're committed to delivering quality Indian snacks globally.</p>
        <div class="social-links">
          <a href="https://www.facebook.com/thekesrinamkeen" class="social-link">
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