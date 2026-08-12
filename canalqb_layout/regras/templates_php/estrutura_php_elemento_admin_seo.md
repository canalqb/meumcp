# 🛠️ Estrutura PHP: Elemento Gerenciador de SEO e Identidade Visual (Universal)

Este template define a estrutura para uma página administrativa de configuração de SEO (Meta Tags) e Identidade Visual (Logo e Cores), permitindo que o administrador controle o visual e a indexação do site sem alterar o código.

## 🗄️ Esquema de Banco de Dados (MySQL/MariaDB)

```sql
-- Prefixos obrigatórios: projeto_tipo_
CREATE TABLE IF NOT EXISTS sys_seo_config (
    page_slug VARCHAR(100) PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    keywords VARCHAR(255),
    og_image TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS sys_configuracoes (
    chave VARCHAR(100) PRIMARY KEY,
    valor TEXT,
    tipo VARCHAR(50) DEFAULT 'string',
    descricao VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Índices para performance
CREATE INDEX idx_seo_slug ON sys_seo_config (page_slug(191));
```

## 📄 Estrutura da Página (`views/admin/seo_config.php`)

```php
<?php 
if (!defined('APP')) { http_response_code(403); exit('Acesso direto negado'); }
require_once __DIR__ . '/../../config/security.php';

// Processamento de Formulários
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_require();
    
    // Salvar SEO por Página
    if (isset($_POST['save_seo'])) {
        $stmt = db()->prepare("INSERT INTO sys_seo_config (page_slug, title, description, keywords, og_image) 
            VALUES (:slug, :title, :desc, :keys, :img) 
            ON DUPLICATE KEY UPDATE title=VALUES(title), description=VALUES(description), keywords=VALUES(keywords), og_image=VALUES(og_image)");
        
        $stmt->execute([
            ':slug'  => $_POST['page_slug'],
            ':title' => $_POST['title'],
            ':desc'  => $_POST['description'],
            ':keys'  => $_POST['keywords'],
            ':img'   => $_POST['og_image'] ?: null
        ]);
        echo '<div class="alert alert-success"><i class="fas fa-check-circle"></i> SEO atualizado para: ' . htmlspecialchars($_POST['page_slug']) . '</div>';
    }
    
    // Salvar Identidade Visual
    if (isset($_POST['save_identity'])) {
        $configs = [
            'site_logo_url' => $_POST['logo_url'],
            'brand_color'   => $_POST['brand_color'],
            'site_name'     => $_POST['site_name']
        ];
        
        foreach ($configs as $chave => $valor) {
            $valJson = json_encode($valor);
            $stmt = db()->prepare("INSERT INTO sys_configuracoes (chave, valor, tipo) VALUES (?, ?, 'string') ON DUPLICATE KEY UPDATE valor = ?");
            $stmt->execute([$chave, $valJson, $valJson]);
        }
        
        echo '<div class="alert alert-success"><i class="fas fa-palette"></i> Identidade Visual atualizada!</div>';
    }
}

// Carregar Dados
$seos = db()->query("SELECT * FROM sys_seo_config ORDER BY page_slug ASC")->fetchAll();

function getConfig(string $key, $default = '') {
    $raw = db()->prepare("SELECT valor FROM sys_configuracoes WHERE chave = ?");
    $raw->execute([$key]);
    $res = $raw->fetchColumn();
    return $res ? json_decode($res, true) : $default;
}

$logoUrl    = getConfig('site_logo_url');
$brandColor = getConfig('brand_color', '#007bff');
$siteName   = getConfig('site_name', 'Meu Sistema');
?>

<div class="admin-header">
    <h1><i class="fas fa-search-plus"></i> SEO & Identidade Visual</h1>
    <p>Otimize sua presença nos buscadores e defina a personalidade da sua marca.</p>
</div>

<div class="grid-container">
    <div class="col-main">
        <!-- Card: Lista de SEO -->
        <div class="card shadow">
            <div class="card-header">
                <h2><i class="fas fa-globe-americas"></i> Páginas Configuradas</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Identificador (Slug)</th>
                                <th>Título SEO</th>
                                <th>Descrição</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($seos as $s): ?>
                                <tr>
                                    <td><code><?= htmlspecialchars($s['page_slug']) ?></code></td>
                                    <td><strong><?= htmlspecialchars($s['title']) ?></strong></td>
                                    <td><small><?= mb_strimwidth(htmlspecialchars($s['description']), 0, 60, '...') ?></small></td>
                                    <td>
                                        <button class="btn-icon" onclick="fillSeo(<?= htmlspecialchars(json_encode($s)) ?>)" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if(!$seos): ?>
                                <tr><td colspan="4" class="empty-msg">Nenhuma regra de SEO cadastrada.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Card: Identidade Visual -->
        <div class="card shadow" style="margin-top: 2rem;">
            <div class="card-header">
                <h2><i class="fas fa-palette"></i> Cores e Logotipo</h2>
            </div>
            <div class="card-body">
                <form method="post">
                    <?= csrf_input() ?>
                    <div class="form-group">
                        <label>Nome do Site/Marca:</label>
                        <input type="text" name="site_name" value="<?= htmlspecialchars((string)$siteName) ?>" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Cor Principal:</label>
                            <input type="color" name="brand_color" value="<?= htmlspecialchars($brandColor) ?>" style="height: 45px;">
                        </div>
                        <div class="form-group">
                            <label>URL do Logotipo:</label>
                            <input type="text" name="logo_url" value="<?= htmlspecialchars((string)$logoUrl) ?>" placeholder="https://exemplo.com/logo.png">
                        </div>
                    </div>
                    <button type="submit" name="save_identity" class="btn-primary"><i class="fas fa-save"></i> Atualizar Identidade</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-side">
        <!-- Card: Editor SEO -->
        <div class="card shadow">
            <div class="card-header">
                <h2><i class="fas fa-pen-nib"></i> Editor de Meta Tags</h2>
            </div>
            <div class="card-body">
                <form method="post" id="seo-form">
                    <?= csrf_input() ?>
                    <div class="form-group">
                        <label>Identificador da Página (Slug):</label>
                        <input type="text" name="page_slug" id="f-slug" required placeholder="Ex: home, contato, sobre">
                    </div>
                    <div class="form-group">
                        <label>Título da Aba (Title Tag):</label>
                        <input type="text" name="title" id="f-title" required placeholder="Título atraente para o Google">
                    </div>
                    <div class="form-group">
                        <label>Meta Descrição:</label>
                        <textarea name="description" id="f-desc" rows="4" placeholder="Breve resumo para os resultados de busca..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Palavras-chave (keywords):</label>
                        <input type="text" name="keywords" id="f-kw" placeholder="palavra1, palavra2, ...">
                    </div>
                    <div class="form-group">
                        <label>URL Imagem Social (OpenGraph):</label>
                        <input type="text" name="og_image" id="f-img" placeholder="https://exemplo.com/social.jpg">
                    </div>
                    <button type="submit" name="save_seo" class="btn-primary btn-full"><i class="fas fa-save"></i> Salvar SEO</button>
                    <button type="button" class="btn-secondary btn-full btn-sm" onclick="resetSeoForm()" style="margin-top: 0.5rem;">Limpar</button>
                </form>
            </div>
        </div>
        
        <div class="card shadow tip-card" style="margin-top: 1.5rem;">
            <div class="card-body">
                <h3><i class="fas fa-lightbulb"></i> Dica Ninja</h3>
                <p>Mantenha seu <strong>Meta Título</strong> abaixo de 60 caracteres e a <strong>Descrição</strong> entre 150-160 para evitar cortes no Google.</p>
            </div>
        </div>
    </div>
</div>

<script>
function fillSeo(data) {
    document.getElementById('f-slug').value = data.page_slug;
    document.getElementById('f-title').value = data.title;
    document.getElementById('f-desc').value = data.description || '';
    document.getElementById('f-img').value = data.og_image || '';
    document.getElementById('f-kw').value = data.keywords || '';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function resetSeoForm() {
    document.getElementById('seo-form').reset();
}
</script>

<style>
/* Layout Base Administrativo (Assumindo variáveis globais de CSS) */
.grid-container { display: grid; grid-template-columns: 1fr 340px; gap: 2rem; align-items: start; }
.card { background: var(--card-bg, #fff); border-radius: 12px; border: 1px solid var(--border-color, #eee); overflow: hidden; }
.card-header { padding: 1.25rem; background: rgba(0,0,0,0.02); border-bottom: 1px solid var(--border-color, #eee); }
.card-body { padding: 1.5rem; }
.admin-header { margin-bottom: 2.5rem; border-left: 5px solid var(--brand-color, #007bff); padding-left: 1.5rem; }
.admin-header h1 { font-size: 1.8rem; margin: 0; }
.admin-header p { color: #666; margin-top: 0.5rem; }

.admin-table { width: 100%; border-collapse: collapse; }
.admin-table th { text-align: left; padding: 1rem; background: #f8f9fa; font-size: 0.85rem; color: #555; }
.admin-table td { padding: 1rem; border-bottom: 1px solid #eee; vertical-align: middle; }

.form-group { margin-bottom: 1.25rem; }
.form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem; }
.form-group input, .form-group textarea { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; transition: 0.3s; }
.form-group input:focus, .form-group textarea:focus { border-color: var(--brand-color, #007bff); outline: none; box-shadow: 0 0 0 3px rgba(0,123,255,0.1); }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

.btn-primary { background: var(--brand-color, #007bff); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; cursor: pointer; font-weight: 600; transition: 0.3s; }
.btn-primary:hover { filter: brightness(1.1); transform: translateY(-1px); }
.btn-secondary { background: #f8f9fa; color: #555; border: 1px solid #ddd; padding: 0.5rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem; }
.btn-icon { background: none; border: 1px solid #eee; padding: 0.5rem; border-radius: 6px; color: #555; cursor: pointer; transition: 0.2s; }
.btn-icon:hover { background: #f0f0f0; color: var(--brand-color, #007bff); }
.btn-full { width: 100%; }
.btn-sm { font-size: 0.8rem; padding: 0.5rem; }

.tip-card { background: #fffde7; border-color: #fdd835; }
.empty-msg { text-align: center; padding: 2rem; color: #888; font-style: italic; }

@media (max-width: 1024px) { .grid-container { grid-template-columns: 1fr; } }
</style>
```

## 🛠️ Implementação no `index.php` (Header)

Para que o SEO funcione, inclua no `<head>` do seu `index.php` ou layout principal:

```php
<?php
// Tentar buscar SEO no banco
$page = $_GET['page'] ?? 'home';
try {
    $stmt = db()->prepare("SELECT * FROM sys_seo_config WHERE page_slug = ?");
    $stmt->execute([$page]);
    $seo = $stmt->fetch();
} catch (Exception $e) { $seo = null; }

$title = $seo['title'] ?? 'Nome Padrão do Site';
$desc  = $seo['description'] ?? 'Descrição padrão do site';
$img   = $seo['og_image'] ?? 'URL_DA_LOGO_PADRAO';
?>
<title><?= htmlspecialchars($title) ?></title>
<meta name="description" content="<?= htmlspecialchars($desc) ?>" />
<meta property="og:title" content="<?= htmlspecialchars($title) ?>" />
<meta property="og:description" content="<?= htmlspecialchars($desc) ?>" />
<meta property="og:image" content="<?= htmlspecialchars($img) ?>" />
<meta property="og:type" content="website" />
```
