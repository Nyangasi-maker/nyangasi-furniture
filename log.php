<?php
session_start();
ob_start();

$db_host = 'localhost';
$db_user = 'root';
$db_name = 'customers';

$conn=new mysqli("localhost","root","","customers");
if ($conn->connect_error) {
    die('Database connection error');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: log.php');
    exit;
}

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if ($email === '' || $password === '') {
    header('Location: log.php?error=empty');
    exit;
}

$stmt = $conn->prepare("SELECT id, password_hash, fname FROM registers WHERE email = ? LIMIT 1");
if (!$stmt) {
    header('Location: login.php?error=db');
    exit;
}
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    if (password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['fname'];

        ob_end_clean();
        header('Location: dashboard.html');
        exit;
    } else {
        header('Location: login.php?error=invalid');
        exit;
    }
} else {
    header('Location: login.php?error=notfound');
    exit;
}

// close
$stmt->close();
$conn->close();
?>
