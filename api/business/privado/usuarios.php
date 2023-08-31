<?php
require_once('../../entities/dto/usuarios.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $usuario = new Usuarios;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'exception' => null, 'dataset' => null);
    //arreglo para guardar los permisos de usuario
    $permisos = array();
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_empleado'])) {
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
                if (!$usuario->setUser($_POST['usuario'])) {
                    $result['exception'] = 'Alias incorrecto';
                } elseif ($usuario->editProfile()) {
                    $result['status'] = 1;
                    $_SESSION['alias_usuario'] = $usuario->getUser();
                    $result['message'] = 'Perfil modificado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'changePassword':
                $_POST = Validator::validateForm($_POST);
                if (!$usuario->setId($_SESSION['id_usuario_administrador'])) {
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
            case 'login':
                $_POST = Validator::validateForm($_POST);
                if (!$usuario->setUser($_POST['usuario'])) {
                    $result['exception'] = 'No hay coincidencia con las credenciales ingresadas';
                } elseif (!$usuario->setClave($_POST['clave'])) {
                    $result['exception'] = 'No hay coincidencia con las credenciales ingresadas';
                } else {
                    $data = $usuario->LogIn($_POST['clave']);
                    if ($data == false) {
                        $result['exception'] = 'Clave incorrecta';
                    } else if ($data == 'zzz') {
                        $result['exception'] = 'No hay coincidencia con las credenciales ingresadas';
                    } elseif ($data != false) {
                        $_SESSION['id_empleado'] = $usuario->getId();
                        $_SESSION['usuario'] = $usuario->getUser();
                        $_SESSION['tipo'] = $usuario->getTipo_empleado();
                        $_SESSION['id_cargo'] = $usuario->getId_cargo();
                        $_SESSION['empleado'] = $usuario->getEmpleado();
                        $result['dataset'] = $data;
                        $result['status'] = 1;
                        $result['message'] = 'Autenticación correcta, ¡Bienvenido!';
                    } else {
                        $result['exception'] = Database::getException();
                    }
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
?>