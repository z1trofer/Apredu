//direccion de la api
const PERMISOS_API = 'business/permisos.php';
//cuerpo de la tabla
const TB_BODY = document.getElementById('permisos-body');
//cabecera de la talba
const TB_HEAD = document.getElementById('permisos-head');
//formulario para agregar un cargo
const FORM_INSERT = document.getElementById('insert-form');


//funcion para verificar que el usuario tenga los permisos para acceder
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
    TB_HEAD.innerHTML = '<th scope="col"> </th>';
    //se manda a llamar al servidor
    const JSON_P = await dataFetch(PERMISOS_API, 'getHeaders');
    const JSON_C = await dataFetch(PERMISOS_API, 'ObtenerPermisos');
     
    //se comprueba el resultado de ambas consultas
    if (JSON_C.status && JSON_P.status) {
        //se carga el nombre de los cargos en los encabezados de la tabla
        JSON_C.dataset.forEach(row => {
             
            TB_HEAD.innerHTML += `
            <th scope="col">${row[1]} <button type="button" id="icon-private" class="btn btn-danger btn-floating icon-private">
            <img id="icon-img-private" src="../recursos/iconos/icons8-menos-24.png" onclick="deleteCargo(${row[0]}, '${row[1]}')">
          </button></th>
            `;
        });
        cargos_ids = [];
        //se eliminan los primeros 2 campos del arreglo (id_cargo, cargo)
        for (let i = 0; i < JSON_C.dataset.length; i++) {
            cargos_ids.push(JSON_C.dataset[i][0]);
            JSON_C.dataset[i].splice(0, 1);
            JSON_C.dataset[i].splice(0, 1);
        }
        //variable que contendra la injeccion en la vista
        htm = null;
        //se carga mediante un arreglo los atributos
        for (let i = 0; i < JSON_P.dataset.length; i++) {
            htm = `
            <tr>
            <td>${JSON_P.dataset[i][0]}</td>
            `;
            for (let is = 0; is < JSON_C.dataset.length; is++) {
                if(JSON_C.dataset[is][i] == 1){
                    htm = htm + `
                    <td>
                        <div class="form-check form-switch">
                        <input  class="form-check-input" type="checkbox" role="switch"
                        name="estados" checked onchange="cambiarPermiso('${JSON_P.dataset[i][0]}', 0, ${cargos_ids[is]})"/>
                        </div>
                    </td>
                        `;
                }else{
                    htm = htm + `
                    <td>
                        <div class="form-check form-switch">
                        <input  class="form-check-input" type="checkbox" role="switch"
                        name="estados" onchange="cambiarPermiso('${JSON_P.dataset[i][0]}', 1, ${cargos_ids[is]})"/>
                        </div>
                    </td>
                        `;
                }
            }
            htm = htm + '</tr>';
            TB_BODY.innerHTML += htm;
        }
    } else {
        sweetAlert(2, JSON_C.exception + ' ' + JSON_P.exception, false);
    }
}

//Función asíncrona para cambiar los permisos de un cargo
async function cambiarPermiso(atributo, permiso, cargo){
    const RESPONSE = await confirmAction('¿Estas seguro de cambiar los permisos de '+ atributo +'?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        const FORM = new FormData();
        FORM.append('atributo', atributo);
        FORM.append('permiso', permiso);
        FORM.append('cargo', cargo);
        const JSON = await dataFetch(PERMISOS_API, 'cambiarPermiso', FORM);
        if(JSON.status){
            fillTable();
             
            sweetAlert(1, JSON.message, true);
        }else{
            fillTable();
             
            sweetAlert(2, JSON.exception, false);
        }
    }else{
        fillTable();
    }
};

//evento para agregar un cargo al sistema
FORM_INSERT.addEventListener('submit', async (event) => {
    event.preventDefault();
    //se confirma la acción con el usuario
    RESPONSE = await confirmAction('¿Estas seguro de añadir el cargo "'+ FORM_INSERT.cargo.value +'"? no podras modificar su nombre y solo lo podras eliminar cuando no este asignado a ningun empleado');
            //se evalua la respuesta
    if (RESPONSE) {
        const FORM = new FormData(FORM_INSERT);
        const JSON = await dataFetch(PERMISOS_API, 'agregarCargo', FORM);
        if(JSON.status){
            fillTable();
            sweetAlert(1, JSON.message, true);
        }else{
            sweetAlert(2, JSON.exception, false);
        }
    }
});

//eliminar un cargo de un sistema
async function deleteCargo(id, cargo){
    RESPONSE = await confirmAction('¿Estas seguro de añadir el cargo "'+ cargo +'"? no podras modificar su nombre y solo lo podras eliminar cuando no este asignado a ningun empleado');
    if (RESPONSE) {
        const FORM = new FormData();
        FORM.append('id_cargo', id);
        const JSON = await dataFetch(PERMISOS_API, 'eliminarCargo', FORM);
        if(JSON.status){
            fillTable();
            sweetAlert(1, JSON.message, true);
        }else{
            sweetAlert(2, JSON.exception, false);
        }
    }
};
