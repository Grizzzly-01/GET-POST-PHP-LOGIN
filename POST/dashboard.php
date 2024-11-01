<?php
session_start();
$host = 'localhost';
$db = 'login_system';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['username'])) {
    if (isset($_COOKIE['remember_token'])) {
        $remember_token = $_COOKIE['remember_token'];

        /*if (isset($_COOKIE['username'])) {
            echo ' User: ' . $_COOKIE['username'];
        } else {
            echo 'Cookie user tidak ditemukan';
        }*/

        $sql = "SELECT * FROM users WHERE remember_token = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $remember_token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            $_SESSION['username'] = $user['username'];
        } else {
            header("Location: login.php");
            exit;
        }
    } else {
        header("Location: login.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        h1 {
            font-size: 36px;
            text-align: center;
            margin-bottom: 20px;
        }

        body {
            font-size: 16px;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        a {
            font-size: 14px;
            color: #333;
            text-decoration: none;
            border: 1px solid #333;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #333;
            color: white;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Dashboard</h1>
        <p>Selamat datang, <?php echo $_SESSION['username']; ?>!</p>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
