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

// Verificar si se ha pasado un ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Eliminar el idioma de la base de datos
    $sql = "DELETE FROM language WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Idioma eliminado correctamente.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Redirigir a la página principal después de eliminar
    header("Location: languages.php");
    exit();
} else {
    echo "No se proporcionó un ID válido.";
}

// Cerrar la conexión
$conn->close();
?>
