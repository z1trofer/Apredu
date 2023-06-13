<?php
require_once('../../entities/dto/notas.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $notas = new Notas;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'exception' => null, 'dataset' => null, 'username' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_empleado'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'ObtenerMateriasDocente':
                if (!$notas->setId_empleado($_SESSION['id_empleado'])) {
                    $result['exception'] = 'empleado incorrecto';
                } elseif ($result['dataset'] = $notas->ObtenerMateriasDocente()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'ObtenerTrimestres':
                $_POST = Validator::validateForm($_POST);
                if ($result['dataset'] = $notas->ObtenerTrimestres($_POST['anio'])) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'Error al obtener los trimestres';
                }
                break;
            case 'ObtenerActividades':
                $_POST = Validator::validateForm($_POST);
                if (!$notas->setId_empleado($_SESSION['id_empleado'])) {
                    $result['exception'] = 'empleado incorrecto'.$_SESSION['id_empleado'];
                } elseif (!$notas->setId_asignatura($_POST['asignatura'])) {
                    $result['exception'] = 'asignatura, incorrecta';
                } elseif (!$notas->setId_trimestre($_POST['trimestre'])) {
                    $result['exception'] = 'trimestre incorrecto';
                } elseif (!$notas->setId_grado($_POST['grado'])) {
                    $result['exception'] = 'grado0 incorrecto';
                } elseif ($result['dataset'] = $notas->ObtenerActividades()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = $result['exception'] = Database::getException().$_SESSION['id_empleado'];
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
