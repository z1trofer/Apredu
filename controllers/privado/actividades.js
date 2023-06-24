// Constantes para completar las rutas de la API.
const ACTIVIDADES_API = 'business/privado/actividades.php';
const NOTAS_API = 'business/privado/notas.php';
const titulo_modal = document.getElementById('modal-title');
const TBODY_ROWS = document.getElementById('TablaEm');
const FORMULARIO = document.getElementById('save-form');
const SEARCH_FORM = document.getElementById('search');

//constante para obtener fechas
const DATE = new Date();
//se guarda el año en una constante
const ANIO = DATE.getFullYear();

//variables para busqueda parametrizada
let id_trimestre = null;
let id_grado = null;
let id_asignatura = null;


// javascript se manda a llamar el id y en php el name

// Método manejador de eventos para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para llenar la tabla con los registros disponibles.
    fillTable();
    CargarTrimestres();
    CargarGrados();
    CargarAsignaturas();
  
});

SEARCH_FORM.addEventListener('submit', async (event) => {
    //metodo para evitar que se recargue la pagina
    event.preventDefault();
    //se valida si hay parametros de busqueda
    if (document.getElementById('dropTrimestre').selectedIndex == 0
        && document.getElementById('dropGrado').selectedIndex == 0
        && document.getElementById('dropAsignatura').selectedIndex == 0) {
        //si la respuesta es false se cargan los mangas sin ningun tipo de filtro
        fillTable();
    } else {
        //se declara el catalogo de mangas
        TBODY_ROWS.innerHTML = '';
        // Constante tipo objeto con los datos del formulario.
        const FORM = new FormData(SEARCH_FORM);
        // Petición para guardar los datos del formulario.
        const JSON = await dataFetch(ACTIVIDADES_API, 'FiltrosActividades', FORM);
        if (JSON.status) {
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
        } else {
            //en caso de error se manda la alerta correspondiente
            sweetAlert(2, JSON.exception, false);
        }
    }
});

FORMULARIO.addEventListener('submit', async (event) => {
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
    (form) ? action = 'FiltrosActividades' : action = 'readAll';
    // Petición para obtener los registros disponibles.
    // const JSON = await dataFetch(ACTIVIDADES_API, action, form);
    const JSON = await dataFetch(ACTIVIDADES_API, action,form);
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
    titulo_modal.textContent = 'Asignar una nueva actividad';
    fillSelect(ACTIVIDADES_API, 'readTipoActividades', 'tipo_actividad', 'Seleccione un tipo de actividad');
    fillSelect(ACTIVIDADES_API, 'readDetalle', 'detalle', 'Seleccione una asignación');
    fillSelect(ACTIVIDADES_API, 'readTrimestre', 'trimestre', 'Seleccione un trimestre');
}

async function updateActividades(id_actividad) {
    // FORMULARIO.reset();
    const FORM = new FormData();
    FORM.append('id_actividad', id_actividad);
    const JSON = await dataFetch(ACTIVIDADES_API, 'readOne', FORM);
    if (JSON.status) {
        titulo_modal.textContent = 'Modificar actividad asignada';
        document.getElementById('id').value = JSON.dataset.id_actividad;
        document.getElementById('nombre').value = JSON.dataset.nombre_actividad;
        document.getElementById('ponderacion').value = JSON.dataset.ponderacion;
        document.getElementById('fecha_entrega').value = JSON.dataset.fecha_entrega;
        document.getElementById('descripcion').value = JSON.dataset.descripcion;
        fillSelect(ACTIVIDADES_API, 'readTipoActividades', 'tipo_actividad','Seleccione un tipo de actividad' ,JSON.dataset.id_tipo_actividad);
        fillSelect(ACTIVIDADES_API, 'readDetalle', 'detalle','Seleccione una asignación' ,JSON.dataset.id_detalle_asignatura_empleado);
        fillSelect(ACTIVIDADES_API, 'readTrimestre', 'trimestre','Seleccione un trimestre' ,JSON.dataset.id_trimestre);
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

//----------------------------------------Filtros---------------------------------
//función Cargar Trimestres
async function CargarTrimestres() {
    //se instancia un formulario
    const FORM = new FormData();
    //se instancia el año como parametro en el formulario
    FORM.append('anio', ANIO);
    //se llama a la API para obtener los trimestres del año respectivo
    const JSON = await dataFetch(NOTAS_API, 'ObtenerTrimestres', FORM);
    //se comprueba la respuesta de la api
    if (JSON.status) {
        //se declara el combobox de trimestres en la variable dropdown
        dropdown = document.getElementById('listTrimestre');
        //se limpia el dropdown para asegurarse que no haya ningun contenido
        dropdown.innerHTML = '';
        //se llena el dropdown mediante la respuesta de la api
        JSON.dataset.forEach(async row => {
            //el dropdown se llena con el trimestre que poseea el valor de true
            //se le asignan valores a las variables id_trimestre y trimestre para usarlos en posteriores consultas
            id_trimestre = row.id_trimestre;
            //trimestre = row.trimestre;
            //se asigna el nombre del trimestre en el boton
            document.getElementById('dropTrimestre').innerHTML = row.trimestre;
            //se llena el dropdown con el trimestre especifico
            dropdown.innerHTML += `
                <li><a class="dropdown-item" onclick="OpcionTrimestre('${row.id_trimestre}','${row.trimestre}')">${row.trimestre}</a></li>
                `
        });
    } else {
        //se envia un mensaje con el error respectivo
        sweetAlert(2, "Ocurrio un error al cargar los trimestres, por favor comuniquese con un administrador", false);
    }
};

//funcion para cambiar el trimestre seleccionado en el dropdown de trimestres
//parametros: id_trimestre y el nombre del trimestre
function OpcionTrimestre(id_trimestreFun, trimestreFun) {

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
};
/*
document.getElementById('buscar').addEventListener('onclick', async (event) => {
    debugger

});*/

//función Cargar Grados
async function CargarAsignaturas() {
    //se instancia un formulario
    const FORM = new FormData();
    //se instancia el año como parametro en el formulario
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
        sweetAlert(2, "Ocurrio un error al cargar las asignaturas, por favor comuniquese con un administrador", false);
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