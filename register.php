<?php
$conn=new mysqli("localhost","root","","user_db");

if( $conn->connect_error) {
    die("connection failed:".$conn->connect_error);
}
$username = $_POST['username'];
$password=$_POST['password'];


$sql = "INSERT INTO users (username, password) 
VALUES('$username','$password')";
    if ($conn->query($sql) === TRUE) {
        // ✅ Redirect to login page
        header("Location: login.php");
        exit();
    } else {
        echo "Error, Tafadhali rudia tena: " . $conn->error;
    }
    
    $conn->close();
    ?>