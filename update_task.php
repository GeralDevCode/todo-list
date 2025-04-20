<?php
include 'includes/db.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    
    $stmt = $conn->prepare("SELECT status FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $task = $result->fetch_assoc();
    
    
    $new_status = $task['status'] == 'completada' ? 'pendiente' : 'completada';
    
    $stmt = $conn->prepare("UPDATE tasks SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $id);
    $stmt->execute();
}

header("Location: index.php");
exit();
?>