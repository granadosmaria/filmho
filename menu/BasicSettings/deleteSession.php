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

// Verificar si se ha enviado un id para eliminar
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Preparar y ejecutar la consulta para eliminar la sesión
    $sql = "DELETE FROM session WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Sesión eliminada correctamente.";
        header("Location: session.php"); // Redirige al listado de sesiones
        exit;
    } else {
        echo "Error al eliminar la sesión: " . $conn->error;
    }
} else {
    echo "ID no proporcionado.";
    exit;
}

// Cerrar la conexión
$conn->close();
?>
