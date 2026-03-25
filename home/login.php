<?php
session_start();
$conn = mysqli_connect("localhost:8889", "root", "root", "php_shopping app");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$message = "";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $row['hkmuid'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['usertype'] = $row['usertype'];

        if ($row['usertype'] == "admin") {
            header("Location: ../admin/adminpage.php");
            exit();
        } else {
            header("Location: userpage.php");
            exit();
        }
    } else {
        $message = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>

<main class="login_page">
    <div class="login_box">
        <h1 class="login_title">Welcome Back, Please Sign In</h1>
        <p class="login_subtitle">Welcome to HKMU Shopping App</p>

        <?php if ($message != ""): ?>
            <div class="login_error" style="color:red; text-align:center; margin-bottom:10px;"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="login_group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <div class="login_group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <div class="login_remember" style="display: flex; align-items: center; gap: 5px; margin-bottom: 15px;">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me</label>
            </div>

            <button type="submit" name="login" class="login_btn_main">Login</button>
            <a href="register.php" class="login_btn_second">Create New Account</a>
        </form>

        <div class="login_links" style="margin-top: 15px; text-align: center; display: flex; flex-direction: column; gap: 10px;">
            <a href="../forget.php">Forget password?</a>
            
            <a href="chpw.php">Change password?</a>
        </div>
    </div>
</main>

</body>
</html>