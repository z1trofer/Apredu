<?php
require_once('../../entities/dto/usuarios.php');
require_once('../../entities/dto/permisos.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia las clases correspondiente.
    $usuario = new Usuarios;
    $permisos = new Permisos;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'exception' => null, 'dataset' => null, 'clave' => false);
    //arreglo para guardar los permisos de usuario
    $permisos = array();
    // se valida el estado de la sesión
    if (isset($_SESSION['id_empleado']) and Validator::validateSessionTime()) {
        //hay una sesión activa
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            //acción obtener los empleados registrados
            case 'readUsers':
                if ($usuario->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Error de autenticación';
                } else {
                    $result['exception'] = 'Debe crear un usuario para comenzar';
                }
                break;
                //acción obtener información de la sesión
            case 'getSession':
                if (isset($_SESSION['usuario'])) {
                    $result['status'] = 1;
                    $result['session'] = 1;
                    $result['usuario'] = $_SESSION['usuario'];
                    $result['tipo'] = $_SESSION['tipo'];
                    $result['id_cargo'] = $_SESSION['id_cargo'];
                    $result['id_empleado'] = $_SESSION['id_empleado'];
                    $result['empleado'] = $_SESSION['empleado'];
                    //$result['atributos_vista'] = $_SESSION['atributos_vista'];
                } else {
                    $result['exception'] = 'La sesión ya no es válida';
                }
                break;
                //obtener permisos de las vistas (navbar)
            case 'getPermisosVista':

                if (isset($_SESSION['usuario'])) {
                    $access = array('edit_permisos', 'view_grados', 'view_trimestres', 'view_asignaturas', 'view_actividades', 'view_empleados', 'view_estudiantes', 'view_responsables', 'view_fichas', 'view_notas');
                    if (!$usuario->setId($_SESSION['id_empleado'])) {
                        $result['exception'] = 'Error con el empleado';
                    } elseif ($result['dataset'] = $usuario->obtenerAtributosVista($access)) {
                        $result['status'] = 1;
                        $result['message'] = 'God';
                    } else {
                        $result['message'] = 'Error con los permisos' . Database::getException();
                    }
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
                //salir de la sesión
            case 'logOut':
                if (session_destroy()) {
                    $result['status'] = 1;
                    $result['message'] = 'Sesión eliminada correctamente';
                } else {
                    $result['exception'] = 'Ocurrió un problema al cerrar la sesión';
                }
                break;
                //obtener los datos del usuario
            case 'readProfile':
                if ($result['dataset'] = $usuario->readProfile()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Usuario inexistente';
                }
                break;
                //cambiar información del empleado
            case 'editProfile':
                $_POST = Validator::validateForm($_POST);
                if (!$usuario->setNombre_empleado($_POST['nombres'])) {
                    $result['exception'] = 'Nombre incorrecto';
                } elseif (!$usuario->setapellido_empleado($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos incorrectos';
                } elseif (!$usuario->setcorreo_empleado($_POST['correo'])) {
                    $result['exception'] = 'Correo incorrecto';
                } elseif (!$usuario->setusuario_empleado($_POST['usuario'])) {
                    $result['exception'] = 'Usuario incorrecto';
                } elseif ($usuario->editProfile()) {
                    $result['status'] = 1;
                    $_SESSION['usuario'] = $_POST['usuario'];
                    $result['message'] = 'Perfil modificado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
                //cambiar contraseña (perfil)
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
                    $result['message'] = 'Contraseña restablecida correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
                //crear un usuario/empleado
            case 'create':
                $_POST = Validator::validateForm($_POST);
                if (!$usuario->setUser($_POST['usuario'])) {
                    $result['exception'] = 'Alias incorrecto';
                } elseif ($_POST['contrasenia'] != $_POST['confirmar']) {
                    $result['exception'] = 'Claves diferentes';
                } elseif (!$usuario->setClave($_POST['contrasenia'])) {
                    $result['exception'] = Validator::getPasswordError();
                } elseif (!$usuario->setEstado($_POST['estado'])) {
                    $result['exeption'] = 'Estado incorrecto';
                } elseif ($usuario->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Usuario creado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        // no hay sesión activa
        switch ($_GET['action']) {
                //verificar si existen usuarios en la base
            case 'readUsers':
                if ($usuario->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Error de autenticación';
                } else {
                    $result['exception'] = 'Debe crear un usuario para comenzar';
                }
                break;
                //logear un usuario en sistemas
            case 'login':
                $_POST = Validator::validateForm($_POST);
                if (!$usuario->setUser($_POST['usuario'])) {
                    $result['exception'] = 'Ingrese un usuario';
                    //validando clave
                } elseif (!$usuario->setClaveLog($_POST['clave'])) {
                    $result['exception'] = 'Ingrese una contraseña';
                } elseif (!$data = $usuario->logIn($_POST['clave'])) {
                    $result['exception'] = 'Credenciales incorrectas';
                } else {
                    if ($data == false) {
                        //se dio un error en el servidor
                        $result['exception'] = 'Credenciales incorrectas';
                    } else if ($data == 'zzz') {
                        //el usuario esta bloqueado
                        $result['exception'] = 'Este usuario ha sido bloqueado. Contacta a los administradores para desbloquear el usuario';
                    } else if ($data == 'timer') {
                        //el usuario tiene un contador de tiempo para iniciar sesión
                        $result['exception'] = 'Ha intentado iniciar sessión demasiadas veces, espere un momento para volver a intentar';
                    } else if ($data == 'time') {
                        //el usuario intento iniciar sesión demasiadas veces por lo que se le pondra un contador de tiempo
                        if (!$usuario->agregarIntento()) {
                            $result['exception'] = 'Error al agregar el intento';
                        } elseif ($usuario->subirTiempoInicio(time())) {
                            $result['exception'] = 'Ha intentado iniciar sessión demasiadas veces, espere 30s para volver a intentar';
                        } else {
                            $result['exception'] = 'Error en el servidor';
                        }
                    } else if ($usuario->dias_clave >= 90) {
                        //el usuario tiene una contraseña vieja de mas de noventa dias, por lo que se le solicitará que cambie la contraseña
                        $_SESSION['id_empleado_clave'] = $usuario->getId();
                        $_SESSION['clave_caducada'] = $_POST['clave'];
                        $result['clave'] = true;
                        $result['exception'] = 'Su contraseña ha caducado';
                    } else if ($data == 'bloquear') {
                        //el usuario será bloqueado por acumular intentos fallidos.
                        if ($usuario->blockUser()) {
                            $result['exception'] = 'Ha intentado iniciar sessión demasiadas veces. Usuario ha sido bloqueado, por favor contacta a un administrador';
                        } else {
                            $result['exception'] = 'Error en el servidor';
                        }
                    } else if ($data == 'fail') {
                        //el usuario falló el intento de sesión al no introducir sus credenciales correctamente
                        if ($usuario->agregarIntento()) {
                            $result['exception'] = 'Las credenciales no coinciden';
                        } else {
                            $result['exception'] = 'Error en el servidor';
                        }
                    } elseif ($data != false) {
                        //el usuario inició sesión correctamente
                        if ($usuario->resetIntentos()) {
                            //se reinicia el contador de intentos para el usuario correspondinte
                            $result['status'] = 1;
                            $_SESSION['usuario'] = $usuario->getUser();
                            //$result['dataset'] = $data;
                            $_SESSION['empleado'] = $usuario->getEmpleado();
                            $_SESSION['correo_empleado'] = $usuario->getCorreo_empleado();
                            $_SESSION['tiempo'] = time();
                            $_SESSION['id_empleado_ad'] = $usuario->getId();
                            $_SESSION['ad'] = random_int(100000, 999999);
                            $mensaje = $_SESSION['ad'];
                            // Se envía el código al correo del usuario que inicio sesión en lo anterior...
                            if (Props::sendMail($usuario->getCorreo_empleado(), 'Código de autenticación', 'Hola, te saluda la asistencia del Colegio Aprendo Contigo, este tu código de verificación:', $mensaje)) {
                                $result['message'] = 'Credenciales correctas, revise su correo';
                            } else {
                                $result['exception'] = 'Ocurrió un problema al enviar el correo';
                            }
                        } else {
                            $result['exception'] = 'Error en el servidor';
                        }
                    } else {
                        $result['exception'] = Database::getException();
                    }
                }
                break;
                // Acción de segundo factor de seguridad "ad"
            case 'ad':
                $_POST = Validator::validateForm($_POST);
                // Se valida el código enviado y el código ingresado
                if ($_POST['codigo_verificacion'] != $_SESSION['ad']) {
                    $result['exception'] = 'Código incorrecto';
                } elseif ($usuario->checkAD($_SESSION['id_empleado_ad'])) {
                    //se obtienen los dataos faltantes de el empleado
                    unset($_SESSION['id_empleado_ad']);
                    unset($_SESSION['ad']);
                    $_SESSION['id_empleado'] = $usuario->getId();
                    $_SESSION['tipo'] = $usuario->getTipo_empleado();
                    $_SESSION['id_cargo'] = $usuario->getId_cargo();
                    $_SESSION['correo_empleado'] = $usuario->getCorreo_empleado();
                    $_SESSION['tiempo'] = time();
                    $result['status'] = 1;
                    $result['message'] = 'Autenticación correcta, ¡Bienvenido!';
                } else {
                    $result['exception'] = 'Usuario incorrecto';
                }
                break;
                //enviar Ping de recuperación de contraseña
            case 'crearEnviarPing':
                $_POST = Validator::validateForm($_POST);
                if (!$usuario->setUser($_POST['user-recu'])) {
                    $result['exception'] = 'Ingrese un usuario';
                    //validando usuario
                } elseif (!$usuario->getCorreo()) {
                    $result['exception'] = 'Este usuario no existe';
                } else {
                    //se genera el codigo usando un algoritmo
                    $_SESSION['pin_recu'] = random_int(1000000, 9999999);
                    //se envia el correo
                    if (Props::sendMail($usuario->getCorreo_empleado(), 'Código de recuperación', 'Hola, te saluda la asistencia del Colegio Aprendo Contigo, para que puedas recuperar tu cuenta te envimos el siguiente codigo:', $_SESSION['pin_recu'])) {
                        $_SESSION['intentos_recu'] = 0;
                        $result['status'] = 1;
                        $result['message'] = 'Se ha enviado un pin de recuperación a su correo electronico';
                    } else {
                        $result['exception'] = 'Ocurrió un problema al enviar el correo';
                    }
                }
                break;
                //Validación del codigo de recuperación
            case 'validarRecu':
                $_POST = Validator::validateForm($_POST);
                //se verifican los intentos del codigo
                if ($_SESSION['intentos_recu'] > 5) {
                    //se interrumpe el intento de recuperación y se cierra la sesión parcial
                    $result['exception'] = 'Su codigo de recuperación ya no es valido debido a que ha fallado muchas veces';
                    session_destroy();
                } elseif ($_POST['code-recu'] == $_SESSION['pin_recu']) {
                    //el codigo de recuperación es correcto
                    $_SESSION['pin-us'] = $_POST['code-recu'];
                    $result['status'] = 1;
                    $result['message'] = 'Por favor ingrese una nueva contraseña';
                } else {
                    //el codigo de recuperación es erroneo, se agrega un intento de recuperación fallido
                    $_SESSION['intentos_recu'] = $_SESSION['intentos_recu'] + 1;
                    $result['exception'] = 'El codigo de recuperación no coincide';
                }
                break;
                //cambiar contraseña (recuperación)
            case 'changePassword':
                $_POST = Validator::validateForm($_POST);
                //cambio de contraseña por dias y sesión
                if (isset($_SESSION['clave_caducada']) || isset($_SESSION['id_empleado'])) {
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
                        $result['message'] = 'Contraseña restablecida correctamente';
                    } else {
                        $result['exception'] = Database::getException();
                    }
                    //cambio de contraseña por recuperacion
                } elseif ($_SESSION['pin_recu'] == $_SESSION['pin-us']) {
                    if (!$usuario->setId($_SESSION['id_empleado_clave'])) {
                        $result['exception'] = 'Usuario incorrecto';
                    } elseif ($_POST['claveNueva'] != $_POST['confirmarNueva']) {
                        $result['exception'] = 'Claves nuevas diferentes';
                    } elseif (!$usuario->setClave($_POST['claveNueva'])) {
                        $result['exception'] = Validator::getPasswordError();
                    } elseif ($usuario->changePassword()) {
                        session_destroy();
                        $result['status'] = 1;
                        $result['message'] = 'Contraseña restablecida correctamente';
                    } else {
                        $result['exception'] = Database::getException();
                    }
                } else {
                    $result['exception'] = 'No tienes autorización para realizar esta acción';
                }
                break;
                //obtener información de la sesión
            case 'getSession':
                if (isset($_SESSION['usuario'])) {
                    $result['status'] = 1;
                    $result['session'] = 1;
                } else {
                    $result['exception'] = 'La sesión ya no es válida';
                }
                break;
                //registrar un empleado (primer uso)
            case 'signup':
                $_POST = Validator::validateForm($_POST);
                if ($usuario->readAll()) {
                    $result['exception'] = "Ya existen usuarios registrados";
                } elseif (!$usuario->setNombre_empleado($_POST['nombres'])) {
                    $result['exception'] = 'Nombre incorrecto';
                } elseif (!$usuario->setapellido_empleado($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos incorrectos';
                } elseif (!$usuario->setdui($_POST['dui'])) {
                    $result['exception'] = 'DUI incorrecto';
                } elseif (!$usuario->setUser($_POST['usuario'])) {
                    $result['exception'] = 'Usuario incorrecto';
                } elseif ($_POST['clave'] != $_POST['claveConfirm']) {
                    $result['exception'] = 'Las contraseñas no coinciden';
                } elseif (!$usuario->setclave($_POST['clave'])) {
                    $result['exception'] = Validator::getPasswordError();
                } elseif (!$usuario->setcorreo_empleado($_POST['correo'])) {
                    $result['exception'] = 'Correo incorrecto';
                } elseif (!$usuario->setDireccion($_POST['direccion'])) {
                    $result['exception'] = 'Direccion incorrecta';
                } elseif (!isset($_POST['cargo'])) {
                    $result['exception'] = 'Seleccione un tipo de actividad';
                } elseif (!$usuario->setid_cargo($_POST['cargo'])) {
                    $result['exception'] = 'Cargo incorrecto';
                } elseif (!$usuario->setfecha_nacimiento($_POST['fecha_nacimiento'])) {
                    $result['exception'] = 'Fecha incorrecta';
                } elseif ($usuario->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Usuario registrado correctamente';
                } else {
                    $result['exception'] = Database::getException();;
                }
                break;
                //obtener los cargos
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
                //cerrar sesión
                case 'logOut':
                    if (session_destroy()) {
                        $result['status'] = 1;
                        $result['message'] = 'Sesión eliminada correctamente';
                    } else {
                        $result['exception'] = 'Ocurrió un problema al cerrar la sesión';
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
