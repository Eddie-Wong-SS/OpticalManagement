<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

function sendEmail($Subject, $Body, $Address)
{


    $mail = new PHPMailer();
    $mail->IsSMTP(); // Set mailer to use SMTP
    $mail->SMTPAuth= true;
    $mail->SMTPSecure="ssl";
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465;
    $mail->CharSet= "big5";

    $mail->Username="wong.eddie.eddie@gmail.com";
    $mail->Password= "darg0narix4rfv";
    $mail->SetFrom('wong.eddie.eddie@gmail.com','Eddie Wong');//sender email


    $mail->Sender = 'account_bounces-user=wong.eddie.eddie@gmail.com';
    $mail->Subject= $Subject;
    $mail->Body= $Body;
    $mail->IsHTML(true);
    $mail->AddAddress($Address);

    if(!$mail->Send())
    {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
}