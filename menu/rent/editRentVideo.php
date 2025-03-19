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

// Verificar si se pasa un ID por GET para editar el video de alquiler
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Obtener los datos del video de alquiler con el ID especificado
    $sql = "SELECT * FROM rent_video WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $video = $result->fetch_assoc();

    // Verificar si existe el registro
    if (!$video) {
        die("No se encontró el video de alquiler con el ID especificado.");
    }

    // Si se envía el formulario de edición
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Recoger los datos del formulario
        $video_id = $conn->real_escape_string($_POST['video_id']);
        $price = $conn->real_escape_string($_POST['price']);
        $type_id = $conn->real_escape_string($_POST['type_id']);
        $type = $conn->real_escape_string($_POST['type']);
        $time = $conn->real_escape_string($_POST['time']);

        // Actualizar los datos del video de alquiler en la base de datos
        $update_sql = "UPDATE rent_video SET video_id = ?, price = ?, type_id = ?, type = ?, time = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("iisssi", $video_id, $price, $type_id, $type, $time, $id);
        $update_stmt->execute();

        // Redirigir a la lista de videos de alquiler después de editar
        header("Location: rent_video.php");
        exit;
    }
} else {
    die("No se especificó el ID del video de alquiler.");
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Video de Alquiler</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="main-content">
        <div id="content">
            <h1>Editar Video de Alquiler</h1>

            <!-- Formulario de edición -->
            <form action="editRentVideo.php?id=<?php echo $video['id']; ?>" method="POST">
                <label for="video_id">ID del Video:</label>
                <input type="text" id="video_id" name="video_id" value="<?php echo $video['video_id']; ?>" required>

                <label for="price">Precio:</label>
                <input type="number" id="price" name="price" value="<?php echo $video['price']; ?>" required>

                <label for="type_id">ID de Tipo:</label>
                <input type="text" id="type_id" name="type_id" value="<?php echo $video['type_id']; ?>" required>

                <label for="type">Tipo de Video:</label>
                <input type="text" id="type" name="type" value="<?php echo $video['type']; ?>" required>

                <label for="time">Duración del Alquiler:</label>
                <input type="text" id="time" name="time" value="<?php echo $video['time']; ?>" required>

                <button type="submit" class="btn-black">Actualizar Video de Alquiler</button>
            </form>

            <a href="rent_video.php" class="btn-black">Volver a la lista de videos</a>
        </div>
    </div>
</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
