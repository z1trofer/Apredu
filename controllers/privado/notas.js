//-----------------NOTAS-----------------------
/*
    - falta modo usuario administrador(director)
*/

//Declaración API notas
const NOTAS_API = 'business/privado/notas.php';

const CONT_MATERIAS = document.getElementById('cont_materias');
//cantante para obtener fechas
const DATE = new Date();
//se guarda el año en una constante
const ANIO = DATE.getFullYear();

//variable definir trimestre
let id_trimestre = null;
let trimestre = null;


/*
console.log(
    DATE.getHours(),
    DATE.toLocaleString('es-SV', {
      timeZone: 'America/El_Salvador',
    }),
    DATE.toLocaleTimeString('de-DE', {
        timeZone: 'Europe/Berlin',
        
      })+' berlin',
      DATE.getFullYear(),
      DATE.getHours()
  ); // 👉️ "1/15/2022, 11:54:44 PM"
*/

async function CargarTrimestres() {
    //se instancia un formulario
    const FORM = new FormData();
    //se instancia el año en el formulario
    FORM.append('anio', ANIO);
    //se llama a la api
    const JSON = await dataFetch(NOTAS_API, 'ObtenerTrimestres', FORM);
    //se comprueba la respuesta de la api
    if (JSON.status) {
        //se declara el dropdown en una variable
        dropdown = document.getElementById('trimestres_drop');
        //se limpia el dropdown
        dropdown.innerHTML = '';

        //se llena el dropdown por cada fila
        JSON.dataset.forEach(row => {
            //se llena el trimistre que esta habilitado para editar
            if (row.estado == true) {
                debugger
                id_trimestre = row.id_trimestre;
                trimestre = row.trimestre;
                document.getElementById('TrimestreSelect').innerHTML = row.trimestre;
                dropdown.innerHTML += `
                <li><a class="dropdown-item" onclick="OpcionTrimestre('${row.trimestre}')">${row.trimestre}</a></li>
                `
            }

        });
    } else {
        console.log('error al cargar los trimestres');
    }
}

async function CargarAsignaturas(){
    let materia = null;
    //se coloca el año respectivo
    document.getElementById('aniooo').innerHTML = 'año ' + ANIO;
    // Petición para consultar los usuarios registrados.
    const JSON = await dataFetch(NOTAS_API, 'ObtenerMateriasDocente');
    // Se comprueba si existe una sesión, de lo contrario se sigue con el flujo normal.
    if (JSON.status) {
        //se cargan los trimestres segun el trimestre actual habilitado

        //añadiendo nombre del docente
        document.getElementById('docenteNombre').innerHTML = JSON.dataset[1].nombre;
        //se asigna el docente a la variable docente
        //se vacia el contenedor de las asignaturas
        CONT_MATERIAS.innerHTML = '';
        //se llena el contenedor con los datos
        JSON.dataset.forEach(row => {
            //se compara la variable materia con el id_asignatura de los datos
            if (row.id_asignatura == materia) {
                debugger
                //si la respuesta es true solo se agrega el grado a la asignatura ya ingresada
                document.getElementById('grados' + row.id_asignatura).innerHTML += `
                <li><a class="dropdown-item" href="notas_subir.html?asignatura=${row.id_asignatura}&docente=${row.nombre}&trimestre=${trimestre}&grado=${row.grado}&materia=${row.asignatura}">${row.grado}</a></li>

            `;
            } else {
                debugger
                // de lo contrario se agrega una nueva asignatura con el primer grado que esta tenga
                CONT_MATERIAS.innerHTML += `
                
            <div class="col">
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
                  <li><a class="dropdown-item" href="notas_subir.html?asignatura=${row.id_asignatura}&docente=${row.nombre}&trimestre=${trimestre}&grado=${row.grado}&materia=${row.asignatura}">${row.grado}</a></li>
                </ul>
              </div>
        </div>
            `;
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
        // Se direcciona a la página web de bienvenida.

    } else {
        // Se muestra el formulario para registrar el primer usuario.
        //sweetAlert(2, 'aaaaaa', false);
    }
 
}

document.addEventListener('DOMContentLoaded', async () => {


        CargarTrimestres();
        CargarAsignaturas();

});

//funcion para cambiar el trimestre seleccionado
function OpcionTrimestre(trimestreFun) {
    trimestre = trimestreFun;
    document.getElementById('TrimestreSelect').innerHTML = trimestre;
};