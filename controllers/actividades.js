// Constantes para completar las rutas de la API.
const ACTIVIDADES_API = 'business/actividades.php';
const NOTAS_API = 'business/notas.php';
const TITULO_MODAL = document.getElementById('modal-title');
const TBODY_ROWS = document.getElementById('tbody');
const TBODY_ROWS_TIPO = document.getElementById('tbody-tipos');
const FORMULARIO = document.getElementById('save-form');
const FORM_TIPO = document.getElementById('form-tipos');
const SEARCH_FORM = document.getElementById('search');

//constante para obtener fechas
const DATE = new Date();
//se guarda el año en una constante
const ANIO = DATE.getFullYear();

//variables para busqueda parametrizada
let id_trimestre = null;
let id_grado = null;
let id_asignatura = null;

//Funcion para validar las vistas de cada permiso de usuario
async function validate() {
    const JSON = await dataFetch(ACTIVIDADES_API, 'getVistaAutorizacion');
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
    await CargarTrimestres();
    //fillTable();
    await CargarGrados();
    await cargarAsignaturas();
    await CargarNombreDocente();
    }
});

//funcion para cargar el nombre del docente cuando sea un docente el que ha iniciado session
async function CargarNombreDocente() {
    //se declara el label en una varianle
    label = document.getElementById('nombre_empleado');
    //se llama a la API para obtener los datos
    const SESSION = await dataFetch(USER_API, 'getSession');
    //se verifica el id_cargo
        //se llena el label con el nombre del docente
        label.innerHTML = "Empleado: " + SESSION.empleado + ", " + SESSION.tipo;
        //se deja el label vacio
};

//función del evento submit del formulario para agregar/guardar los datos de una actividad
FORMULARIO.addEventListener('submit', async (event) => {
    //se evita la ejecución del evento default
    event.preventDefault();
    //se verifica si hay un id para determinar la accion
    (document.getElementById('id').value) ? action = 'update' : action = 'create';
    //se declara una constante con el formulario
    const FORM = new FormData(FORMULARIO);
    //se llama al servidor con la acción correspondiente
    const JSON = await dataFetch(ACTIVIDADES_API, action, FORM);
    if (JSON.status) {
        // Se carga nuevamente la tabla para visualizar los cambios.
        FORMULARIO.reset();
        // Se muestra un mensaje de éxito.
        sweetAlert(1, JSON.message, true);
        document.getElementById('closeAct').click();
        //BusquedaParametrizada();
    } else {
        sweetAlert(2, JSON.exception, false);
    }
});

/*
* Función asíncrona para llenar la tabla con los registros disponibles.
* Parámetros: form (objeto opcional con los datos de búsqueda).
* Retorno: ninguno.
*/
async function fillTable(form) {
    // Se inicializa el contenido de la tabla.
    TBODY_ROWS.innerHTML = '';
    // Petición para obtener los registros disponibles.
    const JSON = await dataFetch(ACTIVIDADES_API, 'filtrosActividades', form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
        JSON.dataset.forEach(row => {
            // Se establece un icono para el estado del producto.
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TBODY_ROWS.innerHTML += `
            <tr>
                <th>${row.nombre_actividad}</th>
                <td>${row.tipo_actividad}</td>
                <td>${row.descripcion}</td>
                <td>${row.ponderacion}%</td>
                <td>${row.fecha_entrega}</td>
                <td><button tittle="actualizar información"  onclick="updateActividades(${row.id_actividad})" type="button" class="btn btn-info " data-mdb-toggle="modal"
                data-mdb-target="#ModalActividad"><i class="fa-solid fa-pencil"></i></button>
                <button tittle="Eliminar" onclick="DeleteActividades(${row.id_actividad})" type="button" class="btn btn-danger"><i class="fa-sharp fa-solid fa-trash"></i></button>
                </td>
                
            </tr>`
            ;
        });

        // RECORDS.textContent = JSON.message;

    } else {
        sweetAlert(4, JSON.exception, true);
    }
}

