<!-- register.php -->
<div class="container-fluid vh-100 d-flex justify-content-center align-items-center" style="background: linear-gradient(135deg, #667eea, #764ba2);">
    <div class="card shadow-lg p-4 rounded-3" style="width: 100%; max-width: 400px; background-color: #fff;">
        <h3 class="text-center mb-4 fw-bold" style="color: #333;">Register</h3>

        <?php
        if (isset($_POST['register'])) {

            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $password_raw = trim($_POST['password']);

            if (empty($name) || empty($email) || empty($password_raw)) {
                $_SESSION['error'] = "All fields are required!";
                header("Location: ./?page=register");
                exit;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "Invalid email format!";
                header("Location: ./?page=register");
                exit;
            }

            $check = $db->prepare("SELECT id FROM users WHERE email=?");
            $check->bind_param("s", $email);
            $check->execute();
            $result = $check->get_result();

            if ($result->num_rows > 0) {
                $_SESSION['error'] = "Email already exists!";
                header("Location: ./?page=register");
                exit;
            }

            $password = password_hash($password_raw, PASSWORD_DEFAULT);

            $stmt = $db->prepare("INSERT INTO users(name,email,password) VALUES(?,?,?)");
            $stmt->bind_param("sss", $name, $email, $password);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Register successful! Please login.";
                header("Location: ./?page=login");
                exit;
            } else {
                $_SESSION['error'] = "Registration failed!";
                header("Location: ./?page=register");
                exit;
            }
        }
        ?>

        <form method="post" class="needs-validation" novalidate>
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter your name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>
            <div class="mb-3 position-relative">
                <label class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                <span onclick="togglePass()" style="position:absolute; right:10px; top:38px; cursor:pointer;"><i class="fa fa-eye"></i></span>
            </div>

            <script>
                function togglePass() {
                    let p = document.getElementById("password");
                    p.type = (p.type === "password") ? "text" : "password";
                }
            </script>
            <button type="submit" name="register" class="btn w-100 fw-bold text-white" style="background: linear-gradient(90deg, #6a11cb 0%, #2575fc 100%);">Register</button>
        </form>

        <p class="mt-3 text-center text-muted">Already have an account? <a href="./?page=login" class="text-decoration-none" style="color: #6a11cb;">Login</a></p>
    </div>
</div>

<script>
    (function() {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>