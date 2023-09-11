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
        //fillHeaders()
        fillTable();
    } else {
        location.href = 'principal.html';
    }
});

async function fillHeaders() {
    TB_HEAD.innerHTML = '';
    //se manda a llamar al servidor
    const JSON = await dataFetch(PERMISOS_API, 'getHeaders');
    //se comprueba el resultado
    if (JSON.status) {
        debugger
        JSON.dataset.forEach(row => {
            TB_HEAD.innerHTML += `
            <th scope="col">${row.COLUMN_NAME}</th>
            `;
        })
        /*JSON.dataset.forEach(row => {


        });*/
    } else {
        sweetAlert(2, JSON.exception, false);
    }
}

async function fillTable() {

    // Se inicializa el contenido de la tabla.
    TB_BODY.innerHTML = '';
    TB_HEAD.innerHTML = '<th scope="col"> asdasd</th>';
    //se manda a llamar al servidor
    const JSON_P = await dataFetch(PERMISOS_API, 'getHeaders');
    const JSON_C = await dataFetch(PERMISOS_API, 'ObtenerPermisos');
    debugger
    //se comprueba el resultado
    if (JSON_C.status && JSON_P.status) {
        /*TB_BODY.innerHTML += `
        <tr>
        <td>${JSON_C.dataset[0][2]}</td>
        `
        console.log(JSON_P.dataset[0][0]);
*/
        //se carga el nombre de los cargos en los encabezados de la tabla
        JSON_C.dataset.forEach(row => {
            TB_HEAD.innerHTML += `
            <th scope="col">${row[1]}</th>
            `;
        });
        debugger
        for (let i = 2; i < JSON_P.dataset.length; i++) {
            TB_BODY.innerHTML += `
            <tr>
            <td>${JSON_P.dataset[i][0]}</td>
            `;
            for (let is = 2; is < JSON_C.dataset.length; is++) {
                TB_BODY.innerHTML += `
                <td>${JSON_C.dataset[i][is]}</td>
                `;
            }
        }

    } else {
        sweetAlert(2, JSON_C.exception + ' ' + JSON_P.exception, false);
    }
}
