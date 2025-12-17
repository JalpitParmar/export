<?php
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'bhoomitradeline@gmail.com';
    $mail->Password   = 'wtkm tcxk kfuo ahlm';   // Gmail App Password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // Fix SSL Error
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer'       => false,
            'verify_peer_name'  => false,
            'allow_self_signed' => true
        ]
    ];

    $mail->setFrom('bhoomitradeline@gmail.com', 'Bhoomi Trade Line');
    $mail->addAddress('bhoomitradeline@gmail.com'); // receiver (your own mail)

    // Reset password URL
    $resetLink = "https://bhoomitradeline.com/forgotpassword.php";

    // Email Content
    $mail->isHTML(true);
    $mail->Subject = "Password Reset Request – Bhoomi Trade Line";

    $mail->Body = "
    <div style='background:#f4f4f4; padding:25px; font-family:Arial;'>
        <div style='max-width:600px; margin:auto; background:#ffffff; padding:25px; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.08);'>

            <h2 style='text-align:center; color:#ff6600;'>Reset Your Password</h2>

            <p style='font-size:16px; color:#333;'>
                You recently requested to reset your password for your <b>Bhoomi Trade Line</b> account.
                Click the button below to reset it.
            </p>

            <div style='text-align:center; margin:25px 0;'>
                <a href='$resetLink' 
                   style='background:#ff6600; padding:12px 20px; color:#fff; text-decoration:none;
                          font-size:16px; border-radius:5px; display:inline-block;'>
                    Reset Password
                </a>
            </div>

           
            <p style='font-size:14px; color:#777; margin-top:20px;'>
                If you did not request a password reset, you can safely ignore this email.
            </p>

            <p style='text-align:center; font-size:13px; color:#aaa; margin-top:40px;'>
                © " . date('Y') . " Bhoomi Trade Line. All Rights Reserved.
            </p>

        </div>
    </div>
    ";

    $mail->send();

    header("Location: login.php");
    exit();

} catch (Exception $e) {
    echo "Error sending email: {$mail->ErrorInfo}";
}
?>
