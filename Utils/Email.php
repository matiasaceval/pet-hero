<?php
namespace Utils;
use PHPMailer\PHPMailer as PHPMailer;
use PHPMailer\Exception as Exception;

abstract class Email {
    public static function sendEmail(array $shippingAddress, string $subject, string $body, string $attachment = null) {
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->Host = 'smtp.gmail.com';           // Enable SMTP authentication
            $mail->SMTPAuth = true;                       // Send using SMTP
            $mail->Username = 'pet.hero.mdq@gmail.com';  // SMTP username
            $mail->Password = EMAIL_APP_PASS;           // SMTP password
            $mail->SMTPSecure = 'tls';                      // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
            $mail->Port = 587;                        // TCP port to connect to

            //Recipients
            $mail->setFrom('pet.hero.mdq@gmail.com', 'Pet Hero');
            foreach($shippingAddress as $address) {
                $mail->addAddress($address);
            }

            // Content
            $mail->isHTML(true);                             // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $body;
            if ($attachment != null) {
                $mail->addAttachment($attachment);
            }
            $mail->send();
        } catch (Exception $e) {
        }
    }
}
