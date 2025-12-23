<?php
session_start();

// Verificar se está logado
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
  header('Location: login.php');
  exit;
}

require_once 'config.php';

// Verificar se é uma ação de formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['action'])) {
    switch ($_POST['action']) {
      case 'add_article':
        addArticle($_POST);
        break;
      case 'edit_article':
        editArticle($_POST);
        break;
      case 'delete_article':
        deleteArticle($_POST['article_id']);
        break;
    }
  }
}

// Verificar se há artigo para editar
$editing_article = null;
if (isset($_GET['edit'])) {
  $edit_id = (int) $_GET['edit'];
  foreach ($articles as $article) {
    if ($article['id'] == $edit_id) {
      $editing_article = $article;
      break;
    }
  }
}

// Função para adicionar artigo
function addArticle($data)
{
  global $articles;

  $newArticle = [
    'id' => getNextArticleId(),
    'title' => $data['title'],
    'excerpt' => $data['excerpt'],
    'content' => $data['content'],
    'date' => date('Y-m-d'),
    'author' => $data['author'],
    'category' => $data['category'],
    'category_slug' => $data['category_slug'],
    'read_time' => calculateReadTime($data['content']),
    'featured' => isset($data['featured']) ? true : false
  ];

  $articles[] = $newArticle;
  saveArticlesToFile($articles);

  header('Location: admin.php?success=1');
  exit;
}

// Função para editar artigo
function editArticle($data)
{
  global $articles;

  $articleId = $data['article_id'];
  foreach ($articles as &$article) {
    if ($article['id'] == $articleId) {
      $article['title'] = $data['title'];
      $article['excerpt'] = $data['excerpt'];
      $article['content'] = $data['content'];
      $article['author'] = $data['author'];
      $article['category'] = $data['category'];
      $article['category_slug'] = $data['category_slug'];
      $article['read_time'] = calculateReadTime($data['content']);
      $article['featured'] = isset($data['featured']) ? true : false;
      break;
    }
  }

  saveArticlesToFile($articles);
  header('Location: admin.php?success=2');
  exit;
}

// Função para excluir artigo
function deleteArticle($articleId)
{
  global $articles;

  $articles = array_filter($articles, function ($article) use ($articleId) {
    return $article['id'] != $articleId;
  });

  saveArticlesToFile($articles);
  header('Location: admin.php?success=3');
  exit;
}

// Função auxiliar para obter próximo ID
function getNextArticleId()
{
  global $articles;
  $maxId = 0;
  foreach ($articles as $article) {
    if ($article['id'] > $maxId) {
      $maxId = $article['id'];
    }
  }
  return $maxId + 1;
}

// Função para calcular tempo de leitura
function calculateReadTime($content)
{
  $wordCount = str_word_count(strip_tags($content));
  $readTime = ceil($wordCount / 200); // 200 palavras por minuto
  return $readTime . ' min';
}

// Função para salvar artigos em arquivo
function saveArticlesToFile($articles)
{
  $configContent = file_get_contents('config.php');

  // Encontrar e substituir o array de artigos
  $startPattern = '/\$articles = \[/';
  $endPattern = '/\];/';

  $startPos = preg_match($startPattern, $configContent, $matches, PREG_OFFSET_CAPTURE);
  if ($startPos) {
    $startOffset = $matches[0][1];

    // Encontrar o final do array
    $remaining = substr($configContent, $startOffset);
    $bracketCount = 0;
    $endOffset = 0;

    for ($i = 0; $i < strlen($remaining); $i++) {
      if ($remaining[$i] === '[') {
        $bracketCount++;
      } elseif ($remaining[$i] === ']') {
        $bracketCount--;
        if ($bracketCount === 0) {
          $endOffset = $i;
          break;
        }
      }
    }

    // Gerar novo array de artigos
    $newArticlesArray = generateArticlesArray($articles);

    // Substituir no arquivo
    $before = substr($configContent, 0, $startOffset);
    $after = substr($configContent, $startOffset + $endOffset + 1);

    $newContent = $before . $newArticlesArray . $after;
    file_put_contents('config.php', $newContent);
  }
}

