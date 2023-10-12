// Constante para completar la ruta de la API.
const ASIGNATURAS_API = 'business/asignaturas.php';
// Constante para establecer el formulario de buscar.
// Constante para establecer el formulario de guardar.
const SAVE_FORM_AS = document.getElementById('save-form-as');
// Constante para establecer el título de la modal.
const MODAL_TITLE = document.getElementById('modal-title');
// Constantes para establecer el contenido de la tabla.
const TBODY_ROWS = document.getElementById('tbody-rows');
const RECORDS = document.getElementById('records');
// Constante tipo objeto para establecer las opciones del componente Modal.

//funcion para verificar que el usuario tenga los permisos para acceder
async function validate() {
    const JSON = await dataFetch(ASIGNATURAS_API, 'getVistaAutorizacion');
    if (JSON.status) {
        return true;
    } else {
        return false;
    }
}
// Método manejador de eventos para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', async () => {
    if (await validate() == true) {
        fillTable();
    }
    // Llamada a la función para llenar la tabla con los registros disponibles.

});

// Método manejador de eventos para cuando se envía el formulario de guardar.
SAVE_FORM_AS.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se verifica la acción a realizar.
    (document.getElementById('id_asignatura').value) ? action = 'update' : action = 'create';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM_AS);
    // Petición para guardar los datos del formulario.
    const JSON = await dataFetch(ASIGNATURAS_API, action, FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        // Se carga nuevamente la tabla para visualizar los cambios.
        fillTable();
        document.getElementById("closeAsignatura").click();
        // Se muestra un mensaje de éxito.
        sweetAlert(1, JSON.message, true);
    } else {
        sweetAlert(2, JSON.exception, false);
    }
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
    const JSON = await dataFetch(ASIGNATURAS_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        // Se recorre el conjunto de registros fila por fila.
        JSON.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TBODY_ROWS.innerHTML += `
                <tr>
                <td>${row.id_asignatura}</td>
                    <td>${row.asignatura}</td>
                    <td>
                    <button onclick="openUpdate(${row.id_asignatura})"  type="button" class="btn btn-info " data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="fa-solid fa-pencil"></i>
                    </button></td>
                    <td>
                    <button onclick="openDelete(${row.id_asignatura})" type="button" class="btn btn-danger ">
                    <i class="fas fa-trash"></i></button>
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
    SAVE_FORM_AS.reset();
}

/*
*   Función asíncrona para preparar el formulario al momento de actualizar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
async function openUpdate(id) {
    // Se define una constante tipo objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('id_asignatura', id);
    // Petición para obtener los datos del registro solicitado.
    const JSON = await dataFetch(ASIGNATURAS_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        SAVE_FORM_AS.reset();
        // Se inicializan los campos del formulario.
        document.getElementById('id_asignatura').value = JSON.dataset.id_asignatura;
        document.getElementById('asignatura').value = JSON.dataset.asignatura;
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
    const RESPONSE = await confirmAction('¿Desea eliminar esta asignatura de forma permanente?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define una constante tipo objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('id_asignatura', id);
        // Petición para eliminar el registro seleccionado.
        const JSON = await dataFetch(ASIGNATURAS_API, 'delete', FORM);
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

function openReport() {
    // Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
    const PATH = new URL(`${SERVER_URL}reports/asignatura_empleado.php`);
    // Se abre el reporte en una nueva pestaña del navegador web.
    window.open(PATH.href);
}

function openReport(id) {
    // Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
    const PATH = new URL(`${SERVER_URL}reports/asignatura_empleado.php`);
    // Se agrega un parámetro a la ruta con el valor del registro seleccionado.
    PATH.searchParams.append('id_asignatura', id);
    // Se abre el reporte en una nueva pestaña del navegador web.
    window.open(PATH.href);
}

//Buscador
(function (document) {
    'buscador';

    var LightTableFilter = (function (Arr) {

        var _input;

        function _onInputEvent(e) {
            _input = e.target;
            var tables = document.getElementsByClassName(_input.getAttribute('data-table'));
            Arr.forEach.call(tables, function (table) {
                Arr.forEach.call(table.tBodies, function (tbody) {
                    Arr.forEach.call(tbody.rows, _filter);
                });
            });
        }

        function _filter(row) {
            var text = row.textContent.toLowerCase(), val = _input.value.toLowerCase();
            row.style.display = text.indexOf(val) === -1 ? 'none' : 'table-row';
        }

        return {
            init: function () {
                var inputs = document.getElementsByClassName('light-table-filter');
                Arr.forEach.call(inputs, function (input) {
                    input.oninput = _onInputEvent;
                });
            }
        };
    })(Array.prototype);

    document.addEventListener('readystatechange', function () {
        if (document.readyState === 'complete') {
            LightTableFilter.init();
        }
    });

})(document);





