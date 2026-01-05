<!DOCTYPE html>
<html lang="pt-BR" class="dark">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Stone Edger - Artigo</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&amp;family=Inter:wght@300;400;500;600&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
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
                        // Brand colors for articles
                        'brand-bg': '#0F1115',
                        'brand-card': '#1A1D23',
                        'brand-text': '#E5E7EB',
                        'brand-accent-green': '#58A65C',
                        'brand-accent-red': '#E26D5C',
                        'brand-gray': '#9CA3AF',
                        'brand-border': '#2D323B',
                        // Alias for compatibility
                        primary: '#D4AF37',
                        "primary-hover": '#b5952f',
                    },
                    fontFamily: {
                        display: ['"Playfair Display"', 'serif'],
                        sans: ['"Montserrat"', 'sans-serif'],
                        playfair: ['"Playfair Display"', 'serif'],
                        montserrat: ['"Montserrat"', 'sans-serif'],
                    },
                    backgroundImage: {
                        'gradient-gold': 'linear-gradient(135deg, #D4AF37, #b39020)',
                    },
                    typography: (theme) => ({
                        DEFAULT: {
                            css: {
                                color: theme('colors.stone.gray'),
                                a: {
                                    color: theme('colors.stone.gold'),
                                    textDecoration: 'none',
                                    fontWeight: '600',
                                    '&:hover': {
                                        color: theme('colors.stone.goldHover'),
                                        textDecoration: 'underline',
                                    },
                                },
                                h1: {
                                    color: '#ffffff',
                                    fontFamily: theme('fontFamily.display'),
                                },
                                h2: {
                                    color: '#ffffff',
                                    marginTop: '2.5em',
                                    marginBottom: '1em',
                                    fontFamily: theme('fontFamily.display'),
                                },
                                h3: {
                                    color: '#ffffff',
                                    fontFamily: theme('fontFamily.display'),
                                },
                                h4: {
                                    color: '#ffffff',
                                    fontFamily: theme('fontFamily.display'),
                                },
                                strong: { color: '#ffffff', fontWeight: '700' },
                                blockquote: {
                                    color: theme('colors.stone.gray'),
                                    borderLeftColor: theme('colors.stone.gold'),
                                    fontStyle: 'italic',
                                },
                                code: {
                                    color: theme('colors.stone.gold'),
                                    backgroundColor: theme('colors.stone.navy'),
                                    padding: '0.25rem',
                                    borderRadius: '0.25rem',
                                    fontWeight: '500',
                                },
                                'ul > li::marker': {
                                    color: theme('colors.stone.gold'),
                                },
                            },
                        },
                    }),
                },
            },
        };
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Playfair Display', serif;
        }

        .text-gradient {
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            background-image: linear-gradient(to right, #D4AF37, #b39020);
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
            background: rgba(0, 0, 0, 0.95);
            /* Overlay escuro */
            z-index: -1;
        }
    </style>
</head>

<body class="text-stone-light font-montserrat transition-colors duration-300 min-h-screen flex flex-col">
    <div class="fixed-bg"></div>
    <div class="bg-overlay"></div>
    <nav class="sticky top-0 z-50 bg-stone-navy/95 backdrop-blur border-b border-stone-gold/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center">
                    <a class="flex items-center gap-2" href="index.php">
                        <div
                            class="h-8 w-8 rounded-full border border-primary flex items-center justify-center text-primary font-serif font-bold">
                            S</div>
                        <span class="font-display font-bold text-xl tracking-wide text-white">STONE
                            <span class="text-stone-gold">EDGER</span></span>
                    </a>
                </div>
                <div class="hidden md:flex space-x-8 items-center">
                    <a class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors text-sm font-medium uppercase tracking-wider"
                        href="index.php">Home</a>
                    <a class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors text-sm font-medium uppercase tracking-wider"
                        href="index.php#sobre">Sobre</a>
                    <a class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors text-sm font-medium uppercase tracking-wider"
                        href="index.php#atuacao">Atuação</a>
                    <a class="text-primary font-bold border-b-2 border-primary pb-1 text-sm uppercase tracking-wider"
                        href="blog.php">Blog</a>
                    <a class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors text-sm font-medium uppercase tracking-wider"
                        href="contatos.php">Contatos</a>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <button
                        class="bg-transparent border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors flex items-center gap-2">
                        <span class="material-icons text-sm">login</span> Login
                    </button>
                    <button
                        class="bg-primary text-black px-5 py-2 rounded text-sm font-bold hover:bg-primary-hover transition-colors flex items-center gap-2 shadow-lg shadow-primary/20">
                        <span class="material-icons text-sm">person_add</span> Cadastro
                    </button>
                </div>
                <div class="md:hidden flex items-center">
                    <button class="text-gray-500 hover:text-white focus:outline-none">
                        <span class="material-icons text-3xl">menu</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>
    <main class="flex-grow">
        <div
            class="relative w-full bg-surface-light dark:bg-surface-dark pt-12 pb-8 border-b border-gray-200 dark:border-gray-800">
            <div class="mx-auto max-w-4xl px-4 sm:px-6">
                <div class="mb-4 flex items-center gap-2 text-sm text-primary font-medium uppercase tracking-wider">
                    <span id="artigo-categoria">Mercado Financeiro</span>
                    <span class="text-gray-400 dark:text-gray-500">•</span>
                    <span class="text-gray-400 dark:text-gray-500" id="artigo-tempo-leitura">8 min de leitura</span>
                </div>
                <h1 class="mb-6 text-4xl font-display font-bold leading-tight tracking-tight text-gray-900 dark:text-white sm:text-5xl lg:text-6xl"
                    id="artigo-titulo">
                    Como proteger sua carteira em tempos de alta inflação
                </h1>
                <p class="mb-8 text-xl leading-relaxed text-gray-600 dark:text-gray-400" id="artigo-descricao">
                    Entenda quais ativos performam melhor quando o poder de compra diminui e descubra estratégias
                    fundamentais para manter a rentabilidade real dos seus investimentos no longo prazo.
                </p>
                <div class="flex items-center gap-4 border-t border-gray-200 dark:border-gray-800 pt-6">
                    <div class="h-12 w-12 overflow-hidden rounded-full border-2 border-primary">
                        <img id="artigo-avatar" alt="Avatar do autor" class="h-full w-full object-cover"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuAG5qunG-npeGTjIiO1HjH_cGXnR8tky9tmuLh4Runh4YCm2vZQ1w6weiks2nWSXy4UmcZ3RIBElOfdekqgrc-fswIRA1WDwc1w8I54ZH3m63RipQFRo6KZA3lnkDELxv8K4EBPKeC42dbzQ1JjH5yTT2yADQEPrbvSIGuoNjECMP-hraXMdg4pIEbGgGfWQbet_IOud2UJQ4R7Pyq-ea83hBZD46hdOV5jLFGc55DKHcGSIt8dPmsg1fIR1nZVYsQU43uR5P7ZNcI" />
                    </div>
                    <div class="flex flex-col">
                        <span class="text-base font-bold text-gray-900 dark:text-white" id="artigo-autor">Carlos
                            Mendes</span>
                        <span class="text-sm text-gray-500 dark:text-gray-400" id="artigo-data-cargo">Analista Chefe •
                            12 OUT, 2023</span>
                    </div>
                    <div class="ml-auto flex gap-2">
                        <button aria-label="Compartilhar"
                            class="flex h-8 w-8 items-center justify-center rounded-full border border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-primary hover:text-black hover:border-primary transition-colors">
                            <span class="material-icons text-lg">share</span>
                        </button>
                        <button aria-label="Salvar"
                            class="flex h-8 w-8 items-center justify-center rounded-full border border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-primary hover:text-black hover:border-primary transition-colors">
                            <span class="material-icons text-lg">bookmark_border</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full bg-background-light dark:bg-background-dark pb-12">
            <div class="mx-auto max-w-5xl px-4 sm:px-6">
                <div
                    class="aspect-video w-full overflow-hidden rounded-xl bg-surface-light dark:bg-surface-dark relative border border-gray-200 dark:border-gray-800">
                    <img id="artigo-imagem" alt="Imagem do artigo" class="h-full w-full object-cover opacity-90"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuDzZzKQCkf9I5lb_u-VATboJf6Uz-kJpzCk1wyy-VYcpLmyOW-pW7VhnECtqA58oaFR9DFBSo2FqF3GPDBNUmvvCrP_vaXnNGFpyizqmyqRd9u5uWAzv_qchK2Xt6davT9jxCnJXTG0CHnMPZnmZrWH3G0EzhF-g9oRbgOdj2otczongo4pfx4WzYtvp-kZa39zsFrgSAj6LYSsmFD2CRaPMnTLxtOUg0sKvrOa45vSNBt74wkH1d285dlNncqx0uQdEGUvMjRAHbA" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                </div>
            </div>
        </div>
        <div class="relative mx-auto flex max-w-7xl justify-center px-4 sm:px-6 pb-20">
            <aside class="hidden xl:flex fixed left-[calc(50%-640px)] top-32 flex-col gap-4">
                <p
                    class="mb-2 text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400 [writing-mode:vertical-rl] rotate-180">
                    Compartilhar</p>
                <button
                    class="group flex h-10 w-10 items-center justify-center rounded-lg border border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:border-primary hover:bg-primary/10 hover:text-primary transition-all">
                    <span class="text-sm font-bold">In</span>
                </button>
                <button
                    class="group flex h-10 w-10 items-center justify-center rounded-lg border border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:border-primary hover:bg-primary/10 hover:text-primary transition-all">
                    <span class="text-sm font-bold">X</span>
                </button>
                <button
                    class="group flex h-10 w-10 items-center justify-center rounded-lg border border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:border-primary hover:bg-primary/10 hover:text-primary transition-all">
                    <span class="material-icons text-[20px]">link</span>
                </button>
            </aside>
            <div class="w-full max-w-[680px]">
                <article class="prose prose-lg dark:prose-invert max-w-none" id="artigo-conteudo">
                    <p class="lead text-xl text-gray-700 dark:text-gray-300">
                        A inflação é um dos maiores desafios para investidores que buscam preservar e fazer crescer seu
                        patrimônio. Quando o poder de compra diminui, é fundamental entender quais ativos podem oferecer
                        proteção real e até mesmo oportunidades de crescimento.
                    </p>
                    <p>
                        Muitos investidores focam apenas na rentabilidade nominal, mas o que realmente importa é a
                        rentabilidade real - aquela que supera a inflação. Neste artigo, exploramos estratégias
                        comprovadas para proteger sua carteira em períodos de alta inflação.
                    </p>
                </article>
                <div class="mt-12 mb-8 h-px w-full bg-gray-200 dark:bg-gray-800"></div>
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">Tags:</span>
                        <div class="flex flex-wrap gap-2" id="artigo-tags">
                            <span
                                class="rounded-full bg-surface-light dark:bg-surface-dark border border-gray-200 dark:border-gray-800 px-3 py-1 text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-primary hover:border-primary cursor-pointer transition-colors">Renda
                                Fixa</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            class="flex items-center gap-1 rounded-lg px-3 py-1.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-surface-light dark:hover:bg-surface-dark hover:text-primary transition-colors border border-gray-200 dark:border-gray-800">
                            <span class="material-icons text-[18px]">thumb_up</span>
                            <span>245</span>
                        </button>
                        <button
                            class="flex items-center gap-1 rounded-lg px-3 py-1.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-surface-light dark:hover:bg-surface-dark hover:text-primary transition-colors border border-gray-200 dark:border-gray-800">
                            <span class="material-icons text-[18px]">comment</span>
                            <span>42</span>
                        </button>
                    </div>
                </div>
                <section class="mt-16">
                    <h3 class="mb-6 text-xl font-display font-bold text-gray-900 dark:text-white">Discussão (42)</h3>
                    <div class="mb-10 flex gap-4">
                        <div
                            class="h-10 w-10 flex-shrink-0 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold border border-primary/30">
                            Você</div>
                        <div class="flex-grow">
                            <textarea
                                class="w-full rounded-lg bg-surface-light dark:bg-surface-dark border border-gray-200 dark:border-gray-800 p-3 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors resize-y min-h-[100px]"
                                placeholder="Compartilhe seus pensamentos sobre esta análise..."></textarea>
                            <div class="mt-2 flex justify-end">
                                <button
                                    class="rounded-lg bg-primary px-4 py-2 text-sm font-bold text-black hover:bg-primary-hover transition-colors">Publicar
                                    Comentário</button>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="h-10 w-10 overflow-hidden rounded-full border-2 border-primary"
                            data-alt="User avatar">
                            <img alt="Avatar do usuário" class="h-full w-full object-cover"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuC6CfMpHuKCMW0lQ4KbrP3vzrf1IuMhyl4hcbnY8GVofVgE7PwXOIdOEddwfHgzJUlYCYPkpHdNwsquy3DCRixcH4ND9q2L6IL9LQ6Kfc030scFBBvvDemSPbcGrewYiPL-hPoYLiMiMfBI0wE3rSBlRzbRimN4wYH36UrxEyevg5xgabd30PAcUqNDtU6Jde2wq9c88kAYT1aY0mlj6kQzPZ_Obm3rsUKc62duCCI3obWovyCfSxF7DSxYn2hE_hXUCcqVm_A_iIQ" />
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-bold text-gray-900 dark:text-white text-sm">Marcus Chen</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">há 2 horas</span>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                Exatamente o que eu precisava. Estava com dificuldades para entender como proteger minha
                                carteira durante períodos inflacionários. A tabela de comparação realmente esclareceu os
                                aspectos de proteção para mim.
                            </p>
                            <div class="mt-2 flex items-center gap-4">
                                <button
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 hover:text-primary transition-colors">Responder</button>
                                <button
                                    class="flex items-center gap-1 text-xs font-medium text-gray-500 dark:text-gray-400 hover:text-primary transition-colors">
                                    <span class="material-icons text-[14px]">favorite</span> 12
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <section class="border-t border-stone-gold/20 bg-stone-navy/90 py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6">
                <h2 class="mb-8 text-2xl font-display font-bold text-gray-900 dark:text-white">Continue Lendo</h2>
                <div id="continue-lendo-grid" class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Artigos carregados via JS -->
                </div>
            </div>
        </section>

    </main>
    <footer class="bg-stone-navy/80 backdrop-blur-lg pt-16 pb-8 border-t border-stone-gold/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center gap-2 mb-6">
                        <div
                            class="h-8 w-8 rounded-full border border-primary flex items-center justify-center text-primary font-serif font-bold">
                            S</div>
                        <span class="font-display font-bold text-xl tracking-wide text-white">STONE <span
                                class="text-primary">EDGER</span></span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed mb-6">
                        Nosso objetivo é ajudar você a construir e proteger seu legado financeiro com clareza,
                        simplicidade e transparência.
                    </p>
                    <div class="flex gap-4">
                        <a class="w-10 h-10 rounded border border-gray-700 flex items-center justify-center text-gray-400 hover:text-primary hover:border-primary transition-colors"
                            href="#">
                            <span class="material-icons text-lg">facebook</span>
                        </a>
                        <a class="w-10 h-10 rounded border border-gray-700 flex items-center justify-center text-gray-400 hover:text-primary hover:border-primary transition-colors"
                            href="#">
                            <span class="material-icons text-lg">camera_alt</span>
                        </a>
                        <a class="w-10 h-10 rounded border border-gray-700 flex items-center justify-center text-gray-400 hover:text-primary hover:border-primary transition-colors"
                            href="#">
                            <span class="material-icons text-lg">business_center</span>
                        </a>
                    </div>
                </div>
                <div>
                    <h4 class="text-primary font-bold text-sm uppercase tracking-widest mb-6">Links Rápidos</h4>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li><a class="hover:text-primary transition-colors" href="index.php">Home</a></li>
                        <li><a class="hover:text-primary transition-colors" href="index.php#sobre">Sobre Nós</a></li>
                        <li><a class="hover:text-primary transition-colors" href="index.php#atuacao">Áreas de
                                Atuação</a></li>
                        <li><a class="hover:text-primary transition-colors" href="blog.php">Blog</a></li>
                        <li><a class="hover:text-primary transition-colors" href="contatos.php">Contato</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-primary font-bold text-sm uppercase tracking-widest mb-6">Informações</h4>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li class="flex items-start gap-3">
                            <span class="material-icons text-primary text-base mt-0.5">location_on</span>
                            <span>Av. Júlio Lima, 132<br />Rio de Janeiro - RJ</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="material-icons text-primary text-base">email</span>
                            <span>contato@stoneedger.com.br</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="material-icons text-primary text-base">phone</span>
                            <span>+55 (21) 99412-0058</span>
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-primary font-bold text-sm uppercase tracking-widest mb-6">Newsletter</h4>
                    <form class="flex flex-col gap-3">
                        <input
                            class="bg-black/30 border border-gray-700 text-white px-4 py-2 rounded focus:border-primary focus:ring-0 text-sm"
                            placeholder="Email" type="email" />
                        <button
                            class="bg-primary text-black font-bold py-2 rounded hover:bg-primary-hover transition-colors text-sm uppercase">OK</button>
                    </form>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-500 text-xs">© 2025 Stone Edger. Todos os direitos reservados.</p>
                <div class="flex gap-6 text-xs text-gray-500">
                    <a class="hover:text-primary" href="#">Termos de Uso</a>
                    <a class="hover:text-primary" href="#">Privacidade</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Função para obter parâmetro da URL
        function obterParametroURL(nome) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(nome);
        }

        // Carregar artigo do localStorage
        function carregarArtigo() {
            const artigoId = parseInt(obterParametroURL('id'));

            if (!artigoId) {
                mostrarErro();
                return;
            }

            const artigos = JSON.parse(localStorage.getItem('artigos') || '[]');
            const artigo = artigos.find(a => a.id === artigoId);

            if (!artigo) {
                mostrarErro();
                return;
            }

            // Preencher informações do artigo
            document.getElementById('artigo-categoria').textContent = artigo.categoria;
            document.getElementById('artigo-tempo-leitura').textContent = `${artigo.tempoLeitura} min de leitura`;
            document.getElementById('artigo-titulo').textContent = artigo.titulo;
            document.getElementById('artigo-descricao').textContent = artigo.descricao;
            document.getElementById('artigo-avatar').src = artigo.avatarAutor;
            document.getElementById('artigo-avatar').alt = artigo.autor;
            document.getElementById('artigo-autor').textContent = artigo.autor;
            document.getElementById('artigo-data-cargo').textContent = `${artigo.cargoAutor} • ${formatarData(artigo.dataPublicacao)}`;
            document.getElementById('artigo-imagem').src = artigo.imagemUrl;
            document.getElementById('artigo-imagem').alt = artigo.titulo;

            // Preencher conteúdo do artigo
            const conteudoContainer = document.getElementById('artigo-conteudo');
            // Se o conteúdo contém HTML, usar innerHTML, senão usar textContent e converter quebras de linha
            if (artigo.conteudo.includes('<') || artigo.conteudo.includes('&lt;')) {
                conteudoContainer.innerHTML = artigo.conteudo;
            } else {
                // Converter quebras de linha em parágrafos
                const paragrafos = artigo.conteudo.split('\n\n').filter(p => p.trim());
                conteudoContainer.innerHTML = paragrafos.map(p => {
                    const texto = p.trim();
                    if (texto.startsWith('#')) {
                        // É um título
                        const nivel = texto.match(/^#+/)[0].length;
                        const textoLimpo = texto.replace(/^#+\s*/, '');
                        return `<h${nivel}>${textoLimpo}</h${nivel}>`;
                    } else if (texto.startsWith('-') || texto.startsWith('*')) {
                        // É uma lista
                        const itens = texto.split('\n').filter(i => i.trim().startsWith('-') || i.trim().startsWith('*'));
                        const listaHTML = itens.map(item => {
                            const textoItem = item.replace(/^[-*]\s*/, '');
                            return `<li>${textoItem}</li>`;
                        }).join('');
                        return `<ul>${listaHTML}</ul>`;
                    } else {
                        return `<p>${texto}</p>`;
                    }
                }).join('');
            }

            // Preencher tags (usando a categoria como tag principal)
            const tagsContainer = document.getElementById('artigo-tags');
            tagsContainer.innerHTML = `
        <span class="rounded-full bg-surface-light dark:bg-surface-dark border border-gray-200 dark:border-gray-800 px-3 py-1 text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-primary hover:border-primary cursor-pointer transition-colors">${artigo.categoria}</span>
    `;

            // Atualizar título da página
            document.title = `${artigo.titulo} - Stone Edger`;

            // Carregar artigos recomendados
            carregarRecomendados(artigo.id);

            // Executar scripts contidos no conteúdo
            setTimeout(() => {
                executarScripts(document.getElementById('artigo-conteudo'));
            }, 100);
        }

        // Função para executar scripts injetados via innerHTML
        function executarScripts(container) {
            const scripts = container.querySelectorAll('script');
            scripts.forEach(oldScript => {
                const newScript = document.createElement('script');
                Array.from(oldScript.attributes).forEach(attr => {
                    newScript.setAttribute(attr.name, attr.value);
                });
                newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                oldScript.parentNode.replaceChild(newScript, oldScript);
            });
        }

        function carregarRecomendados(idAtual) {
            const container = document.getElementById('continue-lendo-grid');
            if (!container) return;

            const artigos = JSON.parse(localStorage.getItem('artigos') || '[]');

            // Filtrar artigo atual e embaralhar
            const recomendados = artigos
                .filter(a => a.id !== idAtual)
                .sort(() => 0.5 - Math.random())
                .slice(0, 3);

            if (recomendados.length === 0) {
                // Esconde a seção se não houver outros artigos
                if (container.parentElement && container.parentElement.tagName === 'SECTION') {
                    container.parentElement.style.display = 'none';
                }
                return;
            }

            container.innerHTML = recomendados.map(artigo => `
                <a class="group flex flex-col gap-3" href="artigo.php?id=${artigo.id}">
                    <div class="aspect-[16/9] w-full overflow-hidden rounded-lg bg-surface-light dark:bg-surface-dark relative border border-gray-200 dark:border-gray-800">
                        <img alt="${artigo.titulo}"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105 opacity-90"
                            src="${artigo.imagemUrl}" />
                        <div class="absolute top-3 left-3 rounded bg-primary px-2 py-1 text-xs font-bold text-black">
                            ${artigo.categoria}
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-display font-bold text-gray-900 dark:text-white group-hover:text-primary transition-colors line-clamp-2">
                            ${artigo.titulo}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Por ${artigo.autor} • ${artigo.tempoLeitura} min de leitura
                        </p>
                    </div>
                </a>
            `).join('');
        }

        function mostrarErro() {
            document.querySelector('main').innerHTML = `
        <div class="text-center py-20">
            <div class="max-w-2xl mx-auto px-4">
                <h1 class="text-4xl font-display font-bold text-gray-900 dark:text-white mb-4">Artigo não encontrado</h1>
                <p class="text-gray-600 dark:text-gray-400 mb-8">O artigo que você está procurando não existe ou foi removido.</p>
                <a href="blog.php" class="inline-flex items-center text-primary font-bold hover:underline">
                    <span class="material-icons mr-2">arrow_back</span> Voltar para o Blog
                </a>
            </div>
        </div>
    `;
        }

        function formatarData(data) {
            const date = new Date(data);
            const meses = ['JAN', 'FEV', 'MAR', 'ABR', 'MAI', 'JUN', 'JUL', 'AGO', 'SET', 'OUT', 'NOV', 'DEZ'];
            return `${date.getDate()} ${meses[date.getMonth()]}, ${date.getFullYear()}`;
        }

        // Carregar artigo quando a página carregar
        document.addEventListener('DOMContentLoaded', carregarArtigo);
    </script>

</body>

</html>