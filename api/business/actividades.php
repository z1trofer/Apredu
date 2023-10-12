<?php
require_once('../entities/dto/actividades.php');
require_once('../entities/dto/permisos.php');
// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clases correspondientes.
    $actividades = new Actividades;
    $permisos = new Permisos;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null, 'dataset' => null);
    // Se verifica si existe una sesión iniciada y válida, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_empleado']) and Validator::validateSessionTime()) {
        // Se compara la acción a realizar.
        switch ($_GET['action']) {
                //verificar los permisos para ver la interfaz de actividades
            case 'getVistaAutorizacion':
                //validar espacios en el formulario
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la acción
                $access = array('view_actividades');
                //se valida el id del empleado logeado
                if (!$permisos->setId($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                    //se ejecuta la acción
                } elseif (!$permisos->getPermissions(($access))) {
                    //acceso denegado
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                } else {
                    //acceso concedido
                    $result['status'] = 1;
                }
                break;
                //obtener los registros de actividades según parametros
            case 'filtrosActividades':
                //validar espacios en el formulario
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la acción
                $access = array('view_actividades');
                if (!$permisos->setId($_SESSION['id_empleado'])) {
                    //se valida el id del empleado logeado
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    //acceso denegado
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se validan los parametros
                } elseif (!$actividades->setIdGrado($_POST['grado'])) {
                    $result['exception'] = 'id grado invalido';
                } elseif (!$actividades->setIdAsignatura($_POST['asignatura'])) {
                    $result['exception'] = 'id asignatura invalido';
                } elseif (!$actividades->setIdTrimestre($_POST['trimestre'])) {
                    $result['exception'] = 'id trimestre invalido';
                    //se ejecuta la acción
                } elseif ($result['dataset'] = $actividades->filtrarActividades()) {
                    //proceso exitoso
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } elseif (Database::getException()) {
                    //error de base de datos
                    $result['exception'] = Database::getException();
                } else {
                    //no hay datos registrados
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
                //obtener todos los registros de tipo_actividades
            case 'readTipoActividades':
                //se declaran los permisos necesarios para la acción
                $access = array('view_actividades');
                if (!$permisos->setId($_SESSION['id_empleado'])) {
                    //se valida el id del empleado logeado
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    //acceso denegado
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la acción
                } elseif ($result['dataset'] = $actividades->readTipoActividades()) {
                    //proceso exitoso
                    $result['status'] = 1;
                    $result['message'] = 'Existen registros';
                } elseif (Database::getException()) {
                    //error de base de datos
                    $result['exception'] = Database::getException();
                } else {
                    //no hay datos registrados
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
                //obtener todos los registros de grados
            case 'readGrados':
                //se declaran los permisos necesarios para la acción
                $access = array('view_actividades');
                if (!$permisos->setId($_SESSION['id_empleado'])) {
                    //se valida el id del empleado logeado
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    //acceso denegado
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la acción
                } elseif ($result['dataset'] = $actividades->readGrados()) {
                    //proceso exitoso
                    $result['status'] = 1;
                    $result['message'] = 'Existen registros';
                } elseif (Database::getException()) {
                    //error de base de datos
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
                //obtener todos los registros de asignaturas
            case 'readAsignaturas':
                //validar espacios en el formulario
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la acción
                $access = array('view_actividades');
                if (!$permisos->setId($_SESSION['id_empleado'])) {
                    //se valida el id del empleado logeado
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    //acceso denegado
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se validan los parametros
                } elseif (!$actividades->setIdGrado($_POST['id_grado'])) {
                    $result['exception'] = 'id grado incorrecto';
                    //se ejecuta la acción
                } elseif ($result['dataset'] = $actividades->readAsignaturas()) {
                    //proceso exitoso
                    $result['status'] = 1;
                    $result['message'] = 'Existen registros';
                } elseif (Database::getException()) {
                    //error de base de datos
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
                //obtener todos los registros de trimestres
            case 'readTrimestre':
                //se declaran los permisos necesarios para la acci´pn
                $access = array('view_actividades');
                if (!$permisos->setId($_SESSION['id_empleado'])) {
                    //se valida el id del empleado logeado
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    //acceso denegado
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la acción
                } elseif ($result['dataset'] = $actividades->readTrimestres()) {
                    //proceso exitoso
                    $result['status'] = 1;
                    $result['message'] = 'Existen registros';
                } elseif (Database::getException()) {
                    //error en base de datos
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
                //obtener los detalles de las actividades (grado y asignatura a la que esta asignada)
            case 'readDetalle':
                //se declaran los permisos necesarios para la acción
                $access = array('view_actividades');
                //permiso para determinar el nivel de información a mostrar
                $level = array('view_all_actividades');
                if (!$permisos->setId($_SESSION['id_empleado'])) {
                    //se valida el id del empleado logeado
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    //acceso denegado
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la acción
                } elseif ($result['dataset'] = $actividades->readDetalleAsignaturaGrado($permisos->getPermissions(($level)))) {
                    //proceso exitoso
                    $result['status'] = 1;
                    $result['message'] = 'Existen registros';
                } elseif (Database::getException()) {
                    //error de base de datos
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
                //agregar un registro de actividad
            case 'create':
                //validar espacios en el formulario
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la acción
                $access = array('edit_actividades');
                if (!$permisos->setId($_SESSION['id_empleado'])) {
                    //se valida el id del empleado logeado
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    //acceso denegado
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se validan los campos a ingresar
                } elseif (!$actividades->setNombreActividad($_POST['nombre'])) {
                    $result['exception'] = 'Nombre de actividad no valido';
                } elseif (!$actividades->setPonderacion($_POST['ponderacion'])) {
                    $result['exception'] = 'Ponderación no valida, asegurate que sea unicamente un valor entero entre 1-100 sin el simbolo de porcentaje';
                } elseif (!$actividades->setDescripcion($_POST['descripcion'])) {
                    $result['exception'] = 'Descripción incorrecta';
                } elseif (!$actividades->setIdTipoActividad($_POST['tipo_actividad'])) {
                    $result['exception'] = 'Tipo de actividad inválido';
                } elseif (!$actividades->setIdDetalleAsignaturaEmpleado($_POST['detalle'])) {
                    $result['exception'] = 'Asignación no válida';
                } elseif (!$actividades->setIdTrimestre($_POST['trimestre'])) {
                    $result['exception'] = 'Trimestre no válido';
                } elseif (!$actividades->setFechaEntrega($_POST['fecha_entrega'])) {
                    $result['exception'] = 'Fecha no válida';
                } elseif (!$actividades->validatePonderacion(false)) {
                    $result['exception'] = 'La ponderación total be ser menor o igual a 100';
                    //se ejecuta la acción
                } elseif ($actividades->createRow()) {
                    //proceso exitoso
                    $result['status'] = 1;
                    $result['message'] = 'Se ha creado correctamente';
                } elseif (Database::getException()) {
                    //error de base de datos
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = "Ocurrio un error al ingresar la actividad";
                }
                break;
                //obtener información de un registro de actividad
            case 'readOne':
                //validar espacios en el formulario
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la acción
                $access = array('view_actividades');
                if (!$permisos->setId($_SESSION['id_empleado'])) {
                    //se valida el id del empleado logeado
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    //acceso denegado
                    $result['exceptión'] = 'No tienes autorizacion para realizar esta acción';
                    //se valida los parametros
                } elseif (!$actividades->setIdActividad($_POST['id_actividad'])) {
                    $result['exception'] = 'Actividad incorrecta';
                    //se ejecuta la acción
                } elseif ($result['dataset'] = $actividades->readOne()) {
                    //proceso exitoso
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    //error de base de datos
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Actividad inexistente';
                }
                break;
                //actualizar un registro
            case 'update':
                //validar espacios en el formulario
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('edit_actividades');
                if (!$permisos->setId($_SESSION['id_empleado'])) {
                    //se valida el id del empleado logeado
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    //acceso denegado
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se validan los parametros
                } elseif (!$actividades->setIdActividad($_POST['id'])) {
                    $result['exception'] = 'id incorrecto';
                } elseif (!$data = $actividades->readOne()) {
                    $result['exception'] = 'id inexistente';
                } elseif (!$actividades->setNombreActividad($_POST['nombre'])) {
                    $result['exception'] = 'Nombre incorrecto';
                } elseif (!$actividades->setPonderacion($_POST['ponderacion'])) {
                    $result['exception'] = 'Ponderación incorrecta';
                } elseif (!$actividades->setDescripcion($_POST['descripcion'])) {
                    $result['exception'] = 'Descripción incorrecta';
                } elseif (!isset($_POST['tipo_actividad'])) {
                    $result['exception'] = 'Seleccione un tipo de actividad';
                } elseif (!$actividades->setIdTipoActividad($_POST['tipo_actividad'])) {
                    $result['exception'] = 'Tipo de actividad incorrecto';
                } elseif (!$actividades->setFechaEntrega($_POST['fecha_entrega'])) {
                    $result['exception'] = 'Fecha incorrecta';
                } elseif (!$actividades->validatePonderacion(true)) {
                    $result['exception'] = 'La ponderación total dbe ser menor o igual a 100';
                    //se ejecuta la accion
                } elseif ($actividades->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Se ha actualizado correctamente';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = "No se pudo actualizar el registro";
                }
                break;
                //eliminar un registro de actividades
            case 'delete':
                //se declaran los permisos necesarios para la acción
                $access = array('edit_actividades', 'view_all_actividades', 'edit_tipo_actividades');
                if (!$permisos->setId($_SESSION['id_empleado'])) {
                    //se valida el id del empleado logeado
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    //acceso denegado
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se valida el parametro
                } elseif (!$actividades->setIdActividad($_POST['id_actividad'])) {
                    $result['exception'] = 'Actividad incorrecta';
                } elseif (!$data = $actividades->readOne()) {
                    $result['exception'] = 'Actividad inexistente';
                    //se ejecuta la acción
                } elseif ($actividades->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Actividad eliminada correctamente';
                } elseif (Database::getException()) {
                    //error de base de datos
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = "No se pudo elimianr el registro";
                }
                break;
                //añadir un registro a tipo_actividades
            case 'addTipoActividad':
                //validar espacios en el formulario
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('edit_tipo_actividades');
                if (!$permisos->setId($_SESSION['id_empleado'])) {
                    //se valida el id del empleado logeado
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    //acceso denegado
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se ejecuta la accion
                } elseif (!$actividades->setTipoActividad($_POST['tipo_actividad'])) {
                    $result['exception'] = 'tipo Actividad incorrecto incorrecto';
                } elseif ($actividades->addTipoActividad()) {
                    //proceso exitoso
                    $result['status'] = 1;
                    $result['message'] = 'Tipo Actividad agregado correctamente';
                } elseif (Database::getException()) {
                    //error de base de datos
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = "No se pudo agregar el registro";
                }
                break;
                //actualizar un registro de tipo actividades
            case 'updateTipoActividad':
                //validar espacios en el formulario
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la acción
                $access = array('edit_tipo_actividades');
                if (!$permisos->setId($_SESSION['id_empleado'])) {
                     //se valida el id del empleado logeado
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    //acceso denegado
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se validan los parametros
                } elseif (!$actividades->setIdTipoActividad($_POST['id_tipo'])) {
                    $result['exception'] = 'id tipo incorrecto';
                } elseif (!$data = $actividades->readOneTipoActividad()) {
                    $result['exception'] = 'id inexistente';
                } elseif (!$actividades->setTipoActividad($_POST['tipo_actividad'])) {
                    $result['exception'] = 'id tipo incorrecto';
                    //se ejecuta la accion
                } elseif ($actividades->updateTipoActividad()) {
                    //proceso exitoso
                    $result['status'] = 1;
                    $result['message'] = 'Se ha actualizado correctamente';
                } elseif (Database::getException()) {
                    //error de base de datos
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = "No se pudo agregar el registro";
                }
                break;
                //eliminar un registro de tipo actividades
            case 'deleteTipoActividad':
                //validar espacios en el formulario
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('edit_actividades', 'view_all_actividades', 'edit_tipo_actividades' ,'edit_admin');
                if (!$permisos->setId($_SESSION['id_empleado'])) {
                    //se valida el id del empleado logeado
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se validan los parametros
                } elseif (!$actividades->setIdTipoActividad($_POST['id_tipo'])) {
                    $result['exception'] = 'id tipo incorrecto';
                } elseif (!$data = $actividades->readOneTipoActividad()) {
                    $result['exception'] = 'Actividad inexistente';
                    //se ejecuta la acción
                } elseif ($actividades->deleteTipoActividad()) {
                    //proceso exitoso
                    $result['status'] = 1;
                    $result['message'] = 'Se eliminó el registro correctamente';
                } elseif (Database::getException()) {
                    //error de base de datos
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = "No se pudo agregar el registro";
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
