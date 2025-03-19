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

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Obtener los detalles de la sección
    $sql = "SELECT * FROM channel_section WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $section = $result->fetch_assoc();
    } else {
        echo "Sección no encontrada.";
        exit();
    }

} else {
    echo "ID no proporcionado.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title']) && isset($_POST['channel_id']) && isset($_POST['video_type']) && isset($_POST['type_id']) && isset($_POST['screen_layout'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $channel_id = (int)$_POST['channel_id'];
    $video_type = $conn->real_escape_string($_POST['video_type']);
    $type_id = (int)$_POST['type_id'];
    $screen_layout = $conn->real_escape_string($_POST['screen_layout']);

    // Actualizar la sección
    $sql = "UPDATE channel_section SET title = '$title', channel_id = $channel_id, video_type = '$video_type', type_id = $type_id, screen_layout = '$screen_layout' WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Sección actualizada correctamente.";
        header("Location: channelSection.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

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
    <form action="editSection.php?id=<?php echo $section['id']; ?>" method="POST">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo $section['title']; ?>" required>

        <label for="channel_id">Channel ID:</label>
        <input type="number" id="channel_id" name="channel_id" value="<?php echo $section['channel_id']; ?>" required>

        <label for="video_type">Video Type:</label>
        <input type="text" id="video_type" name="video_type" value="<?php echo $section['video_type']; ?>" required>

        <label for="type_id">Type ID:</label>
        <input type="number" id="type_id" name="type_id" value="<?php echo $section['type_id']; ?>" required>

        <label for="screen_layout">Screen Layout:</label>
        <input type="text" id="screen_layout" name="screen_layout" value="<?php echo $section['screen_layout']; ?>" required>

        <button type="submit">Actualizar Sección</button>
    </form>
</body>
</html>

<?php
$conn->close();
?>
