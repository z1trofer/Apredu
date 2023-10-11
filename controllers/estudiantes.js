// Constante para completar la ruta de la API.
const ESTUDIANTE_API = 'business/estudiantes.php';
// Constante para establecer el formulario de guardar para el estudiante.
const SAVE_FORM_E = document.getElementById('save-formE');
// Constante para establecer el formulario de guardar para el responsable.
const SEARCH_FORM = document.getElementById('search-form');
// Constantes para establecer el contenido de la tabla.
const SAVE_FORM_C = document.getElementById('save_formC');
const TBODY_ROWS = document.getElementById('tbody-rows');
const RECORDS = document.getElementById('records');

//variables para busqueda parametrizada
let id_grado = null;
let warning = null;

// Evitar recargar la pagina 
document.addEventListener('DOMContentLoaded', async () => {
    if (await validate() == true) {
        // Llamada a la función para llenar la tabla con los registros disponibles.
        fillTable();
        CargarGrados();
        const TODAY = new Date();
        let day = ('0' + TODAY.getDate()).slice(-2);
        var month = ('0' + (TODAY.getMonth() + 1)).slice(-2);
        let year = TODAY.getFullYear() - 4;
        let date = `${year}-${month}-${day}`;
        document.getElementById('nacimiento').max = date;
    } else {
        location.href = 'principal.html';
    }
});

//funcion para verificar que el usuario tiene acceso a la pagina
async function validate() {

    const JSON = await dataFetch(ESTUDIANTE_API, 'getVistaAutorizacion');

    if (JSON.status) {
        return true;
    } else {
        return false;
    }

}

