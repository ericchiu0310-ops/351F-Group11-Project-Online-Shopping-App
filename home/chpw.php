<?php
session_start();
$conn = mysqli_connect("localhost:8889", "root", "root", "php_shopping app");

$message = "";

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // SUCCESS: Send the email to the next page in the URL
        header("Location: change.php?email=" . urlencode($email));
        exit();
    } else {
        $message = "That email is not registered.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verify Email</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
    <div class="my_form">
        <form action="" method="POST">
            <h2>Verify Email</h2>
            <?php if ($message != ""): ?>
                <p style="color: red; text-align: center;"><?php echo $message; ?></p>
            <?php endif; ?>

            <div class="input_deg">
                <label>Email Address</label>
                <input type="email" name="email" required>
            </div>
            <button type="submit" name="submit" class="login_btn_main">Next</button>
        </form>
    </div>
</body>
</html>