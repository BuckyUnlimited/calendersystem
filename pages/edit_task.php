<?php
include './config/db.con.php';
if(!isset($_SESSION['user_id'])) exit;

if(isset($_POST['edit_task'])) {
    $id = $_POST['id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = $_POST['category'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $stmt = $db->prepare("UPDATE tasks SET title=?, description=?, category=?, priority=?, status=?, start_time=?, end_time=? WHERE id=? AND user_id=?");
    $stmt->bind_param("ssssssiii",$title,$description,$category,$priority,$status,$start_time,$end_time,$id,$_SESSION['user_id']);
    $stmt->execute();

    header('Location: ./?page=task'); 
    exit;
}
?>