<?php
require_once('../../entities/dto/responsables.php');
require_once('../../entities/dto/estudiantes.php');
require_once('../../entities/dto/permisos.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $estudiante = new Estudiantes;
    $responsable = new Responsables;
    $permisos = new Permisos;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null, 'dataset' => null);
    //arreglo para filtro parametrizado
    $filtro = array('grado' => 0);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_empleado'])and Validator::validateSessionTime()) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'getVistaAutorizacion':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('view_estudiantes');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
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
                if (!$permisos->setid($_SESSION['id_empleado'])) {
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
                $filtro['grado'] = $_POST['grado'];
                //se declaran los permisos necesarios para la accion
                $access = array('view_estudiantes');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif ($result['dataset'] = $estudiante->FiltrarEstudiante($filtro)) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'search':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('view_estudiantes');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif ($_POST['search'] == '') {
                    $result['exception'] = 'Ingrese un valor';
                } elseif ($result['dataset'] = $Cliente_p->searchRows($_POST['search'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay coincidencias';
                }
                break;
            //Selecccionar un registro por medio de consultas en las queries accionado por un onUpdate
            case 'readOne':
                //se declaran los permisos necesarios para la accion
                $access = array('view_estudiantes');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
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
                    $result['exception'] = 'Estudiante inexistente';
                }
                break;
            case 'readGrado':
                //se declaran los permisos necesarios para la accion
                $access = array('view_estudiantes');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
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
            case 'createResponsable':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('edit_estudiantes');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$responsable->setNombresResponsable($_POST['nombre_responsable'])) {
                    $result['exception'] = 'Nombres incorrectos';
                } elseif (!$responsable->setApellidosResponsable($_POST['apellido_responsable'])) {
                    $result['exception'] = 'Apellidos incorrectos';
                } elseif (!$responsable->setDui($_POST['dui'])) {
                    $result['exception'] = 'DUI incorrecto';
                } elseif (!$responsable->setCorreo($_POST['correo_responsable'])) {
                    $result['exception'] = 'Correo incorrecto';
                } elseif (!$responsable->setLugarTrabajo($_POST['trabajo'])) {
                    $result['exception'] = 'Lugar de trabajo incorrecto';
                } elseif (!$responsable->setTelefonoTrabajo($_POST['telefono_trabajo'])) {
                    $result['exception'] = 'Telefono de trabajo incorrecto';
                } elseif (!$responsable->setParentesco($_POST['parentesco_responsable'])) {
                    $result['exception'] = 'Parentesco incorrecto';
                } elseif ($responsable->createResponsable()) {
                    $result['status'] = 1;
                    $result['message'] = 'Usuario creado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'CreateEstudiante':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('edit_estudiantes');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
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
                } elseif (!$estudiante->setIdGrado($_POST['grados_estudiante'])) {
                    $result['exception'] = 'Grado incorrecto';
                } elseif (!$estudiante->setUsuarioEstudiante($_POST['usuario_estudiante'])) {
                    $result['exception'] = 'Alias incorrecto';
                } elseif (!$estudiante->setEstado(isset($_POST['estado']) ? 0 : 1)) {
                    $result['exception'] = 'Estado incorrecto';
                } elseif (!$estudiante->setClave($_POST['clave'])) {
                    $result['exception'] = Validator::getPasswordError();
                } elseif ($estudiante->CreateEstudiante()) {
                    $result['status'] = 1;
                    $result['message'] = 'Estudiante creado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            // Acción para actualizar un dato en la tabla de estudiantes
            case 'updateEstudiante':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('edit_estudiantes');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
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
                } elseif (!$estudiante->setIdGrado($_POST['grados_estudiante'])) {
                    $result['exception'] = 'Grado incorrecto';
                } elseif (!$estudiante->setUsuarioEstudiante($_POST['usuario_estudiante'])) {
                    $result['exception'] = 'Alias incorrecto';
                } elseif (!$estudiante->setEstado(isset($_POST['estados']) ? 0 : 1)) {
                    $result['exception'] = 'Estado incorrecto';
                } elseif ($estudiante->UpdateEstudiante()) {
                    $result['status'] = 1;
                    $result['message'] = 'Estudiante modificado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'createFicha':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('edit_fichas');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$estudiante->setIdEstudiante($_POST['id_estudiante_ficha'])) {
                    $result['exception'] = 'Estudiante incorrecto';
                } elseif (!$data = $estudiante->setDescripcionFicha($_POST['ficha_descripcion'])) {
                    $result['exception'] = 'Descripción incorrecta';
                } elseif (!$data = $estudiante->setIdEmpleado($_POST['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif ($estudiante->createFicha()) {
                    $result['status'] = 1;
                    $result['message'] = 'Ficha de conducta creada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            //Acción para eliminar un dato en la tabla de clientes
            case 'deleteEstudiante':
                //se declaran los permisos necesarios para la accion
                $access = array('edit_estudiantes');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
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
?>