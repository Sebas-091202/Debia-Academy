<?php
session_start();
require_once("../bd/conn.php");

$id_usuario = $_SESSION["id"];
$usuario = $_SESSION["usuario"];
$area = $_SESSION["area"];

/* ✅ LISTA DE MODULOS BASE */
$modulos_base = [
  1 => "Introducción al Área",
  2 => "Procedimientos Clave",
  3 => "Normativa y Ética"
];

$modulos = [];

/* ✅ CONSULTAR PROGRESO DE CADA MODULO */
foreach ($modulos_base as $id => $titulo) {

    $sql = "SELECT porcentaje 
            FROM progreso 
            WHERE id_usuario = :usuario AND id_modulo = :modulo";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ":usuario"=>$id_usuario,
        ":modulo"=>$id
    ]);

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    $porcentaje = $resultado ? $resultado["porcentaje"] : 0;

    $modulos[] = [
        "id"=>$id,
        "titulo"=>$titulo,
        "progreso"=>$porcentaje
    ];
}

/* ✅ VALIDAR SI COMPLETÓ TODO */
$completo = true;

foreach($modulos as $m){
    if($m['progreso'] < 100){
        $completo = false;
        break;
    }
}

/* ✅ PROGRESO GLOBAL REAL */
$total = array_sum(array_column($modulos,'progreso'));
$cantidad = count($modulos);

$progresoGlobal = $cantidad > 0 ? $total / $cantidad : 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>DEBIA Academy</title>

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">

<style>

* {
  box-sizing: border-box;
}

body {
  margin: 0;
  font-family: 'Montserrat', sans-serif;
  height: 100vh;

  background: linear-gradient(135deg, #c40c0c, #111827, #ffffff);
  background-size: 600% 600%;
  animation: gradientAnimation 22s ease infinite;

  display: flex;
  justify-content: center;
  align-items: center;
}

@keyframes gradientAnimation {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

.dashboard {
  width: 95%;
  max-width: 1100px;
  background: rgba(255,255,255,0.15);
  backdrop-filter: blur(20px);
  border-radius: 20px;
  padding: 40px;
  box-shadow: 0 15px 30px rgba(0,0,0,.5);
  color: #fff;
}

/* HEADER */
.header {
  text-align: center;
  margin-bottom: 30px;
}

.header img {
  height: 100px;
  border-radius: 50%;
  box-shadow: 0 6px 15px rgba(0,0,0,.4);
}

.header h1 {
  margin-top: 10px;
  font-size: 24px;
}

/* PROGRESO GLOBAL */
.progress-global {
  margin-bottom: 30px;
}

.progress-bar {
  height: 10px;
  background: rgba(255,255,255,0.2);
  border-radius: 10px;
  overflow: hidden;
}

.progress-bar span {
  display: block;
  height: 100%;
  background: #60a5fa;
}

/* GRID DE MODULOS */
.modules {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px,1fr));
  gap: 20px;
}

/* CARD */
.card {
  background: rgba(255,255,255,0.08);
  border-radius: 16px;
  padding: 20px;
  transition: .5s;
  box-shadow: 0 8px 20px rgba(0,0,0,.2);
}

.card:hover {
  transform: translateY(-7px);
  background-color: rgba(0,119,182,0.9);
}

/* TITULO */
.card h3 {
  font-size: 16px;
}

/* BARRA MODULO */
.bar {
  height: 8px;
  background: rgba(255,255,255,0.3);
  border-radius: 8px;
  overflow: hidden;
  margin: 10px 0;
}

.bar span {
  display: block;
  height: 100%;
  background: #22c55e;
}

/* BOTON */
.btn {
  display: inline-block;
  margin-top: 10px;
  padding: 8px 14px;
  border-radius: 10px;
  background: #2563eb;
  color: white;
  text-decoration: none;
  font-size: 14px;
  box-shadow: 0 2px 5px rgba(0, 0, .2, .4);
}

.btn:hover {
  background-color: #21344A;
}

.btn.disabled {
  background: gray;
  pointer-events: none;
}

/* EVALUACION */
.eval {
  border: 2px dashed rgba(255,255,255,0.4);
  text-align: center;
}

/* LOGOUT */
.logout {
  margin-top: 20px;
  display: inline-block;
  background: #dc2626;
}

.logout:hover {
  background: #991b1b;
}

/* RESPONSIVE */
@media(max-width:600px){
  .dashboard { padding: 20px; }
}

</style>
</head>

<body>

<div class="dashboard">

  <div class="header">
    <img src="../img/debia-academy-blanco.png">
    <h1><?php echo $usuario; ?> - Área: <?php echo $area; ?></h1>
  </div>

  <!-- PROGRESO GLOBAL -->
  <div class="progress-global">
      <p>Progreso general</p>
      <div class="progress-bar">
        <span style="width: <?php echo $progresoGlobal; ?>%"></span>
      </div>
  </div>

  <!-- MODULOS -->
  <div class="modules">

    <?php foreach($modulos as $m): ?>
    <div class="card">
      <h3><?php echo $m['titulo']; ?></h3>

      <div class="bar">
        <span style="width:<?php echo $m['progreso'];?>%"></span>
      </div>

      <a href="modulo.php?id=<?php echo $m['id'];?>" class="btn">Ver módulo</a>
    </div>
    <?php endforeach; ?>

    <!-- EVALUACION -->
    <div class="card eval">
      <h3>Evaluación Final</h3>
      <p>
        <?php
          echo $completo
          ? "✅ Puedes presentar la evaluación"
          : "🔒 Completa todos los módulos";
        ?>
      </p>

      <a href="evaluacion.php"
         class="btn <?php echo !$completo ? 'disabled':''; ?>">
         Ir a Evaluación
      </a>
    </div>

  </div>

  <!-- LOGOUT -->
  <a href="../controller/logout.php" class="btn logout">Cerrar Sesión</a>

</div>

</body>
</html>