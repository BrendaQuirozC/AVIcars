<?php 
/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-03-02 17:59:49
 * @Last Modified by:   Cairo G. Resendiz
 * @Last Modified time: 2018-05-14 17:57:28
 */

//require_once ($_SERVER['DOCUMENT_ROOT']) . '/phpmailer/vendor/phpmailer/phpmailer/src/PHPMailer.php';
//require_once ($_SERVER['DOCUMENT_ROOT']) . '/phpmailer/vendor/phpmailer/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load composer's autoloader
require ($_SERVER['DOCUMENT_ROOT']) . '/libraries/phpmailer/vendor/autoload.php';

$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
	session_start();
    //Server settings
    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                  // Set mailer to use SMTP
    $mail->Host = 'smtp.1and1.mx';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'juan.gonzalez@skytel.com.mx';                 // SMTP username
    $mail->Password = '52753689';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('apoyovial@infotraffic.com.mx', 'apoyovial');
    $mail->addAddress($_SESSION["mail"], $_SESSION['user']);     // Add a recipient
    //$mail->addAddress('ellen@example.com');               // Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Confirma tu correo para Apoyo Vial';
    $mail->Body    =  "
    	<div style='text-align: center;'><p>Hola  <b>".$_SESSION['user']."!</p></b>
			<a href='http://testavicars.infosite.com.mx/confirm/?token=".$_SESSION["sessionkey"]."&account=activate'>
				<div style='background-color: rgba(235,194,20,.8); padding: 15px 10px; text-align: center; border-radius: 5px;'>
					Confirma que has recibido este correo
				</div>
			</a>
			<p>Una vez confirmado podr&aacute;s obtener todos los beneficios en Apoyo Vial </p>
		</div> 
    ";
    $mail->AltBody = "Hola " .$_SESSION["user"]."!. Confirma que has recibido este correo, una vez confirmado podr&aacute;s obtener todos los beneficios en Apoyo Vial
    ";
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}




 ?>