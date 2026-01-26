<?php
require_once 'api/session_handler.php';
$isLoggedIn = isset($_SESSION['user_id']);
$user_name = $_SESSION['user_name'] ?? '';
$avatar_url = $_SESSION['avatar_url'] ?? '';

// Default placeholder avatar if missing
if ($isLoggedIn && empty($avatar_url)) {
    $avatar_url = 'https://lh3.googleusercontent.com/aida-public/AB6AXuCTCifV9f7veeImD6mpBg5MYpyLXZuX0Wn-PekVpNu3vhVQG721dQEl5WbsrR0o1vraCZDBH5trp5oRZRL1eoPcs3dQ2f-TLvIbK0zrlOY8h0HhQ2cwU_AEwwuY_aTR73AIIqfDUGiolLRlNIFv2tosDtVNg9Of2mQ6U3go3M0Stl4z-ovMmuKmAZstI_VMgVwz4eMj131GaJWanBRhtp4sq_-iwpm3rpvT2lnUsLqCG5sWw3sBN2vvSkwzE6IoKjRM1kJVgZGQng0';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suitability | Stone Edger</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap"
        rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />

    <!-- Supabase SDK -->
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Tailwind CSS -->
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
                    }
                }
            }
        }
    </script>

    <style>
        /* Shared Styles */
        body {
            background-color: #050a14;
            color: #F5F5F5;
            font-family: 'Montserrat', sans-serif;
        }

        .fixed-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
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
            z-index: -1;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        /* Form Specific Styles */
        input[type="radio"] {
            -webkit-appearance: none;
            appearance: none;
            background-color: transparent;
            margin: 0;
            width: 1.25em;
            height: 1.25em;
            border: 2px solid #D4AF37;
            border-radius: 50%;
            display: grid;
            place-content: center;
            cursor: pointer;
        }

        input[type="radio"]::before {
            content: "";
            width: 0.65em;
            height: 0.65em;
            border-radius: 50%;
            transform: scale(0);
            transition: 120ms transform ease-in-out;
            box-shadow: inset 1em 1em #D4AF37;
        }

        input[type="radio"]:checked::before {
            transform: scale(1);
        }

        .question-card:hover {
            border-color: rgba(212, 175, 55, 0.5);
            background: rgba(255, 255, 255, 0.12);
        }

        /* Progress Bar */
        #progress-bar {
            transition: width 0.5s ease-in-out;
        }
    </style>
</head>

