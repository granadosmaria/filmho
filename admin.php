<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dt_live_v3";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    <link rel="icon" href="logo.png" type="image/png">
    <link rel="stylesheet" href="styles.css">
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
                    <li><a href="menu/BasicSettings/types.php"><i class="fas fa-cogs"></i> Types</a></li>
                    <li><a href="menu/BasicSettings/category.php"><i class="fas fa-list"></i> Category</a></li>
                    <li><a href="menu/BasicSettings/avatar.php"><i class="fas fa-user"></i> Avatar</a></li>
                    <li><a href="menu/BasicSettings/languages.php"><i class="fas fa-language"></i> Languages</a></li>
                    <li><a href="menu/BasicSettings/session.php"><i class="fas fa-clock"></i> Session</a></li>
                    <li><a href="menu/BasicSettings/page.php"><i class="fas fa-file"></i> Page</a></li>
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
                    <li><a href="menu/channel/channels.php"><i class="fas fa-video"></i> Channels</a></li>
                    <li><a href="menu/channel/channelBanner.php"><i class="fas fa-image"></i> Channel Banner</a></li>
                    <li><a href="menu/channel/channelSection.php"><i class="fas fa-th-large"></i> Channel Section</a></li>
                </ul>
            </li>

            <!-- Users -->
            <li><a href="menu/users.php"><i class="fas fa-users"></i> Users</a></li>

            <!-- Cast -->
            <li><a href="menu/cast.php"><i class="fas fa-clapperboard"></i> Cast</a></li>

            <!-- Videos -->
            <li><a href="menu/videos.php"><i class="fas fa-video"></i> Videos</a></li>

            <!-- TV Shows -->
            <li><a href="menu/tvShows.php"><i class="fas fa-tv"></i> TV Shows</a></li>

            <!-- Rent -->
            <li class="submenu">
                <a href="#"><i class="fas fa-dollar-sign"></i> Rent</a>
                <ul class="submenu-links">
                    <li><a href="menu/rent/rentVideos.php"><i class="fas fa-video"></i> Rent Videos</a></li>
                    <li><a href="menu/rent/rentTransactions.php"><i class="fas fa-exchange-alt"></i> Rent Transactions</a></li>
                </ul>
            </li>

            <!-- Subscription -->
            <li class="submenu">
                <a href="#"><i class="fas fa-gift"></i> Subscription</a>
                <ul class="submenu-links">
                    <li><a href="menu/subcription/package.php"><i class="fas fa-box"></i> Package</a></li>
                    <li><a href="menu/subcription/transactions.php"><i class="fas fa-exchange-alt"></i> Transactions</a></li>
                    <li><a href="menu/subcription/payment.php"><i class="fas fa-credit-card"></i> Payment</a></li>
                </ul>
            </li>

            <!-- Espacios adicionales al final -->
            <li><a href="#"> </a></li>
            <li><a href="#"> </a></li>
        </ul>
    </div>

<!-- Contenido Principal -->
<div class="main-content">
    <!-- Aquí va el contenido de cada página -->
    <div id="content">
        <br><br><br>
        <!-- Espacio donde se carga el contenido específico, pero aquí ponemos una imagen -->
        <img src="logo.png" style="width: 100%; height: auto;">
    </div>
</div>

    <script src="scripts.js"></script>
</body>

</html>
