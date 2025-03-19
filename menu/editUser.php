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

    // Obtener los detalles del usuario
    $sql = "SELECT * FROM user WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
    } else {
        echo "Usuario no encontrado.";
        exit();
    }

} else {
    echo "ID no proporcionado.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['mobile']) && isset($_POST['expiry_date']) && isset($_POST['type'])) {
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_path = "uploads/" . $image;
    move_uploaded_file($image_tmp, $image_path);

    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    $expiry_date = $conn->real_escape_string($_POST['expiry_date']);
    $type = $conn->real_escape_string($_POST['type']);

    // Actualizar el usuario
    $sql = "UPDATE user SET image = '$image', name = '$name', email = '$email', mobile = '$mobile', expiry_date = '$expiry_date', type = '$type' WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Usuario actualizado correctamente.";
        header("Location: user.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
</head>
<body>
    <h1>Editar Usuario</h1>
    <form action="editUser.php?id=<?php echo $user['id']; ?>" method="POST" enctype="multipart/form-data">
        <label for="image">Imagen:</label>
        <input type="file" id="image" name="image">

        <label for="name">Nombre:</label>
        <input type="text" id="name" name="name" value="<?php echo $user['name']; ?>" required>

        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>

        <label for="mobile">Móvil:</label>
        <input type="text" id="mobile" name="mobile" value="<?php echo $user['mobile']; ?>" required>

        <label for="expiry_date">Fecha de Expiración:</label>
        <input type="date" id="expiry_date" name="expiry_date" value="<?php echo $user['expiry_date']; ?>" required>

        <label for="type">Tipo:</label>
        <select name="type" id="type" required>
            <option value="admin" <?php echo ($user['type'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
            <option value="user" <?php echo ($user['type'] == 'user') ? 'selected' : ''; ?>>Usuario</option>
        </select>

        <button type="submit">Actualizar Usuario</button>
    </form>
</body>
</html>

<?php
$conn->close();
?>
