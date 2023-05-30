/*
*   Controlador de uso general en las páginas web del sitio privado.
*   Sirve para manejar las plantillas del encabezado y pie del documento.
*/

// Constante para completar la ruta de la API.
const USER_API = 'business/privado/usuarios.php';
// Constantes para obtener la etiqueta donde va el usuario
//const USER_LOG = document.getElementById('USerLog');



// Método manejador de eventos para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', async () => {
    // Petición para obtener en nombre del usuario que ha iniciado sesión.
    const JSON = await dataFetch(USER_API, 'getUser');
    // Se verifica si el usuario está autenticado, de lo contrario se envía a iniciar sesión.
    if (JSON.session) {
        // Se comprueba si existe un alias definido para el usuario, de lo contrario se muestra un mensaje con la excepción.
        if (JSON.status) {
            //USER_LOG.innerHTML = `<img src="../../resources/img/user.png" height="20">${JSON.usuario}`;
            // Se inicializa el componente Dropdown para que funcione la lista desplegable en los menús.
        } else {
            sweetAlert(3, JSON.exception, false, 'index.html');
            JSON = await dataFetch(USER_API, 'logOut');
            location.href = 'index.html';
        }
    }
    else if (location.pathname == '/Tienda-en-linea-7mangas/views/private/index.html') {
        // Se inicializa el componente Tooltip para que funcionen las sugerencias textuales.
    } else {
        location.href = 'index.html';
    }
    // Se comprueba si la página web es la principal, de lo contrario se direcciona a iniciar sesión
}
);