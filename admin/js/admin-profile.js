// MOBILE MENU TOGGLE
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
    
    // TAB FUNCTIONALITY
    const profileTabs = document.querySelectorAll('.profile-tab');
    const profilePanels = document.querySelectorAll('.profile-panel');
    
    profileTabs.forEach(tab => {
      tab.addEventListener('click', function() {
        const targetTab = this.getAttribute('data-tab');
        
        // Remove active class from all tabs and panels
        profileTabs.forEach(t => t.classList.remove('active'));
        profilePanels.forEach(p => p.classList.remove('active'));
        
        // Add active class to clicked tab and corresponding panel
        this.classList.add('active');
        document.getElementById(`${targetTab}-panel`).classList.add('active');
      });
    });
    
    // SAVE PROFILE FUNCTIONALITY
    const saveProfileBtn = document.getElementById('saveProfile');
    const successMessage = document.getElementById('successMessage');
    
    saveProfileBtn.addEventListener('click', function() {
      // Validate passwords if they're being changed
      const newPassword = document.getElementById('newPassword').value;
      const confirmPassword = document.getElementById('confirmPassword').value;
      
      if (newPassword && newPassword !== confirmPassword) {
        alert('Passwords do not match!');
        return;
      }
      
      // Update profile header name if fullName field is changed
      const fullName = document.getElementById('fullName').value;
      document.querySelector('.profile-info h2').textContent = fullName;
      
      // Show success message
      successMessage.classList.add('show');
      
      // Hide success message after 3 seconds
      setTimeout(function() {
        successMessage.classList.remove('show');
      }, 3000);
      
      // In a real application, you would save the profile to a server here
      console.log('Profile saved!');
    });
    
    // CANCEL CHANGES FUNCTIONALITY
    const cancelChangesBtn = document.getElementById('cancelChanges');
    
    cancelChangesBtn.addEventListener('click', function() {
      if (confirm('Are you sure you want to discard your changes?')) {
        // In a real application, you would reload the page or reset the form
        location.reload();
      }
    });
    
    // CHANGE AVATAR FUNCTIONALITY
    const changeAvatarBtn = document.querySelector('.change-avatar-btn');
    
    changeAvatarBtn.addEventListener('click', function() {
      // In a real application, you would open a file picker here
      alert('Avatar upload functionality would be implemented here');
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
      if (window.innerWidth > 768 && sidebar.classList.contains('active')) {
        closeMobileMenu();
      }
    });