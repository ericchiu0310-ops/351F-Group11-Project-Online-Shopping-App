<?php
session_start();
$conn = mysqli_connect("localhost:8889", "root", "root", "php_shopping app");

// Get the email from the URL (the link sent in the email)
$email_to_reset = isset($_GET['email']) ? mysqli_real_escape_string($conn, $_GET['email']) : "";

$message = "";

if (isset($_POST['submit'])) {
    $newpw = mysqli_real_escape_string($conn, $_POST['new_password']);
    $verifypw = mysqli_real_escape_string($conn, $_POST['verify_password']);
    $email_hidden = mysqli_real_escape_string($conn, $_POST['email_hidden']);

    if ($newpw !== $verifypw) {
        $message = "Passwords do not match!";
    } elseif (empty($email_hidden)) {
        $message = "Error: User identity lost. Please request a new link.";
    } else {
        // THE FIX: The WHERE clause targets ONLY the correct user
        $update = "UPDATE users SET password='$newpw' WHERE email='$email_hidden'";
        
        if (mysqli_query($conn, $update)) {
            header("Location: success.php?reset=done");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>

<div class="my_form">
    <form action="" method="POST">
        <h2>New Password</h2>
        
        <?php if ($message != ""): ?>
            <p style="color: red; margin-bottom: 10px;"><?php echo $message; ?></p>
        <?php endif; ?>

        <input type="hidden" name="email_hidden" value="<?php echo htmlspecialchars($email_to_reset); ?>">

        <div class="input_deg">
            <label>New Password</label>
            <input type="password" name="new_password" required>
        </div>

        <div class="input_deg">
            <label>Confirm Password</label>
            <input type="password" name="verify_password" required>
        </div>

        <button type="submit" name="submit" class="login_btn_main">Update Password</button>
    </form>
</div>

</body>
</html>