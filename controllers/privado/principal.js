const GRADOS_API = 'business/privado/grados.php';
const ASIGNATURA_API = 'business/privado/asignaturas.php';
//llamar formulario log in
let tipoUsuario = null;

document.addEventListener('DOMContentLoaded', async () => {
    // Petición para consultar los usuarios registrados.
    const JSON = await dataFetch(USER_API, 'getSession');
     
    //agregarndo valor a tipo_usuario
    tipoUsuario = JSON.id_cargo;
    //filtrar el menu de usuario segun los permisos respectivos
    validarPermisos();
    // Se comprueba si existe una sesión, de lo contrario se sigue con el flujo normal.
    if (JSON.tipo) {
        // Se direcciona a la página web de bienvenida.
        document.getElementById('userM').innerHTML = `Sesión iniciada como: ${JSON.usuario}`
    } else {
        // Se muestra el formulario para registrar el primer usuario.
        //sweetAlert(2, 'aaaaaa', false);
    }
    graficoBarrasSubCategorias()
    graficoPieMaterias()
});

//funcion para controlar las opciones disponibles segun el nivel de usuario
function validarPermisos(){
     
    switch (tipoUsuario) {
        //caso nivel de usuario docente/profesor
        case "2":
            //se esconden los contenidos que no correspondan
            document.getElementById('onlydocentes').hidden = true;
            //se declara en la variable un arreglo con los elementos que tengan la clase "doc"
            things = document.getElementsByClassName('doc');
            //se inicia un bucle que recorre el arreglo
            for (let index = 0; index < things.length; index++) {
                //se esconde el elemento que no deba ver el docente
                things[index].hidden = true;
              }
            break;
        default:
            break;
    }
}

/*
*   Función asíncrona para mostrar en un gráfico de barras la cantidad de productos por categoría.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
async function graficoBarrasSubCategorias() {
    // Petición para obtener los datos del gráfico.
    const DATA = await dataFetch(GRADOS_API, 'cantidadEstudiantesXgrado');
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
    if (DATA.status) {
        // Se declaran los arreglos para guardar los datos a graficar.
        let grados = [];
        let cantidad_estudiantes = [];
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se agregan los datos a los arreglos.
            grados.push(row.grado);
            cantidad_estudiantes.push(row.cantidad_estudiantes);
        });
        // Llamada a la función que genera y muestra un gráfico de barras. Se encuentra en el archivo components.js
        barGraph('chart1', grados, cantidad_estudiantes, 'Estudiantes', 'Top 3 grados con más estudiantes');
    } else {
        document.getElementById('chart1').remove();
        console.log(DATA.exception);
    }
}

async function graficoPieMaterias() {
    // Petición para obtener los datos del gráfico.
    const DATA = await dataFetch(ASIGNATURA_API, 'MateriasDocentes');
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
    if (DATA.status) {
        // Se declaran los arreglos para guardar los datos a graficar.
        let nombre_empleado = [];
        let cantidad_materias_asignadas = [];
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se agregan los datos a los arreglos.
            nombre_empleado.push(row.nombre_empleado);
            cantidad_materias_asignadas.push(row.cantidad_materias_asignadas);
        });
        // Llamada a la función que genera y muestra un gráfico de barras. Se encuentra en el archivo components.js
        pieGraph('chart2', nombre_empleado, cantidad_materias_asignadas, 'Top 5 docentes con mas materias', 'Top 5 docentes con mas materias');
    } else {
        document.getElementById('chart2').remove();
        console.log(DATA.exception);
    }
}
