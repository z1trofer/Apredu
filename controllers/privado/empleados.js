// Constante para completar la ruta de la API.
const EMPLEADOS_API = 'business/privado/empleados.php';
// Constante para establecer el formulario de buscar.
// Constante para establecer el formulario de guardar.
const SAVE_FORM = document.getElementById('save-form');
// Constante para establecer el título de la modal.
const titulo_modal = document.getElementById('modal-title');
const titulo_modal2 = document.getElementById('modal-title2');
const SAVE_FORM_DETALLE = document.getElementById('save-form-detalle');
// Constantes para establecer el contenido de la tabla.
const TBODY_ROWS = document.getElementById('table-alum');
const RECORDS = document.getElementById('records');
const SEARCH_FORM = document.getElementById('search-form');
const FORM_INFOACTI = document.getElementById('info-form');
// Constante para cargar la tabla de la ficha de actividades
const TBODY_ROWS_ACT = document.getElementById('table_act');
// 
const CMB_ASIGNATURA = document.getElementById('asignatura');
const CMB_GRADO = document.getElementById('grado');

// Variable
var valor_asignatura;
var valor_grado;
var valor_empleado;

function CapturandoDatos() {
    console.log(valor_asignatura);
    console.log(valor_grado);
    // fillTable2(id_empleado);
    CargandoDatos( valor_empleado, valor_grado, valor_asignatura);
}

function CargandoDatos(empleado, grado, asignatura){
    fillTable2(empleado, grado, asignatura);
}


CMB_ASIGNATURA.addEventListener('change', () => {
    // CAPTURANDO EL ID DE ASIGNATURA CADA VEZ QUE SE CAMBIA
    valor_asignatura = CMB_ASIGNATURA.options[CMB_ASIGNATURA.selectedIndex].value;
    // fillTable2(null, null, valor_asignatura);
    CapturandoDatos();

});

CMB_GRADO.addEventListener('change', () => {
    // CAPTURANDO EL ID DE GRADO CADA VEZ QUE SE CAMBIA
    valor_grado = CMB_GRADO.options[CMB_GRADO.selectedIndex].value;
    // fillTable2(null, valor_grado, null);
});

// Constante tipo objeto para establecer las opciones del componente Modal.

// Método manejador de eventos para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para llenar la tabla con los registros disponibles.
    fillTable();
    // Constante tipo objeto para obtener la fecha y hora actual.
    const TODAY = new Date();
    // Se declara e inicializa una variable para guardar el día en formato de 2 dígitos.
    let day = ('0' + TODAY.getDate()).slice(-2);
    // Se declara e inicializa una variable para guardar el mes en formato de 2 dígitos.
    var month = ('0' + (TODAY.getMonth() + 1)).slice(-2);
    // Se declara e inicializa una variable para guardar el año con la mayoría de edad.
    let year = TODAY.getFullYear() - 18;
    // Se declara e inicializa una variable para establecer el formato de la fecha.
    let date = `${year}-${month}-${day}`;
    // Se asigna la fecha como valor máximo en el campo del formulario.
    document.getElementById('fecha_nacimiento').max = date;
});

// SAVE_FORM_DETALLE.addEventListener('submit', async(event) =>{
//     event.preventDefault();
//     (document.getElementById('id').value) ? action = 'update' : action = 'create';
//     const FORM = new FormData(SAVE_FORM_DETALLE);
//     const JSON = await dataFetch(EMPLEADOS_API, action, FORM);
//     if (JSON.status) {
//         // Se carga nuevamente la tabla para visualizar los cambios.
//         fillTable2();      
//         // Se muestra un mensaje de éxito.
//         sweetAlert(1, JSON.message, true);
//     } else {
//         sweetAlert(2, JSON.exception, false);
//     }
// });


SEARCH_FORM.addEventListener('submit', (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SEARCH_FORM);
    // Llamada a la función para llenar la tabla con los resultados de la búsqueda.
    fillTable(FORM);
});

