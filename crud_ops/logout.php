<?php
session_start();
session_destroy();
header("Location: ../backend/login_frontend.php");
exit();
?>