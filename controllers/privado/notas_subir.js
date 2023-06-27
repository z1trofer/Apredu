
//Declaración API notas
const NOTAS_API = 'business/privado/notas.php';
//declaración tabla de actividades
const ROWS_ACTIVIDADES = document.getElementById('rows_actividades');
//declarando objeto para obtener los parámetros de consulta de la URL
var datos = new URLSearchParams(window.location.search);

//arreglo para subir notas
let notas = [];
//variables para consultas posteriores
let id_actividad = null;
let actividad = null;

//evento Content load para cuando se cargue la pagina
document.addEventListener('DOMContentLoaded', async () => {
    // Petición para cargar la funcion CargarNotasDetalles
    CargarNotasDetalles();
    //peticion para cargar la funcion CargarActividaddes
    CargarActividades();
});

//funcion para cargar los datos de las notas
async function CargarNotasDetalles(){
    //Se verificia que haya un session activada mediante la API
    const JSON = await dataFetch(USER_API, 'getSession');
    //se verifica la respuesta de la API
    if(JSON.status){
        //se llenan los labels con los datos enviados mediante la url
        document.getElementById('docenteNota').innerHTML = datos.get('docente');
        document.getElementById('gradoNota').innerHTML = datos.get('grado');
        document.getElementById('trimestreNota').innerHTML = datos.get('trimestre');
        document.getElementById('asignaturaNota').innerHTML = datos.get('materia');
    }
};

//funcion para cargar las actividades correspondiente a la asignatura, trimestre y grado asignado
async function CargarActividades(){
    //instanciando un formulario
    const FORM = new FormData();
    //cargando los parametros de asignatura, trimestre y grado al formulario
    FORM.append('asignatura', datos.get('asignatura'));
    console.log(datos.get('asignatura'));
    FORM.append('trimestre', datos.get('idtrimestre'));
    console.log(datos.get('idtrimestre'));
    FORM.append('grado', datos.get('idgrado'));
    console.log(datos.get('idgrado'));
    //se llama a la API para obtener las actividades correspondiente a las parametros en el formulario
    const JSON = await dataFetch(NOTAS_API, 'ObtenerActividades', FORM);
    //se verifica la respuesta de la API
    if(JSON.status){
        //se cargan las actividades en el combobox
        JSON.dataset.forEach(row => {
            //se declara una variable con el combobox de las actividades
                dropdown = document.getElementById('activiadadlist');
                //se carga el combobox con las actividades
                dropdown.innerHTML += `
                <li><a class="dropdown-item" onclick="CargarNotas('${row.id_actividad}', '${row.nombre_actividad}')">${row.nombre_actividad}</a></li>
                `
        }); 
    }else{
        //se envia un mensaje con el error respectivo
        sweetAlert(2, JSON.exception, false);

    }
};

//funcion para Cargar notas segun el trimestre seleccionado
async function CargarNotas(id_actividadFun, actividadFun) {
    //se declara la variable id_actividad con el paramentro de la funcion
    id_actividad = id_actividadFun;
    //se vacia la tabla de actividades
    ROWS_ACTIVIDADES.innerHTML = ""
    //Se instancia un formulario
    const FORM = new FormData();
    //Se añaden paramentros al formulario
    FORM.append('actividad', id_actividadFun);
    //se asigna como label en el boton el nombre de la actividad seleccionada
    document.getElementById('btnactividad').innerHTML = actividadFun;
    //se llama a la API para obtener las notas de la actividad en base a los parametros del formulario
    const JSON = await dataFetch(NOTAS_API, 'ObtenerActividad', FORM);
     
    //se verifica la respuesta
    if(JSON.status){
         
        //se Cargan los detalles de la actividad (ponderación y descripción)
        document.getElementById('actvdetalles').hidden = false;
        document.getElementById('txtponderacion').innerHTML = JSON.dataset[0].ponderacion+"%";
        document.getElementById('txtdescripcion').innerHTML = JSON.dataset[0].descripcion;
        //se cargan las rows de la tabla
        JSON.dataset.forEach(row => {
            //se declara la variable color
            color = null;
            //se verifica el valor de la nota
            if(Number(row.nota)>6){
                //de declara la variable nota con el valor de la row
                nota = row.nota;
                //se define color como apro de aprobado
                color = "apro";
            } else if(row.nota == null){
                //si no hay nota se define la variable nota como no ingresada
                nota = "No Ingresada"
            }else{
                //de lo contrario se toma la nota como reprobada
                nota = row.nota;
                color = "repro";
            }
            //se llena la row con los datos de la base
            ROWS_ACTIVIDADES.innerHTML += `
            <tr>
                <th scope="col">${row.n_lista}</th>
                <th scope="col">${row.apellido_estudiante}</th>
                <th scope="col">${row.nombre_estudiante}</th>
                <th scope="col"><input id="input${row.id_nota}" class="${color} notaIn" value="${nota}" onclick="value=''" onblur="NotaUpdatePreparar(${row.id_nota})"></th>
            </tr>
            `
        }); 
    }else{
        //se envia un mensaje con el error
        sweetAlert(2, JSON.exception, false);
    }
};

//funcion para guardar las notas a modificar parametro: id de la nota
function NotaUpdatePreparar(id_nota){
    //se declara el valor del input seleccionado en la variable ntoa
    nota = document.getElementById('input'+id_nota).value
    //se guarda el parametro junto con la nota en un arreglo
    notas.push([id_nota, nota]);
};

//funcion para guardar los cambios en la nota
async function UpdateNotas(){
    //variable para indicar el estado de la subida
    correcto = true;
    //Mensaje de confirmación de la accion
    const RESPONSE = await confirmAction('¿Estas seguro de guardar los cambios?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        //se declara un blucle para recorrer el arreglo
        for (let i = 0; i < notas.length; i++) {
            //se instancia un formulario
            const FORM = new FormData();
            //se insertan como parametros el id de la nota y la nota
            FORM.append('id', notas[i][0]);
            FORM.append('nota', notas[i][1]);
            //Se llama a la API para cambiar la nota con los parametros seleccionados
            const JSON = await dataFetch(NOTAS_API, 'ActualizarNotas', FORM);
            //se verifica la respuesta
            if(JSON.status != 1){
                //de ser falsa se cambia la variable a false indicando que no se logro subir todas las notas
                correcto = false;
            }
        }
        //se vacia el arreglo
        notas = [];
        //se verficia el estado de la subida
        if(correcto == false){
            //se notifica del error
            sweetAlert(2, "Algunos cambios no se guardaron ya que el campo no cumplica con el formato adecuado, por favor revise que el formato sea numeros del 1 al 10 con 2 decimales", false);
        }
        //se llama a la función Cargar notas para cargar nuevamente la tabla
        CargarNotas(id_actividad, actividad);
    }
    
}

