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
    ":usuario" => $id_usuario,
    ":modulo" => $id
  ]);

  $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

  $porcentaje = $resultado ? $resultado["porcentaje"] : 0;

  $modulos[] = [
    "id" => $id,
    "titulo" => $titulo,
    "progreso" => $porcentaje
  ];
}

/* ✅ VALIDAR SI COMPLETÓ TODO */
$completo = true;

foreach ($modulos as $m) {
  if ($m['progreso'] < 100) {
    $completo = false;
    break;
  }
}

/* ✅ PROGRESO GLOBAL REAL */
$total = array_sum(array_column($modulos, 'progreso'));
$cantidad = count($modulos);

$progresoGlobal = $cantidad > 0 ? $total / $cantidad : 0;
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>DEBIA Academy</title>

  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/user.css">
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

      <?php foreach ($modulos as $m): ?>
        <div class="card">
          <h3><?php echo $m['titulo']; ?></h3>

          <div class="bar">
            <span style="width:<?php echo $m['progreso']; ?>%"></span>
          </div>

          <a href="modulo.php?id=<?php echo $m['id']; ?>" class="btn">Ver módulo</a>
        </div>
      <?php endforeach; ?>

      <!-- EVALUACION -->
      <div class="card eval">
        <h3>Evaluación Final</h3>
        <p>
          <p class="estado">
            <?php 
            $m = end($modulos);
            if($m['progreso'] == 100){
              echo "✅ Completado";
            } else if($m['progreso'] > 0){
              echo "⏳ En progreso";
            } else {
              echo "📌 No iniciado";
            }
            ?>
                    <p>Complete todos los módulos para acceder a la evaluación.</p>
          </p>

        </p>

        <a href="evaluacion.php"
          class="btn <?php echo !$completo ? 'disabled' : ''; ?>">
          Ir a Evaluación
        </a>
      </div>

    </div>

    <!-- LOGOUT -->
    <a href="../controller/logout.php" class="btn logout">Cerrar Sesión</a>

  </div>

</body>

</html>