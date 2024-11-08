document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('form[action="?r=login"]');
    
    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const formData = new FormData(loginForm);
                const response = await fetch('?r=login', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    const errorDiv = document.querySelector('.alert-danger') || document.createElement('div');
                    errorDiv.className = 'alert alert-danger';
                    errorDiv.textContent = data.message;
                    loginForm.insertBefore(errorDiv, loginForm.querySelector('button'));
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    }
}); 