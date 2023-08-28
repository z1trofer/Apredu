//llamar formulario log in
const LOGIN_FORM = document.getElementById('formulario_login');


document.addEventListener('DOMContentLoaded', async () => {
    // Petición para consultar los usuarios registrados.
    const JSON = await dataFetch(USER_API, 'getSession');
    // Se comprueba si existe una sesión, de lo contrario se sigue con el flujo normal.
    if (JSON.session) {
        // Se direcciona a la página web de bienvenida.
        location.href = 'principal.html';
    } else {
        //se envia un mensaje de error
        sweetAlert(2, 'La sesión ya no es valida', false);
    }
});

//declaracion de id_grado
let idgrado = null

// Método manejador de eventos para cuando se envía el formulario de inicio de sesión.
LOGIN_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(LOGIN_FORM);
    // Petición para iniciar sesión.
    const JSON = await dataFetch(USER_API, 'login', FORM);
     
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        //se redirecciona al la interfaz principal
        location.href = 'principal.html';
        sweetAlert(1, JSON.message, true, 'principal.html');
    } else {
        //exepcion
        console.log(JSON.execption);
        sweetAlert(2, JSON.exception, false);
    }
});