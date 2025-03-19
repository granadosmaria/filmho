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

// Verificar si se pasa el ID del cast para editar
if (isset($_GET['id'])) {
    $cast_id = $_GET['id'];

    // Obtener el cast de la base de datos
    $sql = "SELECT * FROM cast WHERE id = $cast_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        die("Cast no encontrado");
    }
}

// Procesar el formulario de actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $image = $_POST['image'];
    $name = $_POST['name'];
    $type = $_POST['type'];
    $personal_info = $_POST['personal_info'];

    // Actualizar el registro
    $update_sql = "UPDATE cast SET image='$image', name='$name', type='$type', personal_info='$personal_info' WHERE id=$cast_id";

    if ($conn->query($update_sql) === TRUE) {
        echo "Cast actualizado correctamente.";
        header('Location: cast.php'); // Redirigir después de la actualización
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cast</title>
</head>
<body>
    <h1>Editar Cast</h1>

    <form method="POST" action="">
        <label for="image">Imagen:</label>
        <input type="text" id="image" name="image" value="<?php echo $row['image']; ?>" required>

        <label for="name">Nombre:</label>
        <input type="text" id="name" name="name" value="<?php echo $row['name']; ?>" required>

        <label for="type">Tipo:</label>
        <input type="text" id="type" name="type" value="<?php echo $row['type']; ?>" required>

        <label for="personal_info">Información Personal:</label>
        <textarea id="personal_info" name="personal_info" required><?php echo $row['personal_info']; ?></textarea>

        <button type="submit">Actualizar</button>
    </form>
</body>
</html>
