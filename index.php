<?php
require_once 'api/session_handler.php';
$isLoggedIn = isset($_SESSION['user_id']);
$user_name = $_SESSION['user_name'] ?? '';
$avatar_url = $_SESSION['avatar_url'] ?? '';
if ($isLoggedIn && empty($avatar_url)) {
    $avatar_url = 'https://lh3.googleusercontent.com/aida-public/AB6AXuCTCifV9f7veeImD6mpBg5MYpyLXZuX0Wn-PekVpNu3vhVQG721dQEl5WbsrR0o1vraCZDBH5trp5oRZRL1eoPcs3dQ2f-TLvIbK0zrlOY8h0HhQ2cwU_AEwwuY_aTR73AIIqfDUGiolLRlNIFv2tosDtVNg9Of2mQ6U3go3M0Stl4z-ovMmuKmAZstI_VMgVwz4eMj131GaJWanBRhtp4sq_-iwpm3rpvT2lnUsLqCG5sWw3sBN2vvSkwzE6IoKjRM1kJVgZGQng0';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stone Edger | Educação Financeira & Investimentos</title>
    <meta name="description"
        content="Stone Edger - Segurança e Tranquilidade Financeira. Advocacia de Alto Padrão e Consultoria Financeira.">

    <!-- Google Fonts: Playfair Display & Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap"
        rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS (via CDN para arquivo único) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        stone: {
                            navy: '#050a14',
                            gold: '#D4AF37',
                            goldHover: '#b5952f',
                            light: '#F5F5F5',
                            gray: '#CCCCCC',
                            glass: 'rgba(255, 255, 255, 0.08)',
                            glassBorder: 'rgba(255, 255, 255, 0.2)'
                        }
                    },
                    fontFamily: {
                        playfair: ['"Playfair Display"', 'serif'],
                        montserrat: ['"Montserrat"', 'sans-serif'],
                    },
                    backgroundImage: {
                        'gradient-gold': 'linear-gradient(135deg, #D4AF37, #b39020)',
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 1s ease-out forwards',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* --- Estilos Globais e Reset --- */
        html {
            scroll-behavior: smooth;
        }

        body {
            background-color: #050a14;
            color: #F5F5F5;
            font-family: 'Montserrat', sans-serif;
            overflow-x: hidden;
        }

        /* Background Fixo */
        .fixed-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Tenta carregar fundo.jpg local, se não, usa a imagem da web */
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
            background: rgba(0, 0, 0, 0.85);
            /* Overlay escuro */
            z-index: -1;
        }

        /* Utilitários de Vidro (Glassmorphism) */
        .glass-panel {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Scrollbar Personalizada */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #050a14;
        }

        ::-webkit-scrollbar-thumb {
            background: #D4AF37;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #b5952f;
        }

        /* Utilitários de Animação */
        .animate-delay-100 {
            animation-delay: 100ms;
        }

        .animate-delay-200 {
            animation-delay: 200ms;
        }
    </style>
</head>

