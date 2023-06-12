// Constantes para completar las rutas de la API.
const ACTIVIDADES_API = 'business/privado/actividades.php';
const titulo_modal = document.getElementById('modal-title');
const TBODY_ROWS = document.getElementById('TablaEm');
const FORMULARIO = document.getElementById('save-form');
// Constante para establecer el formulario de buscar.


// javascript se manda a llamar el id y en php el name

// Método manejador de eventos para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
// Llamada a la función para llenar la tabla con los registros disponibles.
fillTable();
});


FORMULARIO.addEventListener('submit', async(event) =>{
event.preventDefault();
(document.getElementById('id').value) ? action = 'update' : action = 'create';
const FORM = new FormData(FORMULARIO);
const JSON = await dataFetch(ACTIVIDADES_API, action, FORM);
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
* Función asíncrona para llenar la tabla con los registros disponibles.
* Parámetros: form (objeto opcional con los datos de búsqueda).
* Retorno: ninguno.
*/
async function fillTable(form = null) {
// Se inicializa el contenido de la tabla.
TBODY_ROWS.innerHTML = '';
// RECORDS.textContent ='';
// Se verifica la acción a realizar.
(form) ? action = 'search' : action = 'readAll';
// Petición para obtener los registros disponibles.
const JSON = await dataFetch(ACTIVIDADES_API, action, form);
// Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
if (JSON.status) {
// Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
JSON.dataset.forEach(row => {
// Se establece un icono para el estado del producto.

// Se crean y concatenan las filas de la tabla con los datos de cada registro.
TBODY_ROWS.innerHTML += `
<tr>
    <div class="row">
        <div class="col-sm text-center">
            <h3>Titulo</h3>
            <td>${row.nombre_actividad}</td>
            <h3>Tipo de actividad</h3>
            <button class="btn btn-primary" type="button" id="btnEm_grado" data-mdb-toggle="dropdown"
                aria-expanded="false">
                <td>${row.tipo_actividad}</td>

            </button>
        </div>
        <div class="col-sm text-center">
            <h3>Descripción</h3>
            <td>${row.descripcion}</td>
        </div>
        <div class="col-sm text-center">
            <h3>Ponderación</h3>
            <td>${row.ponderacion} %</td>
            <h3>Fecha Limite</h3>
            <td>${row.fecha_entrega}</td>
        </div>
        <div class="col-sm-1 text-center">
            <br>
            <br>

            <button style="border-style: none; background: transparent; "><img
                    src="../../recursos/iconos/informacion-removebg-preview.png" data-mdb-toggle="modal"
                    data-mdb-target="#ModalActividad" onclick="updateActividades(${row.id_actividad})" alt=""></button>

        </div>
        <div class="col-sm-1 text-center">
            <br>
            <br>
            <button style="border-style: none; background: transparent; "><img src="../../recursos/iconos/eliminar2.png"
                    alt="" onclick="DeleteActividades(${row.id_actividad})"></button>

        </div>
    </div>

    <hr class="hr" />

</tr>
`;
});

// RECORDS.textContent = JSON.message;

} else {
sweetAlert(4, JSON.exception, true);
}
}

function createActividades() {
// FORMULARIO.reset();
titulo_modal.textContent ='Asignar una nueva actividad';
fillSelect(ACTIVIDADES_API, 'readTipoActividades', 'tipo_actividad');
fillSelect(ACTIVIDADES_API, 'readDetalle', 'detalle');
fillSelect(ACTIVIDADES_API, 'readTrimestre', 'trimestre');
}

async function updateActividades(id_actividad) {
// FORMULARIO.reset();
const FORM = new FormData();
FORM.append('id_actividad', id_actividad);
const JSON = await dataFetch(ACTIVIDADES_API, 'readOne', FORM);
if (JSON.status) {
titulo_modal.textContent ='Modificar actividad asignada';
document.getElementById('id').value = JSON.dataset.id_actividad;
document.getElementById('nombre').value = JSON.dataset.nombre_actividad;
document.getElementById('ponderacion').value = JSON.dataset.ponderacion;
document.getElementById('fecha_entrega').value = JSON.dataset.fecha_entrega;
document.getElementById('descripcion').value = JSON.dataset.descripcion;
fillSelect(ACTIVIDADES_API, 'readTipoActividades', 'tipo_actividad', JSON.dataset.tipo_actividad);
fillSelect(ACTIVIDADES_API, 'readDetalle', 'detalle', JSON.dataset.asignacion);
fillSelect(ACTIVIDADES_API, 'readTrimestre', 'trimestre', JSON.dataset.trimestre);
fillSelect(ACTIVIDADES_API, 'readAll', 'nombre', JSON.dataset.id_actividad);
}
}

async function DeleteActividades(id_actividad) {
// Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
const RESPONSE = await confirmAction('¿Desea eliminar la actividad de forma permanente?');
// Se verifica la respuesta del mensaje.
if (RESPONSE) {
// Se define una constante tipo objeto con los datos del registro seleccionado.
const FORM = new FormData();
FORM.append('id_actividad', id_actividad);
// Petición para eliminar el registro seleccionado.
const JSON = await dataFetch(ACTIVIDADES_API, 'delete', FORM);
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