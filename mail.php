<?php 
// Import PHPMailer classes into the global namespace 
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\SMTP; 
use PHPMailer\PHPMailer\Exception; 
 
// Include library files 
require 'PHPMailer/Exception.php'; 
require 'PHPMailer/PHPMailer.php'; 
require 'PHPMailer/SMTP.php'; 
 
function send_email($email_adress,$mail_subject,$mail_content){


// Create an instance; Pass `true` to enable exceptions 
    $mail = new PHPMailer; 
    // Server settings 
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;    //Enable verbose debug output 
    $mail->isSMTP();                            // Set mailer to use SMTP 
    $mail->Host = 'smtp-relay.sendinblue.com';           // Specify main and backup SMTP servers 
    $mail->SMTPAuth = true;                     // Enable SMTP authentication 
    $mail->Username = 'artwangspare@gmail.com';       // SMTP username 
    $mail->Password = 'ByvHP8n2aCYQ5d6z';         // SMTP password 
    //$mail->SMTPSecure = 'ssl';                  // Enable TLS encryption, `ssl` also accepted 
    $mail->Port = 587;                          // TCP port to connect to 
    
    // Sender info 
    $mail->setFrom('artwangspare@gmail.com', 'auto-sender'); 

    // Add a recipient 
    $mail->addAddress($email_adress); 
    
    //$mail->addCC('cc@example.com'); 
    //$mail->addBCC('bcc@example.com'); 
    
    // Set email format to HTML 
    $mail->isHTML(true); 
    
    // Mail subject 
    $mail->Subject = $mail_subject; 
    
    // Mail body content 
    $bodyContent = $mail_content; 
    $mail->Body    = $bodyContent; 

    
    // Send email 
    if(!$mail->send()) { 
        echo 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo; 
    } 

}