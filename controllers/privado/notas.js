//-----------------NOTAS-----------------------
/*
    - falta modo usuario administrador(director)
*/

//Declaración API notas
const NOTAS_API = 'business/privado/notas.php';
//declaración de la sección donde apareceran las materias
const CONT_MATERIAS = document.getElementById('cont_materias');
//constante para obtener fechas
const DATE = new Date();
//se guarda el año en una constante
const ANIO = DATE.getFullYear();
//variables definir trimestre
let id_trimestre = null;
let trimestre = null;
//se declara constante con el tipo_usuario

//evento Content load para cuando se cargue la pagina
document.addEventListener('DOMContentLoaded', async () => {
    //función para cargar los Trimestres
    await CargarTrimestres();
    //Función para cargar las asignaturas 
    await CargarAsignaturas();

    graficoPieNotas()
});

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
        const SESSION = await dataFetch(USER_API, 'getSession');
        //se declara el combobox de trimestres en la variable dropdown
        dropdown = document.getElementById('trimestres_drop');
        //se limpia el dropdown para asegurarse que no haya ningun contenido
        dropdown.innerHTML = '';
        //se llena el dropdown mediante la respuesta de la api
        JSON.dataset.forEach(async row => {
            //el dropdown se llena con el trimestre que poseea el valor de true
            if (row.estado == true || SESSION.id_cargo != 2) {
                 
                //se le asignan valores a las variables id_trimestre y trimestre para usarlos en posteriores consultas
                id_trimestre = row.id_trimestre;
                trimestre = row.trimestre;
                //se asigna el nombre del trimestre en el boton
                document.getElementById('TrimestreSelect').innerHTML = row.trimestre;
                //se llena el dropdown con el trimestre especifico
                dropdown.innerHTML += `
                <li><a class="dropdown-item" onclick="OpcionTrimestre('${row.id_trimestre}','${row.trimestre}')">${row.trimestre}</a></li>
                `
            }
        });
    } else {
        //se envia un mensaje con el error respectivo
        sweetAlert(2, "Ocurrio un error al cargar los trimestres, por favor comuniquese con un administrador", false);
    }
}

async function CargarAsignaturas(){
    const SESSION = await dataFetch(USER_API, 'getSession');
    //se declara la variable materia
    let materia = null;
    //se carga el año actual en el label respectivo
    document.getElementById('aniooo').innerHTML = 'año ' + ANIO;
    //se declara la variable accion
    accion = null;
    //se copara el tipo de usuario para determinar la accion a realizar
    if(SESSION.id_cargo == 2){
        accion = 'ObtenerMateriasDocente';
    }else{
        accion = 'ObtenerMaterias';
    }
     
    //llamada a la API obtener las materias del docente logeado
    const JSON = await dataFetch(NOTAS_API, accion);
    //Se compara la respuesta de la api
    if (JSON.status) {
         
        //Se Carga el nombre del docente logeado en el label
        if(accion == 'ObtenerMateriasDocente'){
            document.getElementById('docenteNombre').innerHTML = JSON.dataset[1].nombre;
        }else{
            document.getElementById('docenteNombre').hidden = true;
        }
        //se vacia el contenedor de las asignaturas
        CONT_MATERIAS.innerHTML = '';
        //se llena el contenedor con los datos
        JSON.dataset.forEach(row => {
            //se compara la variable materia con el id_asignatura de los datos
            if (row.id_asignatura == materia) {
                //si la respuesta es true solo se agrega el grado a la asignatura ya ingresada junto con parametros para asignar las notas
                document.getElementById('grados' + row.id_asignatura).innerHTML += `
                <li><a class="dropdown-item" onclick="getIdTrimestre(${row.id_asignatura}, ${row.id_grado}, '${row.grado}', '${row.nombre}', '${row.asignatura}')">${row.grado}</a></li>
            `;
            } else {
                // de lo contrario se agrega una nueva asignatura con el primer grado que esta tenga
                CONT_MATERIAS.innerHTML += `
                
            <div class="col margin">
            <div class="btn-group">
                <button id="dropasig${row.id_asignatura}" 
                  class="btn btn-primary btn-lg dropdown-toggle"
                  type="button"
                  data-mdb-toggle="dropdown"
                  aria-expanded="false"
                >
                  ${row.asignatura}
                </button>
                <ul class="dropdown-menu" id="grados${row.id_asignatura}">
                  <li><a class="dropdown-item" onclick="getIdTrimestre(${row.id_asignatura}, ${row.id_grado}, '${row.grado}', '${row.nombre}', '${row.asignatura}')">${row.grado}</a></li>
                </ul>
              </div>
        </div>
            `;
            debugger
                //se obtiene el id del boton de la asignatura
                color = document.getElementById('dropasig' + row.id_asignatura);
                //se compara el id de la asignatura para asignar un color al boton
                switch (row.id_asignatura) {
                    case 1:
                        color.className = "btn btn-warning btn-lg dropdown-toggle";
                        break;
                    case 2:
                        color.className = "btn btn-success btn-lg dropdown-toggle";
                        break;
                    case 4:
                        color.className = "btn btn-secondary btn-lg dropdown-toggle";
                        break;
                    case 5:
                        color.className = "btn btn-dark btn-lg dropdown-toggle";
                        break;
                    case 6:
                        color.className = "btn btn-danger btn-lg dropdown-toggle";
                        break;
                    default:
                        break;
                }
                //se define la variable materia como el id_asignatura de la materia
                materia = row.id_asignatura;
            }
        });
    } else {
        //se manda un mensaje con el error respectivo
        sweetAlert(2, "Ha habido un problema al cargar las asignaturas", false);
    }
}

