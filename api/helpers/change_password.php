<?php
require_once('../helpers/database.php');
require_once('./config_contra.php');
$id = $_POST['id_empleado'];
$pass = $_POST['new_password'];

// Encriptar la contraseña
$hashedPass = password_hash($pass, PASSWORD_DEFAULT);

$query = "UPDATE empleados SET clave = '$hashedPass' WHERE id_empleado= $id";
$conexion->query($query);

header("Location: ../../vistas/privado/index.html?message=success_password");
?>
