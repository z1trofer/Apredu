/*
*   Controlador de uso general en las páginas web del sitio privado.
*   Sirve para manejar las plantillas del encabezado y pie del documento.
*/

// Constante para completar la ruta de la API.
const USER_API = 'business/privado/usuarios.php';
// Constantes para obtener la etiqueta donde va el usuario
const NAVBAR = document.getElementById('navbar');
// Método manejador de eventos para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', async () => {
     
    // Petición para obtener en nombre del usuario que ha iniciado sesión.
    const JSON = await dataFetch(USER_API, 'getSession');
    // Se verifica si el usuario está autenticado, de lo contrario se envía a iniciar sesión.
    if (JSON.session) {
        // Se comprueba si existe un alias definido para el usuario, de lo contrario se muestra un mensaje con la excepción.
        if (JSON.status) {

            NAVBAR.innerHTML = `        
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
            <!-- Container wrapper -->
            <div class="container">
                <!-- Navbar brand -->
                <a class="img_navbar" href="principal.html">
                    <img src="../../recursos/logo.png" style="width: 9rem;" alt="">
                </a>

                <!-- Toggle button -->
                <button class="navbar-toggler" type="button" data-mdb-toggle="collapse"
                    data-mdb-target="#navbarButtonsExample" aria-controls="navbarButtonsExample" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarRightAlignExample">
                    <!-- Left links -->
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="principal.html">Dashboard</a>
                        </li>
                        <li class="nav-item dropdown " id="mantenimientos_list" hidden>
                            <a class="nav-link active dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                                data-mdb-toggle="dropdown" aria-expanded="false">
                                Mantenimientos
                            </a>
                            <ul class="dropdown-menu"  aria-labelledby="navbarDropdownMenuLink">
                                <li>
                                    <a class="dropdown-item" id="vista_grados" href="grados.html">Grados</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" id="vista_asignaturas" href="asignaturas.html">Asignaturas</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" id="vista_trimestres" href="trimestres.html">Trimestres escolares</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" id="vista_permisos" href="permisos.html">Permisos</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown" id="docentes_list" hidden>
                            <a class="nav-link active dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                                data-mdb-toggle="dropdown" aria-expanded="false">
                                Docentes
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li>
                                    <a class="dropdown-item doce" id="vista_empleados" href="empleados.html">Personal académico</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" id="vista_actividades" href="actividades.html">Actividades</a>
                                </li>
                                
                            </ul>
                        </li>
                        <li class="nav-item dropdown" id="estudiantes_list" hidden>
                            <a class="nav-link active dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                                data-mdb-toggle="dropdown" aria-expanded="false">
                                estudiantes
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li>
                                    <a class="dropdown-item" id="vista_estudiantes" href="estudiantes.html">Alumnos</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" id="vista_responsables" href="responsables.html">Responsables</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" id="vista_fichas" href="fichas.html">Fichas de conducta</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" id="vista_notas" href="notas_ingresar.html">Notas</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                        </li>
                    </ul>
                    <!-- Left links -->
                </div>
                <div class="dropdown">
                    <a class="dropdown-toggle d-flex align-items-center hidden-arrow" href="#"
                        id="navbarDropdownMenuAvatar" role="button" data-mdb-toggle="dropdown" aria-expanded="false">
                        <img src="../../recursos/iconos/usuario.png" class="rounded-circle" height="25"
                            alt="Black and White Portrait of a Man" loading="lazy" />
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar">
                        <li>
                            <a class="dropdown-item" id="userActive" class="nav-link active">${JSON.usuario}</a>
                                <a class="dropdown-item">cargo: ${JSON.tipo}</a>
                                <a class="dropdown-item" href="perfil.html"> Editar perfil</a>
                                <a class="dropdown-item" onclick="logOut()">Cerrar sesión</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Collapsible wrapper -->
            </div>
            <!-- Container wrapper -->
            </nav>`;
            
            //si el nivel de usuario es docente se ocultan los menus respectivos
            vistaPermisos();
            /*if(JSON.id_cargo == 2){
                menus = document.getElementsByClassName('doce');
                for (let i = 0; i < menus.length; i++) {
                    menus[i].hidden = true;
                    
                }
            }*/
            // Se inicializa el componente Dropdown para que funcione la lista desplegable en los menús.
        } else {
            sweetAlert(3, JSON.exception, false, 'index.html');
            JSON = await dataFetch(USER_API, 'logOut');
            location.href = 'index.html';
        }
    }
    else if (location.pathname == '/Apredu/vistas/privado/index.html') {
        // Se inicializa el componente Tooltip para que funcionen las sugerencias textuales.
    } else {
        location.href = 'index.html';
    }
    // Se comprueba si la página web es la principal, de lo contrario se direcciona a iniciar sesión
}
);

