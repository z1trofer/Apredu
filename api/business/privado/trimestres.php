<?php
require_once('../../entities/dto/trimestres.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $trimestres = new Trimestres;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null, 'dataset' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_empleado'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'readAll':
                //se hace la consulta a la base por medio de parametros de la querie para llenado de la tabla
                if ($result['dataset'] = $trimestres->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen '.count($result['dataset']).' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                }else{
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
                // Acción para crear un dato en la tabla de grados
            case 'create':
                $_POST = Validator::validateForm($_POST);
                if (!$trimestres->setAnio($_POST['anio'])) {
                    $result['exception'] = 'Año incorrecto';
                }elseif ($trimestres->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Año creado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
                //Selecccionar un registro por medio de consultas en las queries accionado por un onUpdate
            case 'readOne':
                if (!$grados->setIdAnio($_POST['id_anio'])) {
                    $result['exception'] = 'Anio incorrecto';
                } elseif ($result['dataset'] = $trimestres->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Anio inexistente';
                }
                break;
                // Acción para actualizar un dato en la tabla grados
            case 'update':
                $_POST = Validator::validateForm($_POST);
                if (!$trimestres->setIdTrimestre($_POST['id'])) {
                    $result['exception'] = 'trimestre incorrecta';
                } elseif ($trimestres->updateRow()) {
                        $result['status'] = 1;
                        $result['message'] = 'Trimestre Actualizado correctamente';
                        $trimestres->updateTabla();
                    } else {
                        $result['exception'] = Database::getException();
                    }
                break;
                // Acción para eliminar un dato de la tabla categorías
            case 'delete':
                if (!$grados->setIdAnio($_POST['id_anio'])) {
                    $result['exception'] = 'Grado incorrecto';
                } elseif (!$data = $trimestres->readOne()) {
                    $result['exception'] = 'Grado inexistente';
                } elseif ($trimestres->deleteRow()) {
                    $result['status'] = 1;                   
                    $result['message'] = 'Grado eliminado correctamente';
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