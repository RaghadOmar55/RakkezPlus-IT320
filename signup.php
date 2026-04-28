<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("db_connection.php");

$error = "";
$success = "";

if (isset($_POST['signup'])) {

    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];

    $checkSql = "SELECT * FROM users WHERE email='$email'";
    $checkResult = mysqli_query($conn, $checkSql);

    if (!$checkResult) {
        $error = "Check error: " . mysqli_error($conn);
    } elseif (mysqli_num_rows($checkResult) > 0) {
        $error = "This email is already registered.";
    } else {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $idSql = "SELECT MAX(id) AS maxID FROM users";
        $idResult = mysqli_query($conn, $idSql);
        $idRow = mysqli_fetch_assoc($idResult);
        $newID = $idRow['maxID'] + 1;

        $sql = "INSERT INTO users (id, name, email, password, isAdmin)
                VALUES ('$newID', '$name', '$email', '$hashedPassword', 0)";

        if (mysqli_query($conn, $sql)) {
            $success = "Account created successfully! Redirecting to login...";
        } else {
            $error = "Insert error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up - Rakkez+</title>
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

    <div class="signup-container">
        <h2>Sign Up</h2>
        <p class="signup-subtitle">Create your account and start your focus journey.</p>

        <?php if (!empty($error)) { ?>
            <p style="color:red; text-align:center;"><?php echo $error; ?></p>
        <?php } ?>

        <?php if (!empty($success)) { ?>
            <p style="color:green; text-align:center;"><?php echo $success; ?></p>

            <script>
                setTimeout(function() {
                    window.location.href = "login.php";
                }, 1500);
            </script>
        <?php } ?>

        <form method="POST" action="signup.php">

            <label for="signupName">Full Name</label>
            <input type="text"
                   name="name"
                   id="signupName"
                   placeholder="Enter your name"
                   required
                   minlength="4">

            <label for="signupEmail">Email Address</label>
            <input type="email"
                   name="email"
                   id="signupEmail"
                   placeholder="Enter your email"
                   required>

            <label for="signupPassword">Password</label>
            <div class="signup-password-box">
                <input type="password"
                       name="password"
                       id="signupPassword"
                       placeholder="Enter your password"
                       required
                       minlength="8">

                <button type="button" onclick="toggleSignupPassword()">👁️</button>
            </div>

            <button type="submit" name="signup" class="signup-btn">Sign Up</button>
        </form>

        <p>
            Already have an account?
            <a href="login.php">Log In</a>
        </p>
    </div>

    <div class="footer">
        <p>© Rakkez+ - Helping students build better study habits</p>
    </div>

    <script>
        function toggleSignupPassword() {
            var pass = document.getElementById("signupPassword");

            if (pass.type === "password") {
                pass.type = "text";
            } else {
                pass.type = "password";
            }
        }
    </script>

</body>
</html>