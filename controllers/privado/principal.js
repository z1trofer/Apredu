//llamar formulario log in
let tipoUsuario = null;

document.addEventListener('DOMContentLoaded', async () => {
    // Petición para consultar los usuarios registrados.
    const JSON = await dataFetch(USER_API, 'getSession');
    //agregarndo valor a tipo_usuario
    tipoUsuario = JSON.id_cargo;
    //filtrar el menu de usuario segun los permisos respectivos
    validarPermisos()
    // Se comprueba si existe una sesión, de lo contrario se sigue con el flujo normal.
    if (JSON.tipo) {
        // Se direcciona a la página web de bienvenida.
        document.getElementById('userM').innerHTML = `Sesión iniciada como: ${JSON.usuario}, ${JSON.tipo}`
    } else {
        // Se muestra el formulario para registrar el primer usuario.
        //sweetAlert(2, 'aaaaaa', false);
    }
});


//funcion para controlar las opciones disponibles segun el nivel de usuario
function validarPermisos(){
    debugger
    switch (tipoUsuario) {
        //caso nivel de usuario docente/profesor
        case "2":
            document.getElementById('onlydocentes').hidden = true;
            things = document.getElementsByClassName('doc');
            for (let index = 0; index < things.length; index++) {
                things[index].hidden = true;
              }
            break;
        default:
            break;
    }
}
