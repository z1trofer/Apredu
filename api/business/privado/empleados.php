<?php
require_once('../../entities/dto/empleados.php');
require_once('../../entities/dto/permisos.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $Empleados_p = new Empleados;
    $permisos = new Permisos;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => null, 'message' => null, 'exception' => null, 'dataset' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_empleado'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'getVistaAutorizacion':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('view_empleados');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                } else {
                    $result['status'] = 1;
                }
                break;
            case 'readAll':
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('view_empleados');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                } elseif ($result['dataset'] = $Empleados_p->readAll($_POST['check'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'readPorDetalle':
                if (!$Empleados_p->setid_empleado($_POST['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$Empleados_p->setid_grado($_POST['id_grado'])) {
                    $result['exception'] = 'Grado incorrecto';
                } elseif (!$Empleados_p->setid_asignatura($_POST['id_asignatura'])) {
                    $result['exception'] = 'Asignatura incorrecta';
                } elseif ($result['dataset'] = $Empleados_p->ObtenerActividades()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Empleado inexistente';
                }
                break;
            case 'readSinFiltros':
                if (!$Empleados_p->setid_empleado($_POST['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif ($result['dataset'] = $Empleados_p->readSinFiltros()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Empleado inexistente';
                }
                break;
            case 'readCargos':
                if ($result['dataset'] = $Empleados_p->readCargos()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'readAsignaturas_empleado':
                if (!$Empleados_p->setid_empleado($_POST['data'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif ($result['dataset'] = $Empleados_p->readAsignaturas_empleado()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Empleado inexistente';
                }
                break;
            case 'readAsignaturas':
                if ($result['dataset'] = $Empleados_p->readAsignaturas()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Empleado inexistente';
                }
                break;
            case 'readAsignaturasGrado':
                $_POST = Validator::validateForm($_POST);
                if ($result['dataset'] = $Empleados_p->readAsignaturasGrado($_POST['data'])) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Empleado inexistente';
                }
                break;
            case 'readGrados_empleado':
                if (!$Empleados_p->setid_empleado($_POST['data'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif ($result['dataset'] = $Empleados_p->readGrados_empleado()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Empleado inexistente';
                }
                break;
            case 'readGrados':
                if ($result['dataset'] = $Empleados_p->readGrados()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Empleado inexistente';
                }
                break;
            case 'search':
                $_POST = Validator::validateForm($_POST);
                //validar atributo
                $access = array('view_empleados');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //accion
                } elseif ($_POST['search'] == '') {
                    $result['exception'] = 'Ingrese un valor para buscar';
                } elseif ($result['dataset'] = $Empleados_p->searchRows($_POST['search'], $_POST['check'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay coincidencias';
                }
                break;
            case 'create':
                $_POST = Validator::validateForm($_POST);
                if (!$Empleados_p->setnombre_empleado($_POST['nombres'])) {
                    $result['exception'] = 'Nombre del empleado mal ingresado';
                } elseif (!$Empleados_p->setapellido_empleado($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos del empleado mal ingresada';
                } elseif (!$Empleados_p->setdui($_POST['dui'])) {
                    $result['exception'] = 'DUI mal ingresada';
                } elseif (!$Empleados_p->setusuario_empleado($_POST['usuario'])) {
                    $result['exception'] = 'Usuario mal ingresado';
                } elseif ($_POST['clave'] != $_POST['claveConfirm']) {
                    $result['exception'] = 'Las contraseñas no coinciden';
                } elseif (!$Empleados_p->setclave($_POST['clave'])) {
                    $result['exception'] = 'Clave mal ingresada';
                } elseif (!$Empleados_p->setcorreo_empleado($_POST['correo'])) {
                    $result['exception'] = 'Correo mal ingresado';
                } elseif (!$Empleados_p->setdireccion($_POST['direccion'])) {
                    $result['exception'] = 'Direccion mal ingresado';
                } elseif (!isset($_POST['cargo'])) {
                    $result['exception'] = 'Seleccione un tipo de actividad';
                } elseif (!$Empleados_p->setid_cargo($_POST['cargo'])) {
                    $result['exception'] = 'Cargo incorrecto';
                } elseif (!$Empleados_p->setfecha_nacimiento($_POST['fecha_nacimiento'])) {
                    $result['exception'] = 'Fecha incorrecta';
                } elseif ($Empleados_p->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Se ha creado correctamente';
                } else {
                    $result['exception'] = Database::getException();;
                }
                break;
            case 'readOne':
                if (!$Empleados_p->setid_empleado($_POST['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif ($result['dataset'] = $Empleados_p->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Empleado inexistente';
                }
                break;
            case 'update':
                $_POST = Validator::validateForm($_POST);
                if (!$Empleados_p->setid_empleado($_POST['id'])) {
                    $result['exception'] = 'id malo';
                } elseif (!$Empleados_p->setnombre_empleado($_POST['nombres'])) {
                    $result['exception'] = 'Nombre del empleado mal ingresado';
                } elseif (!$Empleados_p->setapellido_empleado($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos del empleado mal ingresada';
                } elseif (!$Empleados_p->setdui($_POST['dui'])) {
                    $result['exception'] = 'DUI mal ingresada';
                } elseif (!$Empleados_p->setusuario_empleado($_POST['usuario'])) {
                    $result['exception'] = 'Usuario mal ingresado';
                } /*elseif ($_POST['clave'] != $_POST['claveConfirm']) {
                    $result['exception'] = 'Las contraseñas no coinciden';
                } elseif (!$Empleados_p->setclave($_POST['clave'])) {
                    $result['exception'] = 'Clave mal ingresada';
                } */ elseif (!$Empleados_p->setcorreo_empleado($_POST['correo'])) {
                    $result['exception'] = 'Correo mal ingresado';
                } elseif (!$Empleados_p->setdireccion($_POST['direccion'])) {
                    $result['exception'] = 'Direccion mal ingresado';
                } elseif (!isset($_POST['cargo'])) {
                    $result['exception'] = 'Seleccione un tipo de actividad';
                } elseif (!$Empleados_p->setid_cargo($_POST['cargo'])) {
                    $result['exception'] = 'Cargo incorrecto';
                } elseif (!$Empleados_p->setfecha_nacimiento($_POST['fecha_nacimiento'])) {
                    $result['exception'] = 'Fecha incorrecta';
                } elseif ($Empleados_p->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Se ha actualizado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'delete':
                if (!$Empleados_p->setid_empleado($_POST['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$data = $Empleados_p->readOne()) {
                    $result['exception'] = 'Empleado inexistente';
                } elseif ($Empleados_p->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Empleado eliminado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'CargarDetalles':
                $_POST = Validator::validateForm($_POST);
                if ($result['dataset'] = $Empleados_p->CargarDetalles($_POST['id'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
                //opcion actualizar el id del docente en detalle asignatura, empleados
            case 'ActualizarDetalle':
                //(se reutiliza el setid_empleado para validar los numeros naturales)
                $_POST = Validator::validateForm($_POST);
                if (!$Empleados_p->setid_empleado($_POST['grado'])) {
                    $result['exception'] = 'grado Incorrecto';
                } elseif (!$Empleados_p->setid_empleado($_POST['asignatura'])) {
                    $result['exception'] = 'asignatura Incorrecta';
                } elseif (!$Empleados_p->setid_empleado($_POST['id'])) {
                    $result['exception'] = 'id Incorrecta';
                } elseif ($Empleados_p->ActualizarDetalle($_POST['asignatura'], $_POST['grado'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Se ha creado correctamente';
                } else {
                    $result['exception'] = Database::getException();;
                }
                break;
                //opción para consultar si el detalle que se desea actualizar esta ya asignado a otro docente
            case 'VerificarDetalle':
                //se valida que haya un formulario
                $_POST = Validator::validateForm($_POST);
                //se empieza a validar los parametros
                if (!$Empleados_p->setid_empleado($_POST['grado'])) {
                    $result['exception'] = 'grado Incorrecto';
                } elseif (!$Empleados_p->setid_empleado($_POST['asignatura'])) {
                    $result['exception'] = 'asignatura Incorrecta';
                } elseif (!$Empleados_p->setid_empleado($_POST['id'])) {
                    $result['exception'] = 'id Incorrecta';
                    //se ejecuta la consulta
                } elseif ($data = $Empleados_p->verificarDetalle($_POST['asignatura'], $_POST['grado'])) {
                    //se verifica el resultado de la consulta
                    if ($data['id_empleado'] == null) {
                        $result['status'] = 1;
                    } elseif ($data['id_empleado'] == $_POST['id']) {
                        $result['status'] = 0;
                        $result['message'] = "Esta asignatura y grado ya estan asignados a este docente";
                    } else {
                        $result['status'] = 2;
                        $result['message'] = "Esta asignatura y grado ya estan asignados a un docente, si asignas este docente, el otro docente será desasignado. ¿Deseas Continuar?";
                    }
                } else {
                    $result['exception'] = Database::getException();;
                }
                break;
                //eliminar el detalle de un empleado respecto a los grados y asignaturas
            case 'deleteAsignation':
                if (!$Empleados_p->setid_empleado($_POST['id'])) {
                    $result['exception'] = 'El id no es correcto';
                } elseif ($Empleados_p->deleteDetalle()) {
                    $result['status'] = 1;
                    $result['message'] = 'Asignación eliminada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
                break;
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
