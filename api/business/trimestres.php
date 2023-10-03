<?php
require_once('../entities/dto/trimestres.php');
require_once('../entities/dto/permisos.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $trimestres = new Trimestres;
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
                $access = array('view_trimestres');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    //se deniega el acceso
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                } else {
                    $result['status'] = 1;
                }
                break;
            case 'readAll':
                //se declaran los permisos necesarios para la accion
                $access = array('view_trimestres');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    //se deniega el acceso
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                } elseif ($result['dataset'] = $trimestres->readAll()) {
                //se hace la consulta a la base por medio de parametros de la querie para llenado de la tabla
                    $result['status'] = 1;
                    $result['message'] = 'Existen '.count($result['dataset']).' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                }else{
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
                // Acción para crear un dato en la tabla de grados
            case 'create':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('edit_trimestres');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                } elseif (!$trimestres->setAnio($_POST['anio'])) {
                    $result['exception'] = 'Año incorrecto';
                }elseif ($trimestres->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Año creado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
                //Selecccionar un registro por medio de consultas en las queries accionado por un onUpdate
            case 'readOne':
                //se declaran los permisos necesarios para la accion
                $access = array('view_trimestres');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    //se deniega el acceso
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                } elseif (!$grados->setIdAnio($_POST['id_anio'])) {
                    $result['exception'] = 'Año incorrecto';
                } elseif ($result['dataset'] = $trimestres->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Año inexistente';
                }
                break;
                // Acción para actualizar un dato en la tabla grados
            case 'update':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('edit_trimestres');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    //se deniega el acceso
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                } elseif (!$trimestres->setIdTrimestre($_POST['id'])) {
                    $result['exception'] = 'Trimestre incorrecto';
                } elseif ($trimestres->updateRow()) {
                        $result['status'] = 1;
                        $result['message'] = 'Trimestre modificado correctamente';
                        $trimestres->updateTabla();
                    } else {
                        $result['exception'] = Database::getException();
                    }
                break;
                // Acción para eliminar un dato de la tabla categorías
            case 'delete':
                //se declaran los permisos necesarios para la accion
                $access = array('edit_trimestres');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    //se deniega el acceso
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                } elseif (!$grados->setIdAnio($_POST['id_anio'])) {
                    $result['exception'] = 'Grado incorrecto';
                } elseif (!$data = $trimestres->readOne()) {
                    $result['exception'] = 'Grado inexistente';
                } elseif ($trimestres->deleteRow()) {
                    $result['status'] = 1;                   
                    $result['message'] = 'Grado eliminado correctamente';
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