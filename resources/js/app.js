
// resources/js/app.js
import './bootstrap';

document.addEventListener('DOMContentLoaded', function() {
    // CSRF Token Setup
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Modal elements
    const loginModal = document.getElementById('loginModal');
    const registerModal = document.getElementById('registerModal');
    
    // Registration steps
    const registerStep1 = document.getElementById('registerStep1');
    const registerStep2 = document.getElementById('registerStep2');
    const registerStep3 = document.getElementById('registerStep3');
    
    // Buttons
    const loginBtn = document.getElementById('loginBtn');
    const registerBtn = document.getElementById('registerBtn');
    const switchToRegisterBtn = document.getElementById('switchToRegister');
    const switchToLoginBtn = document.getElementById('switchToLogin');
    const closeModalBtns = document.querySelectorAll('.closeModal');
    const resendOtpBtn = document.getElementById('resendOtp');
    
    // Forms
    const loginForm = document.getElementById('loginForm');
    const emailVerificationForm = document.getElementById('emailVerificationForm');
    const otpVerificationForm = document.getElementById('otpVerificationForm');
    const registrationForm = document.getElementById('registrationForm');
    
    // Message containers
    const loginMessage = document.getElementById('loginMessage');
    const registerMessage = document.getElementById('registerMessage');
    
    // Helper functions
    function showModal(modal) {
        modal.classList.remove('hidden');
    }
    
    function hideModal(modal) {
        modal.classList.add('hidden');
    }
    
    function showMessage(container, message, isError = false) {
        container.textContent = message;
        container.classList.remove('hidden');
        container.classList.remove('text-green-600', 'text-red-600');
        container.classList.add(isError ? 'text-red-600' : 'text-green-600');
        
        // Auto-hide message after 5 seconds
        setTimeout(() => {
            container.classList.add('hidden');
        }, 5000);
    }
    
    // Open login modal
    loginBtn.addEventListener('click', function() {
        showModal(loginModal);
    });
    
    // Open register modal
    registerBtn.addEventListener('click', function() {
        showModal(registerModal);
    });
    
    // Switch between modals
    switchToRegisterBtn.addEventListener('click', function() {
        hideModal(loginModal);
        showModal(registerModal);
        
        // Reset registration steps
        registerStep1.classList.remove('hidden');
        registerStep2.classList.add('hidden');
        registerStep3.classList.add('hidden');
    });
    
    switchToLoginBtn.addEventListener('click', function() {
        hideModal(registerModal);
        showModal(loginModal);
    });
    
    // Close modals
    closeModalBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            hideModal(loginModal);
            hideModal(registerModal);
        });
    });
    
    // Handle login form submission
    loginForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(loginForm);
        
        try {
            const response = await fetch('/login', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (response.ok) {
                showMessage(loginMessage, data.message);
                
                // Redirect to dashboard if redirect URL is provided
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    // Fallback: reload the page
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            } else {
                showMessage(loginMessage, data.message, true);
            }
        } catch (error) {
            showMessage(loginMessage, 'An error occurred. Please try again.', true);
        }
    });
    
    
    // Handle email verification form submission
    emailVerificationForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const email = document.getElementById('registerEmail').value;
        const formData = new FormData();
        formData.append('email', email);
        formData.append('for_registration', true);
        
        try {
            const response = await fetch('/send-otp', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (response.ok) {
                // Store email for next steps
                document.getElementById('otpEmail').value = email;
                document.getElementById('regEmail').value = email;
                
                // Move to step 2
                registerStep1.classList.add('hidden');
                registerStep2.classList.remove('hidden');
                
                showMessage(registerMessage, data.message);
            } else {
                showMessage(registerMessage, data.message, true);
            }
        } catch (error) {
            showMessage(registerMessage, 'An error occurred. Please try again.', true);
        }
    });
    
    // Handle OTP verification form submission
    otpVerificationForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(otpVerificationForm);
        
        try {
            const response = await fetch('/verify-otp', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (response.ok) {
                // Move to step 3
                registerStep2.classList.add('hidden');
                registerStep3.classList.remove('hidden');
                
                showMessage(registerMessage, data.message);
            } else {
                showMessage(registerMessage, data.message, true);
            }
        } catch (error) {
            showMessage(registerMessage, 'An error occurred. Please try again.', true);
        }
    });
    
    // Handle registration form submission
    registrationForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(registrationForm);
        
        try {
            const response = await fetch('/register', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (response.ok) {
                showMessage(registerMessage, data.message);
                
                // Redirect to dashboard if redirect URL is provided
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    // Fallback: reload the page
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            } else {
                showMessage(registerMessage, data.message, true);
            }
        } catch (error) {
            showMessage(registerMessage, 'An error occurred. Please try again.', true);
        }
    });
    
    // Handle resend OTP button
    resendOtpBtn.addEventListener('click', async function() {
        const email = document.getElementById('otpEmail').value;
        const formData = new FormData();
        formData.append('email', email);
        formData.append('for_registration', true);
        
        try {
            const response = await fetch('/send-otp', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (response.ok) {
                showMessage(registerMessage, 'New OTP sent successfully');
            } else {
                showMessage(registerMessage, data.message, true);
            }
        } catch (error) {
            showMessage(registerMessage, 'An error occurred. Please try again.', true);
        }
    });
});