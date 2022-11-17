<?php
namespace Utils;
use PHPMailer\PHPMailer as PHPMailer;
use PHPMailer\Exception as Exception;

abstract class Email {
    public static function sendEmail(array $shippingAddress, string $subject, string $body, string $attachment = null): void {
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

    public static function forgotPassword(int $code, string $userTypePrefix, string $userType): string {
        
        return '<center>
    <table id="m_-1900029610290897684backgroundTable" style="border-spacing:0;border-collapse:collapse;font-family:proxima-nova,\'helvetica neue\',helvetica,arial,geneva,sans-serif;width:100%!important;height:100%!important;color:#4c4c4c;font-size:15px;line-height:150%;background:#ffffff;margin:0;padding:0;border:0">
        <tbody>
            <tr style="vertical-align:top;padding:0">
                <td align="center" valign="top" style="vertical-align:top;padding:0">
                    <table id="m_-1900029610290897684templateContainer" style="border-spacing:0;border-collapse:collapse;font-family:proxima-nova,\'helvetica neue\',helvetica,arial,geneva,sans-serif;width:600px;color:#4c4c4c;font-size:15px;line-height:150%;background:#ffffff;margin:40px 0;padding:0;border:0">
                        <tbody>
                            <tr style="vertical-align:top;padding:0">
                                <td align="center" valign="top" style="vertical-align:top;padding:0 40px">
                                    <table id="m_-1900029610290897684templateContent" style="border-spacing:0;border-collapse:collapse;font-family:proxima-nova,\'helvetica neue\',helvetica,arial,geneva,sans-serif;width:100%;background:#ffffff;margin:0;padding:0;border:0">
                                        <tbody>
                                            <tr style="vertical-align:top;padding:0">
                                                <td style="vertical-align:top;text-align:left;padding:0" align="left" valign="top">
                                                    <h1 id="m_-1900029610290897684logo" style="color:#6e5baa;display:block;font-family:hybrea,proxima-nova,\'helvetica neue\',helvetica,arial,geneva,sans-serif;font-size:32px;font-weight:200;text-align:left;margin:0 0 40px" align="left"><img src="https://i.imgur.com/a4aQeCG.png" alt="pet-hero" height="64" style="outline:none;text-decoration:none;border:0" class="CToWUd" data-bit="iit"></h1>

                                                    <p style="margin:20px 0; color: #222;">Someone (hopefully you) has requested a password reset for your Pet Hero account registered as ' . $userTypePrefix . ' <strong>' . $userType . '</strong>. Insert the code below in our page to set a new password:</p>

                                                    <p style="margin:40px 0; color: #ffff; text-align: center; letter-spacing: 1.5px;"><strong style="font-size: 24px; background-color:#463ac5; border-radius: 10px; padding: 12px 20px 12px 20px">' . $code . '</strong></p>

                                                    <p style="margin:20px 0; color: #222;">If you don\'t wish to reset your password, disregard this email and no action will be taken.</p>


                                                    <p style="margin:20px 0; color: #222;">
                                                        The Pet Hero Team<br>
                                                    </p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr style="vertical-align:top;padding:0">
                                <td align="center" valign="top" style="vertical-align:top;padding:0 40px">
                                    <table id="m_-1900029610290897684footerContent" style="border-spacing:0;border-collapse:collapse;font-family:proxima-nova,\'helvetica neue\',helvetica,arial,geneva,sans-serif;width:100%;border-top-style:solid;border-top-color:#ebeaef;color:#999999;font-size:12px;background:#ffffff;margin:0;padding:0;border-width:1px 0 0">
                                        <tbody>
                                            <tr style="vertical-align:top;padding:0">
                                                <td valign="top" style="vertical-align:top;text-align:left;padding:0" align="left">
                                                    <p style="margin:20px 0">
                                                        Pet Hero is a platform designed so that owners can host their pets with trusted staff.
                                                    </p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</center>';
    }
}