async function fillTipoActividades() {
    // Se inicializa el contenido de la tabla.
    TBODY_ROWS_TIPO.innerHTML = '';
    // Se verifica la acción a realizar.
    // Petición para obtener los registros disponibles.
    const JSON = await dataFetch(ACTIVIDADES_API, 'readTipoActividades');
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
        JSON.dataset.forEach(row => {
            // Se establece un icono para el estado del producto.
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TBODY_ROWS_TIPO.innerHTML += `
            <tr>
                <th>${row.id_tipo_actividad}</th>
                <td>${row.tipo_actividad}</td>
                <td><button tittle="actualizar información"  onclick="openUpdateTipoActividades(${row.id_tipo_actividad}, '${row.tipo_actividad}')" type="button" class="btn btn-info "><i class="fa-solid fa-pencil"></i></button>
                <button tittle="Eliminar" onclick="deleteTipoActividades(${row.id_tipo_actividad})" type="button" class="btn btn-danger"><i class="fa-sharp fa-solid fa-trash"></i></button>
                </td>
                
            </tr>`
            ;
        });

        // RECORDS.textContent = JSON.message;

    } else {
        sweetAlert(4, JSON.exception, true);
    }
}

//Agregar actividades
FORM_TIPO.addEventListener('submit', async (event) => {
    event.preventDefault();
    let JSON;
    const FORM = new FormData(FORM_TIPO);
     
    if(document.getElementById('id_tipo').value == ""){
        JSON = await dataFetch(ACTIVIDADES_API, 'addTipoActividad', FORM);

    }else{
        JSON = await dataFetch(ACTIVIDADES_API, 'updateTipoActividad', FORM);
    }
    if (JSON.status) {
        // Se carga nuevamente la tabla para visualizar los cambios.
        FORM_TIPO.reset();
        // Se muestra un mensaje de éxito.
        sweetAlert(1, JSON.message, true);
        fillTipoActividades();
        document.getElementById('submitTipo').innerHTML = "Agregar";
        document.getElementById('labelStatus').hidden = true;
        document.getElementById('resetForm').hidden = true;
    } else {
        sweetAlert(2, JSON.exception, false);
    }
})

//Eliminar las actividades
async function deleteTipoActividades(id){
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Estas seguro de eliminar este tipo de actividad? Solo podrás hacerlo si no hay actividades con este tipo de actividad y se borrará de manera permanente');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define una constante tipo objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('id_tipo', id);
        // Petición para eliminar el registro seleccionado.
        const JSON = await dataFetch(ACTIVIDADES_API, 'deleteTipoActividad', FORM);
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (JSON.status) {
            // Se carga nuevamente la tabla para visualizar los cambios.
            fillTipoActividades();
            // Se muestra un mensaje de éxito.
            sweetAlert(1, JSON.message, true);
        } else {
            sweetAlert(2, JSON.exception, false);
        }
    }
}

//Se reinician valores del Forms
function resetTipoActividades(){
    FORM_TIPO.reset();
    document.getElementById('labelStatus').hidden = true;
    document.getElementById('submitTipo').innerHTML = "Agregar";
    document.getElementById('resetForm').hidden = true;
}

//Funcion para llenar los campos al actualizar
function openUpdateTipoActividades(id, tipo){
    document.getElementById('id_tipo').value = id;
    document.getElementById('tipo_actividad').value = tipo;
    document.getElementById('labelStatus').hidden = false;
    document.getElementById('labelStatus').innerHTML = "Actualizando: "+tipo;
    document.getElementById('submitTipo').innerHTML = "Guardar";
    document.getElementById('resetForm').hidden = false;
}

//Filtrar actividades
document.getElementById('btnTipoActividades').addEventListener('click', async () => {
    fillTipoActividades();
})

