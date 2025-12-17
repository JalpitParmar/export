// LOADING SCREEN
window.addEventListener('load', function() {
  // You can adjust the timeout as needed for your backend loading
  setTimeout(function() {
    const loaderWrapper = document.getElementById('loaderWrapper');
    loaderWrapper.style.opacity = '0';
    loaderWrapper.style.visibility = 'hidden';
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

// FADE IN ANIMATION
const fadeElements = document.querySelectorAll('.fade-in');

function checkFade() {
  fadeElements.forEach(element => {
    const elementTop = element.getBoundingClientRect().top;
    const elementVisible = 150;

    if (elementTop < window.innerHeight - elementVisible) {
      element.classList.add('active');
    }
  });
}

window.addEventListener('scroll', checkFade);
window.addEventListener('load', checkFade);