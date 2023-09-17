<?php
require_once('../../entities/dto/responsables_vista.php');
require_once('../../entities/dto/permisos.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $responsable = new ResponsablesVista;
    $permisos = new Permisos;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null, 'dataset' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_empleado'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'readAll':
                if ($result['dataset'] = $responsable->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
                //Acción para crear un nuevo Responsable 
            case 'create':
                $_POST = Validator::validateForm($_POST);
                if (!$responsable->setNombresResponsable($_POST['nombres'])) {
                    $result['exception'] = 'Nombres incorrectos';
                } elseif (!$responsable->setApellidosResponsable($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos incorrectos';
                } elseif (!$responsable->setDui($_POST['dui'])) {
                    $result['exception'] = 'DUI incorrecto';
                } elseif (!$responsable->setCorreo($_POST['correo'])) {
                    $result['exception'] = 'Correo incorrecto';
                } elseif (!$responsable->setLugarTrabajo($_POST['lugar'])) {
                    $result['exception'] = 'Lugar incorrecto';
                } elseif (!$responsable->setTelefonoTrabajo($_POST['telefono'])) {
                    $result['exception'] = 'Telefono incorrecto';
                } elseif (!$responsable->setParentesco($_POST['parentesco'])) {
                    $result['exception'] = 'Parentesco incorrecto';
                } elseif (!$responsable->setIdAlumno($_POST['estudiante'])) {
                    $result['exception'] = 'Estudiante incorrecto';
                } elseif ($responsable->createRow()) {
                    /*if (!$responsable->setIdResponsable($responsable->ObtenerResponsableIDdui())) {
                            $result['status'] = 1; 
                            $result['message'] = 'Responsable creado correctamente';
                    }else{
                        $result['exception'] = "Error al guardar los datos del respons";
                    }
                    */
                    $result['message'] = 'Responsable creado';
                    if ($responsable->setIdResponsable($responsable->ObtenerResponsableIDdui())) {
                        if ($responsable->AgregarResponsableDetalle()) {
                            $result['status'] = 1;
                            $result['message'] = 'Responsable creado correctamente';
                        } else {
                            $result['exception'] = "Error al agregar el responsable";
                        }
                        $result['message'] = 'Responsable creado correctamente';
                    } else {
                        $result['exception'] = "Error al obtener el responsable";
                    }
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
                //leer un dato seleccionado para luego actualizarlo o solo leer la información 
            case 'readOne':
                if (!$responsable->setIdResponsable($_POST['id_responsable'])) {
                    $result['exception'] = 'Responsable incorrecto';
                } elseif ($result['dataset'] = $responsable->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Responsable inexistente';
                }
                break;
                //Acción para actualizar un dato de la tabla usuarios
            case 'update':
                $_POST = Validator::validateForm($_POST);
                if (!$responsable->setIdResponsable($_POST['id_responsable'])) {
                    $result['exception'] = 'Responsable incorrecto';
                } elseif (!$responsable->readOne()) {
                    $result['exception'] = 'Responsable inexistente';
                } elseif (!$responsable->setNombresResponsable($_POST['nombres'])) {
                    $result['exception'] = 'Nombres incorrectos';
                } elseif (!$responsable->setApellidosResponsable($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos incorrectos';
                } elseif (!$responsable->setDui($_POST['dui'])) {
                    $result['exception'] = 'DUI incorrectos';
                } elseif (!$responsable->setCorreo($_POST['correo'])) {
                    $result['exception'] = 'Correo incorrecto';
                } elseif (!$responsable->setLugarTrabajo($_POST['lugar'])) {
                    $result['exception'] = 'Lugar incorrecto';
                } elseif (!$responsable->setTelefonoTrabajo($_POST['telefono'])) {
                    $result['exception'] = 'Telefono incorrecto';
                } elseif (!$responsable->setParentesco($_POST['parentesco'])) {
                    $result['exception'] = 'Parentesco incorrecto';
                } elseif (!$responsable->setIdAlumno($_POST['estudiante'])) {
                    $result['exception'] = 'Estudiante incorrecto';
                } elseif ($responsable->updateRow()) {
                    $result['message'] = 'Responsable creado';
                    if ($responsable->ActualizarResponsableDetalle()) {
                        $result['status'] = 1;
                        $result['message'] = 'Responsable actualizado correctamente';
                    } else {
                        $result['exception'] = "Error al actualizar el responsable";
                    }
                    $result['message'] = 'Responsable actualizado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
                /*case 'readOne':
                if (!$responsable->setIdResponsable($_POST['id_responsable'])) {
                    $result['exception'] = 'Responsable incorrecto';
                } elseif ($result['dataset'] = $responsable->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Responsable inexistente';
                }
                break;*/
            case 'SearchEstudiante':
                $_POST = Validator::validateForm($_POST);
                if ($result['dataset'] = $responsable->SearchEstudiantes($_POST['data'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['except+ion'] = 'No hay datos registrados';
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
?>