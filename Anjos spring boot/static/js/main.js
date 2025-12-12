// Toggle menu lateral
document.addEventListener('DOMContentLoaded', function() {
    const botonMenu = document.querySelector('.boton-menu');
    const menuLateral = document.querySelector('.menu-lateral');
    
    if (botonMenu && menuLateral) {
        botonMenu.addEventListener('click', function() {
            menuLateral.classList.toggle('activo');
            botonMenu.classList.toggle('activo');
        });
    }
    
    // Cerrar menú al hacer clic fuera
    document.addEventListener('click', function(event) {
        if (menuLateral && botonMenu) {
            if (!menuLateral.contains(event.target) && 
                !botonMenu.contains(event.target) && 
                menuLateral.classList.contains('activo')) {
                menuLateral.classList.remove('activo');
                botonMenu.classList.remove('activo');
            }
        }
    });
});

// Funciones de carrito
function updateCartItem(cartItemId, quantity) {
    // Implementación para actualizar cantidad en carrito
    console.log('Actualizando carrito:', cartItemId, quantity);
}

function removeFromCart(cartItemId) {
    if (confirm('¿Estás seguro de eliminar este producto del carrito?')) {
        // Implementación para eliminar del carrito
        console.log('Eliminando del carrito:', cartItemId);
    }
}

// Funciones de favoritos
function toggleFavorite(productId) {
    // Implementación para agregar/quitar favorito
    console.log('Toggle favorito:', productId);
}

// Validación de formularios
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    }
}
