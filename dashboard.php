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
    </style>
</head>

<body class="antialiased selection:bg-stone-gold selection:text-stone-navy flex flex-col min-h-screen">

    <!-- Elementos de Fundo -->
    <div class="fixed-bg"></div>
    <div class="bg-overlay"></div>

    <div class="relative flex min-h-screen w-full flex-col group/design-root">

        <!-- Header -->
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
                        <button
                            class="min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-9 px-4 bg-stone-glass hover:bg-stone-glassBorder text-white text-xs font-bold transition-colors border border-stone-glassBorder uppercase tracking-wider">
                            <span class="truncate">Sair</span>
                        </button>
                        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-9 ring-2 ring-stone-gold shadow-[0_0_15px_rgba(212,175,55,0.3)]"
                            style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCTCifV9f7veeImD6mpBg5MYpyLXZuX0Wn-PekVpNu3vhVQG721dQEl5WbsrR0o1vraCZDBH5trp5oRZRL1eoPcs3dQ2f-TLvIbK0zrlOY8h0HhQ2cwU_AEwwuY_aTR73AIIqfDUGiolLRlNIFv2tosDtVNg9Of2mQ6U3go3M0Stl4z-ovMmuKmAZstI_VMgVwz4eMj131GaJWanBRhtp4sq_-iwpm3rpvT2lnUsLqCG5sWw3sBN2vvSkwzE6IoKjRM1kJVgZGQng0");'>
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
                    <button
                        class="mobile-link text-stone-gray hover:text-white uppercase tracking-widest font-bold text-sm border border-stone-glassBorder px-6 py-2 rounded-full mt-4">Sair</button>
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
                    <!-- Search Input -->
                    <div class="w-full lg:max-w-xs">
                        <label
                            class="flex w-full h-10 rounded-lg bg-stone-navy/50 border border-stone-glassBorder focus-within:ring-2 focus-within:ring-stone-gold transition-all overflow-hidden">
                            <div class="flex items-center justify-center pl-3 text-stone-gray">
                                <span class="material-symbols-outlined text-lg">search</span>
                            </div>
                            <input
                                class="w-full h-full bg-transparent border-none text-white text-sm placeholder:text-stone-gray focus:ring-0 px-2 outline-none"
                                placeholder="Buscar ativo..." />
                            <button
                                class="h-full px-3 bg-stone-gold hover:bg-stone-goldHover text-stone-navy text-sm font-bold rounded-r-lg shadow-sm transition-colors">
                                Buscar
                            </button>
                        </label>
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
                        <p class="text-white text-3xl font-extrabold mt-3">R$ 452.190,50</p>
                        <p class="text-stone-gray text-xs mt-1">em 12 ativos</p>
                    </div>
                    <!-- Daily Gain/Loss -->
                    <div
                        class="bg-stone-glass rounded-xl border border-stone-glassBorder p-5 flex flex-col justify-between shadow-lg hover:border-stone-gold/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <p class="text-stone-gray text-sm font-medium uppercase tracking-wider">Ganho (24h)</p>
                            <span class="material-symbols-outlined text-xl text-success">arrow_upward</span>
                        </div>
                        <p class="text-success text-3xl font-extrabold mt-3">+R$ 2.450,12</p>
                        <p class="text-success text-xs mt-1 font-bold">+0.54%</p>
                    </div>
                    <!-- Overall Profit/Loss -->
                    <div
                        class="bg-stone-glass rounded-xl border border-stone-glassBorder p-5 flex flex-col justify-between shadow-lg hover:border-stone-gold/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <p class="text-stone-gray text-sm font-medium uppercase tracking-wider">P/L Acumulado</p>
                            <span class="material-symbols-outlined text-xl text-success">trending_up</span>
                        </div>
                        <p class="text-success text-3xl font-extrabold mt-3">R$ 54.120,00</p>
                        <p class="text-stone-gray text-xs mt-1 font-bold">Total: <span
                                class="text-success">+13.5%</span></p>
                    </div>
                    <!-- Biggest Winner -->
                    <div
                        class="bg-stone-glass rounded-xl border border-stone-glassBorder p-5 flex flex-col justify-between shadow-lg hover:border-stone-gold/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <p class="text-stone-gray text-sm font-medium uppercase tracking-wider">Melhor Ativo (Mês)
                            </p>
                            <span class="material-symbols-outlined text-xl text-stone-gold">workspace_premium</span>
                        </div>
                        <p class="text-white text-3xl font-extrabold mt-3">RENT3</p>
                        <p class="text-success text-xs mt-1 font-bold">+18.3%</p>
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
                        <div class="chart-placeholder w-full rounded-lg"></div>
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

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                        <!-- CARD 1: PETR4 -->
                        <div
                            class="relative flex flex-col gap-4 rounded-xl bg-stone-glass py-4 px-5 shadow-xl border border-stone-glassBorder overflow-hidden group hover:border-stone-gold/50 hover:shadow-2xl hover:shadow-stone-gold/10 hover:scale-[1.01] transition-all duration-300">

                            <!-- Decorative Background Blur -->
                            <div
                                class="absolute -right-10 -top-10 h-64 w-64 bg-stone-gold/5 rounded-full blur-3xl pointer-events-none">
                            </div>

                            <div
                                class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4 w-full z-10 flex-1">

                                <!-- 1. Identity & Price -->
                                <div class="flex items-start gap-4 shrink-0">
                                    <!-- Company Logo Placeholder -->
                                    <div
                                        class="h-16 w-16 shrink-0 rounded-full overflow-hidden border-2 border-stone-gold/40">
                                        <img src="https://placehold.co/80x80/050a14/D4AF37?text=P4" alt="Logotipo PETR4"
                                            class="w-full h-full object-cover" />
                                    </div>
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-2 mb-0.5">
                                            <h2 class="text-xl font-bold text-white leading-none">PETR4</h2>
                                            <span
                                                class="px-1.5 py-0.5 rounded-full bg-stone-gold/10 border border-stone-gold/20 text-[8px] font-bold uppercase tracking-wider text-stone-gold">Ação</span>
                                        </div>
                                        <p
                                            class="text-[10px] font-medium text-stone-gray mb-1.5 truncate max-w-[150px]">
                                            Petróleo Brasileiro S.A.</p>

                                        <!-- Preço atual -->
                                        <span class="text-lg font-bold text-white tracking-tight mb-0.5">R$ 34,50</span>

                                        <!-- Variação -->
                                        <div
                                            class="flex items-center text-[10px] font-semibold text-success bg-success/10 px-2 py-0.5 rounded-md w-fit">
                                            <span class="material-symbols-outlined text-[12px] mr-1">trending_up</span>
                                            <span>+1.25%</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- 2. Stats & Metrics -->
                                <div
                                    class="grid grid-cols-2 gap-x-4 gap-y-3 w-full lg:w-fit lg:min-w-[200px] lg:justify-end lg:pl-4 lg:py-1">

                                    <!-- Stat 1 -->
                                    <div class="flex flex-col gap-0.5">
                                        <span
                                            class="text-[9px] font-semibold text-stone-gray uppercase tracking-wider">Dividend
                                            Yield</span>
                                        <div class="flex items-center gap-1">
                                            <span
                                                class="material-symbols-outlined text-stone-gold text-[16px]">account_balance_wallet</span>
                                            <span class="text-base font-bold text-white">18.5%</span>
                                        </div>
                                    </div>

                                    <!-- Stat 2 -->
                                    <div class="flex flex-col gap-0.5">
                                        <span
                                            class="text-[9px] font-semibold text-stone-gray uppercase tracking-wider">P/L
                                            (P/L)</span>
                                        <div class="flex items-center gap-1">
                                            <span
                                                class="material-symbols-outlined text-stone-gold text-[16px]">paid</span>
                                            <span class="text-base font-bold text-white">5.2x</span>
                                        </div>
                                    </div>

                                    <!-- Stat 3 -->
                                    <div class="flex flex-col gap-0.5">
                                        <span
                                            class="text-[9px] font-semibold text-stone-gray uppercase tracking-wider">Crescimento
                                            12m</span>
                                        <div class="flex items-center gap-1">
                                            <span
                                                class="material-symbols-outlined text-stone-gold text-[16px]">monitoring</span>
                                            <span class="text-base font-bold text-white">+42.3%</span>
                                        </div>
                                    </div>

                                    <!-- Stat 4 -->
                                    <div class="flex flex-col gap-0.5">
                                        <span
                                            class="text-[9px] font-semibold text-stone-gray uppercase tracking-wider">P/VP
                                            (P/VP)</span>
                                        <div class="flex items-center gap-1">
                                            <span
                                                class="material-symbols-outlined text-stone-gold text-[16px]">balance</span>
                                            <span class="text-base font-bold text-white">0.95x</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Separator -->
                            <div class="h-px w-full bg-stone-glassBorder"></div>

                            <!-- 3. Right Section: Actions -->
                            <div class="flex flex-col sm:flex-row justify-between gap-3 w-full shrink-0 z-10">
                                <button
                                    class="flex-1 flex items-center justify-center gap-2 px-3 h-6 bg-stone-gold hover:bg-stone-goldHover text-stone-navy text-[10px] font-bold rounded-full transition-colors shadow-lg shadow-stone-gold/20">
                                    <span>RI</span>
                                    <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                </button>
                                <button
                                    class="flex-1 flex items-center justify-center gap-2 px-3 h-6 bg-transparent hover:bg-white/5 border border-stone-glassBorder text-stone-gray text-[10px] font-bold rounded-full transition-colors hover:border-stone-gold hover:text-white">
                                    <span>Mais Info</span>
                                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                </button>
                            </div>
                        </div>

                        <!-- CARD 2: RENT3 -->
                        <div
                            class="relative flex flex-col gap-4 rounded-xl bg-stone-glass py-4 px-5 shadow-xl border border-stone-glassBorder overflow-hidden group hover:border-stone-gold/50 hover:shadow-2xl hover:shadow-stone-gold/10 hover:scale-[1.01] transition-all duration-300">

                            <!-- Decorative Background Blur -->
                            <div
                                class="absolute -right-10 -top-10 h-64 w-64 bg-stone-gold/5 rounded-full blur-3xl pointer-events-none">
                            </div>

                            <div
                                class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4 w-full z-10 flex-1">

                                <!-- 1. Identity & Price -->
                                <div class="flex items-start gap-4 shrink-0">
                                    <!-- Company Logo Placeholder -->
                                    <div
                                        class="h-16 w-16 shrink-0 rounded-full overflow-hidden border-2 border-stone-gold/40">
                                        <img src="https://placehold.co/80x80/050a14/D4AF37?text=R3" alt="Logotipo RENT3"
                                            class="w-full h-full object-cover" />
                                    </div>
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-2 mb-0.5">
                                            <h2 class="text-xl font-bold text-white leading-none">RENT3</h2>
                                            <span
                                                class="px-1.5 py-0.5 rounded-full bg-stone-gold/10 border border-stone-gold/20 text-[8px] font-bold uppercase tracking-wider text-stone-gold">Ação</span>
                                        </div>
                                        <p
                                            class="text-[10px] font-medium text-stone-gray mb-1.5 truncate max-w-[150px]">
                                            Localiza S.A. (Aluguel)</p>

                                        <!-- Preço atual -->
                                        <span class="text-lg font-bold text-white tracking-tight mb-0.5">R$ 52,10</span>

                                        <!-- Variação -->
                                        <div
                                            class="flex items-center text-[10px] font-semibold text-danger bg-danger/10 px-2 py-0.5 rounded-md w-fit">
                                            <span
                                                class="material-symbols-outlined text-[12px] mr-1">trending_down</span>
                                            <span>-0.85%</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- 2. Stats & Metrics -->
                                <div
                                    class="grid grid-cols-2 gap-x-4 gap-y-3 w-full lg:w-fit lg:min-w-[200px] lg:justify-end lg:pl-4 lg:py-1">

                                    <!-- Stat 1 -->
                                    <div class="flex flex-col gap-0.5">
                                        <span
                                            class="text-[9px] font-semibold text-stone-gray uppercase tracking-wider">Dividend
                                            Yield</span>
                                        <div class="flex items-center gap-1">
                                            <span
                                                class="material-symbols-outlined text-stone-gold text-[16px]">account_balance_wallet</span>
                                            <span class="text-base font-bold text-white">1.2%</span>
                                        </div>
                                    </div>

                                    <!-- Stat 2 -->
                                    <div class="flex flex-col gap-0.5">
                                        <span
                                            class="text-[9px] font-semibold text-stone-gray uppercase tracking-wider">P/L
                                            (P/L)</span>
                                        <div class="flex items-center gap-1">
                                            <span
                                                class="material-symbols-outlined text-stone-gold text-[16px]">paid</span>
                                            <span class="text-base font-bold text-white">25.8x</span>
                                        </div>
                                    </div>

                                    <!-- Stat 3 -->
                                    <div class="flex flex-col gap-0.5">
                                        <span
                                            class="text-[9px] font-semibold text-stone-gray uppercase tracking-wider">Crescimento
                                            12m</span>
                                        <div class="flex items-center gap-1">
                                            <span
                                                class="material-symbols-outlined text-stone-gold text-[16px]">monitoring</span>
                                            <span class="text-base font-bold text-white">+18.3%</span>
                                        </div>
                                    </div>

                                    <!-- Stat 4 -->
                                    <div class="flex flex-col gap-0.5">
                                        <span
                                            class="text-[9px] font-semibold text-stone-gray uppercase tracking-wider">P/VP
                                            (P/VP)</span>
                                        <div class="flex items-center gap-1">
                                            <span
                                                class="material-symbols-outlined text-stone-gold text-[16px]">balance</span>
                                            <span class="text-base font-bold text-white">3.1x</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Separator -->
                            <div class="h-px w-full bg-stone-glassBorder"></div>

                            <!-- 3. Right Section: Actions -->
                            <div class="flex flex-col sm:flex-row justify-between gap-3 w-full shrink-0 z-10">
                                <button
                                    class="flex-1 flex items-center justify-center gap-2 px-3 h-6 bg-stone-gold hover:bg-stone-goldHover text-stone-navy text-[10px] font-bold rounded-full transition-colors shadow-lg shadow-stone-gold/20">
                                    <span>RI</span>
                                    <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                </button>
                                <button
                                    class="flex-1 flex items-center justify-center gap-2 px-3 h-6 bg-transparent hover:bg-white/5 border border-stone-glassBorder text-stone-gray text-[10px] font-bold rounded-full transition-colors hover:border-stone-gold hover:text-white">
                                    <span>Mais Info</span>
                                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                </button>
                            </div>
                        </div>

                        <!-- CARD 3: ROMI3 -->
                        <div
                            class="relative flex flex-col gap-4 rounded-xl bg-stone-glass py-4 px-5 shadow-xl border border-stone-glassBorder overflow-hidden group hover:border-stone-gold/50 hover:shadow-2xl hover:shadow-stone-gold/10 hover:scale-[1.01] transition-all duration-300 h-full">

                            <!-- Decorative Background Blur -->
                            <div
                                class="absolute -right-10 -top-10 h-64 w-64 bg-stone-gold/5 rounded-full blur-3xl pointer-events-none">
                            </div>

                            <div
                                class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4 w-full z-10 flex-1">

                                <div class="flex items-start gap-4 shrink-0">
                                    <!-- Company Logo Placeholder -->
                                    <div
                                        class="h-16 w-16 shrink-0 rounded-full overflow-hidden border-2 border-stone-gold/40">
                                        <img src="https://placehold.co/80x80/050a14/D4AF37?text=R3" alt="Logotipo ROMI3"
                                            class="w-full h-full object-cover" />
                                    </div>
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-2 mb-0.5">
                                            <h2 class="text-xl font-bold text-white leading-none">ROMI3</h2>
                                            <span
                                                class="px-1.5 py-0.5 rounded-full bg-stone-gold/10 border border-stone-gold/20 text-[8px] font-bold uppercase tracking-wider text-stone-gold">Ação</span>
                                        </div>
                                        <p
                                            class="text-[10px] font-medium text-stone-gray mb-1.5 truncate max-w-[150px]">
                                            Indústrias Romi S.A.</p>

                                        <!-- Preço atual -->
                                        <span class="text-lg font-bold text-white tracking-tight mb-0.5">R$
                                            14,20</span>

                                        <!-- Variação -->
                                        <div
                                            class="flex items-center text-[10px] font-semibold text-success bg-success/10 px-2 py-0.5 rounded-md w-fit">
                                            <span class="material-symbols-outlined text-[12px] mr-1">trending_up</span>
                                            <span>+1.50%</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- 2. Stats & Metrics -->
                                <div
                                    class="grid grid-cols-2 gap-x-4 gap-y-3 w-full lg:w-fit lg:min-w-[200px] lg:justify-end lg:pl-4 lg:py-1">

                                    <!-- Stat 1 -->
                                    <div class="flex flex-col gap-0.5">
                                        <span
                                            class="text-[9px] font-semibold text-stone-gray uppercase tracking-wider">Dividend
                                            Yield</span>
                                        <div class="flex items-center gap-1">
                                            <span
                                                class="material-symbols-outlined text-stone-gold text-[16px]">account_balance_wallet</span>
                                            <span class="text-base font-bold text-white">4.5%</span>
                                        </div>
                                    </div>

                                    <!-- Stat 2 -->
                                    <div class="flex flex-col gap-0.5">
                                        <span
                                            class="text-[9px] font-semibold text-stone-gray uppercase tracking-wider">P/L
                                            (P/L)</span>
                                        <div class="flex items-center gap-1">
                                            <span
                                                class="material-symbols-outlined text-stone-gold text-[16px]">paid</span>
                                            <span class="text-base font-bold text-white">8.1x</span>
                                        </div>
                                    </div>

                                    <!-- Stat 3 -->
                                    <div class="flex flex-col gap-0.5">
                                        <span
                                            class="text-[9px] font-semibold text-stone-gray uppercase tracking-wider">Crescimento
                                            12m</span>
                                        <div class="flex items-center gap-1">
                                            <span
                                                class="material-symbols-outlined text-stone-gold text-[16px]">monitoring</span>
                                            <span class="text-base font-bold text-white">+5.4%</span>
                                        </div>
                                    </div>

                                    <!-- Stat 4 -->
                                    <div class="flex flex-col gap-0.5">
                                        <span
                                            class="text-[9px] font-semibold text-stone-gray uppercase tracking-wider">P/VP
                                            (P/VP)</span>
                                        <div class="flex items-center gap-1">
                                            <span
                                                class="material-symbols-outlined text-stone-gold text-[16px]">balance</span>
                                            <span class="text-base font-bold text-white">1.2x</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Separator -->
                            <div class="h-px w-full bg-stone-glassBorder"></div>

                            <!-- 3. Right Section: Actions -->
                            <div class="flex flex-col sm:flex-row justify-between gap-3 w-full shrink-0 z-10">
                                <button
                                    class="flex-1 flex items-center justify-center gap-2 px-3 h-6 bg-stone-gold hover:bg-stone-goldHover text-stone-navy text-[10px] font-bold rounded-full transition-colors shadow-lg shadow-stone-gold/20">
                                    <span>RI</span>
                                    <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                </button>
                                <button onclick="openAssetModal('ROMI3')"
                                    class="flex-1 flex items-center justify-center gap-2 px-3 h-6 bg-transparent hover:bg-white/5 border border-stone-glassBorder text-stone-gray text-[10px] font-bold rounded-full transition-colors hover:border-stone-gold hover:text-white">
                                    <span>Mais Info</span>
                                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                </button>
                            </div>
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
                        <button type="button" class="text-stone-gray hover:text-white transition-colors"
                            onclick="closeAssetModal()">
                            <span class="material-symbols-outlined">close</span>
                        </button>
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

                                <!-- 3. Valuation -->
                                <div>
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
                                <div>
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
                                <div>
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

    <script>
        // --- Mobile Menu Toggle ---
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');

        if (btn && menu) {
            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
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
                    document.getElementById('modalLogo').src = `https://placehold.co/80x80/050a14/D4AF37?text=${ticker.substring(0, 2)}`;
                    document.getElementById('modalType').textContent = asset.tipo || 'Ação';
                    document.getElementById('modalSector').textContent = asset.setor || 'N/A';
                    document.getElementById('modalPrice').textContent = formatCurrency(asset.cotacao);
                    document.getElementById('modalDate').textContent = 'Atualizado em: ' + formatDate(asset.data_ultima_cotacao);

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
    </script>

</body>

</html>