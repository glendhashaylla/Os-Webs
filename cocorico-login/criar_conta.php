<?php
session_start();

// Se já está logado, redireciona para dashboard
if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit;
}

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'config/conexao.php';
    require_once 'config/validacao.php';
    
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $confirma_senha = $_POST['confirma_senha'] ?? '';
    
    // Validações
    if (empty($nome) || empty($email) || empty($senha) || empty($confirma_senha)) {
        $erro = 'Todos os campos são obrigatórios!';
    } elseif (!validar_email($email)) {
        $erro = 'Email inválido!';
    } elseif (strlen($senha) < 6) {
        $erro = 'Senha deve ter no mínimo 6 caracteres!';
    } elseif ($senha !== $confirma_senha) {
        $erro = 'As senhas não conferem!';
    } else {
        try {
            // Verifica se email já existe
            $sql_check = "SELECT id FROM usuarios WHERE email = ?";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->execute([$email]);
            
            if ($stmt_check->rowCount() > 0) {
                $erro = 'Este email já está cadastrado!';
            } else {
                // Criptografa a senha
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                
                // Insere novo usuário
                $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nome, $email, $senha_hash]);
                
                $sucesso = 'Conta criada com sucesso! Você será redirecionado para login...';
                header('refresh:2;url=index.php');
            }
        } catch (Exception $e) {
            $erro = 'Erro ao criar conta: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - Cocóricó</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h1>Criar Conta - Cocóricó</h1>
            
            <?php if ($erro): ?>
                <div class="alerta alerta-erro">
                    <?php echo htmlspecialchars($erro); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($sucesso): ?>
                <div class="alerta alerta-sucesso">
                    <?php echo htmlspecialchars($sucesso); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="formulario">
                <input 
                    type="text" 
                    name="nome" 
                    placeholder="Seu Nome" 
                    value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>"
                    required
                >
                
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
                    placeholder="Senha (mín. 6 caracteres)" 
                    required
                >
                
                <input 
                    type="password" 
                    name="confirma_senha" 
                    placeholder="Confirme a Senha" 
                    required
                >
                
                <button type="submit" class="btn-entrar">Criar Conta</button>
            </form>
            
            <p class="texto-cadastro">Já tem uma conta?</p>
            <a href="index.php" class="btn-criar-conta">Fazer Login</a>
        </div>
    </div>
</body>
</html>
