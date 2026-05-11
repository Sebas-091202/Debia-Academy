<?php
require_once("../bd/conn.php");

$id_modulo = $_POST["id_modulo"];
$tipo = $_POST["tipo"];
$contenido = $_POST["contenido"];

$ruta = null;

/* SUBIR ARCHIVO */
if(isset($_FILES["archivo"]) && $_FILES["archivo"]["error"] == 0){

    $nombre = time()."_".$_FILES["archivo"]["name"];
    $ruta = "../uploads/".$nombre;

    move_uploaded_file($_FILES["archivo"]["tmp_name"], $ruta);
}

/* GUARDAR */
$sql = "INSERT INTO contenidos (id_modulo, tipo, contenido)
VALUES (:m,:t,:c)";

$stmt = $conn->prepare($sql);

$stmt->execute([
":m"=>$id_modulo,
":t"=>$tipo,
":c"=> $ruta ? $ruta : $contenido
]);

header("Location: ../views/admin_modulos.php");
exit;
?>  