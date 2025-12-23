<!DOCTYPE html>
<html lang="pt-BR" class="dark">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Stone Edger Blog</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&amp;family=Inter:wght@300;400;500;600&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
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
                        // Alias for compatibility not to break existing semantic names immediately
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
                }
            }
        }
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
    <header class="relative py-20 bg-stone-navy/90 border-b border-stone-gold/20">
        <div class="absolute inset-0 overflow-hidden opacity-10 pointer-events-none">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary rounded-full blur-3xl opacity-20"></div>
            <div class="absolute top-1/2 left-1/4 w-64 h-64 bg-blue-900 rounded-full blur-3xl opacity-20"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <span
                class="inline-block py-1 px-3 rounded-full bg-primary/10 border border-primary/30 text-primary text-xs font-bold tracking-widest uppercase mb-4">
                Educação Financeira
            </span>
            <h1 class="text-4xl md:text-6xl font-display font-bold text-gray-900 dark:text-white mb-6">
                Insights para o seu <span class="italic text-primary">Patrimônio</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto mb-8 font-light">
                Análises de mercado, estratégias de investimento e as últimas novidades do cenário financeiro
                simplificadas para você.
            </p>
            <div class="max-w-xl mx-auto relative">
                <input
                    class="w-full py-4 pl-12 pr-4 rounded bg-white dark:bg-accent-dark border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent shadow-lg transition-all"
                    placeholder="Buscar artigos, temas ou autores..." type="text" />
                <span
                    class="material-icons absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">search</span>
            </div>
        </div>
    </header>
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">
        <section class="mb-16" id="secaoDestaque">
            <div class="flex items-center gap-2 mb-6">
                <span class="h-0.5 w-8 bg-primary"></span>
                <h2 class="text-xl font-bold uppercase tracking-widest text-gray-900 dark:text-white">Destaque da Semana
                </h2>
            </div>
            <div id="destaqueArtigo"
                class="grid md:grid-cols-12 gap-8 bg-stone-navy/90 rounded-xl overflow-hidden border border-stone-glassBorder shadow-xl group hover:border-stone-gold/50 transition-all duration-300">
                <!-- Conteúdo será inserido via JavaScript -->
            </div>
        </section>
        <section class="mb-10">
            <div class="flex flex-wrap items-center justify-between border-b border-stone-gold/20 pb-4 gap-4">
                <div class="flex overflow-x-auto space-x-2 md:space-x-4 pb-2 md:pb-0 scrollbar-hide">
                    <button
                        class="btn-filtro-categoria whitespace-nowrap px-4 py-2 rounded-full bg-gradient-gold text-stone-navy font-bold text-sm"
                        data-categoria="">Todos</button>
                    <button
                        class="btn-filtro-categoria whitespace-nowrap px-4 py-2 rounded-full border border-stone-glassBorder text-stone-gray hover:bg-stone-glassBorder text-sm transition-colors"
                        data-categoria="Renda Fixa">Renda Fixa</button>
                    <button
                        class="btn-filtro-categoria whitespace-nowrap px-4 py-2 rounded-full border border-stone-glassBorder text-stone-gray hover:bg-stone-glassBorder text-sm transition-colors"
                        data-categoria="Renda Variável">Renda Variável</button>
                    <button
                        class="btn-filtro-categoria whitespace-nowrap px-4 py-2 rounded-full border border-stone-glassBorder text-stone-gray hover:bg-stone-glassBorder text-sm transition-colors"
                        data-categoria="Economia Global">Economia Global</button>
                    <button
                        class="btn-filtro-categoria whitespace-nowrap px-4 py-2 rounded-full border border-stone-glassBorder text-stone-gray hover:bg-stone-glassBorder text-sm transition-colors"
                        data-categoria="Educação">Educação</button>
                    <button
                        class="btn-filtro-categoria whitespace-nowrap px-4 py-2 rounded-full border border-stone-glassBorder text-stone-gray hover:bg-stone-glassBorder text-sm transition-colors"
                        data-categoria="Mercado Financeiro">Mercado Financeiro</button>
                    <button
                        class="btn-filtro-categoria whitespace-nowrap px-4 py-2 rounded-full border border-stone-glassBorder text-stone-gray hover:bg-stone-glassBorder text-sm transition-colors"
                        data-categoria="Criptoativos">Criptoativos</button>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <span>Ordenar por:</span>
                    <select id="ordenarArtigos"
                        class="bg-transparent border-none font-semibold text-white focus:ring-0 cursor-pointer p-0 pr-8">
                        <option value="recente">Mais recentes</option>
                        <option value="antigo">Mais antigos</option>
                    </select>
                </div>
            </div>
        </section>
        <div id="listaArtigos" class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Artigos serão inseridos via JavaScript -->
            <div
                class="flex flex-col bg-gradient-gold rounded-lg overflow-hidden p-8 text-center justify-center items-center shadow-lg relative">
                <div class="absolute inset-0 bg-stone-navy/10 mix-blend-overlay"></div>
                <div class="relative z-10">
                    <div
                        class="w-16 h-16 bg-stone-navy text-stone-gold rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="material-icons text-3xl">mark_email_unread</span>
                    </div>
                    <h3 class="text-2xl font-display font-bold text-black mb-2">
                        Morning Call
                    </h3>
                    <p class="text-black/80 text-sm mb-6 px-4">
                        Receba nossas análises diárias antes da abertura do mercado.
                    </p>
                    <form class="w-full">
                        <input
                            class="w-full mb-3 px-4 py-3 rounded bg-white/90 border-0 placeholder-gray-500 text-stone-navy focus:ring-2 focus:ring-stone-navy"
                            placeholder="Seu melhor e-mail" type="email" />
                        <button
                            class="w-full bg-stone-navy text-stone-gold font-bold py-3 rounded hover:bg-stone-navy/90 transition-colors uppercase tracking-wider text-sm">
                            Inscrever-se Grátis
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="mt-16 flex justify-center">
            <nav class="flex items-center gap-2">
                <button
                    class="w-10 h-10 flex items-center justify-center rounded border border-gray-300 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:bg-primary hover:text-black hover:border-primary transition-colors">
                    <span class="material-icons text-sm">chevron_left</span>
                </button>
                <button
                    class="w-10 h-10 flex items-center justify-center rounded bg-primary text-black font-bold border border-primary">1</button>
                <button
                    class="w-10 h-10 flex items-center justify-center rounded border border-gray-300 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">2</button>
                <button
                    class="w-10 h-10 flex items-center justify-center rounded border border-gray-300 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">3</button>
                <span class="text-gray-500 px-2">...</span>
                <button
                    class="w-10 h-10 flex items-center justify-center rounded border border-gray-300 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:bg-primary hover:text-black hover:border-primary transition-colors">
                    <span class="material-icons text-sm">chevron_right</span>
                </button>
            </nav>
        </div>
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
        // Carregar artigos do localStorage
        let artigos = JSON.parse(localStorage.getItem('artigos') || '[]');
        let categoriaFiltro = '';
        let ordenacao = 'recente';

        // Inicialização
        document.addEventListener('DOMContentLoaded', function () {
            renderizarDestaque();
            renderizarArtigos();

            // Event listeners para filtros
            document.querySelectorAll('.btn-filtro-categoria').forEach(btn => {
                btn.addEventListener('click', function () {
                    categoriaFiltro = this.dataset.categoria;
                    atualizarBotoesFiltro();
                    renderizarArtigos();
                });
            });

            const ordenarSelect = document.getElementById('ordenarArtigos');
            if (ordenarSelect) {
                ordenarSelect.addEventListener('change', function () {
                    ordenacao = this.value;
                    renderizarArtigos();
                });
            }
        });

        function atualizarBotoesFiltro() {
            document.querySelectorAll('.btn-filtro-categoria').forEach(btn => {
                if (btn.dataset.categoria === categoriaFiltro) {
                    btn.className = 'btn-filtro-categoria whitespace-nowrap px-4 py-2 rounded-full bg-gradient-gold text-stone-navy font-bold text-sm';
                } else {
                    btn.className = 'btn-filtro-categoria whitespace-nowrap px-4 py-2 rounded-full border border-stone-glassBorder text-stone-gray hover:bg-stone-glassBorder text-sm transition-colors';
                }
            });
        }

        function renderizarDestaque() {
            const destaqueContainer = document.getElementById('destaqueArtigo');
            if (!destaqueContainer) return;

            const artigoDestaque = artigos.find(a => a.destaque) || artigos[0];

            if (!artigoDestaque) {
                destaqueContainer.innerHTML = '<div class="col-span-12 text-center py-12 text-gray-500 dark:text-gray-400">Nenhum artigo em destaque.</div>';
                return;
            }

            const dataFormatada = formatarData(artigoDestaque.dataPublicacao);

            destaqueContainer.innerHTML = `
        <div class="md:col-span-7 relative h-64 md:h-auto overflow-hidden">
            <img alt="${artigoDestaque.titulo}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" src="${artigoDestaque.imagemUrl}"/>
            <div class="absolute inset-0 bg-gradient-to-t from-stone-navy/90 via-transparent to-transparent opacity-80"></div>
        </div>
        <div class="md:col-span-5 p-8 flex flex-col justify-center">
            <div class="flex items-center gap-3 mb-4 text-xs font-bold tracking-wider text-stone-gold">
                <span class="uppercase">${artigoDestaque.categoria}</span>
                <span class="text-stone-gray">•</span>
                <span class="text-stone-gray">${artigoDestaque.tempoLeitura} min de leitura</span>
            </div>
            <h3 class="text-3xl font-display font-bold text-white mb-4 leading-tight group-hover:text-stone-gold transition-colors">
                ${artigoDestaque.titulo}
            </h3>
            <p class="text-stone-gray mb-6 line-clamp-3">
                ${artigoDestaque.descricao}
            </p>
            <div class="mt-auto flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img alt="Avatar do autor" class="w-10 h-10 rounded-full border-2 border-stone-gold" src="${artigoDestaque.avatarAutor}"/>
                    <div>
                        <p class="text-sm font-semibold text-white">${artigoDestaque.autor}</p>
                        <p class="text-xs text-stone-gray">${artigoDestaque.cargoAutor}</p>
                    </div>
                </div>
                <a class="text-primary font-bold text-sm flex items-center gap-1 group-hover:translate-x-1 transition-transform" href="artigo.php?id=${artigoDestaque.id}">
                    Ler Artigo <span class="material-icons text-sm">arrow_forward</span>
                </a>
            </div>
        </div>
    `;
        }

        function renderizarArtigos() {
            const listaContainer = document.getElementById('listaArtigos');
            if (!listaContainer) return;

            // Filtrar artigos
            let artigosFiltrados = artigos.filter(artigo => {
                // Excluir o artigo em destaque da lista
                if (artigo.destaque) return false;
                // Filtrar por categoria
                if (categoriaFiltro && artigo.categoria !== categoriaFiltro) return false;
                return true;
            });

            // Ordenar
            if (ordenacao === 'recente') {
                artigosFiltrados.sort((a, b) => new Date(b.dataPublicacao) - new Date(a.dataPublicacao));
            } else if (ordenacao === 'antigo') {
                artigosFiltrados.sort((a, b) => new Date(a.dataPublicacao) - new Date(b.dataPublicacao));
            }

            if (artigosFiltrados.length === 0) {
                listaContainer.innerHTML = '<div class="col-span-full text-center py-12 text-gray-500 dark:text-gray-400">Nenhum artigo encontrado.</div>';
                return;
            }

            listaContainer.innerHTML = artigosFiltrados.map(artigo => {
                const dataFormatada = formatarData(artigo.dataPublicacao);
                return `
            <article class="flex flex-col bg-stone-navy/90 rounded-lg overflow-hidden border border-stone-glassBorder hover:shadow-2xl hover:border-stone-gold/30 transition-all duration-300 group">
                <div class="h-48 overflow-hidden relative">
                    <img alt="${artigo.titulo}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" src="${artigo.imagemUrl}"/>
                    <span class="absolute top-4 left-4 bg-stone-navy/70 backdrop-blur-sm text-stone-gold text-xs font-bold px-3 py-1 rounded uppercase tracking-wider border border-stone-gold/20">${artigo.categoria}</span>
                </div>
                <div class="p-6 flex-grow flex flex-col">
                    <div class="text-stone-gray text-xs mb-3 flex items-center gap-2">
                        <span class="material-icons text-sm text-stone-gold">calendar_today</span> ${dataFormatada}
                    </div>
                    <h3 class="text-xl font-display font-bold text-white mb-3 group-hover:text-stone-gold transition-colors">
                        ${artigo.titulo}
                    </h3>
                    <p class="text-stone-gray text-sm mb-4 line-clamp-3 flex-grow">
                        ${artigo.descricao}
                    </p>
                    <a class="inline-flex items-center text-stone-gold font-bold text-sm hover:underline" href="artigo.php?id=${artigo.id}">
                        Ler na íntegra <span class="material-icons text-sm ml-1">arrow_forward</span>
                    </a>
                </div>
            </article>
        `;
            }).join('');
        }

        function formatarData(data) {
            const date = new Date(data);
            const meses = ['JAN', 'FEV', 'MAR', 'ABR', 'MAI', 'JUN', 'JUL', 'AGO', 'SET', 'OUT', 'NOV', 'DEZ'];
            return `${date.getDate()} ${meses[date.getMonth()]}, ${date.getFullYear()}`;
        }
    </script>

</body>

</html>