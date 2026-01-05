<!DOCTYPE html>
<html lang="pt-BR" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Análise de Ativos | Stone Edger</title>
    <meta name="description" content="Análise Fundamentalista de Ativos - Stone Edger.">

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
            background-image: url('img/logo.png');
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
            background: rgba(0, 0, 0, 0.95);
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

        /* --- Speedometer Gauge Styles --- */
        .graph-container {
            --size: 12rem;
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
            font-size: 1.2rem;
            color: #fff;
            position: absolute;
            left: -1.5rem;
            top: 1.5rem;
            --emo-rotate: 90deg;
            transform: rotate(var(--emo-rotate));
            text-shadow: 0 0 5px rgba(0,0,0,0.5);
        }

        .slice:nth-child(1) { --stroke-color: #10b981; --rotate: 0deg; }
        .slice:nth-child(1) .fa-regular { --emo-rotate: 0deg; }
        .slice:nth-child(2) { --stroke-color: #6ee7b7; --rotate: 36deg; }
        .slice:nth-child(2) .fa-regular { --emo-rotate: -36deg; }
        .slice:nth-child(3) { --stroke-color: #f59e0b; --rotate: 72deg; }
        .slice:nth-child(3) .fa-regular { --emo-rotate: -72deg; }
        .slice:nth-child(4) { --stroke-color: #f87171; --rotate: 108deg; }
        .slice:nth-child(4) .fa-regular { --emo-rotate: -108deg; }
        .slice:nth-child(5) { --stroke-color: #ef4444; --rotate: 144deg; }
        .slice:nth-child(5) .fa-regular { --emo-rotate: -144deg; }

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
            --turn: calc(45deg + (36 * calc(var(--rating)* 1deg)) );
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
                        class="text-stone-gray hover:text-stone-gold transition-colors text-sm uppercase tracking-widest font-medium">Portfólio</a>
                    <a href="analise.php"
                        class="text-stone-gold transition-colors text-sm uppercase tracking-widest font-bold border-b-2 border-stone-gold pb-1">Análise</a>
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
                        class="mobile-link text-white text-lg hover:text-stone-gold uppercase tracking-widest font-bold">Portfólio</a>
                    <a href="analise.php"
                        class="mobile-link text-stone-gold text-lg uppercase tracking-widest font-bold">Análise</a>
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
                    class="flex flex-col gap-6 items-center justify-center bg-stone-glass p-8 md:p-12 rounded-2xl border border-stone-glassBorder text-center">
                    <div class="flex flex-col gap-2">
                        <h1
                            class="text-white text-3xl md:text-5xl font-playfair font-bold leading-tight tracking-tight">
                            Análise Fundamentalista</h1>
                        <p class="text-stone-gray text-base font-normal max-w-xl mx-auto">Pesquise por um ativo para
                            visualizar seus indicadores completos.</p>
                    </div>

                    <!-- Search Input -->
                    <div class="w-full max-w-lg relative">
                        <label
                            class="flex w-full h-14 rounded-full bg-stone-navy/80 border border-stone-glassBorder focus-within:ring-2 focus-within:ring-stone-gold transition-all overflow-hidden shadow-xl">
                            <div class="flex items-center justify-center pl-5 text-stone-gray">
                                <span class="material-symbols-outlined text-xl">search</span>
                            </div>
                            <input id="tickerInput"
                                class="w-full h-full bg-transparent border-none text-white text-lg placeholder:text-stone-gray focus:ring-0 px-4 outline-none uppercase"
                                placeholder="Digite o ticker (ex: PETR4)" onkeypress="handleEnter(event)" />
                            <button onclick="searchAsset()"
                                class="h-full px-8 bg-stone-gold hover:bg-stone-goldHover text-stone-navy text-base font-bold rounded-r-full shadow-sm transition-colors">
                                Pesquisar
                            </button>
                        </label>
                        <p id="searchError"
                            class="absolute -bottom-6 left-0 w-full text-center text-danger text-sm hidden"></p>
                    </div>
                </div>

                <!-- Results Section (Initially Hidden) -->
                <div id="resultsContainer" class="hidden flex-col gap-6 animate-fade-in">

                    <!-- Loading State -->
                    <div id="loadingState" class="hidden flex justify-center py-12">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-stone-gold"></div>
                    </div>

                    <!-- Data Content -->
                    <div id="dataContent"
                        class="hidden bg-stone-glass rounded-2xl border border-stone-glassBorder p-6 md:p-8 shadow-2xl">

                        <!-- 1. Resumo -->
                        <div class="flex flex-col md:flex-row gap-6 items-start mb-8">
                            <div
                                class="h-24 w-24 shrink-0 rounded-full border-2 border-stone-gold/40 overflow-hidden bg-stone-navy">
                                <img id="resLogo" src="" alt="Logo" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 w-full">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h2 id="resTicker"
                                            class="text-4xl font-bold text-white leading-none font-playfair"></h2>
                                        <p id="resName" class="text-stone-gray text-lg mt-1"></p>
                                        <div class="flex gap-2 mt-3">
                                            <span id="resType"
                                                class="px-3 py-1 rounded-full bg-stone-gold/10 border border-stone-gold/20 text-xs font-bold uppercase tracking-wider text-stone-gold"></span>
                                            <span id="resSector"
                                                class="px-3 py-1 rounded-full bg-stone-glass border border-stone-glassBorder text-xs font-bold uppercase tracking-wider text-stone-gray"></span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-stone-gray uppercase tracking-wider">Cotação Atual</p>
                                        <p id="resPrice" class="text-4xl font-bold text-white mt-1"></p>
                                        <p id="resDate" class="text-xs text-stone-gray mt-1"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="h-px w-full bg-stone-glassBorder mb-8"></div>

                        <!-- 2. Oscilações -->
                        <div class="mb-8">
                            <h4
                                class="text-stone-gold font-bold text-base uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="material-symbols-outlined text-xl">trending_up</span> Oscilações
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">Dia</p>
                                    <p id="oscDay" class="font-bold text-white text-lg"></p>
                                </div>
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">Mês</p>
                                    <p id="oscMonth" class="font-bold text-white text-lg"></p>
                                </div>
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">30 Dias</p>
                                    <p id="osc30d" class="font-bold text-white text-lg"></p>
                                </div>
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">12 Meses</p>
                                    <p id="osc12m" class="font-bold text-white text-lg"></p>
                                </div>
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">2025</p>
                                    <p id="osc2025" class="font-bold text-white text-lg"></p>
                                </div>
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">2024</p>
                                    <p id="osc2024" class="font-bold text-white text-lg"></p>
                                </div>
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">2023</p>
                                    <p id="osc2023" class="font-bold text-white text-lg"></p>
                                </div>
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">2022</p>
                                    <p id="osc2022" class="font-bold text-white text-lg"></p>
                                </div>
                            </div>
                        </div>

                        <!-- 2.5 Margem de Segurança (Speedometers) -->
                        <div class="mb-12">
                            <h4 class="text-stone-gold font-bold text-base uppercase tracking-wider mb-8 flex items-center gap-2">
                                <span class="material-symbols-outlined text-xl">speed</span> Margem de Segurança (Preço Justo)
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                <!-- Graham Gauge -->
                                <div class="flex flex-col items-center gap-4 bg-stone-navy/20 p-6 rounded-2xl border border-stone-glassBorder/30">
                                    <p class="text-xs text-stone-gray uppercase tracking-widest font-bold">Método Graham</p>
                                    <div class="graph-container" id="gaugeGraham" style="--rating: 2.5;">
                                        <div class="half-donut">
                                            <div class="slice"><i class="fa-regular fa-face-grin-hearts"></i></div>
                                            <div class="slice"><i class="fa-regular fa-face-smile"></i></div>
                                            <div class="slice"><i class="fa-regular fa-face-meh"></i></div>
                                            <div class="slice"><i class="fa-regular fa-face-frown"></i></div>
                                            <div class="slice"><i class="fa-regular fa-face-grimace"></i></div>
                                        </div>
                                        <div class="marker"></div>
                                    </div>
                                    <div class="text-center">
                                        <p id="labelGraham" class="text-lg font-bold text-white">N/A</p>
                                        <p id="subGraham" class="text-[10px] text-stone-gray uppercase tracking-tighter mt-1"></p>
                                    </div>
                                </div>

                                <!-- Average Gauge -->
                                <div class="flex flex-col items-center gap-4 bg-stone-navy/20 p-6 rounded-2xl border border-stone-gold/20 shadow-[0_0_20px_rgba(212,175,55,0.05)]">
                                    <p class="text-xs text-stone-gold uppercase tracking-widest font-bold">Média Ponderada</p>
                                    <div class="graph-container" id="gaugeAverage" style="--rating: 2.5;">
                                        <div class="half-donut">
                                            <div class="slice"><i class="fa-regular fa-face-grin-hearts"></i></div>
                                            <div class="slice"><i class="fa-regular fa-face-smile"></i></div>
                                            <div class="slice"><i class="fa-regular fa-face-meh"></i></div>
                                            <div class="slice"><i class="fa-regular fa-face-frown"></i></div>
                                            <div class="slice"><i class="fa-regular fa-face-grimace"></i></div>
                                        </div>
                                        <div class="marker"></div>
                                    </div>
                                    <div class="text-center">
                                        <p id="labelAverage" class="text-lg font-bold text-white">N/A</p>
                                        <p id="subAverage" class="text-[10px] text-stone-gray uppercase tracking-tighter mt-1"></p>
                                    </div>
                                </div>

                                <!-- Bazin Gauge -->
                                <div class="flex flex-col items-center gap-4 bg-stone-navy/20 p-6 rounded-2xl border border-stone-glassBorder/30">
                                    <p class="text-xs text-stone-gray uppercase tracking-widest font-bold">Método Bazin</p>
                                    <div class="graph-container" id="gaugeBazin" style="--rating: 2.5;">
                                        <div class="half-donut">
                                            <div class="slice"><i class="fa-regular fa-face-grin-hearts"></i></div>
                                            <div class="slice"><i class="fa-regular fa-face-smile"></i></div>
                                            <div class="slice"><i class="fa-regular fa-face-meh"></i></div>
                                            <div class="slice"><i class="fa-regular fa-face-frown"></i></div>
                                            <div class="slice"><i class="fa-regular fa-face-grimace"></i></div>
                                        </div>
                                        <div class="marker"></div>
                                    </div>
                                    <div class="text-center">
                                        <p id="labelBazin" class="text-lg font-bold text-white">N/A</p>
                                        <p id="subBazin" class="text-[10px] text-stone-gray uppercase tracking-tighter mt-1"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Valuation -->
                        <div class="mb-8">
                            <h4
                                class="text-stone-gold font-bold text-base uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="material-symbols-outlined text-xl">balance</span> Indicadores de Valuation
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">P/L</p>
                                    <p id="valPL" class="font-bold text-white text-lg"></p>
                                </div>
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">P/VP</p>
                                    <p id="valPVP" class="font-bold text-white text-lg"></p>
                                </div>
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">Div. Yield</p>
                                    <p id="valDY" class="font-bold text-white text-lg"></p>
                                </div>
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">EV/EBITDA</p>
                                    <p id="valEVEBITDA" class="font-bold text-white text-lg"></p>
                                </div>
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">P/EBIT</p>
                                    <p id="valPEBIT" class="font-bold text-white text-lg"></p>
                                </div>
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">PSR</p>
                                    <p id="valPSR" class="font-bold text-white text-lg"></p>
                                </div>
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">P/Ativos</p>
                                    <p id="valPAssets" class="font-bold text-white text-lg"></p>
                                </div>
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">P/Cap. Giro</p>
                                    <p id="valPCapGiro" class="font-bold text-white text-lg"></p>
                                </div>
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">Graham (Justo)</p>
                                    <p id="valGraham" class="font-bold text-white text-lg"></p>
                                </div>
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">Bazin (Justo)</p>
                                    <p id="valBazin" class="font-bold text-white text-lg"></p>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Rentabilidade & Eficiência -->
                        <div class="mb-8">
                            <h4
                                class="text-stone-gold font-bold text-base uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="material-symbols-outlined text-xl">monitoring</span> Rentabilidade &
                                Eficiência
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">ROE</p>
                                    <p id="effROE" class="font-bold text-white text-lg"></p>
                                </div>
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">ROIC</p>
                                    <p id="effROIC" class="font-bold text-white text-lg"></p>
                                </div>
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">Margem Bruta</p>
                                    <p id="effGrossMargin" class="font-bold text-white text-lg"></p>
                                </div>
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">Margem Líquida</p>
                                    <p id="effNetMargin" class="font-bold text-white text-lg"></p>
                                </div>
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">Margem EBIT</p>
                                    <p id="effEBITMargin" class="font-bold text-white text-lg"></p>
                                </div>
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">Giro Ativos</p>
                                    <p id="effAssetTurnover" class="font-bold text-white text-lg"></p>
                                </div>
                            </div>
                        </div>

                        <!-- 5. Balanço & Resultados -->
                        <div>
                            <h4
                                class="text-stone-gold font-bold text-base uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="material-symbols-outlined text-xl">account_balance</span> Balanço &
                                Resultados
                            </h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">Valor de Mercado</p>
                                    <p id="finMarketCap" class="font-bold text-white text-lg"></p>
                                </div>
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">Valor da Firma</p>
                                    <p id="finFirmValue" class="font-bold text-white text-lg"></p>
                                </div>
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">Patrimônio Líquido</p>
                                    <p id="finEquity" class="font-bold text-white text-lg"></p>
                                </div>
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">Dívida Líquida</p>
                                    <p id="finNetDebt" class="font-bold text-white text-lg"></p>
                                </div>
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">Receita Líquida (12m)</p>
                                    <p id="finNetRevenue12m" class="font-bold text-white text-lg"></p>
                                </div>
                                <div class="bg-stone-navy/30 p-4 rounded-xl border border-stone-glassBorder">
                                    <p class="text-xs text-stone-gray uppercase">Lucro Líquido (12m)</p>
                                    <p id="finNetIncome12m" class="font-bold text-white text-lg"></p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </main>
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

        function handleEnter(event) {
            if (event.key === 'Enter') {
                searchAsset();
            }
        }

        function searchAsset() {
            const tickerInput = document.getElementById('tickerInput');
            const ticker = tickerInput.value.trim().toUpperCase();
            const errorMsg = document.getElementById('searchError');
            const resultsContainer = document.getElementById('resultsContainer');
            const loadingState = document.getElementById('loadingState');
            const dataContent = document.getElementById('dataContent');

            if (!ticker) {
                errorMsg.textContent = 'Por favor, digite um ticker.';
                errorMsg.classList.remove('hidden');
                return;
            }

            // Reset state
            errorMsg.classList.add('hidden');
            resultsContainer.classList.remove('hidden');
            loadingState.classList.remove('hidden');
            dataContent.classList.add('hidden');

            // Fetch data
            fetch(`api/get_asset_details.php?ticker=${ticker}`)
                .then(response => {
                    if (!response.ok) {
                        if (response.status === 404) {
                            throw new Error('Ativo não encontrado na base de dados.');
                        } else if (response.status === 500) {
                            throw new Error('Erro interno no servidor ao processar a consulta.');
                        }
                        throw new Error('Erro na conexão com o servidor.');
                    }
                    return response.json();
                })
                .then(assetData => {
                    const asset = Array.isArray(assetData) ? assetData[0] : assetData;

                    if (!asset) {
                        throw new Error('Ativo não encontrado. Verifique o ticker e tente novamente.');
                    }

                    // --- Populate Data ---

                    // 1. Resumo
                    document.getElementById('resTicker').textContent = asset.papel || ticker;
                    document.getElementById('resName').textContent = asset.empresa || ticker;
                    document.getElementById('resLogo').src = `https://placehold.co/80x80/050a14/D4AF37?text=${ticker.substring(0, 2)}`;
                    document.getElementById('resType').textContent = asset.tipo || 'Ação';
                    document.getElementById('resSector').textContent = asset.setor || 'N/A';
                    document.getElementById('resPrice').textContent = formatCurrency(asset.cotacao);
                    document.getElementById('resDate').textContent = 'Atualizado em: ' + formatDate(asset.data_ultima_cotacao);

                    // 2. Oscilações
                    colorizePercent('oscDay', asset.osc_dia);
                    colorizePercent('oscMonth', asset.osc_mes);
                    colorizePercent('osc30d', asset.osc_30_dias);
                    colorizePercent('osc12m', asset.osc_12_meses);
                    colorizePercent('osc2025', asset.osc_2025);
                    colorizePercent('osc2024', asset.osc_2024);
                    colorizePercent('osc2023', asset.osc_2023);
                    colorizePercent('osc2022', asset.osc_2022);

                    // 3. Valuation
                    document.getElementById('valPL').textContent = asset.p_l ? parseFloat(asset.p_l).toFixed(2) : '-';
                    document.getElementById('valPVP').textContent = asset.p_vp ? parseFloat(asset.p_vp).toFixed(2) : '-';
                    document.getElementById('valDY').textContent = formatPercent(asset.div_yield);
                    document.getElementById('valEVEBITDA').textContent = asset.ev_ebitda ? parseFloat(asset.ev_ebitda).toFixed(2) : '-';
                    document.getElementById('valPEBIT').textContent = asset.p_ebit ? parseFloat(asset.p_ebit).toFixed(2) : '-';
                    document.getElementById('valPSR').textContent = asset.psr ? parseFloat(asset.psr).toFixed(2) : '-';
                    document.getElementById('valPAssets').textContent = asset.p_ativos ? parseFloat(asset.p_ativos).toFixed(2) : '-';
                    document.getElementById('valPCapGiro').textContent = asset.p_cap_giro ? parseFloat(asset.p_cap_giro).toFixed(2) : '-';

                    // Graham Calculation: Sqrt(22.5 * LPA * VPA)
                    let grahamPrice = null;
                    if (asset.lpa > 0 && asset.vpa > 0) {
                        grahamPrice = Math.sqrt(22.5 * parseFloat(asset.lpa) * parseFloat(asset.vpa));
                    }
                    document.getElementById('valGraham').textContent = grahamPrice ? formatCurrency(grahamPrice) : 'N/A';

                    // Bazin Calculation: (Price * Yield) / 0.06
                    let bazinPrice = null;
                    if (asset.cotacao && asset.div_yield) {
                        // Dividend Paid = Price * Yield
                        const dividendPaid = parseFloat(asset.cotacao) * parseFloat(asset.div_yield);
                        bazinPrice = dividendPaid / 0.06;
                    }
                    document.getElementById('valBazin').textContent = bazinPrice ? formatCurrency(bazinPrice) : 'N/A';

                    // 4. Rentabilidade
                    document.getElementById('effROE').textContent = formatPercent(asset.roe);
                    document.getElementById('effROIC').textContent = formatPercent(asset.roic);
                    document.getElementById('effGrossMargin').textContent = formatPercent(asset.marg_bruta);
                    document.getElementById('effNetMargin').textContent = formatPercent(asset.marg_liquida);
                    document.getElementById('effEBITMargin').textContent = formatPercent(asset.marg_ebit);
                    document.getElementById('effAssetTurnover').textContent = asset.giro_ativos ? parseFloat(asset.giro_ativos).toFixed(2) : '-';

                    // 5. Balanço
                    document.getElementById('finMarketCap').textContent = formatCurrency(asset.valor_mercado);
                    document.getElementById('finFirmValue').textContent = formatCurrency(asset.valor_firma);
                    document.getElementById('finEquity').textContent = formatCurrency(asset.patrimonio_liquido);
                    document.getElementById('finNetDebt').textContent = formatCurrency(asset.divida_liquida);
                    document.getElementById('finNetRevenue12m').textContent = formatCurrency(asset.receita_liquida_12m);
                    document.getElementById('finNetIncome12m').textContent = formatCurrency(asset.lucro_liquido_12m);

                    // --- Speedometer Ratings ---
                    const curPrice = parseFloat(asset.cotacao);

                    function updateGauge(gaugeId, labelId, subId, fairValue) {
                        const gauge = document.getElementById(gaugeId);
                        const label = document.getElementById(labelId);
                        const sub = document.getElementById(subId);

                        if (!fairValue || !curPrice || fairValue <= 0) {
                            label.textContent = 'N/A';
                            sub.textContent = 'Indisponível';
                            gauge.style.setProperty('--rating', 2.5);
                            return;
                        }

                        const ratio = curPrice / fairValue;
                        // Rating logic: 
                        // 0 is Very Cheap (Ratio <= 0.5)
                        // 2.5 is Fair (Ratio == 1.0)
                        // 5 is Very Expensive (Ratio >= 1.5)
                        const rating = Math.min(Math.max((ratio - 0.5) * 5, 0), 5);
                        
                        gauge.style.setProperty('--rating', rating);
                        label.textContent = formatCurrency(fairValue);
                        
                        const upside = ((fairValue / curPrice) - 1) * 100;
                        if (upside > 0) {
                            sub.textContent = `Desconto: ${upside.toFixed(1)}%`;
                            sub.className = "text-[10px] text-success uppercase tracking-tighter mt-1";
                        } else {
                            sub.textContent = `Ágio: ${Math.abs(upside).toFixed(1)}%`;
                            sub.className = "text-[10px] text-danger uppercase tracking-tighter mt-1";
                        }
                    }

                    // Update Graham
                    updateGauge('gaugeGraham', 'labelGraham', 'subGraham', grahamPrice);

                    // Update Bazin
                    updateGauge('gaugeBazin', 'labelBazin', 'subBazin', bazinPrice);

                    // Update Average
                    let avgPrice = null;
                    if (grahamPrice && bazinPrice) {
                        avgPrice = (grahamPrice + bazinPrice) / 2;
                    } else if (grahamPrice) {
                        avgPrice = grahamPrice;
                    } else if (bazinPrice) {
                        avgPrice = bazinPrice;
                    }
                    updateGauge('gaugeAverage', 'labelAverage', 'subAverage', avgPrice);

                    // Show data
                    loadingState.classList.add('hidden');
                    dataContent.classList.remove('hidden');
                })
                .catch(err => {
                    console.error('Error:', err);
                    loadingState.classList.add('hidden');
                    errorMsg.textContent = err.message;
                    errorMsg.classList.remove('hidden');
                });
        }
    </script>

</body>

</html>