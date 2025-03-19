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

    // Eliminar el banner
    $sql = "DELETE FROM channel_banner WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Banner eliminado correctamente.";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "No se proporcionó un ID válido.";
}

$conn->close();

// Redirigir a la página principal después de la eliminación
header("Location: channelBanner.php");
exit();
?>
