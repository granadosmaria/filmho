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

// Obtener el ID del avatar a editar
$id = $_GET['id'];

// Obtener los detalles del avatar actual
$sql = "SELECT id, name, avatar FROM avatar WHERE id = $id";
$result = $conn->query($sql);
$avatar = $result->fetch_assoc();

// Procesar el formulario de edición
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];

    // Si se sube una nueva imagen
    if ($_FILES['avatar']['tmp_name']) {
        $avatar = addslashes(file_get_contents($_FILES['avatar']['tmp_name']));
        $sql = "UPDATE avatar SET name = '$name', avatar = '$avatar' WHERE id = $id";
    } else {
        $sql = "UPDATE avatar SET name = '$name' WHERE id = $id";
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: avatar.php"); // Redirigir al listado de avatares
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Avatar</title>
</head>

<body>
    <h1>Editar Avatar</h1>
    <form action="editavatar.php?id=<?php echo $avatar['id']; ?>" method="POST" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $avatar['name']; ?>" required>

        <label for="avatar">Avatar (Imagen):</label>
        <input type="file" id="avatar" name="avatar" accept="image/*">

        <button type="submit">Actualizar Avatar</button>
    </form>
</body>

</html>

<?php
// Cerrar la conexión
$conn->close();
?>
