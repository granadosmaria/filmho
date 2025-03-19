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
$total_sql = "SELECT COUNT(*) AS total FROM tv_show";
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

// Agregar un nuevo programa de TV
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name']) && isset($_POST['video_type'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $video_type = $conn->real_escape_string($_POST['video_type']);

    // Preparar y ejecutar la consulta para insertar una nueva fila
    $stmt = $conn->prepare("INSERT INTO tv_show (name, video_type) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $video_type);
    $stmt->execute();
    echo "Nuevo programa de TV agregado correctamente.";
}

// Verificar si se ha enviado un término de búsqueda
$search_query = "";
if (isset($_POST['search'])) {
    $search_query = $conn->real_escape_string($_POST['search']);
    $sql = "SELECT id, name, video_type FROM tv_show WHERE name LIKE '%$search_query%' OR video_type LIKE '%$search_query%'";
} else {
    $sql = "SELECT id, name, video_type FROM tv_show LIMIT $offset, $items_per_page";
}

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Páginas</title>
    <link rel="stylesheet" href="../styles.css">
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
            <li><a href="admin.html"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>

            <!-- Basic Settings -->
            <li class="submenu">
                <a href="#"><i class="fas fa-cogs"></i> Basic Settings</a>
                <ul class="submenu-links">
                    <li><a href="BasicSettings/types.php"><i class="fas fa-cogs"></i> Types</a></li>
                    <li><a href="BasicSettings/category.php"><i class="fas fa-list"></i> Category</a></li>
                    <li><a href="BasicSettings/avatar.php"><i class="fas fa-user"></i> Avatar</a></li>
                    <li><a href="BasicSettings/languages.php"><i class="fas fa-language"></i> Languages</a></li>
                    <li><a href="BasicSettings/session.php"><i class="fas fa-clock"></i> Session</a></li>
                    <li><a href="BasicSettings/page.php"><i class="fas fa-file"></i> Page</a></li>
                    <li><a href="BasicSettings/coupons.php"><i class="fas fa-ticket-alt"></i> Coupons</a></li>
                </ul>
            </li>

            <!-- Home -->
            <li class="submenu">
                <a href="#"><i class="fas fa-home"></i> Home</a>
                <ul class="submenu-links">
                    <li><a href="home/banner.php"><i class="fas fa-image"></i> Banner</a></li>
                    <li><a href="home/section.php"><i class="fas fa-th"></i> Section</a></li>
                </ul>
            </li>

            <!-- Channel -->
            <li class="submenu">
                <a href="#"><i class="fas fa-tv"></i> Channel</a>
                <ul class="submenu-links">
                    <li><a href="channel/channels.php"><i class="fas fa-video"></i> Channels</a></li>
                    <li><a href="channel/channelBanner.php"><i class="fas fa-image"></i> Channel Banner</a></li>
                    <li><a href="channel/channelSection.php"><i class="fas fa-th-large"></i> Channel Section</a></li>
                </ul>
            </li>

            <!-- Users -->
            <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>

            <!-- Cast -->
            <li><a href="cast.php"><i class="fas fa-clapperboard"></i> Cast</a></li>

            <!-- Videos -->
            <li><a href="videos.php"><i class="fas fa-video"></i> Videos</a></li>

            <!-- TV Shows -->
            <li><a href="tvShows.php"><i class="fas fa-tv"></i> TV Shows</a></li>

            <!-- Upcoming -->
            <li class="submenu">
                <a href="#"><i class="fas fa-calendar-alt"></i> Upcoming</a>
                <ul class="submenu-links">
                    <li><a href="upcoming/videos.php"><i class="fas fa-video"></i> Videos</a></li>
                    <li><a href="upcoming/tvshows.php"><i class="fas fa-tv"></i> TV Shows</a></li>
                </ul>
            </li>

            <!-- Rent -->
            <li class="submenu">
                <a href="#"><i class="fas fa-dollar-sign"></i> Rent</a>
                <ul class="submenu-links">
                    <li><a href="rent/rentVideos.php"><i class="fas fa-video"></i> Rent Videos</a></li>
                    <li><a href="rent/rentTransactions.php"><i class="fas fa-exchange-alt"></i> Rent Transactions</a></li>
                </ul>
            </li>

            <!-- Subscription -->
            <li class="submenu">
                <a href="#"><i class="fas fa-gift"></i> Subscription</a>
                <ul class="submenu-links">
                    <li><a href=".subcription/package.php"><i class="fas fa-box"></i> Package</a></li>
                    <li><a href="subcription/transactions.php"><i class="fas fa-exchange-alt"></i> Transactions</a></li>
                    <li><a href="subcription/payment.php"><i class="fas fa-credit-card"></i> Payment</a></li>
                </ul>
            </li>

            <!-- Settings -->
            <li><a href="sattings.php"><i class="fas fa-cogs"></i> Settings</a></li>

            <!-- Notifications -->
            <li><a href="notification.php"><i class="fas fa-bell"></i> Notifications</a></li>

            <!-- Espacios adicionales al final -->
            <li><a href="#"> </a></li>
            <li><a href="#"> </a></li>
        </ul>
    </div>
    <div class="main-content">
        <div id="content">
            <h1>Administración de Programas de TV</h1>

            <!-- Formulario para agregar un nuevo programa de TV -->
            <form action="tv_show.php" method="POST">
                <label for="name">Nombre:</label>
                <input type="text" id="name" name="name" required>

                <label for="video_type">Tipo de Video:</label>
                <input type="text" id="video_type" name="video_type" required>

                <button type="submit" class="btn-black">Agregar Programa de TV</button>
            </form>

            <!-- Búsqueda de programas de TV -->
            <form method="POST" action="tv_show.php" class="search-form">
                <input type="text" name="search" placeholder="Buscar programa..." value="<?php echo $search_query; ?>" class="search-input">
                <button type="submit" class="btn-black"><i class="fas fa-search"></i></button>
            </form>

            <!-- Tabla con programas de TV -->
            <table border="1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Tipo de Video</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['video_type'] . "</td>";
                            echo "<td><a href='editTvShow.php?id=" . $row['id'] . "'><i class='fas fa-edit'></i></a> | <a href='deleteTvShow.php?id=" . $row['id'] . "'><i class='fas fa-trash'></i></a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No hay programas de TV disponibles</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Paginación -->
            <div class="pagination">
                <?php
                if ($page > 1) {
                    echo "<a href='tv_show.php?page=" . ($page - 1) . "'>&laquo; Anterior</a>";
                }

                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i == $page) {
                        echo "<span class='current-page'>$i</span>";
                    } else {
                        echo "<a href='tv_show.php?page=$i'>$i</a>";
                    }
                }

                if ($page < $total_pages) {
                    echo "<a href='tv_show.php?page=" . ($page + 1) . "'>Siguiente &raquo;</a>";
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