<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize input
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // ------------------------------ VALIDATION ------------------------------

    if (empty($name)) {
        echo "<script>alert('Name is required.'); window.history.back();</script>";
        exit();
    }

    if (!preg_match("/^[a-zA-Z ]{2,50}$/", $name)) {
        echo "<script>alert('Invalid name. Only letters and spaces allowed.'); window.history.back();</script>";
        exit();
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email address.'); window.history.back();</script>";
        exit();
    }

    if (empty($message)) {
        echo "<script>alert('Message cannot be empty.'); window.history.back();</script>";
        exit();
    }

    if (strlen($message) > 1000) {
        echo "<script>alert('Message is too long. Max 1000 characters.'); window.history.back();</script>";
        exit();
    }

    // ------------------------------ SEND EMAIL ------------------------------

    $mail = new PHPMailer(true);

    try {
        // SMTP SETTINGS
        $mail->isSMTP();
        $mail->Host       = "smtp.gmail.com";
        $mail->SMTPAuth   = true;
        $mail->Username   = "bhoomitradeline@gmail.com";   // Your Gmail
        $mail->Password   = "wtkm tcxk kfuo ahlm";         // App Password
        $mail->SMTPSecure = "tls";
        $mail->Port       = 587;

        // Fix SSL handshake error
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ]
        ];

        // ------------------------------ FROM & TO ------------------------------

        // Always send from your own email (secure)
        $mail->setFrom("bhoomitradeline@gmail.com", "Website Contact Form");

        // Email that will receive the message
        $mail->addAddress("bhoomitradeline@gmail.com");

        // Add reply-to for user's email
        $mail->addReplyTo($email, $name);

        // ------------------------------ EMAIL CONTENT ------------------------------

        $mail->isHTML(true);
        $mail->Subject = "New Contact Form Message - Bhoomi Trade Line";

        $mail->Body = "
            <div style='font-family:Arial; padding:15px; background:#fafafa;'>
                <h2 style='color:#ff6600;'>New Message From Contact Form</h2>

                <p><strong>Name:</strong> {$name}</p>
                <p><strong>Email:</strong> {$email}</p>

                <div style='margin-top:15px; padding:10px; background:#fff; border-left:4px solid #ff6600;'>
                    <strong>Message:</strong>
                    <p>{$message}</p>
                </div>

                <br>
                <p style='font-size:12px; color:#777;'>© " . date("Y") . " Bhoomi Trade Line | Contact Form System</p>
            </div>
        ";

        $mail->send();

        echo "<script>alert('Message sent successfully!'); window.location='index.php#contact';</script>";
        exit();

    } catch (Exception $e) {
        echo "<script>alert('Mail error: {$mail->ErrorInfo}'); window.location='index.php#contact';</script>";
        exit();
    }
}

// FINAL fallback redirect
header("Location: index.php#contact");
exit();
?>
