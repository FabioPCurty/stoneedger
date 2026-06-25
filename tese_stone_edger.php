<?php
require_once 'api/session_handler.php';
$isLoggedIn = isset($_SESSION['user_id']);
$user_id = $_SESSION['user_id'] ?? null;
$user_email = $_SESSION['user_email'] ?? null;
$user_name = $_SESSION['user_name'] ?? ($user_email ? explode('@', $user_email)[0] : '');
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
    <title>Tese de Investimentos | Stone Edger</title>
    <meta name="description"
        content="Tese de Investimentos da Stone Edger - Além do Status Quo: Uma Nova Visão sobre Investimento Geracional.">

    <!-- Google Fonts: Playfair Display & Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap"
        rel="stylesheet">

    <!-- Material Symbols -->
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />

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
                        },
                        success: "#10b981", // Emerald 500
                        danger: "#ef4444" // Red 500
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
            background-image: url('img/bg.jpg');
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
            background: rgba(0, 0, 0, 0.95); /* Opacidade de analise.php */
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
    </style>
</head>

<body class="antialiased selection:bg-stone-gold selection:text-stone-navy flex flex-col min-h-screen">

    <!-- Elementos de Fundo -->
    <div class="fixed-bg"></div>
    <div class="bg-overlay"></div>

    <!-- Header (Navbar) -->
    <header id="main-header" class="fixed w-full z-50 transition-all duration-300 py-6 bg-transparent">
        <div class="container mx-auto px-6 flex justify-between items-center">

            <!-- Logo -->
            <a href="index.php"
                class="font-playfair text-2xl md:text-3xl font-bold text-white tracking-wider flex items-center gap-2 group">
                <div
                    class="w-10 h-10 border-2 border-stone-gold flex items-center justify-center rounded-full group-hover:shadow-[0_0_15px_rgba(212,175,55,0.6)] transition-all duration-300">
                    <span class="text-stone-gold font-serif italic text-xl">S</span>
                </div>
                <span>STONE <span class="text-stone-gold">EDGER</span></span>
            </a>

            <!-- Desktop Menu -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="index.php#home"
                    class="text-stone-gray hover:text-stone-gold transition-colors text-sm uppercase tracking-widest font-medium">Home</a>
                <a href="index.php#sobre"
                    class="text-stone-gray hover:text-stone-gold transition-colors text-sm uppercase tracking-widest font-medium">Sobre</a>
                <a href="index.php#atuacao"
                    class="text-stone-gray hover:text-stone-gold transition-colors text-sm uppercase tracking-widest font-medium">Atuação</a>
                <a href="analise.php"
                    class="text-stone-gray hover:text-stone-gold transition-colors text-sm uppercase tracking-widest font-medium">Análise</a>
                <a href="blog.php"
                    class="text-stone-gray hover:text-stone-gold transition-colors text-sm uppercase tracking-widest font-medium">Blog</a>

                <?php if ($isLoggedIn): ?>
                    <!-- Profile Trigger -->
                    <div class="relative">
                        <button id="userMenuBtn" class="flex items-center focus:outline-none group">
                            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-9 ring-2 <?php echo empty($_SESSION['investor_profile'] ?? '') ? 'ring-red-500 animate-pulse shadow-[0_0_15px_rgba(239,68,68,0.6)]' : 'ring-stone-gold shadow-[0_0_15px_rgba(212,175,55,0.3)] group-hover:ring-offset-2 group-hover:ring-offset-stone-navy group-hover:ring-stone-goldHover'; ?> transition-all"
                                style='background-image: url("<?php echo $avatar_url; ?>");'>
                            </div>
                        </button>

                        <!-- User Dropdown Menu -->
                        <div id="userDropdown"
                            class="absolute right-0 mt-3 w-64 bg-stone-navy/95 backdrop-blur-xl border border-stone-glassBorder rounded-xl shadow-2xl hidden z-[100] transform transition-all origin-top-right">
                            <div class="p-4 border-b border-stone-glassBorder text-center">
                                <div class="mx-auto w-12 h-12 rounded-full border-2 border-stone-gold mb-2 overflow-hidden bg-cover bg-center"
                                    style='background-image: url("<?php echo $avatar_url; ?>");'></div>
                                <p class="text-stone-gold font-bold text-sm uppercase tracking-widest mb-0.5">
                                    <?php echo htmlspecialchars($user_name); ?>
                                </p>
                            </div>
                            <div class="py-2">
                                <a href="dashboard.php"
                                    class="flex items-center gap-3 px-4 py-3 text-stone-gray hover:text-white hover:bg-stone-glass transition-colors">
                                    <span class="material-symbols-outlined text-lg">dashboard</span>
                                    <span class="text-xs font-bold tracking-wider uppercase">Dashboard</span>
                                </a>
                                <a href="#" onclick="openProfileModal(); return false;"
                                    class="flex items-center gap-3 px-4 py-3 text-stone-gray hover:text-white hover:bg-stone-glass transition-colors group/profile">
                                    <span class="material-symbols-outlined text-lg">person</span>
                                    <span class="text-xs font-bold tracking-wider uppercase">Meu Perfil</span>
                                </a>
                                <a href="suitability.php"
                                    class="flex items-center gap-3 px-4 py-3 text-stone-gold bg-stone-gold/10 hover:text-white hover:bg-stone-glass transition-colors">
                                    <span class="material-symbols-outlined text-lg">psychology</span>
                                    <span class="text-xs font-bold tracking-wider uppercase">Suitability</span>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <a href="contatos.php"
                    class="bg-gradient-gold text-stone-navy px-6 py-2 rounded-full font-bold uppercase text-xs tracking-wider hover:scale-105 shadow-[0_4px_15px_rgba(212,175,55,0.3)] transition-all duration-300">
                    Contato
                </a>
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
                <a href="index.php#home"
                    class="mobile-link text-white text-lg hover:text-stone-gold uppercase tracking-widest font-bold">Home</a>
                <a href="index.php#sobre"
                    class="mobile-link text-white text-lg hover:text-stone-gold uppercase tracking-widest font-bold">Sobre</a>
                <a href="index.php#atuacao"
                    class="mobile-link text-white text-lg hover:text-stone-gold uppercase tracking-widest font-bold">Atuação</a>
                <a href="contatos.php"
                    class="mobile-link bg-gradient-gold text-stone-navy px-8 py-3 rounded-lg font-bold uppercase tracking-wider">Contatos</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow pt-32 pb-24 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header Banner -->
            <div class="text-center mb-12 animate-fade-in-up">
                <span class="inline-block border border-stone-gold/30 glass-panel px-4 py-2 rounded-full text-stone-gold font-bold tracking-[0.2em] uppercase mb-4 text-xs md:text-sm">
                    Tese de Investimentos
                </span>
                <h1 class="font-playfair text-3xl md:text-5xl font-bold text-white mb-6 leading-tight drop-shadow-lg">
                    Além do Status Quo:<br>
                    <span class="text-stone-gold italic font-serif">Uma Nova Visão sobre Investimento Geracional</span>
                </h1>
                <div class="h-1 w-24 bg-gradient-gold mx-auto"></div>
            </div>

            <!-- Content Area in glass panel -->
            <article class="glass-panel rounded-2xl p-8 md:p-12 shadow-2xl text-stone-gray font-light leading-relaxed space-y-8 animate-fade-in-up delay-100">
                
                <p class="text-lg">
                    A filosofia de investimentos da <strong class="text-white font-semibold">Stone Edger</strong> nasce de uma ruptura necessária com o senso comum financeiro. Durante décadas, o mercado defendeu a abordagem tradicional de ciclo de vida: a ideia de que os investidores devem diversificar entre ações e títulos e reduzir a exposição a ações à medida que envelhecem.
                </p>

                <p class="text-lg">
                    No entanto, evidências baseadas em 2.600 anos de dados de 39 países desenvolvidos revelam que essa prática é subótima para a acumulação de riqueza e preservação de capital. Nossa filosofia se embasa em pilares matemáticos e históricos irrefutáveis, estruturados para horizontes de longo prazo (30 anos ou mais).
                </p>

                <!-- Section 1 -->
                <div>
                    <h2 class="font-playfair text-2xl md:text-3xl font-bold text-white border-l-4 border-stone-gold pl-4 mb-4 mt-8">
                        1. Eficiência na Acumulação de Riqueza e Bem-Estar
                    </h2>
                    <p class="mb-4">
                        A estratégia de 100% ações (idealmente alocada em um terço de ações domésticas e dois terços de ações internacionais) supera os benchmarks tradicionais em todas as métricas de valorização de longo prazo.
                    </p>
                    <ul class="list-none space-y-3 pl-2">
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-stone-gold mt-1.5 shrink-0"></i>
                            <span><strong class="text-white font-medium">Patrimônio na Aposentadoria:</strong> A estratégia gera, em média, 50% mais riqueza do que uma carteira balanceada (60/40) e 39% mais do que um fundo de data-alvo (TDF).</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-stone-gold mt-1.5 shrink-0"></i>
                            <span><strong class="text-white font-medium">Custo de Oportunidade:</strong> Para atingir o mesmo nível de utilidade na aposentadoria que um investidor de 100% ações (poupando 10% da renda), um investidor em fundos de data-alvo precisaria poupar 63% a mais de seu salário durante toda a vida profissional.</span>
                        </li>
                    </ul>
                </div>

                <!-- Visual comparison -->
                <div class="bg-stone-navy/40 border border-stone-glassBorder/50 rounded-xl p-6 md:p-8 space-y-6">
                    <h3 class="font-playfair text-lg md:text-xl font-bold text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-stone-gold">bar_chart</span> Comparação de Riqueza Estimada na Aposentadoria
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-xs uppercase tracking-wider mb-2">
                                <span class="text-stone-gold font-bold">Estratégia Stone Edger (100% Ações)</span>
                                <span class="text-white font-bold">150% (Base)</span>
                            </div>
                            <div class="h-4 bg-stone-navy border border-stone-glassBorder rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-gold rounded-full w-full"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-xs uppercase tracking-wider mb-2">
                                <span class="text-stone-gray font-medium">Fundo de Data-Alvo (TDF)</span>
                                <span class="text-stone-gray font-bold">111%</span>
                            </div>
                            <div class="h-4 bg-stone-navy border border-stone-glassBorder rounded-full overflow-hidden">
                                <div class="h-full bg-stone-gray/40 rounded-full" style="width: 74%;"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-xs uppercase tracking-wider mb-2">
                                <span class="text-stone-gray font-medium">Carteira Balanceada (60/40)</span>
                                <span class="text-stone-gray font-bold">100%</span>
                            </div>
                            <div class="h-4 bg-stone-navy border border-stone-glassBorder rounded-full overflow-hidden">
                                <div class="h-full bg-stone-gray/20 rounded-full" style="width: 66%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2 -->
                <div>
                    <h2 class="font-playfair text-2xl md:text-3xl font-bold text-white border-l-4 border-stone-gold pl-4 mb-4 mt-8">
                        2. O Paradoxo da Segurança: Risco de Ruína Financeira
                    </h2>
                    <p class="mb-6">
                        Embora os títulos sejam percebidos como "seguros", eles aumentam drasticamente a probabilidade de um investidor esgotar seus recursos antes da morte (risco de ruína) devido aos retornos reais inferiores e à vulnerabilidade inflacionária. Devido aos baixos retornos reais médios (apenas <span class="text-stone-gold font-medium italic">0,95%</span> ao ano, contra <span class="text-stone-gold font-medium italic">7,03%</span> das ações internacionais), a inclusão de títulos em uma carteira de aposentadoria sabota o crescimento real do patrimônio.
                    </p>

                    <!-- Responsive Table -->
                    <div class="overflow-x-auto rounded-xl border border-stone-glassBorder bg-stone-navy/40">
                        <table class="w-full border-collapse text-left text-sm text-stone-gray">
                            <thead class="bg-stone-navy border-b border-stone-glassBorder text-stone-gold font-bold uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">Estratégia de Alocação</th>
                                    <th class="px-6 py-4">Probabilidade de Ruína (Regra dos 4%)</th>
                                    <th class="px-6 py-4">Probabilidade sob Alta Inflação</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-glassBorder/30">
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4 font-semibold text-white">Estratégia Stone Edger (100% Ações)</td>
                                    <td class="px-6 py-4 text-success font-bold">6,7%</td>
                                    <td class="px-6 py-4 text-success font-bold">15,9%</td>
                                </tr>
                                <tr class="hover:bg-white/5 transition-colors bg-stone-glass/30">
                                    <td class="px-6 py-4">Carteira Balanceada (60/40)</td>
                                    <td class="px-6 py-4">16,9%</td>
                                    <td class="px-6 py-4 text-danger font-medium">51,1%</td>
                                </tr>
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4">Fundo de Data-Alvo (TDF)</td>
                                    <td class="px-6 py-4">19,7%</td>
                                    <td class="px-6 py-4 text-danger font-medium">62,8%</td>
                                </tr>
                                <tr class="hover:bg-white/5 transition-colors bg-stone-glass/30">
                                    <td class="px-6 py-4">100% Títulos Públicos (Bills)</td>
                                    <td class="px-6 py-4 text-danger font-bold">38,9%</td>
                                    <td class="px-6 py-4 text-danger font-bold uppercase tracking-wide">Risco Crítico</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Section 3 -->
                <div>
                    <h2 class="font-playfair text-2xl md:text-3xl font-bold text-white border-l-4 border-stone-gold pl-4 mb-4 mt-8">
                        3. A Falácia dos Títulos em Horizontes de 30 Anos
                    </h2>
                    <p class="mb-4">
                        A tese baseia-se na mudança das propriedades dos ativos em prazos longos. No curto prazo, os títulos parecem menos voláteis, mas em janelas de 30 anos, o cenário se inverte:
                    </p>
                    <ul class="list-none space-y-3 pl-2">
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-stone-gold mt-1.5 shrink-0"></i>
                            <span><strong class="text-white font-medium">Variância de Longo Prazo:</strong> Em 30 anos, a variância anualizada das ações internacionais diminui para uma razão de <span class="text-stone-gold font-medium italic">0,75</span>, enquanto a dos títulos mais que dobra para uma razão de <span class="text-stone-gold font-medium italic">2,30</span>.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-stone-gold mt-1.5 shrink-0"></i>
                            <span><strong class="text-white font-medium">Perda do Benefício de Diversificação:</strong> No curto prazo, os títulos têm uma correlação baixa com as ações domésticas (<span class="text-stone-gold font-medium italic">0,21</span>). Entretanto, no longo prazo (30 anos), essa correlação sobe para <span class="text-stone-gold font-medium italic">0,45</span>, falhando em proteger a carteira justamente nos momentos de maior necessidade.</span>
                        </li>
                    </ul>
                </div>

                <!-- Section 4 -->
                <div>
                    <h2 class="font-playfair text-2xl md:text-3xl font-bold text-white border-l-4 border-stone-gold pl-4 mb-4 mt-8">
                        4. Ações como Escudo de Capital Contra a Inflação
                    </h2>
                    <p class="mb-4">
                        As ações protegem melhor contra a inflação do que os títulos principalmente devido à sua capacidade de preservar o poder de compra real ao longo de horizontes de longo prazo, enquanto o valor dos títulos é severamente corroído em cenários inflacionários. Os títulos oferecem pagamentos nominais fixos que não se ajustam à inflação. As ações, por outro lado, representam a participação em empresas que podem ajustar seus preços e receitas de acordo com o custo de vida.
                    </p>
                    <ul class="list-none space-y-3 pl-2">
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-stone-gold mt-1.5 shrink-0"></i>
                            <span>Em horizontes de 30 anos, os títulos apresentam uma correlação fortemente negativa de <span class="text-stone-gold font-medium italic">-0,78</span> com a inflação.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-stone-gold mt-1.5 shrink-0"></i>
                            <span>Já as ações internacionais mantêm uma correlação quase nula (<span class="text-stone-gold font-medium italic">-0,01</span>), demonstrando que acompanham o aumento de preços.</span>
                        </li>
                    </ul>
                    <p class="mt-4">
                        A estratégia substitui os títulos pela diversificação geográfica em ações. Esta forma de diversificação oferece benefícios de redução de risco superiores aos títulos no longo prazo, pois permite ao investidor fugir de pressões inflacionárias específicas de um único país.
                    </p>
                </div>

                <!-- Conclusion -->
                <div class="border-t border-stone-glassBorder/30 pt-8 mt-12">
                    <h2 class="font-playfair text-2xl md:text-3xl font-bold text-white border-l-4 border-stone-gold pl-4 mb-4">
                        Conclusão
                    </h2>
                    <p class="text-lg">
                        Investir por 30 anos em uma carteira 100% ações não é apenas uma estratégia de crescimento, mas a forma mais eficiente de proteção de capital real. O investidor aceita uma volatilidade intermediária maior (drawdowns que podem chegar a 73% em cenários extremos) em troca de uma probabilidade significativamente menor de insolvência na velhice e de um patrimônio final substancialmente superior.
                    </p>
                </div>

                <!-- Footer Sign -->
                <div class="text-center text-sm font-semibold tracking-widest text-stone-gold uppercase pt-8">
                    Stone Edger — Investindo Além do Status Quo
                </div>

            </article>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-stone-navy/80 backdrop-blur-lg pt-16 pb-8 border-t border-stone-gold/30 mt-auto">
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
                        <li><a href="index.php#home"
                                class="text-stone-gray hover:text-stone-gold transition-colors font-light">Home</a></li>
                        <li><a href="index.php#sobre"
                                class="text-stone-gray hover:text-stone-gold transition-colors font-light">Sobre Nós</a>
                        </li>
                        <li><a href="index.php#atuacao"
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

    <!-- Modal: MEU PERFIL -->
    <div id="profileModal" class="fixed inset-0 z-[200] hidden">
        <div class="absolute inset-0 bg-stone-navy/90 backdrop-blur-md"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div
                class="bg-stone-navy border border-stone-glassBorder rounded-2xl w-full max-w-md overflow-hidden shadow-2xl animate-modal-in">
                <div class="p-6 border-b border-stone-glassBorder flex justify-between items-center bg-stone-glass">
                    <h3 class="text-stone-gold font-playfair text-xl font-bold italic">Meu Perfil</h3>
                    <button onclick="closeProfileModal()" class="text-stone-gray hover:text-white transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <div class="p-8 flex flex-col items-center gap-6">
                    <div class="relative group">
                        <div id="profile-modal-avatar"
                            class="w-32 h-32 rounded-full border-4 border-stone-gold shadow-2xl bg-cover bg-center overflow-hidden"
                            style='background-image: url("<?php echo $avatar_url; ?>");'>
                        </div>
                        <?php if ($isLoggedIn): ?>
                            <label for="avatar-input"
                                class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-full opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity">
                                <span class="material-symbols-outlined text-white text-3xl"
                                    style="font-size: 32px;">photo_camera</span>
                            </label>
                            <input type="file" id="avatar-input" class="hidden" accept="image/*"
                                onchange="handleAvatarUpload(this)">
                        <?php endif; ?>
                    </div>

                    <div class="w-full text-center">
                        <h4 id="profile-display-name" class="text-xl font-bold text-white mb-1">
                            <?php echo $user_name; ?>
                        </h4>
                        <p class="text-stone-gray text-sm mb-4"><?php echo $user_email; ?></p>

                        <div class="h-px w-full bg-stone-glassBorder my-6"></div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-stone-glass p-3 rounded-xl border border-stone-glassBorder">
                                <span class="text-[10px] text-stone-gray uppercase block mb-1 font-bold">Status</span>
                                <span class="text-xs font-bold text-success uppercase tracking-widest">Ativo</span>
                            </div>
                            <div class="bg-stone-glass p-3 rounded-xl border border-stone-glassBorder">
                                <span class="text-[10px] text-stone-gray uppercase block mb-1 font-bold">Plano</span>
                                <span class="text-xs font-bold text-stone-gold uppercase tracking-widest">Premium</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="upload-status" class="px-8 pb-6 text-center hidden">
                    <div
                        class="flex items-center justify-center gap-2 text-stone-gold text-xs border border-stone-gold/20 bg-stone-gold/5 py-2 rounded-lg">
                        <i class="fas fa-circle-notch fa-spin"></i>
                        <span id="upload-status-text">Enviando nova foto...</span>
                    </div>
                </div>

                <div class="p-4 bg-stone-glass text-center border-t border-stone-glassBorder">
                    <button onclick="closeProfileModal()"
                        class="px-8 py-2.5 bg-stone-gold hover:bg-stone-goldHover text-stone-navy text-[10px] font-bold rounded-full transition-colors uppercase tracking-widest shadow-lg shadow-stone-gold/20">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
    <script>
        const SUPABASE_URL = "https://puxuilkexmjpjnrkqysq.supabase.co";
        const SUPABASE_KEY = "sb_publishable_EtvYR3UkvESNn-Ci2MuzrQ_cJYoTOJF";
        const _supabase = supabase.createClient(SUPABASE_URL, SUPABASE_KEY);

        // Mobile Menu Logic
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuBtn?.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Profile Menu Logic (Desktop)
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userDropdown = document.getElementById('userDropdown');

        userMenuBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown?.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (userDropdown && !userMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.classList.add('hidden');
            }
        });

        // Profile Modal Logic
        function openProfileModal() {
            document.getElementById('profileModal').classList.remove('hidden');
            document.getElementById('userDropdown')?.classList.add('hidden');
            document.getElementById('mobile-menu')?.classList.add('hidden');
        }

        function closeProfileModal() {
            document.getElementById('profileModal').classList.add('hidden');
        }

        async function handleAvatarUpload(input) {
            if (!input.files || !input.files[0]) return;

            const file = input.files[0];
            const userId = "<?php echo $user_id; ?>";
            const fileExt = file.name.split('.').pop();
            const fileName = `${userId}/${Date.now()}.${fileExt}`;

            const statusDiv = document.getElementById('upload-status');
            const statusText = document.getElementById('upload-status-text');
            statusDiv.classList.remove('hidden');
            statusText.textContent = "Processando imagem...";

            try {
                // 0. Ensure session is active in JS
                const { data: { session: currentSession } } = await _supabase.auth.getSession();
                if (!currentSession) {
                    statusText.textContent = "Reautenticando...";
                    await _supabase.auth.setSession({
                        access_token: "<?php echo $_SESSION['access_token'] ?? ''; ?>",
                        refresh_token: ""
                    });
                }

                // 1. Upload to Supabase Storage
                statusText.textContent = "Fazendo upload...";
                const { data, error: uploadError } = await _supabase.storage
                    .from('avatars')
                    .upload(fileName, file, {
                        cacheControl: '3600',
                        upsert: true
                    });

                if (uploadError) {
                    console.error('Supabase Storage Error:', uploadError);
                    throw new Error(`Erro no upload: ${uploadError.message}`);
                }

                // 2. Get Public URL
                const { data: { publicUrl } } = _supabase.storage
                    .from('avatars')
                    .getPublicUrl(fileName);

                statusText.textContent = "Sincronizando perfil...";

                // 3. Update User Metadata
                const { error: updateError } = await _supabase.auth.updateUser({
                    data: { avatar_url: publicUrl }
                });

                if (updateError) throw updateError;

                // 4. Sync with PHP Session
                const formData = new FormData();
                formData.append('access_token', "<?php echo $_SESSION['access_token'] ?? ''; ?>");
                formData.append('user_id', userId);
                formData.append('email', "<?php echo $user_email; ?>");
                formData.append('full_name', "<?php echo $user_name; ?>");
                formData.append('avatar_url', publicUrl);

                await fetch('api/auth_sync.php', {
                    method: 'POST',
                    body: formData
                });

                // 5. Update UI
                const avatarElements = [
                    document.getElementById('nav-avatar'),
                    document.getElementById('dropdown-avatar'),
                    document.getElementById('profile-modal-avatar')
                ];

                avatarElements.forEach(el => {
                    if (el) el.style.backgroundImage = `url("${publicUrl}")`;
                });

                statusText.textContent = "Sucesso! Perfil atualizado.";
                setTimeout(() => {
                    statusDiv.classList.add('hidden');
                }, 2000);

            } catch (err) {
                console.error('Upload error:', err);
                statusText.textContent = `Erro: ${err.message || 'Falha na conexão'}`;
                statusText.parentElement.classList.replace('text-stone-gold', 'text-danger');
            }
        }

        // --- Navbar Scroll Effect ---
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
    </script>
</body>

</html>
