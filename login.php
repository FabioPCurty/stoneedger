<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
  if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    header('Location: administra.php');
  } else {
    header('Location: dashboard.php');
  }
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Stone Edger</title>
  <!-- Google Fonts -->
  <link
    href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&family=Playfair+Display:wght@700&display=swap"
    rel="stylesheet">
  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --bg-color: #050a14;
      --text-color: #ffffff;
      --primary-color: #D4AF37;
      --secondary-color: #b39020;
      --overlay-dark: rgba(0, 0, 0, 0.85);
      --overlay-light: rgba(255, 255, 255, 0.08);
      --input-bg: rgba(0, 0, 0, 0.4);
      --glass-border: rgba(255, 255, 255, 0.1);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Montserrat', sans-serif;
      transition: background-color 0.3s, color 0.3s;
    }

    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(var(--overlay-dark), var(--overlay-dark)),
        url("img/fundo.jpg") center/cover fixed,
        url("https://images.unsplash.com/photo-1611974765270-ca1258634369?ixlib=rb-4.0.3&auto=format&fit=crop&w=1080&q=80") center/cover fixed;
      color: var(--text-color);
      padding: 2rem;
    }

    /* Container de Login */
    .form-container {
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(20px);
      padding: 3rem;
      border-radius: 24px;
      width: 100%;
      max-width: 450px;
      border: 1px solid var(--glass-border);
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
      animation: fadeIn 0.8s ease-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .logo-section {
      text-align: center;
      margin-bottom: 2rem;
    }

    .logo-box {
      width: 50px;
      height: 50px;
      border: 2px solid var(--primary-color);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      font-family: 'Playfair Display', serif;
      font-style: italic;
      font-size: 1.5rem;
      color: var(--primary-color);
      margin-bottom: 1rem;
    }

    .logo-text {
      font-family: 'Playfair Display', serif;
      font-size: 1.8rem;
      font-weight: 700;
      letter-spacing: 2px;
    }

    .logo-text span {
      color: var(--primary-color);
    }

    .form-title {
      font-size: 1.5rem;
      margin-bottom: 0.5rem;
      text-align: center;
      font-weight: 700;
      color: #fff;
    }

    .form-subtitle {
      text-align: center;
      color: #94a3b8;
      font-size: 0.875rem;
      margin-bottom: 2rem;
    }

    .form-group {
      margin-bottom: 1.25rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-size: 0.7rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      color: var(--primary-color);
    }

    .form-group input {
      width: 100%;
      padding: 14px 16px;
      border: 1px solid var(--glass-border);
      border-radius: 12px;
      background: var(--input-bg);
      color: var(--text-color);
      font-size: 0.95rem;
      transition: all 0.3s;
      outline: none;
    }

    .form-group input:focus {
      border-color: var(--primary-color);
      background: rgba(255, 255, 255, 0.1);
      box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1);
    }

    .btn-submit {
      width: 100%;
      padding: 14px;
      border: none;
      border-radius: 12px;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: #050a14;
      font-size: 0.9rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      cursor: pointer;
      transition: all 0.3s;
      margin-top: 1rem;
      box-shadow: 0 10px 15px -3px rgba(212, 175, 55, 0.3);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .btn-submit:hover:not(:disabled) {
      transform: translateY(-2px);
      box-shadow: 0 20px 25px -5px rgba(212, 175, 55, 0.4);
    }

    .btn-submit:disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }

    .form-toggle {
      text-align: center;
      margin-top: 2rem;
      font-size: 0.85rem;
      color: #94a3b8;
    }

    .form-toggle a {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 700;
      margin-left: 4px;
    }

    .form-toggle a:hover {
      text-decoration: underline;
    }

    #login-message {
      margin-bottom: 1.5rem;
      padding: 1rem;
      border-radius: 12px;
      font-size: 0.85rem;
      text-align: center;
      display: none;
      animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .back-home {
      text-align: center;
      margin-top: 2rem;
    }

    .back-home a {
      color: #64748b;
      text-decoration: none;
      font-size: 0.8rem;
      transition: color 0.3s;
    }

    .back-home a:hover {
      color: var(--primary-color);
    }

    /* Statuses */
    .status-error {
      background: rgba(239, 68, 68, 0.15);
      color: #ef4444;
      border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .status-success {
      background: rgba(16, 185, 129, 0.15);
      color: #10b981;
      border: 1px solid rgba(16, 185, 129, 0.3);
    }
  </style>
</head>

<body>
  <div class="form-container">
    <!-- Logo -->
    <div class="logo-section">
      <div class="logo-box">S</div>
      <div class="logo-text">STONE <span>EDGER</span></div>
    </div>

    <h2 class="form-title">Acesso Restrito</h2>
    <p class="form-subtitle">Gerencie sua carteira de investimentos</p>

    <div id="login-message"></div>

    <?php if (isset($_GET['registered'])): ?>
      <div class="status-success"
        style="padding: 1rem; border-radius: 12px; font-size: 0.85rem; text-align: center; margin-bottom: 1.5rem;">
        <i class="fas fa-check-circle"></i> Cadastro concluído! Faça seu login.
      </div>
    <?php endif; ?>

    <form id="login-form">
      <div class="form-group">
        <label>E-mail de Acesso</label>
        <input type="email" id="email" required placeholder="seu@email.com">
      </div>

      <div class="form-group">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
          <label style="margin-bottom: 0;">Senha</label>
          <a href="recuperar-senha.php"
            style="color: var(--primary-color); font-size: 0.7rem; text-decoration: none; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Esqueceu?</a>
        </div>
        <input type="password" id="password" required placeholder="••••••••">
      </div>

      <button type="submit" class="btn-submit" id="login-btn">
        <span>Entrar no Sistema</span>
        <i class="fas fa-arrow-right"></i>
      </button>

      <div class="form-toggle">
        Ainda não é membro? <a href="cadastroU.php">Criar Conta Premium</a>
      </div>
    </form>

    <div class="back-home">
      <a href="index.php">← Voltar para a Home</a>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
  <script>
    const SUPABASE_URL = "https://puxuilkexmjpjnrkqysq.supabase.co";
    const SUPABASE_KEY = "sb_publishable_EtvYR3UkvESNn-Ci2MuzrQ_cJYoTOJF";
    const _supabase = supabase.createClient(SUPABASE_URL, SUPABASE_KEY);

    const loginForm = document.getElementById('login-form');
    const loginMsg = document.getElementById('login-message');
    const loginBtn = document.getElementById('login-btn');

    function showStatus(msg, type = 'error') {
      loginMsg.textContent = msg;
      loginMsg.className = type === 'error' ? 'status-error' : 'status-success';
      loginMsg.style.display = 'block';
      loginMsg.style.padding = '1rem';
      loginMsg.style.borderRadius = '12px';
      loginMsg.style.fontSize = '0.85rem';
      loginMsg.style.textAlign = 'center';
      loginMsg.style.marginBottom = '1.5rem';
    }

    loginForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;

      loginBtn.disabled = true;
      loginBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> AUTENTICANDO...';

      try {
        const { data: authData, error: authError } = await _supabase.auth.signInWithPassword({
          email,
          password
        });

        if (authError) throw authError;

        if (authData.session) {
          showStatus("Login realizado! Sincronizando...", "success");

          const formData = new FormData();
          formData.append('access_token', authData.session.access_token);
          formData.append('user_id', authData.user.id);
          formData.append('email', authData.user.email);
          formData.append('full_name', authData.user.user_metadata?.full_name || 'Usuário');

          const syncResponse = await fetch('api/auth_sync.php', {
            method: 'POST',
            body: formData
          });

          if (syncResponse.ok) {
            const result = await syncResponse.json();
            if (result.is_admin) {
              window.location.href = 'administra.php';
            } else {
              window.location.href = 'dashboard.php';
            }
          } else {
            throw new Error("Erro na sincronização de sessão.");
          }
        }
      } catch (err) {
        showStatus(err.message || "Dados de acesso inválidos.");
        loginBtn.disabled = false;
        loginBtn.innerHTML = '<span>Entrar no Sistema</span><i class="fas fa-arrow-right"></i>';
      }
    });
  </script>
</body>

</html>