<?php
session_start();
session_destroy();
header("Location: ../views/index_Login.php");
exit;
?>