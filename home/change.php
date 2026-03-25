<?php
session_start();
$conn = mysqli_connect("localhost:8889", "root", "root", "php_shopping app");

// Catch the email from the URL (the ?email= part)
$email_to_update = isset($_GET['email']) ? mysqli_real_escape_string($conn, $_GET['email']) : "";

// If no email is provided, they shouldn't be here
if (empty($email_to_update)) {
    header("Location: chpw.php");
    exit();
}

$message = "";

if (isset($_POST['submit'])) {
    $oldpw = mysqli_real_escape_string($conn, $_POST['old_password']);
    $newpw = mysqli_real_escape_string($conn, $_POST['new_password']);
    $verifypw = mysqli_real_escape_string($conn, $_POST['verify_password']);

    if ($newpw !== $verifypw) {
        $message = "New passwords do not match!";
    } else {
        // THE FIX: Check old password ONLY for this specific email
        $check_sql = "SELECT * FROM users WHERE email='$email_to_update' AND password='$oldpw'";
        $result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($result) > 0) {
            // THE FIX: Update ONLY this user's row
            $update_sql = "UPDATE users SET password='$newpw' WHERE email='$email_to_update'";
            if (mysqli_query($conn, $update_sql)) {
                header("Location: success.php?reset=done");
                exit();
            }
        } else {
            $message = "Incorrect old password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Change Password</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
    <div class="my_form">
        <form action="" method="POST">
            <h2>Change Password</h2>
            <p style="text-align:center; font-size:0.8em; color:#666;">Changing for: <?php echo htmlspecialchars($email_to_update); ?></p>
            
            <?php if ($message != ""): ?>
                <p style="color: red; text-align: center;"><?php echo $message; ?></p>
            <?php endif; ?>

            <div class="input_deg">
                <label>Old Password</label>
                <input type="password" name="old_password" required>
            </div>
            <div class="input_deg">
                <label>New Password</label>
                <input type="password" name="new_password" required>
            </div>
            <div class="input_deg">
                <label>Confirm New Password</label>
                <input type="password" name="verify_password" required>
            </div>
            <button type="submit" name="submit" class="login_btn_main">Update Password</button>
        </form>
    </div>
</body>
</html>