<?php
require_once('../../entities/dto/responsables.php');
require_once('../../entities/dto/estudiantes.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $estudiante = new Estudiantes;
    $responsable = new Responsables;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null, 'dataset' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_empleado'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
                //se hace la consulta a la base por medio de parametros de la querie para llenado de la tabla
                case 'readAll':
                    if ($result['dataset'] = $estudiante->readAll()) {
                        $result['status'] = 1;
                        $result['message'] = 'Existen '.count($result['dataset']).' registros';
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay datos registrados';
                    }
                    break;
                 //Selecccionar un registro por medio de consultas en las queries accionado por un onUpdate
            case 'readEstudiante':
                if (!$estudiante->setIdEstudiante($_POST['id_estudiante'])) {
                    $result['exception'] = 'estudiante incorrecto';
                } elseif ($result['dataset'] = $estudiante->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'cliente inexistente';
                }
                break;
                case 'readGrado':
                    if ($result['dataset'] = $estudiante->readGrado()) {
                        $result['status'] = 1;
                        $result['message'] = 'Existen '.count($result['dataset']).' registros';
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay grados registrados';
                    }
                    break;
            case 'createResponsable':
                $_POST = Validator::validateForm($_POST);
                if (!$responsable->setNombresResponsable($_POST['nombre_responsable'])) {
                    $result['exception'] = 'Nombres incorrectos';
                } elseif (!$responsable->setApellidosResponsable($_POST['apellido_responsable'])) {
                    $result['exception'] = 'Apellidos incorrectos';
                } elseif (!$responsable->setDui($_POST['dui'])) {
                    $result['exception'] = 'Dui incorrecto';
                }elseif (!$responsable->setCorreo($_POST['correo_responsable'])) {
                    $result['exception'] = 'Correo incorrecto';
                }elseif (!$responsable->setLugarTrabajo($_POST['trabajo'])) {
                    $result['exception'] = 'Lugar de trabajo incorrecto';
                }  elseif (!$responsable->setTelefonoTrabajo($_POST['telefono_trabajo'])) {
                    $result['exception'] = 'Telefono de trabajo incorrecto';
                }elseif (!$responsable->setParentesco($_POST['parentesco_responsable'])) {
                    $result['exception'] = 'Parentesco incorrecto';
                }elseif ($responsable->createResponsable()) {
                    $result['status'] = 1;
                    $result['message'] = 'Usuario creado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
                case 'CreateEstudiante':
                    $_POST = Validator::validateForm($_POST);
                    if (!$estudiante->setNombresEstudiante($_POST['nombre_estudiante'])) {
                        $result['exception'] = 'Nombres incorrectos';
                    } elseif (!$estudiante->setApellidosEstudiante($_POST['apellido_estudiante'])) {
                        $result['exception'] = 'Apellidos incorrectos';
                    } elseif (!$estudiante->setNacimiento($_POST['fecha_estudiante'])) {
                        $result['exception'] = 'Fecha incorrecta';
                    } elseif (!$estudiante->setDireccionEstudiante($_POST['direccion_estudiante'])) {
                        $result['exception'] = 'dirección incorrecta';
                    } elseif (!$estudiante->setNie($_POST['nie'])) {
                        $result['exception'] = 'Nie incorrecto';
                    } elseif (!$estudiante->setIdGrado($_POST['grados_estudiante'])) {
                        $result['exception'] = 'Grado incorrecto';
                    } elseif (!$estudiante->setUsuarioEstudiante($_POST['usuario_estudiante'])) {
                        $result['exception'] = 'Alias incorrecto';
                    }  elseif (!$estudiante->setClave($_POST['clave'])) {
                        $result['exception'] = Validator::getPasswordError();
                    }   elseif ($estudiante->CreateEstudiante()) {
                        $result['status'] = 1;
                        $result['message'] = 'Estudiante creado correctamente';
                    } else {
                        $result['exception'] = Database::getException();
                    }
                    break;
                // Acción para actualizar un dato en la tabla de clientes
            case 'update':
                $_POST = Validator::validateForm($_POST);
                if (!$cliente->setId($_POST['id'])) {
                    $result['exception'] = 'Cliente incorrecta';
                } elseif (!$data = $cliente->readOne()) {
                    $result['exception'] = 'Cliente inexistente';
                } elseif (!$cliente->setNombres($_POST['nombre'])) {
                    $result['exception'] = 'Nombre incorrecto';
                }elseif (!$cliente->setApellidos($_POST['apellido'])) {
                    $result['exception'] = 'Apellido incorrecto';
                }elseif (!$cliente->setDUI($_POST['dui'])) {
                    $result['exception'] = 'Dui incorrecto';
                }elseif (!$cliente->setCorreo($_POST['correo'])) {
                    $result['exception'] = 'Correo incorrecto';
                }elseif (!$cliente->setDireccion($_POST['direccion'])) {
                    $result['exception'] = 'Direccion incorrecto';
                }elseif (!$cliente->setClave($_POST['clave'])) {
                    $result['exception'] = 'clave incorrecto';
                }elseif (!$cliente->setEstado(isset($_POST['estados']) ? 1 : 0)) {
                    $result['exception'] = 'Estado incorrecto';   
                }elseif (!$cliente->setTelefono($_POST['telefono'])) {
                    $result['exception'] = 'Telefono incorrecto';
                }  elseif ($cliente->updateRow()) {
                        $result['status'] = 1;
                        $result['message'] = 'Estado del cliente modificada correctamente';
                    } else {
                        $result['exception'] = Database::getException();
                    }    
                break;
                //Acción para eliminar un dato en la tabla de clientes
            case 'delete':
                if (!$cliente->setId($_POST['id_cliente'])) {
                    $result['exception'] = 'cliente incorrecta';
                } elseif (!$data = $cliente->readOne()) {
                    $result['exception'] = 'cliente inexistente';
                } elseif ($cliente->deleteRow()) {
                    $result['status'] = 1;                   
                    $result['message'] = 'cliente eliminada correctamente';
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