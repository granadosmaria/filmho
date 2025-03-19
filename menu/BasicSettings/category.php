<?php
// Conexión a la base de datos
$servername = "localhost"; // Cambia esto si tu servidor de base de datos no está en localhost
$username = "root"; // Tu usuario de base de datos
$password = ""; // Tu contraseña de base de datos
$dbname = "dt_live_v3"; // Nombre de la base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
// Establecer el número de elementos por página
$items_per_page = 10;

// Obtener el número total de registros
$total_sql = "SELECT COUNT(*) AS total FROM category";
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


// Agregar una nueva categoría si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name']) && isset($_POST['image']) && isset($_POST['action']) ) {
    $name = $_POST['name'];
    $image = $_POST['image'];
    $action = $_POST['action'];

    // Preparar y ejecutar la consulta para insertar la nueva categoría
    $sql = "INSERT INTO category (name, image, action]
    ) VALUES ('$name', '$image', '$action')";

    if ($conn->query($sql) === TRUE) {
        echo "Nueva categoría agregada correctamente.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Verificar si se ha enviado un término de búsqueda
$search_query = "";
if (isset($_POST['search'])) {
    $search_query = $_POST['search'];
    $sql = "SELECT id, name, image FROM category WHERE name LIKE '%$search_query%' OR action LIKE '%$search_query%'";
} else {
    $sql = "SELECT id, name, image  FROM category";
}

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    <link rel="icon" href="../../logo.png" type="logo.png">
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
            <li><a href="../../admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>

            <!-- Basic Settings -->
            <li class="submenu">
                <a href="#"><i class="fas fa-cogs"></i> Basic Settings</a>
                <ul class="submenu-links">
                    <li><a href="types.php"><i class="fas fa-cogs"></i> Types</a></li>
                    <li><a href="#"><i class="fas fa-list"></i> Category</a></li>
                    <li><a href="avatar.php"><i class="fas fa-user"></i> Avatar</a></li>
                    <li><a href="languages.php"><i class="fas fa-language"></i> Languages</a></li>
                    <li><a href="session.php"><i class="fas fa-clock"></i> Session</a></li>
                    <li><a href="page.php"><i class="fas fa-file"></i> Page</a></li>
                </ul>
            </li>

            <!-- Home -->
            <li class="submenu">
                <a href="#"><i class="fas fa-home"></i> Home</a>
                <ul class="submenu-links">
                    <li><a href="menu/home/banner.php"><i class="fas fa-image"></i> Banner</a></li>
                    <li><a href="menu/home/section.php"><i class="fas fa-th"></i> Section</a></li>
                </ul>
            </li>

            <!-- Channel -->
            <li class="submenu">
                <a href="#"><i class="fas fa-tv"></i> Channel</a>
                <ul class="submenu-links">
                    <li><a href="/channel/channels.php"><i class="fas fa-video"></i> Channels</a></li>
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
                    <li><a href="/rent/rentVideos.php"><i class="fas fa-video"></i> Rent Videos</a></li>
                    <li><a href="rent/rentTransactions.php"><i class="fas fa-exchange-alt"></i> Rent Transactions</a></li>
                </ul>
            </li>

            <!-- Subscription -->
            <li class="submenu">
                <a href="#"><i class="fas fa-gift"></i> Subscription</a>
                <ul class="submenu-links">
                    <li><a href="subcription/package.php"><i class="fas fa-box"></i> Package</a></li>
                    <li><a href="subcription/transactions.php"><i class="fas fa-exchange-alt"></i> Transactions</a></li>
                    <li><a href="subcription/payment.php"><i class="fas fa-credit-card"></i> Payment</a></li>
                </ul>
            </li>

            <li><a href="#"> </a></li>
            <li><a href="#"> </a></li>
        </ul>
    </div>

<!-- Contenido Principal -->
<div class="main-content">
    <div id="content">
        <h1>Category</h1>
            <!-- Formulario para agregar nuevos tipos -->
        <form action="category.php" method="POST">
            <label for="name">Name:</label>
            <input type="img" id="name" name="name" required>
            <label for="life">image:</label>
            <input type="life" id="type" name="image" required>
            <button type="submit" class="btn-black">Add category</button>
        </form>
        <!-- Barra de búsqueda -->
        <form method="POST" action="types.php" class="search-form">
            <input type="text" name="search" placeholder="Buscar categoria..." value="<?php echo $search_query; ?>" class="search-input">
            <button type="submit" class="btn-black"><i class="fas fa-search"></i></button>
        </form>
            <!-- Tabla de Categorías -->
            <table border="1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Mostrar las categorías desde la base de datos
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['category']) . "' alt='category' width='100'></td>";
                            echo "<td>" . $row['action'] . "</td>";
                            // Usando íconos para editar y eliminar
                            echo "<td><a href='editcate.php?id=" . $row['id'] . "'><i class='fas fa-edit'></i></a> | <a href='delete.php?id=" . $row['id'] . "'><i class='fas fa-trash'></i></a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No hay categorías disponibles</td></tr>";
                    }
                    ?>
            </tbody>
        </table>
        <!-- Paginación -->
        <div class="pagination">
                <?php
                if ($page > 1) {
                    echo "<a href='category.php?page=" . ($page - 1) . "'>&laquo; Anterior</a>";
                }

                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i == $page) {
                        echo "<span class='current-page'>$i</span>";
                    } else {
                        echo "<a href='category.php?page=$i'>$i</a>";
                    }
                }

                if ($page < $total_pages) {
                    echo "<a href='category.php?page=" . ($page + 1) . "'>Siguiente &raquo;</a>";
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
