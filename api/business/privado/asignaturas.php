<?php
require_once('../../entities/dto/asignaturas.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $asignaturas = new Asignaturas;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null, 'dataset' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_empleado'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'readAll':
                if ($result['dataset'] = $asignaturas->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen '.count($result['dataset']).' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
                case 'MateriasDocentes':
                    if ($result['dataset'] = $asignaturas->MateriasDocentes()) {
                        $result['status'] = 1;
                    } else {
                        $result['exception'] = 'No hay datos disponibles';
                    }
                    break;
                //Acción para crear un nueva subcategoría 
                case 'create':
                    $_POST = Validator::validateForm($_POST);
                    if (!$asignaturas->setAsignatura($_POST['asignatura'])) {
                        $result['exception'] = 'asignatura incorrectos';
                    }   elseif ($asignaturas->createRow()) {
                        $result['status'] = 1;
                        $result['message'] = 'asignatura creado correctamente';
                    } else {
                        $result['exception'] = Database::getException();
                    }
                    break;
                //leer un dato seleccionado para luego actualizarlo o solo leer la información 
            case 'readOne':
                if (!$asignaturas->setId($_POST['id_asignatura'])) {
                    $result['exception'] = 'asignaturas incorrecta';
                    print_r($_POST);
                } elseif ($result['dataset'] = $asignaturas->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'asignaturas inexistente';
                }
                break;
                //Acción para actualizar un dato de la tabla usuarios
                case 'update':
                    $_POST = Validator::validateForm($_POST);
                    if (!$asignaturas->setId($_POST['id_asignatura'])) {
                        $result['exception'] = 'Asignatura incorrecta';
                    } elseif (!$asignaturas->readOne()) {
                        $result['exception'] = 'Usuario inexistente';
                    } elseif (!$asignaturas->setAsignatura($_POST['asignatura'])) {
                        $result['exception'] = 'Asignaturas incorrecta';
                    } elseif ($asignaturas->updateRow()) {
                        $result['status'] = 1;
                        $result['message'] = 'Asignatura modificada correctamente';
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
?>