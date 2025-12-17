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
    
    // // LOGOUT FUNCTIONALITY
    // const logoutBtn = document.getElementById('logoutBtn');
    
    // logoutBtn.addEventListener('click', function(e) {
    //   e.preventDefault();
    //   if (confirm('Are you sure you want to logout?')) {
    //     // In a real application, you would handle the logout process here
    //     // For this demo, we'll just show an alert
    //     alert('You have been logged out successfully!');
    //     // Redirect to login page or home page
    //     // window.location.href = 'login.html';
    //   }
    // });
    
    // IMAGE UPLOAD
    const imageUploadBox = document.getElementById('imageUploadBox');
    const uploadedImages = document.getElementById('uploadedImages');
    let imageCount = 0;
    
    imageUploadBox.addEventListener('click', function() {
      // In a real application, this would open a file picker
      // For this demo, we'll simulate image upload with random images
      if (imageCount < 5) { // Limit to 5 images
        const randomSeed = Math.random().toString(36).substring(7);
        const imageUrl = `https://picsum.photos/seed/${randomSeed}/300/300.jpg`;
        
        const imageContainer = document.createElement('div');
        imageContainer.className = 'uploaded-image';
        
        const img = document.createElement('img');
        img.src = imageUrl;
        img.alt = 'Product Image';
        
        const removeBtn = document.createElement('div');
        removeBtn.className = 'remove-image';
        removeBtn.innerHTML = '<i class="fas fa-times"></i>';
        removeBtn.addEventListener('click', function() {
          imageContainer.remove();
          imageCount--;
        });
        
        imageContainer.appendChild(img);
        imageContainer.appendChild(removeBtn);
        uploadedImages.appendChild(imageContainer);
        
        imageCount++;
      } else {
        alert('Maximum 5 images allowed');
      }
    });
    
    // SAVE PRODUCT
    const saveProductBtn = document.getElementById('saveProductBtn');
    const productForm = document.getElementById('productForm');
    
    saveProductBtn.addEventListener('click', function() {
      if (productForm.checkValidity()) {
        // In a real application, you would send the form data to a server
        // For this demo, we'll just show a success message
        alert('Product saved successfully!');
        
        // In a real application, you might redirect to the products page
        // window.location.href = 'products.html';
      } else {
        // If the form is invalid, trigger the browser's validation UI
        productForm.reportValidity();
      }
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
      if (window.innerWidth > 768 && sidebar.classList.contains('active')) {
        closeMobileMenu();
      }
    });