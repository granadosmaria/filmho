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
$total_sql = "SELECT COUNT(*) AS total FROM channel_section";
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

// Agregar una nueva fila a channel_section si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title']) && isset($_POST['channel_id']) && isset($_POST['video_type']) && isset($_POST['type_id']) && isset($_POST['screen_layout'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $channel_id = (int)$_POST['channel_id'];
    $video_type = $conn->real_escape_string($_POST['video_type']);
    $type_id = (int)$_POST['type_id'];
    $screen_layout = $conn->real_escape_string($_POST['screen_layout']);

    // Preparar y ejecutar la consulta para insertar una nueva fila
    $sql = "INSERT INTO channel_section (title, channel_id, video_type, type_id, screen_layout) 
            VALUES ('$title', $channel_id, '$video_type', $type_id, '$screen_layout')";

    if ($conn->query($sql) === TRUE) {
        echo "Nueva sección agregada correctamente.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Verificar si se ha enviado un término de búsqueda
$search_query = "";
if (isset($_POST['search'])) {
    $search_query = $conn->real_escape_string($_POST['search']);
    $sql = "SELECT id, title, channel_id, video_type, type_id, screen_layout FROM channel_section WHERE title LIKE '%$search_query%' OR video_type LIKE '%$search_query%'";
} else {
    $sql = "SELECT id, title, channel_id, video_type, type_id, screen_layout FROM channel_section LIMIT $offset, $items_per_page";
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
                    <li><a href="channels.php"><i class="fas fa-video"></i> Channels</a></li>
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

            </li>

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


            <li><a href="#"> </a></li>
            <li><a href="#"> </a></li>
        </ul>
    </div>
    <div class="main-content">
        <div id="content">
            <h1>Secciones del Canal</h1>

            <form action="channelSection.php" method="POST">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>

                <label for="channel_id">Channel ID:</label>
                <input type="number" id="channel_id" name="channel_id" required>

                <label for="video_type">Video Type:</label>
                <input type="text" id="video_type" name="video_type" required>

                <label for="type_id">Type ID:</label>
                <input type="number" id="type_id" name="type_id" required>

                <label for="screen_layout">Screen Layout:</label>
                <input type="text" id="screen_layout" name="screen_layout" required>

                <button type="submit" class="btn-black">Add Section</button>
            </form>

            <form method="POST" action="channelSection.php" class="search-form">
                <input type="text" name="search" placeholder="Buscar sección..." value="<?php echo $search_query; ?>" class="search-input">
                <button type="submit" class="btn-black"><i class="fas fa-search"></i></button>
            </form>

            <table border="1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Channel ID</th>
                        <th>Video Type</th>
                        <th>Type ID</th>
                        <th>Screen Layout</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['title'] . "</td>";
                            echo "<td>" . $row['channel_id'] . "</td>";
                            echo "<td>" . $row['video_type'] . "</td>";
                            echo "<td>" . $row['type_id'] . "</td>";
                            echo "<td>" . $row['screen_layout'] . "</td>";
                            echo "<td><a href='editSection.php?id=" . $row['id'] . "'><i class='fas fa-edit'></i></a> | <a href='deleteSection.php?id=" . $row['id'] . "'><i class='fas fa-trash'></i></a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No hay secciones disponibles</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <div class="pagination">
                <?php
                if ($page > 1) {
                    echo "<a href='channelSection.php?page=" . ($page - 1) . "'>&laquo; Anterior</a>";
                }

                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i == $page) {
                        echo "<span class='current-page'>$i</span>";
                    } else {
                        echo "<a href='channelSection.php?page=$i'>$i</a>";
                    }
                }

                if ($page < $total_pages) {
                    echo "<a href='channelSection.php?page=" . ($page + 1) . "'>Siguiente &raquo;</a>";
                }
                ?>
            </div>
        </div>
    </div>

    <script src="../../scripts.js"></script>
</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>