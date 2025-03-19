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

// Verificar si se ha enviado el ID para eliminar
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Eliminar el registro de la base de datos
    $delete_sql = "DELETE FROM category WHERE id = $id";

    if ($conn->query($delete_sql) === TRUE) {
        echo "Categoría eliminada correctamente.";
        header("Location: categories.php"); // Redirigir de vuelta a la lista de categorías
        exit;
    } else {
        echo "Error al eliminar la categoría: " . $conn->error;
    }
} else {
    echo "ID no proporcionado.";
    exit;
}

// Cerrar la conexión
$conn->close();
?>
