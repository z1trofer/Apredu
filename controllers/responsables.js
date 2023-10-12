
// Constante para completar la ruta de la API.
const RESPONSABLES_API = 'business/responsables.php';
// Constante para establecer el formulario de buscar.
// Constante para establecer el formulario de guardar.
const SAVE_FORM = document.getElementById('save-form');
// Constante para establecer el título de la modal.
const MODAL_TITLE = document.getElementById('modal-title');
// Constantes para establecer el contenido de la tabla.
const TBODY_ROWS = document.getElementById('tbody-rows');
const RECORDS = document.getElementById('records');
// Constante tipo objeto para establecer las opciones del componente Modal.
//formulario bsucar
const SEARCH = document.getElementById('search-form');

//funcion para verificar que el usuario tiene acceso a la pagina
async function validate() {
    const JSON = await dataFetch(RESPONSABLES_API, 'getVistaAutorizacion');
    if (JSON.status) {
        return true;
    } else {
        return false;
    }
}

// Método manejador de eventos para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', async () => {
    if (await validate() == true) {
    // Llamada a la función para llenar la tabla con los registros disponibles.
    const FORM = new FormData(SEARCH);
    fillTable(FORM);
    }
});

// Método manejador de eventos para cuando se envía el formulario de guardar.
SAVE_FORM.addEventListener('submit', async (event) => {
     
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se verifica la acción a realizar.
    (document.getElementById('id_responsable').value) ? action = 'update' : action = 'create';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM);
    // Petición para guardar los datos del formulario.
    const JSON = await dataFetch(RESPONSABLES_API, action, FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        // Se carga nuevamente la tabla para visualizar los cambios.
        fillTable();
        document.getElementById('closeM').click();
        // Se muestra un mensaje de éxito.
        sweetAlert(1, JSON.message, true);
    } else {
        sweetAlert(2, JSON.exception, false);
    }
});
/*
async function search(){
    const FORM = new FormData();
    fillTable(FORM);
}
*/

SEARCH.addEventListener('submit', async (event) => {
     
    event.preventDefault();
    const FORM = new FormData(SEARCH);
    fillTable(FORM);
});

/*
//buscador estudiantes
//Buscar estudiantes dentro de modal funcion
document.getElementById('searchEs').addEventListener('change', async () => {
    data = document.getElementById('search').value;
    const FORM = new FormData();
    FORM.append('param', document.getElementById('search').value);
    fillSelect2(RESPONSABLES_API, 'searchEstudiante', 'estudiante', data, null)
   /* const JSON = dataFetch(RESPONSABLES_API, 'searchEstudiante', FORM);
    if(JSON.status){
        fillSelect
    }
});
*/
document.getElementById('searchEs').addEventListener('change', async () => {
    data = document.getElementById('searchEs').value;
    const FORM = new FormData();
    FORM.append('param', document.getElementById('searchEs').value);
    fillSelect2(RESPONSABLES_API, 'searchEstudiante', 'selectEs', data, null)
   /* const JSON = dataFetch(RESPONSABLES_API, 'searchEstudiante', FORM);
    if(JSON.status){
        fillSelect
    }*/
});



/*
*   Función asíncrona para llenar la tabla con los registros disponibles.
*   Parámetros: form (objeto opcional con los datos de búsqueda).
*   Retorno: ninguno.
*/
async function fillTable(form = null) {
    // Se inicializa el contenido de la tabla.
    TBODY_ROWS.innerHTML = '';
    RECORDS.textContent = '';
    // Se verifica la acción a realizar.
    (form) ? action = 'search' : action = 'readAll';
    // Petición para obtener los registros disponibles.
    const JSON = await dataFetch(RESPONSABLES_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        // Se recorre el conjunto de registros fila por fila.
        JSON.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TBODY_ROWS.innerHTML += `  
                <tr>
                <td>${row.id_responsable}</td>
                    <td>${row.nombre_responsable}</td>
                    <td>${row.apellido_responsable}</td>
                    <td>${row.dui}</td>
                    <td>${row.correo_responsable}</td>
                    <td>${row.lugar_de_trabajo}</td>
                    <td>${row.telefono}</td>
                    <td>
                    <button onclick="openUpdate(${row.id_responsable})"  type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="fa-solid fa-pencil"></i>
                    </button>
                    <button onclick="openDelete(${row.id_responsable})" type="button" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `;
        });
        RECORDS.textContent = JSON.message;
    } else {
        sweetAlert(4, JSON.exception, true);
    }
}

/*
*   Función para preparar el formulario al momento de insertar un registro.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
function openCreate() {
    // Se restauran los elementos del formulario.
    SAVE_FORM.reset();
}

/*
*   Función asíncrona para preparar el formulario al momento de actualizar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
async function openUpdate(id) {
    // Se define una constante tipo objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('id_responsable', id);
    // Petición para obtener los datos del registro solicitado.
    const JSON = await dataFetch(RESPONSABLES_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        SAVE_FORM.reset();
        // Se inicializan los campos del formulario.
        document.getElementById('id_responsable').value = JSON.dataset.id_responsable;
        document.getElementById('nombres').value = JSON.dataset.nombre_responsable;
        document.getElementById('apellidos').value = JSON.dataset.apellido_responsable;
        document.getElementById('dui').value = JSON.dataset.dui;
        document.getElementById('correo').value = JSON.dataset.correo_responsable;
        document.getElementById('lugar').value = JSON.dataset.lugar_de_trabajo;
        document.getElementById('telefono').value = JSON.dataset.telefono;
    } else {
        sweetAlert(2, JSON.exception, false);
    }
}

/*
*   Función asíncrona para eliminar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
async function openDelete(id) {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Desea eliminar la subcategoría de forma permanente?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define una constante tipo objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('id_responsable', id);
        // Petición para eliminar el registro seleccionado.
        const JSON = await dataFetch(RESPONSABLES_API, 'delete', FORM);
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (JSON.status) {
            // Se carga nuevamente la tabla para visualizar los cambios.
            fillTable();
            // Se muestra un mensaje de éxito.
            sweetAlert(1, JSON.message, true);
        } else {
            sweetAlert(2, JSON.exception, false);
        }
    }
}
