const SAVE_FORM_AUTENTIFICACION = document.getElementById("codigo_verificacion-form");
const USER_API = 'business/usuarios.php';


document.addEventListener('DOMContentLoaded', async () => {
    // Petición para consultar los usuarios registrados.
    const JSON = await dataFetch(USER_API, 'getSession');
    // const JSON2 = await dataFetch(USER_API, 'cheackSession');
    // Se comprueba si existe una sesión, de lo contrario se sigue con el flujo normal.
    if (JSON.status) {
        // Se direcciona a la página web de bienvenida.
        location.href('principal.html');
    } else {
        //se envia un mensaje de error
        sweetAlert(2, 'La sesión ya no es valida', false);
    }

});

//evento submit del formulario
SAVE_FORM_AUTENTIFICACION.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM_AUTENTIFICACION);
    // Petición para guardar los datos del formulario.
    const JSON = await dataFetch(USER_API, 'ad', FORM);
    //si no ha iniciado sesion
    if (JSON.status) {
        //mensaje si todo fue correcto cambio de contraseña
        sweetAlert(1, JSON.message, true, 'principal.html');//aqui el html de cambiar contra
    } else {
        //problema mensaje
        sweetAlert(2, JSON.exception, false);
    }
});

//función asíncrona para reiniciar el proceso de inicio de sesión
async function volverIntentar() {
     
    // Se muestra un mensaje de confirmación y se captura la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Está seguro de reiniciar el proceso de inicio de sesión?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
         
        // Petición para eliminar la sesión.
        const JSON = await dataFetch(USER_API, 'logOut');
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (JSON.status) {
            sweetAlert(1, JSON.message, true, 'index.html');
        } else {
            sweetAlert(2, JSON.exception, false);
        }
    }
}