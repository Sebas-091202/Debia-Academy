<?php
session_start();
require_once("../bd/conn.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $correo = $_POST["correo"];
    $contrasena = $_POST["contrasena"];

    $sql = "SELECT * FROM usuarios WHERE correo = :correo";
    $stmt = $conn->prepare($sql);
    $stmt->execute([":correo"=>$correo]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($contrasena, $user["contrasena"])){

        $_SESSION["usuario"] = $user["nombre"];
        $_SESSION["area"] = $user["area"];
        $_SESSION["id"] = $user["id"];

        header("Location: ../views/index_User.php");
        exit;

    } else {
        echo "<script>alert('Credenciales incorrectas'); window.location.href='../views/index_Login.html';</script>";
    }
}
?>