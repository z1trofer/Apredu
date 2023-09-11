//direccion de la api
const PERMISOS_API = 'business/privado/permisos.php';
//cuerpo de la tabla
const TB_BODY = document.getElementById('permisos-body');
//cabecera de la talba
const TB_HEAD = document.getElementById('permisos-head');



async function validate() {
    const JSON = await dataFetch(PERMISOS_API, 'getVistaAutorizacion');
    if (JSON.status) {
        return true;
    } else {
        return false;
    }
}

document.addEventListener('DOMContentLoaded', async () => {
    // Petición para consultar los usuarios registrados.
    if (await validate() == true) {
        // Llamada a la función para llenar la tabla con los registros disponibles.
        fillHeaders()
        //fillTable();
    } else {
        location.href = 'principal.html';
    }
});

async function fillHeaders() {
    TB_HEAD.innerHTML = '';
    //se manda a llamar al servidor
    const JSON = await dataFetch(PERMISOS_API, 'getHeaders');
    //se comprueba el resultado
    if(JSON.status){
        debugger
        JSON.dataset.forEach(row => {
            TB_HEAD.innerHTML += `
            <th scope="col">${row.COLUMN_NAME}</th>
            `;
        })
        /*JSON.dataset.forEach(row => {


        });*/
    }else{
        sweetAlert(2, JSON.exception, false);
    }
}

async function fillTable() {
    
    // Se inicializa el contenido de la tabla.
    TB_BODY.innerHTML = '';
    //se manda a llamar al servidor
    const JSON = await dataFetch(PERMISOS_API, 'ObtenerPermisos');
    //se comprueba el resultado
    if(JSON.status){
        /*JSON.dataset.forEach(row => {


        });*/
    }else{
        sweetAlert(2, JSON.exception, false);
    }
}
