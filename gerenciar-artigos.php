<?php
require_once 'api/session_handler.php';

// Protection: Redirect if not logged in or not an admin
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: index.php?error=unauthorized');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR" class="dark">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Stone Edger - Gerenciar Artigos</title>
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
                    <a href="logout.php"
                        class="bg-transparent border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors flex items-center gap-2">
                        <span class="material-icons text-sm">logout</span> Sair
                    </a>
                </div>
                <div class="md:hidden flex items-center">
                    <button class="text-gray-500 hover:text-white focus:outline-none">
                        <span class="material-icons text-3xl">menu</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-display font-bold text-gray-900 dark:text-white mb-2">Gerenciar Artigos</h1>
                <p class="text-gray-600 dark:text-gray-400">Crie, edite e gerencie os artigos do blog</p>
            </div>
            <div class="flex gap-3">
                <a href="administra.php"
                    class="border border-stone-gold text-stone-gold px-6 py-3 rounded text-sm font-bold hover:bg-stone-gold hover:text-stone-navy transition-colors flex items-center gap-2">
                    <span class="material-icons text-sm">arrow_back</span> Voltar
                </a>
                <button id="btnNovoArtigo"
                    class="bg-primary text-black px-6 py-3 rounded text-sm font-bold hover:bg-primary-hover transition-colors flex items-center gap-2 shadow-lg shadow-primary/20">
                    <span class="material-icons text-sm">add</span> Novo Artigo
                </button>
            </div>
        </div>

        <!-- Modal para Criar/Editar Artigo -->
        <div id="modalArtigo" class="hidden fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-stone-navy bg-opacity-75 transition-opacity" onclick="fecharModal()"></div>
                <div
                    class="inline-block align-bottom bg-stone-navy/95 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-stone-gold/30">
                    <div
                        class="bg-stone-navy px-6 py-4 border-b border-stone-gold/20 flex justify-between items-center">
                        <h3 class="text-xl font-display font-bold text-white" id="modalTitulo">Novo
                            Artigo</h3>
                        <button onclick="fecharModal()" class="text-gray-500 hover:text-white">
                            <span class="material-icons">close</span>
                        </button>
                    </div>
                    <form id="formArtigo" class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-stone-gold mb-2">Título do
                                    Artigo</label>
                                <input type="text" id="titulo" name="titulo" required
                                    class="w-full px-4 py-2 rounded-lg bg-stone-navy border border-stone-glassBorder text-white focus:outline-none focus:ring-2 focus:ring-stone-gold focus:border-transparent placeholder-stone-gray"
                                    placeholder="Digite o título do artigo" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-stone-gold mb-2">Categoria</label>
                                <select id="categoria" name="categoria" required
                                    class="w-full px-4 py-2 rounded-lg bg-stone-navy border border-stone-glassBorder text-white focus:outline-none focus:ring-2 focus:ring-stone-gold focus:border-transparent">
                                    <option value="">Selecione uma categoria</option>
                                    <option value="Renda Fixa">Renda Fixa</option>
                                    <option value="Renda Variável">Renda Variável</option>
                                    <option value="Economia Global">Economia Global</option>
                                    <option value="Educação">Educação</option>
                                    <option value="Mercado Financeiro">Mercado Financeiro</option>
                                    <option value="Criptoativos">Criptoativos</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-stone-gray mb-2">Tempo de
                                    Leitura (minutos)</label>
                                <input type="number" id="tempoLeitura" name="tempoLeitura" min="1" required
                                    class="w-full px-4 py-2 rounded-lg bg-stone-navy border border-stone-glassBorder text-white focus:outline-none focus:ring-2 focus:ring-stone-gold focus:border-transparent placeholder-stone-gray"
                                    placeholder="Ex: 5" />
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-stone-gray mb-2">URL da
                                    Imagem</label>
                                <input type="url" id="imagemUrl" name="imagemUrl" required
                                    class="w-full px-4 py-2 rounded-lg bg-stone-navy border border-stone-glassBorder text-white focus:outline-none focus:ring-2 focus:ring-stone-gold focus:border-transparent placeholder-stone-gray"
                                    placeholder="https://exemplo.com/imagem.jpg" />
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-stone-gray mb-2">Descrição/Resumo</label>
                                <textarea id="descricao" name="descricao" rows="3" required
                                    class="w-full px-4 py-2 rounded-lg bg-stone-navy border border-stone-glassBorder text-white focus:outline-none focus:ring-2 focus:ring-stone-gold focus:border-transparent resize-none placeholder-stone-gray"
                                    placeholder="Breve descrição do artigo que aparecerá na listagem"></textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-stone-gray mb-2">Conteúdo
                                    do Artigo</label>
                                <textarea id="conteudo" name="conteudo" rows="12" required
                                    class="w-full px-4 py-2 rounded-lg bg-stone-navy border border-stone-glassBorder text-white focus:outline-none focus:ring-2 focus:ring-stone-gold focus:border-transparent resize-none font-mono text-sm placeholder-stone-gray"
                                    placeholder="Digite o conteúdo completo do artigo em Markdown ou HTML"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-stone-gray mb-2">Autor</label>
                                <input type="text" id="autor" name="autor" required
                                    class="w-full px-4 py-2 rounded-lg bg-stone-navy border border-stone-glassBorder text-white focus:outline-none focus:ring-2 focus:ring-stone-gold focus:border-transparent placeholder-stone-gray"
                                    placeholder="Nome do autor" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-stone-gray mb-2">Cargo do
                                    Autor</label>
                                <input type="text" id="cargoAutor" name="cargoAutor" required
                                    class="w-full px-4 py-2 rounded-lg bg-stone-navy border border-stone-glassBorder text-white focus:outline-none focus:ring-2 focus:ring-stone-gold focus:border-transparent placeholder-stone-gray"
                                    placeholder="Ex: Analista Chefe" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-stone-gray mb-2">URL do
                                    Avatar do Autor</label>
                                <input type="url" id="avatarAutor" name="avatarAutor" required
                                    class="w-full px-4 py-2 rounded-lg bg-stone-navy border border-stone-glassBorder text-white focus:outline-none focus:ring-2 focus:ring-stone-gold focus:border-transparent placeholder-stone-gray"
                                    placeholder="https://exemplo.com/avatar.jpg" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-stone-gray mb-2">Data de
                                    Publicação</label>
                                <input type="date" id="dataPublicacao" name="dataPublicacao" required
                                    class="w-full px-4 py-2 rounded-lg bg-stone-navy border border-stone-glassBorder text-white focus:outline-none focus:ring-2 focus:ring-stone-gold focus:border-transparent" />
                            </div>
                            <div class="md:col-span-2">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" id="destaque" name="destaque"
                                        class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary" />
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Marcar como Destaque da
                                        Semana</span>
                                </label>
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-800">
                            <button type="button" onclick="fecharModal()"
                                class="px-6 py-2 rounded-lg border border-stone-glassBorder text-stone-gray hover:bg-stone-navy hover:text-white transition-colors font-medium">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-6 py-2 rounded-lg bg-gradient-gold text-stone-navy font-bold hover:brightness-110 transition-colors shadow-lg shadow-stone-gold/20">
                                Salvar Artigo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Filtros e Busca -->
        <div class="mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex-1 w-full md:w-auto">
                <input type="text" id="buscarArtigo" placeholder="Buscar artigos..."
                    class="w-full md:w-96 px-4 py-2 rounded-lg bg-stone-navy/90 border border-stone-glassBorder text-white placeholder-stone-gray focus:outline-none focus:ring-2 focus:ring-stone-gold focus:border-transparent" />
            </div>
            <div class="flex gap-2">
                <select id="filtroCategoria"
                    class="px-4 py-2 rounded-lg bg-stone-navy/90 border border-stone-glassBorder text-white focus:outline-none focus:ring-2 focus:ring-stone-gold focus:border-transparent">
                    <option value="" class="bg-stone-navy">Todas as categorias</option>
                    <option value="Renda Fixa" class="bg-stone-navy">Renda Fixa</option>
                    <option value="Renda Variável" class="bg-stone-navy">Renda Variável</option>
                    <option value="Economia Global" class="bg-stone-navy">Economia Global</option>
                    <option value="Educação" class="bg-stone-navy">Educação</option>
                    <option value="Mercado Financeiro" class="bg-stone-navy">Mercado Financeiro</option>
                    <option value="Criptoativos" class="bg-stone-navy">Criptoativos</option>
                </select>
                <select id="ordenarPor"
                    class="px-4 py-2 rounded-lg bg-stone-navy/90 border border-stone-glassBorder text-white focus:outline-none focus:ring-2 focus:ring-stone-gold focus:border-transparent">
                    <option value="recente" class="bg-stone-navy">Mais recentes</option>
                    <option value="antigo" class="bg-stone-navy">Mais antigos</option>
                    <option value="titulo" class="bg-stone-navy">Título A-Z</option>
                </select>
            </div>
        </div>

        <!-- Lista de Artigos -->
        <div id="listaArtigos" class="grid gap-6">
            <!-- Os artigos serão inseridos aqui via JavaScript -->
        </div>

        <!-- Template de Card de Artigo -->
        <template id="templateArtigo">
            <div
                class="artigo-card bg-stone-navy/90 rounded-lg overflow-hidden border border-stone-glassBorder hover:shadow-2xl hover:border-stone-gold/30 transition-all duration-300">
                <div class="grid md:grid-cols-12 gap-0">
                    <div class="md:col-span-3 h-48 md:h-auto overflow-hidden relative">
                        <img class="artigo-imagem w-full h-full object-cover" src="" alt="" />
                        <span
                            class="absolute top-3 left-3 bg-stone-navy/70 backdrop-blur-sm text-stone-gold text-xs font-bold px-3 py-1 rounded uppercase tracking-wider border border-stone-gold/20 artigo-categoria"></span>
                    </div>
                    <div class="md:col-span-9 p-6 flex flex-col">
                        <div class="flex items-center gap-3 mb-3 text-xs font-bold tracking-wider text-stone-gold">
                            <span class="artigo-categoria"></span>
                            <span class="text-stone-gray">•</span>
                            <span class="artigo-tempo-leitura text-stone-gray"></span>
                        </div>
                        <h3 class="text-xl font-display font-bold text-white mb-2 artigo-titulo">
                        </h3>
                        <p class="text-stone-gray text-sm mb-4 line-clamp-2 flex-grow artigo-descricao">
                        </p>
                        <div class="flex items-center justify-between mt-auto">
                            <div class="flex items-center gap-3">
                                <img class="artigo-avatar w-8 h-8 rounded-full border-2 border-stone-gold object-cover"
                                    src="" alt="" />
                                <div>
                                    <p class="text-sm font-semibold text-white artigo-autor"></p>
                                    <p class="text-xs text-stone-gray artigo-data"></p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button
                                    class="btn-visualizar px-4 py-2 rounded-lg border border-stone-glassBorder text-stone-gray hover:bg-stone-navy hover:text-white transition-colors text-sm font-medium flex items-center gap-1">
                                    <span class="material-icons text-sm">visibility</span> Ver
                                </button>
                                <button
                                    class="btn-editar px-4 py-2 rounded-lg bg-stone-gold/10 border border-stone-gold text-stone-gold hover:bg-stone-gold hover:text-stone-navy transition-colors text-sm font-medium flex items-center gap-1">
                                    <span class="material-icons text-sm">edit</span> Editar
                                </button>
                                <button
                                    class="btn-deletar px-4 py-2 rounded-lg border border-red-300 dark:border-red-700 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors text-sm font-medium flex items-center gap-1">
                                    <span class="material-icons text-sm">delete</span> Deletar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
        </template>

        <!-- Modal de Confirmação de Exclusão -->
        <div id="modalConfirmacao" class="hidden fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-stone-navy bg-opacity-90 transition-opacity"
                    onclick="fecharModalConfirmacao()"></div>
                <div class="relative bg-stone-navy/95 rounded-lg shadow-xl max-w-md w-full border border-stone-gold/30">
                    <div class="p-6">
                        <h3 class="text-lg font-display font-bold text-white mb-4">Confirmar Exclusão
                        </h3>
                        <p class="text-stone-gray mb-6">Tem certeza que deseja excluir este artigo? Esta ação não pode
                            ser desfeita.</p>
                        <div class="flex justify-end gap-3">
                            <button onclick="fecharModalConfirmacao()"
                                class="px-4 py-2 rounded-lg border border-stone-glassBorder text-stone-gray hover:bg-stone-navy hover:text-white transition-colors text-sm font-medium">
                                Cancelar
                            </button>
                            <button id="btnConfirmarExclusao"
                                class="px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-bold hover:bg-red-700 transition-colors shadow-lg shadow-red-900/20">
                                Excluir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
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
                    <form class="flex flex-col gap-3" onsubmit="event.preventDefault();">
                        <input id="newsletter-email"
                            class="bg-black/30 border border-gray-700 text-white px-4 py-2 rounded focus:border-primary focus:ring-0 text-sm"
                            placeholder="Email" type="email" required />
                        <button id="newsletter-btn"
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
        // Armazenamento
        let artigos = [];
        let artigoEditando = null;

        // Inicialização
        document.addEventListener('DOMContentLoaded', function () {
            buscarArtigosSupabase();

            // Event listeners
            document.getElementById('btnNovoArtigo').addEventListener('click', abrirModalNovo);
            document.getElementById('formArtigo').addEventListener('submit', salvarArtigo);
            document.getElementById('buscarArtigo').addEventListener('input', filtrarArtigos);
            document.getElementById('filtroCategoria').addEventListener('change', filtrarArtigos);
            document.getElementById('ordenarPor').addEventListener('change', filtrarArtigos);
        });

        async function buscarArtigosSupabase() {
            try {
                const response = await fetch('api/get_articles.php');
                const data = await response.json();
                if (Array.isArray(data)) {
                    artigos = data;
                    renderizarArtigos();
                }
            } catch (error) {
                console.error('Erro ao buscar artigos:', error);
            }
        }

        function criarArtigosExemplo() {
            artigos = [
                {
                    id: 1,
                    titulo: "Como proteger sua carteira em tempos de alta inflação",
                    categoria: "Mercado Financeiro",
                    tempoLeitura: 8,
                    imagemUrl: "https://lh3.googleusercontent.com/aida-public/AB6AXuDzZzKQCkf9I5lb_u-VATboJf6Uz-kJpzCk1wyy-VYcpLmyOW-pW7VhnECtqA58oaFR9DFBSo2FqF3GPDBNUmvvCrP_vaXnNGFpyizqmyqRd9u5uWAzv_qchK2Xt6davT9jxCnJXTG0CHnMPZnmZrWH3G0EzhF-g9oRbgOdj2otczongo4pfx4WzYtvp-kZa39zsFrgSAj6LYSsmFD2CRaPMnTLxtOUg0sKvrOa45vSNBt74wkH1d285dlNncqx0uQdEGUvMjRAHbA",
                    descricao: "Entenda quais ativos performam melhor quando o poder de compra diminui e descubra estratégias fundamentais para manter a rentabilidade real dos seus investimentos no longo prazo.",
                    conteudo: "A inflação é um dos maiores desafios para investidores...",
                    autor: "Carlos Mendes",
                    cargoAutor: "Analista Chefe",
                    avatarAutor: "https://lh3.googleusercontent.com/aida-public/AB6AXuAG5qunG-npeGTjIiO1HjH_cGXnR8tky9tmuLh4Runh4YCm2vZQ1w6weiks2nWSXy4UmcZ3RIBElOfdekqgrc-fswIRA1WDwc1w8I54ZH3m63RipQFRo6KZA3lnkDELxv8K4EBPKeC42dbzQ1JjH5yTT2yADQEPrbvSIGuoNjECMP-hraXMdg4pIEbGgGfWQbet_IOud2UJQ4R7Pyq-ea83hBZD46hdOV5jLFGc55DKHcGSIt8dPmsg1fIR1nZVYsQU43uR5P7ZNcI",
                    dataPublicacao: "2023-10-12",
                    destaque: true
                },
                {
                    id: 2,
                    titulo: "Small Caps: Oportunidade ou Risco Desnecessário?",
                    categoria: "Renda Variável",
                    tempoLeitura: 5,
                    imagemUrl: "https://lh3.googleusercontent.com/aida-public/AB6AXuD2_jHPFYnGR-02R_x8s3qCCPiuNSDog19KPqBdGT2XBjupDBCoxI8RHC7YK9YqF8vBVLQo8pnKPoGdW6P2poInpaP7fp226F7X0o7rN4EHzx9PnjYP6N6h7H3RrRN7tfrZRVfLZPfawlFQIJrGZeYnp0l0HgkBi7Lo_z0WJpp90zsPHYsDFawCQdPjvHDFRpi483eyz9Be4wq6byF2jgobFJUkbgZdsnGtnlbeAJ4_KtaYBbhZhcAY5ByWrvqw_Qq2DykakyXir0g",
                    descricao: "Analisamos o cenário atual das empresas de menor capitalização na bolsa brasileira e o que esperar para o próximo semestre.",
                    conteudo: "As small caps representam uma oportunidade interessante...",
                    autor: "Alex Morgan",
                    cargoAutor: "Analista de Mercado",
                    avatarAutor: "https://lh3.googleusercontent.com/aida-public/AB6AXuC6CfMpHuKCMW0lQ4KbrP3vzrf1IuMhyl4hcbnY8GVofVgE7PwXOIdOEddwfHgzJUlYCYPkpHdNwsquy3DCRixcH4ND9q2L6IL9LQ6Kfc030scFBBvvDemSPbcGrewYiPL-hPoYLiMiMfBI0wE3rSBlRzbRimN4wYH36UrxEyevg5xgabd30PAcUqNDtU6Jde2wq9c88kAYT1aY0mlj6kQzPZ_Obm3rsUKc62duCCI3obWovyCfSxF7DSxYn2hE_hXUCcqVm_A_iIQ",
                    dataPublicacao: "2023-10-12",
                    destaque: false
                },
                {
                    id: 3,
                    titulo: "Investimento em Dividendos: É Adequado para Você?",
                    categoria: "Renda Variável",
                    tempoLeitura: 12,
                    imagemUrl: "https://lh3.googleusercontent.com/aida-public/AB6AXuCyUVg29yjeSAkdhhVZeX22LbnhQuC5wp_Umzky8wbIU9spjLrQ6z8aF1JjwElExGtXRAhGXSoXxh2SbtS_G73rlZM2vFxp0XDCHBycbgNWOM-Glfeqp2wgHhIhOdGcqlUcNQGlqrdVDillQjh0iDsuo-SDZcpswSw2vJAgfrk4QyWULvS94pDdsXWjhdgmlINhVojk2l3os4mDf3p393tjU-fGFDjHXKCmPmNQlI9gAm6uWcWs9I9a4TObwDQRuuj2MyWwZWU-Oxg",
                    descricao: "Descubra se a estratégia de investimento focada em dividendos se alinha com seus objetivos financeiros e perfil de investidor. Uma análise completa sobre os prós, contras e quando faz sentido investir em ações pagadoras de dividendos.",
                    conteudo: `<p class="lead text-xl text-gray-700 dark:text-gray-300">
                Investir em ações que pagam dividendos é uma das estratégias mais populares entre investidores que buscam geração de renda passiva. Mas será que essa abordagem é realmente adequada para o seu perfil e objetivos financeiros?
            </p>
            
            <p>
                Muitos investidores são atraídos pela promessa de receber pagamentos regulares de dividendos, imaginando que isso garantirá uma fonte de renda estável. No entanto, como toda estratégia de investimento, o investimento em dividendos tem suas vantagens, desvantagens e momentos mais adequados para ser implementado.
            </p>
            
            <div class="my-10 rounded-lg border-l-4 border-primary bg-surface-light dark:bg-surface-dark p-6 shadow-sm border border-gray-200 dark:border-gray-800">
                <h3 class="mb-3 flex items-center gap-2 text-lg font-display font-bold text-gray-900 dark:text-white mt-0">
                    <span class="material-icons text-primary">lightbulb</span>
                    Principais Insights
                </h3>
                <ul class="space-y-2 text-base text-gray-700 dark:text-gray-300 mb-0 list-none pl-0">
                    <li class="flex gap-2">
                        <span class="text-primary">•</span>
                        <span>Dividendos podem fornecer renda passiva, mas não são garantidos e podem ser cortados a qualquer momento.</span>
                    </li>
                    <li class="flex gap-2">
                        <span class="text-primary">•</span>
                        <span>Empresas que pagam dividendos consistentes geralmente são mais maduras e estáveis, mas podem ter menor potencial de crescimento.</span>
                    </li>
                    <li class="flex gap-2">
                        <span class="text-primary">•</span>
                        <span>A estratégia é mais adequada para investidores que buscam renda atual e têm horizonte de investimento de longo prazo.</span>
                    </li>
                    <li class="flex gap-2">
                        <span class="text-primary">•</span>
                        <span>É fundamental diversificar e não focar apenas no yield (rendimento), mas também na qualidade da empresa.</span>
                    </li>
                </ul>
            </div>
            
            <h2>O Que São Dividendos e Como Funcionam</h2>
            
            <p>
                Dividendos são uma parcela dos lucros de uma empresa que é distribuída aos acionistas. Quando uma empresa tem lucro, ela pode optar por reinvestir esse dinheiro no negócio (para crescimento) ou distribuir uma parte aos acionistas na forma de dividendos.
            </p>
            
            <p>
                No Brasil, as empresas geralmente pagam dividendos trimestralmente ou anualmente. O valor pago é proporcional à quantidade de ações que você possui. Por exemplo, se uma empresa paga R$ 0,50 por ação e você possui 100 ações, você receberá R$ 50,00 em dividendos.
            </p>
            
            <h2>Vantagens do Investimento em Dividendos</h2>
            
            <h3>1. Renda Passiva Regular</h3>
            <p>
                Uma das maiores vantagens é a possibilidade de gerar renda passiva. Se você construir uma carteira diversificada de ações pagadoras de dividendos, pode receber pagamentos regulares sem precisar vender suas ações.
            </p>
            
            <h3>2. Empresas Estabelecidas e Estáveis</h3>
            <p>
                Empresas que pagam dividendos consistentes geralmente são empresas maduras, com modelos de negócio estabelecidos e fluxo de caixa estável. Isso pode significar menor volatilidade e mais previsibilidade.
            </p>
            
            <h3>3. Reinvestimento Composto</h3>
            <p>
                Você pode usar os dividendos recebidos para comprar mais ações, criando um efeito de composição. Com o tempo, isso pode acelerar significativamente o crescimento do seu patrimônio.
            </p>
            
            <h3>4. Proteção Parcial Contra Inflação</h3>
            <p>
                Empresas sólidas geralmente aumentam seus dividendos ao longo do tempo, o que pode ajudar a proteger seu poder de compra contra a inflação, especialmente quando comparado a investimentos de renda fixa com taxas fixas.
            </p>
            
            <h2>Desvantagens e Riscos</h2>
            
            <h3>1. Dividendos Não São Garantidos</h3>
            <p>
                Ao contrário dos juros de títulos de renda fixa, os dividendos não são garantidos. Uma empresa pode cortar ou suspender os pagamentos a qualquer momento, especialmente em períodos de crise ou quando precisa de capital para investimentos.
            </p>
            
            <h3>2. Potencial de Crescimento Limitado</h3>
            <p>
                Empresas que pagam dividendos altos podem ter menos capital disponível para reinvestir e crescer. Isso pode resultar em menor valorização do preço das ações comparado a empresas de crescimento que reinvestem todos os lucros.
            </p>
            
            <h3>3. Imposto de Renda</h3>
            <p>
                No Brasil, os dividendos são isentos de Imposto de Renda para pessoa física desde 1995. No entanto, se você investir em ações de empresas estrangeiras, pode haver tributação dependendo do país.
            </p>
            
            <h3>4. Foco Excessivo no Yield</h3>
            <p>
                Muitos investidores se deixam seduzir por yields (rendimentos) muito altos, mas isso pode ser um sinal de alerta. Um yield anormalmente alto pode indicar que a empresa está em dificuldades ou que o preço da ação caiu significativamente.
            </p>
            
            <h2>Quando o Investimento em Dividendos Faz Sentido</h2>
            
            <p>
                A estratégia de dividendos é mais adequada para investidores que:
            </p>
            
            <ul>
                <li><strong>Buscam renda passiva:</strong> Se você precisa ou deseja uma fonte de renda regular sem vender seus ativos.</li>
                <li><strong>Têm horizonte de longo prazo:</strong> Para aproveitar o efeito composto e a estabilidade das empresas pagadoras de dividendos.</li>
                <li><strong>Valorizam estabilidade:</strong> Preferem empresas estabelecidas com menor volatilidade.</li>
                <li><strong>Estão próximos ou na aposentadoria:</strong> Quando a geração de renda se torna mais importante que o crescimento do capital.</li>
            </ul>
            
            <h2>Como Construir uma Carteira de Dividendos</h2>
            
            <h3>1. Diversificação é Fundamental</h3>
            <p>
                Não coloque todos os ovos na mesma cesta. Diversifique entre diferentes setores (bancos, energia, consumo, utilities) e empresas de diferentes tamanhos. Uma carteira bem diversificada reduz o risco de ter todos os dividendos cortados simultaneamente.
            </p>
            
            <h3>2. Foque na Qualidade, Não Apenas no Yield</h3>
            <p>
                Uma empresa com yield de 8% que corta dividendos no próximo ano é pior que uma empresa com yield de 4% que aumenta os dividendos consistentemente há 10 anos. Analise:
            </p>
            
            <ul>
                <li>Histórico de pagamento de dividendos</li>
                <li>Crescimento dos dividendos ao longo do tempo</li>
                <li>Payout ratio (percentual do lucro distribuído)</li>
                <li>Saúde financeira da empresa</li>
                <li>Setor e perspectivas de crescimento</li>
            </ul>
            
            <h3>3. Considere Fundos Imobiliários (FIIs)</h3>
            <p>
                Os Fundos Imobiliários são uma excelente alternativa para quem busca dividendos. Eles são obrigados por lei a distribuir pelo menos 95% dos lucros aos cotistas, geralmente mensalmente. Além disso, os rendimentos são isentos de Imposto de Renda para pessoa física.
            </p>
            
            <h3>4. Reinvesta os Dividendos</h3>
            <p>
                Se você não precisa da renda imediatamente, considere reinvestir os dividendos recebidos. Isso acelera o crescimento da sua carteira através do efeito composto. Com o tempo, você receberá dividendos sobre os dividendos reinvestidos.
            </p>
            
            <h2>Métricas Importantes para Avaliar</h2>
            
            <div class="my-8 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-800">
                <table class="w-full text-left text-sm my-0">
                    <thead class="bg-surface-light dark:bg-surface-dark text-gray-900 dark:text-white">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Métrica</th>
                            <th class="px-4 py-3 font-semibold">O Que Significa</th>
                            <th class="px-4 py-3 font-semibold">Faixa Ideal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800 bg-white dark:bg-background-dark">
                        <tr>
                            <td class="px-4 py-3 font-medium text-primary">Dividend Yield</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">Percentual do preço da ação pago em dividendos</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">3% a 6% (depende do setor)</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-medium text-primary">Payout Ratio</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">Percentual do lucro distribuído</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">40% a 60% (permite reinvestimento)</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-medium text-primary">CAGR de Dividendos</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">Crescimento anual composto dos dividendos</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">Acima da inflação</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-medium text-primary">Anos Consecutivos</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">Anos seguidos pagando dividendos</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">5+ anos (consistência)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <h2>Estratégias de Investimento em Dividendos</h2>
            
            <h3>Estratégia 1: Dividendos Crescentes</h3>
            <p>
                Foque em empresas que não apenas pagam dividendos, mas que aumentam os pagamentos regularmente. Essas empresas geralmente têm modelos de negócio resilientes e capacidade de crescer mesmo em cenários adversos.
            </p>
            
            <h3>Estratégia 2: High Yield</h3>
            <p>
                Busque empresas com yields altos, mas sempre analisando a sustentabilidade. Yields muito altos (acima de 10%) geralmente são sinais de alerta e podem indicar problemas na empresa.
            </p>
            
            <h3>Estratégia 3: Dividendos Aristocratas</h3>
            <p>
                No Brasil, não temos uma lista oficial como nos EUA, mas você pode criar sua própria lista de "aristocratas" - empresas que pagam dividendos há muitos anos consecutivos e aumentam os valores regularmente.
            </p>
            
            <h2>Conclusão: É Adequado para Você?</h2>
            
            <blockquote>
                "Investir em dividendos não é uma estratégia única que serve para todos. É uma ferramenta poderosa quando usada corretamente, mas pode ser inadequada se não se alinhar com seus objetivos e perfil de risco."
            </blockquote>
            
            <p>
                O investimento em dividendos pode ser uma excelente estratégia se você:
            </p>
            
            <ul>
                <li>Busca renda passiva e tem paciência para o longo prazo</li>
                <li>Valoriza estabilidade e previsibilidade</li>
                <li>Está disposto a fazer análise fundamental das empresas</li>
                <li>Entende que dividendos não são garantidos</li>
                <li>Pode diversificar adequadamente sua carteira</li>
            </ul>
            
            <p>
                Por outro lado, pode não ser adequado se você:
            </p>
            
            <ul>
                <li>Busca crescimento agressivo de capital no curto prazo</li>
                <li>Prefere empresas de tecnologia e crescimento rápido</li>
                <li>Não tem tempo ou interesse em analisar empresas</li>
                <li>Precisa de liquidez imediata</li>
                <li>Está começando a investir e tem pouco capital (diversificação limitada)</li>
            </ul>
            
            <p>
                Lembre-se: a melhor estratégia é aquela que você entende, acredita e consegue executar consistentemente. Se investir em dividendos ressoa com seus objetivos e perfil, pode ser uma excelente forma de construir patrimônio e gerar renda passiva ao longo do tempo.
            </p>
            
            <p>
                Como sempre, recomendamos diversificar sua estratégia. Você não precisa escolher entre crescimento e dividendos - pode ter ambos na sua carteira, ajustando as proporções conforme seus objetivos e fase da vida.
            </p>`,
                    autor: "Sarah Jenks",
                    cargoAutor: "Analista Sênior de Renda Variável",
                    avatarAutor: "https://lh3.googleusercontent.com/aida-public/AB6AXuDW24TXEJ4pboGVAM8oirvVs1bvBRDaZ166230v_hZ5VZRuwpuiiBD0eFvQM7pT_kdYUPryAjbrEo3rtqjiOszn1AoLOgSbH6DuBnKpzqN5lFlNpg2Kp1Q-Bzha8uevoHMuIULSLHk3X8ZVgWLe9rk4fg4n17EMBHCIXwQjwZy97k8NwKlGXvu08Eb-SYZY_EwM-N3Z5lHpKGN2A58DB-HU__VGRiP0R_Cy8XubRxOkySsZ7oiQoCfsN_BH0lorNtAgTCGvQA7wa4g",
                    dataPublicacao: "2023-10-15",
                    destaque: false
                }
            ];
            salvarArtigos();
            renderizarArtigos();
        }

        function abrirModalNovo() {
            artigoEditando = null;
            document.getElementById('modalTitulo').textContent = 'Novo Artigo';
            document.getElementById('formArtigo').reset();
            document.getElementById('dataPublicacao').valueAsDate = new Date();
            document.getElementById('modalArtigo').classList.remove('hidden');
        }

        function abrirModalEditar(id) {
            const artigo = artigos.find(a => a.id == id);
            if (!artigo) return;

            artigoEditando = artigo;
            document.getElementById('modalTitulo').textContent = 'Editar Artigo';
            document.getElementById('titulo').value = artigo.titulo;
            document.getElementById('categoria').value = artigo.categoria;
            document.getElementById('tempoLeitura').value = artigo.tempo_leitura || artigo.tempoLeitura;
            document.getElementById('imagemUrl').value = artigo.imagem_url || artigo.imagemUrl;
            document.getElementById('descricao').value = artigo.descricao;
            document.getElementById('conteudo').value = artigo.conteudo;
            document.getElementById('autor').value = artigo.autor;
            document.getElementById('cargoAutor').value = artigo.cargo_autor || artigo.cargoAutor;
            document.getElementById('avatarAutor').value = artigo.avatar_autor || artigo.avatarAutor;
            document.getElementById('dataPublicacao').value = artigo.data_publicacao || artigo.dataPublicacao;
            document.getElementById('destaque').checked = artigo.destaque || false;
            document.getElementById('modalArtigo').classList.remove('hidden');
        }

        function fecharModal() {
            document.getElementById('modalArtigo').classList.add('hidden');
            artigoEditando = null;
        }

        async function salvarArtigo(e) {
            e.preventDefault();

            const submitBtn = e.target.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Salvando...';

            const dados = {
                id: artigoEditando ? artigoEditando.id : null,
                titulo: document.getElementById('titulo').value,
                categoria: document.getElementById('categoria').value,
                tempoLeitura: parseInt(document.getElementById('tempoLeitura').value),
                imagemUrl: document.getElementById('imagemUrl').value,
                descricao: document.getElementById('descricao').value,
                conteudo: document.getElementById('conteudo').value,
                autor: document.getElementById('autor').value,
                cargoAutor: document.getElementById('cargoAutor').value,
                avatarAutor: document.getElementById('avatarAutor').value,
                dataPublicacao: document.getElementById('dataPublicacao').value,
                destaque: document.getElementById('destaque').checked
            };

            try {
                const response = await fetch('api/save_article.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(dados)
                });
                const result = await response.json();

                if (result.success) {
                    alert('Artigo salvo com sucesso!');
                    if (result.newsletter && !dados.id) {
                        console.log('Newsletter triggered:', result.newsletter);
                    }
                    buscarArtigosSupabase();
                    fecharModal();
                } else {
                    alert('Erro ao salvar: ' + (result.error || 'Erro desconhecido'));
                }
            } catch (error) {
                alert('Erro na conexão com o servidor.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Salvar Artigo';
            }
        }

        function salvarArtigos() {
            // No longer used, replaced by save_article.php
        }

        function renderizarArtigos(artigosFiltrados = null) {
            const lista = document.getElementById('listaArtigos');
            const template = document.getElementById('templateArtigo');
            const artigosParaRenderizar = artigosFiltrados || artigos;

            lista.innerHTML = '';

            if (artigosParaRenderizar.length === 0) {
                lista.innerHTML = '<div class="text-center py-12 text-gray-500 dark:text-gray-400">Nenhum artigo encontrado.</div>';
                return;
            }

            artigosParaRenderizar.forEach(artigo => {
                const card = template.content.cloneNode(true);

                card.querySelector('.artigo-imagem').src = artigo.imagem_url || artigo.imagemUrl;
                card.querySelector('.artigo-imagem').alt = artigo.titulo;
                card.querySelectorAll('.artigo-categoria').forEach(el => el.textContent = artigo.categoria);
                card.querySelector('.artigo-tempo-leitura').textContent = `${artigo.tempo_leitura || artigo.tempoLeitura} min de leitura`;
                card.querySelector('.artigo-titulo').textContent = artigo.titulo;
                card.querySelector('.artigo-descricao').textContent = artigo.descricao;
                card.querySelector('.artigo-avatar').src = artigo.avatar_autor || artigo.avatarAutor;
                card.querySelector('.artigo-autor').textContent = artigo.autor;
                card.querySelector('.artigo-data').textContent = formatarData(artigo.data_publicacao || artigo.dataPublicacao);

                card.querySelector('.btn-visualizar').addEventListener('click', () => {
                    window.open(`artigo.php?id=${artigo.id}`, '_blank');
                });

                card.querySelector('.btn-editar').addEventListener('click', () => {
                    abrirModalEditar(artigo.id);
                });

                card.querySelector('.btn-deletar').addEventListener('click', () => {
                    abrirModalConfirmacao(artigo.id);
                });

                lista.appendChild(card);
            });
        }

        function filtrarArtigos() {
            const busca = document.getElementById('buscarArtigo').value.toLowerCase();
            const categoria = document.getElementById('filtroCategoria').value;
            const ordenar = document.getElementById('ordenarPor').value;

            let artigosFiltrados = artigos.filter(artigo => {
                const matchBusca = !busca || artigo.titulo.toLowerCase().includes(busca) ||
                    artigo.descricao.toLowerCase().includes(busca) ||
                    artigo.autor.toLowerCase().includes(busca);
                const matchCategoria = !categoria || artigo.categoria === categoria;
                return matchBusca && matchCategoria;
            });

            // Ordenar
            if (ordenar === 'recente') {
                artigosFiltrados.sort((a, b) => new Date(b.dataPublicacao) - new Date(a.dataPublicacao));
            } else if (ordenar === 'antigo') {
                artigosFiltrados.sort((a, b) => new Date(a.dataPublicacao) - new Date(b.dataPublicacao));
            } else if (ordenar === 'titulo') {
                artigosFiltrados.sort((a, b) => a.titulo.localeCompare(b.titulo));
            }

            renderizarArtigos(artigosFiltrados);
        }

        function formatarData(data) {
            const date = new Date(data);
            const meses = ['JAN', 'FEV', 'MAR', 'ABR', 'MAI', 'JUN', 'JUL', 'AGO', 'SET', 'OUT', 'NOV', 'DEZ'];
            return `${date.getDate()} ${meses[date.getMonth()]}, ${date.getFullYear()}`;
        }

        let artigoParaDeletar = null;

        function abrirModalConfirmacao(id) {
            artigoParaDeletar = id;
            document.getElementById('modalConfirmacao').classList.remove('hidden');
        }

        function fecharModalConfirmacao() {
            document.getElementById('modalConfirmacao').classList.add('hidden');
            artigoParaDeletar = null;
        }

        document.getElementById('btnConfirmarExclusao').addEventListener('click', async function () {
            if (artigoParaDeletar) {
                try {
                    const response = await fetch(`api/delete_article.php?id=${artigoParaDeletar}`);
                    const result = await response.json();
                    if (result.success) {
                        buscarArtigosSupabase();
                        fecharModalConfirmacao();
                    } else {
                        alert('Erro ao excluir artigo.');
                    }
                } catch (error) {
                    alert('Erro na conexão.');
                }
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