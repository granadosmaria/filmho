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

    // Obtener los datos del idioma
    $sql = "SELECT id, name, image FROM language WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $image = $row['image'];
    } else {
        echo "Idioma no encontrado.";
        exit();
    }
}

// Procesar el formulario de edición
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
    $name = $_POST['name'];

    // Verificar si se sube una nueva imagen
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
        $sql = "UPDATE language SET name = '$name', image = '$image' WHERE id = $id";
    } else {
        $sql = "UPDATE language SET name = '$name' WHERE id = $id";
    }

    if ($conn->query($sql) === TRUE) {
        echo "Idioma actualizado correctamente.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Idioma</title>
    <link rel="stylesheet" href="../../styles.css">
</head>

<body>
    <h1>Editar Idioma</h1>
    <form action="editlanguage.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
        <label for="name">Nombre del Idioma:</label>
        <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>

        <label for="image">Imagen (Opcional):</label>
        <input type="file" id="image" name="image" accept="image/*">

        <button type="submit" class="btn-black">Guardar Cambios</button>
    </form>

    <a href="languages.php">Volver a la lista de idiomas</a>
</body>

</html>

<?php
// Cerrar la conexión
$conn->close();
?>
