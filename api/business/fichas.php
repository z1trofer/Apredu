<?php
require_once('../entities/dto/fichas.php');
require_once('../entities/dto/permisos.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $fichas = new Fichas;
    $permisos = new Permisos;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null, 'dataset' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_empleado'])and Validator::validateSessionTime()) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'getVistaAutorizacion':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('view_fichas');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                } else {
                    $result['status'] = 1;
                }
                break;
            //se hace la consulta a la base por medio de parametros de la querie para llenado de la tabla
            case 'readAll':
                //se declaran los permisos necesarios para la accion
                $access = array('view_fichas');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif ($result['dataset'] = $fichas->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'readEmpleado':
                //se declaran los permisos necesarios para la accion
                $access = array('view_fichas');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif ($result['dataset'] = $fichas->readEmpleado()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay grados registrados';
                }
                break;
            case 'readOneEstudiante':
                //se declaran los permisos necesarios para la accion
                $access = array('view_fichas');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$fichas->setIdestudiante($_POST['id_estudiante'])) {
                    $result['exception'] = 'Estudiante incorrecto';
                } elseif ($result['dataset'] = $fichas->readOneestudiante()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Estudiante inexistente';
                }
                break;
            case 'create':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('edit_fichas');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$fichas->setIdestudiante($_POST['id_estudiante'])) {
                    $result['exception'] = 'Estudiante incorrecto';
                } elseif (!$data = $fichas->setdescripcion_ficha($_POST['descripcion'])) {
                    $result['exception'] = 'Descripción incorrecta';
                } elseif (!$data = $fichas->setid_empleado($_POST['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif ($fichas->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Ficha de conducta creada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'readAllFichas':
                //se declaran los permisos necesarios para la accion
                $access = array('view_fichas');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif ($result['dataset'] = $fichas->readAllFichas()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'readOneFichaXestudiante':
                //se declaran los permisos necesarios para la accion
                $access = array('view_fichas');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$fichas->setIdestudiante($_POST['id_estudiante'])) {
                    $result['exception'] = 'Estudiante incorrecto';
                } elseif ($result['dataset'] = $fichas->readOneFichaXestudiante()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay ninguna ficha de conducta aplicada';
                }
                break;
            case 'readOne':
                //se declaran los permisos necesarios para la accion
                $access = array('view_fichas');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$fichas->setid_ficha($_POST['id_ficha'])) {
                    $result['exception'] = 'Ficha incorrecta';
                } elseif ($result['dataset'] = $fichas->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Ficha inexistente';
                }
                break;
            case 'update':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('edit_fichas');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$fichas->setid_ficha($_POST['id_ficha'])) {
                    $result['exception'] = 'Asignatura incorrecta';
                } elseif (!$fichas->readOne()) {
                    $result['exception'] = 'Usuario inexistente';
                } elseif (!$fichas->setIdestudiante($_POST['id_estudiante'])) {
                    $result['exception'] = 'Estudiante incorrecto';
                } elseif (!$fichas->setdescripcion_ficha($_POST['descripcion'])) {
                    $result['exception'] = 'Descripción incorrecta';
                } elseif (!$fichas->setfecha_ficha($_POST['fecha'])) {
                    $result['exception'] = 'Fecha incorrecta';
                } elseif (!$fichas->setid_empleado($_POST['nombre_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif ($fichas->updateRow()) {
                    $result['status'] = 1;                  
                    $result['message'] = 'Ficha modificada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'MasFichasConducta':
                //se valida el parametro
                $_POST = Validator::validateForm($_POST);
                if (!$fichas->setid_grado($_POST['grado_conduct'])) {
                    $parametro = 'Todos';
                } else {
                    $parametro = $_POST['grado_conduct'];
                }
                //se declaran los permisos necesarios para la accion 
                $access = array('view_fichas');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif ($result['dataset'] = $fichas->EstudianteMasReportes($parametro)) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'No hay datos disponibles';
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
        // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
        header('content-type: application/json; charset=utf-8');
        // Se imprime el resultado en formato JSON y se retorna al controlador.
        print(json_encode($result));
    } else {
        print(json_encode('Acceso denegado'));
    }
} else {
    print(json_encode('Recurso no disponible'));
}
?>