<?php
session_start();

// Basic protection: Redirect if not logged in or not an admin
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    // If logged in but not an admin, redirect to user dashboard or home
    header('Location: index.php?error=unauthorized');
    exit;
}

$admin_name = $_SESSION['user_name'] ?? '';
$admin_email = $_SESSION['user_email'] ?? '';
$display_name = !empty($admin_name) ? $admin_name : (!empty($admin_email) ? explode('@', $admin_email)[0] : 'Admin');
?>
<!DOCTYPE html>

<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Dashboard Principal - STONE EDGER</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&amp;family=Noto+Sans:wght@400;500;700&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#D4AF37",
                        "background-light": "#f6f6f8",
                        "background-dark": "#050a14",
                        "surface-dark": "#0a1221",
                        "surface-light": "#ffffff",
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
                        "display": ["Manrope", "sans-serif"],
                        "body": ["Noto Sans", "sans-serif"],
                    },
                    borderRadius: { "DEFAULT": "0.375rem", "lg": "0.5rem", "xl": "0.75rem", "2xl": "1rem", "full": "9999px" },
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Manrope', sans-serif;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-white overflow-hidden">
    <div class="flex h-screen w-full">
        <!-- Side Navigation -->
        <aside
            class="flex w-64 flex-col bg-white dark:bg-[#0a1221] border-r border-slate-200 dark:border-slate-800 flex-shrink-0 transition-all duration-300">
            <div class="flex h-full flex-col justify-between p-4">
                <div class="flex flex-col gap-6">
                    <!-- Brand -->
                    <div class="flex items-center gap-3 px-2">
                        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 bg-primary/20 flex items-center justify-center text-primary"
                            data-alt="Stone Edger logo abstract">
                            <span class="material-symbols-outlined text-2xl">diamond</span>
                        </div>
                        <div class="flex flex-col">
                            <h1 class="text-slate-900 dark:text-white text-base font-bold leading-normal tracking-wide">
                                STONE EDGER</h1>
                            <p
                                class="text-slate-500 dark:text-[#92a4c9] text-xs font-normal leading-normal uppercase tracking-wider">
                                Admin Console</p>
                        </div>
                    </div>
                    <!-- Navigation Links -->
                    <nav class="flex flex-col gap-2">
                        <a class="flex items-center gap-3 px-3 py-3 rounded-lg bg-primary text-white transition-colors"
                            href="#">
                            <span class="material-symbols-outlined"
                                style="font-variation-settings: 'FILL' 1;">dashboard</span>
                            <p class="text-sm font-medium leading-normal">Dashboard</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-3 rounded-lg text-slate-600 dark:text-[#92a4c9] hover:bg-slate-100 dark:hover:bg-[#232f48] hover:text-slate-900 dark:hover:text-white transition-colors"
                            href="#">
                            <span class="material-symbols-outlined">group</span>
                            <p class="text-sm font-medium leading-normal">Usuários</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-3 rounded-lg text-slate-600 dark:text-[#92a4c9] hover:bg-slate-100 dark:hover:bg-[#232f48] hover:text-slate-900 dark:hover:text-white transition-colors"
                            href="#">
                            <span class="material-symbols-outlined">menu_book</span>
                            <p class="text-sm font-medium leading-normal">Conteúdos</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-3 rounded-lg text-slate-600 dark:text-[#92a4c9] hover:bg-slate-100 dark:hover:bg-[#232f48] hover:text-slate-900 dark:hover:text-white transition-colors"
                            href="#">
                            <span class="material-symbols-outlined">trending_up</span>
                            <p class="text-sm font-medium leading-normal">Evolução</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-3 rounded-lg text-slate-600 dark:text-[#92a4c9] hover:bg-slate-100 dark:hover:bg-[#232f48] hover:text-slate-900 dark:hover:text-white transition-colors"
                            href="#">
                            <span class="material-symbols-outlined">description</span>
                            <p class="text-sm font-medium leading-normal">Reports</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-3 rounded-lg text-slate-600 dark:text-[#92a4c9] hover:bg-slate-100 dark:hover:bg-[#232f48] hover:text-slate-900 dark:hover:text-white transition-colors"
                            href="#">
                            <span class="material-symbols-outlined">settings</span>
                            <p class="text-sm font-medium leading-normal">Settings</p>
                        </a>
                    </nav>
                </div>
                <!-- Footer Action -->
                <a href="logout.php"
                    class="flex items-center gap-3 px-3 py-3 rounded-lg text-slate-600 dark:text-[#92a4c9] hover:bg-slate-100 dark:hover:bg-[#232f48] hover:text-red-500 dark:hover:text-red-400 cursor-pointer transition-colors">
                    <span class="material-symbols-outlined">logout</span>
                    <p class="text-sm font-medium leading-normal">Sign Out</p>
                </a>
            </div>
        </aside>
        <!-- Main Content Wrapper -->
        <main class="flex-1 flex flex-col h-full overflow-hidden relative">
            <!-- Top Navigation -->
            <header
                class="flex items-center justify-between whitespace-nowrap border-b border-solid border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0a1221] px-6 py-4 flex-shrink-0 z-20">
                <div class="flex items-center gap-4">
                    <button
                        class="text-slate-500 hover:text-slate-800 dark:text-[#92a4c9] dark:hover:text-white lg:hidden">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <div class="flex items-center gap-2 text-slate-900 dark:text-white">
                        <span class="material-symbols-outlined text-primary">analytics</span>
                        <h2 class="text-lg font-bold leading-tight tracking-[-0.015em]">Dashboard</h2>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <!-- Search -->
                    <div class="hidden md:flex flex-col min-w-40 !h-10 w-64">
                        <div
                            class="flex w-full flex-1 items-stretch rounded-lg h-full bg-slate-100 dark:bg-[#232f48] overflow-hidden group focus-within:ring-2 focus-within:ring-primary/50 transition-all">
                            <div
                                class="text-slate-500 dark:text-[#92a4c9] flex border-none items-center justify-center pl-3">
                                <span class="material-symbols-outlined text-[20px]">search</span>
                            </div>
                            <input
                                class="flex w-full min-w-0 flex-1 resize-none overflow-hidden border-none bg-transparent h-full placeholder:text-slate-400 dark:placeholder:text-[#92a4c9]/70 px-3 text-sm font-normal leading-normal text-slate-900 dark:text-white focus:outline-none focus:ring-0"
                                placeholder="Search data..." />
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <button
                            class="relative flex items-center justify-center rounded-lg size-10 text-slate-600 dark:text-[#92a4c9] hover:bg-slate-100 dark:hover:bg-[#232f48] transition-colors">
                            <span class="material-symbols-outlined">notifications</span>
                            <span
                                class="absolute top-2 right-2 size-2 bg-red-500 rounded-full border-2 border-white dark:border-[#111722]"></span>
                        </button>
                        <div class="w-px h-8 bg-slate-200 dark:bg-slate-800 mx-1"></div>
                        <div class="relative">
                            <button id="userMenuBtn"
                                class="flex items-center gap-3 cursor-pointer focus:outline-none group">
                                <div class="text-right hidden sm:block">
                                    <p class="text-sm font-bold text-slate-900 dark:text-white leading-none">
                                        <?php echo htmlspecialchars($display_name); ?>
                                    </p>
                                    <p class="text-xs text-slate-500 dark:text-[#92a4c9] mt-1">Administrator</p>
                                </div>
                                <div class="size-10 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold border-2 border-primary/30 transition-all group-hover:ring-2 group-hover:ring-primary/20"
                                    data-alt="Administrator profile picture">
                                    <?php echo strtoupper(substr($display_name, 0, 1)); ?>
                                </div>
                            </button>

                            <!-- User Dropdown Menu -->
                            <div id="userDropdown"
                                class="absolute right-0 mt-3 w-64 bg-white dark:bg-[#0a1221] border border-slate-200 dark:border-slate-800 rounded-xl shadow-xl hidden z-[100] transform transition-all origin-top-right">
                                <div class="p-4 border-b border-slate-200 dark:border-slate-800">
                                    <p class="text-slate-900 dark:text-white font-bold text-sm">
                                        <?php echo htmlspecialchars($display_name); ?>
                                    </p>
                                    <p class="text-slate-500 dark:text-[#92a4c9] text-xs truncate">
                                        <?php echo htmlspecialchars($admin_email); ?></p>
                                </div>
                                <div class="py-2">
                                    <a href="dashboard.php"
                                        class="flex items-center gap-3 px-4 py-3 text-slate-600 dark:text-[#92a4c9] hover:bg-slate-50 dark:hover:bg-[#232f48] transition-colors">
                                        <span class="material-symbols-outlined text-lg">home</span>
                                        <span class="text-sm font-medium">Voltar ao Dashboard</span>
                                    </a>
                                    <a href="#"
                                        class="flex items-center gap-3 px-4 py-3 text-slate-600 dark:text-[#92a4c9] hover:bg-slate-50 dark:hover:bg-[#232f48] transition-colors">
                                        <span class="material-symbols-outlined text-lg">person</span>
                                        <span class="text-sm font-medium">Meu Perfil</span>
                                    </a>
                                    <a href="#"
                                        class="flex items-center gap-3 px-4 py-3 text-slate-600 dark:text-[#92a4c9] hover:bg-slate-50 dark:hover:bg-[#232f48] transition-colors">
                                        <span class="material-symbols-outlined text-lg">settings</span>
                                        <span class="text-sm font-medium">Configurações</span>
                                    </a>
                                </div>
                                <div class="p-2 border-t border-slate-200 dark:border-slate-800">
                                    <a href="logout.php"
                                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                                        <span class="material-symbols-outlined text-lg">logout</span>
                                        <span class="text-sm font-bold uppercase tracking-wider">Sair da Conta</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Scrollable Dashboard Content -->
            <div class="flex-1 overflow-y-auto bg-slate-50 dark:bg-[#0f1520] p-6 lg:p-8 scroll-smooth">
                <div class="max-w-[1400px] mx-auto flex flex-col gap-8">
                    <!-- Welcome Section -->
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                        <div class="flex flex-col gap-1">
                            <h1
                                class="text-slate-900 dark:text-white text-3xl md:text-4xl font-extrabold tracking-tight">
                                Bem vindo, <?php echo htmlspecialchars($display_name); ?></h1>
                            <p class="text-slate-500 dark:text-[#92a4c9] text-base font-normal">Resumo financeiro de
                                hoje.</p>
                        </div>
                        <div class="flex gap-3">
                            <button
                                class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-[#232f48] text-slate-700 dark:text-white rounded-lg text-sm font-semibold shadow-sm hover:bg-slate-50 dark:hover:bg-[#2d3b55] transition-colors border border-slate-200 dark:border-transparent">
                                <span class="material-symbols-outlined text-[20px]">cloud_download</span>
                                Download Report
                            </button>
                            <button
                                class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold shadow-lg shadow-primary/20 hover:bg-primary/90 transition-colors">
                                <span class="material-symbols-outlined text-[20px]">add</span>
                                New Entry
                            </button>
                        </div>
                    </div>
                    <!-- KPI Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                        <!-- Card 1 -->
                        <div
                            class="flex flex-col gap-3 rounded-xl p-5 bg-white dark:bg-[#1e293b] border border-slate-100 dark:border-slate-800 shadow-sm">
                            <div class="flex justify-between items-start">
                                <div class="p-2 rounded-lg bg-stone-gold/10 text-stone-gold">
                                    <span class="material-symbols-outlined">payments</span>
                                </div>
                                <span
                                    class="flex items-center text-emerald-500 bg-emerald-50 dark:bg-emerald-500/10 px-2 py-1 rounded text-xs font-bold">
                                    <span class="material-symbols-outlined text-[16px] mr-1">trending_up</span>
                                    +12%
                                </span>
                            </div>
                            <div>
                                <p class="text-slate-500 dark:text-[#92a4c9] text-sm font-medium">Receita</p>
                                <h3 id="kpi-revenue" class="text-slate-900 dark:text-white text-2xl font-bold mt-1">R$
                                    0,00</h3>
                            </div>
                        </div>
                        <!-- Card 2 -->
                        <div
                            class="flex flex-col gap-3 rounded-xl p-5 bg-white dark:bg-[#1e293b] border border-slate-100 dark:border-slate-800 shadow-sm">
                            <div class="flex justify-between items-start">
                                <div
                                    class="p-2 rounded-lg bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400">
                                    <span class="material-symbols-outlined">school</span>
                                </div>
                                <span
                                    class="flex items-center text-emerald-500 bg-emerald-50 dark:bg-emerald-500/10 px-2 py-1 rounded text-xs font-bold">
                                    <span class="material-symbols-outlined text-[16px] mr-1">trending_up</span>
                                    +5%
                                </span>
                            </div>
                            <div>
                                <p class="text-slate-500 dark:text-[#92a4c9] text-sm font-medium">Usuários Ativos</p>
                                <h3 id="kpi-users" class="text-slate-900 dark:text-white text-2xl font-bold mt-1">0</h3>
                            </div>
                        </div>
                        <!-- Card 3 -->
                        <div
                            class="flex flex-col gap-3 rounded-xl p-5 bg-white dark:bg-[#1e293b] border border-slate-100 dark:border-slate-800 shadow-sm">
                            <div class="flex justify-between items-start">
                                <div
                                    class="p-2 rounded-lg bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400">
                                    <span class="material-symbols-outlined">monitoring</span>
                                </div>
                                <span
                                    class="flex items-center text-rose-500 bg-rose-50 dark:bg-rose-500/10 px-2 py-1 rounded text-xs font-bold">
                                    <span class="material-symbols-outlined text-[16px] mr-1">trending_down</span>
                                    -2%
                                </span>
                            </div>
                            <div>
                                <p class="text-slate-500 dark:text-[#92a4c9] text-sm font-medium">Volume Gerido</p>
                                <h3 id="kpi-volume" class="text-slate-900 dark:text-white text-2xl font-bold mt-1">R$
                                    0,00</h3>
                            </div>
                        </div>
                        <!-- Card 4 -->
                        <div
                            class="flex flex-col gap-3 rounded-xl p-5 bg-white dark:bg-[#1e293b] border border-slate-100 dark:border-slate-800 shadow-sm">
                            <div class="flex justify-between items-start">
                                <div
                                    class="p-2 rounded-lg bg-pink-50 dark:bg-pink-500/10 text-pink-600 dark:text-pink-400">
                                    <span class="material-symbols-outlined">person_remove</span>
                                </div>
                                <span
                                    class="flex items-center text-emerald-500 bg-emerald-50 dark:bg-emerald-500/10 px-2 py-1 rounded text-xs font-bold">
                                    <span class="material-symbols-outlined text-[16px] mr-1">trending_down</span>
                                    -0.5%
                                </span>
                            </div>
                            <div>
                                <p class="text-slate-500 dark:text-[#92a4c9] text-sm font-medium">Taxa de Aderência</p>
                                <h3 id="kpi-adherence" class="text-slate-900 dark:text-white text-2xl font-bold mt-1">0%
                                </h3>
                            </div>
                        </div>
                    </div>
                    <!-- Main Chart Section -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Large Chart -->
                        <div
                            class="lg:col-span-2 rounded-xl bg-white dark:bg-[#1e293b] border border-slate-100 dark:border-slate-800 shadow-sm p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                                <div>
                                    <h3 class="text-slate-900 dark:text-white text-lg font-bold">Taxa de Adesão</h3>
                                    <p class="text-slate-500 dark:text-[#92a4c9] text-sm">Evolução de novos assinantes
                                        nos ultimos 30 dias</p>
                                </div>
                                <div class="flex items-center gap-2 bg-slate-100 dark:bg-[#111722] p-1 rounded-lg">
                                    <button
                                        class="px-3 py-1.5 rounded text-xs font-medium bg-white dark:bg-[#232f48] text-slate-900 dark:text-white shadow-sm">30
                                        Dias</button>
                                    <button
                                        class="px-3 py-1.5 rounded text-xs font-medium text-slate-500 dark:text-[#92a4c9] hover:text-slate-900 dark:hover:text-white">3
                                        Mmeses</button>
                                    <button
                                        class="px-3 py-1.5 rounded text-xs font-medium text-slate-500 dark:text-[#92a4c9] hover:text-slate-900 dark:hover:text-white">YTD</button>
                                </div>
                            </div>
                            <!-- Chart Placeholder -->
                            <div class="relative w-full h-[300px] mt-4">
                                <svg class="overflow-visible" fill="none" height="100%" preserveaspectratio="none"
                                    viewbox="0 0 800 300" width="100%" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <lineargradient id="chartGradient" x1="0" x2="0" y1="0" y2="1">
                                            <stop offset="0%" stop-color="#D4AF37" stop-opacity="0.3"></stop>
                                            <stop offset="100%" stop-color="#D4AF37" stop-opacity="0"></stop>
                                        </lineargradient>
                                    </defs>
                                    <!-- Grid Lines -->
                                    <line stroke="#334155" stroke-dasharray="4 4" stroke-opacity="0.2" x1="0" x2="800"
                                        y1="290" y2="290"></line>
                                    <line stroke="#334155" stroke-dasharray="4 4" stroke-opacity="0.2" x1="0" x2="800"
                                        y1="220" y2="220"></line>
                                    <line stroke="#334155" stroke-dasharray="4 4" stroke-opacity="0.2" x1="0" x2="800"
                                        y1="150" y2="150"></line>
                                    <line stroke="#334155" stroke-dasharray="4 4" stroke-opacity="0.2" x1="0" x2="800"
                                        y1="80" y2="80"></line>
                                    <line stroke="#334155" stroke-dasharray="4 4" stroke-opacity="0.2" x1="0" x2="800"
                                        y1="10" y2="10"></line>
                                    <!-- The Chart Line/Area -->
                                    <path
                                        d="M0 250 C 50 240, 100 200, 150 210 C 200 220, 250 150, 300 140 C 350 130, 400 160, 450 120 C 500 80, 550 100, 600 70 C 650 40, 700 60, 750 30 L 800 20 L 800 300 L 0 300 Z"
                                        fill="url(#chartGradient)"></path>
                                    <path
                                        d="M0 250 C 50 240, 100 200, 150 210 C 200 220, 250 150, 300 140 C 350 130, 400 160, 450 120 C 500 80, 550 100, 600 70 C 650 40, 700 60, 750 30 L 800 20"
                                        fill="none" stroke="#D4AF37" stroke-linecap="round" stroke-width="3"></path>
                                    <!-- Highlight Point -->
                                    <circle cx="600" cy="70" fill="#D4AF37" r="6" stroke="#ffffff" stroke-width="2">
                                    </circle>
                                    <rect class="dark:fill-[#232f48]" fill="#1e293b" height="36" rx="6" width="100"
                                        x="550" y="20"></rect>
                                    <text fill="white" font-family="Manrope" font-size="12" font-weight="bold"
                                        text-anchor="middle" x="600" y="44">$1.1M Revenue</text>
                                </svg>
                            </div>
                        </div>
                        <!-- Side Panel: Recent Activity -->
                        <div class="flex flex-col gap-4">
                            <!-- Quick Actions Grid -->
                            <div class="grid grid-cols-2 gap-3">
                                <a href="gerenciar-artigos.php"
                                    class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl bg-white dark:bg-[#1e293b] border border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-[#232f48] transition-colors group">
                                    <div
                                        class="p-2 rounded-full bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                                        <span class="material-symbols-outlined">add_task</span>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Adicionar
                                        Materia</span>
                                </a>
                                <button
                                    class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl bg-white dark:bg-[#1e293b] border border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-[#232f48] transition-colors group">
                                    <div
                                        class="p-2 rounded-full bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                                        <span class="material-symbols-outlined">verified_user</span>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Verify
                                        KYC</span>
                                </button>
                                <button
                                    class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl bg-white dark:bg-[#1e293b] border border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-[#232f48] transition-colors group">
                                    <div
                                        class="p-2 rounded-full bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                                        <span class="material-symbols-outlined">summarize</span>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Export
                                        CSV</span>
                                </button>
                                <button
                                    class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl bg-white dark:bg-[#1e293b] border border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-[#232f48] transition-colors group">
                                    <div
                                        class="p-2 rounded-full bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                                        <span class="material-symbols-outlined">support_agent</span>
                                    </div>
                                    <span
                                        class="text-xs font-semibold text-slate-700 dark:text-slate-300">Suporte</span>
                                </button>
                            </div>
                            <!-- Top Performers Widget -->
                            <div
                                class="flex-1 rounded-xl bg-white dark:bg-[#1e293b] border border-slate-100 dark:border-slate-800 shadow-sm p-5">
                                <h3 class="text-slate-900 dark:text-white text-base font-bold mb-4">Top Conteúdos</h3>
                                <div class="flex flex-col gap-4">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-slate-700 dark:text-slate-300 font-medium">Educação
                                                Financeira</span>
                                            <span class="text-slate-500 dark:text-[#92a4c9]">85% Full</span>
                                        </div>
                                        <div
                                            class="w-full h-2 bg-slate-100 dark:bg-[#111722] rounded-full overflow-hidden">
                                            <div class="h-full bg-primary w-[85%] rounded-full"></div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-slate-700 dark:text-slate-300 font-medium">Renda
                                                Variável</span>
                                            <span class="text-slate-500 dark:text-[#92a4c9]">62% Full</span>
                                        </div>
                                        <div
                                            class="w-full h-2 bg-slate-100 dark:bg-[#111722] rounded-full overflow-hidden">
                                            <div class="h-full bg-stone-gold w-[62%] rounded-full"></div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-slate-700 dark:text-slate-300 font-medium">Finanças</span>
                                            <span class="text-slate-500 dark:text-[#92a4c9]">45% Full</span>
                                        </div>
                                        <div
                                            class="w-full h-2 bg-slate-100 dark:bg-[#111722] rounded-full overflow-hidden">
                                            <div class="h-full bg-stone-gold/70 w-[45%] rounded-full"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Recent Transactions Table -->
                    <div
                        class="rounded-xl bg-white dark:bg-[#1e293b] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
                        <div
                            class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                            <h3 class="text-slate-900 dark:text-white text-lg font-bold">Novos Usuários</h3>
                            <a class="text-primary text-sm font-semibold hover:underline" href="#">View All</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-slate-50 dark:bg-[#111722]">
                                    <tr>
                                        <th
                                            class="p-4 text-xs font-bold text-slate-500 dark:text-[#92a4c9] uppercase tracking-wider">
                                            Transaction ID</th>
                                        <th
                                            class="p-4 text-xs font-bold text-slate-500 dark:text-[#92a4c9] uppercase tracking-wider">
                                            Usuário</th>
                                        <th
                                            class="p-4 text-xs font-bold text-slate-500 dark:text-[#92a4c9] uppercase tracking-wider">
                                            Date</th>
                                        <th
                                            class="p-4 text-xs font-bold text-slate-500 dark:text-[#92a4c9] uppercase tracking-wider">
                                            Primeiro aporte</th>
                                        <th
                                            class="p-4 text-xs font-bold text-slate-500 dark:text-[#92a4c9] uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="p-4 text-xs font-bold text-slate-500 dark:text-[#92a4c9] uppercase tracking-wider text-right">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody id="recent-users-table" class="divide-y divide-slate-100 dark:divide-slate-800">
                                    <tr class="hover:bg-slate-50 dark:hover:bg-[#232f48]/50 transition-colors">
                                        <td class="p-4 text-sm text-slate-600 dark:text-slate-300 font-mono">#TRX-9821
                                        </td>
                                        <td
                                            class="p-4 text-sm text-slate-900 dark:text-white font-medium flex items-center gap-3">
                                            <div class="size-8 rounded-full bg-cover bg-center"
                                                data-alt="User avatar small"
                                                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAw353ifZX0-84en8k71ic9241eDkT3fTxCCITS5YuNLTFiEIhjYaIJl5Mt56YRr7VZYu28abdBKgRjq-InXszdjwVIx6CVTHx9FTDM6GY0cs5KB7By-dlQWv4_OeXIREFAPEqdWJUwEctIW8x0g0CO9FTc0IlNQTueYu7aNsoghFYmH9B4uYEgDeYMzaaAYCi4At2ncCTNw1HGCzqpmZxpthgoD0SC-u7WemYFVD1ctAfF73dkm74SBXMFWfpK1XYAmfLEw-5n761C");'>
                                            </div>
                                            Jane Cooper
                                        </td>
                                        <td class="p-4 text-sm text-slate-500 dark:text-[#92a4c9]">Oct 24, 2023</td>
                                        <td class="p-4 text-sm text-slate-900 dark:text-white font-bold">$1,200.00</td>
                                        <td class="p-4">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-400">
                                                Completed
                                            </span>
                                        </td>
                                        <td class="p-4 text-right">
                                            <button
                                                class="text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-white">
                                                <span class="material-symbols-outlined text-lg">more_vert</span>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-slate-50 dark:hover:bg-[#232f48]/50 transition-colors">
                                        <td class="p-4 text-sm text-slate-600 dark:text-slate-300 font-mono">#TRX-9820
                                        </td>
                                        <td
                                            class="p-4 text-sm text-slate-900 dark:text-white font-medium flex items-center gap-3">
                                            <div class="size-8 rounded-full bg-cover bg-center"
                                                data-alt="User avatar small"
                                                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBZTYXWR45FfRIquWUbDc9OAdzBmpY4oO-hf9ftUjB1D1RigGGq8effHgvaz4QraDtnnD53bzL1Fw0KS24VecGlEiHnoCEbNXOHIKFxD1kMApnnI0CKKRiSYrP0SYR5yzMaWSjxmnJVc1pg7PzVyZ-peaqdjCl9dm1siIanPRhm4H9Mo37c6IHkU89wZvjyFv-RQZawfXWr43vm-qxfnPlC1kB0eHwDsmmnG7a3LH4MlzsRSMDTVy71m6avzExtMJzMQU3A7qsJ3UJQ");'>
                                            </div>
                                            Wade Warren
                                        </td>
                                        <td class="p-4 text-sm text-slate-500 dark:text-[#92a4c9]">Oct 24, 2023</td>
                                        <td class="p-4 text-sm text-slate-900 dark:text-white font-bold">$450.00</td>
                                        <td class="p-4">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-500/20 dark:text-amber-400">
                                                Pending
                                            </span>
                                        </td>
                                        <td class="p-4 text-right">
                                            <button
                                                class="text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-white">
                                                <span class="material-symbols-outlined text-lg">more_vert</span>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-slate-50 dark:hover:bg-[#232f48]/50 transition-colors">
                                        <td class="p-4 text-sm text-slate-600 dark:text-slate-300 font-mono">#TRX-9819
                                        </td>
                                        <td
                                            class="p-4 text-sm text-slate-900 dark:text-white font-medium flex items-center gap-3">
                                            <div class="size-8 rounded-full bg-cover bg-center"
                                                data-alt="User avatar small"
                                                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuD5TT_uJ9sftiEI7D836wOHYcYrenNjwqX3de5waKuRu9Evii29RjHdXQOZjF0t-yw3C1KNcGh4Xvr2qIAI24d8OiBmmOQNkiU2f1bF6Tspl16Zs5zRIPQhf8sP6P6erbdhlCmrdVhCLpBSp92v3xz8R0RzvuGrBSHok6gdBL2vEp6FScjbIuZcRXC1JHYHQEGtz9cyUroNUcmPlxtytzjJRhnYiaC4X1t1rD75Tugb9-6mRmIreSUsQvDNk6mPNNcjYOW-PgSBgHyd");'>
                                            </div>
                                            Esther Howard
                                        </td>
                                        <td class="p-4 text-sm text-slate-500 dark:text-[#92a4c9]">Oct 23, 2023</td>
                                        <td class="p-4 text-sm text-slate-900 dark:text-white font-bold">$2,300.00</td>
                                        <td class="p-4">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-400">
                                                Completed
                                            </span>
                                        </td>
                                        <td class="p-4 text-right">
                                            <button
                                                class="text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-white">
                                                <span class="material-symbols-outlined text-lg">more_vert</span>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Footer -->
                    <div
                        class="flex items-center justify-between py-6 border-t border-slate-200 dark:border-slate-800/50 mt-4">
                        <p class="text-xs text-slate-500 dark:text-[#92a4c9]">© 2025 STONE EDGER. All rights reserved.
                        </p>
                        <div class="flex gap-4">
                            <a class="text-xs text-slate-500 hover:text-primary dark:text-[#92a4c9] dark:hover:text-white"
                                href="#">Privacy Policy</a>
                            <a class="text-xs text-slate-500 hover:text-primary dark:text-[#92a4c9] dark:hover:text-white"
                                href="#">Terms of Service</a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        // UI Interactions
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userDropdown = document.getElementById('userDropdown');
        const mobileMenuToggle = document.querySelector('header button'); // The only button in header before profile
        const sidebar = document.querySelector('aside');

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

        if (mobileMenuToggle && sidebar) {
            mobileMenuToggle.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });
        }

        async function fetchAdminMetrics() {
            try {
                const response = await fetch('api/get_admin_metrics.php');
                const data = await response.json();

                if (!response.ok) {
                    console.error('API Error:', data.error, data.details);
                    return;
                }

                console.log('Admin Metrics Debug:', data.debug);

                // Update KPI Cards
                document.getElementById('kpi-users').textContent = data.total_users || 0;

                const formatter = new Intl.NumberFormat('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                });

                document.getElementById('kpi-volume').textContent = formatter.format(data.total_volume || 0);
                document.getElementById('kpi-adherence').textContent = (data.adherence_rate || 0).toFixed(1) + '%';
                document.getElementById('kpi-revenue').textContent = formatter.format((data.active_users || 0) * 49.90); // Mock revenue

                // Update Recent Users Table
                const tableBody = document.getElementById('recent-users-table');
                tableBody.innerHTML = ''; // Always clear mock data on success

                if (data.recent_users && data.recent_users.length > 0) {
                    data.recent_users.forEach(user => {
                        const date = new Date(user.created_at).toLocaleDateString('pt-BR');
                        const statusClass = user.status === 'Ativo'
                            ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-400'
                            : 'bg-amber-100 text-amber-800 dark:bg-amber-500/20 dark:text-amber-400';

                        const secondaryInfo = user.user_name !== user.portfolio_name
                            ? `<span class="text-[10px] text-slate-400 block">${user.portfolio_name}</span>`
                            : '';

                        const row = `
                            <tr class="hover:bg-slate-50 dark:hover:bg-[#232f48]/50 transition-colors">
                                <td class="p-4 text-sm text-slate-600 dark:text-slate-300 font-mono">#USR-${user.user_id.substring(0, 4)}</td>
                                <td class="p-4 text-sm text-slate-900 dark:text-white font-medium flex items-center gap-3">
                                    <div class="size-8 rounded-full bg-stone-gold/20 flex items-center justify-center text-stone-gold font-bold text-xs uppercase">
                                        ${user.user_name.charAt(0)}
                                    </div>
                                    <div>
                                        <span class="block">${user.user_name}</span>
                                        ${secondaryInfo}
                                    </div>
                                </td>
                                <td class="p-4 text-sm text-slate-500 dark:text-[#92a4c9]">${date}</td>
                                <td class="p-4 text-sm text-slate-900 dark:text-white font-bold">${formatter.format(user.total_value)}</td>
                                <td class="p-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusClass}">
                                        ${user.status}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <button class="text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-white">
                                        <span class="material-symbols-outlined text-lg">more_vert</span>
                                    </button>
                                </td>
                            </tr>
                        `;
                        tableBody.insertAdjacentHTML('beforeend', row);
                    });
                } else {
                    tableBody.innerHTML = '<tr><td colspan="6" class="p-8 text-center text-slate-500">Nenhum usuário recente encontrado.</td></tr>';
                }

            } catch (error) {
                console.error('Error fetching admin metrics:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', fetchAdminMetrics);
    </script>
</body>

</html>