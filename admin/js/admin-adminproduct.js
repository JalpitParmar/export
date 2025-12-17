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
    
    // // PRODUCT CARD CLICK
    // const productCards = document.querySelectorAll('.product-card');
    // const productModal = document.getElementById('productModal');
    // const closeModal = document.getElementById('closeModal');
    // const cancelBtn = document.getElementById('cancelBtn');
    
    // productCards.forEach(card => {
    //   card.addEventListener('click', function(e) {
    //     // Don't open modal if clicking on action buttons
    //     if (!e.target.closest('.product-actions')) {
    //       // Get product details
    //       const productImage = this.querySelector('.product-image').src;
    //       const productName = this.querySelector('.product-name').textContent;
    //       const productCategory = this.querySelector('.product-category').textContent;
    //       const productDescription = this.querySelector('.product-description').textContent;
    //       const productPrice = this.querySelector('.product-price').textContent;
          
    //       // Update modal content
    //       document.getElementById('modalProductImage').src = productImage;
    //       document.getElementById('modalProductName').textContent = productName;
    //       document.getElementById('modalProductCategory').textContent = productCategory;
    //       document.getElementById('modalProductDescription').textContent = productDescription;
    //       document.getElementById('modalProductPrice').textContent = productPrice;
          
    //       // Open modal
    //       productModal.classList.add('active');
    //     }
    //   });
    // });
    
    // // Close modal
    // closeModal.addEventListener('click', function() {
    //   productModal.classList.remove('active');
    // });
    
    // cancelBtn.addEventListener('click', function() {
    //   productModal.classList.remove('active');
    // });
    
    // // Close modal when clicking outside
    // window.addEventListener('click', function(event) {
    //   if (event.target === productModal) {
    //     productModal.classList.remove('active');
    //   }
    // });
    
    // // EDIT BUTTONS
    // const editButtons = document.querySelectorAll('.action-btn.edit');
    
    // editButtons.forEach(button => {
    //   button.addEventListener('click', function(e) {
    //     e.stopPropagation(); // Prevent card click event
    //     // Here you would normally open an edit form
    //     alert('Edit functionality would be implemented here');
    //   });
    // });
    
    // // DELETE BUTTONS
    // const deleteButtons = document.querySelectorAll('.action-btn.delete');
    
    // deleteButtons.forEach(button => {
    //   button.addEventListener('click', function(e) {
    //     e.stopPropagation(); // Prevent card click event
    //     // Here you would normally show a confirmation dialog
    //     if (confirm('Are you sure you want to delete this product?')) {
    //       alert('Product deleted successfully!');
    //       // In a real application, you would remove the card from the grid
    //     }
    //   });
    // });
    
    // // PAGINATION
    // const paginationBtns = document.querySelectorAll('.pagination-btn');
    
    // paginationBtns.forEach(btn => {
    //   btn.addEventListener('click', function() {
    //     // Remove active class from all buttons
    //     paginationBtns.forEach(b => b.classList.remove('active'));
        
    //     // Add active class to clicked button (if not a chevron)
    //     if (!this.querySelector('.fa-chevron-left') && !this.querySelector('.fa-chevron-right')) {
    //       this.classList.add('active');
    //     }
        
    //     // In a real application, you would load the corresponding page
    //   });
    // });
    
    // Handle window resize
    window.addEventListener('resize', function() {
      if (window.innerWidth > 768 && sidebar.classList.contains('active')) {
        closeMobileMenu();
      }
    });