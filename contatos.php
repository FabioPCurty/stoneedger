<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stone Edger | Real Estate</title>

    <!-- Google Fonts: Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* --- RESET & VARIÁVEIS --- */
        :root {
            --primary-gold: #D4AF37;
            --primary-gold-hover: #b5952f;
            --text-light: #F5F5F5;
            --text-gray: #CCCCCC;
            --glass-bg: rgba(255, 255, 255, 0.08);
            --glass-border: rgba(255, 255, 255, 0.2);
            --dark-overlay: rgba(0, 0, 0, 0.85);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #050a14;
            color: var(--text-light);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow-x: hidden;
        }

        /* --- BACKGROUND --- */
        .bg-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Substitua 'fundo.jpg' pela sua imagem de fundo anterior */
            background-image: url('fundo.jpg');
            /* Fallback caso não tenha a imagem local ainda */
            background-image: url('img/fundo.jpg'), url('https://images.unsplash.com/photo-1611974765270-ca1258634369?ixlib=rb-4.0.3&auto=format&fit=crop&w=1080&q=80');
            background-size: cover;
            background-position: center;
            z-index: -2;
        }

        .bg-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--dark-overlay);
            z-index: -1;
        }

        /* --- CONTAINER PRINCIPAL --- */
        .card-container {
            width: 100%;
            max-width: 420px;
            min-height: 100vh;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-left: 1px solid var(--glass-border);
            border-right: 1px solid var(--glass-border);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5);
            padding: 40px 25px;
            display: flex;
            flex-direction: column;
            align-items: center;
            opacity: 0;
            animation: fadeIn 1s ease-out forwards;
        }

        @media (min-width: 480px) {
            .card-container {
                min-height: auto;
                border-radius: 20px;
                border: 1px solid var(--glass-border);
                margin: 20px;
            }
        }

        /* --- PERFIL & EFEITOS ESPECIAIS --- */
        .profile-section {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }

        .profile-img {
            width: 130px;
            /* Levemente maior para destacar o logo */
            height: 130px;
            border-radius: 50%;

            /* Ajustes para o Logotipo */
            object-fit: contain;
            /* Garante que o texto do logo apareça inteiro */
            background-color: #000;
            /* Fundo preto para misturar com o logo */

            border: 3px solid var(--primary-gold);
            padding: 5px;
            /* Espaço entre a borda dourada e o logo */
            background-clip: content-box;
            margin-bottom: 15px;

            /* Preparação para a Animação */
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
            cursor: pointer;
        }

        /* EFEITO AO PASSAR O MOUSE (HOVER) */
        .profile-img:hover {
            transform: scale(1.1);
            /* Aumenta 10% */
            border-color: #fff;
            /* A borda pisca para branco/prata */
            /* Efeito de brilho intenso (Glow) */
            box-shadow: 0 0 25px rgba(212, 175, 55, 0.8),
                0 0 50px rgba(212, 175, 55, 0.4);
        }

        h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 5px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        h2 {
            font-size: 0.8rem;
            color: var(--primary-gold);
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .bio-text {
            font-size: 0.85rem;
            color: var(--text-gray);
            font-weight: 300;
            line-height: 1.5;
            max-width: 90%;
            margin: 0 auto;
        }

        /* --- BOTÕES --- */
        .grid-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            width: 100%;
            margin-bottom: 20px;
        }

        .action-btn {
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            padding: 15px;
            border-radius: 12px;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .action-btn i {
            font-size: 1.5rem;
            color: var(--primary-gold);
        }

        .action-btn span {
            font-size: 0.8rem;
            font-weight: 500;
        }

        .action-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-3px);
            border-color: var(--primary-gold);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        /* --- BOTÃO SALVAR --- */
        .save-contact-btn {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-gold), #b39020);
            color: #000;
            border: none;
            padding: 18px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .save-contact-btn:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 20px rgba(212, 175, 55, 0.5);
        }

        /* --- DESTAQUE IMÓVEL --- */
        .property-highlight {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }

        .property-highlight:hover {
            transform: translateY(-5px);
            border-color: var(--primary-gold);
        }

        .prop-img-container {
            position: relative;
            width: 100%;
            height: 160px;
        }

        .prop-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .prop-tag {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--primary-gold);
            color: #000;
            padding: 5px 10px;
            font-size: 0.7rem;
            font-weight: 700;
            border-radius: 4px;
            text-transform: uppercase;
        }

        .prop-content {
            padding: 15px;
            text-align: center;
        }

        .prop-title {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--text-light);
        }

        .prop-link {
            font-size: 0.8rem;
            color: var(--primary-gold);
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            border-bottom: 1px solid transparent;
            transition: border-color 0.3s;
        }

        .prop-link:hover {
            border-bottom-color: var(--primary-gold);
        }

        /* --- RODAPÉ --- */
        footer {
            text-align: center;
            margin-top: auto;
            width: 100%;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 15px;
        }

        .social-link {
            color: var(--text-gray);
            font-size: 1.2rem;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .social-link:hover {
            color: var(--primary-gold);
            transform: scale(1.2);
        }

        .copyright {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.4);
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
    </style>
</head>

<body>

    <!-- Background -->
    <div class="bg-image"></div>
    <div class="bg-overlay"></div>

    <!-- Main Card -->
    <main class="card-container">

        <!-- Header / Logo -->
        <div class="profile-section">
            <!-- 
               IMPORTANTE: Salve a imagem do logotipo como 'logo.png' 
               na mesma pasta deste arquivo.
            -->
            <a href="index.php">
                <img src="img/logo.png" alt="Stone Edger Logo" class="profile-img">
            </a>

            <!-- Fallback para teste caso não tenha o arquivo local, remover depois -->
            <script>
                document.querySelector('.profile-img').onerror = function () {
                    // Se não achar o logo.png, mostra um placeholder para não quebrar
                    this.src = 'https://via.placeholder.com/150/000000/D4AF37?text=STONE+EDGER';
                };
            </script>

            <h1>Stone Edger</h1>
            <h2>Educação Financeira & Investimentos</h2>
            <p class="bio-text">Soluções inteligentes para o mercado financeiro e para quem quer aprender a gerenciar
                seu portifólio.</p>
        </div>

        <!-- Grid Buttons -->
        <div class="grid-actions">
            <a href="https://wa.me/5521994120058text=Olá gostaria de mais informações sobre investimentos ou educação financeira"
                target="_blank" class="action-btn">
                <i class="fa-brands fa-whatsapp"></i>
                <span>WhatsApp</span>
            </a>
            <a href="https://www.google.com.br/maps/place/Av.+J%C3%BAlio+Lima,+132+-+Laranjal,+S%C3%A3o+Gon%C3%A7alo+-+RJ,+24720-000/@-22.8133409,-42.9995506,17z/data=!3m1!4b1!4m6!3m5!1s0x9990c1dabaae25:0x31389d79604b5a4d!8m2!3d-22.8133459!4d-42.9969757!16s%2Fg%2F11v130309f?entry=ttu&g_ep=EgoyMDI1MTIwMi4wIKXMDSoASAFQAw%3D%3D"
                target="_blank" class="action-btn">
                <i class="fa-solid fa-location-dot"></i>
                <span>Escritório</span>
            </a>
            <a href="mailto:stone_edger@outlook.com" class="action-btn">
                <i class="fa-regular fa-envelope"></i>
                <span>E-mail</span>
            </a>
            <a href="https://stoneedger.rf.gd" target="_blank" class="action-btn">
                <i class="fa-solid fa-globe"></i>
                <span>Site</span>
            </a>
        </div>

        <!-- Save Contact -->
        <button id="vcardBtn" class="save-contact-btn" onclick="downloadVCard()">
            <i class="fa-solid fa-user-plus"></i> SALVAR CONTATO
        </button>

        <!-- Highlight -->
        <div class="property-highlight">
            <div class="prop-img-container">
                <img src="https://fiibrasil.com.br/wp-content/uploads/2024/09/OIAG11-750x375.jpg?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
                    alt="Mansão Moderna">
                <span class="prop-tag">Oportunidade </span>
            </div>
            <div class="prop-content">
                <div class="prop-title">FII AGRO APRESENTA RESILIÊNCIA</div>
                <a href="https://fnet.bmfbovespa.com.br/fnet/publico/downloadDocumento?id=997475" class="prop-link">VER
                    OPORTUNIDADE <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>

        <!-- Footer -->
        <footer>
            <div class="social-icons">
                <a href="#" class="social-link"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" class="social-link"><i class="fa-brands fa-linkedin-in"></i></a>
                <a href="https://web.facebook.com/profile.php?id=100094316734074" class="social-link"><i
                        class="fa-brands fa-facebook-f"></i></a>
            </div>
            <p class="copyright">© 2025 Stone Edger Corp.<br>All rights reserved.</p>
        </footer>

    </main>

    <script>
        function downloadVCard() {
            const contact = {
                name: "Stone Edger",
                org: "Stone Edger Finance",
                title: "Finance & Education",
                tel: "+5521994120058",
                email: "contact@stoneedger.com",
                url: "https://stoneedger.rf.gd"
            };

            const vCardData = `BEGIN:VCARD
VERSION:3.0
FN:${contact.name}
ORG:${contact.org}
TITLE:${contact.title}
TEL;TYPE=CELL:${contact.tel}
EMAIL:${contact.email}
URL:${contact.url}
END:VCARD`;

            const blob = new Blob([vCardData], { type: "text/vcard;charset=utf-8" });
            const url = URL.createObjectURL(blob);
            const link = document.createElement("a");

            link.href = url;
            link.setAttribute("download", "Stone_Edger.vcf");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Interação touch para mobile
        document.querySelectorAll('a, button, .profile-img').forEach(elem => {
            elem.addEventListener('touchstart', function () {
                this.style.transform = 'scale(0.95)';
            });
            elem.addEventListener('touchend', function () {
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });
    </script>
</body>

</html>