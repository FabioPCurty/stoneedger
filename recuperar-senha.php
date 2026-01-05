<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha | Stone Edger</title>
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
            transition: all 0.3s;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(var(--overlay-dark), var(--overlay-dark)),
                url("img/fundo.jpg") center/cover fixed;
            color: var(--text-color);
            padding: 2rem;
        }

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
            margin-bottom: 1rem;
            text-align: center;
            font-weight: 700;
        }

        .form-subtitle {
            text-align: center;
            color: #94a3b8;
            font-size: 0.875rem;
            margin-bottom: 2.5rem;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 1.5rem;
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
            outline: none;
        }

        .form-group input:focus {
            border-color: var(--primary-color);
            background: rgba(255, 255, 255, 0.1);
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

        #message-box {
            margin-bottom: 1.5rem;
            padding: 1rem;
            border-radius: 12px;
            font-size: 0.85rem;
            text-align: center;
            display: none;
        }

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

        .back-link {
            text-align: center;
            margin-top: 2rem;
        }

        .back-link a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .back-link a:hover {
            color: var(--primary-color);
        }
    </style>
</head>

<body>
    <div class="form-container">
        <div class="logo-section">
            <div class="logo-box">S</div>
            <div class="logo-text">STONE <span>EDGER</span></div>
        </div>

        <h2 class="form-title">Recuperação</h2>
        <p class="form-subtitle">Esqueceu sua senha? Não se preocupe. Informe seu e-mail para receber as instruções.</p>

        <div id="message-box"></div>

        <form id="reset-form">
            <div class="form-group">
                <label>E-mail de Cadastro</label>
                <input type="email" id="email" required placeholder="seu@email.com">
            </div>

            <button type="submit" class="btn-submit" id="submit-btn">
                <span>Enviar Instruções</span>
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>

        <div class="back-link">
            <a href="login.php">← Voltar para o Login</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
    <script>
        const SUPABASE_URL = "https://puxuilkexmjpjnrkqysq.supabase.co";
        const SUPABASE_KEY = "sb_publishable_EtvYR3UkvESNn-Ci2MuzrQ_cJYoTOJF";
        const _supabase = supabase.createClient(SUPABASE_URL, SUPABASE_KEY);

        const form = document.getElementById('reset-form');
        const msgBox = document.getElementById('message-box');
        const btn = document.getElementById('submit-btn');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const email = document.getElementById('email').value;

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> ENVIANDO...';

            try {
                const { error } = await _supabase.auth.resetPasswordForEmail(email, {
                    redirectTo: window.location.origin + '/nova-senha.php',
                });

                if (error) throw error;

                msgBox.textContent = "E-mail enviado! Verifique sua caixa de entrada para redefinir sua senha.";
                msgBox.className = "status-success";
                msgBox.style.display = 'block';
                form.reset();
            } catch (err) {
                msgBox.textContent = err.message || "Ocorreu um erro ao processar o pedido.";
                msgBox.className = "status-error";
                msgBox.style.display = 'block';
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<span>Enviar Instruções</span><i class="fas fa-paper-plane"></i>';
            }
        });
    </script>
</body>

</html>