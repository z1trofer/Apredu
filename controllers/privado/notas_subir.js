
// Obtener los parámetros de consulta de la URL
var datos = new URLSearchParams(window.location.search);

// Obtener el valor del parámetro 'parametro1'


document.addEventListener('DOMContentLoaded', async () => {
    // Petición para consultar los usuarios registrados.
    CargarNotas();
});

async function CargarNotas(){
    const JSON = await dataFetch(USER_API, 'getSession');
    if(JSON.status){
        document.getElementById('docenteNota').innerHTML = datos.get('docente');
        document.getElementById('gradoNota').innerHTML = datos.get('grado');
        document.getElementById('trimestreNota').innerHTML = datos.get('trimestre');
        document.getElementById('asignaturaNota').innerHTML = datos.get('materia');
    }

}