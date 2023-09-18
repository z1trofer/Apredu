<?php
require_once('../../entities/dto/asignaturas.php');
require_once('../../entities/dto/permisos.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $asignaturas = new Asignaturas;
    $permisos = new Permisos;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null, 'dataset' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_empleado'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'getVistaAutorizacion':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('view_asignaturas');
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
                $access = array('view_asignaturas');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif ($result['dataset'] = $asignaturas->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'MateriasDocentes':
                //se declaran los permisos necesarios para la accion
                $access = array('view_asignaturas');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif ($result['dataset'] = $asignaturas->MateriasDocentes()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'No hay datos disponibles';
                }
                break;
                //Acción para crear un nueva subcategoría 
            case 'create':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('edit_asignaturas');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$asignaturas->setAsignatura($_POST['asignatura'])) {
                    $result['exception'] = 'Asignatura incorrecta';
                } elseif ($asignaturas->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Asignatura creada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
                //leer un dato seleccionado para luego actualizarlo o solo leer la información 
            case 'readOne':
                //se declaran los permisos necesarios para la accion
                $access = array('view_asignaturas');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$asignaturas->setId($_POST['id_asignatura'])) {
                    $result['exception'] = 'Asignatura incorrectas';
                    print_r($_POST);
                } elseif ($result['dataset'] = $asignaturas->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Asignaturas inexistentes';
                }
                break;
                //Acción para actualizar un dato de la tabla usuarios
            case 'update':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('edit_asignaturas');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$asignaturas->setId($_POST['id_asignatura'])) {
                    $result['exception'] = 'Asignatura incorrecta';
                } elseif (!$asignaturas->readOne()) {
                    $result['exception'] = 'Usuario inexistente';
                } elseif (!$asignaturas->setAsignatura($_POST['asignatura'])) {
                    $result['exception'] = 'Asignatura incorrecta';
                } elseif ($asignaturas->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Asignatura modificada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'delete':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('edit_asignaturas');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$asignaturas->setId($_POST['id_asignatura'])) {
                    $result['exception'] = 'Asignatura incorrecta';
                } elseif ($asignaturas->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Asignatura eliminada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
                //Acción para eliminar un dato de la tabla usuarios
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
