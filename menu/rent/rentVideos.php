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
$total_sql = "SELECT COUNT(*) AS total FROM rent_video";
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

// Agregar un nuevo video de alquiler
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['video_id']) && isset($_POST['price']) && isset($_POST['type_id']) && isset($_POST['type']) && isset($_POST['time'])) {
    $video_id = $conn->real_escape_string($_POST['video_id']);
    $price = $conn->real_escape_string($_POST['price']);
    $type_id = $conn->real_escape_string($_POST['type_id']);
    $type = $conn->real_escape_string($_POST['type']);
    $time = $conn->real_escape_string($_POST['time']);

    // Preparar y ejecutar la consulta para insertar un nuevo alquiler de video
    $stmt = $conn->prepare("INSERT INTO rent_video (video_id, price, type_id, type, time) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $video_id, $price, $type_id, $type, $time);
    $stmt->execute();
    echo "Nuevo video de alquiler agregado correctamente.";
}

// Verificar si se ha enviado un término de búsqueda
$search_query = "";
if (isset($_POST['search'])) {
    $search_query = $conn->real_escape_string($_POST['search']);
    $sql = "SELECT id, video_id, price, type_id, type, time FROM rent_video WHERE video_id LIKE '%$search_query%' OR price LIKE '%$search_query%' OR type LIKE '%$search_query%'";
} else {
    $sql = "SELECT id, video_id, price, type_id, type, time FROM rent_video LIMIT $offset, $items_per_page";
}

$result = $conn->query($sql);
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
                    <li><a href="../BasicSettings/coupons.php"><i class="fas fa-ticket-alt"></i> Coupons</a></li>
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
            <!-- Rent -->
            <li class="submenu">
                <a href="#"><i class="fas fa-dollar-sign"></i> Rent</a>
                <ul class="submenu-links">
                    <li><a href="rentVideos.php"><i class="fas fa-video"></i> Rent Videos</a></li>
                    <li><a href="rentTransactions.php"><i class="fas fa-exchange-alt"></i> Rent Transactions</a></li>
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
        <div id="content">
            <h1>Administración de Videos de Alquiler</h1>

            <!-- Formulario para agregar un nuevo video de alquiler -->
            <form action="rent_video.php" method="POST">
                <label for="video_id">ID del Video:</label>
                <input type="text" id="video_id" name="video_id" required>

                <label for="price">Precio:</label>
                <input type="number" id="price" name="price" required>

                <label for="type_id">ID de Tipo:</label>
                <input type="text" id="type_id" name="type_id" required>

                <label for="type">Tipo de Video:</label>
                <input type="text" id="type" name="type" required>

                <label for="time">Duración del Alquiler:</label>
                <input type="text" id="time" name="time" required>

                <button type="submit" class="btn-black">Agregar Video de Alquiler</button>
            </form>

            <!-- Búsqueda de videos de alquiler -->
            <form method="POST" action="rent_video.php" class="search-form">
                <input type="text" name="search" placeholder="Buscar video..." value="<?php echo $search_query; ?>" class="search-input">
                <button type="submit" class="btn-black"><i class="fas fa-search"></i></button>
            </form>

            <!-- Tabla con videos de alquiler -->
            <table border="1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID Video</th>
                        <th>Precio</th>
                        <th>ID Tipo</th>
                        <th>Tipo</th>
                        <th>Tiempo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['video_id'] . "</td>";
                            echo "<td>" . $row['price'] . "</td>";
                            echo "<td>" . $row['type_id'] . "</td>";
                            echo "<td>" . $row['type'] . "</td>";
                            echo "<td>" . $row['time'] . "</td>";
                            echo "<td><a href='editRentVideo.php?id=" . $row['id'] . "'><i class='fas fa-edit'></i></a> | <a href='deleteRentVideo.php?id=" . $row['id'] . "'><i class='fas fa-trash'></i></a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No hay videos de alquiler disponibles</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Paginación -->
            <div class="pagination">
                <?php
                if ($page > 1) {
                    echo "<a href='rent_video.php?page=" . ($page - 1) . "'>&laquo; Anterior</a>";
                }

                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i == $page) {
                        echo "<span class='current-page'>$i</span>";
                    } else {
                        echo "<a href='rent_video.php?page=$i'>$i</a>";
                    }
                }

                if ($page < $total_pages) {
                    echo "<a href='rent_video.php?page=" . ($page + 1) . "'>Siguiente &raquo;</a>";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>