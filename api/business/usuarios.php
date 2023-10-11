<?php
require_once('../entities/dto/usuarios.php');
require_once('../entities/dto/permisos.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia las clases correspondientes.
    $usuario = new Usuarios;
    $permisos = new Permisos;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'exception' => null, 'dataset' => null, 'clave' => false);
    // se valida el estado de la sesión 
    if (isset($_SESSION['id_empleado']) and Validator::validateSessionTime()) {
        //hay una sesión activa
        $result['session'] = 1;
        // Se compara la acción a realizar cuando hay una session
        switch ($_GET['action']) {
                //obtener los empleados registrados
            case 'readUsers':
                if ($usuario->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Error de autenticación';
                } else {
                    $result['exception'] = 'Debe crear un usuario para comenzar';
                }
                break;
                //obtener información de la variable SESSION
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
                //se verifica la sessión
                if (isset($_SESSION['usuario'])) {
                    //se declaran todos los permisos necesarios
                    $access = array('edit_permisos', 'view_grados', 'view_trimestres', 'view_asignaturas', 'view_actividades', 'view_empleados', 'view_estudiantes', 'view_responsables', 'view_fichas', 'view_notas');
                    if (!$usuario->setId($_SESSION['id_empleado'])) {
                        //se valida el id empleado
                        $result['exception'] = 'Error con el empleado';
                    } elseif ($result['dataset'] = $usuario->obtenerAtributosVista($access)) {
                        //acceso concedido
                        $result['status'] = 1;
                        $result['message'] = 'God';
                    } else {
                        //acceso denegado
                        $result['message'] = 'Acceso denegado' . Database::getException();
                    }
                } else {
                    $result['exception'] = 'La sesión ya no es válida';
                }
                break;
                //salir de la sesión
            case 'logOut':
                //se reinicia la variable session
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
                //cambiar información del empleado (desde perfil)
            case 'editProfile':
                //se validan los especios en el formulario
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la acción
                $access = array('edit_perfil');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    //acceso denegado
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se validan los parametros
                } elseif (!$usuario->setNombre_empleado($_POST['nombres'])) {
                    $result['exception'] = 'Nombre incorrecto';
                } elseif (!$usuario->setapellido_empleado($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos incorrectos';
                } elseif (!$usuario->setcorreo_empleado($_POST['correo'])) {
                    $result['exception'] = 'Correo incorrecto';
                } elseif (!$usuario->setusuario_empleado($_POST['usuario'])) {
                    $result['exception'] = 'Usuario incorrecto';
                    //se ejecuta la acción
                } elseif ($usuario->editProfile()) {
                    //proceso exitoso
                    $result['status'] = 1;
                    $_SESSION['usuario'] = $_POST['usuario'];
                    $result['message'] = 'Perfil modificado correctamente';
                } elseif (Database::getException()) {
                    //error de base de datos
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = "No se pudo actualizar el perfil";
                }
                break;
                //cambiar contraseña (desde perfil)
            case 'changePassword':
                //se validan los espeacios en el formularios
                $_POST = Validator::validateForm($_POST);
                //se declaran los permisos necesarios para la accion
                $access = array('edit_perfil');
                if (!$permisos->setid($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$permisos->getPermissions(($access))) {
                    //se deniega el acceso
                    $result['exception'] = 'No tienes autorizacion para realizar esta acción';
                    //se validan los parametros
                } elseif (!$usuario->setId($_SESSION['id_empleado'])) {
                    $result['exception'] = 'Usuario incorrecto';
                } elseif ($_POST['nueva'] == $_POST['actual']) {
                    $result['exception'] = 'La clave nueva debe ser diferente a la actual.';
                } elseif (!$usuario->checkPassword($_POST['actual'])) {
                    $result['exception'] = 'Clave actual incorrecta';
                } elseif ($_POST['nueva'] != $_POST['confirmar']) {
                    $result['exception'] = 'Claves nuevas diferentes';
                } elseif (!$usuario->setClave($_POST['nueva'])) {
                    $result['exception'] = Validator::getPasswordError();
                    //se ejecuta la acción
                } elseif ($usuario->changePassword()) {
                    //proceso exitoso
                    $result['status'] = 1;
                    $result['message'] = 'Contraseña restablecida correctamente';
                } elseif (Database::getException()) {
                    //error de base de datos
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = "No se pudo cambiar la contraseña";
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        //acciones cuando no hay una sesión activa
        switch ($_GET['action']) {
                //verificar si existen usuarios en la base (primer uso)
            case 'readUsers':
                if ($usuario->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Error de autenticación';
                } else {
                    $result['exception'] = 'Debe crear un usuario para comenzar';
                }
                break;
                //logear un usuario en la pagina
            case 'login':
                //se validan los espacios en el formulario
                $_POST = Validator::validateForm($_POST);
                //se si hay usuario ingresado
                if (!$usuario->setUser($_POST['usuario'])) {
                    $result['exception'] = 'Ingrese un usuario';
                    //se valida que haya contraseña ingresada
                } elseif (!$usuario->setClaveLog($_POST['clave'])) {
                    $result['exception'] = 'Ingrese una contraseña';
                } elseif (!$data = $usuario->logIn($_POST['clave'])) {
                    //ubo un error de base de datos
                    $result['exception'] = 'Las credenciales no coinciden';
                } else {
                    if ($data == false) {
                        //se dio un error en el servidor
                        $result['exception'] = 'Las credenciales no coinciden';
                    } else if ($data == 'zzz') {
                        //el usuario esta bloqueado
                        $result['exception'] = 'Este usuario ha sido bloqueado. Contacta a los administradores para desbloquear el usuario';
                    } else if ($data == 'timer') {
                        //el usuario tiene un contador de tiempo para iniciar sesión
                        $result['exception'] = 'Ha intentado iniciar sessión demasiadas veces, espere un momento para volver a intentar';
                    } else if ($data == 'time') {
                        //el usuario intento iniciar sesión demasiadas veces por lo que se le pondra un contador de tiempo
                        if (!$usuario->agregarIntento()) {
                            //se agrega un intento
                            $result['exception'] = 'Error al agregar el intento';
                        } elseif ($usuario->subirTiempoInicio(time())) {
                            //se sube el contador con el tiempo actual
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
                        //el usuario ingresó contraseñas validas
                        if ($usuario->resetIntentos()) {
                            //se reinicia el contador de intentos para el usuario correspondinte
                            $result['status'] = 1;
                            $_SESSION['usuario'] = $usuario->getUser();
                            $_SESSION['empleado'] = $usuario->getEmpleado();
                            $_SESSION['correo_empleado'] = $usuario->getCorreo_empleado();
                            $_SESSION['tiempo'] = time();
                            $_SESSION['id_empleado_ad'] = $usuario->getId();
                            $_SESSION['2fa_intentos'] = 0;
                            $_SESSION['ad'] = random_int(10000000, 99999999);
                            $mensaje = $_SESSION['ad'];
                            // Se envía el código al correo del usuario que inicio sesión para validarse
                            if (Props::sendMail($usuario->getCorreo_empleado(), 'Código de autenticación', 'Hola, te saluda la asistencia del Colegio Aprendo Contigo, este tu código de verificación:', $mensaje)) {
                                //correo enviado
                                $result['message'] = 'Credenciales correctas, revise su correo';
                            } else {
                                $result['exception'] = 'Ocurrió un problema al enviar el correo';
                            }
                        } else {
                            $result['exception'] = 'Error en el servidor';
                        }
                    } else {
                        $result['exception'] = "error al iniciar";
                    }
                }
                break;
                // Acción de segundo factor de seguridad
            case 'ad':
                //se validan los espacios del formulario
                $_POST = Validator::validateForm($_POST);
                //se valida los intentos de inicio
                if ($_SESSION['2fa_intentos'] > 4) {
                    $result['exception'] = 'El codigo ha sido bloqueado debido a que has fallado demasiadas veces. Por favor reinicia el proceso de inicio de sesión';
                } //se comparan los codigos de autenticación 
                elseif ($_POST['codigo_verificacion'] != $_SESSION['ad']) {
                    //de no ser iguales se agrega un intento de incicio
                    $_SESSION['2fa_intentos'] = $_SESSION['2fa_intentos'] + 1;
                    $result['exception'] = 'Código incorrecto';
                    //se obtienen los dataos faltantes de el empleado
                } elseif ($usuario->checkAD($_SESSION['id_empleado_ad'])) {
                    //se borrar los valores de "ad"
                    //proceso exitoso
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
                //se validan los datos del formulario
                $_POST = Validator::validateForm($_POST);
                //validando usuario
                if (!$usuario->setUser($_POST['user-recu'])) {
                    $result['exception'] = 'Ingrese un usuario';
                    //validando correo
                } elseif (!$usuario->getCorreo()) {
                    $result['exception'] = 'Este usuario no existe';
                } else {
                    //se genera el codigo usando un algoritmo
                    $_SESSION['pin_recu'] = random_int(1000000, 9999999);
                    //se envia el correo
                    if (Props::sendMail($usuario->getCorreo_empleado(), 'Código de recuperación', 'Hola, te saluda la asistencia del Colegio Aprendo Contigo, para que puedas recuperar tu cuenta te envimos el siguiente codigo:', $_SESSION['pin_recu'])) {
                        //se crea un valor de intentos
                        //proceso exitoso
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
                    //se interrumpe el intento de recuperación y se cierra la sesión
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
                //cambiar contraseña
            case 'changePassword':
                $_POST = Validator::validateForm($_POST);
                //cambio de contraseña por dias y sesión
                if (isset($_SESSION['clave_caducada']) || isset($_SESSION['id_empleado'])) {
                    //verifica el id del empleado
                    if (!$usuario->setId($_SESSION['id_empleado_clave'])) {
                        $result['exception'] = 'Usuario incorrecto';
                    //se compara que la clave no sea igual a la clave antigua
                    } elseif ($_POST['claveNueva'] == $_SESSION['clave_caducada']) {
                        $result['exception'] = 'La clave nueva debe ser diferente a la anterior.';
                    //se comparan la confirmación de clave
                    } elseif ($_POST['claveNueva'] != $_POST['confirmarNueva']) {
                        $result['exception'] = 'Claves nuevas diferentes';
                    //se encapsula el dato
                    } elseif (!$usuario->setClave($_POST['claveNueva'])) {
                        $result['exception'] = Validator::getPasswordError();
                    //se ejecuta la acción
                    } elseif ($usuario->changePassword()) {
                        //operación exitosa
                        session_destroy();
                        $result['status'] = 1;
                        $result['message'] = 'Contraseña restablecida correctamente';
                    } else {
                        $result['exception'] = Database::getException();
                    }
                    //cambio de contraseña por recuperacion
                    //se valida nuevamente el pin de recuperación
                } elseif ($_SESSION['pin_recu'] == $_SESSION['pin-us']) {
                    //se valida el usuario
                    if (!$usuario->setId($_SESSION['id_empleado_clave'])) {
                        $result['exception'] = 'Usuario incorrecto';
                        //se compara la confirmación de clave
                    } elseif ($_POST['claveNueva'] != $_POST['confirmarNueva']) {
                        $result['exception'] = 'Claves nuevas diferentes';
                        //se encapsula el dato
                    } elseif (!$usuario->setClave($_POST['claveNueva'])) {
                        $result['exception'] = Validator::getPasswordError();
                        //se ejecuta la acción
                    } elseif ($usuario->changePassword()) {
                        //proceso exitoso
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
                //se validan los espacios en el formualrio
                $_POST = Validator::validateForm($_POST);
                //se verifica que no haya empleados
                if ($usuario->readAll()) {
                    $result['exception'] = "Ya existen usuarios registrados";
                    //se validan los datos
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
                } elseif (!$usuario->setTelefono($_POST['telefono'])) {
                    $result['exception'] = 'Correo incorrecto';
                } elseif (!isset($_POST['cargo'])) {
                    $result['exception'] = 'Seleccione un cargo';
                } elseif (!$usuario->setid_cargo($_POST['cargo'])) {
                    $result['exception'] = 'Cargo incorrecto';
                } elseif (!$usuario->setfecha_nacimiento($_POST['fecha_nacimiento'])) {
                    $result['exception'] = 'Fecha incorrecta';
                    //se ejecuta el proceso
                } elseif ($usuario->createRow()) {
                    //proceso exitoso
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
