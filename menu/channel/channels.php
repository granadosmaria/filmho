<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dt_live_v3";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Número de elementos por página
$items_per_page = 10;

// Definir la variable de búsqueda
$search_query = '';

// Verificar si se ha enviado un término de búsqueda
if (isset($_POST['search'])) {
    $search_query = $_POST['search'];
}

// Obtener el número total de registros
$total_sql = "SELECT COUNT(*) AS total FROM channel WHERE name LIKE '%$search_query%'";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_items = $total_row['total'];

// Calcular el número total de páginas
$total_pages = ceil($total_items / $items_per_page);

// Obtener la página actual desde la URL
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
if ($page > $total_pages) $page = $total_pages;

// Calcular el desplazamiento (offset) para la consulta SQL
$offset = ($page - 1) * $items_per_page;
if ($offset < 0) {
    $offset = 0;  // Asegurarse de que el desplazamiento no sea negativo
}

// **Eliminar Canal**
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_to_delete = $_GET['id'];

    // Consultar si el ID existe antes de eliminar
    $check_sql = "SELECT * FROM channel WHERE id = $id_to_delete";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // Eliminar el registro de la base de datos
        $delete_sql = "DELETE FROM channel WHERE id = $id_to_delete";

        if ($conn->query($delete_sql) === TRUE) {
            header('Location: channel.php'); // Redirigir después de eliminar
            exit;
        } else {
            echo "Error al eliminar el registro: " . $conn->error;
        }
    } else {
        echo "No se encontró el registro para eliminar.";
    }
}

// **Editar Canal**
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id_to_edit = $_GET['id'];

    // Obtener la información del canal a editar
    $edit_sql = "SELECT * FROM channel WHERE id = $id_to_edit";
    $edit_result = $conn->query($edit_sql);
    $channel_data = $edit_result->fetch_assoc();

    // Verificar si el canal fue encontrado
    if (!$channel_data) {
        echo "Canal no encontrado.";
    }

    // Si el formulario de edición fue enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image']) && isset($_FILES['landscape']) && isset($_POST['name'])) {
        $name = $_POST['name'];
        $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
        $landscape = addslashes(file_get_contents($_FILES['landscape']['tmp_name']));

        // Actualizar el canal en la base de datos
        $update_sql = "UPDATE channel SET name = '$name', image = '$image', landscape = '$landscape' WHERE id = $id_to_edit";

        if ($conn->query($update_sql) === TRUE) {
            echo "Canal actualizado correctamente.";
            header('Location: channel.php'); // Redirigir después de actualizar
            exit;
        } else {
            echo "Error al actualizar el canal: " . $conn->error;
        }
    }
}

// Consulta para obtener los registros de la base de datos con búsqueda
$sql = "SELECT id, name, image, landscape FROM channel WHERE name LIKE '%$search_query%' LIMIT $offset, $items_per_page";
$result = $conn->query($sql);