// Método manejador de eventos para cuando se envía el formulario de guardar.
SAVE_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se verifica la acción a realizar.
    (document.getElementById('id').value) ? action = 'update' : action = 'create';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM);
    // Petición para guardar los datos del formulario.
    const JSON = await dataFetch(EMPLEADOS_API, action, FORM);
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

/*
*   Función asíncrona para llenar la tabla con los registros disponibles.
*   Parámetros: form (objeto opcional con los datos de búsqueda).
*   Retorno: ninguno.
*/
async function fillTable(form = null) {
    // Se inicializa el contenido de la tabla.
    TBODY_ROWS.innerHTML = '';

    // Se verifica la acción a realizar.
    (form) ? action = 'search' : action = 'readAll';
    // Petición para obtener los registros disponibles.
    const JSON = await dataFetch(EMPLEADOS_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        // Se recorre el conjunto de registros fila por fila.
        JSON.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TBODY_ROWS.innerHTML += `
                
                <tr>
                <td>${row.apellido_empleado}</td>
                <td>${row.nombre_empleado}</td>
                <td>${row.cargo}</td>
                <td>${row.correo_empleado}</td>


                    <td>
                        <button type="button" class="btn btn btn-floating btn-lg" data-mdb-toggle="modal"
                            data-mdb-target="#ModalDocentesAct" onclick="openDetalleActividad(${row.id_empleado})">
                            <img src="../../recursos/iconos/notas.png" alt="">
                        </button>
                    </td>
                    <td>
                        <button type="button" class="btn btn btn-floating btn-lg" data-mdb-toggle="modal"
                            data-mdb-target="#ModalDocentesInfo" onclick="openUpdate(${row.id_empleado})">
                            <img src="../../recursos/iconos/informacion.png" alt="">
                        </button>
                        <button type="button" class="btn btn btn-floating btn-lg" onclick="openDelete(${row.id_empleado})">
                        <img src="../../recursos/iconos/eliminar2.png" alt="">
                    </button>

                        <!-- Modal -->

                    </td>
                </tr>
                
            `;
        });

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
    titulo_modal.textContent = 'Asignar un nuevo empleado';
    fillSelect(EMPLEADOS_API, 'readCargos', 'cargo');
}

function openDetalleActividad(id_empleado) {
    console.log(id_empleado);
    titulo_modal2.textContent = 'Información de actividades';
    fillSelect2(EMPLEADOS_API, 'readAsignaturas_empleado', 'asignatura', id_empleado);
    fillSelect2(EMPLEADOS_API, 'readGrados_empleado', 'grado', id_empleado);
    fillTable2(id_empleado);
    valor_empleado = id_empleado;
    document.getElementById('nombre_empleado').value = JSON.dataset.nombre_empleado;
    CapturandoDatos();

}