//Insertar actividades 
function createActividades() {
    FORMULARIO.reset();
    TITULO_MODAL.textContent = 'Asignar una nueva actividad';
     
    fillSelect(ACTIVIDADES_API, 'readTipoActividades', 'tipo_actividad', 'Seleccione un tipo de actividad');
    fillSelect(ACTIVIDADES_API, 'readDetalle', 'detalle', 'Seleccione una asignación');
    fillSelect(ACTIVIDADES_API, 'readTrimestre', 'trimestre', 'Seleccione un trimestre');
    document.getElementById('detalle').disabled = false;
    document.getElementById('trimestre').disabled = false;
}

//Actualizar los datos de la tabla y el registro
async function updateActividades(id_actividad) {
    FORMULARIO.reset();
    const FORM = new FormData();
    FORM.append('id_actividad', id_actividad);
    const JSON = await dataFetch(ACTIVIDADES_API, 'readOne', FORM);
    if (JSON.status) {
        TITULO_MODAL.textContent = 'Modificar actividad asignada';
        document.getElementById('id').value = JSON.dataset.id_actividad;
        document.getElementById('nombre').value = JSON.dataset.nombre_actividad;
        document.getElementById('ponderacion').value = JSON.dataset.ponderacion;
        document.getElementById('fecha_entrega').value = JSON.dataset.fecha_entrega;
        document.getElementById('descripcion').value = JSON.dataset.descripcion;
        fillSelect(ACTIVIDADES_API, 'readTipoActividades', 'tipo_actividad', 'Seleccione un tipo de actividad', JSON.dataset.id_tipo_actividad);
        fillSelect(ACTIVIDADES_API, 'readDetalle', 'detalle', 'Seleccione una asignación', JSON.dataset.id_detalle_asignatura_empleado);
        fillSelect(ACTIVIDADES_API, 'readTrimestre', 'trimestre', 'Seleccione un trimestre', JSON.dataset.id_trimestre);
        fillSelect(ACTIVIDADES_API, 'readAll', 'nombre', JSON.dataset.id_actividad);
        document.getElementById('detalle').disabled = true;
        document.getElementById('trimestre').disabled = true;
    }
}

//Eliminar actividades por medio del id
async function DeleteActividades(id_actividad) {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Desea eliminar la actividad de forma permanente?, ADVERTENCIA: si lo haces, eliminarás todas las notas asociadas a esta.');
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
            BusquedaParametrizada();
            // Se muestra un mensaje de éxito.
            sweetAlert(1, JSON.message, true);
        } else {
            sweetAlert(2, JSON.exception, false);
        }
    }
}

//----------------------------------------Filtros---------------------------------
//función Cargar Trimestres
async function CargarTrimestres() {
    //se instancia un formulario
    const FORM = new FormData();
    const SESSION = await dataFetch(USER_API, 'getSession');
    //se instancia el año como parametro en el formulario
    FORM.append('anio', ANIO);
    //se llama a la API para obtener los trimestres del año respectivo
    const JSON = await dataFetch(ACTIVIDADES_API, 'readTrimestre', FORM);
    //se comprueba la respuesta de la api
    if (JSON.status) {
         
        document.getElementById('titulo_anio').innerHTML = `Año lectivo: ${JSON.dataset[0].anio}, ${JSON.dataset[0].trimestre} `;
        //se declara el combobox de trimestres en la variable dropdown
        dropdown = document.getElementById('listTrimestre');
        //se limpia el dropdown para asegurarse que no haya ningun contenido
        dropdown.innerHTML = '';
        //se llena el dropdown mediante la respuesta de la api
        JSON.dataset.forEach(async row => {
             
            //el dropdown se llena con el trimestre que poseea el valor de true
            //se le asignan valores a las variables id_trimestre y trimestre para usarlos en posteriores consultas
             
            //if (row.estado == true || SESSION.id_cargo != 2) {
                id_trimestre = row.id_trimestre;
                //trimestre = row.trimestre;
                //se asigna el nombre del trimestre en el boton
                document.getElementById('dropTrimestre').innerHTML = row.trimestre;
                //se llena el dropdown con el trimestre especifico
                dropdown.innerHTML += `
                <li><a class="dropdown-item" onclick="opcionTrimestre('${row.id_trimestre}','${row.trimestre}')">${row.trimestre}</a></li>
              `
            //}
        });
    } else {
        //se envia un mensaje con el error respectivo
        sweetAlert(2, JSON.exception, false);
    }
};

