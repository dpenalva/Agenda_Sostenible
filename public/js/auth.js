document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    loginForm?.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const username = document.getElementById('loginUsername').value;
        const password = document.getElementById('loginPassword').value;

        try {
            const response = await fetch('?r=api/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    username, 
                    password 
                })
            });

            const responseData = await response.text();
            console.log('Respuesta del servidor:', responseData);

            try {
                const data = JSON.parse(responseData);
                if (data.success) {
                    window.location.href = data.redirect || '/';
                } else {
                    alert(data.message || 'Error al iniciar sesión');
                }
            } catch (e) {
                console.error('Error al parsear JSON:', e);
                alert('Error en la respuesta del servidor');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al intentar iniciar sesión');
        }
    });

    registerForm?.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const username = document.getElementById('registerUsername').value;
        const nombre = document.getElementById('registerNombre').value;
        const apellidos = document.getElementById('registerApellidos').value;
        const email = document.getElementById('registerEmail').value;
        const password = document.getElementById('registerPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        if (!username || !nombre || !apellidos || !email || !password || !confirmPassword) {
            alert('Por favor, completa todos los campos');
            return;
        }

        if (password !== confirmPassword) {
            alert('Las contraseñas no coinciden');
            return;
        }

        try {
            const response = await fetch('?r=api/auth/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    username, 
                    nombre,
                    apellidos,
                    email, 
                    password 
                })
            });

            const data = await response.json();
            
            if (data.success) {
                alert('Registro exitoso. Por favor, inicia sesión.');
                window.location.href = '?r=login';
            } else {
                alert(data.message || 'Error al registrarse');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al intentar registrarse');
        }
    });
}); 