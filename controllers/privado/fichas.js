const FICHA_API = 'business/privado/fichas.php';
const SAVE_FORM = document.getElementById('save-form');
// Constante para establecer el título de la modal.
const MODAL_TITLE = document.getElementById('modal-title');
// Constantes para establecer el contenido de la tabla.
const TBODY_ROWS = document.getElementById('tbody-rows');
const RECORDS = document.getElementById('records');
const TBODY_ROWS2 = document.getElementById('tbody-rows2');
const RECORDS2 = document.getElementById('records2');
const TBODY_ROWS3 = document.getElementById('tbody-rows3');
const RECORDS3 = document.getElementById('records3');

document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para llenar la tabla con los registros disponibles.
    fillTable();
});

SAVE_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se verifica la acción a realizar.
    (document.getElementById('id_ficha').value) ? action = 'update' : action = 'create';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM);
    // Petición para guardar los datos del formulario.
    const JSON = await dataFetch(FICHA_API, action, FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        // Se carga nuevamente la tabla para visualizar los cambios.
        fillTable();
        // Se muestra un mensaje de éxito.
        sweetAlert(1, JSON.message, true);
    } else {
        sweetAlert(2, JSON.exception, false);
    }
});

async function openFichas(id_ficha) {
    // Se define una constante tipo objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('id_ficha', id_ficha);
    TBODY_ROWS2.innerHTML = '';
    RECORDS2.textContent = '';
    // Petición para obtener los datos del registro solicitado.
    const JSON = await dataFetch(FICHA_API, 'readAllFichas', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        // Se recorre el conjunto de registros fila por fila.
        JSON.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TBODY_ROWS2.innerHTML += `  
            <tr>
                <td>${row.nombre_estudiante}</td>
                <td>${row.apellido_estudiante}</td>
                <td>${row.grado}</td>
                <td>${row.descripcion_ficha}</td>
                <td>${row.fecha_ficha}</td>
                <td>${row.nombre_empleado}</td>
            </tr>
        `;
        });
        RECORDS.textContent = JSON.message;
    } else {
        sweetAlert(4, JSON.exception, true);
    }
}


async function fillTable(form = null) {
    // Se inicializa el contenido de la tabla.
    TBODY_ROWS.innerHTML = '';
    RECORDS.textContent = '';
    // Se verifica la acción a realizar.
    (form) ? action = 'search' : action = 'readAll';
    // Petición para obtener los registros disponibles.
    const JSON = await dataFetch(FICHA_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        // Se recorre el conjunto de registros fila por fila.
        JSON.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TBODY_ROWS.innerHTML += `  
                <tr>
                <td>${row.id_estudiante}</td>
                    <td>${row.apellido_estudiante}</td>
                    <td>${row.nombre_estudiante}</td>
                    <td>${row.grado}</td>
                    <td>
                    <button onclick="openReportConducta(${row.id_estudiante})" class="btn btn-warning btn-outline btn-floating
                    data-mdb-ripple-color=" dark type="button">
                        <i class="fas fa-id-badge"></i>
                    </button>
    
                    </td>
                    <td><button onclick="openCreate(${row.id_estudiante})" type="button" class="btn btn btn-success btn-rounded" data-mdb-toggle="modal" data-mdb-target="#exampleModal">
                    <i class="fa-sharp fa-solid fa-plus"></i>
                    </button>
                    <button onclick="openDetallePorFicha(${row.id_estudiante})" type="button" class="btn btn-info btn-rounded" data-mdb-toggle="modal" data-mdb-target="#VerInfo">
                    <i class="fa-solid fa-eye"></i>
                    </button>
                    <td>
                </tr>
            `;
        });
        RECORDS.textContent = JSON.message;
    } else {
        sweetAlert(4, JSON.exception, true);
    }
}
fillSelect(FICHA_API, 'readEmpleado', 'nombre_empleado');


async function openCreate(id) {
    // Se define una constante tipo objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('id_estudiante', id);
    SAVE_FORM.reset();
    // Petición para obtener los datos del registro solicitado.
    const JSON = await dataFetch(FICHA_API, 'readOneEstudiante', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        // Se inicializan los campos del formulario.
        document.getElementById('id_estudiante').value = JSON.dataset.id_estudiante;
        document.getElementById('nombre_ficha').value = JSON.dataset.nombre_estudiante;
        document.getElementById('apellido_ficha').value = JSON.dataset.apellido_estudiante;
        document.getElementById('grado_ficha').value = JSON.dataset.grado;
        //se llama a la API para obtener los datos
        label = document.getElementById('nombre_empleado');
        //se llama a la API para obtener los datos
        const SESSION = await dataFetch(USER_API, 'getSession');
        //se verifica el id_cargo
        if(SESSION){
            //se llena el label con el nombre del docente
            label.innerHTML = SESSION.nombre;
            document.getElementById('id_empleado').value = SESSION.id_empleado;
        }else{
            //se deja el label vacio
            label.innerHTML = "ekis de";
        }
    } else {
        sweetAlert(2, JSON.exception, false);
    }
}

async function openDetallePorFicha(id_estudiante) {
    // Se define una constante tipo objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('id_estudiante', id_estudiante);
    TBODY_ROWS3.innerHTML = '';
    RECORDS3.textContent = '';
    // Petición para obtener los datos del registro solicitado.
    const JSON = await dataFetch(FICHA_API, 'readOneFichaXestudiante', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        // Se recorre el conjunto de registros fila por fila.
        JSON.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TBODY_ROWS3.innerHTML += `  
            <tr>
                <td>${row.descripcion_ficha}</td>
                <td>${row.fecha_ficha}</td>
                <td>${row.nombre_empleado}</td>
                
            </tr>
        `;
        });
        RECORDS3.textContent = JSON.message;
    } else {
        sweetAlert(4, JSON.exception, true);
    }
}

async function openDetalle(id_ficha) {
    // Se define una constante tipo objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('id_ficha', id_ficha);
    // Petición para obtener los datos del registro solicitado.
    const JSON = await dataFetch(FICHA_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        // Se inicializan los campos del formulario.
        document.getElementById('id_ficha').value = JSON.dataset.id_ficha;
        document.getElementById('id_estudiante').value = JSON.dataset.id_estudiante;
        document.getElementById('descripcion').value = JSON.dataset.descripcion_ficha;
        document.getElementById('fecha').value = JSON.dataset.fecha_ficha;
        fillSelect(FICHA_API, 'readEmpleado', 'nombre_empleado', JSON.dataset.nombre_empleado);
    } else {
        sweetAlert(2, JSON.exception, false);
    }
}

function openReportSemanal() {
    // Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
    const PATH = new URL(`${SERVER_URL}reports/dashboard/fichas_en_semana.php`);
    // Se abre el reporte en una nueva pestaña del navegador web.
    window.open(PATH.href);
}


function openReportConducta(id_estudiante) {
    // Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
    const PATH = new URL(`${SERVER_URL}reports/dashboard/fichas_por_estudiante.php`);
    // Se agrega un parámetro a la ruta con el valor del registro seleccionado.
    PATH.searchParams.append('id_estudiante', id_estudiante);
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

