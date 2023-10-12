<?php
require_once('../entities/dto/estudiantes.php');
require_once('../entities/dto/permisos.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $estudiante = new Estudiantes;
    $permisos = new Permisos;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null, 'dataset' => null);
    //arreglo para filtro parametrizado
    $filtro = array('grado' => 0);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_empleado']) and Validator::validateSessionTime()) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'getVistaAutorizacion':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('view_estudiantes');
                if (!$permisos->setId($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                } else {
                    $result['status'] = 1;
                }
                break;
                //se hace la consulta a la base por medio de parametros de la querie para llenado de la tabla
            case 'readAll':
                //se declaran los permisos necesarios para la accion
                $access = array('view_estudiantes');
                if (!$permisos->setId($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif ($result['dataset'] = $estudiante->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
                //filtro de grado para el estudiante
            case 'FiltrosEstudiantes':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('view_estudiantes');
                if (!$permisos->setId($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                } elseif (!$estudiante->setIdGrado($_POST['grado'])) {
                    $result['exception'] = 'Id grado invalido';
                    //se ejecuta la accion
                } elseif ($result['dataset'] = $estudiante->FiltrarEstudiante($_POST['search'])) {
                    $result['status'] = 1;
                    // Realiza el conteo de cuantos resultados hay
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
                //Selecccionar un registro por medio de consultas en las queries accionado por un onUpdate
            case 'readOne':
                //se declaran los permisos necesarios para la accion
                $access = array('view_estudiantes');
                if (!$permisos->setId($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$estudiante->setIdEstudiante($_POST['id_estudiante'])) {
                    $result['exception'] = 'Estudiante incorrecto';
                } elseif ($result['dataset'] = $estudiante->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    // el estudiante no existe
                    $result['exception'] = 'Estudiante inexistente';
                }
                break;
            case 'readGrado':
                //se declaran los permisos necesarios para la accion
                $access = array('view_estudiantes');
                if (!$permisos->setId($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif ($result['dataset'] = $estudiante->readGrado()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay grados registrados';
                }
                break;
                // Crear un nuevo estudiante
            case 'createEstudiante':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('edit_estudiantes');
                if (!$permisos->setId($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion 
                } elseif (!$estudiante->setNombresEstudiante($_POST['nombre_estudiante'])) {
                    $result['exception'] = 'Nombres incorrectos';
                } elseif (!$estudiante->setApellidosEstudiante($_POST['apellido_estudiante'])) {
                    $result['exception'] = 'Apellidos incorrectos';
                } elseif (!$estudiante->setNacimiento($_POST['nacimiento'])) {
                    $result['exception'] = 'Fecha incorrecta';
                } elseif (!$estudiante->setDireccionEstudiante($_POST['direccion_estudiante'])) {
                    $result['exception'] = 'Dirección incorrecta';
                } elseif (!$estudiante->setNie($_POST['nie'])) {
                    $result['exception'] = 'NIE incorrecto';
                } elseif (!$estudiante->setIdGrado($_POST['grado'])) {
                    $result['exception'] = 'Grado incorrecto';
                } elseif (!$estudiante->setIdResponsable($_POST['selectRes'])) {
                    $result['exception'] = 'responsable incorrecto';
                } elseif (!$estudiante->setParentesco($_POST['parentesco'])) {
                    $result['exception'] = 'parentesco incorrecto';
                } elseif (!$estudiante->setEstado($_POST['estado'])) {
                    $result['exception'] = 'Estado incorrecto';
                } elseif (!$estudiante->setIdResponsable($_POST['selectRes'])) {
                    $result['exception'] = 'Resposnable incorrecto';
                } elseif (!$estudiante->setParentesco($_POST['parentesco'])) {
                    $result['exception'] = 'Parentesco incorrecto';
                } elseif ($estudiante->createEstudiante()) {
                    $result['status'] = 1;
                    $result['message'] = 'Estudiante creado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
                // Acción para actualizar un dato en la tabla de estudiantes
            case 'updateEstudiante':

                //se validan los espacios del formulario
                $_POST = Validator::validateForm($_POST);
                //se valida si se modifica el id grado
                if (!$estudiante->setIdEstudiante($_POST['id_estudiante'])) {
                    $result['exception'] = 'Estudiante incorrecto';
                } elseif (!$data = $estudiante->readOne()) {
                    $result['exception'] = 'Estudiante inexistente';
                }
                if ($data['id_grado'] != $_POST['grado']) {
                    //se declaran los permisos necesarios para la accion
                    $access = array('edit_estudiantes', 'edit_admin');
                } else {
                    //se declaran los permisos necesarios para la accion
                    $access = array('edit_estudiantes');
                }
                //se validan los permisos
                if (!$permisos->setId($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$estudiante->setIdEstudiante($_POST['id_estudiante'])) {
                    $result['exception'] = 'Estudiante incorrecto';
                } elseif (!$data = $estudiante->readOne()) {
                    $result['exception'] = 'Estudiante inexistente';
                } elseif (!$estudiante->setNombresEstudiante($_POST['nombre_estudiante'])) {
                    $result['exception'] = 'Nombre incorrecto';
                } elseif (!$estudiante->setApellidosEstudiante($_POST['apellido_estudiante'])) {
                    $result['exception'] = 'Apellido incorrecto';
                } elseif (!$estudiante->setNacimiento($_POST['nacimiento'])) {
                    $result['exception'] = 'Fecha de nacimiento incorrecta';
                } elseif (!$estudiante->setDireccionEstudiante($_POST['direccion_estudiante'])) {
                    $result['exception'] = 'Direccion incorrecta';
                } elseif (!$estudiante->setNie($_POST['nie'])) {
                    $result['exception'] = 'NIE incorrecto';
                } elseif (!$estudiante->setIdGrado($_POST['grado'])) {
                    $result['exception'] = 'Grado incorrecto';
                } elseif (!$estudiante->setEstado($_POST['estado'])) {
                    $result['exception'] = 'Estado incorrecto';
                } elseif (!$estudiante->setIdResponsable($_POST['selectRes'])) {
                    $result['exception'] = 'Resposnable incorrecto';
                } elseif (!$estudiante->setParentesco($_POST['parentesco'])) {
                    $result['exception'] = 'Parentesco incorrecto';
                } elseif ($estudiante->updateEstudiante()) {
                    $result['status'] = 1;
                    $result['message'] = 'Estudiante modificado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
                //buscar un Responsable
            case 'searchResponsables':
                $_POST = Validator::validateForm($_POST);
                //obtener permisos de la accion
                $access = array('view_responsables');
                if (!$permisos->setId($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    //se deniega la acción
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                } elseif ($result['dataset'] = $estudiante->searchResponsables($_POST['data'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['except+ion'] = 'No hay datos registrados';
                }
                break;
                //Acción para eliminar un dato en la tabla de clientes
            case 'deleteEstudiante':
                //se declaran los permisos necesarios para la accion
                $access = array('edit_estudiantes');
                if (!$permisos->setId($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$estudiante->setIdEstudiante($_POST['id_estudiante'])) {
                    $result['exception'] = 'Estudiante incorrecto';
                } elseif (!$data = $estudiante->readOne()) {
                    $result['exception'] = 'Estudiante inexistente';
                } elseif ($estudiante->deleteEstudiante()) {
                    $result['status'] = 1;
                    $result['message'] = 'Estudiante eliminado correctamente';
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
