<?php
require_once 'config.php';

// Verificar se a categoria foi fornecida
$category_slug = isset($_GET['cat']) ? $_GET['cat'] : 'comece-investir';

// Verificar se a categoria existe
if (!isset($categories[$category_slug])) {
  $category_slug = 'comece-investir';
}

$category = $categories[$category_slug];
$category_articles = getArticlesByCategory($articles, $category_slug);
$article_count = countArticlesByCategory($articles, $category_slug);

// Configurações da página
$page_title = $category['name'] . " | Stone Edger - Blog Financeiro";
$page_description = $category['description'];
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="<?php echo $page_description; ?>" />
  <title><?php echo $page_title; ?></title>
  <link rel="stylesheet" href="style.css" />
  <!-- Font Awesome Cdn Link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
  <style>
    /* Estilos específicos da categoria */
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

    .category-container {
      margin-left: 140px;
      padding: 20px;
      overflow-y: auto;
      height: 100vh;
      width: calc(100% - 140px);
    }

    .category-header {
      text-align: center;
      margin-bottom: 3rem;
    }

    .category-title {
      font-size: 3rem;
      color: #fff;
      margin-bottom: 1rem;
    }

    .category-description {
      color: #999;
      font-size: 1.2rem;
      margin-bottom: 1rem;
    }

    .category-count {
      color:
        <?php echo $category['color']; ?>
      ;
      font-size: 1rem;
      font-weight: bold;
    }

    .breadcrumb {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 0.5rem;
      margin-bottom: 2rem;
      font-size: 0.9rem;
      color: #999;
    }

    .breadcrumb a {
      color: #fff;
      text-decoration: none;
    }

    .breadcrumb a:hover {
      color:
        <?php echo $category['color']; ?>
      ;
    }

    .breadcrumb .separator {
      color: #666;
    }

    .articles-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 2rem;
      margin-bottom: 3rem;
    }

    .article-card {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 10px;
      padding: 1.5rem;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      transition: transform 0.3s ease;
    }

    .article-card:hover {
      transform: translateY(-5px);
    }

    .article-card.featured {
      border: 2px solid
        <?php echo $category['color']; ?>
      ;
      background: rgba(<?php echo hex2rgb($category['color']); ?>, 0.1);
    }

    .article-meta {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
      font-size: 0.9rem;
      color: #999;
    }

    .article-category {
      background:
        <?php echo $category['color']; ?>
      ;
      color: #fff;
      padding: 0.3rem 0.8rem;
      border-radius: 15px;
      font-weight: bold;
      font-size: 0.8rem;
    }

    .article-title {
      font-size: 1.3rem;
      color: #fff;
      margin-bottom: 1rem;
      line-height: 1.4;
    }

    .article-excerpt {
      color: #ccc;
      line-height: 1.6;
      margin-bottom: 1rem;
    }

    .article-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .read-more {
      background:
        <?php echo $category['color']; ?>
      ;
      color: #fff;
      padding: 0.5rem 1rem;
      border-radius: 5px;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s ease;
    }

    .read-more:hover {
      background:
        <?php echo adjustBrightness($category['color'], -20); ?>
      ;
    }

    .read-time {
      color: #999;
      font-size: 0.9rem;
    }

    .no-articles {
      text-align: center;
      color: #999;
      font-size: 1.2rem;
      margin: 3rem 0;
    }

    .back-to-blog {
      text-align: center;
      margin-top: 2rem;
    }

    .back-to-blog a {
      background: rgba(255, 255, 255, 0.1);
      color: #fff;
      padding: 0.8rem 1.5rem;
      border-radius: 5px;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s ease;
    }

    .back-to-blog a:hover {
      background: rgba(255, 255, 255, 0.2);
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
            foreach ($nav_links as $text => $url):
              $link_path = basename(parse_url($url, PHP_URL_PATH));
              $isActive = ($link_path === $current_path) ? 'active' : '';
              ?>
              <li class="<?php echo $isActive; ?>"><a href="<?php echo $url; ?>"><?php echo $text; ?></a></li>
            <?php endforeach; ?>
          </ul>
          <ul class="icons">
            <?php foreach ($social_links as $platform => $url): ?>
              <li><a href="<?php echo $url; ?>" target="_blank" rel="noopener"><i
                    class="fab fa-<?php echo $platform; ?>"></i></a></li>
            <?php endforeach; ?>
          </ul>
        </nav>

        <div class="line"></div>

        <!-- Category Content -->
        <div class="category-container">
          <div class="category-header">
            <h1 class="category-title"><?php echo $category['name']; ?></h1>
            <p class="category-description"><?php echo $category['description']; ?></p>
            <p class="category-count"><?php echo $article_count; ?> artigo<?php echo $article_count != 1 ? 's' : ''; ?>
            </p>

            <!-- Breadcrumb -->
            <div class="breadcrumb">
              <a href="index.php">Início</a>
              <span class="separator">›</span>
              <a href="blog.php">Blog</a>
              <span class="separator">›</span>
              <span><?php echo $category['name']; ?></span>
            </div>
          </div>

          <?php if (empty($category_articles)): ?>
            <div class="no-articles">
              <i class="fas fa-file-alt" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
              <p>Nenhum artigo encontrado nesta categoria.</p>
              <p>Em breve teremos conteúdo exclusivo sobre <?php echo strtolower($category['name']); ?>!</p>
            </div>
          <?php else: ?>
            <div class="articles-grid">
              <?php foreach ($category_articles as $article): ?>
                <article class="article-card <?php echo $article['featured'] ? 'featured' : ''; ?>">
                  <div class="article-meta">
                    <span class="article-category"><?php echo $article['category']; ?></span>
                    <span><?php echo date('d/m/Y', strtotime($article['date'])); ?></span>
                  </div>

                  <h2 class="article-title"><?php echo $article['title']; ?></h2>
                  <p class="article-excerpt"><?php echo $article['excerpt']; ?></p>

                  <div class="article-footer">
                    <a href="artigo.php?id=<?php echo $article['id']; ?>" class="read-more">Ler Mais</a>
                    <span class="read-time"><?php echo $article['read_time']; ?> de leitura</span>
                  </div>
                </article>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <div class="back-to-blog">
            <a href="blog.php">← Voltar ao Blog</a>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Footer -->
  <footer style="position: fixed; bottom: 10px; right: 10px; color: #666; font-size: 12px;">
    © <?php echo $site_config['current_year']; ?> Stone Edger. Todos os direitos reservados.
  </footer>

</body>

</html>

<?php
// Função auxiliar para converter hex para RGB
function hex2rgb($hex)
{
  $hex = str_replace("#", "", $hex);
  $r = hexdec(substr($hex, 0, 2));
  $g = hexdec(substr($hex, 2, 2));
  $b = hexdec(substr($hex, 4, 2));
  return "$r, $g, $b";
}

// Função auxiliar para ajustar brilho da cor
function adjustBrightness($hex, $steps)
{
  $hex = str_replace("#", "", $hex);
  $r = hexdec(substr($hex, 0, 2));
  $g = hexdec(substr($hex, 2, 2));
  $b = hexdec(substr($hex, 4, 2));

  $r = max(0, min(255, $r + $steps));
  $g = max(0, min(255, $g + $steps));
  $b = max(0, min(255, $b + $steps));

  return "#" . str_pad(dechex($r), 2, "0", STR_PAD_LEFT) .
    str_pad(dechex($g), 2, "0", STR_PAD_LEFT) .
    str_pad(dechex($b), 2, "0", STR_PAD_LEFT);
}
?>