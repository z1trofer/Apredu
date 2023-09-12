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
$numero = rand(900000,000);

if($result->num_rows > 0){
  $mail = new PHPMailer(true);
  $_SESSION['numero_aleatorio'] = $numero;
try {
  
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'paulrivas004@gmail.com';
    $mail->Password   = 'jtlgtzpzjvtblyan';
    $mail->Port       = 587;

    $mail->setFrom('paulrivas004@gmail.com', 'Asistente Apredu');
    $mail->addAddress( $email , 'NOMBRE_ELECTRONICO_PARA');
    $mail->isHTML(true);
    $mail->Subject = 'Verificacion por codigo aleatorio' ;
    $mail->Body    =  $numero .= "      Por favor digite, este numero en el espacio asignado para poder acceder al sistema";

    $mail->send();
    header("Location: ../../vistas/privado/index.html?message=ok");
} catch (Exception $e) {
  header("Location: ../../vistas/privado/index.html?message=error");
}

}else{
  header("Location: ../../vistas/privado/index.html?message=not_found");
}

?>
