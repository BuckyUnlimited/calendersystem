<?php
include './config/db.con.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ./?page=login');
    exit;
}

$user_id = $_SESSION['user_id'];


$stmt = $db->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    if (!empty($_FILES['image']['name'])) {
        $img = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "assets/uploads/" . $img);

        $stmt = $db->prepare("UPDATE users SET name=?, email=?, profile_image=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $email, $img, $user_id);
    } else {
        $stmt = $db->prepare("UPDATE users SET name=?, email=? WHERE id=?");
        $stmt->bind_param("ssi", $name, $email, $user_id);
    }

    $stmt->execute();
    header("Location: ./?page=profile");
    exit;
}

// CHANGE PASSWORD
if (isset($_POST['change_password'])) {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];

    if (password_verify($current, $user['password'])) {
        $new_pass = password_hash($new, PASSWORD_DEFAULT);

        $stmt = $db->prepare("UPDATE users SET password=? WHERE id=?");
        $stmt->bind_param("si", $new_pass, $user_id);
        $stmt->execute();

        $msg = "Password changed!";
    } else {
        $err = "Wrong password!";
    }
}
?>

<div class="container mt-4">

    <h3 class="mb-4">⚙ Profile & Settings</h3>

    <?php if (isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>
    <?php if (isset($err)) echo "<div class='alert alert-danger'>$err</div>"; ?>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow p-3 text-center">

                <img src="assets/uploads/<?php echo $user['profile_image']; ?>"
                    class="rounded-circle mb-3" width="120" height="120">

                <form method="post" enctype="multipart/form-data">

                    <input type="file" name="image" class="form-control mb-2">

                    <input type="text" name="name" class="form-control mb-2" value="<?php echo $user['name']; ?>">
                    <input type="email" name="email" class="form-control mb-2" value="<?php echo $user['email']; ?>">

                    <button class="btn btn-primary w-100" name="update_profile">Update Profile</button>
                </form>

            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow p-3">

                <h5>🔐 Change Password</h5>

                <form method="post">
                    <input type="password" name="current_password" placeholder="Current" class="form-control mb-2">
                    <input type="password" name="new_password" placeholder="New Password" class="form-control mb-2">

                    <button class="btn btn-warning w-100" name="change_password">Change Password</button>
                </form>

            </div>

            <div class="card shadow p-3 mt-3">
                <h5>🌙 Theme</h5>

                <button onclick="toggleTheme()" class="btn btn-dark w-100">Toggle Dark Mode</button>
            </div>

        </div>

    </div>

</div>

<script>
    function toggleTheme() {
        let mode = localStorage.getItem('theme');

        if (mode === 'dark') {
            document.body.classList.remove('bg-dark', 'text-white');
            localStorage.setItem('theme', 'light');
        } else {
            document.body.classList.add('bg-dark', 'text-white');
            localStorage.setItem('theme', 'dark');
        }
    }

    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('bg-dark', 'text-white');
    }
</script>