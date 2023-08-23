
// Constante para completar la ruta de la API.
const RESPONSABLES_API = 'business/privado/responsables_vista.php';
// Constante para establecer el formulario de buscar.
// Constante para establecer el formulario de guardar.
const SAVE_FORM = document.getElementById('save-form');
// Constante para establecer el título de la modal.
const MODAL_TITLE = document.getElementById('modal-title');
// Constantes para establecer el contenido de la tabla.
const TBODY_ROWS = document.getElementById('tbody-rows');
const RECORDS = document.getElementById('records');
// Constante tipo objeto para establecer las opciones del componente Modal.

// Método manejador de eventos para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para llenar la tabla con los registros disponibles.
    fillTable();
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


//buscador estudiantes
//Buscar estudiantes dentro de modal funcion
document.getElementById('search').addEventListener('change', async () => {
    data = document.getElementById('search').value;
    const FORM = new FormData();
    FORM.append('param', document.getElementById('search').value);
    fillSelect2(RESPONSABLES_API, 'SearchEstudiante', 'estudiante', data, null)
   /* const JSON = dataFetch(RESPONSABLES_API, 'SearchEstudiante', FORM);
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
                    <td>${row.telefono_trabajo}</td>
                    <td>${row.parentesco}</td>
                    <td>
                    <button onclick="openUpdate(${row.id_responsable})"  type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Actualizar
                    </button>
                    <button onclick="openDelete(${row.id_responsable})" type="button" class="btn btn-danger">Eliminar</button>
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
    document.getElementById('estudiante_label').hidden = true;
    document.getElementById('estudiante_aniadir').hidden = true;
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
        document.getElementById('telefono').value = JSON.dataset.telefono_trabajo;
        document.getElementById('parentesco').value = JSON.dataset.parentesco;
        document.getElementById('estudiante_label').hidden = false;
        document.getElementById('estudiante_aniadir').hidden = false;
        document.getElementById('estudiante_label').innerHTML = `Estudiante: ${JSON.dataset.estudiante}`;
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
