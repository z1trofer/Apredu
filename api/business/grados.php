<?php
require_once('../entities/dto/grados.php');
require_once('../entities/dto/permisos.php');
// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $grados = new Grados;
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
                $access = array('view_grados');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                } else {
                    $result['status'] = 1;
                }
                break;
            case 'readAll':
                //se hace la consulta a la base por medio de parametros de la querie para llenado de la tabla
                //se declaran los permisos necesarios para la accion
                $access = array('view_grados');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif ($result['dataset'] = $grados->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'cantidadEstudiantesXgrado':
                //se declaran los permisos necesarios para la accion
                $access = array('view_grados');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif ($result['dataset'] = $grados->cantidadEstudiantesXgrado()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'No hay datos disponibles';
                }
                break;
            // Acción para crear un dato en la tabla de grados
            case 'create':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('edit_grados');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$grados->setGrado($_POST['grado'])) {
                    $result['exception'] = 'Grado incorrecto';
                } elseif ($grados->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Grado creado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            //Selecccionar un registro por medio de consultas en las queries accionado por un onUpdate
            case 'readOne':
                //se declaran los permisos necesarios para la accion
                $access = array('view_grados');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$grados->setId($_POST['id_grado'])) {
                    $result['exception'] = 'Grado incorrecto';
                } elseif ($result['dataset'] = $grados->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Grado inexistente';
                }
                break;
            // Acción para actualizar un dato en la tabla grados
            case 'update':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('edit_grados');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$grados->setId($_POST['id'])) {
                    $result['exception'] = 'Grado incorrecta';
                } elseif (!$data = $grados->readOne()) {
                    $result['exception'] = 'Grado inexistente';
                } elseif (!$grados->setGrado($_POST['grado'])) {
                    $result['exception'] = 'Grado incorrecto';
                } elseif ($grados->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Grado modificado correctamente';
                } else if (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = '';
                }
                break;
            // Acción para eliminar un dato de la tabla categorías
            case 'delete':
                //se declaran los permisos necesarios para la accion
                $access = array('edit_grados');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$grados->setId($_POST['id_grado'])) {
                    $result['exception'] = 'Grado incorrecto';
                } elseif (!$data = $grados->readOne()) {
                    $result['exception'] = 'Grado inexistente';
                } elseif ($grados->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Grado eliminado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            //------------------detalles grado-----------------
            case 'readDetalle':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('edit_detalles_docentes');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$grados->setId($_POST['id'])) {
                    $result['exception'] = 'id incorrecto';
                } elseif ($result['dataset'] = $grados->readDetalle()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'createDetalle':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('edit_detalles_docentes');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$grados->setId($_POST['id'])) {
                    $result['exception'] = 'Grado incorrecto';
                } elseif ($grados->InsertarDetalle($_POST['detalle'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Cambios guardados exitosamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'deleteDetalle':
                //se declaran los permisos necesarios para la accion
                $access = array('edit_detalles_docentes');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$grados->setId($_POST['id_detalle'])) {
                    $result['exception'] = 'Valor incorrecto';
                } elseif ($grados->deleteDetalle()) {
                    $result['status'] = 1;
                    $result['message'] = 'Cambio eliminado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;

            case 'readAsignaturas':
                //se declaran los permisos necesarios para la accion
                $access = array('view_grados');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif ($result['dataset'] = $grados->readAsignaturas()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'graficoPromedio':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('view_grados');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$grados->setId($_POST['id'])) {
                    $result['exception'] = 'Grado incorrecto';
                } elseif ($result['dataset'] = $grados->graficoPromedio()) {
                    $result['status'] = 1;
                    $result['message'] = 'Encontrado correctamente';

                } else {
                    $result['exception'] = 'No hay datos disponibles';
                }
                break;
            case 'graficoPromedio2':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('view_grados');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$grados->setId($_POST['id_grado'])) {
                    $result['exception'] = 'Grado incorrecta';
                } elseif (!$data = $grados->readOne()) {
                    $result['exception'] = 'Grado inexistente';
                } elseif ($result['dataset'] = $grados->graficoPromedio()) {
                    $result['status'] = 1;
                    $result['message'] = 'Encontrado correctamente';
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