function getIdTrimestre(asginatura, id_grado, grado, docente, materia){
    location.href = "notas_subir.html?asignatura="+asginatura+"&idtrimestre="+id_trimestre+"&idgrado="+id_grado+"&grado="+grado+"&docente="+docente+"&trimestre="+trimestre+"&materia="+materia;
    //url = '?asignatura=${row.id_asignatura}&idtrimestre=${id_trimestre}&idgrado=${row.id_grado}&docente=${row.nombre}&trimestre=${trimestre}&grado=${row.grado}&materia=${row.asignatura}'
}

//funcion para cambiar el trimestre seleccionado en el dropdown de trimestres
//parametros: id_trimestre y el nombre del trimestre
function OpcionTrimestre(id_trimestreFun, trimestreFun) {
     
    //se iguala el id_trimeste con el paramentro de la función y con trimestres respectivamente
    id_trimestre = id_trimestreFun;
    trimestre = trimestreFun;
    //se designa el texto del boton como el trimestre seleccionado
    document.getElementById('TrimestreSelect').innerHTML = trimestre;
};

// funcion para reporte de productos por categoria
function openReport() {
    // Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
    const PATH = new URL(`${SERVER_URL}reports/dashboard/notas_subir.php`);
    // Se abre el reporte en una nueva pestaña del navegador web.
    window.open(PATH.href);
}

async function graficoPieNotas() {
    // Petición para obtener los datos del gráfico.
    const DATA = await dataFetch(NOTAS_API, 'notaGlobal');
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
    if (DATA.status) {
        // Se declaran los arreglos para guardar los datos a graficar.
        let nombre_estudiante = [];
        let promedio = [];
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se agregan los datos a los arreglos.
            nombre_estudiante.push(row.nombre_estudiante);
            promedio.push(row.promedio);
        });
        // Llamada a la función que genera y muestra un gráfico de barras. Se encuentra en el archivo components.js
        pieGraph('chartNotas', nombre_estudiante, promedio, 'Estudiantes con mayor promedio global', 'Top 3 estudiantes por mayores notas globales');
    } else {
        document.getElementById('chartNotas').remove();
        console.log(DATA.exception);
    }
}
