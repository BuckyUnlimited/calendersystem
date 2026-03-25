<?php
include './config/db.con.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit;
}

$user_id = $_SESSION['user_id'];


if (isset($_POST['add_task'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = $_POST['category'];
    $priority = $_POST['priority'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $stmt = $db->prepare("INSERT INTO tasks (user_id, title, description, category, priority, start_time, end_time) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param("issssss", $user_id, $title, $description, $category, $priority, $start_time, $end_time);
    $stmt->execute();

    if ($stmt->affected_rows) {
        header('Location: ./?page=dashboard');
        // Redirect after adding
        exit;
    } else {
        $error = "Failed to add task!";
    }
}
?>

 