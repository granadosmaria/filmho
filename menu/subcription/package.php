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

// Establecer el número de elementos por página
$items_per_page = 10;

// Obtener el número total de registros
$total_sql = "SELECT COUNT(*) AS total FROM package";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_items = $total_row['total'];

// Calcular el número total de páginas
$total_pages = ceil($total_items / $items_per_page);

// Obtener la página actual desde la URL
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1; 
if ($page > $total_pages) $page = $total_pages; 

// Calcular el desplazamiento para la consulta SQL
$offset = ($page - 1) * $items_per_page;
if ($offset < 0) $offset = 0; 

// Agregar una nueva fila o editar si se pasa un ID
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['name'], $_POST['price'], $_POST['type_id'], $_POST['watch_on_laptop_tv'], $_POST['ads_free_movies_shows'], $_POST['no_of_device'], $_POST['video_quality'], $_POST['time'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $price = (float)$_POST['price'];
        $type_id = (int)$_POST['type_id'];
        $watch_on_laptop_tv = $conn->real_escape_string($_POST['watch_on_laptop_tv']);
        $ads_free_movies_shows = $conn->real_escape_string($_POST['ads_free_movies_shows']);
        $no_of_device = (int)$_POST['no_of_device'];
        $video_quality = $conn->real_escape_string($_POST['video_quality']);
        $time = $conn->real_escape_string($_POST['time']);

        if (isset($_POST['edit_id']) && $_POST['edit_id'] != '') {
            // Editar un registro existente
            $edit_id = (int)$_POST['edit_id'];
            $sql_update = "UPDATE package SET name='$name', price=$price, type_id=$type_id, watch_on_laptop_tv='$watch_on_laptop_tv', ads_free_movies_shows='$ads_free_movies_shows', no_of_device=$no_of_device, video_quality='$video_quality', time='$time' WHERE id=$edit_id";
            if ($conn->query($sql_update) === TRUE) {
                echo "Registro editado correctamente.";
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            // Insertar un nuevo registro
            $sql_insert = "INSERT INTO package (name, price, type_id, watch_on_laptop_tv, ads_free_movies_shows, no_of_device, video_quality, time) 
                           VALUES ('$name', $price, $type_id, '$watch_on_laptop_tv', '$ads_free_movies_shows', $no_of_device, '$video_quality', '$time')";
            if ($conn->query($sql_insert) === TRUE) {
                echo "Nuevo paquete agregado correctamente.";
            } else {
                echo "Error: " . $conn->error;
            }
        }
    }
}

