// Obtener todos los ítems con clase "submenu"
const submenuItems = document.querySelectorAll('.submenu');

// Añadir un evento de clic a cada ítem principal con submenú
submenuItems.forEach(item => {
    const mainLink = item.querySelector('a');
    
    mainLink.addEventListener('click', function(event) {
        // Prevenir que el enlace realice su acción por defecto
        event.preventDefault();
        
        // Alternar la clase "active" para mostrar/ocultar el submenú
        item.classList.toggle('active');
    });
});

// Función para manejar el cierre de sesión
const logoutIcon = document.getElementById("logout");
if (logoutIcon) {
    logoutIcon.addEventListener('click', (event) => {
        // Evitar que el enlace haga su acción por defecto
        event.preventDefault();
        
        // Eliminar el valor de 'isLoggedIn' del localStorage
        localStorage.removeItem('isLoggedIn');
        
        // Redirigir a la página de inicio de sesión
        window.location.href = "index.html";
    });
}

// Definir las credenciales correctas
const correctEmail = "admin@filmho.com";
const correctPassword = "123456789";

// Obtener los elementos del DOM para el formulario de login
const loginForm = document.getElementById("login-form");
const emailInput = document.getElementById("email");
const passwordInput = document.getElementById("password");
const errorMessage = document.getElementById("error-message");

// Verificar si el usuario ya está autenticado
if (localStorage.getItem('isLoggedIn') === 'true') {
    // Si el usuario ya está autenticado, redirigir al panel
    window.location.href = "admin.php";
}

// Verificar las credenciales de inicio de sesión
if (loginForm) {
    loginForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Evitar el envío por defecto del formulario

        // Obtener los valores ingresados
        const email = emailInput.value.trim();
        const password = passwordInput.value.trim();

        // Verificar las credenciales
        if (email === correctEmail && password === correctPassword) {
            // Si las credenciales son correctas, guardar el estado de sesión
            localStorage.setItem('isLoggedIn', 'true');

            // Redirigir al panel de administración
            window.location.href = "admin.php";
        } else {
            // Si las credenciales son incorrectas, mostrar mensaje de error
            errorMessage.textContent = "Correo o contraseña incorrectos.";
            errorMessage.style.display = "block";
        }
    });
}