/*
*   Función asíncrona para preparar el formulario al momento de actualizar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
async function openUpdate(id_empleado) {
    // Se define una constante tipo objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('id_empleado', id_empleado);
    // Petición para obtener los datos del registro solicitado.
    const JSON = await dataFetch(EMPLEADOS_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        titulo_modal.textContent = 'Modificar el empleado';
        // Se inicializan los campos del formulario.
        document.getElementById('id').value = JSON.dataset.id_empleado;
        document.getElementById('nombres').value = JSON.dataset.nombre_empleado;
        document.getElementById('apellidos').value = JSON.dataset.apellido_empleado;
        document.getElementById('dui').value = JSON.dataset.dui;
        fillSelect(EMPLEADOS_API, 'readCargos', 'cargo', JSON.dataset.cargo);
        document.getElementById('correo').value = JSON.dataset.correo_empleado;
        document.getElementById('direccion').value = JSON.dataset.direccion;
        document.getElementById('fecha_nacimiento').value = JSON.dataset.fecha_nacimiento;
        document.getElementById('usuario').value = JSON.dataset.usuario_empleado;
        document.getElementById('clave').value = JSON.dataset.clave;


    } else {
        sweetAlert(2, JSON.exception, false);
    }
}

/*
*   Función asíncrona para eliminar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
async function openDelete(id_empleado) {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Desea eliminar el empleado de forma permanente?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define una constante tipo objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('id_empleado', id_empleado);
        // Petición para eliminar el registro seleccionado.
        const JSON = await dataFetch(EMPLEADOS_API, 'delete', FORM);
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

async function fillTable2(id_empleado, id_grado = null, id_asignatura = null) {
    // Se inicializa el contenido de la tabla.
    if (id_grado == null && id_asignatura == null) {
        const FORM = new FormData();
        FORM.append('id_empleado', id_empleado);
        const JSON = await dataFetch(EMPLEADOS_API, 'readSinFiltros', FORM);
        if (JSON.status) {
            TBODY_ROWS_ACT.innerHTML = '';

            JSON.dataset.forEach((row) => {

                // Se crean y concatenan las filas de la tabla con los datos de cada registro.
                TBODY_ROWS_ACT.innerHTML += `
            <div class="row">
                    
            <div class="col-sm-2">
                <th>Actividad</th>
                <p>${row.nombre_actividad}</p>
                <button class="btn btn-primary" type="button" id="btnTipo_Act"
                    data-mdb-toggle="dropdown">
                    Activid..
                </button>

            </div>
            <div class="col-sm-4">
                <th>Descripción</th>
                <p>${row.descripcion}</p>
            </div>
            <div class="col-sm-1">
                <th>%</th>
                <p>${row.ponderacion}</p>
            </div>
            <div class="col-sm-2">
                <th>Grado</th>
                <button class="btn btn-primary" type="button" id="btnEm_grado"
                    data-mdb-toggle="dropdown" aria-expanded="false">
                    ${row.grado}
                </button>

            </div>
            <div class="col-sm-3">
                <th>Asignatura</th>
                <button class="btn btn-primary" type="button" id="btnEm_grado"
                    data-mdb-toggle="dropdown" aria-expanded="false">
                    ${row.asignatura}              
            </button>
                <th>Fecha de entrega</th>
                <p>${row.fecha_entrega}</p>

            </div>
        </div>

        <hr class="hr" />       
            `;
            })
        } else {
            console.log("Error al mostrar");
        }
    } else if(id_grado != null && id_asignatura == null) {
        console.log('No se admiten un valor nulo');
    }else{
        const FORM = new FormData();
        FORM.append('id_empleado', id_empleado);
        FORM.append('id_grado', id_grado);
        FORM.append('id_asignatura', id_asignatura);
        const JSON = await dataFetch(EMPLEADOS_API, 'readPorDetalle', FORM);
        if (JSON.status) {
            TBODY_ROWS_ACT.innerHTML = '';

            JSON.dataset.forEach((row) => {

                // Se crean y concatenan las filas de la tabla con los datos de cada registro.
                TBODY_ROWS_ACT.innerHTML += `
            <div class="row">
                    
            <div class="col-sm-2">
                <th>Actividad</th>
                <p>${row.nombre_actividad}</p>
                <button class="btn btn-primary" type="button" id="btnTipo_Act"
                    data-mdb-toggle="dropdown">
                    Activid..
                </button>

            </div>
            <div class="col-sm-4">
                <th>Descripción</th>
                <p>${row.descripcion}</p>
            </div>
            <div class="col-sm-1">
                <th>%</th>
                <p>${row.ponderacion}</p>
            </div>
            <div class="col-sm-2">
                <th>Grado</th>
                <button class="btn btn-primary" type="button" id="btnEm_grado"
                    data-mdb-toggle="dropdown" aria-expanded="false">
                    ${row.grado}
                </button>

            </div>
            <div class="col-sm-3">
                <th>Asignatura</th>
                <button class="btn btn-primary" type="button" id="btnEm_grado"
                    data-mdb-toggle="dropdown" aria-expanded="false">
                    ${row.asignatura}
                </button>
                <th>Fecha de entrega</th>
                <p> ${row.fecha_entrega}</p>

            </div>
        </div>

        <hr class="hr" />       
            `;
            })
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
