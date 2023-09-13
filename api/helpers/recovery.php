<?php
require_once('./config_contra.php');

require '../../PHPMailer/Exception.php';
require '../../PHPMailer/PHPMailer.php';
require '../../PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = $_POST['email'];

// Genera un token único
$token = bin2hex(random_bytes(16));

// Almacena el token en la base de datos junto con el ID de empleado
$query = "INSERT INTO recovery_tokens (token, id_empleado) VALUES ('$token', (SELECT id_empleado FROM empleados WHERE correo_empleado = '$email'))";
$conexion->query($query);

// Envío del correo electrónico
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'paulrivas004@gmail.com';
    $mail->Password   = 'jtlgtzpzjvtblyan';
    $mail->Port       = 587;

    $mail->setFrom('paulrivas004@gmail.com', 'Asistente virtual-Apredu');
    $mail->addAddress($email, 'NOMBRE_ELECTRONICO_PARA');
    $mail->isHTML(true);
    $mail->Subject = 'Recuperación de contraseña' ;
    $mail->Body    = 'Hola, este es un correo generado para solicitar tu recuperación de contraseña, por favor, visita la página de <a href="http://localhost/Apredu/change_password.php?token='.$token.'">Recuperación de contraseña</a>';

    $mail->send();
    header("Location: ../../vistas/privado/index.html?message=ok");
} catch (Exception $e) {
    header("Location: ../../vistas/privado/index.html?message=error");
}
?>
