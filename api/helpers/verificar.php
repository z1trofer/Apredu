<?php
require_once('./config_contra.php');

session_start();

// Obtener el número ingresado por el usuario desde el formulario
$numero_ingresado = $_POST['numero_ingresado'];

// Obtener el número aleatorio almacenado en la variable de sesión
if (isset($_SESSION['numero_aleatorio'])) {
    $numero_almacenado = $_SESSION['numero_aleatorio'];

    if ($numero_ingresado == $numero_almacenado) {
        // Los números coinciden, acceso concedido
        echo "Acceso concedido";
    } else {
        // Los números no coinciden, acceso denegado
        echo "Acceso denegado";
    }
} else {
    // La variable de sesión no contiene un número aleatorio, maneja este caso según tus necesidades
    echo "Número aleatorio no encontrado en la sesión";
}

?>
