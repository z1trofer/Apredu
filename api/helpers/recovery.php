<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../../PHPMailer/Exception.php';
require '../../PHPMailer/PHPMailer.php';
require '../../PHPMailer/SMTP.php';

require_once('./config_contra.php');
$email = $_POST['email'];
$query = "SELECT * FROM empleados where correo_empleado = '$email'";
$result = $conexion->query($query);
$row = $result->fetch_assoc();

if($result->num_rows > 0){
  $mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'paulrivas004@gmail.com';
    $mail->Password   = 'jtlgtzpzjvtblyan';
    $mail->Port       = 587;

    $mail->setFrom('paulrivas004@gmail.com', 'NOMBRE_FORM');
    $mail->addAddress('manbo631@gmail.com', 'NOMBRE_ELECTRONICO_PARA');
    $mail->isHTML(true);
    $mail->Subject = 'Recuperación de contraseña';
    $mail->Body    = 'Hola, este es un correo generado para solicitar tu recuperación de contraseña, por favor, visita la página de <a href="http://localhost/Apredu/change_password.php?id_empleado
    
    
    
    
    ='.$row['id_empleado'].'">Recuperación de contraseña</a>';

    $mail->send();
    header("Location: ../../vistas/privado/index.html?message=ok");
} catch (Exception $e) {
  header("Location: ../../vistas/privado/index.html?message=error");
}

}else{
  header("Location: ../../vistas/privado/index.html?message=not_found");
}

?>
