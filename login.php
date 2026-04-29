<?php
session_start();
include("db_connection.php");

$error = "";

if (isset($_POST['login'])) {

    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {

        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {

            $_SESSION['userID'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['isAdmin'] = $row['isAdmin'];

            if ($row['isAdmin'] == 1) {
                header("Location: admin.php");
                exit();
            } else {
                header("Location: index.php");
                exit();
            }

        } else {
            $error = "Invalid email or password.";
        }

    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Rakkez+</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="home-body">

    <div class="home-navbar">
        <div class="home-logo">
            <a href="home.php"><img src="images/logo.jpeg" width="100"></a>
        </div>

        <div class="home-nav-links">
            <a href="home.php">Home</a>
            <a href="login.php">Log In</a>
            <a href="signup.php">Sign Up</a>
        </div>
    </div>

    <div class="login-container">
        <h2>Log In</h2>
        <p class="login-subtitle">Welcome back! Let’s keep you focused.</p>

        <?php if (!empty($error)) { ?>
            <p style="color:red; text-align:center;">
                <?php echo $error; ?>
            </p>
        <?php } ?>

        <form method="POST" action="login.php">

            <label for="login-email">Email Address</label>
            <input type="email"
                   name="email"
                   id="login-email"
                   placeholder="Enter your email"
                   required
                   pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">

            <label for="password">Password</label>
            <div class="login-password-box">
                <input type="password"
                       name="password"
                       id="password"
                       placeholder="Enter your password"
                       required
                       minlength="6">

                <button type="button" onclick="togglePassword()">👁️</button>
            </div>

            <button type="submit" name="login" class="login-btn">
                Log In
            </button>

        </form>

        <p>
            Don’t have an account?
            <a href="signup.php">Sign Up</a>
        </p>
    </div>

    <div class="footer">
        <p>© Rakkez+ - Helping students build better study habits</p>
    </div>

    <script>
        function togglePassword() {
            var pass = document.getElementById("password");

            if (pass.type === "password") {
                pass.type = "text";
            } else {
                pass.type = "password";
            }
        }
    </script>

</body>
</html>