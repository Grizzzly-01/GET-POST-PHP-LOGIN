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

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['username']) && isset($_GET['password'])) {
        $username = htmlspecialchars($_GET['username'], ENT_QUOTES, 'UTF-8');
        $password = htmlspecialchars($_GET['password'], ENT_QUOTES, 'UTF-8');

        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (md5($password) === $user['password']) {
                $_SESSION['username'] = $user['username'];

                if (isset($_GET['remember_me'])) {
                    $remember_token = bin2hex(random_bytes(16));
                    setcookie('remember_token', $remember_token, time() + (86400 * 30), "/");

                    $update_token_sql = "UPDATE users SET remember_token = ? WHERE id = ?";
                    $stmt = $conn->prepare($update_token_sql);
                    $stmt->bind_param("si", $remember_token, $user['id']);
                    $stmt->execute();
                }

                header("Location: dashboard.php");
                exit;
            } else {
                $error_message ="Login gagal. Password salah.";
            }
        } else {
            $error_message = "Login gagal. Username tidak ditemukan.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelompok 2</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #111823;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        form h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        input[type="text"], 
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="checkbox"] {
            margin-right: 10px;
        }

        label {
            font-size: 14px;
            color: #666;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #ff4654;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #ba3a46;
        }

        .form-group {
            margin-bottom: 20px;
        }

        @media (max-width: 600px) {
            form {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
<form action="login.php" method="GET" autocomplete="off"><br>
<?php echo $error_message; ?>
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <label><input type="checkbox" name="remember_me"> Remember Me</label><br><br>
    <button type="submit">Login</button>
</form>
</body>
</html>