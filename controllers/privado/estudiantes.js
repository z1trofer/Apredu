// Constante para completar la ruta de la API.
const ESTUDIANTE_API = 'business/privado/estudiantes.php';
// Constante para establecer el formulario de guardar para el estudiante.
const SAVE_FORM_E = document.getElementById('save-formE');
// Constante para establecer el formulario de guardar para el responsable.
const SAVE_FORM_R = document.getElementById('save-formR');
// Constantes para establecer el contenido de la tabla.
const TBODY_ROWS = document.getElementById('tbody-rows');
const RECORDS = document.getElementById('records');

document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para llenar la tabla con los registros disponibles.
    fillTable();
    const TODAY = new Date();
    // Se declara e inicializa una variable para guardar el día en formato de 2 dígitos.
    let day = ('0' + TODAY.getDate()).slice(-2);
    // Se declara e inicializa una variable para guardar el mes en formato de 2 dígitos.
    var month = ('0' + (TODAY.getMonth() + 1)).slice(-2);
    // Se declara e inicializa una variable para guardar el año con la mayoría de edad.
    let year = TODAY.getFullYear() - 4;
    // Se declara e inicializa una variable para establecer el formato de la fecha.
    let date = `${year}-${month}-${day}`;
    // Se asigna la fecha como valor máximo en el campo del formulario.
    document.getElementById('fecha_estudiante').max = date;
});

//Funcion de fillSelect pero adaptada para la lista de grados
async function fillList(filename, action, list, selected = null) {
    const JSON = await dataFetch(filename, action);
    let content = '';
    if (JSON.status) {
        JSON.dataset.forEach(row => {
            value = Object.values(row)[0];
            text = Object.values(row)[1];
            if (value != selected) {
                content += `<li><a class="dropdown-item" onclick="opcionGrado('${value}')">${text}</a></li>`;
            } else {
                content += `<li><a class="dropdown-item" onclick="opcionGrado('${value}')" class="active">${text}</a></li>`;
            }
        });
    } else {
        content += '<li><a class="dropdown-item">No hay opciones disponibles</a></li>';
    }
    document.getElementById(list).innerHTML = content;
}

// función para cambiar el valor del botón del parentesco y asignarle un valor a un input oculto de parentesco_responsable
function opcionParentesco(parentesco) {
    document.getElementById('parentesco').innerHTML = parentesco;
    document.getElementById('parentesco_responsable').value = parentesco;
}

function opcionGrado(grado) {
    document.getElementById('grado').innerHTML = grado;
    document.getElementById('grados_estudiante').value = grado;
}

// Método manejador de eventos para cuando se envía el formulario de guardar.
SAVE_FORM_R.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se verifica la acción a realizar.
    (document.getElementById('id_responsable').value) ? action = 'update' : action = 'createResponsable';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM_R);
    // Petición para guardar los datos del formulario.
    const JSON = await dataFetch(ESTUDIANTE_API, action, FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        sweetAlert(1, JSON.message, true);
    } else {
        sweetAlert(2, JSON.exception, false);
    }
});


// Método manejador de eventos para cuando se envía el formulario de guardar.
SAVE_FORM_E.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se verifica la acción a realizar.
    (document.getElementById('id_estudiante').value) ? action = 'update' : action = 'CreateEstudiante';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM_E);
    // Petición para guardar los datos del formulario.
    const JSON = await dataFetch(ESTUDIANTE_API, action, FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
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
    const JSON = await dataFetch(ESTUDIANTE_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        // Se recorre el conjunto de registros fila por fila.
        JSON.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TBODY_ROWS.innerHTML += `  
                <tr>
                    <td>${row.apellido_estudiante}</td>
                    <td>${row.nombre_estudiante}</td>
                    <td>${row.grado}</td>
                    <td><button type="button" class="btn btn btn-floating btn-lg" data-mdb-toggle="modal" data-mdb-target="#myModal"><img src="../../recursos/iconos/conducta.png" alt=""></button></td>
                    <td><button type="button" class="btn btn btn-floating btn-lg" data-mdb-toggle="modal"data-mdb-target="#myModal2"><img src="../../recursos/iconos/notas.png" alt=""></button></td>
                    <td><button type="button" class="btn btn btn-floating btn-lg" data-mdb-toggle="modal"data-mdb-target="#ModalEstInfo2"><img src="../../recursos/iconos/informacion.png" alt=""></button></td>
                </tr>
            `;
        });
        RECORDS.textContent = JSON.message;
    } else {
        sweetAlert(4, JSON.exception, true);
    }
}

function openCreate() {
    // Se restauran los elementos del formulario.
    SAVE_FORM_E.reset();
    SAVE_FORM_R.reset();
    fillList(ESTUDIANTE_API, 'readGrado','lectura');
}
