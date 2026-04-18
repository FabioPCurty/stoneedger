<?php
require_once 'api/session_handler.php';

// Protection: Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user_email = $_SESSION['user_email'];
$user_name = $_SESSION['user_name'] ?? explode('@', $user_email)[0];
$avatar_url = $_SESSION['avatar_url'] ?? '';
if (empty($avatar_url)) {
    $avatar_url = 'https://lh3.googleusercontent.com/aida-public/AB6AXuCTCifV9f7veeImD6mpBg5MYpyLXZuX0Wn-PekVpNu3vhVQG721dQEl5WbsrR0o1vraCZDBH5trp5oRZRL1eoPcs3dQ2f-TLvIbK0zrlOY8h0HhQ2cwU_AEwwuY_aTR73AIIqfDUGiolLRlNIFv2tosDtVNg9Of2mQ6U3go3M0Stl4z-ovMmuKmAZstI_VMgVwz4eMj131GaJWanBRhtp4sq_-iwpm3rpvT2lnUsLqCG5sWw3sBN2vvSkwzE6IoKjRM1kJVgZGQng0';
}
?>
<!DOCTYPE html>
<html lang="pt-BR" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mercado | Stone Edger</title>
    <meta name="description" content="Inteligência de Mercado Stone Edger">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>

    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
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
                        success: "#10b981", 
                        danger: "#ef4444" 
                    },
                    fontFamily: {
                        playfair: ['"Playfair Display"', 'serif'],
                        montserrat: ['"Montserrat"', 'sans-serif'],
                        headline: ["Manrope"],
                        body: ["Inter"]
                    },
                    backgroundImage: {
                        'gradient-gold': 'linear-gradient(135deg, #D4AF37, #b39020)',
                    },
                }
            }
        }
    </script>

    <style>
        html { scroll-behavior: smooth; }
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
            background-image: url('img/bg.jpg'), url('https://images.unsplash.com/photo-1611974765270-ca1258634369?ixlib=rb-4.0.3&auto=format&fit=crop&w=1080&q=80');
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
            background: rgba(0, 0, 0, 0.99);
            z-index: -1;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #050a14; }
        ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.4); }
    </style>
</head>

