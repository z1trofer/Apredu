<?php
require_once('../../entities/dto/responsablesVista.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $responsable = new ResponsablesVista;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null, 'dataset' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_empleado'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'readAll':
                if ($result['dataset'] = $responsable->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen '.count($result['dataset']).' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
                //Acción para crear un nueva subcategoría 
                case 'create':
                    $_POST = Validator::validateForm($_POST);
                    if (!$responsable->setNombresResponsable($_POST['nombres'])) {
                        $result['exception'] = 'Nombres incorrectos';
                    } else if (!$responsable->setApellidosResponsable($_POST['apellidos'])) {
                        $result['exception'] = 'Apellidos incorrectos';
                    }else if (!$responsable->setDui($_POST['dui'])) {
                        $result['exception'] = 'Dui incorrectos';
                    }
                    else if (!$responsable->setCorreo($_POST['correo'])) {
                        $result['exception'] = 'Correo incorrectos';
                    }
                    else if (!$responsable->setLugarTrabajo($_POST['lugar'])) {
                        $result['exception'] = 'Lugar incorrectos';
                    }
                    else if (!$responsable->setTelefonoTrabajo($_POST['telefono'])) {
                        $result['exception'] = 'Telefono incorrectos';
                    }
                    else if (!$responsable->setParentesco($_POST['parentesco'])) {
                        $result['exception'] = 'Parentesco incorrectos';
                    }
                    elseif ($responsable->createRow()) {
                        $result['status'] = 1;
                        $result['message'] = 'Responsable creado correctamente';
                    } else {
                        $result['exception'] = Database::getException();
                    }
                    break;
                //leer un dato seleccionado para luego actualizarlo o solo leer la información 
            case 'readOne':
                if (!$responsable->setIdResponsable($_POST['id_responsable'])) {
                    $result['exception'] = 'responsable incorrecto';
                    print_r($_POST);
                } elseif ($result['dataset'] = $responsable->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'responsable inexistente';
                }
                break;
                //Acción para actualizar un dato de la tabla usuarios
                case 'update':
                    $_POST = Validator::validateForm($_POST);
                    if (!$responsable->setIdResponsable($_POST['id_responsable'])) {
                        $result['exception'] = 'Responsable incorrecto';
                    } elseif (!$responsable->readOne()) {
                        $result['exception'] = 'Responsable inexistente';
                    }else if (!$responsable->setNombresResponsable($_POST['asignatura'])) {
                        $result['exception'] = 'Nombres incorrectos';
                    } else if (!$responsable->setApellidosResponsable($_POST['asignatura'])) {
                        $result['exception'] = 'Apellidos incorrectos';
                    }else if (!$responsable->setDui($_POST[''])) {
                        $result['exception'] = 'Dui incorrectos';
                    } else if (!$responsable->setCorreo($_POST[''])) {
                        $result['exception'] = 'Correo incorrectos';
                    }else if (!$responsable->setLugarTrabajo($_POST[''])) {
                        $result['exception'] = 'Lugar incorrectos';
                    } else if (!$responsable->setTelefonoTrabajo($_POST[''])) {
                        $result['exception'] = 'Telefono incorrectos';
                    }else if (!$responsable->setParentesco($_POST[''])) {
                        $result['exception'] = 'Parentesco incorrectos';
                    }elseif ($responsable->updateRow()) {
                        $result['status'] = 1;
                        $result['message'] = 'Asignatura modificada correctamente';
                    } else {
                        $result['exception'] = Database::getException();
                    }
                    break;
                    //Acción para eliminar un dato de la tabla usuarios
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
