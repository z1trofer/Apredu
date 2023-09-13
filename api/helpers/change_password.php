<?php
require_once('./config_contra.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];

    // Verifica que el token sea válido y está en la base de datos
    $query = "SELECT id_empleado FROM recovery_tokens WHERE token = '$token'";
    $result = $conexion->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_empleado = $row['id_empleado'];

        // Encripta la nueva contraseña
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Actualiza la contraseña en la base de datos para el empleado con el $id_empleado
        $update_query = "UPDATE empleados SET clave = '$hashed_password' WHERE id_empleado = $id_empleado";
        
        if ($conexion->query($update_query) === TRUE) {
            // Contraseña actualizada con éxito, redirige al usuario a una página de éxito o inicio de sesión
            header("Location: ../../vistas/privado/index.html?message=password_updated");
            exit;
        } else {
            // Ocurrió un error al actualizar la contraseña, redirige a una página de error
            header("Location: ../../vistas/privado/index.html?message=password_update_error");
            exit;
        }
    } else {
        // El token no es válido, redirige a una página de error
        header("Location: ../../vistas/privado/index.html?message=token_invalid");
        exit;
    }
}
?>
