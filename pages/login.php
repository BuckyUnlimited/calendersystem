
<?php

if (isset($_GET['success'])) {
    echo '<div class="alert alert-success">Registration successful! Please login.</div>';
}

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $stmt = $db->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: ./?page=dashboard');
        exit;
    } else {
        $error = "Invalid email or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>

        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            background-color: rgba(255, 255, 255, 0.95);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
        }

        .btn-primary {
            background: linear-gradient(90deg, #667eea, #764ba2);
            border: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #764ba2, #667eea);
        }

        .form-control:focus {
            border-color: #764ba2;
            box-shadow: 0 0 0 0.2rem rgba(118, 75, 162, 0.25);
        }

        .alert {
            border-radius: 0.5rem;
        }

        .login-footer a {
            color: #764ba2;
            text-decoration: none;
            font-weight: 500;
        }

        .login-footer a:hover {
            text-decoration: underline;
            color: #667eea;
        }
    </style>
</head>

<body>

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card" style="max-width: 400px; width: 100%;">
            <h3 class="text-center mb-4" style="color:#333;">Welcome Back</h3>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
            </form>
            <p class="mt-3 text-center login-footer">
                Don't have an account? <a href="./?page=register">Register</a>
            </p>
        </div>
    </div>


</body>

</html>