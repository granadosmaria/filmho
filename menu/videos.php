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
$total_sql = "SELECT COUNT(*) AS total FROM video";
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

// Agregar un nuevo video
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['video_type']) && isset($_POST['is_title']) && isset($_POST['category_id'])) {
    $video_type = $conn->real_escape_string($_POST['video_type']);
    $is_title = $_POST['is_title'] == 'on' ? 1 : 0; // Convertir a valor booleano
    $category_id = (int)$_POST['category_id'];

    // Preparar y ejecutar la consulta para insertar un nuevo video
    $sql = "INSERT INTO video (video_type, is_title, category_id) 
            VALUES ('$video_type', $is_title, $category_id)";

    if ($conn->query($sql) === TRUE) {
        echo "Nuevo video agregado correctamente.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Eliminar un video
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $sql_delete = "DELETE FROM video WHERE id = $delete_id";
    if ($conn->query($sql_delete) === TRUE) {
        echo "Video eliminado correctamente.";
    } else {
        echo "Error al eliminar el video: " . $conn->error;
    }
}

// Agregar lógica de búsqueda
$search_query = '';
if (isset($_POST['search']) && !empty($_POST['search'])) {
    $search_query = $_POST['search'];
    $sql_videos = "SELECT v.id, v.video_type, v.is_title, c.name AS category_name 
                   FROM video v 
                   LEFT JOIN category c ON v.category_id = c.id
                   WHERE v.video_type LIKE ? OR c.name LIKE ? 
                   LIMIT ?, ?";
} else {
    $sql_videos = "SELECT v.id, v.video_type, v.is_title, c.name AS category_name 
                   FROM video v 
                   LEFT JOIN category c ON v.category_id = c.id
                   LIMIT ?, ?";
}

// Preparar la consulta de búsqueda
$stmt = $conn->prepare($sql_videos);
if ($search_query) {
    $search_term = "%$search_query%";
    $stmt->bind_param("ssii", $search_term, $search_term, $offset, $items_per_page);
} else {
    $stmt->bind_param("ii", $offset, $items_per_page);
}
$stmt->execute();
$result_videos = $stmt->get_result();

// Obtener todas las categorías para el formulario
$sql_categories = "SELECT id, name FROM category";
$result_categories = $conn->query($sql_categories);
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
            <li><a href="admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>

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

            <!-- Espacios adicionales al final -->
            <li><a href="#"> </a></li>
            <li><a href="#"> </a></li>
        </ul>
    </div>
    <div class="main-content">
        <div id="content">
            <h1>Administración de Videos</h1>

            <!-- Formulario de agregar video -->
            <form action="video.php" method="POST">
                <label for="video_type">Tipo de Video:</label>
                <input type="text" id="video_type" name="video_type" required>

                <label for="is_title">Título</label>
                <input type="text" id="is_title" name="is_title">

                <label for="category_id">Categoría:</label>
                <select name="category_id" id="category_id" required>
                    <?php
                    if ($result_categories->num_rows > 0) {
                        while ($row = $result_categories->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                        }
                    }
                    ?>
                </select>

                <button type="submit" class="btn-black">  Agregar video</button>
            </form>

            <!-- Buscador -->
            <form method="POST" action="video.php" class="search-form">
                <input type="text" name="search" placeholder="Buscar por tipo o categoría" value="<?php echo htmlspecialchars($search_query); ?>" required>
                <button type="submit" class="btn-black"><i class="fas fa-search"></i> </button>
            </form>

            <!-- Tabla de videos -->
            <h2>Videos</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tipo de Video</th>
                        <th>Título Destacado</th>
                        <th>Categoría</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result_videos->num_rows > 0) {
                        while ($row = $result_videos->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['video_type'] . "</td>";
                            echo "<td>" . ($row['is_title'] ? "Sí" : "No") . "</td>";
                            echo "<td>" . $row['category_name'] . "</td>";
                            echo "<td>
                                    <a href='editVideo.php?id=" . $row['id'] . "'><i class='fas fa-edit'></i> Editar</a> | 
                                    <a href='video.php?delete_id=" . $row['id'] . "' onclick='return confirm(\"¿Estás seguro de que quieres eliminar este video?\");'><i class='fas fa-trash'></i> Eliminar</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No hay videos disponibles</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Paginación -->
            <div class="pagination">
                <?php
                if ($page > 1) {
                    echo "<a href='video.php?page=" . ($page - 1) . "'>&laquo; Anterior</a>";
                }

                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i == $page) {
                        echo "<span class='current-page'>$i</span>";
                    } else {
                        echo "<a href='video.php?page=$i'>$i</a>";
                    }
                }

                if ($page < $total_pages) {
                    echo "<a href='video.php?page=" . ($page + 1) . "'>Siguiente &raquo;</a>";
                }
                ?>
            </div>
        </div>
    </div>

    <script src="../scripts.js"></script>
</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