<body class="flex flex-col min-h-screen">

    <div class="fixed-bg"></div>
    <div class="bg-overlay"></div>

    <!-- Header -->
    <header class="fixed w-full z-50 py-4 bg-stone-navy/90 backdrop-blur-md border-b border-stone-glassBorder">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <a href="index.php"
                class="font-playfair text-xl font-bold text-white tracking-wider flex items-center gap-2 group">
                <div class="w-8 h-8 border border-stone-gold flex items-center justify-center rounded-full">
                    <span class="text-stone-gold font-serif italic text-lg">S</span>
                </div>
                <span>STONE <span class="text-stone-gold">EDGER</span></span>
            </a>

            <div class="hidden md:flex items-center gap-4">
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
                                <a href="#" onclick="return false;"
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
                                    class="flex items-center gap-3 px-4 py-3 text-stone-gold bg-stone-gold/10 hover:text-white hover:bg-stone-glass transition-colors">
                                    <span class="material-symbols-outlined text-lg">psychology</span>
                                    <span class="text-xs font-bold tracking-wider uppercase">Suitability</span>
                                </a>
                            </div>
                            <div class="p-2 border-t border-stone-glassBorder">
                                <a href="logout.php"
                                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-red-500 hover:bg-red-500/10 transition-colors">
                                    <span class="material-symbols-outlined text-lg">logout</span>
                                    <span class="text-xs font-bold tracking-wider uppercase">Sair</span>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <!-- Progress Bar -->
        <div class="absolute bottom-0 left-0 w-full h-1 bg-stone-navy">
            <div id="progress-bar" class="h-full bg-stone-gold w-0"></div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow pt-28 pb-20 px-4 md:px-6">
        <div class="container mx-auto max-w-3xl">

            <div class="text-center mb-10">
                <span class="text-stone-gold font-bold tracking-widest uppercase text-xs">Análise de Perfil do
                    Investidor (API)</span>
                <h1 class="font-playfair text-3xl md:text-4xl font-bold text-white mt-2 mb-4">Suitability</h1>
                <p class="text-stone-gray text-sm md:text-base max-w-2xl mx-auto font-light leading-relaxed">
                    Este questionário segue as diretrizes da CVM e ANBIMA para identificar seu perfil de risco e
                    garantir que nossos serviços sejam adequados aos seus objetivos.
                </p>
            </div>

            <form id="suitabilityForm" class="space-y-6" onsubmit="event.preventDefault(); calculateProfile();">

                <!-- 1. Objetivo -->
                <div class="glass-panel p-6 md:p-8 rounded-xl" id="q1">
                    <h3 class="font-playfair text-lg text-white mb-4"><span class="text-stone-gold mr-2">1.</span> Qual
                        é o seu principal objetivo com este investimento?</h3>
                    <div class="space-y-3">
                        <label
                            class="flex items-start gap-3 cursor-pointer group hover:text-stone-gold transition-colors">
                            <input type="radio" name="p1" value="1" required class="mt-1 shrink-0">
                            <span class="text-stone-gray group-hover:text-stone-light text-sm">Preservação de capital,
                                assumindo o menor risco possível.</span>
                        </label>
                        <label
                            class="flex items-start gap-3 cursor-pointer group hover:text-stone-gold transition-colors">
                            <input type="radio" name="p1" value="2" class="mt-1 shrink-0">
                            <span class="text-stone-gray group-hover:text-stone-light text-sm">Aumento gradual do
                                capital, assumindo riscos moderados.</span>
                        </label>
                        <label
                            class="flex items-start gap-3 cursor-pointer group hover:text-stone-gold transition-colors">
                            <input type="radio" name="p1" value="4" class="mt-1 shrink-0">
                            <span class="text-stone-gray group-hover:text-stone-light text-sm">Aumento expressivo do
                                capital, assumindo riscos elevados.</span>
                        </label>
                    </div>
                </div>

                <!-- 2. Horizonte -->
                <div class="glass-panel p-6 md:p-8 rounded-xl" id="q2">
                    <h3 class="font-playfair text-lg text-white mb-4"><span class="text-stone-gold mr-2">2.</span> Por
                        quanto tempo pretende manter os recursos investidos?</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="radio" name="p2" value="1" required> <span
                                class="text-stone-gray group-hover:text-white text-sm">Menos de 1 ano.</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="radio" name="p2" value="2"> <span
                                class="text-stone-gray group-hover:text-white text-sm">De 1 a 3 anos.</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="radio" name="p2" value="3"> <span
                                class="text-stone-gray group-hover:text-white text-sm">De 3 a 5 anos.</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="radio" name="p2" value="4"> <span
                                class="text-stone-gray group-hover:text-white text-sm">Acima de 5 anos.</span>
                        </label>
                    </div>
                </div>

                <!-- 3. Necessidade de Recursos -->
                <div class="glass-panel p-6 md:p-8 rounded-xl" id="q3">
                    <h3 class="font-playfair text-lg text-white mb-4"><span class="text-stone-gold mr-2">3.</span> Qual
                        a sua necessidade futura de uso desses recursos?</h3>
                    <div class="space-y-3">
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="radio" name="p3" value="1" required class="mt-1 shrink-0">
                            <span class="text-stone-gray group-hover:text-white text-sm">Tenho necessidade de utilizar
                                os recursos no curto prazo para despesas correntes.</span>
                        </label>
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="radio" name="p3" value="2" class="mt-1 shrink-0">
                            <span class="text-stone-gray group-hover:text-white text-sm">Prevejo utilizar parte dos
                                recursos no médio prazo (ex: compra de imóvel/carro).</span>
                        </label>
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="radio" name="p3" value="4" class="mt-1 shrink-0">
                            <span class="text-stone-gray group-hover:text-white text-sm">Não tenho necessidade de
                                utilizar os recursos por um longo período (aposentadoria/legado).</span>
                        </label>
                    </div>
                </div>

                <!-- 4. Renda -->
                <div class="glass-panel p-6 md:p-8 rounded-xl" id="q4">
                    <h3 class="font-playfair text-lg text-white mb-4"><span class="text-stone-gold mr-2">4.</span> Qual
                        percentual da sua renda mensal você consegue investir?</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <label
                            class="border border-stone-glassBorder p-3 rounded hover:border-stone-gold cursor-pointer text-center transition-colors">
                            <input type="radio" name="p4" value="1" class="hidden peer">
                            <span class="text-stone-gray peer-checked:text-stone-gold text-sm font-bold block">Até
                                10%</span>
                        </label>
                        <label
                            class="border border-stone-glassBorder p-3 rounded hover:border-stone-gold cursor-pointer text-center transition-colors">
                            <input type="radio" name="p4" value="2" class="hidden peer">
                            <span class="text-stone-gray peer-checked:text-stone-gold text-sm font-bold block">10% a
                                20%</span>
                        </label>
                        <label
                            class="border border-stone-glassBorder p-3 rounded hover:border-stone-gold cursor-pointer text-center transition-colors">
                            <input type="radio" name="p4" value="3" class="hidden peer">
                            <span class="text-stone-gray peer-checked:text-stone-gold text-sm font-bold block">20% a
                                40%</span>
                        </label>
                        <label
                            class="border border-stone-glassBorder p-3 rounded hover:border-stone-gold cursor-pointer text-center transition-colors">
                            <input type="radio" name="p4" value="4" class="hidden peer">
                            <span class="text-stone-gray peer-checked:text-stone-gold text-sm font-bold block">Acima de
                                40%</span>
                        </label>
                    </div>
                </div>

                <!-- 5. Conhecimento -->
                <div class="glass-panel p-6 md:p-8 rounded-xl" id="q5">
                    <h3 class="font-playfair text-lg text-white mb-4"><span class="text-stone-gold mr-2">5.</span> Como
                        você classifica seu conhecimento sobre o mercado financeiro?</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer group"><input type="radio" name="p5"
                                value="1" required> <span class="text-stone-gray text-sm">Nenhum ou
                                Baixo.</span></label>
                        <label class="flex items-center gap-3 cursor-pointer group"><input type="radio" name="p5"
                                value="2"> <span class="text-stone-gray text-sm">Razoável (conheço Renda Fixa e
                                Fundos).</span></label>
                        <label class="flex items-center gap-3 cursor-pointer group"><input type="radio" name="p5"
                                value="4"> <span class="text-stone-gray text-sm">Profundo (conheço Derivativos e
                                estrutura de mercado).</span></label>
                    </div>
                </div>

                <!-- 6. Experiência Produtos -->
                <div class="glass-panel p-6 md:p-8 rounded-xl" id="q6">
                    <h3 class="font-playfair text-lg text-white mb-4"><span class="text-stone-gold mr-2">6.</span> Quais
                        produtos você investiu nos últimos 12 meses?</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer group"><input type="radio" name="p6"
                                value="1" required> <span class="text-stone-gray text-sm">Poupança
                                apenas.</span></label>
                        <label class="flex items-center gap-3 cursor-pointer group"><input type="radio" name="p6"
                                value="2"> <span class="text-stone-gray text-sm">CDBs, Tesouro Direto ou Fundos de Renda
                                Fixa.</span></label>
                        <label class="flex items-center gap-3 cursor-pointer group"><input type="radio" name="p6"
                                value="3"> <span class="text-stone-gray text-sm">Ações, Fundos de Ações ou
                                FIIs.</span></label>
                        <label class="flex items-center gap-3 cursor-pointer group"><input type="radio" name="p6"
                                value="5"> <span class="text-stone-gray text-sm">Derivativos, Futuros ou
                                Opções.</span></label>
                    </div>
                </div>

                <!-- 7. Reação a Perda -->
                <div class="glass-panel p-6 md:p-8 rounded-xl" id="q7">
                    <h3 class="font-playfair text-lg text-white mb-4"><span class="text-stone-gold mr-2">7.</span> Se
                        seus investimentos caíssem 20%, o que você faria?</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer group"><input type="radio" name="p7"
                                value="1" required> <span class="text-stone-gray text-sm">Venderia tudo imediatamente
                                para não perder mais.</span></label>
                        <label class="flex items-center gap-3 cursor-pointer group"><input type="radio" name="p7"
                                value="2"> <span class="text-stone-gray text-sm">Manteria a posição aguardando
                                recuperação.</span></label>
                        <label class="flex items-center gap-3 cursor-pointer group"><input type="radio" name="p7"
                                value="4"> <span class="text-stone-gray text-sm">Investiria mais para aproveitar o preço
                                baixo.</span></label>
                    </div>
                </div>

                <!-- 8. Formação -->
                <div class="glass-panel p-6 md:p-8 rounded-xl" id="q8">
                    <h3 class="font-playfair text-lg text-white mb-4"><span class="text-stone-gold mr-2">8.</span> Qual
                        a sua formação acadêmica?</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer group"><input type="radio" name="p8"
                                value="1" required> <span class="text-stone-gray text-sm">Ensino
                                Fundamental/Médio.</span></label>
                        <label class="flex items-center gap-3 cursor-pointer group"><input type="radio" name="p8"
                                value="2"> <span class="text-stone-gray text-sm">Superior Completo (Áreas não
                                financeiras).</span></label>
                        <label class="flex items-center gap-3 cursor-pointer group"><input type="radio" name="p8"
                                value="3"> <span class="text-stone-gray text-sm">Superior/Pós em Economia, Finanças ou
                                afins.</span></label>
                    </div>
                </div>

                <!-- 9. Patrimônio Financeiro -->
                <div class="glass-panel p-6 md:p-8 rounded-xl" id="q9">
                    <h3 class="font-playfair text-lg text-white mb-4"><span class="text-stone-gold mr-2">9.</span> O
                        volume de recursos que pretende investir representa quanto do seu patrimônio?</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer group"><input type="radio" name="p9"
                                value="1" required> <span class="text-stone-gray text-sm">Acima de 80% (Quase
                                tudo).</span></label>
                        <label class="flex items-center gap-3 cursor-pointer group"><input type="radio" name="p9"
                                value="2"> <span class="text-stone-gray text-sm">Entre 40% e 80%.</span></label>
                        <label class="flex items-center gap-3 cursor-pointer group"><input type="radio" name="p9"
                                value="4"> <span class="text-stone-gray text-sm">Até 40% (Parte menor do
                                patrimônio).</span></label>
                    </div>
                </div>

                <!-- 10. Frequência de Aportes -->
                <div class="glass-panel p-6 md:p-8 rounded-xl" id="q10">
                    <h3 class="font-playfair text-lg text-white mb-4"><span class="text-stone-gold mr-2">10.</span> Com
                        qual frequência você pretende realizar novos aportes?</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer group"><input type="radio" name="p10"
                                value="3" required> <span class="text-stone-gray text-sm">Mensalmente.</span></label>
                        <label class="flex items-center gap-3 cursor-pointer group"><input type="radio" name="p10"
                                value="2"> <span class="text-stone-gray text-sm">Eventualmente /
                                Semestralmente.</span></label>
                        <label class="flex items-center gap-3 cursor-pointer group"><input type="radio" name="p10"
                                value="1"> <span class="text-stone-gray text-sm">Não pretendo fazer novos aportes,
                                apenas este único.</span></label>
                    </div>
                </div>

                <!-- 11. Familiaridade com Risco -->
                <div class="glass-panel p-6 md:p-8 rounded-xl" id="q11">
                    <h3 class="font-playfair text-lg text-white mb-4"><span class="text-stone-gold mr-2">11.</span> Qual
                        frase melhor define sua relação com "Risco x Retorno"?</h3>
                    <div class="space-y-3">
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="radio" name="p11" value="1" required class="mt-1 shrink-0">
                            <span class="text-stone-gray text-sm">Prefiro segurança total, mesmo que o rendimento seja
                                baixo.</span>
                        </label>
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="radio" name="p11" value="2" class="mt-1 shrink-0">
                            <span class="text-stone-gray text-sm">Aceito pequenas oscilações em troca de tentar superar
                                o CDI/Poupança.</span>
                        </label>
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="radio" name="p11" value="5" class="mt-1 shrink-0">
                            <span class="text-stone-gray text-sm">Busco altas rentabilidades e entendo que isso pode
                                significar perdas expressivas no curto prazo.</span>
                        </label>
                    </div>
                </div>

                <!-- Botão Enviar -->
                <div class="text-center pt-8 pb-12">
                    <p class="text-stone-gray text-xs mb-4">*Ao clicar em "Confirmar", você declara que as informações
                        prestadas são verdadeiras.</p>
                    <button type="submit"
                        class="bg-gradient-gold text-stone-navy text-lg px-12 py-4 rounded-lg font-bold uppercase tracking-wide hover:scale-105 shadow-[0_4px_15px_rgba(212,175,55,0.3)] transition-all duration-300 w-full md:w-auto">
                        Confirmar Perfil
                    </button>
                </div>

            </form>

            <!-- Modal Resultado -->
            <div id="resultModal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
                <div class="absolute inset-0 bg-stone-navy/95 backdrop-blur-md transition-opacity"
                    onclick="closeModal()"></div>
                <div
                    class="glass-panel border-stone-gold bg-stone-navy relative p-6 md:p-8 rounded-2xl max-w-4xl w-full text-left shadow-[0_0_50px_rgba(212,175,55,0.4)] transform transition-transform scale-100 max-h-[90vh] overflow-y-auto custom-scrollbar">

                    <button onclick="closeModal()"
                        class="absolute top-4 right-4 text-stone-gray hover:text-white transition-colors">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Left Col: Profile Details -->
                        <div class="flex flex-col justify-center">
                            <div class="flex items-center gap-4 mb-6">
                                <div id="modalIcon"
                                    class="w-16 h-16 bg-stone-gold rounded-full flex items-center justify-center shadow-lg shrink-0">
                                    <i class="fa-solid fa-shield-halved text-3xl text-stone-navy"></i>
                                </div>
                                <div>
                                    <p class="text-stone-gray text-xs uppercase tracking-widest font-bold">Seu Perfil é:
                                    </p>
                                    <h3 id="profileName"
                                        class="font-playfair text-3xl md:text-4xl font-bold text-white uppercase tracking-wider drop-shadow-lg">
                                        ---</h3>
                                </div>
                            </div>

                            <div class="space-y-4 mb-6">
                                <div class="bg-stone-navy/50 p-4 rounded-lg border border-stone-glassBorder">
                                    <h4 class="text-stone-gold text-sm font-bold uppercase mb-2"><i
                                            class="fa-solid fa-bullseye mr-2"></i>Prioridade</h4>
                                    <p id="profilePriority" class="text-stone-gray text-sm"></p>
                                </div>
                                <div class="bg-stone-navy/50 p-4 rounded-lg border border-stone-glassBorder">
                                    <h4 class="text-stone-gold text-sm font-bold uppercase mb-2"><i
                                            class="fa-solid fa-brain mr-2"></i>Comportamento</h4>
                                    <p id="profileBehavior" class="text-stone-gray text-sm"></p>
                                </div>
                                <div class="bg-stone-navy/50 p-4 rounded-lg border border-stone-glassBorder">
                                    <h4 class="text-stone-gold text-sm font-bold uppercase mb-2"><i
                                            class="fa-solid fa-coins mr-2"></i>Investimentos Comuns</h4>
                                    <p id="profileCommon" class="text-stone-gray text-sm"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Right Col: Chart & Allocation -->
                        <div class="flex flex-col">
                            <h4 class="text-white font-bold text-center mb-4 uppercase tracking-widest text-sm">Alocação
                                Recomendada</h4>

                            <!-- Chart Container -->
                            <div class="relative h-64 w-full mb-6">
                                <canvas id="allocationChart"></canvas>
                            </div>

                            <div id="allocationDetails"
                                class="space-y-2 text-sm text-stone-gray bg-stone-glassBorder/20 p-4 rounded-lg border-l-2 border-stone-gold">
                                <!-- Allocation text injected here -->
                            </div>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div
                        class="mt-8 pt-6 border-t border-stone-glassBorder flex flex-col md:flex-row justify-between items-center gap-4">
                        <button onclick="closeModal()"
                            class="text-stone-gray text-xs hover:text-white uppercase tracking-widest order-2 md:order-1">
                            Refazer Questionário
                        </button>
                        <a href="dashboard.php"
                            class="bg-gradient-gold text-stone-navy px-8 py-3 rounded-lg font-bold uppercase hover:scale-105 transition-all shadow-lg flex items-center gap-2 order-1 md:order-2 w-full md:w-auto justify-center">
                            Confirmar e Acessar Dashboard <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script>
        // --- Dropdown Toggle ---
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userDropdown = document.getElementById('userDropdown');

        if (userMenuBtn && userDropdown) {
            userMenuBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                userDropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!userDropdown.contains(e.target) && !userMenuBtn.contains(e.target)) {
                    userDropdown.classList.add('hidden');
                }
            });
        }

        // --- Progress Bar & Interactive Logic ---
        const form = document.getElementById('suitabilityForm');
        const progressBar = document.getElementById('progress-bar');
        const totalQuestions = 11;

        form.addEventListener('change', () => {
            const answered = new Set();
            new FormData(form).forEach((value, key) => answered.add(key));
            const progress = (answered.size / totalQuestions) * 100;
            progressBar.style.width = progress + '%';
        });

        // --- Supabase Config ---
        const SUPABASE_URL = "https://puxuilkexmjpjnrkqysq.supabase.co";
        const SUPABASE_KEY = "sb_publishable_EtvYR3UkvESNn-Ci2MuzrQ_cJYoTOJF";
        const _supabase = supabase.createClient(SUPABASE_URL, SUPABASE_KEY);

        // --- Profile Data Definition ---
        const profilesData = {
            "Conservador": {
                priority: "Segurança e liquidez (facilidade de sacar o dinheiro).",
                behavior: "Tem baixa tolerância a perdas e prefere retornos previsíveis, mesmo que menores.",
                common: "Tesouro Selic, CDBs de grandes bancos, Fundos DI e a própria Poupança.",
                chartData: [95, 5, 0],
                chartLabels: ["Renda Fixa", "Renda Variável", "Outros"],
                chartColors: ["#10b981", "#fbbf24", "#6366f1"],
                details: `
                    <p class="font-bold text-white mb-2">Foco: Preservação de Capital</p>
                    <ul class="list-disc pl-4 space-y-1">
                        <li><strong>90% a 100% em Renda Fixa:</strong> Tesouro Selic, CDBs liquidez diária, LCIs/LCAs.</li>
                        <li><strong>0% a 10% em Renda Variável:</strong> Apenas leve exposição (ex: FIIs baixo risco).</li>
                    </ul>
                `,
                icon: "fa-shield-halved"
            },
            "Moderado": {
                priority: "Equilíbrio entre segurança e rentabilidade.",
                behavior: "Aceita correr um risco pequeno em parte do patrimônio para obter retornos acima da inflação.",
                common: "Fundos Multimercado, LCI/LCA, Debêntures e Fundos Imobiliários.",
                chartData: [65, 22.5, 12.5],
                chartLabels: ["Renda Fixa", "Renda Variável", "Multi/Ouro"],
                chartColors: ["#10b981", "#f59e0b", "#8b5cf6"],
                details: `
                    <p class="font-bold text-white mb-2">Foco: Superar a Inflação</p>
                    <ul class="list-disc pl-4 space-y-1">
                        <li><strong>60% a 70% em Renda Fixa:</strong> Pós-fixados e IPCA+.</li>
                        <li><strong>20% a 25% em Renda Variável:</strong> Ações de dividendos e FIIs.</li>
                        <li><strong>5% a 10% em Multimercados/Ouro:</strong> Proteção e diversificação.</li>
                    </ul>
                `,
                icon: "fa-scale-balanced"
            },
            "Arrojado": {
                priority: "Maximização de lucros no longo prazo.",
                behavior: "Entende oscilações como parte do processo e busca crescimento patrimonial acelerado.",
                common: "Ações na B3, ETFs, Criptomoedas e Investimentos no Exterior.",
                chartData: [35, 45, 20],
                chartLabels: ["Renda Fixa", "Ações/FIIs", "Intl/Cripto"],
                chartColors: ["#10b981", "#ef4444", "#3b82f6"],
                details: `
                    <p class="font-bold text-white mb-2">Foco: Crescimento Acelerado</p>
                    <ul class="list-disc pl-4 space-y-1">
                        <li><strong>30% a 40% em Renda Fixa:</strong> Caixa para oportunidades.</li>
                        <li><strong>40% a 50% em Ações e FIIs:</strong> Growth e setor imobiliário.</li>
                        <li><strong>10% a 20% em Intl/Cripto:</strong> Dólar, tech e escassez digital.</li>
                    </ul>
                `,
                icon: "fa-rocket"
            }
        };

        let myPieChart = null;

        // --- Calculation Logic ---
        async function calculateProfile() {
            const formData = new FormData(form);
            let score = 0;
            let answeredCount = 0;

            let horizonScore = 0;
            let riskScore = 0;

            for (let [key, value] of formData.entries()) {
                let val = parseInt(value);
                score += val;
                answeredCount++;
                if (key === 'p2') horizonScore = val;
                if (key === 'p7') riskScore = val;
            }

            if (answeredCount < totalQuestions) {
                alert("Por favor, responda todas as " + totalQuestions + " perguntas para uma análise precisa.");
                return;
            }

            let profileKey = "";

            // Classification Logic
            if (horizonScore === 1 || riskScore === 1) {
                profileKey = "Conservador";
            } else if (score <= 20) {
                profileKey = "Conservador";
            } else if (score <= 32) {
                profileKey = "Moderado";
            } else {
                profileKey = "Arrojado";
            }

            const data = profilesData[profileKey];

            // Update UI Elements
            document.getElementById('profileName').textContent = profileKey;
            document.getElementById('profilePriority').textContent = data.priority;
            document.getElementById('profileBehavior').textContent = data.behavior;
            document.getElementById('profileCommon').textContent = data.common;
            document.getElementById('allocationDetails').innerHTML = data.details;
            document.getElementById('modalIcon').innerHTML = `<i class="fa-solid ${data.icon} text-3xl text-stone-navy"></i>`;

            // Render Chart
            renderChart(data);

            // Show Modal
            document.getElementById('resultModal').classList.remove('hidden');

            // --- Persist Profile ---
            (async () => {
                try {
                    const { error } = await _supabase.auth.updateUser({
                        data: { investor_profile: profileKey }
                    });
                    if (error) console.error("Supabase Error:", error);

                    const formData = new FormData();
                    formData.append('investor_profile', profileKey);
                    await fetch('api/update_profile.php', { method: 'POST', body: formData });

                    console.log("Perfil salvo!");
                } catch (err) {
                    console.error("Erro ao salvar:", err);
                }
            })();
        }

        function renderChart(data) {
            const ctx = document.getElementById('allocationChart').getContext('2d');

            if (myPieChart) {
                myPieChart.destroy();
            }

            myPieChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.chartLabels,
                    datasets: [{
                        data: data.chartData,
                        backgroundColor: data.chartColors,
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#a8a29e', // Stone Gray
                                font: {
                                    family: 'Montserrat',
                                    size: 10
                                },
                                boxWidth: 12
                            }
                        }
                    },
                    cutout: '65%'
                }
            });
        }

        function closeModal() {
            document.getElementById('resultModal').classList.add('hidden');
        }
    </script>
</body>

</html>