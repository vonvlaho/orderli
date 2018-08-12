<?php
/**
 *
 * Copyright notice
 *
 * (c) 2018
 * Frederic von Vlahovits fvv@posteo.de
 *
 * Licensed under The MIT License (MIT)
 *
 */

namespace FVV\Orderli\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    protected $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->SMTPDebug = 0;
        $this->mailer->Host = 'smtp.gmail.com';
        $this->mailer->Port = 587;
        $this->mailer->SMTPSecure = 'tls';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = "putorderli@gmail.com";
        $this->mailer->Password = "-JCJ:?VC/3qC8/b\$dc3h@N5r>";
        $this->mailer->setFrom('from@example.com', 'Orderli');
    }

    public function sendMail(string $recepients, string $subject, string $html, string $txt)
    {

        try {
            $this->mailer->setFrom('from@example.com', 'Orderli');
            $this->mailer->addAddress($recepients);

            //Content
            $this->mailer->isHTML(true);
            $this->mailer->CharSet = 'UTF-8';
            $this->mailer->Subject =  $subject;
            $this->mailer->Body    =  $html;
            $this->mailer->AltBody =  $txt;

            $this->mailer->send();
            $this->mailer->ClearAllRecipients();
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $this->mailer->ErrorInfo;
        }

    }
}