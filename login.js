
    // LOADING SCREEN
    window.addEventListener('load', function() {
      setTimeout(function() {
        document.querySelector('.loader-wrapper').style.opacity = '0';
        document.querySelector('.loader-wrapper').style.visibility = 'hidden';
      }, 1000);
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

    // // LOGIN FORM
    // document.getElementById('loginForm').addEventListener('submit', function(e) {
    //   e.preventDefault();
      
    //   // Get form values
    //   const username = document.getElementById('username').value;
    //   const password = document.getElementById('password').value;
      
    //   // Hide any previous messages
    //   document.getElementById('errorMessage').style.display = 'none';
    //   document.getElementById('successMessage').style.display = 'none';
      
    //   // Simple validation (in a real app, this would be server-side)
    //   if (username === 'admin' && password === 'admin123') {
    //     // Show success message
    //     document.getElementById('successMessage').style.display = 'flex';
        
    //     // Simulate redirect after successful login
        // setTimeout(function() {
        //   // In a real application, redirect to admin dashboard
        //   alert('Login successful! This would redirect to the admin dashboard.');
        //   window.location.href = 'admin/dashboard.php';
        // }, 2000);
    //   } else {
    //     // Show error message
    //     document.getElementById('errorMessage').style.display = 'flex';
        
    //     // Log failed login attempt (in a real app, this would be server-side)
    //     console.log('Failed login attempt:', {
    //       username: username,
    //       timestamp: new Date().toISOString(),
    //       userAgent: navigator.userAgent
    //     });
    //   }  
    // });

    // // FORGOT PASSWORD LINK
    // document.querySelector('.forgot-password').addEventListener('click', function(e) {
    //   e.preventDefault();
    //   alert('Password reset functionality would be implemented here. In a real application, this would send a reset link to your email.');
    // });

    // PREVENT RIGHT-CLICK ON LOGIN FORM (additional security measure)
    document.querySelector('.login-form-container').addEventListener('contextmenu', function(e) {
      e.preventDefault();
      return false;
    });