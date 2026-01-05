<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro | Stone Edger</title>
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

        /* Container de Cadastro */
        .form-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            padding: 2rem 2.5rem;
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
            margin-bottom: 1rem;
        }

        .logo-box {
            width: 40px;
            height: 40px;
            border: 2px solid var(--primary-color);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-size: 1.2rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
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
            font-size: 1.25rem;
            margin-bottom: 0.25rem;
            text-align: center;
            font-weight: 700;
        }

        .form-subtitle {
            text-align: center;
            color: #94a3b8;
            font-size: 0.8rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 0.75rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.25rem;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--primary-color);
        }

        .form-group input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--glass-border);
            border-radius: 10px;
            background: var(--input-bg);
            color: var(--text-color);
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            background: rgba(255, 255, 255, 0.1);
        }

        .password-rules {
            margin: 8px 0;
            padding: 8px 12px;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            border: 1px solid var(--glass-border);
        }

        .rule {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 4px 0;
            font-size: 0.7rem;
            transition: all 0.3s;
        }

        .valid {
            color: #10b981;
        }

        .invalid {
            color: #ef4444;
            opacity: 0.5;
        }

        .password-strength {
            height: 4px;
            width: 100%;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
            margin-top: 8px;
            overflow: hidden;
        }

        .strength-bar {
            height: 100%;
            width: 0;
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .error-message {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 6px;
            display: none;
        }

        .show-password {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0.75rem 0;
            font-size: 0.8rem;
            cursor: pointer;
            color: #94a3b8;
        }

        .show-password input {
            accent-color: var(--primary-color);
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: #050a14;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 0.25rem;
            box-shadow: 0 10px 15px -3px rgba(212, 175, 55, 0.3);
        }

        .btn-submit:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(212, 175, 55, 0.4);
        }

        .btn-submit:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .form-toggle {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.8rem;
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

        #auth-message {
            margin-bottom: 1rem;
            padding: 0.75rem;
            border-radius: 10px;
            font-size: 0.8rem;
            text-align: center;
            display: none;
        }

        .back-home {
            text-align: center;
            margin-top: 1rem;
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
    </style>
</head>

<body>
    <div class="form-container">
        <!-- Logo -->
        <div class="logo-section">
            <div class="logo-box">S</div>
            <div class="logo-text">STONE <span>EDGER</span></div>
        </div>

        <h2 class="form-title">Crie sua Conta</h2>
        <p class="form-subtitle">Cadastre-se para acessar seu dashboard</p>

        <div id="auth-message"></div>

        <form id="signupForm">
            <div class="form-group">
                <label>Nome Completo</label>
                <input type="text" id="fullName" required placeholder="Digite seu nome">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" id="email" required placeholder="seu@email.com">
            </div>

            <div class="form-group">
                <label>Senha</label>
                <input type="password" id="password" required placeholder="No mínimo 8 caracteres">
                <div class="password-rules">
                    <div class="rule invalid" id="length">
                        <i class="fas fa-check-circle"></i> Mínimo 8 caracteres
                    </div>
                    <div class="rule invalid" id="uppercase">
                        <i class="fas fa-check-circle"></i> Letra maiúscula
                    </div>
                    <div class="rule invalid" id="number">
                        <i class="fas fa-check-circle"></i> Um número
                    </div>
                    <div class="rule invalid" id="special">
                        <i class="fas fa-check-circle"></i> Caractere especial
                    </div>
                </div>
                <div class="password-strength">
                    <div class="strength-bar"></div>
                </div>
            </div>

            <div class="form-group">
                <label>Confirmar Senha</label>
                <input type="password" id="confirmPassword" required placeholder="Repita a senha">
                <div class="error-message" id="passwordError">
                    As senhas não coincidem
                </div>
            </div>

            <label class="show-password">
                <input type="checkbox" id="showPassword">
                <span>Mostrar senhas</span>
            </label>

            <button type="submit" class="btn-submit" id="submit-btn" disabled>CADASTRAR</button>

            <div class="form-toggle">
                Já tem conta? <a href="login.php">Faça Login</a>
            </div>
        </form>

        <div class="back-home">
            <a href="index.php">← Voltar para a Home</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
    <script>
        const SUPABASE_URL = "https://puxuilkexmjpjnrkqysq.supabase.co";
        const SUPABASE_KEY = "sb_publishable_EtvYR3UkvESNn-Ci2MuzrQ_cJYoTOJF";
        const _supabase = supabase.createClient(SUPABASE_URL, SUPABASE_KEY);

        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirmPassword');
        const submitButton = document.getElementById('submit-btn');
        const rules = {
            length: /.{8,}/,
            uppercase: /[A-Z]/,
            number: /[0-9]/,
            special: /[@$!%*?&]/
        };

        function checkPasswordStrength() {
            let strength = 0;
            const passwordValue = password.value;

            Object.keys(rules).forEach(rule => {
                const element = document.getElementById(rule);
                if (rules[rule].test(passwordValue)) {
                    element.classList.add('valid');
                    element.classList.remove('invalid');
                    strength++;
                } else {
                    element.classList.add('invalid');
                    element.classList.remove('valid');
                }
            });

            const strengthBar = document.querySelector('.strength-bar');
            const strengthPercent = (strength / 4) * 100;
            strengthBar.style.width = `${strengthPercent}%`;
            strengthBar.style.background = getStrengthColor(strengthPercent);
        }

        function getStrengthColor(percent) {
            if (percent < 25) return '#ef4444';
            if (percent < 50) return '#f59e0b';
            return '#10b981';
        }

        function validatePasswords() {
            const passwordError = document.getElementById('passwordError');
            if (confirmPassword.value && password.value !== confirmPassword.value) {
                passwordError.style.display = 'block';
                return false;
            }
            passwordError.style.display = 'none';
            return true;
        }

        function validateForm() {
            const isValid = Object.values(rules).every(rule =>
                rule.test(password.value)
            ) && (password.value === confirmPassword.value);

            submitButton.disabled = !isValid;
        }

        password.addEventListener('input', () => {
            checkPasswordStrength();
            validateForm();
        });

        confirmPassword.addEventListener('input', () => {
            validatePasswords();
            validateForm();
        });

        document.getElementById('showPassword').addEventListener('change', (e) => {
            const type = e.target.checked ? 'text' : 'password';
            password.type = type;
            confirmPassword.type = type;
        });

        const authMessage = document.getElementById('auth-message');
        function showMessage(msg, type = 'error') {
            authMessage.textContent = msg;
            authMessage.style.display = 'block';
            authMessage.style.background = type === 'error' ? 'rgba(239, 68, 68, 0.2)' : 'rgba(16, 185, 129, 0.2)';
            authMessage.style.color = type === 'error' ? '#ef4444' : '#10b981';
            authMessage.style.border = `1px solid ${type === 'error' ? '#ef4444' : '#10b981'}`;
        }

        document.getElementById('signupForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const fullName = document.getElementById('fullName').value;
            const email = document.getElementById('email').value;
            const passwordVal = password.value;

            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> CRIANDO CONTA...';

            try {
                const { data, error } = await _supabase.auth.signUp({
                    email,
                    password: passwordVal,
                    options: {
                        data: { full_name: fullName }
                    }
                });

                if (error) throw error;

                if (data.user) {
                    showMessage("CONTA CRIADA COM SUCESSO! Verifique seu e-mail para validar.", "success");
                    document.getElementById('signupForm').reset();
                    setTimeout(() => window.location.href = 'login.php?registered=1', 4000);
                }
            } catch (err) {
                showMessage(err.message || "Ocorreu um erro inesperado.");
                submitButton.disabled = false;
                submitButton.innerHTML = 'CADASTRAR';
            }
        });
    </script>
</body>

</html>