// Lógica de eliminación de un registro
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $sql_delete = "DELETE FROM package WHERE id = $delete_id";
    if ($conn->query($sql_delete) === TRUE) {
        echo "Registro eliminado correctamente.";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Lógica de búsqueda
$search_query = '';
if (isset($_POST['search']) && !empty($_POST['search'])) {
    $search_query = $_POST['search'];
    // Consulta SQL con búsqueda
    $sql_packages = "SELECT * FROM package WHERE name LIKE ? OR video_quality LIKE ? LIMIT ?, ?";
} else {
    // Si no hay búsqueda, solo mostramos todos los paquetes
    $sql_packages = "SELECT * FROM package LIMIT ?, ?";
}

// Preparar la consulta de búsqueda
$stmt = $conn->prepare($sql_packages);
if ($search_query) {
    $search_term = "%$search_query%";
    $stmt->bind_param("ssii", $search_term, $search_term, $offset, $items_per_page);
} else {
    $stmt->bind_param("ii", $offset, $items_per_page);
}
$stmt->execute();
$result_packages = $stmt->get_result();
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
                    <li><a href="../home/banner.php"><i class="fas fa-image"></i> Banner</a></li>
                    <li><a href="../home/section.php"><i class="fas fa-th"></i> Section</a></li>
                </ul>
            </li>

            <!-- Channel -->
            <li class="submenu">
                <a href="#"><i class="fas fa-tv"></i> Channel</a>
                <ul class="submenu-links">
                    <li><a href="../channel/channels.php"><i class="fas fa-video"></i> Channels</a></li>
                    <li><a href="../channel/channelBanner.php"><i class="fas fa-image"></i> Channel Banner</a></li>
                    <li><a href="../channel/channelSection.php"><i class="fas fa-th-large"></i> Channel Section</a></li>
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

            <!-- Upcoming -->
            <li class="submenu">
                <a href="#"><i class="fas fa-calendar-alt"></i> Upcoming</a>
                <ul class="submenu-links">
                    <li><a href="../upcoming/videos.php"><i class="fas fa-video"></i> Videos</a></li>
                    <li><a href="../upcoming/tvshows.php"><i class="fas fa-tv"></i> TV Shows</a></li>
                </ul>
            </li>

            <!-- Rent -->
            <li class="submenu">
                <a href="#"><i class="fas fa-dollar-sign"></i> Rent</a>
                <ul class="submenu-links">
                    <li><a href="../rent/rentVideos.php"><i class="fas fa-video"></i> Rent Videos</a></li>
                    <li><a href="../rent/rentTransactions.php><i class="fas fa-exchange-alt"></i> Rent Transactions</a></li>
                </ul>
            </li>

            <!-- Subscription -->
            <li class="submenu">
                <a href="#"><i class="fas fa-gift"></i> Subscription</a>
                <ul class="submenu-links">
                    <li><a href="package.php"><i class="fas fa-box"></i> Package</a></li>
                    <li><a href="transactions.php"><i class="fas fa-exchange-alt"></i> Transactions</a></li>
                    <li><a href="payment.php"><i class="fas fa-credit-card"></i> Payment</a></li>
                </ul>
            </li>
            <!-- Espacios adicionales al final -->
            <li><a href="#"> </a></li>
            <li><a href="#"> </a></li>
        </ul>
    </div>
    <!-- Contenido Principal -->
    <div class="main-content">
        <div id="content">
<!-- Formulario de agregar/editar paquete -->
<h1>Administración de Paquetes</h1>
    <form action="package.php" method="POST">
        <label for="name">Nombre:</label>
        <input type="text" id="name" name="name" required>

        <label for="price">Precio:</label>
        <input type="number" id="price" name="price" step="0.01" required>

        <label for="type_id">ID de Tipo:</label>
        <input type="number" id="type_id" name="type_id" required>

        <label for="watch_on_laptop_tv">Ver en Laptop/TV:</label>
        <input type="text" id="watch_on_laptop_tv" name="watch_on_laptop_tv" required>

        <label for="ads_free_movies_shows">Películas/Shows sin anuncios:</label>
        <input type="text" id="ads_free_movies_shows" name="ads_free_movies_shows" required>

        <label for="no_of_device">Número de Dispositivos:</label>
        <input type="number" id="no_of_device" name="no_of_device" required>

        <label for="video_quality">Calidad de Video:</label>
        <input type="text" id="video_quality" name="video_quality" required>

        <label for="time">Tiempo (en días):</label>
        <input type="number" id="time" name="time" required>

        <input type="hidden" name="edit_id" value="">
        <button type="submit" class="btn-black">Guardar Paquete</button>
    </form>

    <!-- Buscador -->
    <form method="POST" action="package.php" class="search-form">
        <input type="text" name="search" placeholder="Buscar por nombre o calidad de video" value="<?php echo htmlspecialchars($search_query); ?>" required>
        <button type="submit" class="btn-black"><i class="fas fa-search"></i></button>
    </form>

    <!-- Tabla de paquetes -->
    <table border="1">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Tipo</th>
                <th>Ver en Laptop/TV</th>
                <th>Películas/Shows sin anuncios</th>
                <th>Número de Dispositivos</th>
                <th>Calidad de Video</th>
                <th>Tiempo (días)</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_packages->num_rows > 0) {
                while ($row = $result_packages->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['price'] . "</td>";
                    echo "<td>" . $row['type_id'] . "</td>";
                    echo "<td>" . $row['watch_on_laptop_tv'] . "</td>";
                    echo "<td>" . $row['ads_free_movies_shows'] . "</td>";
                    echo "<td>" . $row['no_of_device'] . "</td>";
                    echo "<td>" . $row['video_quality'] . "</td>";
                    echo "<td>" . $row['time'] . "</td>";
                    echo "<td>
                            <a href='package.php?edit_id=" . $row['id'] . "'>Editar</a> |
                            <a href='package.php?delete_id=" . $row['id'] . "'>Eliminar</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='10'>No hay paquetes disponibles</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="pagination">
        <?php
        if ($page > 1) {
            echo "<a href='package.php?page=" . ($page - 1) . "'>&laquo; Anterior</a>";
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                echo "<span class='current-page'>$i</span>";
            } else {
                echo "<a href='package.php?page=$i'>$i</a>";
            }
        }

        if ($page < $total_pages) {
            echo "<a href='package.php?page=" . ($page + 1) . "'>Siguiente &raquo;</a>";
        }
        ?>
    </div>
</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>