// Função para gerar array de artigos em PHP
function generateArticlesArray($articles)
{
  $output = '$articles = [' . "\n";

  foreach ($articles as $article) {
    $output .= "    [\n";
    $output .= "        'id' => " . (int) $article['id'] . ",\n";
    $output .= "        'title' => '" . addslashes($article['title']) . "',\n";
    // Armazenar campos potencialmente multilinha como base64 e decodificar em tempo de execução
    $excerpt_b64 = base64_encode($article['excerpt']);
    $content_b64 = base64_encode($article['content']);
    $output .= "        'excerpt' => base64_decode('" . $excerpt_b64 . "'),\n";
    $output .= "        'content' => base64_decode('" . $content_b64 . "'),\n";
    $output .= "        'date' => '" . addslashes($article['date']) . "',\n";
    $output .= "        'author' => '" . addslashes($article['author']) . "',\n";
    $output .= "        'category' => '" . addslashes($article['category']) . "',\n";
    $output .= "        'category_slug' => '" . addslashes($article['category_slug']) . "',\n";
    $output .= "        'read_time' => '" . addslashes($article['read_time']) . "',\n";
    $output .= "        'featured' => " . ($article['featured'] ? 'true' : 'false') . "\n";
    $output .= "    ],\n";
  }

  $output .= "];\n";
  return $output;
}

