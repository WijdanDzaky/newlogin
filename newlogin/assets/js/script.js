function toggleForms() {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    
    loginForm.style.display = loginForm.style.display === 'none' ? 'block' : 'none';
    registerForm.style.display = registerForm.style.display === 'none' ? 'block' : 'none';
    
    // Clear messages
    document.getElementById('login-message').innerHTML = '';
    document.getElementById('register-message').innerHTML = '';
    
    // Clear form fields
    document.getElementById('loginForm').reset();
    document.getElementById('registerForm').reset();
}

// Show login form by default
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('login-form').style.display = 'block';
    
    // Handle login form submission
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        submitForm('login');
    });
    
    // Handle registration form submission
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        submitForm('register');
    });
});

function submitForm(formType) {
    const messageElement = formType === 'login' 
        ? document.getElementById('login-message') 
        : document.getElementById('register-message');
    
    const formElement = formType === 'login'
        ? document.getElementById('loginForm')
        : document.getElementById('registerForm');
    
    const formData = new FormData(formElement);
    const url = formType === 'login'
        ? 'includes/login.php'
        : 'includes/register_simple.php';
    
    // Show loading state
    messageElement.innerHTML = '<div class="message"><p>Processing...</p></div>';
    messageElement.style.display = 'block';
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            messageElement.innerHTML = '<div class="message success">' + data.message + '</div>';
            messageElement.style.display = 'block';
            
            // Clear form
            formElement.reset();
            
            // Redirect to dashboard or login page after success
            const redirectUrl = formType === 'login' 
                ? (data.redirect || 'dashboard.php')
                : (data.redirect || 'verify_otp.php');
            
            console.log('Redirecting to:', redirectUrl);
            
            setTimeout(() => {
                window.location.href = redirectUrl;
            }, 1500);
        } else {
            let errorHTML = '<div class="message error">';
            if (data.errors && Array.isArray(data.errors)) {
                errorHTML += '<ul>';
                data.errors.forEach(error => {
                    errorHTML += '<li>' + error + '</li>';
                });
                errorHTML += '</ul>';
            } else {
                errorHTML += '<p>' + data.message + '</p>';
            }
            errorHTML += '</div>';
            messageElement.innerHTML = errorHTML;
            messageElement.style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        messageElement.innerHTML = '<div class="message error"><p>Error: ' + error.message + '</p></div>';
        messageElement.style.display = 'block';
    });
}


