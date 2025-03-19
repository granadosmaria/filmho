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

// Verificar si se pasa el ID del cast para eliminar
if (isset($_GET['id'])) {
    $cast_id = $_GET['id'];

    // Eliminar el cast de la base de datos
    $delete_sql = "DELETE FROM cast WHERE id = $cast_id";

    if ($conn->query($delete_sql) === TRUE) {
        echo "Cast eliminado correctamente.";
        header('Location: cast.php'); // Redirigir después de eliminar
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
