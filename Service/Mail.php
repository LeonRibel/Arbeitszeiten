<?php

namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;




class Mail
{
    private PHPMailer $mailer;

    public function __construct()
    {


        $this->mailer = new PHPMailer(true);

        $this->mailer->isSMTP();
        $this->mailer->Host = 'localhost';        
        $this->mailer->Port = 1025;
        $this->mailer->CharSet = 'UTF-8';

        $this->mailer->setFrom('arbeitszeiten@ape-dev.de', 'Arbeitszeiten System');
    }

    public function sendPasswordReset(string $email, string $username, string $token): bool
    {
    try {
        $link = "http://localhost:5173/settings?token={$token}";
        
        $this->mailer->addAddress($email);
        $this->mailer->Subject = 'Passwort zurücksetzen';
        $this->mailer->isHTML(true);
        $this->mailer->Body = "
            <h2>Hallo $username</h2>
            <p>Sie haben eine Passwort-Zurücksetzung angefordert.</p>
            <p><a href='$link' style='padding:10px 20px;background:#007bff;color:white;text-decoration:none;'>Passwort zurücksetzen</a></p>
            <p>Oder kopieren Sie diesen Link: $link</p>
            <p><small>Gültig für 1 Stunde.</small></p>
        ";
        
        $result = $this->mailer->send();
        $this->mailer->clearAddresses();
        return $result;
        
    } catch (Exception $e) {
        throw new \Exception("Mail-Fehler: " . $this->mailer->ErrorInfo);
    }
}
}