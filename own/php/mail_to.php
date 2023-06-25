<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: origin, x-requested-with, content-type");

$json = file_get_contents('php://input'); 
$data = json_decode($json, true);

require 'PHPMailer/PHPMailerAutoload.php';

if(!empty($data['mail_attachment'])){
    //$base = explode('data:application/pdf;base64,', $data['attachments']);
    //$base = base64_decode($base[0]);
    $base = explode('data:application/pdf;base64,', $data['mail_attachment']);
    $base = base64_decode($base[0]);
    
    $customer_name = $data['customer_name'];
    $customer_email = $data['customer_email'];

    date_default_timezone_set('UTC');
    $date = new DateTime();
    $date = $date->format("Y:m:d");

    $mail = new PHPMailer(true); // note the parameter set to true. 
    //Server settings
    //$mail->SMTPDebug = 4;                                       // Enable verbose debug output
    //$mail->Debugoutput = 'html';
    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Port       = '587';                                    // TCP port to connect to
    $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Host       = 'sent.one.com';  // Specify main and backup SMTP servers
    $mail->Username   = 'konfigurator@pro-digital.de';                     // SMTP username
    $mail->Password   = 'konfi4kunden!';                               // SMTP password
    // $mail->addAddress('konfigurator@pro-digital.de');               // Name is optional
    $mail->setFrom('konfigurator@pro-digital.de');

    $mail->addAddress($customer_email); 
    
    //Recipients
    $mail->CharSet = 'UTF-8';
    $mail->From       = $mail->Username;

    // Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $encoding = 'base64';
    $type = 'application/pdf';
    $mail->addStringAttachment($base, 'planungsunterlagen_' . $date . '.pdf');

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = "Fassade Konfiguration";

    $mail->Body = "<html>
        Name: $customer_name
    </html>";

    if ($mail->send()){
        echo "Email erfolgreich verschickt!";
    } else{
        echo "Fehler!";
    }
}
