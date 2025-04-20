<?php
include 'includes/db.php';

$success = isset($_GET['success']);
$error = isset($_GET['error']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Tareas</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>📝 Lista de Tareas</h1>
        
        <?php if ($success) : ?>
            <div class="alert success">Tarea agregada exitosamente</div>
        <?php endif; ?>
        
        <?php if ($error) : ?>
            <div class="alert error">Error al agregar la tarea</div>
        <?php endif; ?>

        <form action="add_task.php" method="POST" class="task-form">
            <div class="form-group">
                <input 
                    type="text" 
                    name="title" 
                    placeholder="Título de la tarea" 
                    required
                    maxlength="100"
                >
            </div>
            
            <div class="form-group">
                <textarea 
                    name="description" 
                    placeholder="Descripción (máx. 100 caracteres)"
                    rows="3"
                    maxlength="100"
                    oninput="countChars(this)"
                ></textarea>
                <small class="char-count">0/100 caracteres</small>
            </div>
            
            <button type="submit" class="btn-add">
                ➕ Añadir Tarea
            </button>
        </form>

        <div class="task-list-container">
            <h2>Tus Tareas</h2>
            
            <?php
            $sql = "SELECT * FROM tasks ORDER BY 
                    CASE WHEN status = 'pendiente' THEN 0 ELSE 1 END,
                    created_at DESC";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) : ?>
                <ul class="task-list">
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <li class="task-item <?= $row['status'] ?>">
                            <div class="task-content">
                                <h3><?= htmlspecialchars($row['title']) ?></h3>
                                <div class="description-container <?= strlen($row['description']) > 100 ? 'collapsed' : '' ?>">
                                    <?php if (!empty($row['description'])) : ?>
                                        <p><?= htmlspecialchars($row['description']) ?></p>
                                    <?php endif; ?>
                                </div>
                                <?php if (strlen($row['description']) > 100) : ?>
                                    <button class="toggle-description">Ver más ▼</button>
                                <?php endif; ?>
                                <small>
                                    Creada: <?= date('d/m/Y H:i', strtotime($row['created_at'])) ?>
                                </small>
                            </div>
                            
<div class="task-actions">
    <a href="edit_task.php?id=<?= $row['id'] ?>" class="edit-btn" title="Editar">✏️</a>
    <a href="update_task.php?id=<?= $row['id'] ?>" class="status-toggle">
        <?= $row['status'] == 'pendiente' ? 'Completar' : 'Reabrir' ?>
    </a>
    <a href="delete_task.php?id=<?= $row['id'] ?>" class="delete-btn" 
       onclick="return confirm('¿Seguro que quieres eliminar esta tarea?')" title="Eliminar">
       🗑️
    </a>
</div>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else : ?>
                <p class="no-tasks">No hay tareas registradas aún.</p>
            <?php endif; ?>
        </div>
    </div>

    <footer class="app-footer">
        <p>Sistema de Tareas &copy; <?= date('Y') ?></p>
    </footer>

    <script>
    
    function countChars(textarea) {
        const counter = textarea.nextElementSibling;
        counter.textContent = `${textarea.value.length}/100 caracteres`;
        
        if(textarea.value.length > 100) {
            counter.style.color = 'red';
            textarea.setCustomValidity('Máximo 100 caracteres');
        } else {
            counter.style.color = 'inherit';
            textarea.setCustomValidity('');
        }
    }

    
    document.querySelectorAll('.toggle-description').forEach(button => {
        button.addEventListener('click', (e) => {
            const taskItem = e.target.closest('.task-item');
            const descContainer = e.target.previousElementSibling;
            
            descContainer.classList.toggle('collapsed');
            taskItem.classList.toggle('expanded');
            
            const isCollapsed = descContainer.classList.contains('collapsed');
            e.target.innerHTML = isCollapsed ? 'Ver más ▼' : 'Ver menos ▲';
        });
    });
    </script>
</body>
</html>