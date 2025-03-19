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

// Verificar si se ha enviado un id para editar
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener la sesión por el ID
    $sql = "SELECT id, name, action FROM session WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "No se encontró la sesión.";
        exit;
    }
} else {
    echo "ID no proporcionado.";
    exit;
}

// Actualizar los datos de la sesión si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name']) && isset($_POST['action'])) {
    $name = $_POST['name'];
    $action = $_POST['action'];

    // Preparar y ejecutar la consulta para actualizar la sesión
    $sql = "UPDATE session SET name = '$name', action = '$action' WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Sesión actualizada correctamente.";
        header("Location: session.php"); // Redirige al listado de sesiones
        exit;
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
    <title>Editar Sesión</title>
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-left">
                <h2>Editar Sesión</h2>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <h3>Editar sesión #<?php echo $row['id']; ?></h3>
        
        <form action="editSession.php?id=<?php echo $row['id']; ?>" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $row['name']; ?>" required>
            
            <label for="action">Action:</label>
            <input type="text" id="action" name="action" value="<?php echo $row['action']; ?>" required>
            
            <button type="submit" class="btn-black">Actualizar</button>
        </form>
    </div>
</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
