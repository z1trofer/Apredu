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
            case 'ObtenerMaterias':
                if ($result['dataset'] = $notas->ObtenerMaterias()) {
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
            case 'ObtenerTrimestresNoParam':
                $_POST = Validator::validateForm($_POST);
                if ($result['dataset'] = $notas->ObtenerTrimestresActual()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'Error al obtener los trimestres';
                }
                break;
            case 'ObtenerGrados':
                $_POST = Validator::validateForm($_POST);
                if ($result['dataset'] = $notas->ObtenerGrados()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'Error al obtener los grados';
                }
                break;
            case 'ObtenerActividades':
                $_POST = Validator::validateForm($_POST);
                if (!$notas->setId_empleado($_SESSION['id_empleado'])) {
                    $result['exception'] = 'empleado incorrecto';
                } elseif (!$notas->setId_asignatura($_POST['asignatura'])) {
                    $result['exception'] = 'asignatura, incorrecta';
                } elseif (!$notas->setId_trimestre($_POST['trimestre'])) {
                    $result['exception'] = 'trimestre incorrecto';
                } elseif (!$notas->setId_grado($_POST['grado'])) {
                    $result['exception'] = 'grado0 incorrecto';
                } elseif ($_SESSION['tipo'] == 2) {
                    if ($result['dataset'] = $notas->ObtenerActividades()) {
                        $result['status'] = 1;
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay datos registrados';
                    }
                } else {
                    if ($result['dataset'] = $notas->ObtenerActividadesDirector()) {
                        $result['status'] = 1;
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay datos registrados';
                    }
                }
                break;
            case 'ObtenerActividad':
                $_POST = Validator::validateForm($_POST);
                if (!$notas->setId_empleado($_SESSION['id_empleado'])) {
                    $result['exception'] = 'empleado incorrecto';
                } elseif (!$notas->setId_actividad($_POST['actividad'])) {
                    $result['exception'] = 'asignatura, incorrecta';
                } elseif ($_SESSION['tipo'] == 2) {
                    if ($result['dataset'] = $notas->ObtenerActividad()) {
                        $result['status'] = 1;
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay datos registrados';
                    }
                } else {
                    if ($result['dataset'] = $notas->ObtenerActividadDirector()) {
                        $result['status'] = 1;
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay datos registrados';
                    }
                }
                break;
            case 'ActualizarNotas':
                $_POST = Validator::validateForm($_POST);
                if (!$notas->setId_nota($_POST['id'])) {
                    $result['exception'] = 'No Hay id nota';
                } elseif (!$notas->setnota($_POST['nota'])) {
                    $result['exception'] = 'La nota no es valida';
                } elseif ($notas->CambiarNotas()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'topNotas':
                $_POST = Validator::validateForm($_POST);
                $parametros = array('trimestre' => null, 'grado' => null);
                $parametros['trimestre'] = $_POST['trimestre'];
                $parametros['grado'] = $_POST['grado'];
                if ($result['dataset'] = $notas->TopNotas($parametros)) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
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
