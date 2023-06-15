///


//Declaración API notas
const NOTAS_API = 'business/privado/notas.php';
//tabla
const ROWS_ACTIVIDADES = document.getElementById('rows_actividades');
// Obtener los parámetros de consulta de la URL
var datos = new URLSearchParams(window.location.search);

//arreglo para subir notas
let notas = [];
//variable id_actividad
let id_actividad = null;
let actividad = null;

document.addEventListener('DOMContentLoaded', async () => {
    // Petición para consultar los usuarios registrados.
    CargarNotasDetalles();
    CargarActividades();
});

async function CargarNotasDetalles(){
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
    debugger
    const FORM = new FormData();
    //append valores formulario
    FORM.append('asignatura', datos.get('asignatura'));
    console.log(datos.get('asignatura'));
    FORM.append('trimestre', datos.get('idtrimestre'));
    console.log(datos.get('idtrimestre'));
    FORM.append('grado', datos.get('idgrado'));
    console.log(datos.get('idgrado'));

    const JSON = await dataFetch(NOTAS_API, 'ObtenerActividades', FORM);
    if(JSON.status){
        JSON.dataset.forEach(row => {
            debugger
            //se llena el trimistre que esta habilitado para editar
                dropdown = document.getElementById('activiadadlist');
                dropdown.innerHTML += `

                <li><a class="dropdown-item" onclick="CargarNotas('${row.id_actividad}', '${row.nombre_actividad}')">${row.nombre_actividad}</a></li>
                `

        }); 
    }else{
        debugger
        console.log(JSON.exception);
    }
};

//funcion para cambiar el trimestre seleccionado
async function CargarNotas(id_actividadFun, actividadFun) {
    id_actividad = id_actividadFun;
    ROWS_ACTIVIDADES.innerHTML = ""
    //declarando formulario
    const FORM = new FormData();
    //añadiendo parametros al formulario
    FORM.append('actividad', id_actividadFun);
    document.getElementById('btnactividad').innerHTML = actividadFun;
    const JSON = await dataFetch(NOTAS_API, 'ObtenerActividad', FORM);
    if(JSON.status){
        document.getElementById('actvdetalles').hidden = false;
        document.getElementById('txtponderacion').innerHTML = JSON.dataset[0].ponderacion+"%";
        document.getElementById('txtdescripcion').innerHTML = JSON.dataset[0].descripcion;
        debugger
        JSON.dataset.forEach(row => {
            color = null;
            if(Number(row.nota)>6){
                nota = row.nota;
                color = "apro";
            } else if(row.nota == null){
                nota = "No Ingresada"
            }else{
                nota = row.nota;
                color = "repro";
            }
            debugger
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
        console.log(JSON.exception);
    }
};

function NotaUpdatePreparar(id_nota){
    debugger
    nota = document.getElementById('input'+id_nota).value
    notas.push([id_nota, nota]);
};

async function UpdateNotas(){
    debugger
    correcto = true;
    const RESPONSE = await confirmAction('¿Estas seguro de guardar los cambios?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        for (let i = 0; i < notas.length; i++) {
            const FORM = new FormData();
            FORM.append('id', notas[i][0]);
            FORM.append('nota', notas[i][1]);
            const JSON = await dataFetch(NOTAS_API, 'ActualizarNotas', FORM);
            if(JSON.status != 1){
                correcto = false;
            }
            
        }
        debugger
        notas = [];
        if(correcto == false){
            sweetAlert(2, "Algunos cambios no se guardaron ya que el campo no cumplica con el formato adecuado, por favor revise que el formato sea numeros del 1 al 10 con 2 decimales", false);

        }
        CargarNotas(id_actividad, actividad);
    }
    
}

