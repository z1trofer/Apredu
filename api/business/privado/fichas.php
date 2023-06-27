<?php
require_once('../../entities/dto/fichas.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $fichas = new Fichas;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null, 'dataset' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_empleado'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
                //se hace la consulta a la base por medio de parametros de la querie para llenado de la tabla
            case 'readAll':
                if ($result['dataset'] = $fichas->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'readEmpleado':
                if ($result['dataset'] = $fichas->readEmpleado()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay grados registrados';
                }
                break;
            case 'readOneEstudiante':
                if (!$fichas->setIdestudiante($_POST['id_estudiante'])) {
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
                    if (!$fichas->setIdestudiante($_POST['id_estudiante'])) {
                        $result['exception'] = 'estudiante incorrecto';
                    } elseif (!$data = $fichas->setdescripcion_ficha($_POST['descripcion'])) {
                        $result['exception'] = 'Descripción incorrecta';
                    }elseif (!$data = $fichas->setid_empleado($_POST['id_empleado'])) {
                        $result['exception'] = 'empleado incorrecta';
                    }elseif ($fichas->createRow()) {
                        $result['status'] = 1;
                        $result['message'] = 'Ficha de conducta creada correctamente';
                    } else {
                        $result['exception'] = Database::getException();
                    }
                    break;
            case 'readAllFichas':
                if ($result['dataset'] = $fichas->readAllFichas()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'readOneFichaXestudiante':
                if (!$fichas->setIdestudiante($_POST['id_estudiante'])) {
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
                if (!$fichas->setid_ficha($_POST['id_ficha'])) {
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
                if (!$fichas->setid_ficha($_POST['id_ficha'])) {
                    $result['exception'] = 'Asignatura incorrecta';
                } elseif (!$fichas->readOne()) {
                    $result['exception'] = 'Usuario inexistente';
                }elseif (!$fichas->setIdestudiante($_POST['id_estudiante'])) {
                    $result['exception'] = 'Estudiante incorrecto';
                } elseif (!$fichas->setdescripcion_ficha($_POST['descripcion'])) {
                    $result['exception'] = 'descripción incorrecta';
                } elseif (!$fichas->setfecha_ficha($_POST['fecha'])) {
                    $result['exception'] = 'fecha incorrecta';
                } elseif (!$fichas->setid_empleado($_POST['nombre_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif ($fichas->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Ficha modificada correctamente';
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