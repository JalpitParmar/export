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
    // FORM SUBMISSION HANDLING
const settingsForm = document.getElementById('settingsForm');
const successMessage = document.getElementById('successMessage');

settingsForm.addEventListener('submit', function(e) {
  // Let the form submit normally, then show the success message after page reload
  // The PHP message will be shown after the page reloads
});

// Check if there's a PHP success message and show it
document.addEventListener('DOMContentLoaded', function() {
  const phpMessage = document.querySelector('.success-message');
  if (phpMessage) {
    phpMessage.classList.add('show');
    
    // Hide success message after 5 seconds
    setTimeout(function() {
      phpMessage.classList.remove('show');
    }, 5000);
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
    const settingsTabs = document.querySelectorAll('.settings-tab');
    const settingsPanels = document.querySelectorAll('.settings-panel');
    
    settingsTabs.forEach(tab => {
      tab.addEventListener('click', function() {
        const targetTab = this.getAttribute('data-tab');
        
        // Remove active class from all tabs and panels
        settingsTabs.forEach(t => t.classList.remove('active'));
        settingsPanels.forEach(p => p.classList.remove('active'));
        
        // Add active class to clicked tab and corresponding panel
        this.classList.add('active');
        document.getElementById(`${targetTab}-panel`).classList.add('active');
      });
    });
    
    // SAVE SETTINGS FUNCTIONALITY
    const saveSettingsBtn = document.getElementById('saveSettings');
    const successMessage = document.getElementById('successMessage');
    
    saveSettingsBtn.addEventListener('click', function() {
      // Show success message
      successMessage.classList.add('show');
      
      // Hide success message after 3 seconds
      setTimeout(function() {
        successMessage.classList.remove('show');
      }, 3000);
      
      // In a real application, you would save the settings to a server here
      console.log('Settings saved!');
    });
    
    // RESET SETTINGS FUNCTIONALITY
    const resetSettingsBtn = document.getElementById('resetSettings');
    
    resetSettingsBtn.addEventListener('click', function() {
      if (confirm('Are you sure you want to reset all settings to default values?')) {
        // Reset form values to defaults
        document.getElementById('businessAddress').value = '123 Export Street, Mumbai, India 400001';
        document.getElementById('primaryPhone').value = '+91 98765 43210';
        document.getElementById('generalEmail').value = 'info@globaltasteexports.com';
        document.getElementById('exportEmail').value = 'export@globaltasteexports.com';
        
        // Show success message
        successMessage.classList.add('show');
        setTimeout(function() {
          successMessage.classList.remove('show');
        }, 3000);
      }
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
      if (window.innerWidth > 768 && sidebar.classList.contains('active')) {
        closeMobileMenu();
      }
    });