<body class="antialiased selection:bg-stone-gold selection:text-stone-navy flex flex-col min-h-screen">

    <!-- Elementos de Fundo -->
    <div class="fixed-bg"></div>
    <div class="bg-overlay"></div>

    <!-- Header -->
    <header id="main-header" class="fixed w-full z-50 transition-all duration-300 py-6 bg-transparent">
        <div class="container mx-auto px-6 flex justify-between items-center">

            <!-- Logo -->
            <a href="#"
                class="font-playfair text-2xl md:text-3xl font-bold text-white tracking-wider flex items-center gap-2 group">
                <div
                    class="w-10 h-10 border-2 border-stone-gold flex items-center justify-center rounded-full group-hover:shadow-[0_0_15px_rgba(212,175,55,0.6)] transition-all duration-300">
                    <span class="text-stone-gold font-serif italic text-xl">S</span>
                </div>
                <span>STONE <span class="text-stone-gold">EDGER</span></span>
            </a>

            <!-- Desktop Menu -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="#home"
                    class="text-stone-gray hover:text-stone-gold transition-colors text-sm uppercase tracking-widest font-medium">Home</a>
                <a href="#sobre"
                    class="text-stone-gray hover:text-stone-gold transition-colors text-sm uppercase tracking-widest font-medium">Sobre</a>
                <a href="#atuacao"
                    class="text-stone-gray hover:text-stone-gold transition-colors text-sm uppercase tracking-widest font-medium">Atuação</a>
                <a href="analise.php"
                    class="text-stone-gray hover:text-stone-gold transition-colors text-sm uppercase tracking-widest font-medium">Análise</a>
                <a href="blog.php"
                    class="text-stone-gray hover:text-stone-gold transition-colors text-sm uppercase tracking-widest font-medium">Blog</a>

                <?php if ($isLoggedIn): ?>
                    <a href="dashboard.php" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-full bg-center bg-no-repeat bg-cover border border-stone-gold shadow-[0_0_10px_rgba(212,175,55,0.3)] transition-all group-hover:scale-110"
                            style='background-image: url("<?php echo $avatar_url; ?>");'>
                        </div>
                    </a>
                <?php else: ?>
                    <a href="contatos.php"
                        class="bg-gradient-gold text-stone-navy px-6 py-2 rounded-full font-bold uppercase text-xs tracking-wider hover:scale-105 shadow-[0_4px_15px_rgba(212,175,55,0.3)] transition-all duration-300">
                        Contato
                    </a>
                <?php endif; ?>
            </nav>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="md:hidden text-stone-gold text-2xl focus:outline-none">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div id="mobile-menu"
            class="hidden md:hidden bg-stone-navy/95 backdrop-blur-xl absolute w-full top-full left-0 border-t border-stone-glassBorder shadow-2xl">
            <div class="flex flex-col items-center py-8 space-y-6">
                <a href="#home"
                    class="mobile-link text-white text-lg hover:text-stone-gold uppercase tracking-widest font-bold">Home</a>
                <a href="#sobre"
                    class="mobile-link text-white text-lg hover:text-stone-gold uppercase tracking-widest font-bold">Sobre</a>
                <a href="#atuacao"
                    class="mobile-link text-white text-lg hover:text-stone-gold uppercase tracking-widest font-bold">Atuação</a>
                <a href="contatos.php"
                    class="mobile-link bg-gradient-gold text-stone-navy px-8 py-3 rounded-lg font-bold uppercase tracking-wider">Contatos</a>
            </div>
        </div>
    </header>

    <main class="flex-grow">
        <!-- Hero Section -->
        <section id="home" class="relative h-screen flex items-center justify-center overflow-hidden">
            <div class="relative z-10 container mx-auto px-6 text-center">
                <span
                    class="inline-block border border-stone-gold/30 glass-panel px-4 py-2 rounded-full text-stone-gold font-bold tracking-[0.2em] uppercase mb-6 animate-fade-in-up text-xs md:text-sm">
                    Excelência em Educação Financeira & Investimentos
                </span>
                <h1
                    class="font-playfair text-4xl md:text-6xl lg:text-7xl font-bold text-white mb-8 leading-tight animate-fade-in-up animate-delay-100 max-w-5xl mx-auto drop-shadow-lg">
                    Nosso trabalho é <span class="text-stone-gold italic">simplificar</span> e dar <span
                        class="text-stone-gold italic">clareza</span> aos investimentos.
                </h1>
                <p
                    class="font-montserrat text-stone-gray text-lg md:text-xl mb-12 max-w-2xl mx-auto font-light leading-relaxed animate-fade-in-up animate-delay-200">
                    Proteger seu patrimônio e maximizar seus resultados no mercado financeiro sem abrir mãos da
                    segurança.
                </p>

                <div class="flex flex-col sm:flex-row justify-center gap-6 animate-fade-in-up animate-delay-200">
                    <?php if ($isLoggedIn): ?>
                        <a href="dashboard.php"
                            class="bg-gradient-gold text-stone-navy px-8 py-4 rounded-lg font-bold uppercase tracking-wide hover:scale-105 shadow-[0_4px_15px_rgba(212,175,55,0.3)] transition-all duration-300 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-gauge"></i> Ir para o Dashboard
                        </a>
                    <?php else: ?>
                        <a href="cadastroU.php"
                            class="bg-gradient-gold text-stone-navy px-8 py-4 rounded-lg font-bold uppercase tracking-wide hover:scale-105 shadow-[0_4px_15px_rgba(212,175,55,0.3)] transition-all duration-300 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-user-plus"></i> Cadastro
                        </a>
                        <a href="login.php"
                            class="glass-panel text-white border border-stone-glassBorder px-8 py-4 rounded-lg font-bold uppercase tracking-wide hover:bg-white/10 hover:border-stone-gold transition-all duration-300 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-right-to-bracket"></i> Login
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="sobre" class="py-20 relative">
            <div class="container mx-auto px-6">
                <div class="glass-panel rounded-2xl p-8 md:p-12 lg:p-16 shadow-2xl">
                    <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20">
                        <!-- Image -->
                        <div class="w-full lg:w-1/2 relative group">
                            <div
                                class="relative z-10 rounded-xl overflow-hidden shadow-2xl border border-stone-glassBorder">
                                <img src="https://img.freepik.com/fotos-premium/empresario-a-investir-dinheiro-a-maximizar-os-lucros-e-as-metas-de-poupanca_904318-15902.jpg"
                                    alt="Gráfico de ações"
                                    class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-700 opacity-90 group-hover:opacity-100">
                            </div>
                            <div
                                class="absolute -inset-4 bg-stone-gold/20 blur-2xl -z-10 rounded-full opacity-50 group-hover:opacity-80 transition-opacity">
                            </div>
                        </div>

                        <!-- Text -->
                        <div class="w-full lg:w-1/2">
                            <div class="mb-8">
                                <span
                                    class="block text-stone-gold font-bold tracking-widest uppercase text-xs mb-2">Quem
                                    Somos</span>
                                <h2 class="font-playfair text-3xl md:text-4xl lg:text-5xl font-bold text-stone-light">
                                    acreditamos que investir pode – e deve – ser simples, acessível e transparente.</h2>
                                <div class="h-1 w-20 bg-stone-gold mt-4"></div>
                            </div>
                            <p class="text-stone-gray leading-relaxed mb-6 font-light text-lg">
                                Nossa missão é auxiliar investidores iniciantes em seus primeiros passos no mercado
                                financeiro,
                                oferecendo um ambiente claro e objetivo, livre de jargões complicados.
                            </p>
                            <p class="text-stone-gray leading-relaxed mb-8 font-light text-lg">
                                🔹O que oferecemos
                            </p>
                            <p class="text-stone-gray leading-relaxed mb-8 font-light text-lg">
                                · Informações fundamentalistas confiáveis para apoiar decisões conscientes.<br>
                                · Ferramentas práticas de gerenciamento de carteiras, que ajudam a organizar e
                                acompanhar investimentos com facilidade.<br>
                                · Conteúdo educativo pensado para quem está começando, com foco em clareza e
                                aplicabilidade.
                            </p>
                            <a href="#"
                                class="inline-flex items-center text-stone-gold font-bold uppercase tracking-wider hover:text-white transition-colors group border-b border-transparent hover:border-stone-gold pb-1">
                                Saiba Mais <i
                                    class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section id="atuacao" class="py-24">
            <div class="container mx-auto px-6">
                <div class="mb-12 text-center">
                    <span class="block text-stone-gold font-bold tracking-widest uppercase text-xs mb-2">O Que
                        Fazemos</span>
                    <h2 class="font-playfair text-3xl md:text-4xl lg:text-5xl font-bold text-stone-light">Áreas de
                        Atuação</h2>
                    <div class="h-1 w-20 bg-stone-gold mt-4 mx-auto"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Card 1 -->
                    <div
                        class="glass-panel p-10 hover:bg-white/5 border border-stone-glassBorder hover:border-stone-gold transition-all duration-300 group rounded-2xl shadow-lg hover:-translate-y-2">
                        <div
                            class="w-16 h-16 rounded-full bg-stone-navy/50 border border-stone-gold flex items-center justify-center mb-6 group-hover:bg-stone-gold transition-colors duration-300 shadow-[0_0_15px_rgba(212,175,55,0.2)]">
                            <i
                                class="fa-solid fa-chart-line text-2xl text-stone-gold group-hover:text-stone-navy transition-colors duration-300"></i>
                        </div>
                        <h3
                            class="font-playfair text-2xl font-bold text-white mb-4 group-hover:text-stone-gold transition-colors">
                            Mercado Financeiro</h3>
                        <p class="text-stone-gray font-light leading-relaxed">Transformamos complexidade em
                            simplicidade, tornando o mercado acessível a todos.</p>
                    </div>

                    <!-- Card 2 -->
                    <div
                        class="glass-panel p-10 hover:bg-white/5 border border-stone-glassBorder hover:border-stone-gold transition-all duration-300 group rounded-2xl shadow-lg hover:-translate-y-2">
                        <div
                            class="w-16 h-16 rounded-full bg-stone-navy/50 border border-stone-gold flex items-center justify-center mb-6 group-hover:bg-stone-gold transition-colors duration-300 shadow-[0_0_15px_rgba(212,175,55,0.2)]">
                            <i
                                class="fa-solid fa-graduation-cap text-2xl text-stone-gold group-hover:text-stone-navy transition-colors duration-300"></i>
                        </div>
                        <h3
                            class="font-playfair text-2xl font-bold text-white mb-4 group-hover:text-stone-gold transition-colors">
                            Educação Financeira</h3>
                        <p class="text-stone-gray font-light leading-relaxed">Criamos uma experiência acolhedora e
                            segura para
                            que cada investidor iniciante possa evoluir com confiança moldando sua propia jornada.</p>
                    </div>

                    <!-- Card 3 -->
                    <div
                        class="glass-panel p-10 hover:bg-white/5 border border-stone-glassBorder hover:border-stone-gold transition-all duration-300 group rounded-2xl shadow-lg hover:-translate-y-2">
                        <div
                            class="w-16 h-16 rounded-full bg-stone-navy/50 border border-stone-gold flex items-center justify-center mb-6 group-hover:bg-stone-gold transition-colors duration-300 shadow-[0_0_15px_rgba(212,175,55,0.2)]">
                            <i
                                class="fa-solid fa-briefcase text-2xl text-stone-gold group-hover:text-stone-navy transition-colors duration-300"></i>
                        </div>
                        <h3
                            class="font-playfair text-2xl font-bold text-white mb-4 group-hover:text-stone-gold transition-colors">
                            Carteira de Ações</h3>
                        <p class="text-stone-gray font-light leading-relaxed">Unimos tecnologia e conhecimento para
                            entregar informações que realmente fazem diferença.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="py-24 relative overflow-hidden">
            <div
                class="absolute top-0 right-0 opacity-10 pointer-events-none transform translate-x-1/4 -translate-y-1/4">
                <i class="fa-solid fa-scale-balanced text-[30rem] text-stone-glassBorder"></i>
            </div>

            <div class="container mx-auto px-6 relative z-10">
                <div class="mb-16 text-center">
                    <span class="block text-stone-gold font-bold tracking-widest uppercase text-xs mb-2">Prova
                        Social</span>
                    <h2 class="font-playfair text-3xl md:text-4xl lg:text-5xl font-bold text-stone-light">A voz de
                        nossos parceiros</h2>
                    <div class="h-1 w-20 bg-stone-gold mt-4 mx-auto"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Review 1 -->
                    <div
                        class="glass-panel p-8 rounded-xl border border-stone-glassBorder shadow-lg relative group hover:border-stone-gold/50 transition-all duration-300">
                        <div
                            class="absolute -top-6 left-8 bg-gradient-gold text-stone-navy w-12 h-12 flex items-center justify-center text-xl rounded-full shadow-lg">
                            <i class="fa-solid fa-quote-left"></i>
                        </div>
                        <p class="mt-6 mb-6 text-stone-light italic font-playfair text-lg">""</p>
                        <div class="border-t border-stone-glassBorder pt-4">
                            <p class="font-bold text-stone-gold uppercase tracking-wide text-sm">""M""</p>
                            <p class="text-stone-gray text-xs font-bold mt-1">Investidor</p>
                        </div>
                    </div>
                    <!-- Review 2 -->
                    <div
                        class="glass-panel p-8 rounded-xl border border-stone-glassBorder shadow-lg relative group hover:border-stone-gold/50 transition-all duration-300">
                        <div
                            class="absolute -top-6 left-8 bg-gradient-gold text-stone-navy w-12 h-12 flex items-center justify-center text-xl rounded-full shadow-lg">
                            <i class="fa-solid fa-quote-left"></i>
                        </div>
                        <p class="mt-6 mb-6 text-stone-light italic font-playfair text-lg">""</p>
                        <div class="border-t border-stone-glassBorder pt-4">
                            <p class="font-bold text-stone-gold uppercase tracking-wide text-sm">""L""ima""</p>
                            <p class="text-stone-gray text-xs font-bold mt-1">CEO</p>
                        </div>
                    </div>
                    <!-- Review 3 -->
                    <div
                        class="glass-panel p-8 rounded-xl border border-stone-glassBorder shadow-lg relative group hover:border-stone-gold/50 transition-all duration-300">
                        <div
                            class="absolute -top-6 left-8 bg-gradient-gold text-stone-navy w-12 h-12 flex items-center justify-center text-xl rounded-full shadow-lg">
                            <i class="fa-solid fa-quote-left"></i>
                        </div>
                        <p class="mt-6 mb-6 text-stone-light italic font-playfair text-lg">""
                        </p>
                        <div class="border-t border-stone-glassBorder pt-4">
                            <p class="font-bold text-stone-gold uppercase tracking-wide text-sm">""RC""</p>
                            <p class="text-stone-gray text-xs font-bold mt-1">Trader</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="py-24">
            <div class="container mx-auto px-6 max-w-4xl">
                <div class="mb-16 text-center">
                    <span class="block text-stone-gold font-bold tracking-widest uppercase text-xs mb-2">Dúvidas
                        Comuns</span>
                    <h2 class="font-playfair text-3xl md:text-4xl lg:text-5xl font-bold text-stone-light">Perguntas
                        Frequentes</h2>
                    <div class="h-1 w-20 bg-stone-gold mt-4 mx-auto"></div>
                </div>

                <div class="space-y-4">
                    <!-- FAQ Item 1 -->
                    <div
                        class="faq-item border border-stone-glassBorder rounded-lg bg-stone-navy/40 overflow-hidden hover:border-stone-gold/30 transition-colors">
                        <button
                            class="faq-btn w-full text-left px-6 py-5 flex justify-between items-center focus:outline-none">
                            <span class="font-montserrat font-bold text-lg text-white">Como funciona a consultoria para
                                investidores iniciantes?</span>
                            <i class="fa-solid fa-chevron-down text-stone-gray transition-transform duration-300"></i>
                        </button>
                        <div class="faq-content max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
                            <div
                                class="px-6 pb-6 text-stone-gray font-light leading-relaxed border-t border-stone-glassBorder/50 pt-4">
                                Nossa consultoria começa com uma análise detalhada do seu perfil de risco e objetivos.
                                Em seguida, desenhamos um plano de investimentos financeiro personalizado, acompanhando
                                cada
                                passo da sua jornada com objetivos claros e sem abrir mãos da segurança.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div
                        class="faq-item border border-stone-glassBorder rounded-lg bg-stone-navy/40 overflow-hidden hover:border-stone-gold/30 transition-colors">
                        <button
                            class="faq-btn w-full text-left px-6 py-5 flex justify-between items-center focus:outline-none">
                            <span class="font-montserrat font-bold text-lg text-white">A Stone Edger Tem suporte a
                                ativos
                                internacionais?</span>
                            <i class="fa-solid fa-chevron-down text-stone-gray transition-transform duration-300"></i>
                        </button>
                        <div class="faq-content max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
                            <div
                                class="px-6 pb-6 text-stone-gray font-light leading-relaxed border-t border-stone-glassBorder/50 pt-4">
                                No momento não. Porem temos parceiros ao qual podemos lhe direcionar que desfrutam da
                                mesma filisofia e pensamento primados pro nós.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div
                        class="faq-item border border-stone-glassBorder rounded-lg bg-stone-navy/40 overflow-hidden hover:border-stone-gold/30 transition-colors">
                        <button
                            class="faq-btn w-full text-left px-6 py-5 flex justify-between items-center focus:outline-none">
                            <span class="font-montserrat font-bold text-lg text-white">Qual o valor mínimo de patrimônio
                                para ser cliente?</span>
                            <i class="fa-solid fa-chevron-down text-stone-gray transition-transform duration-300"></i>
                        </button>
                        <div class="faq-content max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
                            <div
                                class="px-6 pb-6 text-stone-gray font-light leading-relaxed border-t border-stone-glassBorder/50 pt-4">
                                Como nosso foco é criar um ambiente simples, acessível e transparente para que quer
                                iniciar nessa jornada. Não estabelecemos um valor mínimo rígido.
                                Analisamos a complexidade da demanda e o potencial de crescimento. Nosso foco é
                                construir relacionamentos de longo prazo com quem
                                busca excelência.
                            </div>
                        </div>
                    </div>
                    <!-- FAQ Item 4 -->
                    <div
                        class="faq-item border border-stone-glassBorder rounded-lg bg-stone-navy/40 overflow-hidden hover:border-stone-gold/30 transition-colors">
                        <button
                            class="faq-btn w-full text-left px-6 py-5 flex justify-between items-center focus:outline-none">
                            <span class="font-montserrat font-bold text-lg text-white">É cobrada alguma taxa pela
                                acessoria e ferramentas?</span>
                            <i class="fa-solid fa-chevron-down text-stone-gray transition-transform duration-300"></i>
                        </button>
                        <div class="faq-content max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
                            <div
                                class="px-6 pb-6 text-stone-gray font-light leading-relaxed border-t border-stone-glassBorder/50 pt-4">
                                No momento não é cobrada qulquer tipo de taxa até por que estamos em fase de de
                                desemvolvimento e aprimoramento
                                tanto do site como das ferramentas. Futuramente para acessoria personalizada e
                                ferramentas avançadas poderá ser cobrada uma taxa mensal.
                                ao estilo Fee Fixo. Esse modelo garante transparência e alinhamento sem conflito de
                                interesses.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contatos" class="relative py-24">
            <div class="container mx-auto px-6">
                <div class="glass-panel rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row">

                    <!-- Formulario -->
                    <div class="w-full md:w-3/5 p-8 md:p-12 lg:p-16">
                        <h3 class="font-playfair text-3xl font-bold text-white mb-2">Inicie uma conversa</h3>
                        <p class="text-stone-gray mb-8">Preencha o formulário abaixo e entraremos em contato.</p>

                        <form class="space-y-6"
                            onsubmit="event.preventDefault(); alert('Mensagem enviada com sucesso!');">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-stone-gold text-xs font-bold uppercase mb-2">Nome</label>
                                    <input type="text"
                                        class="w-full bg-stone-navy/50 border border-stone-glassBorder rounded text-white px-4 py-3 focus:outline-none focus:border-stone-gold transition-colors"
                                        placeholder="Seu nome completo">
                                </div>
                                <div>
                                    <label class="block text-stone-gold text-xs font-bold uppercase mb-2">Email</label>
                                    <input type="email"
                                        class="w-full bg-stone-navy/50 border border-stone-glassBorder rounded text-white px-4 py-3 focus:outline-none focus:border-stone-gold transition-colors"
                                        placeholder="seu@email.com">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label
                                        class="block text-stone-gold text-xs font-bold uppercase mb-2">Telefone</label>
                                    <input type="tel"
                                        class="w-full bg-stone-navy/50 border border-stone-glassBorder rounded text-white px-4 py-3 focus:outline-none focus:border-stone-gold transition-colors"
                                        placeholder="(00) 00000-0000">
                                </div>
                                <div>
                                    <label
                                        class="block text-stone-gold text-xs font-bold uppercase mb-2">Assunto</label>
                                    <select
                                        class="w-full bg-stone-navy/50 border border-stone-glassBorder rounded text-white px-4 py-3 focus:outline-none focus:border-stone-gold transition-colors">
                                        <option>Consultoria Financeira</option>
                                        <option>Assessoria Jurídica</option>
                                        <option>Educação / Cursos</option>
                                        <option>Outros</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-stone-gold text-xs font-bold uppercase mb-2">Mensagem</label>
                                <textarea
                                    class="w-full bg-stone-navy/50 border border-stone-glassBorder rounded text-white px-4 py-3 focus:outline-none focus:border-stone-gold transition-colors h-32 resize-none"
                                    placeholder="Como podemos ajudar?"></textarea>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-4">
                                <button type="submit"
                                    class="bg-gradient-gold text-stone-navy px-8 py-3 rounded-lg font-bold uppercase tracking-wide hover:scale-105 shadow-[0_4px_15px_rgba(212,175,55,0.3)] transition-all duration-300 w-full md:w-auto">
                                    Enviar Mensagem <i class="fa-regular fa-paper-plane ml-2"></i>
                                </button>

                                <button type="button" onclick="downloadVCard()"
                                    class="border border-stone-gold text-stone-gold px-8 py-3 rounded-lg font-bold uppercase tracking-wide hover:bg-stone-gold hover:text-stone-navy transition-all duration-300 w-full md:w-auto">
                                    <i class="fa-solid fa-address-card ml-2"></i> Salvar Contato
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Informacoes de Contato -->
                    <div
                        class="w-full md:w-2/5 bg-stone-navy/60 p-8 md:p-12 lg:p-16 flex flex-col justify-center border-t md:border-t-0 md:border-l border-stone-glassBorder">
                        <h4 class="font-playfair text-2xl text-white mb-8">Informações de Contato</h4>

                        <div class="space-y-8">
                            <div class="flex items-start group">
                                <div
                                    class="w-10 h-10 rounded-full border border-stone-gold bg-stone-navy flex items-center justify-center text-stone-gold shrink-0 mt-1 group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-stone-gold font-bold uppercase text-xs">Endereço</p>
                                    <p class="text-stone-gray font-light">Av. Julio Lima, 132<br>Rio de Janeiro - RJ</p>
                                </div>
                            </div>

                            <div class="flex items-start group">
                                <div
                                    class="w-10 h-10 rounded-full border border-stone-gold bg-stone-navy flex items-center justify-center text-stone-gold shrink-0 mt-1 group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-stone-gold font-bold uppercase text-xs">Email</p>
                                    <p class="text-stone-gray font-light">contato@stoneedger.com.br</p>
                                </div>
                            </div>

                            <div class="flex items-start group">
                                <div
                                    class="w-10 h-10 rounded-full border border-stone-gold bg-stone-navy flex items-center justify-center text-stone-gold shrink-0 mt-1 group-hover:scale-110 transition-transform">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-stone-gold font-bold uppercase text-xs">WhatsApp</p>
                                    <p class="text-stone-gray font-light">+55 (21) 99412-0058</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-12 flex space-x-4">
                            <a href="#"
                                class="w-10 h-10 rounded-lg border border-stone-glassBorder flex items-center justify-center text-stone-gray hover:text-stone-navy hover:bg-stone-gold hover:border-stone-gold transition-all">
                                <i class="fa-brands fa-linkedin-in"></i>
                            </a>
                            <a href="#"
                                class="w-10 h-10 rounded-lg border border-stone-glassBorder flex items-center justify-center text-stone-gray hover:text-stone-navy hover:bg-stone-gold hover:border-stone-gold transition-all">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                            <a href="https://web.facebook.com/profile.php?id=100094316734074"
                                class="w-10 h-10 rounded-lg border border-stone-glassBorder flex items-center justify-center text-stone-gray hover:text-stone-navy hover:bg-stone-gold hover:border-stone-gold transition-all">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-stone-navy/80 backdrop-blur-lg pt-16 pb-8 border-t border-stone-gold/30">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-2">
                    <div class="font-playfair text-3xl font-bold tracking-wider mb-6 text-white">
                        STONE <span class="text-stone-gold">EDGER</span>
                    </div>
                    <p class="text-stone-gray font-light max-w-sm mb-6 leading-relaxed">
                        Nosso objetivo é ajudar você a construir e proteger seu legado financeiro.
                    </p>
                </div>

                <!-- Links -->
                <div>
                    <h5 class="text-stone-gold font-bold uppercase tracking-widest mb-6 text-sm">Links Rápidos</h5>
                    <ul class="space-y-4">
                        <li><a href="#home"
                                class="text-stone-gray hover:text-stone-gold transition-colors font-light">Home</a></li>
                        <li><a href="#sobre"
                                class="text-stone-gray hover:text-stone-gold transition-colors font-light">Sobre Nós</a>
                        </li>
                        <li><a href="#atuacao"
                                class="text-stone-gray hover:text-stone-gold transition-colors font-light">Áreas de
                                Atuação</a></li>
                        <li><a href="contatos.php"
                                class="text-stone-gray hover:text-stone-gold transition-colors font-light">Contato</a>
                        </li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div>
                    <h5 class="text-stone-gold font-bold uppercase tracking-widest mb-6 text-sm">Newsletter</h5>
                    <div class="flex">
                        <input type="email" id="newsletter-email" placeholder="Seu melhor e-mail"
                            class="bg-stone-navy border border-stone-glassBorder text-white px-4 py-2 w-full focus:outline-none focus:border-stone-gold font-light">
                        <button id="newsletter-btn"
                            class="bg-stone-gold text-stone-navy px-4 font-bold hover:bg-white transition-colors">OK</button>
                    </div>
                </div>
            </div>

            <div class="border-t border-stone-glassBorder pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-stone-gray text-sm font-light mb-4 md:mb-0">&copy; 2025 Stone Edger. Todos os direitos
                    reservados.</p>
                <div class="flex space-x-6">
                    <a href="#" class="text-stone-gray hover:text-stone-gold text-sm font-light">Termos de Uso</a>
                    <a href="#" class="text-stone-gray hover:text-stone-gold text-sm font-light">Privacidade</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // --- Mobile Menu Toggle ---
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });

        // --- FAQ Accordion ---
        document.querySelectorAll('.faq-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                // Seleciona o conteudo e o icone deste item
                const content = btn.nextElementSibling;
                const icon = btn.querySelector('i');
                const title = btn.querySelector('span');

                // Fecha outros abertos (efeito acordeão unico)
                document.querySelectorAll('.faq-content').forEach(el => {
                    if (el !== content) {
                        el.style.maxHeight = null;
                        el.previousElementSibling.querySelector('i').classList.remove('rotate-180', 'text-stone-gold');
                        el.previousElementSibling.querySelector('span').classList.remove('text-stone-gold');
                    }
                });

                // Toggle atual
                if (content.style.maxHeight) {
                    content.style.maxHeight = null;
                    icon.classList.remove('rotate-180', 'text-stone-gold');
                    title.classList.remove('text-stone-gold');
                } else {
                    content.style.maxHeight = content.scrollHeight + "px";
                    icon.classList.add('rotate-180', 'text-stone-gold');
                    title.classList.add('text-stone-gold');
                }
            });
        });

        // --- VCard Generator Function ---
        function downloadVCard() {
            const contact = {
                name: "Stone Edger",
                org: "Stone Edger Finance",
                title: "Finance Consultancy",
                tel: "+5521994120058",
                email: "contato@stoneedger.com.br",
                url: "https://stoneedger.rf.gd/"
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

        // --- Navbar Scroll Effect ---
        // --- Newsletter Submission ---
        const newsletterBtn = document.getElementById('newsletter-btn');
        const newsletterEmail = document.getElementById('newsletter-email');

        if (newsletterBtn) {
            newsletterBtn.addEventListener('click', async () => {
                const email = newsletterEmail.value.trim();
                if (!email) {
                    alert('Por favor, insira um e-mail.');
                    return;
                }

                newsletterBtn.disabled = true;
                newsletterBtn.textContent = '...';

                try {
                    const response = await fetch('api/newsletter_subscribe.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ email })
                    });
                    const data = await response.json();

                    if (data.success) {
                        alert(data.success);
                        newsletterEmail.value = '';
                    } else {
                        alert(data.error || 'Erro ao se inscrever.');
                    }
                } catch (error) {
                    alert('Erro na conexão com o servidor.');
                } finally {
                    newsletterBtn.disabled = false;
                    newsletterBtn.textContent = 'OK';
                }
            });
        }

        const header = document.getElementById('main-header');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.remove('py-6', 'bg-transparent');
                header.classList.add('py-4', 'bg-stone-navy/90', 'backdrop-blur-md', 'shadow-md');
            } else {
                header.classList.add('py-6', 'bg-transparent');
                header.classList.remove('py-4', 'bg-stone-navy/90', 'backdrop-blur-md', 'shadow-md');
            }
        });
    </script>
</body>

</html>