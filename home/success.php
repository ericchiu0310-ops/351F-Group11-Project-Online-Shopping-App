<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Success</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
<div class="my_form">
    <div style="text-align: center; padding: 20px 0;">
        <?php 
        if (isset($_GET['reset']) && $_GET['reset'] == 'done') {
            echo "<h2 style='font-size: 28px;'>Password Updated</h2>";
            echo "<p style='font-size: 18px;'>Your password has been changed successfully.</p>";
        } else {
            echo "<h2 style='font-size: 28px;'>Email Sent!</h2>";
            echo "<p style='font-size: 18px;'>Please check your inbox for the reset link.</p>";
        }
        ?>
        <div style="margin-top: 30px;">
            <a href="login.php" class="login_btn_main" style="text-decoration: none; display: inline-block; width: auto; padding: 10px 30px;">Back to Login</a>
        </div>
    </div>
</div>
</body>
</html>