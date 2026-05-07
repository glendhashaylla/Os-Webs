<?php
session_start();

// Se já está logado, redireciona para dashboard
if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'config/conexao.php';
    require_once 'config/validacao.php';
    
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    // Validação básica
    if (empty($email) || empty($senha)) {
        $erro = 'Email e senha são obrigatórios!';
    } elseif (!validar_email($email)) {
        $erro = 'Email inválido!';
    } else {
        try {
            $sql = "SELECT id, nome, email, senha FROM usuarios WHERE email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($usuario && password_verify($senha, $usuario['senha'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_email'] = $usuario['email'];
                
                header('Location: dashboard.php');
                exit;
            } else {
                $erro = 'Email ou senha incorretos!';
            }
        } catch (Exception $e) {
            $erro = 'Erro ao conectar com o banco de dados!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Cocóricó</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h1>Bem-vindo(a) ao Cocóricó</h1>
            
            <?php if ($erro): ?>
                <div class="alerta alerta-erro">
                    <?php echo htmlspecialchars($erro); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="formulario">
                <input 
                    type="email" 
                    name="email" 
                    placeholder="email@gmail.com" 
                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                    required
                >
                
                <input 
                    type="password" 
                    name="senha" 
                    placeholder="Senha" 
                    required
                >
                
                <a href="recuperar_senha.php" class="link-esqueci">Esqueceu a senha?</a>
                
                <button type="submit" class="btn-entrar">Entrar</button>
            </form>
            
            <p class="texto-cadastro">Não tem uma conta?</p>
            <a href="criar_conta.php" class="btn-criar-conta">Criar Conta</a>
        </div>
    </div>
</body>
</html>