//Funcion de fillSelect pero adaptada para la lista de grados
async function fillList(filename, action, list, selected = null) {
    const JSON = await dataFetch(filename, action);
    let content = '';
    if (JSON.status) {
        JSON.dataset.forEach(row => {
            value = Object.values(row)[0];
            text = Object.values(row)[1];
            if (value != selected) {
                content += `<li><a class="dropdown-item" onclick="opcionGrado('${value}', '${text}')">${text}</a></li>`;
            } else {
                content += `<li><a class="dropdown-item" onclick="opcionGrado('${value}', '${text}')" class="active">${text}</a></li>`;
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

//metodo para buscar estudiantes
SEARCH_FORM.addEventListener('submit', (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    //declaracion de formulario
    const FORM = new FormData(SEARCH_FORM);
    //se agrega el id grado al formulario
    FORM.append('grado', id_grado);
    //se carga la tabla nuevamente

    fillTable(FORM);
});

// Método manejador de eventos para cuando se envía el formulario de guardar.
SAVE_FORM_E.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se verifica la acción a realizar.
    (document.getElementById('id_estudiante').value) ? action = 'updateEstudiante' : action = 'CreateEstudiante';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM_E);
    
    // Petición para guardar los datos del formulario.
    const JSON = await dataFetch(ESTUDIANTE_API, action, FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        fillTable();
        SAVE_FORM_E.reset();
        sweetAlert(1, JSON.message, true);
        document.getElementById('cancelar').click();
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
    (form) ? action = 'FiltrosEstudiantes' : action = 'readAll';
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
                    
                    <td><button  onclick="openUpdate(${row.id_estudiante})" type="button" class="btn btn-info " data-mdb-toggle="modal"data-mdb-target="#ModalEstInfo"><i class="fa-solid fa-pencil"></i></button>
                    <button  onclick="openReportOne(${row.id_estudiante})" type="button" class="btn btn-primary "><i class="fab fa-elementor"></i></button></td>
                    <td><button  onclick="reportNotas(${row.id_estudiante})" type="button" class="btn btn-warning"><i class="far fa-clipboard"></i></button></td>
                    <td><button onclick="openDelete(${row.id_estudiante})" type="button" class="btn btn-danger"><i class="fa-sharp fa-solid fa-trash"></i></button></td>
                </tr>
            `;
        });
        RECORDS.textContent = JSON.message;
    } else {
        sweetAlert(4, JSON.exception, true);
    }
}

//Funcion que nos permite abrir el modal para actualizar y al mismo tiempo manda a llamar los datos del id seleccionado
async function openUpdate(id) {
    // Se define una constante tipo objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('id_estudiante', id);
    document.getElementById('warning').hidden = true;
    warning = true;
    // Petición para obtener los datos del registro solicitado.
    const JSON = await dataFetch(ESTUDIANTE_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {

        // Se restauran los elementos del formulario.
        SAVE_FORM_E.reset();
        // Se inicializan los campos del formulario.
        document.getElementById('id_estudiante').value = JSON.dataset.id_estudiante;
        document.getElementById('nombre_estudiante').value = JSON.dataset.nombre_estudiante;
        document.getElementById('apellido_estudiante').value = JSON.dataset.apellido_estudiante;
        document.getElementById('nacimiento').value = JSON.dataset.fecha_nacimiento;
        document.getElementById('direccion_estudiante').value = JSON.dataset.direccion;
        document.getElementById('nie').value = JSON.dataset.nie;
        fillSelect(ESTUDIANTE_API, 'readGrado', 'grado', 'Grados', JSON.dataset.id_grado);
        document.getElementById('grado').innerHTML = JSON.dataset.grado;
        document.getElementById('selectRes').innerHTML = `<option value="${JSON.dataset.id_responsable}">${JSON.dataset.nombreRes}</option>`;
        document.getElementById('parentesco').value = JSON.dataset.parentesco_responsable;
        if (JSON.dataset.estado == 1) {
            document.getElementById('estados').checked = true;
        } else {
            document.getElementById('estados').checked = false;
        }
        // Se actualizan los campos para que las etiquetas (labels) no queden sobre los datos.
        document.getElementById('cancelar').hidden = false;
    } else {
        sweetAlert(2, JSON.exception, false);
    }
}

//buscar Reponsables
document.getElementById('searchRes').addEventListener('change', async () => {
    data = document.getElementById('searchRes').value;
    const FORM = new FormData();
    FORM.append('param', document.getElementById('searchRes').value);
    fillSelect2(ESTUDIANTE_API, 'SearchResponsables', 'selectRes', data, null)
    /* const JSON = dataFetch(RESPONSABLES_API, 'SearchEstudiante', FORM);
     if(JSON.status){
         fillSelect
     }*/
});
function openCreate() {
    document.getElementById('warning').hidden = true;
    warning = false;
    // Se restauran los elementos del formulario.
    SAVE_FORM_E.reset();
    fillSelect(ESTUDIANTE_API, 'readGrado', 'grado', 'Grados');

}


/*
*   Función asíncrona para eliminar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
async function openDelete(id) {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Desea eliminar el estudiante de forma permanente?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define una constante tipo objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('id_estudiante', id);
        // Petición para eliminar el registro seleccionado.
        const JSON = await dataFetch(ESTUDIANTE_API, 'deleteEstudiante', FORM);
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

//función Cargar Grados
async function CargarGrados() {
    //se instancia un formulario
    //se instancia el año como parametro en el formulario
    //se llama a la API para obtener los trimestres del año respectivo
    const JSON = await dataFetch(ESTUDIANTE_API, 'readGrado');
    //se comprueba la respuesta de la api
    if (JSON.status) {
        
        //se declara el combobox de trimestres en la variable dropdown
        dropdown = document.getElementById('listGrados');
        //se limpia el dropdown para asegurarse que no haya ningun contenido
        dropdown.innerHTML = '';
        //se llena el dropdown mediante la respuesta de la api
        JSON.dataset.forEach(async row => {
            //el dropdown se llena con el trimestre que poseea el valor de true
            //se le asignan valores a las variables id_trimestre y trimestre para usarlos en posteriores consultas
            id_grado = row.id_grado;
            //trimestre = row.trimestre;
            //se asigna el nombre del trimestre en el boton
            document.getElementById('dropGrados').innerHTML = row.grado;
            //se llena el dropdown con el trimestre especifico
            dropdown.innerHTML += `
                <li><a class="dropdown-item" onclick="OpcionGrado('${row.id_grado}','${row.grado}')">${row.grado}</a></li>
                `
        });

    } else {
        //se envia un mensaje con el error respectivo
        sweetAlert(2, "Ocurrio un error al cargar los grados, por favor comuniquese con un administrador", false);
    }
};

//funcion para cambiar el trimestre seleccionado en el dropdown de trimestres
//parametros: id_trimestre y el nombre del trimestre
function OpcionGrado(id_gradoFun, gradoFun) {
    //se iguala el id_trimeste con el paramentro de la función y con trimestres respectivamente
    id_grado = id_gradoFun;

    //se designa el texto del boton como el trimestre seleccionado
    document.getElementById('dropGrados').innerHTML = gradoFun;
};

document.getElementById('grado').addEventListener('change', () => {
    if (warning == true) {
        document.getElementById('warning').hidden = false;
    }
})
/*
document.getElementById('buscar').addEventListener('onclick', async (event) => {

});*/

/*
// Método manejador de eventos para cuando se envía el formulario de guardar.
SAVE_FORM_C.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se verifica la acción a realizar.
    (document.getElementById('id_estudiante_ficha').value) ? action = 'createFicha' : action = 'createFicha';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM_C);
    // Petición para guardar los datos del formulario.
    const JSON = await dataFetch(ESTUDIANTE_API, action, FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {

        sweetAlert(1, JSON.message, true);
    } else {
        sweetAlert(2, JSON.exception, false);
    }

});
*/
/*
async function openFicha(id) {
    // Se define una constante tipo objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('id_estudiante', id);
    // Petición para obtener los datos del registro solicitado.
    const JSON = await dataFetch(ESTUDIANTE_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        // Se inicializan los campos del formulario.
        document.getElementById('id_estudiante_ficha').value = JSON.dataset.id_estudiante;
        document.getElementById('nombre_ficha').value = JSON.dataset.nombre_estudiante;
        document.getElementById('apellido_ficha').value = JSON.dataset.apellido_estudiante;
        document.getElementById('grado_ficha').value = JSON.dataset.grado;
        //se llama a la API para obtener los datos
        label = document.getElementById('nombre_empleado');
        //se llama a la API para obtener los datos
        const SESSION = await dataFetch(USER_API, 'getSession');
        //se verifica el id_cargo
        if (SESSION) {
            //se llena el label con el nombre del docente
            label.innerHTML = "Docente: " + SESSION.nombre;
            document.getElementById('id_empleado').value = SESSION.id_empleado;
        } else {
            //se deja el label vacio
            label.innerHTML = "ekis de";
        }
        // Se actualizan los campos para que las etiquetas (labels) no queden sobre los datos.
    } else {
        sweetAlert(2, JSON.exception, false);
    }
}
*/

//funcion para cargar el nombre del docente cuando sea un docente el que ha iniciado session
async function CargarNombreDocente() {
    //se declara el label en una varianle
    label = document.getElementById('nombre_empleado');
    //se llama a la API para obtener los datos
    const SESSION = await dataFetch(USER_API, 'getSession');
    //se verifica el id_cargo
    if (SESSION.id_cargo == 2) {
        //se llena el label con el nombre del docente
        label.innerHTML = "Docente: " + SESSION.nombre;
    } else {
        //se deja el label vacio
        label.innerHTML = " ";
    }
};
//--------------filtro


//REPORTE NOTAS
function reportNotas(id) {
    // Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
    const PATH = new URL(`${SERVER_URL}reports/notas.php`);
    // Se abre el reporte en una nueva pestaña del navegador web.
    PATH.searchParams.append('id', id);
    window.open(PATH.href);
}

function openReport() {
    // Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
    const PATH = new URL(`${SERVER_URL}reports/estudiantes_grado.php`);
    // Se abre el reporte en una nueva pestaña del navegador web.
    window.open(PATH.href);
}

function openReportOne(id) {
    // Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
    const PATH = new URL(`${SERVER_URL}reports/estudiante.php`);
    // Se abre el reporte en una nueva pestaña del navegador web.
    PATH.searchParams.append('id_estudiante', id);
    window.open(PATH.href);
}
