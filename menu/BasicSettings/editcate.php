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

// Verificar si se ha enviado el ID para editar
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener el registro que corresponde al ID
    $sql = "SELECT id, name FROM category WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
    } else {
        echo "No se encontró la categoría.";
        exit;
    }

    // Si el formulario fue enviado, actualizar el registro
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];

        // Actualizar el registro en la base de datos
        $update_sql = "UPDATE category SET name = '$name' WHERE id = $id";
        
        if ($conn->query($update_sql) === TRUE) {
            echo "Categoría actualizada correctamente.";
            header("Location: categories.php"); // Redirigir de vuelta a la lista de categorías
            exit;
        } else {
            echo "Error al actualizar la categoría: " . $conn->error;
        }
    }
} else {
    echo "ID no proporcionado.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoría</title>
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>

<h1>Editar Categoría</h1>

<form action="editcate.php?id=<?php echo $id; ?>" method="POST">
    <label for="name">Nombre de la Categoría:</label>
    <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
    <button type="submit" class="btn-black">Actualizar Categoría</button>
</form>

</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