//funcion para cambiar el trimestre seleccionado en el dropdown de trimestres
//parametros: id_trimestre y el nombre del trimestre
function opcionTrimestre(id_trimestreFun, trimestreFun) {

    //se iguala el id_trimeste con el paramentro de la función y con trimestres respectivamente
    id_trimestre = id_trimestreFun;
    //se designa el texto del boton como el trimestre seleccionado
    document.getElementById('dropTrimestre').innerHTML = trimestreFun;
};

//función Cargar Grados
async function CargarGrados() {
    //se instancia un formulario
    const FORM = new FormData();
    //se instancia el año como parametro en el formulario
    FORM.append('id_grado', id_grado);
    //se llama a la API para obtener los trimestres del año respectivo
    const JSON = await dataFetch(ACTIVIDADES_API, 'readGrados', FORM);
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
            document.getElementById('dropGrado').innerHTML = row.grado;
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
    document.getElementById('dropGrado').innerHTML = gradoFun;
    cargarAsignaturas();
};
/*
document.getElementById('buscar').addEventListener('onclick', async (event) => {
     

});*/

//función Cargar Grados
async function cargarAsignaturas() {
    //se instancia un formulario
    const FORM = new FormData();
    //se instancia el año como parametro en el formulario
    FORM.append('id_grado', id_grado);
    FORM.append('id_asignatura', id_asignatura);
    //se llama a la API para obtener los trimestres del año respectivo
    const JSON = await dataFetch(ACTIVIDADES_API, 'readAsignaturas', FORM);
    //se comprueba la respuesta de la api
    if (JSON.status) {
        //se declara el combobox de trimestres en la variable dropdown
        dropdown = document.getElementById('listAsignatura');
        //se limpia el dropdown para asegurarse que no haya ningun contenido
        dropdown.innerHTML = '';
        //se llena el dropdown mediante la respuesta de la api
        JSON.dataset.forEach(async row => {
            //el dropdown se llena con el trimestre que poseea el valor de true
            //se le asignan valores a las variables id_trimestre y trimestre para usarlos en posteriores consultas
            id_asignatura = row.id_asignatura;
            //trimestre = row.trimestre;
            //se asigna el nombre del trimestre en el boton
            document.getElementById('dropAsignatura').innerHTML = row.asignatura;
            //se llena el dropdown con el trimestre especifico
            dropdown.innerHTML += `
                <li><a class="dropdown-item" onclick="OpcionAsignatura('${row.id_asignatura}','${row.asignatura}')">${row.asignatura}</a></li>
                `
        });
    } else {
        //se envia un mensaje con el error respectivo
        sweetAlert(3, "Ocurrio un error al cargar las asignaturas, Posiblemente se deba a que no hay asignaturas asignadas a este curso", false);
    }
};

//funcion para cambiar el trimestre seleccionado en el dropdown de trimestres
//parametros: id_trimestre y el nombre del trimestre
function OpcionAsignatura(id_asignaturaFun, asignaturaFun) {
    //se iguala el id_trimeste con el paramentro de la función y con trimestres respectivamente
    id_asignatura = id_asignaturaFun;
    //se designa el texto del boton como el trimestre seleccionado
    document.getElementById('dropAsignatura').innerHTML = asignaturaFun;
};





///
//----busqueda
async function BusquedaParametrizada() {
    const FORM = new FormData();
    FORM.append('trimestre', id_trimestre);
    FORM.append('grado', id_grado);
    FORM.append('asignatura', id_asignatura);
    fillTable(FORM);
}