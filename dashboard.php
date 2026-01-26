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
    <title>Dashboard | Stone Edger</title>
    <meta name="description" content="Dashboard Financeiro Stone Edger - Acompanhe seus investimentos.">

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
    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #050a14;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        .chart-placeholder {
            min-height: 300px;
            position: relative;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 50px 50px;
        }

        .chart-placeholder::after {
            content: 'Gráfico de Performance (Últimos 12 Meses) - Placeholder';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #999;
            font-size: 1rem;
            font-weight: 500;
        }

        /* --- Speedometer Gauge Styles --- */
        .graph-container {
            --size: 10rem;
            --stroke-size: calc(var(--size)/5);
            --rating: 2.5;
            position: relative;
            display: inline-flex;
            box-sizing: border-box;
            transition: all 0.5s ease;
        }

        .half-donut {
            width: var(--size);
            height: calc(var(--size)/2);
            border-radius: var(--size) var(--size) 0 0;
            position: relative;
            overflow: hidden;
            filter: drop-shadow(0 0 0.3rem #0005);
        }

        .slice {
            --stroke-color: #000;
            --rotate: 0deg;
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: var(--size) var(--size) 0 0;
            border: var(--stroke-size) solid var(--stroke-color);
            box-sizing: border-box;
            border-bottom: none;
            transform-origin: 50% 100%;
            background: #0000;
            transform: rotate(calc(var(--rotate)));
        }

        .slice .fa-regular {
            font-size: 1rem;
            color: #fff;
            position: absolute;
            left: -1.2rem;
            top: 1.2rem;
            --emo-rotate: 90deg;
            transform: rotate(var(--emo-rotate));
            text-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
        }

        .slice:nth-child(1) {
            --stroke-color: #10b981;
            --rotate: 0deg;
        }

        .slice:nth-child(1) .fa-regular {
            --emo-rotate: 0deg;
        }

        .slice:nth-child(2) {
            --stroke-color: #6ee7b7;
            --rotate: 36deg;
        }

        .slice:nth-child(2) .fa-regular {
            --emo-rotate: -36deg;
        }

        .slice:nth-child(3) {
            --stroke-color: #f59e0b;
            --rotate: 72deg;
        }

        .slice:nth-child(3) .fa-regular {
            --emo-rotate: -72deg;
        }

        .slice:nth-child(4) {
            --stroke-color: #f87171;
            --rotate: 108deg;
        }

        .slice:nth-child(4) .fa-regular {
            --emo-rotate: -108deg;
        }

        .slice:nth-child(5) {
            --stroke-color: #ef4444;
            --rotate: 144deg;
        }

        .slice:nth-child(5) .fa-regular {
            --emo-rotate: -144deg;
        }

        .marker {
            position: absolute;
            z-index: 10;
            transform: translateX(-50%);
            --round-size: calc(var(--size) / 10);
            --round-o-size: calc(var(--round-size)* 0.32);
            width: var(--round-size);
            height: var(--round-size);
            left: 50%;
            bottom: 0;
            border: var(--round-o-size) solid #fff;
            border-radius: 50%;
            --turn: calc(45deg + (36 * calc(var(--rating)* 1deg)));
            transform: translate(-50%, 50%) rotate(var(--turn));
            transform-origin: 50% 50%;
            transition: all 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .marker::before {
            content: "";
            position: absolute;
            border: calc(var(--round-size) / 3) solid transparent;
            border-right: calc(var(--size)* 0.4) solid #FFF;
            left: 0%;
            bottom: 0;
            transform: translate(-100%, 50%) rotate(-45deg);
            transform-origin: 100% 50%;
            filter: drop-shadow(calc(var(--round-size) / -10) 0 0.2rem #0008);
        }
    </style>
</head>

<body class="antialiased selection:bg-stone-gold selection:text-stone-navy flex flex-col min-h-screen">

    <!-- Elementos de Fundo -->
    <div class="fixed-bg"></div>
    <div class="bg-overlay"></div>

    <div class="relative flex min-h-screen w-full flex-col group/design-root">

        <!-- Header -->
        <header id="main-header"
            class="fixed w-full z-50 transition-all duration-300 py-4 bg-stone-navy/80 backdrop-blur-md border-b border-stone-glassBorder">
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
                    <a href="dashboard.php"
                        class="text-stone-gold transition-colors text-sm uppercase tracking-widest font-bold border-b-2 border-stone-gold pb-1">Portfólio</a>
                    <a href="analise.php"
                        class="text-stone-gray hover:text-stone-gold transition-colors text-sm uppercase tracking-widest font-medium">Análise</a>
                    <a href="#"
                        class="text-stone-gray hover:text-stone-gold transition-colors text-sm uppercase tracking-widest font-medium">Mercado</a>
                    <a href="#"
                        class="text-stone-gray hover:text-stone-gold transition-colors text-sm uppercase tracking-widest font-medium">Configurações</a>
                </nav>

                <!-- User Profile & Hamburger -->
                <div class="flex items-center gap-4">
                    <!-- User Profile (Desktop) -->
                    <div class="hidden md:flex items-center gap-4">
                        <a href="logout.php"
                            class="min-w-[84px] inline-flex cursor-pointer items-center justify-center overflow-hidden rounded-lg h-9 px-4 bg-stone-glass hover:bg-stone-glassBorder text-white text-xs font-bold transition-colors border border-stone-glassBorder uppercase tracking-wider">
                            <span>Sair</span>
                        </a>

                        <!-- Profile Trigger -->
                        <div class="relative">
                            <button id="userMenuBtn" class="flex items-center focus:outline-none group">
                                <div id="nav-avatar"
                                    class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-9 ring-2 <?php echo empty($_SESSION['investor_profile'] ?? '') ? 'ring-red-500 animate-pulse shadow-[0_0_15px_rgba(239,68,68,0.6)]' : 'ring-stone-gold shadow-[0_0_15px_rgba(212,175,55,0.3)] group-hover:ring-offset-2 group-hover:ring-offset-stone-navy group-hover:ring-stone-goldHover'; ?> transition-all"
                                    style='background-image: url("<?php echo $avatar_url; ?>");'>
                                </div>
                            </button>

                            <!-- User Dropdown Menu -->
                            <div id="userDropdown"
                                class="absolute right-0 mt-3 w-64 bg-stone-navy/95 backdrop-blur-xl border border-stone-glassBorder rounded-xl shadow-2xl hidden z-[100] transform transition-all origin-top-right">
                                <div class="p-4 border-b border-stone-glassBorder text-center">
                                    <div class="mx-auto w-12 h-12 rounded-full border-2 border-stone-gold mb-2 overflow-hidden bg-cover bg-center"
                                        id="dropdown-avatar"
                                        style='background-image: url("<?php echo $avatar_url; ?>");'></div>
                                    <p id="dropdown-name"
                                        class="text-stone-gold font-bold text-sm uppercase tracking-widest mb-0.5">
                                        <?php echo $user_name; ?>
                                    </p>
                                    <p class="text-stone-gray text-[10px] truncate"><?php echo $user_email; ?></p>
                                </div>
                                <div class="py-2">
                                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
                                        <a href="administra.php"
                                            class="flex items-center gap-3 px-4 py-3 text-stone-gold hover:text-white hover:bg-stone-gold/10 transition-colors">
                                            <span class="material-symbols-outlined text-lg">admin_panel_settings</span>
                                            <span class="text-xs font-bold tracking-wider uppercase">Painel
                                                Administrativo</span>
                                        </a>
                                    <?php endif; ?>
                                    <a href="#" onclick="openProfileModal(); return false;"
                                        class="flex items-center gap-3 px-4 py-3 text-stone-gray hover:text-white hover:bg-stone-glass transition-colors group/profile">
                                        <span class="material-symbols-outlined text-lg">person</span>
                                        <div class="flex flex-col">
                                            <span class="text-xs font-bold tracking-wider uppercase">Meu Perfil</span>
                                            <?php
                                            $profile = $_SESSION['investor_profile'] ?? '';
                                            if (empty($profile)) {
                                                echo '<span class="text-[9px] text-red-400 font-bold uppercase animate-pulse">⚠ Definir Perfil</span>';
                                            } else {
                                                echo '<span class="text-[9px] text-stone-gold font-bold uppercase">' . $profile . '</span>';
                                            }
                                            ?>
                                        </div>
                                    </a>
                                    <a href="suitability.php"
                                        class="flex items-center gap-3 px-4 py-3 text-stone-gray hover:text-white hover:bg-stone-glass transition-colors">
                                        <span class="material-symbols-outlined text-lg">psychology</span>
                                        <span class="text-xs font-bold tracking-wider uppercase">Suitability</span>
                                    </a>
                                    <a href="#"
                                        class="flex items-center gap-3 px-4 py-3 text-stone-gray hover:text-white hover:bg-stone-glass transition-colors">
                                        <span class="material-symbols-outlined text-lg">settings</span>
                                        <span class="text-xs font-bold tracking-wider uppercase">Configurações</span>
                                    </a>
                                    <a href="#"
                                        class="flex items-center gap-3 px-4 py-3 text-stone-gray hover:text-white hover:bg-stone-glass transition-colors">
                                        <span class="material-symbols-outlined text-lg">security</span>
                                        <span class="text-xs font-bold tracking-wider uppercase">Segurança</span>
                                    </a>
                                </div>
                                <div class="p-2 border-t border-stone-glassBorder">
                                    <a href="logout.php"
                                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-danger hover:bg-danger/10 transition-colors">
                                        <span class="material-symbols-outlined text-lg">logout</span>
                                        <span class="text-xs font-bold tracking-wider uppercase">Sair da Conta</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button id="mobile-menu-btn" class="md:hidden text-stone-gold text-2xl focus:outline-none">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu Dropdown -->
            <div id="mobile-menu"
                class="hidden md:hidden bg-stone-navy/95 backdrop-blur-xl absolute w-full top-full left-0 border-t border-stone-glassBorder shadow-2xl">
                <div class="flex flex-col items-center py-8 space-y-6">
                    <div class="flex flex-col items-center gap-3 mb-4">
                        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-16 ring-2 ring-stone-gold shadow-[0_0_15px_rgba(212,175,55,0.3)]"
                            style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCTCifV9f7veeImD6mpBg5MYpyLXZuX0Wn-PekVpNu3vhVQG721dQEl5WbsrR0o1vraCZDBH5trp5oRZRL1eoPcs3dQ2f-TLvIbK0zrlOY8h0HhQ2cwU_AEwwuY_aTR73AIIqfDUGiolLRlNIFv2tosDtVNg9Of2mQ6U3go3M0Stl4z-ovMmuKmAZstI_VMgVwz4eMj131GaJWanBRhtp4sq_-iwpm3rpvT2lnUsLqCG5sWw3sBN2vvSkwzE6IoKjRM1kJVgZGQng0");'>
                        </div>
                        <span class="text-stone-gold font-bold uppercase tracking-widest text-sm">Fabio</span>
                    </div>

                    <a href="dashboard.php"
                        class="mobile-link text-stone-gold text-lg uppercase tracking-widest font-bold">Portfólio</a>
                    <a href="analise.php"
                        class="mobile-link text-white text-lg hover:text-stone-gold uppercase tracking-widest font-bold">Análise</a>
                    <a href="#"
                        class="mobile-link text-white text-lg hover:text-stone-gold uppercase tracking-widest font-bold">Mercado</a>
                    <a href="#"
                        class="mobile-link text-white text-lg hover:text-stone-gold uppercase tracking-widest font-bold">Configurações</a>
                    <a href="logout.php"
                        class="mobile-link text-stone-gray hover:text-white uppercase tracking-widest font-bold text-sm border border-stone-glassBorder px-6 py-2 rounded-full mt-4">Sair</a>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 flex justify-center py-6 px-4 sm:px-6 lg:px-8 mt-24">
            <div class="w-full max-w-[1440px] flex flex-col gap-6">

                <!-- Page Heading & Search Section -->
                <div
                    class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between bg-stone-glass p-6 rounded-2xl border border-stone-glassBorder">
                    <div class="flex flex-col gap-1.5">
                        <h1
                            class="text-white text-3xl md:text-4xl font-playfair font-bold leading-tight tracking-tight">
                            Dashboard de Performance</h1>
                        <p class="text-stone-gray text-base font-normal max-w-xl">Acompanhe a performance consolidada do
                            seu portfólio e a movimentação do mercado.</p>
                    </div>
                    <div class="w-full lg:max-w-xs relative">
                        <label
                            class="flex w-full h-10 rounded-lg bg-stone-navy/50 border border-stone-glassBorder focus-within:ring-2 focus-within:ring-stone-gold transition-all overflow-hidden">
                            <div class="flex items-center justify-center pl-3 text-stone-gray">
                                <span class="material-symbols-outlined text-lg">search</span>
                            </div>
                            <input id="assetSearchInput"
                                class="w-full h-full bg-transparent border-none text-white text-sm placeholder:text-stone-gray focus:ring-0 px-2 outline-none"
                                placeholder="Buscar ativo..." autocomplete="off"
                                onkeypress="handleSearchEnter(event)" />
                            <button id="assetSearchBtn"
                                class="h-full px-3 bg-stone-gold hover:bg-stone-goldHover text-stone-navy text-sm font-bold rounded-r-lg shadow-sm transition-colors">
                                Buscar
                            </button>
                        </label>
                        <div id="searchResults"
                            class="absolute top-full left-0 w-full bg-stone-navy/90 backdrop-blur-md border border-stone-glassBorder rounded-lg mt-1 hidden z-[60] shadow-2xl max-h-60 overflow-y-auto">
                        </div>
                    </div>
                </div>

                <!-- Key Metrics Summary -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                    <!-- Total Assets -->
                    <div
                        class="bg-stone-glass rounded-xl border border-stone-glassBorder p-5 flex flex-col justify-between shadow-lg hover:border-stone-gold/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <p class="text-stone-gray text-sm font-medium uppercase tracking-wider">Valor Total</p>
                            <span
                                class="material-symbols-outlined text-xl text-stone-gold">account_balance_wallet</span>
                        </div>
                        <p id="dashboard-total-value" class="text-white text-3xl font-extrabold mt-3">...</p>
                        <p id="dashboard-asset-count" class="text-stone-gray text-xs mt-1">revelando ativos...</p>
                    </div>
                    <!-- Daily Gain/Loss -->
                    <div
                        class="bg-stone-glass rounded-xl border border-stone-glassBorder p-5 flex flex-col justify-between shadow-lg hover:border-stone-gold/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <p class="text-stone-gray text-sm font-medium uppercase tracking-wider">Ganho (24h)</p>
                            <span id="daily-gain-icon"
                                class="material-symbols-outlined text-xl text-success">arrow_upward</span>
                        </div>
                        <p id="dashboard-daily-gain" class="text-success text-3xl font-extrabold mt-3">...</p>
                        <p id="dashboard-daily-gain-pct" class="text-success text-xs mt-1 font-bold">...</p>
                    </div>
                    <!-- Overall Profit/Loss -->
                    <div
                        class="bg-stone-glass rounded-xl border border-stone-glassBorder p-5 flex flex-col justify-between shadow-lg hover:border-stone-gold/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <p class="text-stone-gray text-sm font-medium uppercase tracking-wider">P/L Acumulado</p>
                            <span id="total-gain-icon"
                                class="material-symbols-outlined text-xl text-success">trending_up</span>
                        </div>
                        <p id="dashboard-total-gain" class="text-success text-3xl font-extrabold mt-3">...</p>
                        <p id="dashboard-total-gain-pct" class="text-stone-gray text-xs mt-1 font-bold">Total: ...</p>
                    </div>
                    <!-- Biggest Winner -->
                    <div
                        class="bg-stone-glass rounded-xl border border-stone-glassBorder p-5 flex flex-col justify-between shadow-lg hover:border-stone-gold/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <p class="text-stone-gray text-sm font-medium uppercase tracking-wider">Melhor Ativo
                            </p>
                            <span class="material-symbols-outlined text-xl text-stone-gold">workspace_premium</span>
                        </div>
                        <p id="dashboard-best-ticker" class="text-white text-3xl font-extrabold mt-3">...</p>
                        <p id="dashboard-best-gain" class="text-success text-xs mt-1 font-bold">...</p>
                    </div>
                </div>

                <!-- Chart & Filters Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Chart Placeholder -->
                    <div class="lg:col-span-2 bg-stone-glass rounded-xl border border-stone-glassBorder p-6 shadow-lg">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-white text-xl font-bold font-playfair">Evolução do Portfólio</h2>
                            <div class="flex gap-2">
                                <button
                                    class="text-xs font-bold px-3 py-1 rounded-full bg-stone-gold text-stone-navy">1A</button>
                                <button
                                    class="text-xs font-medium px-3 py-1 rounded-full bg-stone-glassBorder text-stone-gray hover:text-white hover:bg-stone-gold/20 transition-colors">6M</button>
                                <button
                                    class="text-xs font-medium px-3 py-1 rounded-full bg-stone-glassBorder text-stone-gray hover:text-white hover:bg-stone-gold/20 transition-colors">1M</button>
                            </div>
                        </div>
                        <!-- Placeholder for Chart -->
                        <div id="portfolioChart" class="w-full min-h-[300px]"></div>
                    </div>

                    <!-- Quick Filters / Watchlist Summary -->
                    <div
                        class="lg:col-span-1 bg-stone-glass rounded-xl border border-stone-glassBorder p-6 shadow-lg flex flex-col gap-4">
                        <h2 class="text-white text-xl font-bold font-playfair">Avisos</h2>
                        <div class="flex flex-col gap-3">
                            <!-- Sector 1 -->
                            <div class="flex justify-between items-center pb-2 border-b border-stone-glassBorder">
                                <p class="text-white font-medium">Commodities</p>
                                <span class="text-success font-bold text-sm">+1.5%</span>
                            </div>
                            <!-- Sector 2 -->
                            <div class="flex justify-between items-center pb-2 border-b border-stone-glassBorder">
                                <p class="text-white font-medium">Finanças</p>
                                <span class="text-success font-bold text-sm">+0.9%</span>
                            </div>
                            <!-- Sector 3 -->
                            <div class="flex justify-between items-center pb-2 border-b border-stone-glassBorder">
                                <p class="text-white font-medium">Tecnologia</p>
                                <span class="text-danger font-bold text-sm">-0.2%</span>
                            </div>
                            <!-- Sector 4 -->
                            <div class="flex justify-between items-center">
                                <p class="text-white font-medium">Varejo</p>
                                <span class="text-danger font-bold text-sm">-0.8%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Featured Assets -->
                <div class="flex flex-col gap-4 mt-4">
                    <h2 class="text-white text-2xl font-bold font-playfair">Meus Ativos</h2>

                    <div id="assets-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Assets will be rendered here dynamically -->
                        <div class="col-span-full py-12 flex flex-col items-center justify-center opacity-50">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-stone-gold mb-4"></div>
                            <p class="text-stone-gray uppercase tracking-widest text-sm font-bold">Carregando seus
                                ativos...</p>
                        </div>
                    </div>
                </div>
        </main>
    </div>

    <!-- Asset Details Modal -->
    <div id="assetModal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-stone-navy/90 backdrop-blur-sm transition-opacity" onclick="closeAssetModal()">
        </div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-2xl bg-stone-glass border border-stone-glassBorder text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">

                    <!-- Header -->
                    <div
                        class="bg-stone-navy/50 px-4 py-3 sm:px-6 flex justify-between items-center border-b border-stone-glassBorder">
                        <h3 class="text-lg font-bold leading-6 text-white font-playfair" id="modal-title">Detalhes do
                            Ativo</h3>
                        <div class="flex items-center gap-3">
                            <a id="modalRiBtn" href="#" target="_blank"
                                class="hidden flex items-center gap-1.5 px-3 py-1 bg-stone-gold/20 hover:bg-stone-gold/30 text-stone-gold text-[10px] font-bold rounded-full transition-all border border-stone-gold/30">
                                <span>RI</span>
                                <span class="material-symbols-outlined text-sm">open_in_new</span>
                            </a>
                            <button type="button" class="text-stone-gray hover:text-white transition-colors"
                                onclick="closeAssetModal()">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="px-4 py-5 sm:p-6 max-h-[80vh] overflow-y-auto custom-scrollbar">
                        <div id="modalContent" class="flex flex-col gap-6">
                            <!-- Loading State -->
                            <div id="modalLoading" class="flex justify-center py-12">
                                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-stone-gold"></div>
                            </div>

                            <!-- Data Content (Hidden initially) -->
                            <div id="modalData" class="hidden flex-col gap-6">

                                <!-- 1. Resumo -->
                                <div class="flex flex-col md:flex-row gap-6 items-start">
                                    <div
                                        class="h-20 w-20 shrink-0 rounded-full border-2 border-stone-gold/40 overflow-hidden bg-stone-navy">
                                        <img id="modalLogo" src="" alt="Logo" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 w-full">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h2 id="modalTicker" class="text-3xl font-bold text-white leading-none">
                                                </h2>
                                                <p id="modalName" class="text-stone-gray text-sm mt-1"></p>
                                                <div class="flex gap-2 mt-2">
                                                    <span id="modalType"
                                                        class="px-2 py-0.5 rounded-full bg-stone-gold/10 border border-stone-gold/20 text-[10px] font-bold uppercase tracking-wider text-stone-gold"></span>
                                                    <span id="modalSector"
                                                        class="px-2 py-0.5 rounded-full bg-stone-glass border border-stone-glassBorder text-[10px] font-bold uppercase tracking-wider text-stone-gray"></span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-xs text-stone-gray uppercase">Cotação Atual</p>
                                                <p id="modalPrice" class="text-3xl font-bold text-white"></p>
                                                <p id="modalDate" class="text-[10px] text-stone-gray mt-1"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="h-px w-full bg-stone-glassBorder"></div>

                                <!-- 2. Oscilações -->
                                <div>
                                    <h4
                                        class="text-stone-gold font-bold text-sm uppercase tracking-wider mb-3 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-lg">trending_up</span> Oscilações
                                    </h4>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">Dia</p>
                                            <p id="modalOscDay" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">Mês</p>
                                            <p id="modalOscMonth" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">30 Dias</p>
                                            <p id="modalOsc30d" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">12 Meses</p>
                                            <p id="modalOsc12m" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">2025</p>
                                            <p id="modalOsc2025" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">2024</p>
                                            <p id="modalOsc2024" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">2023</p>
                                            <p id="modalOsc2023" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">2022</p>
                                            <p id="modalOsc2022" class="font-bold text-white"></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Margem de Segurança (Speedometers) -->
                                <div id="modalSectionSpeedometers" class="py-4">
                                    <h4
                                        class="text-stone-gold font-bold text-sm uppercase tracking-wider mb-6 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-lg">speed</span> Margem de Segurança
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <!-- Graham -->
                                        <div
                                            class="flex flex-col items-center gap-3 bg-stone-navy/20 p-4 rounded-xl border border-stone-glassBorder/30">
                                            <p class="text-[9px] text-stone-gray uppercase tracking-widest font-bold">
                                                Graham</p>
                                            <div class="graph-container" id="modalGaugeGraham" style="--rating: 2.5;">
                                                <div class="half-donut">
                                                    <div class="slice"><i class="fa-regular fa-face-grin-hearts"></i>
                                                    </div>
                                                    <div class="slice"><i class="fa-regular fa-face-smile"></i></div>
                                                    <div class="slice"><i class="fa-regular fa-face-meh"></i></div>
                                                    <div class="slice"><i class="fa-regular fa-face-frown"></i></div>
                                                    <div class="slice"><i class="fa-regular fa-face-grimace"></i></div>
                                                </div>
                                                <div class="marker"></div>
                                            </div>
                                            <div class="text-center">
                                                <p id="modalLabelGraham" class="text-sm font-bold text-white">N/A</p>
                                                <p id="modalSubGraham"
                                                    class="text-[8px] uppercase tracking-tighter mt-0.5"></p>
                                            </div>
                                        </div>

                                        <!-- Average -->
                                        <div
                                            class="flex flex-col items-center gap-3 bg-stone-navy/20 p-4 rounded-xl border border-stone-gold/20">
                                            <p class="text-[9px] text-stone-gold uppercase tracking-widest font-bold">
                                                Média</p>
                                            <div class="graph-container" id="modalGaugeAverage" style="--rating: 2.5;">
                                                <div class="half-donut">
                                                    <div class="slice"><i class="fa-regular fa-face-grin-hearts"></i>
                                                    </div>
                                                    <div class="slice"><i class="fa-regular fa-face-smile"></i></div>
                                                    <div class="slice"><i class="fa-regular fa-face-meh"></i></div>
                                                    <div class="slice"><i class="fa-regular fa-face-frown"></i></div>
                                                    <div class="slice"><i class="fa-regular fa-face-grimace"></i></div>
                                                </div>
                                                <div class="marker"></div>
                                            </div>
                                            <div class="text-center">
                                                <p id="modalLabelAverage" class="text-sm font-bold text-white">N/A</p>
                                                <p id="modalSubAverage"
                                                    class="text-[8px] uppercase tracking-tighter mt-0.5"></p>
                                            </div>
                                        </div>

                                        <!-- Bazin -->
                                        <div
                                            class="flex flex-col items-center gap-3 bg-stone-navy/20 p-4 rounded-xl border border-stone-glassBorder/30">
                                            <p class="text-[9px] text-stone-gray uppercase tracking-widest font-bold">
                                                Bazin</p>
                                            <div class="graph-container" id="modalGaugeBazin" style="--rating: 2.5;">
                                                <div class="half-donut">
                                                    <div class="slice"><i class="fa-regular fa-face-grin-hearts"></i>
                                                    </div>
                                                    <div class="slice"><i class="fa-regular fa-face-smile"></i></div>
                                                    <div class="slice"><i class="fa-regular fa-face-meh"></i></div>
                                                    <div class="slice"><i class="fa-regular fa-face-frown"></i></div>
                                                    <div class="slice"><i class="fa-regular fa-face-grimace"></i></div>
                                                </div>
                                                <div class="marker"></div>
                                            </div>
                                            <div class="text-center">
                                                <p id="modalLabelBazin" class="text-sm font-bold text-white">N/A</p>
                                                <p id="modalSubBazin"
                                                    class="text-[8px] uppercase tracking-tighter mt-0.5"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 3. Valuation -->
                                <div id="modalSectionValuation">
                                    <h4
                                        class="text-stone-gold font-bold text-sm uppercase tracking-wider mb-3 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-lg">balance</span> Indicadores de
                                        Valuation
                                    </h4>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">P/L</p>
                                            <p id="modalPL" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">P/VP</p>
                                            <p id="modalPVP" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">Div. Yield</p>
                                            <p id="modalDY" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">EV/EBITDA</p>
                                            <p id="modalEVEBITDA" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">P/EBIT</p>
                                            <p id="modalPEBIT" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">PSR</p>
                                            <p id="modalPSR" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">P/Ativos</p>
                                            <p id="modalPAssets" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">P/Cap. Giro</p>
                                            <p id="modalPCapGiro" class="font-bold text-white"></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- 4. Rentabilidade & Eficiência -->
                                <div id="modalSectionRentability">
                                    <h4
                                        class="text-stone-gold font-bold text-sm uppercase tracking-wider mb-3 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-lg">monitoring</span> Rentabilidade
                                        & Eficiência
                                    </h4>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">ROE</p>
                                            <p id="modalROE" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">ROIC</p>
                                            <p id="modalROIC" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">Margem Bruta</p>
                                            <p id="modalGrossMargin" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">Margem Líquida</p>
                                            <p id="modalNetMargin" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">Margem EBIT</p>
                                            <p id="modalEBITMargin" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">Giro Ativos</p>
                                            <p id="modalAssetTurnover" class="font-bold text-white"></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- 5. Balanço & Resultados -->
                                <div id="modalSectionBalance">
                                    <h4
                                        class="text-stone-gold font-bold text-sm uppercase tracking-wider mb-3 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-lg">account_balance</span> Balanço &
                                        Resultados
                                    </h4>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">Valor de Mercado</p>
                                            <p id="modalMarketCap" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">Valor da Firma</p>
                                            <p id="modalFirmValue" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">Patrimônio Líquido</p>
                                            <p id="modalEquity" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">Dívida Líquida</p>
                                            <p id="modalNetDebt" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">Receita Líquida (12m)</p>
                                            <p id="modalNetRevenue12m" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">Lucro Líquido (12m)</p>
                                            <p id="modalNetIncome12m" class="font-bold text-white"></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- 6. Indicadores de FII -->
                                <div id="modalSectionFII" class="hidden">
                                    <h4
                                        class="text-stone-gold font-bold text-sm uppercase tracking-wider mb-3 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-lg">domain</span> Indicadores de FII
                                    </h4>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">P / VP</p>
                                            <p id="modalFIIPVP" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">Div. Yield</p>
                                            <p id="modalFIIDY" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">FFO Yield</p>
                                            <p id="modalFIIFFOYield" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">VP / Cota</p>
                                            <p id="modalFIIVPCota" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">FFO / Cota</p>
                                            <p id="modalFIIFFOCota" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">Dividendo / cota</p>
                                            <p id="modalFIIDivCota" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">Mandato</p>
                                            <p id="modalFIIMandato" class="font-bold text-white text-xs"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">Gestão</p>
                                            <p id="modalFIIGestao" class="font-bold text-white text-xs"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">Rend. (12m)</p>
                                            <p id="modalFIIYield12" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">FFO (12m)</p>
                                            <p id="modalFIIFFO12" class="font-bold text-white"></p>
                                        </div>
                                        <div class="bg-stone-navy/30 p-3 rounded-lg border border-stone-glassBorder">
                                            <p class="text-[10px] text-stone-gray uppercase">Receita (12m)</p>
                                            <p id="modalFIIRevenue12" class="font-bold text-white"></p>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Error State -->
                            <div id="modalError" class="hidden text-center py-4">
                                <span class="material-symbols-outlined text-danger text-4xl mb-2">error</span>
                                <p class="text-white">Erro ao carregar dados.</p>
                                <p id="modalErrorMessage" class="text-sm text-stone-gray mt-1"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div
                        class="bg-stone-navy/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-stone-glassBorder">
                        <button type="button"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-stone-glass border border-stone-glassBorder px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-stone-glassBorder sm:mt-0 sm:w-auto transition-colors"
                            onclick="closeAssetModal()">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Trade Modal (Purchase) -->
    <div id="tradeModal" class="fixed inset-0 z-[110] hidden" aria-labelledby="trade-modal-title" role="dialog"
        aria-modal="true">
        <div class="fixed inset-0 bg-stone-navy/90 backdrop-blur-sm transition-opacity" onclick="closeTradeModal()">
        </div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-2xl bg-stone-glass border border-stone-glassBorder text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md">
                    <form id="tradeForm">
                        <div
                            class="bg-stone-navy/50 px-4 py-3 sm:px-6 flex justify-between items-center border-b border-stone-glassBorder">
                            <h3 class="text-lg font-bold leading-6 text-white font-playfair" id="trade-modal-title">
                                Efetuar Compra</h3>
                            <button type="button" class="text-stone-gray hover:text-white transition-colors"
                                onclick="closeTradeModal()">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>
                        <div class="px-4 py-5 sm:p-6 flex flex-col gap-4">
                            <div>
                                <label
                                    class="block text-xs font-bold text-stone-gold uppercase tracking-wider mb-1">Ativo</label>
                                <input type="text" id="tradeTicker" readonly
                                    class="w-full bg-stone-navy/30 border border-stone-glassBorder rounded-lg px-3 py-2 text-white font-bold outline-none cursor-default">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-xs font-bold text-stone-gold uppercase tracking-wider mb-1">Quantidade</label>
                                    <input type="number" id="tradeQuantity" step="0.01" required placeholder="Ex: 100"
                                        class="w-full bg-stone-navy/50 border border-stone-glassBorder rounded-lg px-3 py-2 text-white outline-none focus:border-stone-gold transition-colors">
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-bold text-stone-gold uppercase tracking-wider mb-1">Preço
                                        Unitário</label>
                                    <input type="number" id="tradePrice" step="0.01" required placeholder="Ex: 25.50"
                                        class="w-full bg-stone-navy/50 border border-stone-glassBorder rounded-lg px-3 py-2 text-white outline-none focus:border-stone-gold transition-colors">
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold text-stone-gold uppercase tracking-wider mb-1">Data
                                    da Operação</label>
                                <input type="date" id="tradeDate" required
                                    class="w-full bg-stone-navy/50 border border-stone-glassBorder rounded-lg px-3 py-2 text-white outline-none focus:border-stone-gold transition-colors">
                            </div>
                        </div>
                        <div
                            class="bg-stone-navy/50 px-4 py-3 flex flex-col sm:flex-row-reverse gap-3 sm:px-6 border-t border-stone-glassBorder">
                            <button type="button" id="buyBtn" onclick="submitTrade('buy')"
                                class="inline-flex w-full justify-center rounded-lg bg-stone-gold px-4 py-2 text-sm font-bold text-stone-navy shadow-sm hover:bg-stone-goldHover sm:w-auto transition-colors">COMPRAR</button>
                            <button type="button" id="sellBtn" onclick="submitTrade('sell')"
                                class="inline-flex w-full justify-center rounded-lg bg-danger px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-danger/80 sm:w-auto transition-colors">VENDER</button>
                            <button type="button"
                                class="inline-flex w-full justify-center rounded-lg bg-stone-glass px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-stone-glassBorder sm:w-auto transition-colors sm:mr-auto"
                                onclick="closeTradeModal()">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    </div>

    <script>
        // --- Mobile Menu Toggle ---
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');

        const userMenuBtn = document.getElementById('userMenuBtn');
        const userDropdown = document.getElementById('userDropdown');

        if (btn && menu) {
            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
        }

        if (userMenuBtn && userDropdown) {
            userMenuBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                userDropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!userMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                    userDropdown.classList.add('hidden');
                }
            });
        }

        function formatCurrency(value) {
            if (value === null || value === undefined) return 'N/A';
            return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
        }

        function formatPercent(value) {
            if (value === null || value === undefined) return '-';
            return (parseFloat(value) * 100).toFixed(2) + '%';
        }

        function formatNumber(value) {
            if (value === null || value === undefined) return '-';
            return new Intl.NumberFormat('pt-BR').format(value);
        }

        function formatDate(value) {
            if (!value) return '-';
            const date = new Date(value);
            return date.toLocaleDateString('pt-BR');
        }

        function colorizePercent(elementId, value) {
            const el = document.getElementById(elementId);
            if (!el) return;
            el.textContent = formatPercent(value);
            el.classList.remove('text-success', 'text-danger', 'text-white');

            if (value > 0) el.classList.add('text-success');
            else if (value < 0) el.classList.add('text-danger');
            else el.classList.add('text-white');
        }

        function openAssetModal(ticker) {
            const modal = document.getElementById('assetModal');
            const loading = document.getElementById('modalLoading');
            const data = document.getElementById('modalData');
            const error = document.getElementById('modalError');

            // Reset state
            modal.classList.remove('hidden');
            loading.classList.remove('hidden');
            data.classList.add('hidden');
            error.classList.add('hidden');

            // Fetch data
            fetch(`api/get_asset_details.php?ticker=${ticker}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(assetData => {
                    const asset = Array.isArray(assetData) ? assetData[0] : assetData;

                    if (!asset) {
                        throw new Error('Asset not found');
                    }

                    // --- Populate Data ---

                    // 1. Resumo
                    document.getElementById('modalTicker').textContent = asset.papel || ticker;
                    document.getElementById('modalName').textContent = asset.empresa || ticker;
                    document.getElementById('modalLogo').src = `img/logos/${ticker}.svg`;
                    document.getElementById('modalLogo').onerror = function () {
                        this.src = 'img/logo.jpg';
                        this.onerror = null;
                    };
                    document.getElementById('modalType').textContent = asset.tipo || (asset.categoria === 'fii' ? 'FII' : 'Ação');
                    document.getElementById('modalSector').textContent = asset.setor || 'N/A';
                    document.getElementById('modalPrice').textContent = formatCurrency(asset.cotacao);
                    document.getElementById('modalDate').textContent = 'Atualizado em: ' + formatDate(asset.data_ultima_cotacao);

                    // Toggle Sections based on Category
                    if (asset.categoria === 'fii') {
                        document.getElementById('modalSectionValuation').classList.add('hidden');
                        document.getElementById('modalSectionRentability').classList.add('hidden');
                        document.getElementById('modalSectionFII').classList.remove('hidden');

                        // Populate FII Fields
                        document.getElementById('modalFIIPVP').textContent = asset.p_vp ? parseFloat(asset.p_vp).toFixed(2) : '-';
                        document.getElementById('modalFIIDY').textContent = formatPercent(asset.div_yield);
                        document.getElementById('modalFIIFFOYield').textContent = formatPercent(asset.ffo_yield);
                        document.getElementById('modalFIIVPCota').textContent = formatCurrency(asset.vp_cota);
                        document.getElementById('modalFIIFFOCota').textContent = formatCurrency(asset.ffo_cota);
                        document.getElementById('modalFIIDivCota').textContent = formatCurrency(asset.dividendo_cota);
                        document.getElementById('modalFIIMandato').textContent = asset.mandato || '-';
                        document.getElementById('modalFIIGestao').textContent = asset.gestao || '-';
                        document.getElementById('modalFIIYield12').textContent = formatCurrency(asset.rendimento_12m);
                        document.getElementById('modalFIIFFO12').textContent = formatCurrency(asset.lucro_liquido_12m);
                        document.getElementById('modalFIIRevenue12').textContent = formatCurrency(asset.receita_liquida_12m);

                        // Hide Speedometers for FIIs (Graham/Bazin not applicable)
                        document.getElementById('modalSectionSpeedometers').classList.add('hidden');
                    } else {
                        document.getElementById('modalSectionValuation').classList.remove('hidden');
                        document.getElementById('modalSectionRentability').classList.remove('hidden');
                        document.getElementById('modalSectionFII').classList.add('hidden');
                        document.getElementById('modalSectionSpeedometers').classList.remove('hidden');
                    }

                    // RI Button in Modal
                    const modalRiBtn = document.getElementById('modalRiBtn');
                    if (asset.url_ri) {
                        modalRiBtn.href = asset.url_ri;
                        modalRiBtn.classList.remove('hidden');
                    } else {
                        modalRiBtn.href = `https://www.google.com/search?q=${ticker}+RI`;
                        modalRiBtn.classList.remove('hidden'); // Still show with search fallback
                    }

                    // 2. Oscilações
                    colorizePercent('modalOscDay', asset.osc_dia);
                    colorizePercent('modalOscMonth', asset.osc_mes);
                    colorizePercent('modalOsc30d', asset.osc_30_dias);
                    colorizePercent('modalOsc12m', asset.osc_12_meses);
                    colorizePercent('modalOsc2025', asset.osc_2025);
                    colorizePercent('modalOsc2024', asset.osc_2024);
                    colorizePercent('modalOsc2023', asset.osc_2023);
                    colorizePercent('modalOsc2022', asset.osc_2022);

                    // 3. Valuation
                    document.getElementById('modalPL').textContent = asset.p_l ? parseFloat(asset.p_l).toFixed(2) : '-';
                    document.getElementById('modalPVP').textContent = asset.p_vp ? parseFloat(asset.p_vp).toFixed(2) : '-';
                    document.getElementById('modalDY').textContent = formatPercent(asset.div_yield);
                    document.getElementById('modalEVEBITDA').textContent = asset.ev_ebitda ? parseFloat(asset.ev_ebitda).toFixed(2) : '-';
                    document.getElementById('modalPEBIT').textContent = asset.p_ebit ? parseFloat(asset.p_ebit).toFixed(2) : '-';
                    document.getElementById('modalPSR').textContent = asset.psr ? parseFloat(asset.psr).toFixed(2) : '-';
                    document.getElementById('modalPAssets').textContent = asset.p_ativos ? parseFloat(asset.p_ativos).toFixed(2) : '-';
                    document.getElementById('modalPCapGiro').textContent = asset.p_cap_giro ? parseFloat(asset.p_cap_giro).toFixed(2) : '-';

                    // 4. Rentabilidade
                    document.getElementById('modalROE').textContent = formatPercent(asset.roe);
                    document.getElementById('modalROIC').textContent = formatPercent(asset.roic);
                    document.getElementById('modalGrossMargin').textContent = formatPercent(asset.marg_bruta);
                    document.getElementById('modalNetMargin').textContent = formatPercent(asset.marg_liquida);
                    document.getElementById('modalEBITMargin').textContent = formatPercent(asset.marg_ebit);
                    document.getElementById('modalAssetTurnover').textContent = asset.giro_ativos ? parseFloat(asset.giro_ativos).toFixed(2) : '-';

                    // 5. Balanço
                    document.getElementById('modalMarketCap').textContent = formatCurrency(asset.valor_mercado);
                    document.getElementById('modalFirmValue').textContent = formatCurrency(asset.valor_firma);
                    document.getElementById('modalEquity').textContent = formatCurrency(asset.patrimonio_liquido);
                    document.getElementById('modalNetDebt').textContent = formatCurrency(asset.divida_liquida);
                    document.getElementById('modalNetRevenue12m').textContent = formatCurrency(asset.receita_liquida_12m);
                    document.getElementById('modalNetIncome12m').textContent = formatCurrency(asset.lucro_liquido_12m);

                    // --- Speedometer Calculation ---
                    const curPrice = parseFloat(asset.cotacao);

                    // Graham calculation
                    let grahamPrice = null;
                    if (asset.lpa > 0 && asset.vpa > 0) {
                        grahamPrice = Math.sqrt(22.5 * parseFloat(asset.lpa) * parseFloat(asset.vpa));
                    }

                    // Bazin calculation
                    let bazinPrice = null;
                    if (asset.cotacao && asset.div_yield) {
                        const dividendPaid = parseFloat(asset.cotacao) * parseFloat(asset.div_yield);
                        bazinPrice = dividendPaid / 0.06;
                    }

                    function updateModalGauge(gaugeId, labelId, subId, fairValue) {
                        const gauge = document.getElementById(gaugeId);
                        const label = document.getElementById(labelId);
                        const sub = document.getElementById(subId);

                        if (!fairValue || !curPrice || fairValue <= 0) {
                            label.textContent = 'N/A';
                            sub.textContent = 'Indisponível';
                            sub.className = "text-[8px] text-stone-gray uppercase tracking-tighter mt-0.5";
                            gauge.style.setProperty('--rating', 2.5);
                            return;
                        }

                        const ratio = curPrice / fairValue;
                        const rating = Math.min(Math.max((ratio - 0.5) * 5, 0), 5);

                        gauge.style.setProperty('--rating', rating);
                        label.textContent = formatCurrency(fairValue);

                        const upside = ((fairValue / curPrice) - 1) * 100;
                        if (upside > 0) {
                            sub.textContent = `Desconto: ${upside.toFixed(1)}%`;
                            sub.className = "text-[8px] text-success uppercase tracking-tighter mt-0.5";
                        } else {
                            sub.textContent = `Ágio: ${Math.abs(upside).toFixed(1)}%`;
                            sub.className = "text-[8px] text-danger uppercase tracking-tighter mt-0.5";
                        }
                    }

                    updateModalGauge('modalGaugeGraham', 'modalLabelGraham', 'modalSubGraham', grahamPrice);
                    updateModalGauge('modalGaugeBazin', 'modalLabelBazin', 'modalSubBazin', bazinPrice);

                    let avgPrice = null;
                    if (grahamPrice && bazinPrice) avgPrice = (grahamPrice + bazinPrice) / 2;
                    else if (grahamPrice) avgPrice = grahamPrice;
                    else if (bazinPrice) avgPrice = bazinPrice;

                    updateModalGauge('modalGaugeAverage', 'modalLabelAverage', 'modalSubAverage', avgPrice);

                    // Show data
                    loading.classList.add('hidden');
                    data.classList.remove('hidden');
                })
                .catch(err => {
                    console.error('Error:', err);
                    loading.classList.add('hidden');
                    error.classList.remove('hidden');
                    const errMsg = document.getElementById('modalErrorMessage');
                    if (errMsg) errMsg.textContent = err.message;
                });
        }

        function closeAssetModal() {
            document.getElementById('assetModal').classList.add('hidden');
        }

        // --- Search & Trade Logic ---
        const searchInput = document.getElementById('assetSearchInput');
        const searchBtn = document.getElementById('assetSearchBtn');
        const searchResults = document.getElementById('searchResults');

        let searchTimeout;

        function performSearch(q) {
            if (q.length < 2) {
                searchResults.classList.add('hidden');
                return;
            }

            fetch(`api/search_assets.php?q=${encodeURIComponent(q)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length > 0) {
                        searchResults.innerHTML = '';
                        data.forEach(asset => {
                            const div = document.createElement('div');
                            div.className = 'p-3 hover:bg-stone-glassBorder cursor-pointer transition-colors border-b border-stone-glassBorder last:border-0';
                            div.innerHTML = `
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="text-white font-bold">${asset.papel}</span>
                                        <span class="text-stone-gray text-xs ml-2">${asset.empresa}</span>
                                    </div>
                                    <span class="text-stone-gold font-bold text-sm">${formatCurrency(asset.cotacao)}</span>
                                </div>
                            `;
                            div.onclick = () => {
                                openTradeModal(asset.papel, asset.cotacao);
                                searchResults.classList.add('hidden');
                            };
                            searchResults.appendChild(div);
                        });
                        searchResults.classList.remove('hidden');
                    } else {
                        searchResults.innerHTML = '<div class="p-4 text-stone-gray text-center text-sm">Nenhum ativo encontrado</div>';
                        searchResults.classList.remove('hidden');
                    }
                });
        }

        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            const q = searchInput.value.trim();
            if (q.length < 2) {
                searchResults.classList.add('hidden');
                return;
            }

            searchTimeout = setTimeout(() => {
                performSearch(q);
            }, 300);
        });

        function handleSearchEnter(event) {
            if (event.key === 'Enter') {
                const q = searchInput.value.trim();
                performSearch(q);
            }
        }

        searchBtn.addEventListener('click', (e) => {
            e.stopPropagation(); // Prevent global click listener from hiding results
            const q = searchInput.value.trim();
            performSearch(q);
        });

        // Close search results when clicking outside
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target) && !searchBtn.contains(e.target)) {
                searchResults.classList.add('hidden');
            }
        });

        function openTradeModal(ticker, currentPrice) {
            document.getElementById('tradeTicker').value = ticker;
            document.getElementById('tradePrice').value = currentPrice || '';
            document.getElementById('tradeQuantity').value = '';
            document.getElementById('tradeDate').value = new Date().toISOString().split('T')[0];
            document.getElementById('tradeModal').classList.remove('hidden');
        }

        function closeTradeModal() {
            document.getElementById('tradeModal').classList.add('hidden');
        }

        async function submitTrade(type) {
            const buyBtn = document.getElementById('buyBtn');
            const sellBtn = document.getElementById('sellBtn');
            const ticker = document.getElementById('tradeTicker').value;
            const quantity = document.getElementById('tradeQuantity').value;
            const price = document.getElementById('tradePrice').value;
            const date = document.getElementById('tradeDate').value;

            if (!quantity || !price || !date) {
                alert('Preencha todos os campos.');
                return;
            }

            const activeBtn = type === 'buy' ? buyBtn : sellBtn;
            const originalText = activeBtn.innerHTML;

            buyBtn.disabled = true;
            sellBtn.disabled = true;
            activeBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i>';

            try {
                const response = await fetch('api/execute_trade.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ ticker, quantity, price, date, type })
                });
                const result = await response.json();

                if (result.success) {
                    alert(`${type === 'buy' ? 'Compra' : 'Venda'} realizada com sucesso!`);
                    closeTradeModal();
                    loadDashboardData();
                } else {
                    alert('Erro: ' + (result.error || 'Ocorreu um problema.'));
                }
            } catch (err) {
                alert('Erro de conexão.');
            } finally {
                buyBtn.disabled = false;
                sellBtn.disabled = false;
                activeBtn.innerHTML = originalText;
            }
        }

        // --- Dashboard Initialization ---
        function updateSummaryCard(id, value, isCurrency = true, isPercent = false, extraId = null, extraValue = null) {
            const el = document.getElementById(id);
            if (!el) return;

            if (isCurrency) el.textContent = formatCurrency(value);
            else if (isPercent) el.textContent = (value * 100).toFixed(2) + '%';
            else el.textContent = value;

            if (id === 'dashboard-daily-gain' || id === 'dashboard-total-gain') {
                const iconId = id === 'dashboard-daily-gain' ? 'daily-gain-icon' : 'total-gain-icon';
                const icon = document.getElementById(iconId);
                const pctEl = id === 'dashboard-daily-gain' ? document.getElementById('dashboard-daily-gain-pct') : null;

                el.classList.remove('text-success', 'text-danger', 'text-white');
                if (icon) icon.classList.remove('text-success', 'text-danger', 'text-white');

                if (value > 0) {
                    el.classList.add('text-success');
                    if (icon) {
                        icon.classList.add('text-success');
                        icon.textContent = 'arrow_upward';
                    }
                    if (id === 'dashboard-daily-gain') {
                        el.textContent = '+' + el.textContent;
                    }
                } else if (value < 0) {
                    el.classList.add('text-danger');
                    if (icon) {
                        icon.classList.add('text-success'); // Keep green for up marker usually but here it's gain
                        icon.textContent = 'arrow_downward';
                        icon.classList.replace('text-success', 'text-danger');
                    }
                }
            }
        }

        function loadDashboardData() {
            fetch('api/get_dashboard_data.php')
                .then(response => response.json())
                .then(data => {
                    const summary = data.summary;
                    const assets = data.assets;

                    // Update Cards
                    updateSummaryCard('dashboard-total-value', summary.total_value);
                    document.getElementById('dashboard-asset-count').textContent = `em ${summary.asset_count} ativos`;

                    updateSummaryCard('dashboard-daily-gain', summary.daily_gain);
                    const dailyPct = document.getElementById('dashboard-daily-gain-pct');
                    dailyPct.textContent = (summary.daily_gain_pct * 100).toFixed(2) + '%';
                    dailyPct.classList.remove('text-success', 'text-danger');
                    dailyPct.classList.add(summary.daily_gain_pct >= 0 ? 'text-success' : 'text-danger');
                    dailyPct.textContent = (summary.daily_gain_pct >= 0 ? '+' : '') + dailyPct.textContent;

                    updateSummaryCard('dashboard-total-gain', summary.total_gain);
                    const totalPct = document.getElementById('dashboard-total-gain-pct');
                    totalPct.innerHTML = `Total: <span class="${summary.total_gain_pct >= 0 ? 'text-success' : 'text-danger'} font-bold">${(summary.total_gain_pct >= 0 ? '+' : '') + (summary.total_gain_pct * 100).toFixed(2)}%</span>`;

                    // Find best winner
                    if (assets.length > 0) {
                        const best = assets.reduce((prev, current) => (prev.osc_dia > current.osc_dia) ? prev : current);
                        document.getElementById('dashboard-best-ticker').textContent = best.ticker;
                        const bestGain = document.getElementById('dashboard-best-gain');
                        bestGain.textContent = (best.osc_dia >= 0 ? '+' : '') + (best.osc_dia * 100).toFixed(2) + '%';
                        bestGain.classList.remove('text-success', 'text-danger');
                        bestGain.classList.add(best.osc_dia >= 0 ? 'text-success' : 'text-danger');
                    }

                    // Render Chart
                    if (data.history) {
                        initChart(data.history);
                    }

                    // Render Assets
                    const grid = document.getElementById('assets-grid');
                    grid.innerHTML = '';

                    assets.forEach(asset => {
                        const card = document.createElement('div');
                        card.className = "relative flex flex-col gap-4 rounded-xl bg-stone-glass py-4 px-5 shadow-xl border border-stone-glassBorder overflow-hidden group hover:border-stone-gold/50 hover:shadow-2xl hover:shadow-stone-gold/10 hover:scale-[1.01] transition-all duration-300";

                        const oscColor = asset.osc_dia >= 0 ? 'success' : 'danger';
                        const oscIcon = asset.osc_dia >= 0 ? 'trending_up' : 'trending_down';
                        const oscSign = asset.osc_dia >= 0 ? '+' : '';

                        card.innerHTML = `
                            <div class="absolute -right-10 -top-10 h-64 w-64 bg-stone-gold/5 rounded-full blur-3xl pointer-events-none"></div>
                            <div onclick="openTradeModal('${asset.ticker}', ${asset.cotacao})" class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4 w-full z-10 flex-1 cursor-pointer">
                                <div class="flex items-start gap-4 shrink-0">
                                    <div class="flex flex-col items-center gap-1.5">
                                        <div class="h-16 w-16 shrink-0 rounded-full overflow-hidden border-2 border-stone-gold/40">
                                            <img src="img/logos/${asset.ticker}.svg" 
                                                 onerror="this.src='img/logo.jpg'; this.onerror=null;" 
                                                 alt="Logo" class="w-full h-full object-cover" />
                                        </div>
                                        <div class="flex flex-col items-center">
                                            <span class="text-[9px] font-bold text-stone-gray uppercase tracking-tighter leading-none opacity-60">Quant.</span>
                                            <span class="text-xs font-bold text-stone-gold leading-none mt-0.5">${asset.quantidade}</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-2 mb-0.5">
                                            <h2 class="text-xl font-bold text-white leading-none">${asset.ticker}</h2>
                                            <span class="px-1.5 py-0.5 rounded-full bg-stone-gold/${asset.categoria === 'fii' ? '20' : '10'} border border-stone-gold/20 text-[8px] font-bold uppercase tracking-wider text-stone-gold">${asset.categoria === 'fii' ? 'FII' : 'Ação'}</span>
                                        </div>
                                        <p class="text-[10px] font-medium text-stone-gray mb-1.5 truncate max-w-[150px]">${asset.empresa}</p>
                                        <span class="text-lg font-bold text-white tracking-tight mb-0.5">${formatCurrency(asset.cotacao)}</span>
                                        <div class="flex items-center text-[10px] font-semibold text-${oscColor} bg-${oscColor}/10 px-2 py-0.5 rounded-md w-fit">
                                            <span class="material-symbols-outlined text-[12px] mr-1">${oscIcon}</span>
                                            <span>${oscSign}${(asset.osc_dia * 100).toFixed(2)}%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-x-4 gap-y-3 w-full lg:w-fit lg:min-w-[200px] lg:justify-end lg:pl-4 lg:py-1">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="text-[9px] font-semibold text-stone-gray uppercase tracking-wider">Dividend Yield</span>
                                        <div class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-stone-gold text-[16px]">account_balance_wallet</span>
                                            <span class="text-base font-bold text-white">${formatPercent(asset.div_yield)}</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-0.5">
                                        <span class="text-[9px] font-semibold text-stone-gray uppercase tracking-wider">${asset.categoria === 'fii' ? 'VP / Cota' : 'P/L'}</span>
                                        <div class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-stone-gold text-[16px]">${asset.categoria === 'fii' ? 'payments' : 'paid'}</span>
                                            <span class="text-base font-bold text-white">${asset.categoria === 'fii' ? formatCurrency(asset.vp_cota) : (asset.p_l ? parseFloat(asset.p_l).toFixed(1) + 'x' : '-')}</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-0.5">
                                        <span class="text-[9px] font-semibold text-stone-gray uppercase tracking-wider">Investido</span>
                                        <div class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-stone-gold text-[16px]">monitoring</span>
                                            <span class="text-base font-bold text-white">${formatCurrency(asset.preco_medio)}</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-0.5">
                                        <span class="text-[9px] font-semibold text-stone-gray uppercase tracking-wider">P/VP</span>
                                        <div class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-stone-gold text-[16px]">balance</span>
                                            <span class="text-base font-bold text-white">${asset.p_vp ? parseFloat(asset.p_vp).toFixed(1) + 'x' : '-'}</span>
                             </div>                          </div>
                                </div>
                            </div>
                            <div class="h-px w-full bg-stone-glassBorder"></div>
                            <div class="flex flex-col sm:flex-row justify-between gap-3 w-full shrink-0 z-10">
                                <button onclick="event.stopPropagation(); window.open('${asset.url_ri || 'https://www.google.com/search?q=' + asset.ticker + '+RI'}', '_blank')" class="flex-1 flex items-center justify-center gap-2 px-3 h-6 bg-stone-gold hover:bg-stone-goldHover text-stone-navy text-[10px] font-bold rounded-full transition-colors shadow-lg shadow-stone-gold/20">
                                    <span>RI</span>
                                    <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                </button>
                                <button onclick="event.stopPropagation(); openAssetModal('${asset.ticker}')" class="flex-1 flex items-center justify-center gap-2 px-3 h-6 bg-transparent hover:bg-white/5 border border-stone-glassBorder text-stone-gray text-[10px] font-bold rounded-full transition-colors hover:border-stone-gold hover:text-white">
                                    <span>Mais Info</span>
                                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                </button>
                            </div>
                        `;
                        grid.appendChild(card);
                    });
                })
                .catch(err => console.error('Dashboard error:', err));
        }

        // --- Chart Logic ---
        let portfolioChart = null;

        function initChart(historyData) {
            const options = {
                series: [{
                    name: 'Valor do Portfólio',
                    data: historyData.map(d => d.value)
                }],
                chart: {
                    type: 'area',
                    height: 300,
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    background: 'transparent',
                    foreColor: '#CCCCCC'
                },
                colors: ['#D4AF37'],
                dataLabels: { enabled: false },
                stroke: {
                    curve: 'smooth',
                    width: 3,
                    colors: ['#D4AF37']
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.45,
                        opacityTo: 0.05,
                        stops: [20, 100, 100, 100]
                    }
                },
                grid: {
                    borderColor: 'rgba(255, 255, 255, 0.05)',
                    strokeDashArray: 4,
                    xaxis: { lines: { show: true } },
                    yaxis: { lines: { show: true } }
                },
                xaxis: {
                    categories: historyData.map(d => {
                        const date = new Date(d.date);
                        return date.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit' });
                    }),
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        formatter: (val) => formatCurrency(val)
                    }
                },
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: (val) => formatCurrency(val)
                    }
                }
            };

            if (portfolioChart) {
                portfolioChart.destroy();
            }

            portfolioChart = new ApexCharts(document.querySelector("#portfolioChart"), options);
            portfolioChart.render();
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', loadDashboardData);
    </script>

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
                        <label for="avatar-input"
                            class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-full opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity">
                            <span class="material-symbols-outlined text-white text-3xl"
                                style="font-size: 32px;">photo_camera</span>
                        </label>
                        <input type="file" id="avatar-input" class="hidden" accept="image/*"
                            onchange="handleAvatarUpload(this)">
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

        // Profile Modal Logic
        function openProfileModal() {
            document.getElementById('profileModal').classList.remove('hidden');
            document.getElementById('userDropdown').classList.add('hidden');
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
            const filePath = `avatars/${fileName}`;

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
                        access_token: "<?php echo $_SESSION['access_token']; ?>",
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
                formData.append('access_token', "<?php echo $_SESSION['access_token']; ?>");
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
    </script>
</body>

</html>