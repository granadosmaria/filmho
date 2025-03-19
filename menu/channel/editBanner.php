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

    // Obtener los detalles del banner
    $sql = "SELECT * FROM channel_banner WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $banner = $result->fetch_assoc();
    } else {
        echo "Banner no encontrado.";
        exit();
    }

} else {
    echo "ID no proporcionado.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name']) && isset($_POST['image']) && isset($_POST['link']) && isset($_POST['order_no'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $image = $conn->real_escape_string($_POST['image']);
    $link = $conn->real_escape_string($_POST['link']);
    $order_no = (int)$_POST['order_no'];

    // Actualizar el banner
    $sql = "UPDATE channel_banner SET name = '$name', image = '$image', link = '$link', order_no = $order_no WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Banner actualizado correctamente.";
        header("Location: channelBanner.php");
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
    <title>Editar Banner</title>
</head>
<body>
    <h1>Editar Banner</h1>
    <form action="editBanner.php?id=<?php echo $banner['id']; ?>" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $banner['name']; ?>" required>

        <label for="image">Image:</label>
        <input type="text" id="image" name="image" value="<?php echo $banner['image']; ?>" required>

        <label for="link">Link:</label>
        <input type="text" id="link" name="link" value="<?php echo $banner['link']; ?>" required>

        <label for="order_no">Order Number:</label>
        <input type="number" id="order_no" name="order_no" value="<?php echo $banner['order_no']; ?>" required>

        <button type="submit">Actualizar Banner</button>
    </form>
</body>
</html>

<?php
$conn->close();
?>
