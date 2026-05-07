<?php
session_start();

// Verifica se está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Cocóricó</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="dashboard-box">
            <div class="header-dashboard">
                <h1>Bem-vindo(a), <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>! 🐓</h1>
                <a href="logout.php" class="btn-logout">Sair</a>
            </div>
            
            <div class="dashboard-content">
                <div class="card-info">
                    <h2>Informações da Conta</h2>
                    <p><strong>Nome:</strong> <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['usuario_email']); ?></p>
                </div>
                
                <div class="card-feature">
                    <h2>🎯 Funcionalidades</h2>
                    <ul>
                        <li>✅ Editar Perfil</li>
                        <li>✅ Alterar Senha</li>
                        <li>✅ Configurações</li>
                        <li>✅ Histórico</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
