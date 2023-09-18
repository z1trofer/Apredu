//llamar formulario log in
const LOGIN_FORM = document.getElementById('formulario_login');
//llamar modal signup
const SIGN_MODAL = new bootstrap.Modal('#ModalDocentesInfo');
//llamar formulario signup
const SIGN_FORM = document.getElementById('save-form');
const PASSWORD_FORM = document.getElementById('save-clave');
//
const PASSWORD_MODAL = new mdb.Modal(document.getElementById('passwordModal'));

document.addEventListener('DOMContentLoaded', async () => {
    // Petición para consultar los usuarios registrados.
    const TODAY = new Date();
    let day = ('0' + TODAY.getDate()).slice(-2);
    var month = ('0' + (TODAY.getMonth() + 1)).slice(-2);
    let year = TODAY.getFullYear() - 20;
    let date = `${year}-${month}-${day}`;
    document.getElementById('fecha_nacimiento').max = date;
    const JSON = await dataFetch(USER_API, 'getSession');
    if(JSON.status){
        location.href = 'principal.html';
    }
    const JSON2 = await dataFetch(USER_API, 'readUsers');
    if(JSON2.status) {
    } else{
         // Se muestra el formulario para registrar el primer usuario
         document.getElementById('btnEmpleados').click();
         sweetAlert(3, '¡Vaya!, parece que es tu primera vez con nosotros, debes registrar el primer usuario', true);
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
        //se redirecciona al la interfaz 
        sweetAlert(1, JSON.message, true, 'autenticacion.html');
    } else {
        if (JSON.clave) {
            sweetAlert(3, JSON.exception, true);
            //Abrir model para cambiar contraseña
            PASSWORD_MODAL.show();
        } else {
            sweetAlert(2, JSON.exception, false);
        }
    }
});


// Método manejador de eventos para cuando se envía el formulario de registro del primer usuario.
SIGN_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SIGN_FORM);
    // Petición para registrar el primer usuario del sitio privado.
    const JSON = await dataFetch(USER_API, 'signup', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        sweetAlert(1, JSON.message, true, 'index.html');
    } else {
        sweetAlert(2, JSON.exception, false);
    }
});

// Método manejador de eventos para cuando se envía el formulario de registro del primer usuario.
PASSWORD_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(PASSWORD_FORM);
    // Petición para registrar el primer usuario del sitio privado.
    const JSON = await dataFetch(USER_API, 'changePassword', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        sweetAlert(1, JSON.message, true);
        PASSWORD_MODAL.hide();
        LOGIN_FORM.reset();
    } else {
        sweetAlert(2, JSON.exception, false);
    }
});

function openCreate() {
    SIGN_FORM.reset();
    // Se restauran los elementos del formulario.
    fillSelect(USER_API, 'readCargos', 'cargo', 'Seleccione un cargo');
};

function evitarCierreModal() {
    $('#ModalDocentesInfo').modal({
        backdrop: 'static', // Evita que se cierre al hacer clic fuera
        keyboard: false // Evita que se cierre al presionar ESC
    });
}
