<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $error = "";

    // Prepare and execute the query safely
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // ✅ Correct way to verify password hashes
        if ($password === $user['password']){
            // Password correct — start session
            $_SESSION['password'] = $user['password'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.html");
            exit();
        } else {
            $error = "❌ Wrong password!";
        }
    } else {
        $error = "❌ User not found!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>

</head>
<body>
  <h2>Login Form</h2>

  <form method="POST" action="">
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Login</button>
    <p>You don't have an account? <a href="registration.html">Register</a></p>
  </form>

  <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>
