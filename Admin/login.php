<?php
session_start();
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // change these before going live
    $valid_user = "admin";
    $valid_pass = "secret123";

    if ($username === $valid_user && $password === $valid_pass) {
        $_SESSION['logged_in'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Admin Login</title>
<style>
body{font-family:Arial;background:#f4f4f4;display:flex;align-items:center;justify-content:center;height:100vh;}
form{background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 6px rgba(0,0,0,0.1);}
input{display:block;margin:10px 0;padding:10px;width:260px;font-size:16px;}
button{background:#007bff;color:white;border:none;padding:10px;width:100%;font-size:16px;border-radius:4px;cursor:pointer;}
.error{color:red;margin-top:10px;}
</style>
</head>
<body>
<form method="post">
<h2>Admin Login</h2>
<input type="text" name="username" placeholder="Username" required>
<input type="password" name="password" placeholder="Password" required>
<button type="submit">Login</button>
<?php if($error) echo "<div class='error'>$error</div>"; ?>
</form>
</body>
</html>
