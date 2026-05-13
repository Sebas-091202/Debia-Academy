<?php
session_start();
require_once("../bd/conn.php");

/* VERIFICAR SESIÓN */
if(!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "ADMIN"){
    header("Location: index_Usuario.php");
    exit;
}

// Evitar cache para que no se pueda volver atrás después de cerrar sesión
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

/* OBTENER MODULOS */
$sql = "SELECT * FROM modulos ORDER BY id DESC";
$modulos = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Admin</title>

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css/admin_Modulos.css">
</head>

<body>

<div class="dashboard">

<!-- HEADER -->
<div class="header">
    <div class="logo"> ADMINISTRACIÓN</div>

    <div class="menu" onclick="toggleMenu()">
        ⚙️
        <div class="dropdown" id="menu">
            <a href="#">Configuración</a>
            <a href="../controller/logout.php">Cerrar sesión</a>
        </div>
    </div>
</div>

<h2>Panel de Gestión de Módulos</h2>

<div class="modules">

<!-- CREAR MODULO -->
<div class="card">

<h3>Crear Módulo</h3>

<form action="../controller/crear_modulo.php" method="POST">

<input type="text" name="titulo" placeholder="Título módulo" required>

<select name="area" required>
<option value="">Área</option>
<option>Investigaciones Privadas</option>
<option>Visitas Domiciliarias</option>
<option>Poligrafía</option>
<option>Validación Académica</option>
<option>Asesoría Jurídica</option>
</select>

<button class="btn">Crear módulo</button>

</form>

</div>

<!-- SUBIR CONTENIDO -->
<div class="card">

<h3>Subir Contenido</h3>

<form action="../controller/subir_archivo.php" method="POST" enctype="multipart/form-data">

<select name="id_modulo" required>
<option value="">Seleccionar módulo</option>

<?php foreach($modulos as $m): ?>
<option value="<?= $m["id"] ?>">
<?= $m["titulo"] ?> (<?= $m["area"] ?>)
</option>
<?php endforeach; ?>

</select>

<select name="tipo" required>
<option value="texto">Texto</option>
<option value="video">Video (YouTube)</option>
<option value="archivo">Archivo (PDF / DOC)</option>
</select>

<textarea name="contenido" placeholder="Texto o link video"></textarea>

<input type="file" name="archivo">

<button class="btn">Subir contenido</button>

</form>

</div>

<!-- LISTA MODULOS -->
<div class="card">

<h3>📚 Módulos creados</h3>

<?php foreach($modulos as $m): ?>
<p>• <?= $m["titulo"] ?> (<?= $m["area"] ?>)</p>
<?php endforeach; ?>

</div>

</div>

</div>

<script>

/* DROPDOWN */
function toggleMenu(){
    let m = document.getElementById("menu");
    m.style.display = m.style.display === "block" ? "none" : "block";
}

</script>

</body>
</html>
