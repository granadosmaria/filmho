<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dt_live_v3";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si la acción es eliminar
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_to_delete = $_GET['id'];

    // Consultar si el ID existe antes de eliminar
    $check_sql = "SELECT * FROM app_section WHERE id = $id_to_delete";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // Eliminar el registro de la base de datos
        $delete_sql = "DELETE FROM app_section WHERE id = $id_to_delete";

        if ($conn->query($delete_sql) === TRUE) {
            echo "Registro eliminado con éxito.";
        } else {
            echo "Error al eliminar el registro: " . $conn->error;
        }
    } else {
        echo "No se encontró el registro para eliminar.";
    }
}

// Obtener todas las secciones para mostrar
$sql = "SELECT * FROM app_section LIMIT $offset, $items_per_page"; 
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sección</title>
</head>
<body>
    <h1>Editar Sección</h1>
    <form action="edit_app_section.php?id=<?php echo $id_to_edit; ?>" method="POST">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo $row['title']; ?>" required>
        
        <label for="video_id">Video ID:</label>
        <input type="text" id="video_id" name="video_id" value="<?php echo $row['video_id']; ?>" required>
        
        <label for="type_id">Type ID:</label>
        <input type="text" id="type_id" name="type_id" value="<?php echo $row['type_id']; ?>" required>
        
        <label for="screen_layout">Screen Layout:</label>
        <input type="text" id="screen_layout" name="screen_layout" value="<?php echo $row['screen_layout']; ?>" required>
        
        <button type="submit" class="btn-black">Actualizar Sección</button>
    </form>
</body>
</html>
