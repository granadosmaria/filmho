<?php
// Conexión a la base de datos
$servername = "localhost"; // Cambia esto si tu servidor de base de datos no está en localhost
$username = "root"; // Tu usuario de base de datos
$password = ""; // Tu contraseña de base de datos
$dbname = "dt_live_v3"; // Nombre de la base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del avatar a eliminar
$id = $_GET['id'];

// Eliminar el avatar
$sql = "DELETE FROM avatar WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    header("Location: avatar.php"); // Redirigir al listado de avatares después de la eliminación
} else {
    echo "Error al eliminar: " . $conn->error;
}

$conn->close();
?>
