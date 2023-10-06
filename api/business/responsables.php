<?php
require_once('../entities/dto/responsables.php');
require_once('../entities/dto/permisos.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $responsable = new ResponsablesVista;
    $permisos = new Permisos;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null, 'dataset' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_empleado']) and Validator::validateSessionTime()) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'getVistaAutorizacion':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('view_responsables');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                } else {
                    $result['status'] = 1;
                }
                break;
            case 'readAll':
                //obtener permisos de la accion
                $access = array('view_responsables');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    //se deniega la acción
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                } elseif ($result['dataset'] = $responsable->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;

            case 'search':
                //se valida el id del estudiante
                if (!$responsable->setIdAlumno($_POST['selectEs'])) {
                    $idEs = 'todos';
                } else {
                    $idEs = $_POST['selectEs'];
                }
                //obtener permisos de la accion
                $access = array('view_responsables');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    //se deniega la acción
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                } elseif ($result['dataset'] = $responsable->search($_POST['searchRes'], $idEs)) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
                //Acción para crear un nuevo Responsable 
            case 'delete':
                $_POST = Validator::validateForm($_POST);
                //obtener permisos de la accion
                $access = array('edit_responsables');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    //se deniega la acción
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                } elseif (!$responsable->setIdResponsable($_POST['id_responsable'])) {
                    //se validan todos los campos
                    $result['exception'] = 'id responsable incorrecto';
                } elseif ($responsable->deleteRow()) {
                    //se comprueba la respuesta de la acci´no
                    $result['status'] = 1;
                    $result['message'] = 'Responsable eliminado satisfactoriamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
                //Acción para crear un nuevo Responsable 
            case 'create':
                $_POST = Validator::validateForm($_POST);
                //obtener permisos de la accion
                $access = array('edit_responsables');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    //se deniega la acción
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                } elseif (!$responsable->setNombresResponsable($_POST['nombres'])) {
                    //se validan todos los campos
                    $result['exception'] = 'Nombres incorrectos';
                } elseif (!$responsable->setApellidosResponsable($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos incorrectos';
                } elseif (!$responsable->setDui($_POST['dui'])) {
                    $result['exception'] = 'DUI incorrecto';
                } elseif (!$responsable->setCorreo($_POST['correo'])) {
                    $result['exception'] = 'Correo incorrecto';
                } elseif (!$responsable->setLugarTrabajo($_POST['lugar'])) {
                    $result['exception'] = 'Lugar incorrecto';
                } elseif (!$responsable->setTelefono($_POST['telefono'])) {
                    $result['exception'] = 'Telefono incorrecto';
                } elseif (!$responsable->setParentesco($_POST['parentesco'])) {
                    $result['exception'] = 'Parentesco incorrecto';
                } elseif (!$responsable->setIdAlumno($_POST['estudiante'])) {
                    $result['exception'] = 'Estudiante incorrecto';
                } elseif ($responsable->createRow()) {
                    if(!$responsable->setIdResponsable($responsable->obtenerResponsableIDdui())){
                        $result['exception'] = 'Error al crear el responsable';
                    } elseif(!$responsable->removeRes()) {
                        $result['exception'] = 'Error al eliminar el id';
                    } elseif($responsable->updateEstId()) {
                        $result['status'] = 1;
                        $result['message'] = 'Responsable actualizado correctamente';
                    } elseif (Database::getException()){
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = "error al guardar el estudiante";
                    }
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
                //leer un dato seleccionado para luego actualizarlo o solo leer la información 
            case 'readOne':
                //se verifican los permisos necesarios
                $access = array('view_responsables');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se deniega el acceso
                } elseif (!$responsable->setIdResponsable($_POST['id_responsable'])) {
                    $result['exception'] = 'Responsable incorrecto';
                } elseif ($result['dataset'] = $responsable->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Responsable inexistente';
                }
                break;
                //Acción para actualizar un dato de la tabla usuarios
            case 'update':
                $_POST = Validator::validateForm($_POST);
                //obtener permisos de la accion
                $access = array('edit_responsables');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    //se deniega la acción
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                } elseif (!$responsable->setIdResponsable($_POST['id_responsable'])) {
                    $result['exception'] = 'Responsable incorrecto';
                } elseif (!$responsable->readOne()) {
                    $result['exception'] = 'Responsable inexistente';
                } elseif (!$responsable->setNombresResponsable($_POST['nombres'])) {
                    $result['exception'] = 'Nombres incorrectos';
                } elseif (!$responsable->setApellidosResponsable($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos incorrectos';
                } elseif (!$responsable->setDui($_POST['dui'])) {
                    $result['exception'] = 'DUI incorrectos';
                } elseif (!$responsable->setCorreo($_POST['correo'])) {
                    $result['exception'] = 'Correo incorrecto';
                } elseif (!$responsable->setLugarTrabajo($_POST['lugar'])) {
                    $result['exception'] = 'Lugar incorrecto';
                } elseif (!$responsable->setTelefono($_POST['telefono'])) {
                    $result['exception'] = 'Telefono incorrecto';
                } elseif (!$responsable->setParentesco($_POST['parentesco'])) {
                    $result['exception'] = 'Parentesco incorrecto';
                } elseif (!$responsable->setIdAlumno($_POST['estudiante'])) {
                    $result['exception'] = 'Estudiante incorrecto';
                } elseif ($responsable->updateRow()) {
                    if(!$responsable->removeRes()) {
                        $result['exception'] = 'Error al eliminar el id';
                    } elseif ($responsable->updateEstId()) {
                        $result['status'] = 1;
                        $result['message'] = 'Responsable actualizado correctamente';
                    } else{
                        $result['exception'] = Database::getException();
                    }
                } else {
                    $result['exception'] = Database::getException();
                }
                break;

                //Quitar un responsable de un estudiante
            case 'removeRes':
                //se verifican los permisos necesarios
                $access = array('edit_responsables');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se deniega el acceso
                } elseif (!$responsable->setIdAlumno($_POST['id_estudiante'])) {
                    $result['exception'] = 'Responsable incorrecto';
                } elseif ($responsable->removeRes()) {
                    $result['status'] = 1;
                    $result['message'] = 'Registro actualizado correctamente';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No se pudo eliminar el registro';
                }
                break;
            case 'SearchEstudiante':
                $_POST = Validator::validateForm($_POST);
                //obtener permisos de la accion
                $access = array('view_responsables');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    //se deniega la acción
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                } elseif ($result['dataset'] = $responsable->SearchEstudiantes($_POST['data'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['except+ion'] = 'No hay datos registrados';
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
