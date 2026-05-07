<?php
/* Necesito que las vistas, muestren el contenido del módulo seleccionado, segun los documentos. ademas que tenga una navegacion dinamica 
de la informacion que se encuentra, que si no le da a marcar como leido, no deje avanzar en el proceso, ademas que el boton de mmodulo
completado este bloqueadon hasta que se marque como leido todo el modulo.*/
session_start();
require_once("../bd/conn.php");

if (!isset($_SESSION["usuario"])) {
    header("Location: index_User.php");
    exit;
}

$id_usuario = $_SESSION["id"];

/* ✅ ID DINÁMICO */
$id_modulo = $_GET["id"] ?? 1;

/* ✅ CONTENIDO TEXTUAL DIRECTO */
switch($id_modulo){

    case 1:
        $titulo = "Introducción al Área";
        $contenido = "
        Bienvenido a Consultorías Debia.

        Este módulo tiene como objetivo brindarte una visión general de:

        - Procesos de validación
        - Importancia de la seguridad en la información
        - Procedimientos iniciales de verificación

        Es importante comprender estos conceptos antes de avanzar.
        ";
    break;

    case 2:
        $titulo = "Procedimientos Clave";
        $contenido = "
        En este módulo aprenderás los procesos fundamentales:

        - Validación de antecedentes laborales
        - Recolección de información del evaluado
        - Verificación de referencias

        Estos pasos deben cumplirse de forma estructurada.
        ";
    break;

    case 3:
        $titulo = "Normativa y Ética";
        $contenido = "
        Este módulo aborda aspectos legales:

        - Protección de datos personales
        - Confidencialidad de la información
        - Responsabilidad profesional

        El incumplimiento de estas normas puede generar consecuencias legales.
        ";
    break;

    default:
        $titulo = "Módulo no encontrado";
        $contenido = "No existe el contenido.";
}

/* ✅ PROGRESO REAL */
$sql = "SELECT porcentaje FROM progreso 
        WHERE id_usuario = :usuario AND id_modulo = :modulo";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ":usuario"=>$id_usuario,
    ":modulo"=>$id_modulo
]);

$res = $stmt->fetch(PDO::FETCH_ASSOC);
$porcentaje = $res ? $res["porcentaje"] : 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title><?php echo $titulo; ?></title>

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">

<style>

body{
    font-family:'Montserrat',sans-serif;
    margin:0;

    background: linear-gradient(135deg,#c40c0c,#111827,#ffffff);
    background-size:600% 600%;
    animation:grad 20s infinite;

    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

@keyframes grad{
    0%{background-position:0% 50%;}
    50%{background-position:100% 50%;}
    100%{background-position:0% 50%;}
}

.container{
    width:900px;
    background:rgba(255,255,255,0.15);
    backdrop-filter:blur(20px);
    padding:30px;
    border-radius:20px;
    color:white;
}

h1{text-align:center;}

/* TEXTO */
.content{
    margin-top:20px;
    line-height:1.7;
    white-space: pre-line;
}

/* PROGRESO */
.bar{
    margin-top:20px;
    height:10px;
    background:rgba(255,255,255,0.3);
    border-radius:10px;
}

.bar span{
    display:block;
    height:100%;
    background:#22c55e;
}

/* BOTONES */
button{
    margin-top:20px;
    width:100%;
    padding:12px;
    border:none;
    border-radius:20px;
    background:#2563eb;
    color:white;
    cursor:pointer;
}

button:hover{background:#1e40af;}

.volver{
    margin-top:15px;
    display:inline-block;
    color:white;
    text-decoration:none;
}

</style>
</head>

<body>

<div class="container">

<h1><?php echo $titulo; ?></h1>

<div class="content">
<?php echo $contenido; ?>
</div>

<!-- ✅ BARRA PROGRESO -->
<div class="bar">
    <span style="width:<?php echo $porcentaje; ?>%"></span>
</div>

<p><?php echo $porcentaje; ?>% completado</p>

<!-- ✅ BOTON -->
<?php if($porcentaje < 100): ?>

<form action="../controller/guardar_progreso.php" method="post">
    <input type="hidden" name="id_modulo" value="<?php echo $id_modulo; ?>">
    <button type="submit">✅ Marcar como completado</button>
</form>

<?php else: ?>

<button disabled>✅ Módulo completado</button>

<?php endif; ?>

<a href="index_User.php" class="volver">← Volver</a>

</div>

</body>
</html>
