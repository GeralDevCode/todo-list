<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if (empty($_POST['title'])) {
        header("Location: index.php?error=1");
        exit();
    }

    $title = trim($_POST['title']);
    $description = trim($_POST['description'] ?? '');

    try {
        $stmt = $conn->prepare("INSERT INTO tasks (title, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $description);
        
        if ($stmt->execute()) {
            header("Location: index.php?success=1");
        } else {
            header("Location: index.php?error=1");
        }
    } catch (Exception $e) {
        header("Location: index.php?error=1");
    }
    exit();
}

header("Location: index.php");
?>