// **Agregar Canal**
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image']) && isset($_FILES['landscape']) && isset($_POST['name']) && !isset($_GET['action'])) {
    $name = $_POST['name'];
    $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
    $landscape = addslashes(file_get_contents($_FILES['landscape']['tmp_name']));

    // Insertar el nuevo canal en la base de datos
    $insert_sql = "INSERT INTO channel (name, image, landscape) VALUES ('$name', '$image', '$landscape')";

    if ($conn->query($insert_sql) === TRUE) {
        echo "Nuevo canal agregado correctamente.";
        header('Location: channel.php'); // Redirigir después de agregar
        exit;
    } else {
        echo "Error al agregar el canal: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Páginas</title>
    <link rel="stylesheet" href="../../styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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

    <!-- Botón para mostrar/ocultar el menú lateral -->
    <div id="toggle-menu" class="toggle-menu">
        <i class="fas fa-bars"></i> <!-- Icono de menú -->
    </div>

    <!-- Menú Lateral -->
    <div id="sidebar" class="sidebar">
        <ul class="sidebar-links">
            <!-- Dashboard -->
            <li><a href="admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>

            <!-- Basic Settings -->
            <li class="submenu">
                <a href="#"><i class="fas fa-cogs"></i> Basic Settings</a>
                <ul class="submenu-links">
                    <li><a href="../BasicSettings/types.php"><i class="fas fa-cogs"></i> Types</a></li>
                    <li><a href="../BasicSettings/category.php"><i class="fas fa-list"></i> Category</a></li>
                    <li><a href="../BasicSettings/avatar.php"><i class="fas fa-user"></i> Avatar</a></li>
                    <li><a href="../BasicSettings/languages.php"><i class="fas fa-language"></i> Languages</a></li>
                    <li><a href="../BasicSettings/session.php"><i class="fas fa-clock"></i> Session</a></li>
                    <li><a href="../BasicSettings/page.php"><i class="fas fa-file"></i> Page</a></li>
                </ul>
            </li>

            <!-- Home -->
            <li class="submenu">
                <a href="#"><i class="fas fa-home"></i> Home</a>
                <ul class="submenu-links">
                    <li><a href="banner.php"><i class="fas fa-image"></i> Banner</a></li>
                    <li><a href="section.php"><i class="fas fa-th"></i> Section</a></li>
                </ul>
            </li>

            <!-- Channel -->
            <li class="submenu">
                <a href="#"><i class="fas fa-tv"></i> Channel</a>
                <ul class="submenu-links">
                    <li><a href="#"><i class="fas fa-video"></i> Channels</a></li>
                    <li><a href="channelBanner.php"><i class="fas fa-image"></i> Channel Banner</a></li>
                    <li><a href="channelSection.php"><i class="fas fa-th-large"></i> Channel Section</a></li>
                </ul>
            </li>

            <!-- Users -->
            <li><a href="../users.php"><i class="fas fa-users"></i> Users</a></li>

            <!-- Cast -->
            <li><a href="../cast.php"><i class="fas fa-clapperboard"></i> Cast</a></li>

            <!-- Videos -->
            <li><a href="../videos.php"><i class="fas fa-video"></i> Videos</a></li>

            <!-- TV Shows -->
            <li><a href="../tvShows.php"><i class="fas fa-tv"></i> TV Shows</a></li>


            <!-- Rent -->
            <li class="submenu">
                <a href="#"><i class="fas fa-dollar-sign"></i> Rent</a>
                <ul class="submenu-links">
                    <li><a href="../rent/rentVideos.php"><i class="fas fa-video"></i> Rent Videos</a></li>
                    <li><a href="../rent/rentTransactions.php"><i class="fas fa-exchange-alt"></i> Rent Transactions</a></li>
                </ul>
            </li>

            <!-- Subscription -->
            <li class="submenu">
                <a href="#"><i class="fas fa-gift"></i> Subscription</a>
                <ul class="submenu-links">
                    <li><a href="../subcription/package.php"><i class="fas fa-box"></i> Package</a></li>
                    <li><a href="../subcription/payment.php"><i class="fas fa-credit-card"></i> Payment</a></li>
                </ul>
            </li>

            <!-- Espacios adicionales al final -->
            <li><a href="#"> </a></li>
            <li><a href="#"> </a></li>
        </ul>
    </div>
<!-- Contenido Principal -->
<div class="main-content">
        <h1>Chanels</h1>

        <!-- Formulario de Agregar Canal -->
        <?php if (!isset($_GET['action']) || $_GET['action'] == 'add'): ?>
            <form method="POST" action="channel.php" enctype="multipart/form-data">
                <h2>Agregar Nuevo Canal</h2>
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required>

                <label for="image">Image:</label>
                <input type="file" name="image" id="image" accept="image/*" required>

                <label for="landscape">Landscape:</label>
                <input type="file" name="landscape" id="landscape" accept="image/*" required>

                <button type="submit" class="btn-black">Agregar Canal</button>
            </form>
        <?php endif; ?>

        <!-- Formulario de Edición de Canal -->
        <?php if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($channel_data)): ?>
            <form method="POST" action="channel.php?action=edit&id=<?php echo $channel_data['id']; ?>" enctype="multipart/form-data">
                <h2>Editar Canal: <?php echo $channel_data['name']; ?></h2>
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" value="<?php echo $channel_data['name']; ?>" required>

                <label for="image">Image:</label>
                <input type="file" name="image" id="image" accept="image/*">

                <label for="landscape">Landscape:</label>
                <input type="file" name="landscape" id="landscape" accept="image/*">

                <button type="submit" class="btn-black">Actualizar Canal</button>
            </form>
        <?php endif; ?>

        <!-- Formulario de búsqueda -->
        <form method="POST" action="channel.php" class="search-form">
            <input type="text" name="search" placeholder="Buscar canal..." value="<?php echo htmlspecialchars($search_query); ?>" class="search-input">
            <button type="submit" class="btn-black"><i class="fas fa-search"></i></button>
        </form>

        <!-- Tabla de Canales -->
        <table border="1">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Landscape</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Mostrar los registros de la base de datos
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['image']) . "' alt='image' width='100'></td>";
                        echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['landscape']) . "' alt='landscape' width='100'></td>";
                        // Botones de editar y eliminar
                        echo "<td>
                                <a href='?action=edit&id=" . $row['id'] . "'>Editar</a> | 
                                <a href='?action=delete&id=" . $row['id'] . "' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este registro?\")'>Eliminar</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No hay registros disponibles</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="pagination">
            <?php
            if ($page > 1) {
                echo "<a href='channel.php?page=" . ($page - 1) . "&search=" . urlencode($search_query) . "'>&laquo; Anterior</a>";
            }

            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $page) {
                    echo "<span class='current-page'>$i</span>";
                } else {
                    echo "<a href='channel.php?page=$i&search=" . urlencode($search_query) . "'>$i</a>";
                }
            }

            if ($page < $total_pages) {
                echo "<a href='channel.php?page=" . ($page + 1) . "&search=" . urlencode($search_query) . "'>Siguiente &raquo;</a>";
            }
            ?>
        </div>
    </div>

    <script src="../../scripts.js"></script>
</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>