//funcion para filtrar el menu navbar en funcion de los permisos del usuario
async function vistaPermisos(){
    
    //se consulta los permisos del usuario
    const JSON = await dataFetch(USER_API, 'getPermisosVista');
    //se valida el resultado
    if(JSON.status){
        
        //se asigna el arreglo con los permisos a la variable atributos
        atributos = JSON.dataset;

        //mantenimientos
        if(atributos.view_grados == 0 && atributos.view_asignaturas == 0 && atributos.view_trimestres == 0 && atributos.edit_permisos == 0){
            document.getElementById('mantenimientos_list').hidden = true;
        }else{
            //se mouestra la lista
            document.getElementById('mantenimientos_list').hidden = false;
            //se declaran los elementos de la lista
            v_grados = document.getElementById('vista_grados');
            v_asignaturas = document.getElementById('vista_asignaturas');
            v_trimestres = document.getElementById('vista_trimestres');
            v_permisos = document.getElementById('vista_permisos');
            (atributos.view_grados == 1) ? v_grados.hidden = false : v_grados.hidden = true;
            (atributos.view_asignaturas == 1) ? v_asignaturas.hidden = false : v_asignaturas.hidden = true;
            (atributos.view_trimestres == 1) ? v_trimestres.hidden = false : v_trimestres.hidden = true;
            (atributos.edit_permisos == 1) ? v_permisos.hidden = false : v_permisos.hidden = true;
    
        }

        //docentes
        if(atributos.view_empleados == 0 && atributos.view_actividades == 0){
            document.getElementById('docentes_list').hidden = true;
        }else{
            //se mouestra la lista
            document.getElementById('docentes_list').hidden = false;
            //se declaran los elementos de la lista
            v_empleados = document.getElementById('vista_empleados');
            v_actividades = document.getElementById('vista_actividades');
            (atributos.view_empleados == 1) ? v_empleados.hidden = false : v_empleados.hidden = true;
            (atributos.view_actividades == 1) ? v_actividades.hidden = false : v_actividades.hidden = true;
    
        }

        //estudiantes
        if(atributos.view_estudiantes == 0 && atributos.view_responsables == 0 && atributos.view_fichas == 0 && atributos.view_notas == 0){
            document.getElementById('estudiantes_list').hidden = true;
        }else{
            
            //se mouestra la lista
            document.getElementById('estudiantes_list').hidden = false;
            //se declaran los elementos de la lista
            v_estudiantes = document.getElementById('vista_estudiantes');
            v_responsables = document.getElementById('vista_responsables');
            v_fichas = document.getElementById('vista_fichas');
            v_notas = document.getElementById('vista_notas');
            (atributos.view_estudiantes == 1) ? v_estudiantes.hidden = false : v_estudiantes.hidden = true;
            (atributos.view_responsables == 1) ? v_responsables.hidden = false : v_responsables.hidden = true;
            (atributos.view_fichas == 1) ? v_fichas.hidden = false : v_fichas.hidden = true;
            (atributos.view_notas == 1) ? v_notas.hidden = false : v_notas.hidden = true;
        }
    }else{
        sweetAlert(2, "Error, No se pudo obtener los permisos", false);
    }
    //se valida la seccion de mantenimientos
   

}