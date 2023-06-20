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
     
    switch (tipoUsuario) {
        //caso nivel de usuario docente/profesor
        case "2":
            //se esconden los contenidos que no correspondan
            document.getElementById('onlydocentes').hidden = true;
            //se declara en la variable un arreglo con los elementos que tengan la clase "doc"
            things = document.getElementsByClassName('doc');
            //se inicia un bucle que recorre el arreglo
            for (let index = 0; index < things.length; index++) {
                //se esconde el elemento que no deba ver el docente
                things[index].hidden = true;
              }
            break;
        default:
            break;
    }
}