<body class="antialiased selection:bg-stone-gold selection:text-stone-navy flex flex-col min-h-screen">

    <!-- Elementos de Fundo -->
    <div class="fixed-bg"></div>
    <div class="bg-overlay"></div>

    <div class="relative flex min-h-screen w-full flex-col group/design-root">

        <!-- Header -->
        <header id="main-header" class="fixed w-full z-50 transition-all duration-300 py-4 bg-stone-navy/80 backdrop-blur-md border-b border-stone-glassBorder">
            <div class="container mx-auto px-6 flex justify-between items-center">

                <!-- Logo -->
                <a href="index.php" class="font-playfair text-2xl md:text-3xl font-bold text-white tracking-wider flex items-center gap-2 group">
                    <div class="w-10 h-10 border-2 border-stone-gold flex items-center justify-center rounded-full group-hover:shadow-[0_0_15px_rgba(212,175,55,0.6)] transition-all duration-300">
                        <span class="text-stone-gold font-serif italic text-xl">S</span>
                    </div>
                    <span>STONE <span class="text-stone-gold">EDGER</span></span>
                </a>

                <!-- Desktop Menu -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="dashboard.php" class="text-stone-gray hover:text-stone-gold transition-colors text-sm uppercase tracking-widest font-medium">Portfólio</a>
                    <a href="analise.php" class="text-stone-gray hover:text-stone-gold transition-colors text-sm uppercase tracking-widest font-medium">Análise</a>
                    <a href="mercado.php" class="text-stone-gold transition-colors text-sm uppercase tracking-widest font-bold border-b-2 border-stone-gold pb-1">Mercado</a>
                    <a href="#" class="text-stone-gray hover:text-stone-gold transition-colors text-sm uppercase tracking-widest font-medium">Configurações</a>
                </nav>

                <!-- User Profile & Hamburger -->
                <div class="flex items-center gap-4">
                    <div class="hidden md:flex items-center gap-4">
                        <a href="logout.php" class="min-w-[84px] inline-flex cursor-pointer items-center justify-center overflow-hidden rounded-lg h-9 px-4 bg-stone-glass hover:bg-stone-glassBorder text-white text-xs font-bold transition-colors border border-stone-glassBorder uppercase tracking-wider">
                            <span>Sair</span>
                        </a>

                        <div class="relative">
                            <button id="userMenuBtn" class="flex items-center focus:outline-none group">
                                <div id="nav-avatar" class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-9 ring-2 <?php echo empty($_SESSION['investor_profile'] ?? '') ? 'ring-red-500 animate-pulse shadow-[0_0_15px_rgba(239,68,68,0.6)]' : 'ring-stone-gold shadow-[0_0_15px_rgba(212,175,55,0.3)] group-hover:ring-offset-2 group-hover:ring-offset-stone-navy group-hover:ring-stone-goldHover'; ?> transition-all" style='background-image: url("<?php echo $avatar_url; ?>");'>
                                </div>
                            </button>
                        </div>
                    </div>

                    <button id="mobile-menu-btn" class="md:hidden text-stone-gold text-2xl focus:outline-none">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 flex justify-center py-6 px-4 sm:px-6 lg:px-8 mt-24">
            <div class="w-full max-w-[1440px] flex flex-col gap-6">

                <!-- Header Section -->
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between bg-stone-glass p-6 rounded-2xl border border-stone-glassBorder">
                    <div class="flex flex-col gap-1.5">
                        <h1 class="text-white text-3xl md:text-4xl font-playfair font-bold leading-tight tracking-tight text-white mb-2">Market Intelligence Dashboard</h1>
                        <p class="text-stone-gray font-medium">Daily Institutional Overview & Strategic Ledger</p>
                    </div>
                </div>

                <!-- Bento Grid: B3 Indices & International Indices -->
                <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
                    
                    <!-- B3 Indices Section -->
                    <section class="xl:col-span-7 bg-stone-glass border border-stone-glassBorder rounded-2xl p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="font-playfair text-xl font-bold tracking-tight text-white">Índices B3</h2>
                            <span class="text-xs font-bold uppercase tracking-widest text-stone-gray">Live Data • B3.SA</span>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4">
                            <!-- Cards with subtle tonal shift - No Borders Rule -->
                            <div class="bg-stone-navy/40 p-4 rounded-xl shadow-sm border border-stone-glassBorder hover:border-stone-gold/50 transition-colors">
                                <span class="block text-[10px] font-bold uppercase text-stone-gray mb-1 tracking-widest">IBOV</span>
                                <span id="val-IBOV" class="text-lg font-headline font-bold text-stone-gray animate-pulse">Carregando...</span>
                            </div>
                            <div class="bg-stone-navy/40 p-4 rounded-xl shadow-sm border border-stone-glassBorder hover:border-stone-gold/50 transition-colors">
                                <span class="block text-[10px] font-bold uppercase text-stone-gray mb-1 tracking-widest">IDIV</span>
                                <span id="val-IDIV" class="text-lg font-headline font-bold text-stone-gray animate-pulse">Carregando...</span>
                            </div>
                            <div class="bg-stone-navy/40 p-4 rounded-xl shadow-sm border border-stone-glassBorder hover:border-stone-gold/50 transition-colors">
                                <span class="block text-[10px] font-bold uppercase text-stone-gray mb-1 tracking-widest">SMLL</span>
                                <span id="val-SMLL" class="text-lg font-headline font-bold text-stone-gray animate-pulse">Carregando...</span>
                            </div>
                            <div class="bg-stone-navy/40 p-4 rounded-xl shadow-sm border border-stone-glassBorder hover:border-stone-gold/50 transition-colors">
                                <span class="block text-[10px] font-bold uppercase text-stone-gray mb-1 tracking-widest">IFIX</span>
                                <span id="val-IFIX" class="text-lg font-headline font-bold text-stone-gray animate-pulse">Carregando...</span>
                            </div>
                            <div class="bg-stone-navy/40 p-4 rounded-xl shadow-sm border border-stone-glassBorder hover:border-stone-gold/50 transition-colors">
                                <span class="block text-[10px] font-bold uppercase text-stone-gray mb-1 tracking-widest">IFNC</span>
                                <span id="val-IFNC" class="text-lg font-headline font-bold text-stone-gray animate-pulse">Carregando...</span>
                            </div>
                            <div class="bg-stone-navy/40 p-4 rounded-xl shadow-sm border border-stone-glassBorder hover:border-stone-gold/50 transition-colors">
                                <span class="block text-[10px] font-bold uppercase text-stone-gray mb-1 tracking-widest">IMAT</span>
                                <span id="val-IMAT" class="text-lg font-headline font-bold text-stone-gray animate-pulse">Carregando...</span>
                            </div>
                            <div class="bg-stone-navy/40 p-4 rounded-xl shadow-sm border border-stone-glassBorder hover:border-stone-gold/50 transition-colors">
                                <span class="block text-[10px] font-bold uppercase text-stone-gray mb-1 tracking-widest">IGCT</span>
                                <span id="val-IGCT" class="text-lg font-headline font-bold text-stone-gray animate-pulse">Carregando...</span>
                            </div>
                            <div class="bg-stone-navy/40 p-4 rounded-xl shadow-sm border border-stone-glassBorder hover:border-stone-gold/50 transition-colors">
                                <span class="block text-[10px] font-bold uppercase text-stone-gray mb-1 tracking-widest">ISE</span>
                                <span id="val-ISE" class="text-lg font-headline font-bold text-stone-gray animate-pulse">Carregando...</span>
                            </div>
                        </div>
                    </section>

                    <!-- International Indices Section -->
                    <section class="xl:col-span-5 bg-stone-glass border border-stone-glassBorder rounded-2xl p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="font-playfair text-xl font-bold tracking-tight text-white">Mercados Internacionais</h2>
                            <span class="material-symbols-outlined text-stone-gold" data-icon="public">public</span>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <div class="bg-stone-navy/40 p-4 rounded-xl shadow-sm border border-stone-glassBorder flex flex-col hover:border-stone-gold/50 transition-colors">
                                <span class="text-[10px] tracking-widest font-bold uppercase text-stone-gray">DJI</span>
                                <span id="val-DJI" class="text-md font-headline font-bold text-stone-gray animate-pulse">Carregando...</span>
                            </div>
                            <div class="bg-stone-navy/40 p-4 rounded-xl shadow-sm border border-stone-glassBorder flex flex-col hover:border-stone-gold/50 transition-colors">
                                <span class="text-[10px] tracking-widest font-bold uppercase text-stone-gray">S&P500</span>
                                <span id="val-SP500" class="text-md font-headline font-bold text-stone-gray animate-pulse">Carregando...</span>
                            </div>
                            <div class="bg-stone-navy/40 p-4 rounded-xl shadow-sm border border-stone-glassBorder flex flex-col hover:border-stone-gold/50 transition-colors">
                                <span class="text-[10px] tracking-widest font-bold uppercase text-stone-gray">NASDAQ</span>
                                <span id="val-NASDAQ" class="text-md font-headline font-bold text-stone-gray animate-pulse">Carregando...</span>
                            </div>
                            <div class="bg-stone-navy/40 p-4 rounded-xl shadow-sm border border-stone-glassBorder flex flex-col hover:border-stone-gold/50 transition-colors">
                                <span class="text-[10px] tracking-widest font-bold uppercase text-stone-gray">FTSE100</span>
                                <span id="val-FTSE100" class="text-md font-headline font-bold text-stone-gray animate-pulse">Carregando...</span>
                            </div>
                            <div class="bg-stone-navy/40 p-4 rounded-xl shadow-sm border border-stone-glassBorder flex flex-col hover:border-stone-gold/50 transition-colors">
                                <span class="text-[10px] tracking-widest font-bold uppercase text-stone-gray">NIKKEI</span>
                                <span id="val-NIKKEI" class="text-md font-headline font-bold text-stone-gray animate-pulse">Carregando...</span>
                            </div>
                            <div class="bg-stone-navy/40 p-4 rounded-xl shadow-sm border border-stone-glassBorder flex flex-col hover:border-stone-gold/50 transition-colors">
                                <span class="text-[10px] tracking-widest font-bold uppercase text-stone-gray">DAX</span>
                                <span id="val-DAX" class="text-md font-headline font-bold text-stone-gray animate-pulse">Carregando...</span>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Dividend Calendar & Relevant Facts -->
                <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
                    <!-- Dividend Calendar Section -->
                    <section class="xl:col-span-8 bg-stone-glass border border-stone-glassBorder rounded-2xl overflow-hidden shadow-sm flex flex-col">
                        <div class="p-6 bg-stone-navy/30 border-b border-stone-glassBorder flex justify-between items-center">
                            <h2 class="font-playfair text-xl font-bold tracking-tight text-white">Calendário de Dividendos</h2>
                            <div class="flex space-x-2">
                                <button class="p-1 text-stone-gold hover:text-white transition-colors"><span class="material-symbols-outlined" data-icon="filter_list">filter_list</span></button>
                                <button class="p-1 text-stone-gold hover:text-white transition-colors"><span class="material-symbols-outlined" data-icon="download">download</span></button>
                            </div>
                        </div>
                        <div class="overflow-x-auto flex-1 p-6 pt-0">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr>
                                        <th class="px-2 py-4 text-[10px] font-extrabold uppercase tracking-widest text-stone-gray">Código</th>
                                        <th class="px-2 py-4 text-[10px] font-extrabold uppercase tracking-widest text-stone-gray">Tipo</th>
                                        <th class="px-2 py-4 text-[10px] font-extrabold uppercase tracking-widest text-stone-gray">Valor (R$)</th>
                                        <th class="px-2 py-4 text-[10px] font-extrabold uppercase tracking-widest text-stone-gray">Registro</th>
                                        <th class="px-2 py-4 text-[10px] font-extrabold uppercase tracking-widest text-stone-gray">DataEx</th>
                                        <th class="px-2 py-4 text-[10px] font-extrabold uppercase tracking-widest text-stone-gray">Pagamento</th>
                                    </tr>
                                </thead>
                                <tbody id="dividend-table-body" class="divide-y divide-stone-glassBorder/50">
                                    <tr class="hover:bg-stone-navy/30 transition-colors animate-pulse">
                                        <td class="px-2 py-4 font-bold text-sm text-stone-gray">---</td>
                                        <td class="px-2 py-4 text-sm text-stone-gray">Carregando...</td>
                                        <td class="px-2 py-4 text-sm text-stone-gray">---</td>
                                        <td class="px-2 py-4 text-sm text-stone-gray">---</td>
                                        <td class="px-2 py-4 text-sm text-stone-gray">---</td>
                                        <td class="px-2 py-4 text-sm text-stone-gray">---</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <!-- Relevant Facts (Fatos Relevantes) -->
                    <section class="xl:col-span-4 flex flex-col gap-6">
                        <div class="bg-stone-glass border border-stone-glassBorder rounded-2xl p-6 flex-1">
                            <h2 class="font-playfair text-xl font-bold tracking-tight text-white mb-6">Fatos Relevantes</h2>
                            <div class="space-y-4">
                                <div class="p-4 bg-stone-navy/40 border border-stone-glassBorder rounded-xl hover:border-stone-gold/50 transition-all cursor-pointer group">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="text-[9px] tracking-widest font-bold text-success uppercase border border-success/30 bg-success/10 px-2 py-0.5 rounded">AQUISIÇÃO</span>
                                        <span class="text-xs text-stone-gray">10:24 AM</span>
                                    </div>
                                    <h3 class="font-bold text-sm text-white group-hover:text-stone-gold transition-colors mb-1">MGLU3: Parceria Estratégica Logística</h3>
                                    <p class="text-xs text-stone-gray line-clamp-2">Magazine Luiza anuncia acordo vinculante para a integração de novos centros de distribuição regionais...</p>
                                </div>
                                <div class="p-4 bg-stone-navy/40 border border-stone-glassBorder rounded-xl hover:border-stone-gold/50 transition-all cursor-pointer group">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="text-[9px] tracking-widest font-bold text-stone-gold uppercase border border-stone-gold/30 bg-stone-gold/10 px-2 py-0.5 rounded">DIVIDENDOS</span>
                                        <span class="text-xs text-stone-gray">09:15 AM</span>
                                    </div>
                                    <h3 class="font-bold text-sm text-white group-hover:text-stone-gold transition-colors mb-1">BBDC4: Atualização Conselho Adm.</h3>
                                    <p class="text-xs text-stone-gray line-clamp-2">O conselho aprovou a distribuição de juros sobre capital próprio complementares baseados nas reservas...</p>
                                </div>
                                <div class="p-4 bg-stone-navy/40 border border-stone-glassBorder rounded-xl hover:border-stone-gold/50 transition-all cursor-pointer group">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="text-[9px] tracking-widest font-bold text-danger uppercase border border-danger/30 bg-danger/10 px-2 py-0.5 rounded">COMPLIANCE</span>
                                        <span class="text-xs text-stone-gray">Ontem</span>
                                    </div>
                                    <h3 class="font-bold text-sm text-white group-hover:text-stone-gold transition-colors mb-1">PETR4: Relatório Operacional</h3>
                                    <p class="text-xs text-stone-gray line-clamp-2">Arquivamento oficial referente às metas de produção para a camada do pré-sal para o próximo quadriênio...</p>
                                </div>
                            </div>
                            <button class="w-full mt-6 py-3 text-xs font-bold uppercase tracking-widest text-stone-gray hover:text-white hover:bg-stone-glass transition-colors rounded-xl border border-stone-glassBorder">Ver Todos. os Fatos</button>
                        </div>
                    </section>
                </div>

                <!-- Investment Flow Section -->
                <section class="bg-stone-glass border border-stone-glassBorder rounded-2xl p-6 lg:p-8">
                    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                        <div>
                            <h2 class="font-playfair text-2xl font-bold tracking-tight text-white mb-1">Fluxo de Investimentos</h2>
                            <p class="text-sm text-stone-gray">Distribuição de Capital Institucional Global e Varejo (Últimos 5 Dias)</p>
                        </div>
                        <div class="flex items-center space-x-4 border border-stone-glassBorder px-4 py-2 rounded-lg bg-stone-navy/30">
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 bg-stone-gold rounded-full"></span>
                                <span class="text-xs font-medium text-white uppercase tracking-widest">Estrangeiros</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 bg-stone-gray rounded-full"></span>
                                <span class="text-xs font-medium text-white uppercase tracking-widest">Institucional</span>
                            </div>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-end">
                        <!-- Data Table -->
                        <div class="lg:col-span-8 overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr>
                                        <th class="pb-4 text-[10px] font-extrabold uppercase tracking-widest text-stone-gray">Data</th>
                                        <th class="pb-4 text-[10px] font-extrabold uppercase tracking-widest text-stone-gray text-right">Estrangeiros</th>
                                        <th class="pb-4 text-[10px] font-extrabold uppercase tracking-widest text-stone-gray text-right">Institucional</th>
                                        <th class="pb-4 text-[10px] font-extrabold uppercase tracking-widest text-stone-gray text-right">Pessoa Física</th>
                                        <th class="pb-4 text-[10px] font-extrabold uppercase tracking-widest text-stone-gray text-right">Inst. Financeira</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-stone-glassBorder/50">
                                    <tr class="hover:bg-stone-navy/30 transition-colors">
                                        <td class="py-4 text-sm font-medium text-white">20/10</td>
                                        <td class="py-4 text-sm text-right text-success font-mono font-bold">+1.240M</td>
                                        <td class="py-4 text-sm text-right text-danger font-mono font-bold">-450M</td>
                                        <td class="py-4 text-sm text-right font-mono text-white">+120M</td>
                                        <td class="py-4 text-sm text-right font-mono text-white">+32M</td>
                                    </tr>
                                    <tr class="hover:bg-stone-navy/30 transition-colors">
                                        <td class="py-4 text-sm font-medium text-white">19/10</td>
                                        <td class="py-4 text-sm text-right text-success font-mono font-bold">+890M</td>
                                        <td class="py-4 text-sm text-right text-success font-mono font-bold">+112M</td>
                                        <td class="py-4 text-sm text-right font-mono text-white">-45M</td>
                                        <td class="py-4 text-sm text-right font-mono text-white">+12M</td>
                                    </tr>
                                    <tr class="hover:bg-stone-navy/30 transition-colors">
                                        <td class="py-4 text-sm font-medium text-white">18/10</td>
                                        <td class="py-4 text-sm text-right text-danger font-mono font-bold">-210M</td>
                                        <td class="py-4 text-sm text-right text-success font-mono font-bold">+670M</td>
                                        <td class="py-4 text-sm text-right font-mono text-white">+302M</td>
                                        <td class="py-4 text-sm text-right font-mono text-white">-88M</td>
                                    </tr>
                                    <tr class="hover:bg-stone-navy/30 transition-colors">
                                        <td class="py-4 text-sm font-medium text-white">17/10</td>
                                        <td class="py-4 text-sm text-right text-success font-mono font-bold">+1.560M</td>
                                        <td class="py-4 text-sm text-right text-danger font-mono font-bold">-1.120M</td>
                                        <td class="py-4 text-sm text-right font-mono text-white">+440M</td>
                                        <td class="py-4 text-sm text-right font-mono text-white">+156M</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Modern "Bar Chart" Visualization -->
                        <div class="lg:col-span-4 bg-stone-navy/40 p-6 rounded-xl shadow-sm border border-stone-glassBorder h-64 flex flex-col justify-between">
                            <div class="flex justify-between items-end h-32 space-x-2">
                                <div class="flex flex-col items-center flex-1 space-y-2">
                                    <div class="w-full bg-stone-gold h-24 rounded-t-sm shadow-[0_0_10px_rgba(212,175,55,0.4)]"></div>
                                    <span class="text-[10px] font-bold text-stone-gray">20/10</span>
                                </div>
                                <div class="flex flex-col items-center flex-1 space-y-2">
                                    <div class="w-full bg-stone-gold/80 h-16 rounded-t-sm"></div>
                                    <span class="text-[10px] font-bold text-stone-gray">19/10</span>
                                </div>
                                <div class="flex flex-col items-center flex-1 space-y-2">
                                    <div class="w-full bg-stone-gray/50 h-8 rounded-t-sm"></div>
                                    <span class="text-[10px] font-bold text-stone-gray">18/10</span>
                                </div>
                                <div class="flex flex-col items-center flex-1 space-y-2">
                                    <div class="w-full bg-stone-gold h-32 rounded-t-sm shadow-[0_0_10px_rgba(212,175,55,0.4)]"></div>
                                    <span class="text-[10px] font-bold text-stone-gray">17/10</span>
                                </div>
                                <div class="flex flex-col items-center flex-1 space-y-2">
                                    <div class="w-full bg-stone-gold/40 h-12 rounded-t-sm"></div>
                                    <span class="text-[10px] font-bold text-stone-gray">16/10</span>
                                </div>
                            </div>
                            <div class="pt-4 border-t border-stone-glassBorder">
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] font-bold text-stone-gray uppercase tracking-widest">Saldo Semanal</span>
                                    <span class="text-lg font-headline font-bold text-success">+R$ 3.48B</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Fetch B3 Indices
            fetch('api/get_b3_indices.php')
                .then(response => response.json())
                .then(data => {
                    if(data.results) {
                        const mapTickers = {
                            'BOVA11': 'IBOV',
                            'DIVO11': 'IDIV',
                            'SMAL11': 'SMLL',
                            'XFIX11': 'IFIX',
                            'FIND11': 'IFNC',
                            'MATB11': 'IMAT',
                            'GOVE11': 'IGCT',
                            'ISUS11': 'ISE'
                        };
                        
                        data.results.forEach(item => {
                            const domId = mapTickers[item.symbol];
                            if(domId) {
                                const valEl = document.getElementById('val-' + domId);
                                if(valEl) {
                                    valEl.classList.remove('animate-pulse');
                                    const pct = item.regularMarketChangePercent;
                                    const sign = pct > 0 ? '+' : '';
                                    valEl.innerText = sign + pct.toFixed(2) + '%';
                                    
                                    valEl.classList.remove('text-success', 'text-danger', 'text-stone-gray');
                                    if (pct > 0) valEl.classList.add('text-success');
                                    else if (pct < 0) valEl.classList.add('text-danger');
                                    else valEl.classList.add('text-stone-gray');
                                }
                            }
                        });
                    }
                })
                .catch(err => {
                    console.error("Erro ao carregar índices B3", err);
                    ['IBOV','IDIV','SMLL','IFIX','IFNC','IMAT','IGCT','ISE'].forEach(id => {
                        const el = document.getElementById('val-'+id);
                        if(el && el.innerText.includes('Carregando')) el.innerText = 'Erro API';
                    });
                });

            // Fetch International Indices
            fetch('api/get_intl_indices.php')
                .then(response => response.json())
                .then(data => {
                    if(data.results) {
                        const mapTickersIntl = {
                            '^DJI': 'DJI',
                            '^GSPC': 'SP500',
                            '^IXIC': 'NASDAQ',
                            '^FTSE': 'FTSE100',
                            '^N225': 'NIKKEI',
                            '^GDAXI': 'DAX'
                        };
                        
                        data.results.forEach(item => {
                            const domId = mapTickersIntl[item.symbol];
                            if(domId) {
                                const valEl = document.getElementById('val-' + domId);
                                if(valEl) {
                                    valEl.classList.remove('animate-pulse');
                                    const pct = item.regularMarketChangePercent;
                                    const sign = pct > 0 ? '+' : '';
                                    valEl.innerText = sign + pct.toFixed(2) + '%';
                                    
                                    valEl.classList.remove('text-success', 'text-danger', 'text-stone-gray');
                                    if (pct > 0) valEl.classList.add('text-success');
                                    else if (pct < 0) valEl.classList.add('text-danger');
                                    else valEl.classList.add('text-stone-gray');
                                }
                            }
                        });
                    }
                })
                .catch(err => {
                    console.error("Erro ao carregar índices Internacionais", err);
                    ['DJI','SP500','NASDAQ','FTSE100','NIKKEI','DAX'].forEach(id => {
                        const el = document.getElementById('val-'+id);
                        if(el && el.innerText.includes('Carregando')) el.innerText = 'Erro API';
                    });
                });

            // Fetch Dividend Calendar
            fetch('api/get_dividendos.php')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('dividend-table-body');
                    if (data.results && data.results.length > 0) {
                        tbody.innerHTML = ''; // Create empty HTML initially
                        
                        data.results.forEach((div, index) => {
                            // Date formatting manually via strings
                            const formatDt = (dateStr) => {
                                if(!dateStr) return '---';
                                const dt = new Date(dateStr);
                                return dt.toLocaleDateString('pt-BR');
                            };
                            
                            const valFmt = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 4 }).format(div.value);
                            const stripeClass = index % 2 === 1 ? 'bg-stone-glass/5' : '';
                            const isJcp = div.type.toUpperCase().includes('JCP') ? 'text-stone-gold' : 'text-success';

                            const tr = document.createElement('tr');
                            tr.className = `hover:bg-stone-navy/30 transition-colors ${stripeClass}`;
                            tr.innerHTML = `
                                <td class="px-2 py-4 font-bold text-sm text-white">${div.ticker}</td>
                                <td class="px-2 py-4 text-sm flex items-center gap-1 font-bold ${isJcp}">${div.type.replace('RENDIMENTO', 'DIV').replace('JCP', 'JCP')}</td>
                                <td class="px-2 py-4 font-mono text-sm text-white">${valFmt}</td>
                                <td class="px-2 py-4 text-sm text-stone-gray">${formatDt(div.approvedOn)}</td>
                                <td class="px-2 py-4 text-sm text-stone-gray">${formatDt(div.lastDatePrior)}</td>
                                <td class="px-2 py-4 text-sm font-semibold text-white">${formatDt(div.paymentDate)}</td>
                            `;
                            tbody.appendChild(tr);
                        });
                    } else {
                        tbody.innerHTML = '<tr><td colspan="6" class="px-2 py-4 text-sm text-stone-gray text-center">Nenhum dividendo recente encontrado na API.</td></tr>';
                    }
                })
                .catch(err => {
                    console.error("Erro ao carregar dividendos", err);
                    document.getElementById('dividend-table-body').innerHTML = '<tr><td colspan="6" class="px-2 py-4 text-sm text-danger text-center">Erro ao comunicar com o servidor.</td></tr>';
                });
        });
    </script>
</body>
</html>
