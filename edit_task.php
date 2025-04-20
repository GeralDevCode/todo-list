<?php
include 'includes/db.php';


$task = null;
if (isset($_GET['id'])) {
    $stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $task = $result->fetch_assoc();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description'] ?? '');

    $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $description, $id);
    
    if ($stmt->execute()) {
        header("Location: index.php?success=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Tarea</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>✏️ Editar Tarea</h1>
        
        <form method="POST" class="task-form">
            <input type="hidden" name="id" value="<?= $task['id'] ?>">
            
            <div class="form-group">
                <input 
                    type="text" 
                    name="title" 
                    placeholder="Título" 
                    value="<?= htmlspecialchars($task['title']) ?>"
                    required
                    maxlength="100"
                >
            </div>
            
            <div class="form-group">
                <textarea 
                    name="description" 
                    placeholder="Descripción"
                    rows="5"
                    maxlength="100"
                    oninput="countChars(this)"
                ><?= htmlspecialchars($task['description']) ?></textarea>
                <small class="char-count"><?= strlen($task['description']) ?>/100 caracteres</small>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-save">💾 Guardar Cambios</button>
                <a href="index.php" class="btn-cancel">❌ Cancelar</a>
            </div>
        </form>
    </div>

    <script>
   
    function countChars(textarea) {
        const counter = textarea.nextElementSibling;
        counter.textContent = `${textarea.value.length}/100 caracteres`;
    }
    </script>
</body>
</html>