<?php
session_start();
require_once("../bd/conn.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $nombre = $_POST["nombre"];
    $tipo = $_POST["tipo_documento"];
    $numero = $_POST["numero_identificacion"];
    $correo = $_POST["correo"];
    $area = $_POST["area"];
    $contrasena = password_hash($_POST["contrasena"], PASSWORD_DEFAULT);

        try {
    $check = $conn->prepare("SELECT * FROM usuarios WHERE correo = :correo");
    $check->execute([":correo"=>$correo]);

    if($check->rowCount() > 0){
        echo "<script>alert('El correo ya existe'); window.location.href='../views/index_Login.html';</script>";
        exit;
    }


        $sql = "INSERT INTO usuarios 
        (nombre, tipo_documento, numero_identificacion, correo, area, contrasena)
        VALUES (:nombre, :tipo, :numero, :correo, :area, :contrasena)";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ":nombre"=>$nombre,
            ":tipo"=>$tipo,
            ":numero"=>$numero,
            ":correo"=>$correo,
            ":area"=>$area,
            ":contrasena"=>$contrasena
        ]);

        /* OBTENER ÚLTIMO USUARIO */
        $id_usuario = $conn->lastInsertId();

        /* CREAR SESIÓN AUTOMÁTICA */
        $_SESSION["usuario"] = $nombre;
        $_SESSION["area"] = $area;
        $_SESSION["id"] = $id_usuario;

        /* REDIRECCIÓN DIRECTA */
        header("Location: ../views/index_User.php");
        exit;

    } catch(PDOException $e){

        echo "<script>alert('Error en registro: " . $e->getMessage() . "'); window.location.href='../views/index_Login.html';</script>";

    }
}
?>