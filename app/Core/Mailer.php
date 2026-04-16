<?php

declare(strict_types=1);

namespace App\Core;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    public function send(string $to, string $subject, string $body): bool
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = config('MAIL_HOST', 'mailpit');
            $mail->Port = (int) config('MAIL_PORT', 1025);

            $username = config('MAIL_USERNAME');
            $password = config('MAIL_PASSWORD');
            $encryption = config('MAIL_ENCRYPTION');

            if (!empty($username)) {
                $mail->SMTPAuth = true;
                $mail->Username = $username;
                $mail->Password = $password ?? '';
            }

            if (!empty($encryption)) {
                $mail->SMTPSecure = $encryption;
            }

            $mail->setFrom(
                config('MAIL_FROM_ADDRESS', 'no-reply@example.com'),
                config('MAIL_FROM_NAME', 'Auth System')
            );

            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            return $mail->send();
        } catch (Exception $e) {
            error_log('Erro ao enviar e-mail: ' . $e->getMessage());
            return false;
        }
    }
}
