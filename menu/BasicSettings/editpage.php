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

// Verificar si se ha enviado un ID de página
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener los detalles de la página desde la base de datos
    $sql = "SELECT * FROM page WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $page = $result->fetch_assoc();
    } else {
        echo "Página no encontrada";
        exit();
    }
} else {
    echo "ID no proporcionado.";
    exit();
}

// Procesar el formulario de edición
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $page_name = $_POST['page_name'];
    
    // Verificar si se ha subido una nueva imagen
    if (isset($_FILES['icon']) && $_FILES['icon']['error'] == 0) {
        // Subir la nueva imagen
        $icon = addslashes(file_get_contents($_FILES['icon']['tmp_name']));
        
        // Actualizar la página con la nueva imagen
        $sql_update = "UPDATE page SET title = '$title', page_name = '$page_name', icon = '$icon' WHERE id = $id";
    } else {
        // Si no se subió una nueva imagen, solo actualizar los campos de texto
        $sql_update = "UPDATE page SET title = '$title', page_name = '$page_name' WHERE id = $id";
    }

    // Ejecutar la consulta de actualización
    if ($conn->query($sql_update) === TRUE) {
        echo "Página actualizada correctamente.";
        header("Location: page.php"); // Redirigir de vuelta al listado de páginas
        exit();
    } else {
        echo "Error: " . $sql_update . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Página</title>
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>
    <!-- Menú Superior -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-left">
                <h2>Panel de Administrador</h2>
            </div>
            <div class="navbar-right">
                <a href="#" id="logout"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
            </div>
        </div>
    </nav>

    <!-- Menú Lateral -->
    <div id="sidebar" class="sidebar">
        <ul class="sidebar-links">
            <li><a href="index.html"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="page.php"><i class="fas fa-file"></i> Page</a></li>
        </ul>
    </div>

    <!-- Contenido Principal -->
    <div class="main-content">
        <div id="content">
            <h1>Editar Página</h1>

            <!-- Formulario de edición -->
            <form action="editpage.php?id=<?php echo $page['id']; ?>" method="POST" enctype="multipart/form-data">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo $page['title']; ?>" required>
                
                <label for="page_name">Page Name:</label>
                <input type="text" id="page_name" name="page_name" value="<?php echo $page['page_name']; ?>" required>
                
                <label for="icon">Icon (Imagen):</label>
                <input type="file" id="icon" name="icon" accept="image/*">
                
                <!-- Mostrar la imagen actual -->
                <div>
                    <h3>Imagen actual:</h3>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($page['icon']); ?>" alt="icon" width="100">
                </div>
                
                <button type="submit" class="btn-black">Actualizar Página</button>
            </form>
        </div>
    </div>

    <script src="../../scripts.js"></script>
</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
