//Declaración API notas
const NOTAS_API = 'business/privado/notas.php';
// Obtener los parámetros de consulta de la URL
var datos = new URLSearchParams(window.location.search);

document.addEventListener('DOMContentLoaded', async () => {
    // Petición para consultar los usuarios registrados.
    CargarNotas();
    CargarActividades();
});

async function CargarNotas(){
    const JSON = await dataFetch(USER_API, 'getSession');
    if(JSON.status){
        document.getElementById('docenteNota').innerHTML = datos.get('docente');
        document.getElementById('gradoNota').innerHTML = datos.get('grado');
        document.getElementById('trimestreNota').innerHTML = datos.get('trimestre');
        document.getElementById('asignaturaNota').innerHTML = datos.get('materia');
    }
};

async function CargarActividades(){
    //declarando formulario

    const FORM = new FormData();
    //append valores formulario
    debugger
    FORM.append('asignatura', datos.get('asignatura'));
    console.log(datos.get('asignatura'));
    FORM.append('trimestre', datos.get('idtrimestre'));
    console.log(datos.get('idtrimestre'));
    FORM.append('grado', datos.get('idgrado'));
    console.log(datos.get('idgrado'));

    const JSON = await dataFetch(NOTAS_API, 'ObtenerActividades', FORM);
    debugger
    if(JSON.status){
        JSON.dataset.forEach(row => {
            debugger
            //se llena el trimistre que esta habilitado para editar
                dropdown = document.getElementById('activiadadlist');
                dropdown.innerHTML += `
                <li><a class="dropdown-item" onclick="OpcionActividad('${row.id_actividad}', '${row.nombre_actividad}')">${row.nombre_actividad}</a></li>
                `

        }); 
    }else{
        console.log(JSON.exception);
    }
};

//funcion para cambiar el trimestre seleccionado
/*function OpcionTrimestre(id_actividadFun, actividadFun) {
    id_actividad = id_actividad;
    trimestre = trimestreFun;
    document.getElementById('TrimestreSelect').innerHTML = trimestre;
};*/