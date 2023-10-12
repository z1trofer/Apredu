<?php
require_once('../entities/dto/notas.php');
require_once('../entities/dto/permisos.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $notas = new Notas;
    $permisos = new Permisos;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'exception' => null, 'dataset' => null, 'username' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_empleado'])and Validator::validateSessionTime()) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'getVistaAutorizacion':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('view_notas');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                } else {
                    $result['status'] = 1;
                }
                break;
            case 'ObtenerMaterias':
                //se declaran los permisos necesarios para la accion
                $access = array('view_notas');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif ($result['dataset'] = $notas->ObtenerMaterias()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'ObtenerTrimestres':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('view_notas');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif ($result['dataset'] = $notas->ObtenerTrimestres()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'Error al obtener los trimestres';
                }
                break;
            case 'ObtenerTrimestresNoParam':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('view_notas');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif ($result['dataset'] = $notas->ObtenerTrimestresActual()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'Error al obtener los trimestres';
                }
                break;
            case 'ObtenerGrados':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('view_notas');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif ($result['dataset'] = $notas->ObtenerGrados()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'Error al obtener los grados';
                }
                break;
            case 'ObtenerActividades':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('view_notas');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$notas->setId_empleado($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$notas->setId_asignatura($_POST['asignatura'])) {
                    $result['exception'] = 'Asignatura incorrecta';
                } elseif (!$notas->setId_trimestre($_POST['trimestre'])) {
                    $result['exception'] = 'Trimestre incorrecto';
                } elseif (!$notas->setId_grado($_POST['grado'])) {
                    $result['exception'] = 'Grado incorrecto';
                } elseif ($_SESSION['id_cargo'] == 2) {
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
            case 'notaGlobal':
                //se declaran los permisos necesarios para la accion
                $access = array('view_notas');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif ($result['dataset'] = $notas->notaGlobal()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'No hay datos disponibles';
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
                    $result['exception'] = 'id incorrecto';
                } elseif (!$notas->setnota($_POST['nota'])) {
                    $result['exception'] = 'Nota inválida';
                } elseif ($notas->CambiarNotas()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'topNotas':
                $_POST = Validator::validateForm($_POST);
                $parametros = array('trimestre' => null, 'grado' => null);
                if (!$notas->setId_trimestre($_POST['trimestre'])) {
                    $parametros['trimestre'] = 'Todos';
                } else {
                    $parametros['trimestre'] = $_POST['trimestre'];
                }
                if (!$notas->setId_grado($_POST['grado'])) {
                    $parametros['grado'] = 'Todos';
                } else {
                    $parametros['grado'] = $_POST['grado'];
                }
                if ($result['dataset'] = $notas->TopNotas($parametros)) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }

                break;
            case 'estudiantesAprobados':
                $_POST = Validator::validateForm($_POST);
                $parametros = array('trimestre' => null, 'grado' => null);
                if (!$notas->setId_trimestre($_POST['trimestre'])) {
                    $parametros['trimestre'] = 'Todos';
                } else {
                    $parametros['trimestre'] = $_POST['trimestre'];
                }
                if (!$notas->setId_grado($_POST['grado'])) {
                    $parametros['grado'] = 'Todos';
                } else {
                    $parametros['grado'] = $_POST['grado'];
                }
                $parametros['condicion'] = $_POST['condicion'];
                if ($result['dataset'] = $notas->AproYRepro($parametros)) {
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
