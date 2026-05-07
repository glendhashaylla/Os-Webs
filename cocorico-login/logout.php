<?php
session_start();

// Destrói a sessão e redireciona para login
session_destroy();
header('Location: index.php');
exit;
?>
