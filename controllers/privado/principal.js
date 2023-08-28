const GRADOS_API = 'business/privado/grados.php';
const NOTAS_API = 'business/privado/notas.php';
const ASIGNATURA_API = 'business/privado/asignaturas.php';
const CONDUCTA_API = 'business/privado/fichas.php';
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
    await llenarCMB();
    graficoBarrasSubCategorias();
    graficoPieMaterias();
    graficoBarrasNotas();
    graficoReportesConductas();
    graficoReproYApro();
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

async function llenarCMB(){
    await fillSelect(NOTAS_API, 'ObtenerTrimestresNoParam', 'trimestre_top','Todos');
    await fillSelect(NOTAS_API, 'ObtenerGrados', 'grado_top','Todos');
}

async function graficoBarrasNotas() {
     
    const FORM = new FormData();
    FORM.append('trimestre', document.getElementById('trimestre_top').value);
    FORM.append('grado', document.getElementById('grado_top').value);
    // Petición para obtener los datos del gráfico.
    const DATA = await dataFetch(NOTAS_API, 'topNotas', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
    if (DATA.status) {
        // Se declaran los arreglos para guardar los datos a graficar.
        let estudiantes = [];
        let notas = [];
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se agregan los datos a los arreglos.
            estudiantes.push(row.nombre);
            notas.push(row.promedio);
        });
        // Llamada a la función que genera y muestra un gráfico de barras. Se encuentra en el archivo components.js
        pieGraph('chart4', estudiantes, notas, 'Top 5 promedios', 'NOTAS');
    } else {
        document.getElementById('chart4').remove();
        console.log(DATA.exception);
    }
}

async function graficoReportesConductas() {
    // Petición para obtener los datos del gráfico.
    const DATA = await dataFetch(CONDUCTA_API, 'MasFichasConducta');
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
    if (DATA.status) {
        // Se declaran los arreglos para guardar los datos a graficar.
        let nombre_estudiante = [];
        let cantidad_ficha = [];
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se agregan los datos a los arreglos.
            nombre_estudiante.push(row.nombre_estudiante);
            cantidad_ficha.push(row.cantidad_ficha);
        });
        // Llamada a la función que genera y muestra un gráfico de barras. Se encuentra en el archivo components.js
        barGraphLineal('chart3', nombre_estudiante, cantidad_ficha, 'Top 3 de los estudiantes con más reportes de conducta', 'Top 3 de los estudiantes con más reportes de conducta');
    } else {
        document.getElementById('chart3').remove();
        console.log(DATA.exception);
    }
}

//grafico para obtener un listado de los estudiantes aprobados y reporbador
async function graficoReproYApro() {
    //se declara un arreglo con valores predefinidos
    let array = ['Aprobados', 'Reprobados'];
    //arrelgo para guardar las cantidades respectivas
    let cantidad = [];
    //loop para repetir el codigo 2 veces
    for (let index = 0; index <= 1; index++) {
        //se llena un formulario con los parametros respectivos
        const FORM = new FormData();
        FORM.append('trimestre', document.getElementById('trimestre_top').value);
        FORM.append('grado', document.getElementById('grado_top').value);
        //segun la repeticion del codigo se manda una condicion diferente para obtener los 
        //aprobados y reprobados
        if(index == 0){
            FORM.append('condicion', '>=');
        }else{
            FORM.append('condicion', '<');
        }
        //se manda a llamar a la consulta
        const DATA = await dataFetch(NOTAS_API, 'estudiantesAprobados', FORM);
        //se verifica la respuesta
        if (DATA.status) {
            //se sube la cantidad al arreglo declarado previamente
            cantidad.push(DATA.dataset.cantidad);
        }else {
        //document.getElementById('chart5').remove();
        console.log(DATA.exception);
        }
        
    };
    //se genera el grafico
    pieGraph('chart5', array, cantidad, 'Estudiante Aprobados', 'Estudiantes');
}   


//eventos para graficos parametrizados graficos
document.getElementById('trimestre_top').addEventListener('change', async (event) => {
     
    //se resetean los canvas de los graficos para dar lugar a unos nuevos
    document.getElementById('chartspace4').innerHTML = "<canvas id='chart4'></canvas>";
    document.getElementById('chartspace5').innerHTML = "<canvas id='chart5'></canvas>";
    //se recargan los graficos
    graficoBarrasNotas();
    graficoReproYApro();
});

document.getElementById('grado_top').addEventListener('change', async (event) => {
    //se resetean los canvas de los graficos para dar lugar a unos nuevos
    document.getElementById('chartspace4').innerHTML = "<canvas id='chart4'></canvas>";
    document.getElementById('chartspace5').innerHTML = "<canvas id='chart5'></canvas>";
        //se recargan los graficos
    graficoBarrasNotas();
    graficoReproYApro();
});
