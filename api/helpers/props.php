<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../libraries/PHPMailer_Lb/src/Exception.php';
require '../libraries/PHPMailer_Lb/src/PHPMailer.php';
require '../libraries/PHPMailer_Lb/src/SMTP.php';

class Props{
  public static function sendMail($address, $subject, $body, $message)
  {

      $mail = new PHPMailer(true);

      try {

          // Configuración del servidor

          // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Habilitar salida de depuración detallada
          $mail->isSMTP();                                            // Enviar usando SMTP
         $mail->Host       = 'smtp.gmail.com';                     // Configurar el servidor SMTP para enviar a través de Gmail
          $mail->SMTPAuth   = true;                                   // Habilitar autenticación SMTP
          $mail->Username   = 'paulrivas004@gmail.com';                     // Nombre de usuario SMTP
          $mail->Password   = 'jtlgtzpzjvtblyan';                               // Contraseña SMTP
          $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            // Habilitar cifrado TLS implícito
          $mail->Port       = 465;                                    // Puerto TCP para conectarse; usa 587 si has configurado `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
          // Destinatarios
          $mail->setFrom('paulrivas004@gmail.com', 'Asistente virtual-Apredu'); // Quien lo envía
          $mail->addAddress($address);     // Agregar un destinatario
          $mail->CharSet = 'UTF-8'; //caracteres especiales
          $mail->isHTML(true);
          $mail->Subject = $subject;
          $mail->Body = $body.' '.$message;
          //$mail->Body = 'Hola, te saluda la asistencia del Colegio Aprendo Contigo, este tu código de verificación: '.$message;
          $mail->send();
          return true;

      } catch (Exception $e) {

          return false;

      }

  }

}
