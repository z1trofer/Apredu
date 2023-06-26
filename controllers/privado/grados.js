// Constante para completar la ruta de la API.
const GRADOS_API = 'business/privado/grados.php';
// Constante para establecer el formulario de guardar.
const SAVE_FORM = document.getElementById('save-form');
// Constante para establecer el título de la modal.
const MODAL_TITLE = document.getElementById('modal-title');
// Constantes para establecer el contenido de la tabla.
const TBODY_ROWS = document.getElementById('tbody-rows');
const RECORDS = document.getElementById('records');
//constante para el formulario detalles
const FORM_DETALLES = document.getElementById('detalle_form');
//constante para la tabla de detalles
const DETALLES_ROWS = document.getElementById('detalle-rows');
// Constante tipo objeto para establecer las opciones del componente Modal.

// Método manejador de eventos para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
  // Llamada a la función para llenar la tabla con los registros disponibles.
  fillTable();
});

//variable id_grado para uso
let id_grado = null;

// Método manejador de eventos para cuando se envía el formulario de guardar.
SAVE_FORM.addEventListener('submit', async (event) => {
  // Se evita recargar la página web después de enviar el formulario.
  event.preventDefault();
  // Se verifica la acción a realizar.
  (document.getElementById('id_grado').value) ? action = 'update' : action = 'create';
  // Constante tipo objeto con los datos del formulario.
  const FORM = new FormData(SAVE_FORM);
  // Petición para guardar los datos del formulario.
  const JSON = await dataFetch(GRADOS_API, action, FORM);
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
  RECORDS.textContent = '';
  // Se verifica la acción a realizar.
  (form) ? action = 'search' : action = 'readAll';
  // Petición para obtener los registros disponibles.
  const JSON = await dataFetch(GRADOS_API, action, form);
  // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
  if (JSON.status) {
    // Se recorre el conjunto de registros fila por fila.
    JSON.dataset.forEach(row => {
      // Se crean y concatenan las filas de la tabla con los datos de cada registro.
      TBODY_ROWS.innerHTML += `
                <tr>
                <td>${row.id_grado}</td>
                    <td>${row.grado}</td>
                    <td>
                    <button onclick="openUpdate(${row.id_grado})"  type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Actualizar
                    </button>
                    <button onclick="openDetalle(${row.id_grado})"  type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalDetalle">
                    Ver Asignaturas
                    </button>
                    <button onclick="openDelete(${row.id_grado})" type="button" class="btn btn-danger">Eliminar</button>
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
  FORM.append('id_grado', id);
  // Petición para obtener los datos del registro solicitado.
  const JSON = await dataFetch(GRADOS_API, 'readOne', FORM);
  // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
  if (JSON.status) {
    SAVE_FORM.reset();
    // Se inicializan los campos del formulario.
    document.getElementById('id_grado').value = JSON.dataset.id_grado;
    document.getElementById('grado').value = JSON.dataset.grado;
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
  const RESPONSE = await confirmAction('¿Desea eliminar el grado de forma permanente?');
  // Se verifica la respuesta del mensaje.
  if (RESPONSE) {
    // Se define una constante tipo objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('id_grado', id);
    // Petición para eliminar el registro seleccionado.
    const JSON = await dataFetch(GRADOS_API, 'delete', FORM);
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

//--------------------------Detalles-----------------------------
async function openDetalle(id) {
  id_grado = id;
  //se llena el select con las asginaturas
  fillSelect(GRADOS_API, 'readAsignaturas', 'detalle', 'Asignaturas');
  DETALLES_ROWS.innerHTML = "";
  //se declara objeto del formulario
  const FORM = new FormData();
  //se declaran los parametros
  FORM.append('id', id);
  //se llama a la api
  const JSON = await dataFetch(GRADOS_API, 'readDetalle', FORM);
  if (JSON.status) {
    // Se recorre el conjunto de registros fila por fila.
    JSON.dataset.forEach(row => {
      // Se crean y concatenan las filas de la tabla con los datos de cada registro.
      DETALLES_ROWS.innerHTML += `
      <tr>
      <td>${row.asignatura}</td>
      <td><button type="button" class="btn btn-danger" onclick="EliminarDetalle(${row.id_detalle_asignatura_empleado})">Eliminar</button></td>
    </tr>
        `;
    });
  } else {
    sweetAlert(4, JSON.exception, true);
  }
}

FORM_DETALLES.addEventListener('submit', async (event) => {
  event.preventDefault();
  //se valida si el select tiene un valor seleccionado
  if(document.getElementById('detalle').value == 'Asignaturas'){
    sweetAlert(4, "Por complete todos los campos solicitados", true);
  }else{
    //se declara constante del formulario
    const FORM = new FormData(FORM_DETALLES);
    //se añade el id_grado como parametro
    FORM.append('id', id_grado);
    //se llama la API
    const JSON = await dataFetch(GRADOS_API, 'createDetalle', FORM);
    if (JSON.status) {
      // Se carga nuevamente la tabla para visualizar los cambios.
      openDetalle(id_grado);
      // Se muestra un mnsaje de éxito.
      sweetAlert(1, JSON.message, true);
    } else {
      sweetAlert(2, JSON.exception, false);
    }
  }
});

//eliminar detalle
async function EliminarDetalle(id) {
  // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
  const RESPONSE = await confirmAction('¿Desea eliminar este detalle de forma permanente?');
  // Se verifica la respuesta del mensaje.
  if (RESPONSE) {
    // Se define una constante tipo objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('id_detalle', id);
    // Petición para eliminar el registro seleccionado.
    const JSON = await dataFetch(GRADOS_API, 'deleteDetalle', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
      // Se carga nuevamente la tabla para visualizar los cambios.
      openDetalle(id_grado);
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
