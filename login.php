<?php
session_start();

// Configurações de login
$admin_password = 'stoneedger2024'; // Senha padrão - altere conforme necessário
$admin_username = 'admin'; // Usuário padrão

// Verificar se já está logado
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
  header('Location: admin.php');
  exit;
}

// Processar login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  if ($username === $admin_username && $password === $admin_password) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_username'] = $username;
    $_SESSION['login_time'] = time();

    header('Location: admin.php');
    exit;
  } else {
    $error_message = 'Usuário ou senha incorretos!';
  }
}

$page_title = "Login | Stone Edger - Administração";
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $page_title; ?></title>
  <link rel="stylesheet" href="style.css" />
  <!-- Font Awesome Cdn Link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
  <style>
    /* Estilos específicos do login */
    #showcase {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100vh;
      overflow: hidden;
    }

    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100vh;
      overflow: hidden;
    }

    .container {
      display: flex;
      height: 100vh;
    }

    nav {
      position: fixed;
      left: 0;
      top: 0;
      height: 100vh;
      width: 120px;
      z-index: 1000;
    }

    .line {
      position: fixed;
      left: 120px;
      top: 0;
      height: 100vh;
      z-index: 999;
    }

    .login-container {
      margin-left: 140px;
      padding: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      width: calc(100% - 140px);
    }

    .login-box {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 15px;
      padding: 3rem;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    .login-header {
      margin-bottom: 2rem;
    }

    .login-title {
      font-size: 2rem;
      color: #fff;
      margin-bottom: 0.5rem;
    }

    .login-subtitle {
      color: #999;
      font-size: 1rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
      text-align: left;
    }

    .form-group label {
      display: block;
      color: #fff;
      margin-bottom: 0.5rem;
      font-weight: 500;
    }

    .form-group input {
      width: 100%;
      padding: 0.8rem;
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 8px;
      background: rgba(255, 255, 255, 0.1);
      color: #fff;
      font-size: 0.9rem;
      backdrop-filter: blur(10px);
      outline: none;
      box-sizing: border-box;
    }

    .form-group input:focus {
      border-color: rgb(251, 186, 0);
    }

    .form-group input::placeholder {
      color: rgba(255, 255, 255, 0.6);
    }

    .btn-login {
      width: 100%;
      background: rgb(251, 186, 0);
      color: #000;
      padding: 0.8rem;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s ease;
      font-size: 1rem;
    }

    .btn-login:hover {
      background: rgba(251, 186, 0, 0.8);
    }

    .error-message {
      background: rgba(244, 67, 54, 0.2);
      border: 1px solid #f44336;
      color: #f44336;
      padding: 1rem;
      border-radius: 8px;
      margin-bottom: 1.5rem;
      text-align: center;
    }

    .login-info {
      margin-top: 2rem;
      padding: 1rem;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 8px;
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .login-info h4 {
      color: #fff;
      margin-bottom: 0.5rem;
      font-size: 0.9rem;
    }

    .login-info p {
      color: #999;
      font-size: 0.8rem;
      margin: 0.3rem 0;
    }

    .back-link {
      margin-top: 1.5rem;
    }

    .back-link a {
      color: #999;
      text-decoration: none;
      font-size: 0.9rem;
      transition: color 0.3s ease;
    }

    .back-link a:hover {
      color: rgb(251, 186, 0);
    }
  </style>
</head>

<body>
  <header id="showcase">
    <div class="overlay">
      <div class="container">
        <nav>
          <div class="logo">
            <img src="./img/logo.jpg" alt="Stone Edger Logo">
          </div>
          <ul class="navbar">
            <li><a href="index.php">Inicio</a></li>
            <li><a href="blog.php">Blog</a></li>
            <li><a href="login.php">Login</a></li>
          </ul>
          <ul class="icons">
            <li><a href="https://facebook.com/stoneedger" target="_blank" rel="noopener"><i
                  class="fab fa-facebook"></i></a></li>
            <li><a href="https://youtube.com/stoneedger" target="_blank" rel="noopener"><i
                  class="fab fa-youtube"></i></a></li>
            <li><a href="https://instagram.com/stoneedger" target="_blank" rel="noopener"><i
                  class="fab fa-instagram"></i></a></li>
          </ul>
        </nav>

        <div class="line"></div>

        <!-- Login Content -->
        <div class="login-container">
          <div class="login-box">
            <div class="login-header">
              <h1 class="login-title">🔐 Login Administrativo</h1>
              <p class="login-subtitle">Acesso restrito à administração do Stone Edger</p>
            </div>

            <?php if (isset($error_message)): ?>
              <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i> <?php echo $error_message; ?>
              </div>
            <?php endif; ?>

            <?php if (isset($_GET['logged_out'])): ?>
              <div class="error-message"
                style="background: rgba(76, 175, 80, 0.2); border-color: #4CAF50; color: #4CAF50;">
                <i class="fas fa-check-circle"></i> Logout realizado com sucesso!
              </div>
            <?php endif; ?>

            <form method="POST" action="login.php">
              <div class="form-group">
                <label for="username">Usuário</label>
                <input type="text" id="username" name="username" placeholder="Digite seu usuário" required>
              </div>

              <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" placeholder="Digite sua senha" required>
              </div>

              <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Entrar
              </button>
            </form>

            <div class="login-info">
              <h4>Informações de Acesso:</h4>
              <p><strong>Usuário:</strong> admin</p>
              <p><strong>Senha:</strong> stoneedger2024</p>
              <p style="color: #f44336; font-size: 0.7rem;">
                ⚠️ Altere essas credenciais em produção!
              </p>
            </div>

            <div class="back-link">
              <a href="index.php">← Voltar ao Site</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Footer -->
  <footer style="position: fixed; bottom: 10px; right: 10px; color: #666; font-size: 12px;">
    © <?php echo date('Y'); ?> Stone Edger. Todos os direitos reservados.
  </footer>

</body>

</html>