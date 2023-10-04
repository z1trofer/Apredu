<?php
require_once('../entities/dto/actividades.php');
require_once('../entities/dto/permisos.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $Actividades_p = new Actividades;
    $permisos = new Permisos;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null, 'dataset' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    // Arreglo para el filtrado parametrizado
    $filtro = array('trimestre' => 0, 'grado' => 0, 'asignatura' => 0);
    if (isset($_SESSION['id_empleado'])and Validator::validateSessionTime()) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'getVistaAutorizacion':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('view_actividades');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                } else {
                    $result['status'] = 1;
                }
                break;
            case 'readAll':
                //se declaran los permisos necesarios para la accion
                $access = array('view_actividades');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif ($result['dataset'] = $Actividades_p->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'FiltrosActividades':
                $_POST = Validator::validateForm($_POST);
                $filtro['grado'] = $_POST['grado'];
                $filtro['asignatura'] = $_POST['asignatura'];
                $filtro['trimestre'] = $_POST['trimestre'];
                //se declaran los permisos necesarios para la accion
                $access = array('view_actividades');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif ($result['dataset'] = $Actividades_p->FiltrarActividades($filtro)) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;

            case 'readTipoActividades':
                //se declaran los permisos necesarios para la accion
                $access = array('view_actividades');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif ($result['dataset'] = $Actividades_p->readTipoActividades()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'readGrados':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('view_actividades');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif ($result['dataset'] = $Actividades_p->readGrados()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'readAsignaturas':
                //se declaran los permisos necesarios para la accion
                $access = array('view_actividades');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$Actividades_p->setid_grado($_POST['id_grado'])) {
                    $result['exception'] = 'Grado malo';
                } elseif ($result['dataset'] = $Actividades_p->readAsignaturas()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'readTrimestre':
                //se declaran los permisos necesarios para la accion
                $access = array('view_actividades');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción, view_actividades';
                    //se ejecuta la accion
                } elseif ($result['dataset'] = $Actividades_p->readTrimestres()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Error';
                }
                break;
            case 'readDetalle':
                //se declaran los permisos necesarios para la accion
                $access = array('view_actividades');
                $level = array('view_all_actividades');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif ($result['dataset'] = $Actividades_p->readDetalle_asignatura_grado($permisos->getPermissions(($level)))) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'search':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('view_actividades');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif ($_POST['search'] == '') {
                    $result['exception'] = 'Ingrese un valor';
                } elseif ($result['dataset'] = $Cliente_p->searchRows($_POST['search'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay coincidencias';
                }
                break;
            case 'create':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('edit_actividades');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$Actividades_p->setnombre_actividad($_POST['nombre'])) {
                    $result['exception'] = 'Nombre incorrecto';
                } elseif (!$Actividades_p->setponderacion($_POST['ponderacion'])) {
                    $result['exception'] = 'Ponderación incorrecta';
                } elseif (!$Actividades_p->setdescripcion($_POST['descripcion'])) {
                    $result['exception'] = 'Descripción incorrecta';
                } elseif (!isset($_POST['tipo_actividad'])) {
                    $result['exception'] = 'Seleccione un tipo de actividad';
                } elseif (!$Actividades_p->setid_tipo_actividad($_POST['tipo_actividad'])) {
                    $result['exception'] = 'Tipo de actividad incorrecto';
                } elseif (!isset($_POST['detalle'])) {
                    $result['exception'] = 'Seleccione un detalle';
                } elseif (!$Actividades_p->setid_detalle_asignatura_empleado($_POST['detalle'])) {
                    $result['exception'] = 'Tipo de asignación incorrecto';
                } elseif (!isset($_POST['trimestre'])) {
                    $result['exception'] = 'Seleccione un trimestre';
                } elseif (!$Actividades_p->setid_trimestre($_POST['trimestre'])) {
                    $result['exception'] = 'Trimestre incorrecto';
                } elseif (!$Actividades_p->setfecha_entrega($_POST['fecha_entrega'])) {
                    $result['exception'] = 'Fecha incorrecta';
                } elseif (!$Actividades_p->validatePonderacion(false)) {
                    $result['exception'] = 'La ponderación total dbe ser menor o igual a 100';
                } elseif ($Actividades_p->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Se ha creado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                    ;
                }
                break;
            case 'readOne':
                //se declaran los permisos necesarios para la accion
                $access = array('view_actividades');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$Actividades_p->setid_actividad($_POST['id_actividad'])) {
                    $result['exception'] = 'Actividad incorrecta';
                } elseif ($result['dataset'] = $Actividades_p->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Actividad inexistente';
                }
                break;
            case 'update':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('edit_actividades');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$Actividades_p->setid_actividad($_POST['id'])) {
                    $result['exception'] = 'id incorrecto';
                } elseif (!$data = $Actividades_p->readOne()) {
                    $result['exception'] = 'id inexistente';
                } elseif (!$Actividades_p->setnombre_actividad($_POST['nombre'])) {
                    $result['exception'] = 'Nombre incorrecto';
                } elseif (!$Actividades_p->setponderacion($_POST['ponderacion'])) {
                    $result['exception'] = 'Ponderación incorrecta';
                } elseif (!$Actividades_p->setdescripcion($_POST['descripcion'])) {
                    $result['exception'] = 'Descripción incorrecta';
                } elseif (!isset($_POST['tipo_actividad'])) {
                    $result['exception'] = 'Seleccione un tipo de actividad';
                } elseif (!$Actividades_p->setid_tipo_actividad($_POST['tipo_actividad'])) {
                    $result['exception'] = 'Tipo de actividad incorrecto';
                } elseif (!$Actividades_p->setfecha_entrega($_POST['fecha_entrega'])) {
                    $result['exception'] = 'Fecha incorrecta';
                } elseif (!$Actividades_p->validatePonderacion(true)) {
                    $result['exception'] = 'La ponderación total dbe ser menor o igual a 100';
                } elseif ($Actividades_p->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Se ha actualizado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'delete':
                //se declaran los permisos necesarios para la accion
                $access = array('edit_actividades', 'view_all_actividades');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$Actividades_p->setid_actividad($_POST['id_actividad'])) {
                    $result['exception'] = 'Actividad incorrecta';
                } elseif (!$data = $Actividades_p->readOne()) {
                    $result['exception'] = 'Actividad inexistente';
                } elseif ($Actividades_p->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Actividad eliminada correctamente';
                } else {
                    $result['exception'] = Database::getException();
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