$page_title = "Administração | Stone Edger - Gerenciar Artigos";
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $page_title; ?></title>
  <link rel="stylesheet" href="style.css" />
  <!-- Font Awesome Cdn Link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
  <style>
    /* Estilos específicos da administração */
    #showcase {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100vh;
      overflow: hidden;
    }

    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100vh;
      overflow: hidden;
    }

    .container {
      display: flex;
      height: 100vh;
    }

    nav {
      position: fixed;
      left: 0;
      top: 0;
      height: 100vh;
      width: 120px;
      z-index: 1000;
    }

    .line {
      position: fixed;
      left: 120px;
      top: 0;
      height: 100vh;
      z-index: 999;
    }

    .admin-container {
      margin-left: 140px;
      padding: 20px;
      overflow-y: auto;
      height: 100vh;
      width: calc(100% - 140px);
    }

    .admin-header {
      text-align: center;
      margin-bottom: 3rem;
    }

    .admin-title {
      font-size: 3rem;
      color: #fff;
      margin-bottom: 1rem;
    }

    .admin-subtitle {
      color: #999;
      font-size: 1.2rem;
    }

    .admin-tabs {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin-bottom: 2rem;
      flex-wrap: wrap;
    }

    .admin-tab {
      background: rgba(255, 255, 255, 0.1);
      color: #fff;
      padding: 0.8rem 1.5rem;
      border-radius: 25px;
      text-decoration: none;
      font-size: 0.9rem;
      font-weight: 500;
      transition: all 0.3s ease;
      border: 1px solid rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(10px);
      cursor: pointer;
    }

    .admin-tab:hover,
    .admin-tab.active {
      background: rgb(251, 186, 0);
      color: #000;
      border-color: rgb(251, 186, 0);
      text-decoration: none;
    }

    .admin-content {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 10px;
      padding: 2rem;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-group label {
      display: block;
      color: #fff;
      margin-bottom: 0.5rem;
      font-weight: 500;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
      width: 100%;
      padding: 0.8rem;
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 5px;
      background: rgba(255, 255, 255, 0.1);
      color: #fff;
      font-size: 0.9rem;
      backdrop-filter: blur(10px);
      outline: none;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
      border-color: rgb(251, 186, 0);
    }

    .form-group textarea {
      min-height: 120px;
      resize: vertical;
    }

    .form-group textarea[name="content"] {
      min-height: 300px;
    }

    .form-group input[type="checkbox"] {
      width: auto;
      margin-right: 0.5rem;
    }

    .btn {
      background: rgb(251, 186, 0);
      color: #000;
      padding: 0.8rem 1.5rem;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s ease;
      margin-right: 1rem;
    }

    .btn:hover {
      background: rgba(251, 186, 0, 0.8);
    }

    .btn-secondary {
      background: rgba(255, 255, 255, 0.2);
      color: #fff;
    }

    .btn-secondary:hover {
      background: rgba(255, 255, 255, 0.3);
    }

    .btn-danger {
      background: #f44336;
      color: #fff;
    }

    .btn-danger:hover {
      background: #d32f2f;
    }

    .articles-list {
      margin-top: 2rem;
    }

    .article-item {
      background: rgba(255, 255, 255, 0.05);
      border-radius: 8px;
      padding: 1rem;
      margin-bottom: 1rem;
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .article-item h3 {
      color: #fff;
      margin-bottom: 0.5rem;
    }

    .article-meta {
      color: #999;
      font-size: 0.8rem;
      margin-bottom: 0.5rem;
    }

    .article-actions {
      margin-top: 1rem;
    }

    .success-message {
      background: rgba(76, 175, 80, 0.2);
      border: 1px solid #4CAF50;
      color: #4CAF50;
      padding: 1rem;
      border-radius: 5px;
      margin-bottom: 2rem;
      text-align: center;
    }

    .hidden {
      display: none;
    }
  </style>
</head>

<body>
  <header id="showcase">
    <div class="overlay">
      <div class="container">
        <nav>
          <div class="logo">
            <img src="./img/logo.jpg" alt="Stone Edger Logo">
          </div>
          <ul class="navbar">
            <?php
            $current_path = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
            $links = [
              'Inicio' => 'index.php',
              'Blog' => 'blog.php',
              'Admin' => 'admin.php'
            ];
            foreach ($links as $text => $url):
              $link_path = basename(parse_url($url, PHP_URL_PATH));
              $isActive = ($link_path === $current_path) ? 'active' : '';
              ?>
              <li class="<?php echo $isActive; ?>"><a href="<?php echo $url; ?>"><?php echo $text; ?></a></li>
            <?php endforeach; ?>
          </ul>
          <ul class="icons">
            <li><a href="https://facebook.com/stoneedger" target="_blank" rel="noopener"><i
                  class="fab fa-facebook"></i></a></li>
            <li><a href="https://youtube.com/stoneedger" target="_blank" rel="noopener"><i
                  class="fab fa-youtube"></i></a></li>
            <li><a href="https://instagram.com/stoneedger" target="_blank" rel="noopener"><i
                  class="fab fa-instagram"></i></a></li>
          </ul>
        </nav>

        <div class="line"></div>

        <!-- Admin Content -->
        <div class="admin-container">
          <div class="admin-header">
            <h1 class="admin-title">Administração <span style="color: rgb(251, 186, 0);">Stone Edger</span></h1>
            <p class="admin-subtitle">Gerencie os artigos do seu blog financeiro</p>
          </div>

          <?php if (isset($_GET['success'])): ?>
            <div class="success-message">
              <?php
              switch ($_GET['success']) {
                case '1':
                  echo 'Artigo adicionado com sucesso!';
                  break;
                case '2':
                  echo 'Artigo editado com sucesso!';
                  break;
                case '3':
                  echo 'Artigo excluído com sucesso!';
                  break;
              }
              ?>
            </div>
          <?php endif; ?>

          <div class="admin-tabs">
            <button class="admin-tab <?php echo !$editing_article ? 'active' : ''; ?>"
              onclick="showTab('add')"><?php echo $editing_article ? 'Editar Artigo' : 'Adicionar Artigo'; ?></button>
            <button class="admin-tab <?php echo $editing_article ? 'active' : ''; ?>" onclick="showTab('list')">Listar
              Artigos</button>
            <a href="logout.php" class="admin-tab" style="background: #f44336; color: #fff;">
              <i class="fas fa-sign-out-alt"></i> Sair
            </a>
          </div>

          <!-- Formulário para Adicionar/Editar Artigo -->
          <div id="add-tab" class="admin-content">
            <h2 style="color: #fff; margin-bottom: 1.5rem;">
              <?php echo $editing_article ? 'Editar Artigo' : 'Adicionar Novo Artigo'; ?></h2>
            <?php if ($editing_article): ?>
              <div
                style="background: rgba(251, 186, 0, 0.1); border: 1px solid rgb(251, 186, 0); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <strong style="color: rgb(251, 186, 0);">✏️ Modo de Edição:</strong> Você está editando o artigo ID
                <?php echo $editing_article['id']; ?>
              </div>
            <?php endif; ?>

            <form method="POST" action="admin.php">
              <input type="hidden" name="action"
                value="<?php echo $editing_article ? 'edit_article' : 'add_article'; ?>">
              <?php if ($editing_article): ?>
                <input type="hidden" name="article_id" value="<?php echo $editing_article['id']; ?>">
              <?php endif; ?>

              <div class="form-group">
                <label for="title">Título do Artigo</label>
                <input type="text" id="title" name="title"
                  value="<?php echo $editing_article ? htmlspecialchars($editing_article['title']) : ''; ?>" required>
              </div>

              <div class="form-group">
                <label for="excerpt">Resumo do Artigo</label>
                <textarea id="excerpt" name="excerpt"
                  required><?php echo $editing_article ? htmlspecialchars($editing_article['excerpt']) : ''; ?></textarea>
              </div>

              <div class="form-group">
                <label for="content">Conteúdo Completo (HTML permitido)</label>
                <textarea id="content" name="content"
                  required><?php echo $editing_article ? ($editing_article['content'] ?? '') : ''; ?></textarea>
                <small style="color: #999; font-size: 0.8rem; margin-top: 0.5rem; display: block;">
                  Você pode usar HTML para formatar o conteúdo, incluindo imagens, tabelas, listas, etc.
                </small>
              </div>

              <div class="form-group">
                <label for="author">Autor</label>
                <input type="text" id="author" name="author"
                  value="<?php echo $editing_article ? htmlspecialchars($editing_article['author']) : 'Equipe Stone Edger'; ?>"
                  required>
              </div>

              <div class="form-group">
                <label for="category_slug">Categoria</label>
                <select id="category_slug" name="category_slug" required>
                  <option value="">Selecione uma categoria</option>
                  <?php foreach ($categories as $slug => $category): ?>
                    <option value="<?php echo $slug; ?>" <?php echo ($editing_article && $editing_article['category_slug'] === $slug) ? 'selected' : ''; ?>>
                      <?php echo $category['name']; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="form-group">
                <label for="category">Nome da Categoria (para exibição)</label>
                <input type="text" id="category" name="category"
                  value="<?php echo $editing_article ? htmlspecialchars($editing_article['category']) : ''; ?>"
                  required>
              </div>

              <div class="form-group">
                <label>
                  <input type="checkbox" name="featured" value="1" <?php echo ($editing_article && $editing_article['featured']) ? 'checked' : ''; ?>>
                  Artigo em Destaque
                </label>
              </div>

              <button type="submit"
                class="btn"><?php echo $editing_article ? 'Salvar Alterações' : 'Adicionar Artigo'; ?></button>
              <?php if ($editing_article): ?>
                <a href="admin.php" class="btn btn-secondary">Cancelar Edição</a>
              <?php endif; ?>
            </form>
          </div>

          <!-- Lista de Artigos -->
          <div id="list-tab" class="admin-content hidden">
            <h2 style="color: #fff; margin-bottom: 1.5rem;">Artigos Existentes</h2>
            <div class="articles-list">
              <?php foreach ($articles as $article): ?>
                <div class="article-item">
                  <h3><?php echo htmlspecialchars($article['title']); ?></h3>
                  <div class="article-meta">
                    <strong>Categoria:</strong> <?php echo htmlspecialchars($article['category']); ?> |
                    <strong>Autor:</strong> <?php echo htmlspecialchars($article['author']); ?> |
                    <strong>Data:</strong> <?php echo date('d/m/Y', strtotime($article['date'])); ?> |
                    <strong>Tempo de Leitura:</strong> <?php echo $article['read_time']; ?>
                    <?php if ($article['featured']): ?>
                      | <span style="color: rgb(251, 186, 0);">★ DESTAQUE</span>
                    <?php endif; ?>
                  </div>
                  <p style="color: #ccc; margin: 0.5rem 0;">
                    <?php echo htmlspecialchars(substr($article['excerpt'], 0, 150)) . '...'; ?></p>
                  <div class="article-actions">
                    <button class="btn btn-secondary" onclick="editArticle(<?php echo $article['id']; ?>)">Editar</button>
                    <button class="btn btn-danger" onclick="deleteArticle(<?php echo $article['id']; ?>)">Excluir</button>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Footer -->
  <footer style="position: fixed; bottom: 10px; right: 10px; color: #666; font-size: 12px;">
    © <?php echo $site_config['current_year']; ?> Stone Edger. Todos os direitos reservados.
  </footer>

  <script>
    function showTab(tabName) {
      // Esconder todas as abas
      document.querySelectorAll('.admin-content').forEach(tab => {
        tab.classList.add('hidden');
      });

      // Remover classe active de todos os botões
      document.querySelectorAll('.admin-tab').forEach(btn => {
        btn.classList.remove('active');
      });

      // Mostrar aba selecionada
      document.getElementById(tabName + '-tab').classList.remove('hidden');

      // Adicionar classe active ao botão clicado
      event.target.classList.add('active');
    }

    function editArticle(articleId) {
      // Redirecionar para página de edição
      window.location.href = 'admin.php?edit=' + articleId;
    }

    function deleteArticle(articleId) {
      if (confirm('Tem certeza que deseja excluir este artigo?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'admin.php';

        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'delete_article';

        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'article_id';
        idInput.value = articleId;

        form.appendChild(actionInput);
        form.appendChild(idInput);
        document.body.appendChild(form);
        form.submit();
      }
    }

    // Auto-preenchimento do nome da categoria
    document.getElementById('category_slug').addEventListener('change', function () {
      const categoryName = this.options[this.selectedIndex].text;
      document.getElementById('category').value = categoryName;
    });
  </script>

</body>

</html>