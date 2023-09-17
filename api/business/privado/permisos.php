<?php
require_once('../../entities/dto/permisos.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $permisos = new Permisos;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'exception' => null, 'dataset' => null, 'username' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_empleado']) and Validator::validateSessionTime()) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'getVistaAutorizacion':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('edit_permisos');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                } else {
                    $result['status'] = 1;
                }
                break;
                //cambiar los permisos de un usuario
            case 'CambiarPermiso':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('edit_permisos');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$permisos->setPermiso($_POST['permiso'])) {
                    $result['exception'] = 'Permiso inválido';
                } elseif (!$permisos->setAtributo($_POST['atributo'])) {
                    $result['exception'] = 'Atributo inválido';
                } elseif (!$permisos->setidCargo($_POST['cargo'])) {
                    $result['exception'] = 'Cargo incorrecto';
                } elseif ($_POST['cargo'] == 1) {
                    $result['exception'] = 'No puedes cambiar los permisos del administrador';
                } elseif ($result['dataset'] = $permisos->changePermissions()) {
                    $result['status'] = 1;
                    $result['message'] = 'Permiso modificado exitosamente';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Error';
                }
                break;

            case 'getHeaders':
                //se declaran los permisos necesarios para la accion
                $access = array('edit_permisos');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif ($result['dataset'] = $permisos->getHeaders()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Error al obtener las columnas';
                }
                break;

            case 'ObtenerPermisos':
                //se declaran los permisos necesarios para la accion
                $access = array('edit_permisos');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    //se ejecuta la accion
                } elseif ($result['dataset'] = $permisos->viewPermissions($permisos->getHeaders())) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Error al obtener los permisos';
                }
                break;
                //agregar un cargo
            case 'agregarCargo':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('edit_permisos');
                if (!$permisos->setCargo($_POST['cargo'])) {
                    $result['exception'] = 'Cargo incorrecto';
                } elseif ($permisos->agregarCargo()) {
                    $result['status'] = 1;
                    $result['message'] = 'Nuevo cargo agregado exitosamente';
                } elseif (Database::getException()) {
                    $result['exception'] = 'Error al agregar un cargo';
                }
                break;
                //eliminar un cargo
            case 'eliminarCargo':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('edit_permisos');
                if (!$permisos->setidCargo($_POST['id_cargo'])) {
                    $result['exception'] = 'Cargo incorrecto';
                } elseif ($permisos->eliminarCargo()) {
                    $result['status'] = 1;
                    $result['message'] = 'Cargo eliminado correctamente';
                } elseif (Database::getException()) {
                    $result['exception'] = 'Error al eliminar el cargo';
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible fuera de la sesión';
        }
    } else {
        $result['exception'] = 'Acción no disponible fuera de la sesión';
    }
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}
