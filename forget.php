<?php
session_start();

// Ensure this path points to your composer vendor folder
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
$conn = mysqli_connect("localhost:8889", "root", "root", "php_shopping app");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

function sendpwEmail($customerEmail)
{
    $mail = new PHPMailer(true);
    try {
        // SMTP Settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'tyteamwork2324@gmail.com';
        $mail->Password   = 'hfiowkemjflayhsc'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        $mail->Timeout    = 30;

        $mail->setFrom('tyteamwork2324@gmail.com', 'HKMU Shopping App');
        $mail->addAddress($customerEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'HKMU Shopping App - Reset Password Request';
        
        // FIX 1: Added :8888 and used urlencode for the space in the folder name
        // This is the link the user clicks in their Gmail inbox
        $resetLink = "http://localhost:8888/351F%20Group%20Project/S351Fupdate/home/reset.php?email=" . urlencode($customerEmail);
        
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; color:#222; line-height: 1.6;'>
                <h3>Reset Your Password</h3>
                <p>Dear customer,</p>
                <p>We received a request to reset your password. Please click the button below to proceed:</p>
                <p><a href='$resetLink' style='background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>Reset Password</a></p>
                <p>If the button doesn't work, copy and paste this link into your browser: <br> $resetLink</p>
                <p>Thank you for using HKMU Shopping App.</p>
            </div>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return $mail->ErrorInfo;
    }
}

$error_msg = "";

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    
    // Verify if the email exists
    $checkEmail = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    
    if (mysqli_num_rows($checkEmail) > 0) {
        $emailResult = sendpwEmail($email);
        
        if ($emailResult === true) {
            // FIX 2: Added :8888 and %20 for the browser redirect
            header("Location: http://localhost:8888/351F%20Group%20Project/S351Fupdate/home/success.php");
            exit(); 
        } else {
            $error_msg = "Mail Error: " . $emailResult;
        }
    } else {
        $error_msg = "This email is not registered in our system.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Forget Password</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>
<body>

<div class="my_form">
    <form action="" method="POST">
        <h2>Forget Password</h2>
        
        <?php if ($error_msg != ""): ?>
            <p style="color: red; background: #ffe6e6; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                <?php echo $error_msg; ?>
            </p>
        <?php endif; ?>

        <div class="input_deg">
            <label><i class="fa fa-envelope"></i> Registered Email</label>
            <input type="email" name="email" placeholder="e.g. user@example.com" required style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>
        
        <button type="submit" name="submit" class="login_btn_main" style="margin-top: 20px; width: 100%;">Send Reset Link</button>
        
        <div style="margin-top: 20px; text-align: center;">
            <a href="home/login.php" style="text-decoration: none; color: #666; font-size: 0.9em;">Back to Login</a>
        </div>
    </form>
</div>

</body>
</html>