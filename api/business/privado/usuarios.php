<?php
require_once('../../entities/dto/usuarios.php');
require_once('../../entities/dto/permisos.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $usuario = new Usuarios;
    $permisos = new Permisos;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'exception' => null, 'dataset' => null, 'clave' => false);
    //arreglo para guardar los permisos de usuario
    $permisos = array();
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_empleado']) and Validator::validateSessionTime()) {
        //se identifica que hay una session iniciada
        $result['session'] = 1;
        //se obtiene el arreglo con los permisos del respectivo usuario
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {

            case 'getSession':
                if (isset($_SESSION['usuario'])) {
                    $result['status'] = 1;
                    $result['session'] = 1;
                    $result['usuario'] = $_SESSION['usuario'];
                    $result['tipo'] = $_SESSION['tipo'];
                    $result['id_cargo'] = $_SESSION['id_cargo'];
                    $result['id_empleado'] = $_SESSION['id_empleado'];
                    $result['nombre'] = $_SESSION['empleado'];
                    //$result['atributos_vista'] = $_SESSION['atributos_vista'];
                } else {
                    $result['exception'] = 'La sesión ya no es válida';
                }
                break;
            case 'getPermisosVista':
                if (isset($_SESSION['usuario'])) {
                    $access = array('edit_permisos', 'view_grados', 'view_trimestres', 'view_asignaturas', 'view_actividades', 'view_empleados', 'view_estudiantes', 'view_responsables','view_fichas', 'view_notas');
                    if(!$usuario->setId($_SESSION['id_empleado'])){
                        $result['exception'] = 'Error con el empleado';
                    }
                    elseif($result['dataset'] = $usuario->obtenerAtributosVista($access))
                    $result['status'] = 1;
                    $result['message'] = 'God';
                } else {
                    $result['exception'] = 'La sesión ya no es válida';
                }
                break;
                //Obtener el usuario administrador
            case 'getUser':
                if (isset($_SESSION['usuario'])) {
                    $result['status'] = 1;
                    $result['usuario'] = $_SESSION['usuario'];
                    $result['tipo'] = $_SESSION['tipo'];
                } else {
                    $result['exception'] = 'Alias de usuario indefinido';
                }
                break;
            case 'logOut':
                if (session_destroy()) {
                    $result['status'] = 1;
                    $result['message'] = 'Sesión eliminada correctamente';
                } else {
                    $result['exception'] = 'Ocurrió un problema al cerrar la sesión';
                }
                break;
            case 'readProfile':
                if ($result['dataset'] = $usuario->readProfile()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Usuario inexistente';
                }
                break;
            case 'editProfile':
                $_POST = Validator::validateForm($_POST);
                if (!$usuario->setNombre_empleado($_POST['nombres'])) {
                    $result['exception'] = 'Nombre incorrecto';
                } elseif (!$usuario->setapellido_empleado($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos incorrecto';
                } elseif (!$usuario->setcorreo_empleado($_POST['correo'])) {
                    $result['exception'] = 'Correo incorrecto';
                } elseif (!$usuario->setusuario_empleado($_POST['usuario'])) {
                    $result['exception'] = 'usuario incorrecto';
                } elseif ($usuario->editProfile()) {
                    $result['status'] = 1;
                    $_SESSION['usuario'] = $_POST['usuario'];
                    $result['message'] = 'Perfil modificado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'changePassword':
                $_POST = Validator::validateForm($_POST);
                if (!$usuario->setId($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Usuario incorrecto';
                } elseif (!$usuario->checkPassword($_POST['actual'])) {
                    $result['exception'] = 'Clave actual incorrecta';
                } elseif ($_POST['nueva'] != $_POST['confirmar']) {
                    $result['exception'] = 'Claves nuevas diferentes';
                } elseif (!$usuario->setClave($_POST['nueva'])) {
                    $result['exception'] = Validator::getPasswordError();
                } elseif ($usuario->changePassword()) {
                    $result['status'] = 1;
                    $result['message'] = 'Contraseña cambiada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $usuario->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'readAdmin':
                if ($result['dataset'] = $usuario->readAdmin()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'readEst':
                if ($result['dataset'] = $usuario->readEst()) {
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
                if ($result['dataset'] = $usuario->searchRows($_POST['search'])) {
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
                if (!$usuario->setUser($_POST['usuario'])) {
                    $result['exception'] = 'Alias incorrecto';
                } elseif ($_POST['contrasenia'] != $_POST['confirmar']) {
                    $result['exception'] = 'Claves diferentes';
                } elseif (!$usuario->setClave($_POST['contrasenia'])) {
                    $result['exception'] = Validator::getPasswordError();
                } elseif (!$usuario->setEstado($_POST['estado'])) {
                    $result['exeption'] = 'Estado malo';
                } elseif ($usuario->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Usuario creado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'readOne':
                if (!$usuario->setId($_POST['id_usuario_administrador'])) {
                    $result['exception'] = 'Usuario incorrecto';
                } elseif ($result['dataset'] = $usuario->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Usuario inexistente';
                }
                break;
            case 'update':
                $_POST = Validator::validateForm($_POST);
                if (!$usuario->setId($_POST['id'])) {
                    $result['exception'] = 'Usuario incorrecto';
                } elseif (!$usuario->setUser($_POST['usuario'])) {
                    $result['exception'] = 'Alias incorrecto';
                } elseif (!$usuario->setEstado($_POST['estado'])) {
                    $result['exeption'] = 'Estado malo';
                } elseif ($usuario->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Usuario creado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'delete':
                if ($_POST['id_usuario_administrador'] == $_SESSION['id_usuario_administrador']) {
                    $result['exception'] = 'No se puede eliminar a sí mismo';
                } elseif (!$usuario->setId($_POST['id_usuario_administrador'])) {
                    $result['exception'] = 'Usuario incorrecto';
                } elseif (!$usuario->readOne()) {
                    $result['exception'] = 'Usuario inexistente';
                } elseif ($usuario->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Usuario eliminado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        // Se compara la acción a realizar cuando el administrador no ha iniciado sesión.
        switch ($_GET['action']) {
            case 'readUsers':
                if ($usuario->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Debe autenticarse para ingresar';
                } else {
                    $result['exception'] = 'Debe crear un usuario para comenzar';
                }
                break;
            case 'login':
                //$_POST = Validator::validateForm($_POST);
                //validando usuario
                if (!$usuario->setUser($_POST['usuario'])) {
                    $result['exception'] = 'Ingrese un usuario';
                    //validando clave
                } elseif (!$usuario->setClaveLog($_POST['clave'])) {
                    $result['exception'] = 'Ingrese una contraseña';
                } elseif (!$data = $usuario->LogIn($_POST['clave'])) {
                    $result['exception'] = 'error en el servidor';
                } else {
                    if ($data == false) {
                        //se dio un error en el servidor
                        $result['exception'] = 'Error en el servidor';
                    } else if ($data == 'zzz') {
                        //usuario bloqueado
                        $result['exception'] = 'Este usuario ha sido bloqueado. Contacta con los administradores para desbloquear el usuario';
                    } else if ($data == 'timer') {
                        //usuario con temporizador
                        $result['exception'] = 'Ha intentado iniciar sessión demasiadas espere un momento para volver a intentar';
                    } else if ($data == 'time') {
                        //el usuario intento iniciar sesion 5 veces seguidas por lo que se le dara un cd para vovler a intentarlo
                        if (!$usuario->agregarIntento()) {
                            $result['exception'] = 'error al agregar el intento';
                        } elseif ($usuario->subirTiempoInicio(time())) {
                            $result['exception'] = 'Ha intentado iniciar sessión demasiadas espere 30s para volver a intentar aaaa';
                        } else {
                            $result['exception'] = 'Error en el servidor time';
                        }
                        //$result['exception'] = 'Has intentado iniciar sesión demasiadas veces. Espera 30 s para volver a intentarlo'/*.$GLOBALS['tiempo_inicio']*/;
                    } else if ($usuario->dias_clave >= 90) {
                        $_SESSION['id_empleado_clave'] = $usuario->getId();
                        $_SESSION['clave_caducada'] = $_POST['clave'];
                        $result['clave'] = true;
                        $result['exception'] = 'Su contraseña ha caducado';
                    } else if ($data == 'time') {
                        //el usuario intento iniciar sesion 5 veces seguidas por lo que se le dara un cd para vovler a intentarlo
                        $usuario->agregarIntento();
                        $_SERVER['tiempo_inicio'] = time();
                        $result['exception'] = 'Has intentado iniciar sesión demasiadas veces. Espera 30 s para volver a intentarlo' /*.$GLOBALS['tiempo_inicio']*/;
                    } else if ($data == 'bloquear') {
                        //el usuario intento iniciar sesion demasiadas veces por lo que este sera bloqueado
                        if ($usuario->blockUser()) {
                            $result['exception'] = 'Ha intentado iniciar sessión demasiadas veces por lo que su usuario ha sido bloquedo, por favor contactate con un administrador';
                        } else {
                            $result['exception'] = 'Error en el servidor bloq';
                        }
                    } else if ($data == 'fail') {
                        //las credenciales no coincidieron por lo que el usuario no logro iniciar sesion
                        if ($usuario->agregarIntento()) {
                            $result['exception'] = 'No hay coincidencia con las credenciales ingresadas fail' /*.$GLOBALS['tiempo_inicio']*/;
                        } else {
                            $result['exception'] = 'Error en el servidor Int';
                        }
                    } elseif ($data != false) {
                        /*if(!$_SESSION['atributos_vista'] = $usuario->obtenerAtributosVista()){
                            $result['exception'] = 'Error al obtener los atributos del usuario';
                        } else*/if ($usuario->resetIntentos()) {
                             //el usuario inicio sesion satisfactoriamente
                            $_SESSION['id_empleado'] = $usuario->getId();
                            $_SESSION['usuario'] = $usuario->getUser();
                            $_SESSION['tipo'] = $usuario->getTipo_empleado();
                            $_SESSION['id_cargo'] = $usuario->getId_cargo();
                            $_SESSION['empleado'] = $usuario->getEmpleado();
                            $_SESSION['correo_empleado'] = $usuario->getCorreo_empleado();
                            $_SESSION['tiempo'] = time();
                            $result['dataset'] = $data;
                            $result['status'] = 1;
                            $result['message'] = 'Autenticación correcta, ¡Bienvenido!';
                        } else {
                            $result['exception'] = 'Error en el servidor resInt';
                        }
                    } else {
                        $result['exception'] = Database::getException();
                    }
                }
                break;
            case 'changePassword':
                $_POST = Validator::validateForm($_POST);
                if (!$usuario->setId($_SESSION['id_empleado_clave'])) {
                    $result['exception'] = 'Usuario incorrecto';
                } elseif ($_POST['claveNueva'] == $_SESSION['clave_caducada']) {
                    $result['exception'] = 'La clave nueva debe ser diferente a la anterior.';
                } elseif ($_POST['claveNueva'] != $_POST['confirmarNueva']) {
                    $result['exception'] = 'Claves nuevas diferentes';
                } elseif (!$usuario->setClave($_POST['claveNueva'])) {
                    $result['exception'] = Validator::getPasswordError();
                } elseif ($usuario->changePassword()) {
                    session_destroy();
                    $result['status'] = 1;
                    $result['message'] = 'Contraseña cambiada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'logOut':
                if (session_destroy()) {
                    $result['status'] = 1;
                    $result['message'] = 'Sesión eliminada correctamente';
                } else {
                    $result['exception'] = 'Ocurrió un problema al cerrar la sesión';
                }
                break;
            case 'getSession':
                if (isset($_SESSION['usuario'])) {
                    $result['status'] = 1;
                    $result['session'] = 1;
                } else {
                    $result['exception'] = 'La sesión ya no es válida';
                }
                break;
            case 'signup':
                $_POST = Validator::validateForm($_POST);
                if ($usuario->readAll()) {
                    $result['exception'] = "Ya existen usuarios registrados";
                } elseif (!$usuario->setNombre_empleado($_POST['nombres'])) {
                    $result['exception'] = 'Nombre del empleado mal ingresado';
                } elseif (!$usuario->setapellido_empleado($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos del empleado mal ingresada';
                } elseif (!$usuario->setdui($_POST['dui'])) {
                    $result['exception'] = 'DUI mal ingresada';
                } elseif (!$usuario->setUser($_POST['usuario'])) {
                    $result['exception'] = 'Usuario mal ingresado';
                } elseif ($_POST['clave'] != $_POST['claveConfirm']) {
                    $result['exception'] = 'Las contraseñas no coinciden';
                } elseif (!$usuario->setclave($_POST['clave'])) {
                    $result['exception'] = Validator::getPasswordError();
                } elseif (!$usuario->setcorreo_empleado($_POST['correo'])) {
                    $result['exception'] = 'Correo mal ingresado';
                } elseif (!$usuario->setDireccion($_POST['direccion'])) {
                    $result['exception'] = 'Direccion mal ingresado';
                } elseif (!isset($_POST['cargo'])) {
                    $result['exception'] = 'Seleccione un tipo de actividad';
                } elseif (!$usuario->setid_cargo($_POST['cargo'])) {
                    $result['exception'] = 'Cargo incorrecto';
                } elseif (!$usuario->setfecha_nacimiento($_POST['fecha_nacimiento'])) {
                    $result['exception'] = 'Fecha incorrecta';
                } elseif ($usuario->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Se ha creado correctamente';
                } else {
                    $result['exception'] = Database::getException();;
                }
                break;
            case 'readCargos':
                if ($result['dataset'] = $usuario->readCargos()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible fuera de la sesión';
        }
    }
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}
