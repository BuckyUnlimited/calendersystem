<?php
include './config/db.con.php';
if(!isset($_SESSION['user_id'])) exit;

if(isset($_POST['delete_task'])){
    $id = $_POST['id'];
    $stmt = $db->prepare("DELETE FROM tasks WHERE id=? AND user_id=?");
    $stmt->bind_param("ii",$id,$_SESSION['user_id']);
    $stmt->execute();

    header('Location: ./?page=task');
    exit;
}
?>