<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dt_live_v3";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del video a editar
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Obtener los datos del video
    $sql = "SELECT * FROM video WHERE id = $id";
    $result = $conn->query($sql);
    $video = $result->fetch_assoc();

    if (!$video) {
        echo "Video no encontrado.";
        exit;
    }
}

// Actualizar el video si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $video_type = $conn->real_escape_string($_POST['video_type']);
    $is_title = $_POST['is_title'] == 'on' ? 1 : 0;
    $category_id = (int)$_POST['category_id'];

    $sql_update = "UPDATE video SET video_type = '$video_type', is_title = $is_title, category_id = $category_id WHERE id = $id";
    
    if ($conn->query($sql_update) === TRUE) {
        echo "Video actualizado correctamente.";
    } else {
        echo "Error al actualizar el video: " . $conn->error;
    }
}

// Obtener las categorías para el formulario
$sql_categories = "SELECT id, name FROM category";
$result_categories = $conn->query($sql_categories);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Video</title>
</head>
<body>
    <h1>Editar Video</h1>
    <form action="editVideo.php?id=<?php echo $id; ?>" method="POST">
        <label for="video_type">Tipo de Video:</label>
        <input type="text" id="video_type" name="video_type" value="<?php echo $video['video_type']; ?>" required>

        <label for="is_title">¿Es título destacado?</label>
        <input type="checkbox" id="is_title" name="is_title" <?php echo $video['is_title'] ? 'checked' : ''; ?>>

        <label for="category_id">Categoría:</label>
        <select name="category_id" id="category_id" required>
            <?php
            if ($result_categories->num_rows > 0) {
                while ($row = $result_categories->fetch_assoc()) {
                    $selected = $video['category_id'] == $row['id'] ? 'selected' : '';
                    echo "<option value='" . $row['id'] . "' $selected>" . $row['name'] . "</option>";
                }
            }
            ?>
        </select>

        <button type="submit">Actualizar Video</button>
    </form>
</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
