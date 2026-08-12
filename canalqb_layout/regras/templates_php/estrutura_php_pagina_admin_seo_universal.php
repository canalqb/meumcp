<?php
/**
 * Template Universal: Página Administrativa de SEO
 * 
 * Este template serve como base para criar páginas de otimização SEO
 * em qualquer tipo de site, seguindo o padrão do projeto pizzaria
 * 
 * @version 1.0
 * @author Sistema IA
 * 
 * Como usar:
 * 1. Copiar este arquivo para views/admin/{nome}_seo.php
 * 2. Substituir variáveis entre {chaves}
 * 3. Ajustar campos conforme necessidade
 * 4. Criar tabela no banco de dados
 */

if (!defined('APP')) { http_response_code(403); exit('Acesso direto negado'); }
require_once __DIR__ . '/../../config/security.php';

// Verificar permissão de administrador
if (currentUserRole() !== 'administrador') {
    header('Location: index.php?page=login');
    exit;
}

// Processamento do formulário
if ((isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '') === 'POST') {
    csrf_require();
    if (isset($_POST['save_seo'])) {
        $stmt = db()->prepare("INSERT INTO {tabela} (page_slug, title, description, keywords, og_image, canonical_url, robots_meta, author, twitter_card, twitter_creator) 
            VALUES (:slug, :title, :desc, :keys, :img, :canonical, :robots, :author, :twitter_card, :twitter_creator) 
            ON DUPLICATE KEY UPDATE title=VALUES(title), description=VALUES(description), keywords=VALUES(keywords), 
            og_image=VALUES(og_image), canonical_url=VALUES(canonical_url), robots_meta=VALUES(robots_meta), 
            author=VALUES(author), twitter_card=VALUES(twitter_card), twitter_creator=VALUES(twitter_creator)");
        
        $stmt->execute([
            ':slug' => $_POST['page_slug'],
            ':title' => $_POST['title'],
            ':desc' => $_POST['description'],
            ':keys' => $_POST['keywords'],
            ':img' => $_POST['og_image'] ?: null,
            ':canonical' => $_POST['canonical_url'] ?: null,
            ':robots' => $_POST['robots_meta'] ?: 'index,follow',
            ':author' => $_POST['author'] ?: null,
            ':twitter_card' => $_POST['twitter_card'] ?: 'summary_large_image',
            ':twitter_creator' => $_POST['twitter_creator'] ?: null
        ]);
        echo '<div class="alert alert-success"><i class="fas fa-search"></i> {mensagem_sucesso}</div>';
    }
}

$seos = db()->query("SELECT * FROM {tabela} ORDER BY page_slug")->fetchAll();

// Configurações do módulo (personalizar conforme necessidade)
$config_seo = [
    'nome' => '{nome_modulo}',
    'titulo' => '{titulo_pagina}',
    'descricao' => '{descricao_pagina}',
    'icone' => '{icone_principal}',
    'prefixo_tabela' => '{prefixo_tabela}',
    'paginas_sugeridas' => [
        // Páginas sugeridas para o site
    ]
];
?>

<div class="admin-header">
    <h1><i class="fas fa-{icone_principal}"></i> <?php echo $config_seo['titulo']; ?></h1>
    <p class="subtitle"><?php echo $config_seo['descricao']; ?></p>
</div>

<div class="grid-container">
    <div class="col-main">
        <div class="card shadow">
            <div class="card-header">
                <h2><i class="fas fa-globe"></i> Configurações SEO Ativas</h2>
                <div class="header-actions">
                    <button class="btn-export" onclick="exportSEO()">
                        <i class="fas fa-download"></i> Exportar
                    </button>
                    <button class="btn-import" onclick="importSEO()">
                        <i class="fas fa-upload"></i> Importar
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?php if ($seos): ?>
                    <div class="table-responsive">
                        <table class="admin-table seo-table">
                            <thead>
                                <tr>
                                    <th>Página</th>
                                    <th>Título</th>
                                    <th>Descrição</th>
                                    <th>Palavras-chave</th>
                                    <th>Imagem OG</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($seos as $seo): ?>
                                    <tr>
                                        <td>
                                            <div class="page-info">
                                                <code><?php echo htmlspecialchars($seo['page_slug']); ?></code>
                                                <small class="page-url"><?php echo APP_URL; ?>/<?php echo $seo['page_slug']; ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="title-info">
                                                <strong><?php echo htmlspecialchars($seo['title']); ?></strong>
                                                <small class="char-count"><?php echo strlen($seo['title']); ?> caracteres</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="desc-info">
                                                <span class="desc-text"><?php echo mb_strimwidth(htmlspecialchars($seo['description']), 0, 120, "..."); ?></span>
                                                <small class="char-count"><?php echo strlen($seo['description']); ?> caracteres</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="keywords-info">
                                                <?php 
                                                $keywords = array_filter(explode(',', $seo['keywords']));
                                                if (!empty($keywords)): 
                                                ?>
                                                    <div class="keywords-list">
                                                        <?php foreach (array_slice($keywords, 0, 3) as $keyword): ?>
                                                            <span class="keyword-tag"><?php echo htmlspecialchars(trim($keyword)); ?></span>
                                                        <?php endforeach; ?>
                                                        <?php if (count($keywords) > 3): ?>
                                                            <span class="keyword-more">+<?php echo count($keywords) - 3; ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="no-keywords">Sem palavras-chave</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if (!empty($seo['og_image'])): ?>
                                                <div class="og-image-preview">
                                                    <img src="<?php echo htmlspecialchars($seo['og_image']); ?>" 
                                                         alt="OG Image" 
                                                         onerror="this.src='<?php echo APP_URL; ?>/img/default-og.jpg'"
                                                         onclick="previewImage('<?php echo htmlspecialchars($seo['og_image']); ?>')">
                                                    <small class="image-status">OK</small>
                                                </div>
                                            <?php else: ?>
                                                <span class="no-image">Sem imagem</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="status-indicators">
                                                <?php if (!empty($seo['title'])): ?>
                                                    <span class="status-badge status-ok" title="Título definido">
                                                        <i class="fas fa-heading"></i>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if (!empty($seo['description'])): ?>
                                                    <span class="status-badge status-ok" title="Descrição definida">
                                                        <i class="fas fa-align-left"></i>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if (!empty($seo['og_image'])): ?>
                                                    <span class="status-badge status-ok" title="Imagem OG definida">
                                                        <i class="fas fa-image"></i>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn-icon" onclick="fillSEO(<?php echo htmlspecialchars(json_encode($seo)); ?>)" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn-icon" onclick="previewSEO(<?php echo htmlspecialchars(json_encode($seo)); ?>)" title="Preview">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn-icon" onclick="testSEO('<?php echo $seo['page_slug']; ?>')" title="Testar">
                                                    <i class="fas fa-search-plus"></i>
                                                </button>
                                                <button class="btn-icon" onclick="deleteSEO(<?php echo $seo['id']; ?>)" title="Excluir" style="color: #dc3545;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Estatísticas -->
                    <div class="seo-stats">
                        <div class="stat-card">
                            <i class="fas fa-file-alt"></i>
                            <div class="stat-info">
                                <strong><?php echo count($seos); ?></strong>
                                <span>Páginas Configuradas</span>
                            </div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-check-circle"></i>
                            <div class="stat-info">
                                <strong><?php echo count(array_filter($seos, fn($s) => !empty($s['title']))); ?></strong>
                                <span>Com Título</span>
                            </div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-align-left"></i>
                            <div class="stat-info">
                                <strong><?php echo count(array_filter($seos, fn($s) => !empty($s['description']))); ?></strong>
                                <span>Com Descrição</span>
                            </div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-image"></i>
                            <div class="stat-info">
                                <strong><?php echo count(array_filter($seos, fn($s) => !empty($s['og_image']))); ?></strong>
                                <span>Com Imagem OG</span>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-search"></i>
                        <h3>Nenhuma configuração SEO encontrada</h3>
                        <p>Comece configurando as páginas principais do seu site para melhorar o posicionamento nos motores de busca.</p>
                        <button class="btn-primary" onclick="showQuickSetup()">
                            <i class="fas fa-rocket"></i> Configuração Rápida
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-side">
        <div class="card shadow">
            <div class="card-header">
                <h2><i class="fas fa-pen-nib"></i> Editor de Meta Tags</h2>
            </div>
            <div class="card-body">
                <form method="post" id="seo-form" class="seo-form">
                    <?php echo csrf_input(); ?>
                    
                    <!-- Informações Básicas -->
                    <div class="form-section">
                        <h3><i class="fas fa-info-circle"></i> Informações Básicas</h3>
                        
                        <div class="form-group">
                            <label for="page_slug">
                                <i class="fas fa-link"></i> URL da Página (Slug)
                            </label>
                            <div class="input-group">
                                <span class="input-prefix"><?php echo APP_URL; ?>/</span>
                                <input type="text" 
                                       name="page_slug" 
                                       id="page_slug" 
                                       placeholder="home, contato, produtos..." 
                                       required
                                       class="form-control">
                            </div>
                            <small class="form-help">Use nomes simples e descritivos, sem espaços ou caracteres especiais.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="title">
                                <i class="fas fa-heading"></i> Título da Página (Title Tag)
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   placeholder="Título que aparece na aba do navegador" 
                                   maxlength="60"
                                   required
                                   class="form-control">
                            <div class="char-counter">
                                <span id="title-count">0</span>/60 caracteres
                            </div>
                            <small class="form-help">Mantenha entre 50-60 caracteres para melhor exibição no Google.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">
                                <i class="fas fa-align-left"></i> Meta Descrição
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="4" 
                                      placeholder="Descrição que aparece nos resultados de busca..." 
                                      maxlength="160"
                                      required
                                      class="form-control"></textarea>
                            <div class="char-counter">
                                <span id="desc-count">0</span>/160 caracteres
                            </div>
                            <small class="form-help">Use entre 150-160 caracteres para melhor exibição.</small>
                        </div>
                    </div>

                    <!-- Palavras-chave -->
                    <div class="form-section">
                        <h3><i class="fas fa-tags"></i> Palavras-chave</h3>
                        
                        <div class="form-group">
                            <label for="keywords">
                                <i class="fas fa-key"></i> Palavras-chave (Keywords)
                            </label>
                            <div class="keywords-input-container">
                                <input type="text" 
                                       name="keywords" 
                                       id="keywords" 
                                       placeholder="palavra1, palavra2, palavra3" 
                                       class="form-control">
                                <div class="keywords-suggestions" id="keywords-suggestions">
                                    <!-- Sugestões serão carregadas aqui -->
                                </div>
                            </div>
                            <small class="form-help">Separe as palavras-chave com vírgula. Use 5-10 palavras relevantes.</small>
                        </div>
                    </div>

                    <!-- Open Graph -->
                    <div class="form-section">
                        <h3><i class="fab fa-facebook"></i> Open Graph (Redes Sociais)</h3>
                        
                        <div class="form-group">
                            <label for="og_image">
                                <i class="fas fa-image"></i> Imagem para Redes Sociais
                            </label>
                            <div class="image-input-group">
                                <input type="text" 
                                       name="og_image" 
                                       id="og_image" 
                                       placeholder="https://exemplo.com/imagem-social.jpg" 
                                       class="form-control">
                                <button type="button" class="btn-upload" onclick="selectOGImage()">
                                    <i class="fas fa-upload"></i>
                                </button>
                            </div>
                            <div class="image-preview" id="og-preview" style="display: none;">
                                <img id="og-preview-img" src="" alt="Preview">
                                <button type="button" class="btn-remove" onclick="removeOGImage()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <small class="form-help">Use imagens de 1200x630px para melhor exibição.</small>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="twitter_card">Tipo do Card Twitter</label>
                                <select name="twitter_card" id="twitter_card" class="form-control">
                                    <option value="summary">Summary</option>
                                    <option value="summary_large_image" selected>Summary Large Image</option>
                                    <option value="app">App</option>
                                    <option value="player">Player</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="twitter_creator">Criador Twitter</label>
                                <input type="text" 
                                       name="twitter_creator" 
                                       id="twitter_creator" 
                                       placeholder="@seu_twitter" 
                                       class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Configurações Avançadas -->
                    <div class="form-section">
                        <h3><i class="fas fa-cog"></i> Configurações Avançadas</h3>
                        
                        <div class="form-group">
                            <label for="canonical_url">
                                <i class="fas fa-link"></i> URL Canônica
                            </label>
                            <input type="text" 
                                   name="canonical_url" 
                                   id="canonical_url" 
                                   placeholder="https://exemplo.com/pagina-canonica" 
                                   class="form-control">
                            <small class="form-help">URL preferencial para evitar conteúdo duplicado.</small>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="robots_meta">Meta Robots</label>
                                <select name="robots_meta" id="robots_meta" class="form-control">
                                    <option value="index,follow" selected>Index, Follow</option>
                                    <option value="noindex,follow">No Index, Follow</option>
                                    <option value="index,nofollow">Index, No Follow</option>
                                    <option value="noindex,nofollow">No Index, No Follow</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="author">Autor</label>
                                <input type="text" 
                                       name="author" 
                                       id="author" 
                                       placeholder="Nome do autor" 
                                       class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="form-actions">
                        <button type="submit" name="save_seo" class="btn-primary btn-full">
                            <i class="fas fa-save"></i> Salvar Configuração SEO
                        </button>
                        <button type="button" class="btn-secondary btn-full" onclick="testCurrentSEO()">
                            <i class="fas fa-search"></i> Testar SEO
                        </button>
                        <button type="button" class="btn-secondary btn-full" onclick="resetForm()">
                            <i class="fas fa-times"></i> Limpar Formulário
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Card de Dicas SEO -->
        <div class="card shadow card-seo-tips">
            <div class="card-header">
                <h3><i class="fas fa-lightbulb"></i> Dicas SEO</h3>
            </div>
            <div class="card-body">
                <div class="tips-carousel">
                    <div class="tip-item active">
                        <i class="fas fa-check-circle text-success"></i>
                        <strong>Título Otimizado:</strong> Use palavras-chave no início e mantenha entre 50-60 caracteres.
                    </div>
                    <div class="tip-item">
                        <i class="fas fa-check-circle text-success"></i>
                        <strong>Meta Descrição:</strong> Seja persuasivo e inclua chamada para ação.
                    </div>
                    <div class="tip-item">
                        <i class="fas fa-check-circle text-success"></i>
                        <strong>Imagens OG:</strong> Use 1200x630px para melhor visualização nas redes sociais.
                    </div>
                    <div class="tip-item">
                        <i class="fas fa-check-circle text-success"></i>
                        <strong>Palavras-chave:</strong> Foque em termos que seu público realmente busca.
                    </div>
                </div>
                <div class="tips-controls">
                    <button class="btn-tip" onclick="previousTip()">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="btn-tip" onclick="nextTip()">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Preview -->
<div id="seo-preview-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-eye"></i> Preview SEO</h3>
            <button class="modal-close" onclick="closeSEOPreview()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <!-- Preview Google -->
            <div class="preview-section">
                <h4><i class="fab fa-google"></i> Resultado Google</h4>
                <div class="google-preview">
                    <div class="google-title" id="preview-title">Título da Página</div>
                    <div class="google-url" id="preview-url">https://exemplo.com/pagina</div>
                    <div class="google-desc" id="preview-desc">Descrição da página que aparece nos resultados...</div>
                </div>
            </div>
            
            <!-- Preview Facebook -->
            <div class="preview-section">
                <h4><i class="fab fa-facebook"></i> Preview Facebook</h4>
                <div class="facebook-preview">
                    <div class="fb-image" id="preview-fb-image">
                        <i class="fas fa-image"></i>
                    </div>
                    <div class="fb-content">
                        <div class="fb-title" id="preview-fb-title">Título da Página</div>
                        <div class="fb-desc" id="preview-fb-desc">Descrição da página...</div>
                        <div class="fb-url" id="preview-fb-url">exemplo.com/pagina</div>
                    </div>
                </div>
            </div>
            
            <!-- Preview Twitter -->
            <div class="preview-section">
                <h4><i class="fab fa-twitter"></i> Preview Twitter</h4>
                <div class="twitter-preview">
                    <div class="tw-content">
                        <div class="tw-title" id="preview-tw-title">Título da Página</div>
                        <div class="tw-desc" id="preview-tw-desc">Descrição da página...</div>
                    </div>
                    <div class="tw-image" id="preview-tw-image">
                        <i class="fas fa-image"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
// Variáveis de configuração
const configSEO = {
    nome: '<?php echo $config_seo['nome']; ?>',
    tabela: '{tabela}',
    prefixo: '{prefixo_tabela}'
};

// Funções principais
function fillSEO(data) {
    // Preencher formulário com dados
    Object.keys(data).forEach(key => {
        const element = document.getElementById(key);
        if (element) {
            element.value = data[key];
            
            // Atualizar contadores e previews
            if (key === 'title') updateTitleCount();
            if (key === 'description') updateDescCount();
            if (key === 'og_image') updateOGPreview();
        }
    });
    
    // Atualizar preview
    updateSEOPreview();
    
    // Scroll para o formulário
    document.getElementById('seo-form').scrollIntoView({ behavior: 'smooth' });
}

function deleteSEO(id) {
    if (confirm('Tem certeza que deseja excluir esta configuração SEO?')) {
        window.location.href = `?page={modulo}_seo&action=delete&id=${id}`;
    }
}

function previewSEO(data) {
    // Preencher dados no modal
    document.getElementById('preview-title').textContent = data.title || 'Título não definido';
    document.getElementById('preview-url').textContent = `${window.location.origin}/${data.page_slug}`;
    document.getElementById('preview-desc').textContent = data.description || 'Descrição não definida';
    
    // Preview Facebook
    document.getElementById('preview-fb-title').textContent = data.title || 'Título não definido';
    document.getElementById('preview-fb-desc').textContent = data.description || 'Descrição não definida';
    document.getElementById('preview-fb-url').textContent = `${window.location.origin}/${data.page_slug}`;
    
    // Preview Twitter
    document.getElementById('preview-tw-title').textContent = data.title || 'Título não definido';
    document.getElementById('preview-tw-desc').textContent = data.description || 'Descrição não definida';
    
    // Imagens
    if (data.og_image) {
        document.getElementById('preview-fb-image').innerHTML = `<img src="${data.og_image}" alt="Preview" onerror="this.innerHTML='<i class=\\'fas fa-image\\'></i>'">`;
        document.getElementById('preview-tw-image').innerHTML = `<img src="${data.og_image}" alt="Preview" onerror="this.innerHTML='<i class=\\'fas fa-image\\'></i>'">`;
    }
    
    // Mostrar modal
    document.getElementById('seo-preview-modal').style.display = 'block';
}

function closeSEOPreview() {
    document.getElementById('seo-preview-modal').style.display = 'none';
}

function testSEO(slug) {
    const url = `${window.location.origin}/${slug}`;
    window.open(`https://search.google.com/search-console/url-inspection/?resource_id=${encodeURIComponent(url)}`, '_blank');
}

function testCurrentSEO() {
    const slug = document.getElementById('page_slug').value;
    if (slug) {
        testSEO(slug);
    } else {
        showToast('Digite uma URL primeiro', 'error');
    }
}

function exportSEO() {
    // Implementar exportação CSV/JSON
    window.location.href = '?page={modulo}_seo&action=export';
}

function importSEO() {
    // Implementar importação CSV/JSON
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = '.json,.csv';
    input.onchange = handleFileImport;
    input.click();
}

function handleFileImport(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            try {
                const data = JSON.parse(e.target.result);
                // Processar dados importados
                showToast('Importação realizada com sucesso!', 'success');
            } catch (error) {
                showToast('Erro ao importar arquivo', 'error');
            }
        };
        reader.readAsText(file);
    }
}

function selectOGImage() {
    // Implementar seleção de imagem
    showToast('Seletor de imagens em desenvolvimento', 'info');
}

function removeOGImage() {
    document.getElementById('og_image').value = '';
    document.getElementById('og-preview').style.display = 'none';
}

function updateOGPreview() {
    const imageUrl = document.getElementById('og_image').value;
    const preview = document.getElementById('og-preview');
    const previewImg = document.getElementById('og-preview-img');
    
    if (imageUrl) {
        previewImg.src = imageUrl;
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
}

function updateTitleCount() {
    const title = document.getElementById('title').value;
    document.getElementById('title-count').textContent = title.length;
    
    // Mudar cor baseado no limite
    const counter = document.getElementById('title-count');
    if (title.length > 60) {
        counter.style.color = '#dc3545';
    } else if (title.length > 50) {
        counter.style.color = '#ffc107';
    } else {
        counter.style.color = '#28a745';
    }
}

function updateDescCount() {
    const desc = document.getElementById('description').value;
    document.getElementById('desc-count').textContent = desc.length;
    
    // Mudar cor baseado no limite
    const counter = document.getElementById('desc-count');
    if (desc.length > 160) {
        counter.style.color = '#dc3545';
    } else if (desc.length > 150) {
        counter.style.color = '#ffc107';
    } else {
        counter.style.color = '#28a745';
    }
}

function updateSEOPreview() {
    const title = document.getElementById('title').value || 'Título da Página';
    const desc = document.getElementById('description').value || 'Descrição da página...';
    const slug = document.getElementById('page_slug').value || 'pagina';
    const ogImage = document.getElementById('og_image').value;
    
    // Atualizar preview Google
    document.getElementById('preview-title').textContent = title;
    document.getElementById('preview-url').textContent = `${window.location.origin}/${slug}`;
    document.getElementById('preview-desc').textContent = desc;
    
    // Atualizar preview Facebook
    document.getElementById('preview-fb-title').textContent = title;
    document.getElementById('preview-fb-desc').textContent = desc;
    document.getElementById('preview-fb-url').textContent = `${window.location.origin}/${slug}`;
    
    // Atualizar preview Twitter
    document.getElementById('preview-tw-title').textContent = title;
    document.getElementById('preview-tw-desc').textContent = desc;
    
    // Atualizar imagens
    if (ogImage) {
        document.getElementById('preview-fb-image').innerHTML = `<img src="${ogImage}" alt="Preview" onerror="this.innerHTML='<i class=\\'fas fa-image\\'></i>'">`;
        document.getElementById('preview-tw-image').innerHTML = `<img src="${ogImage}" alt="Preview" onerror="this.innerHTML='<i class=\\'fas fa-image\\'></i>'">`;
    }
}

function resetForm() {
    document.getElementById('seo-form').reset();
    updateTitleCount();
    updateDescCount();
    updateOGPreview();
    updateSEOPreview();
}

function showQuickSetup() {
    // Implementar configuração rápida
    showToast('Configuração rápida em desenvolvimento', 'info');
}

function previewImage(url) {
    window.open(url, '_blank');
}

// Sistema de dicas carousel
let currentTip = 0;
const tips = document.querySelectorAll('.tip-item');

function showTip(index) {
    tips.forEach((tip, i) => {
        tip.classList.toggle('active', i === index);
    });
}

function nextTip() {
    currentTip = (currentTip + 1) % tips.length;
    showTip(currentTip);
}

function previousTip() {
    currentTip = (currentTip - 1 + tips.length) % tips.length;
    showTip(currentTip);
}

// Auto-rotate tips
setInterval(nextTip, 5000);

// Sistema de Toast
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Contadores de caracteres
    document.getElementById('title').addEventListener('input', updateTitleCount);
    document.getElementById('description').addEventListener('input', updateDescCount);
    document.getElementById('og_image').addEventListener('input', updateOGPreview);
    
    // Preview em tempo real
    document.querySelectorAll('#seo-form input, #seo-form textarea').forEach(input => {
        input.addEventListener('input', updateSEOPreview);
    });
    
    // Inicialização
    updateTitleCount();
    updateDescCount();
    updateSEOPreview();
    
    // Auto-save (opcional)
    let autoSaveTimer;
    document.querySelectorAll('#seo-form input, #seo-form textarea').forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(() => {
                // Implementar auto-save
            }, 5000);
        });
    });
});

// Fechar modal ao clicar fora
window.onclick = function(event) {
    const modal = document.getElementById('seo-preview-modal');
    if (event.target === modal) {
        closeSEOPreview();
    }
}
</script>

<!-- CSS -->
<style>
/* Estilos universais */
.admin-header { 
    margin-bottom: 2rem; 
    border-bottom: 2px solid var(--brand); 
    padding-bottom: 1rem; 
}

.subtitle { 
    color: var(--muted); 
    margin-top: 0.5rem; 
    font-size: 1.1rem;
}

.grid-container { 
    display: grid; 
    grid-template-columns: 1fr 400px; 
    gap: 2rem; 
}

.card { 
    background: var(--card); 
    border-radius: 12px; 
    border: 1px solid var(--border); 
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 12px rgba(0,0,0,0.15);
}

.card-header { 
    background: linear-gradient(135deg, var(--bg) 0%, rgba(0,0,0,0.05) 100%); 
    padding: 1rem 1.5rem; 
    border-bottom: 1px solid var(--border); 
    border-radius: 12px 12px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h2, .card-header h3 {
    margin: 0;
    color: var(--brand);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.header-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-export, .btn-import {
    background: var(--bg);
    border: 1px solid var(--border);
    padding: 8px 12px;
    border-radius: 6px;
    cursor: pointer;
    color: var(--muted);
    transition: all 0.3s ease;
    font-size: 0.85rem;
}

.btn-export:hover, .btn-import:hover {
    background: var(--brand);
    color: white;
    border-color: var(--brand);
}

.card-body { 
    padding: 1.5rem; 
}

.admin-table { 
    width: 100%; 
    border-collapse: collapse; 
    font-size: 0.9rem;
}

.admin-table th { 
    text-align: left; 
    padding: 1rem; 
    background: var(--bg); 
    font-weight: 600;
    border-bottom: 2px solid var(--border);
}

.admin-table td { 
    padding: 1rem; 
    border-bottom: 1px solid var(--border); 
    vertical-align: middle;
}

.admin-table tr:hover {
    background: var(--bg);
}

/* SEO Table Styles */
.seo-table .page-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.seo-table .page-url {
    color: var(--muted);
    font-size: 0.8rem;
    font-family: monospace;
}

.seo-table .title-info,
.seo-table .desc-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.seo-table .char-count {
    color: var(--muted);
    font-size: 0.75rem;
}

.seo-table .keywords-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.25rem;
}

.seo-table .keyword-tag {
    background: var(--brand);
    color: white;
    padding: 2px 6px;
    border-radius: 12px;
    font-size: 0.7rem;
}

.seo-table .keyword-more {
    background: var(--muted);
    color: white;
    padding: 2px 6px;
    border-radius: 12px;
    font-size: 0.7rem;
}

.seo-table .no-keywords,
.seo-table .no-image {
    color: var(--muted);
    font-style: italic;
    font-size: 0.8rem;
}

.seo-table .og-image-preview {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.seo-table .og-image-preview img {
    width: 30px;
    height: 30px;
    object-fit: cover;
    border-radius: 4px;
    cursor: pointer;
}

.seo-table .image-status {
    color: #28a745;
    font-size: 0.7rem;
}

.seo-table .status-indicators {
    display: flex;
    gap: 0.25rem;
}

.seo-table .status-badge {
    background: var(--bg);
    border: 1px solid var(--border);
    padding: 4px;
    border-radius: 4px;
    font-size: 0.7rem;
    color: var(--muted);
}

.seo-table .status-ok {
    background: #d4edda;
    border-color: #28a745;
    color: #155724;
}

.seo-table .action-buttons {
    display: flex;
    gap: 0.25rem;
}

/* SEO Stats */
.seo-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 2rem;
}

.stat-card {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.stat-card i {
    font-size: 2rem;
    color: var(--brand);
}

.stat-info strong {
    display: block;
    font-size: 1.5rem;
    color: var(--text);
}

.stat-info span {
    color: var(--muted);
    font-size: 0.8rem;
}

/* Form Styles */
.form-section {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: var(--bg);
    border-radius: 8px;
    border: 1px solid var(--border);
}

.form-section h3 {
    margin: 0 0 1rem 0;
    color: var(--brand);
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--border);
}

.form-group { 
    margin-bottom: 1.5rem; 
}

.form-group label { 
    display: block; 
    margin-bottom: 0.5rem; 
    font-weight: 600; 
    color: var(--text);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-control {
    width: 100%; 
    padding: 12px; 
    border: 2px solid var(--border); 
    border-radius: 8px; 
    font-family: inherit;
    font-size: 1rem;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: var(--brand);
    box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
}

.input-group {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.input-prefix {
    background: var(--bg);
    border: 2px solid var(--border);
    border-right: none;
    padding: 12px;
    border-radius: 8px 0 0 8px;
    color: var(--muted);
    font-family: monospace;
}

.input-group .form-control {
    border-radius: 0 8px 8px 0;
}

.char-counter {
    text-align: right;
    margin-top: 0.25rem;
    font-size: 0.8rem;
    color: var(--muted);
}

.form-help {
    display: block;
    margin-top: 0.5rem;
    font-size: 0.8rem;
    color: var(--muted);
    font-style: italic;
}

.form-row {
    display: grid; 
    grid-template-columns: 1fr 1fr; 
    gap: 1rem; 
}

.keywords-input-container {
    position: relative;
}

.keywords-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid var(--border);
    border-top: none;
    border-radius: 0 0 8px 8px;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
    display: none;
}

.image-input-group {
    display: flex;
    gap: 0.5rem;
}

.image-input-group .form-control {
    flex: 1;
}

.btn-upload {
    background: var(--brand);
    color: white;
    border: none;
    padding: 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-upload:hover {
    background: var(--brand-dark);
}

.image-preview {
    position: relative;
    margin-top: 1rem;
    display: inline-block;
}

.image-preview img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid var(--border);
}

.btn-remove {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #dc3545;
    color: white;
    border: none;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.form-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-top: 2rem;
}

.btn-primary { 
    background: var(--brand); 
    color: white; 
    border: none; 
    padding: 14px; 
    border-radius: 8px; 
    cursor: pointer; 
    font-size: 1rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover { 
    background: var(--brand-dark);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.btn-secondary { 
    background: var(--bg); 
    color: var(--text); 
    border: 2px solid var(--border); 
    padding: 14px; 
    border-radius: 8px; 
    cursor: pointer; 
    font-size: 1rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-secondary:hover { 
    background: var(--border);
    border-color: var(--brand);
    color: var(--brand);
}

.btn-full { 
    width: 100%; 
}

.btn-icon { 
    background: none; 
    border: 1px solid var(--border); 
    padding: 8px; 
    border-radius: 6px; 
    cursor: pointer; 
    color: var(--muted);
    transition: all 0.3s ease;
}

.btn-icon:hover {
    background: var(--brand);
    color: white;
    border-color: var(--brand);
}

/* SEO Tips Card */
.card-seo-tips {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-color: #2196f3;
}

.tips-carousel {
    position: relative;
    min-height: 80px;
    display: flex;
    align-items: center;
}

.tip-item {
    position: absolute;
    width: 100%;
    opacity: 0;
    transition: opacity 0.5s ease;
    font-size: 0.9rem;
    line-height: 1.4;
}

.tip-item.active {
    opacity: 1;
}

.tips-controls {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 1rem;
}

.btn-tip {
    background: rgba(33, 150, 243, 0.1);
    border: 1px solid #2196f3;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #2196f3;
    transition: all 0.3s ease;
}

.btn-tip:hover {
    background: #2196f3;
    color: white;
}

/* Modal Styles */
.modal {
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    animation: fadeIn 0.3s ease;
}

.modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 0;
    border-radius: 12px;
    width: 90%;
    max-width: 800px;
    max-height: 80vh;
    overflow-y: auto;
    animation: slideIn 0.3s ease;
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: var(--brand);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--muted);
    transition: color 0.3s ease;
}

.modal-close:hover {
    color: var(--brand);
}

.modal-body {
    padding: 1.5rem;
}

.preview-section {
    margin-bottom: 2rem;
}

.preview-section h4 {
    margin: 0 0 1rem 0;
    color: var(--brand);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Google Preview */
.google-preview {
    border: 1px solid #dfe1e5;
    border-radius: 8px;
    padding: 1rem;
    background: white;
}

.google-title {
    color: #1a0dab;
    font-size: 1.2rem;
    font-weight: 400;
    margin-bottom: 0.5rem;
    cursor: pointer;
}

.google-title:hover {
    text-decoration: underline;
}

.google-url {
    color: #006621;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.google-desc {
    color: #545454;
    font-size: 0.9rem;
    line-height: 1.4;
}

/* Facebook Preview */
.facebook-preview {
    border: 1px solid #dddfe2;
    border-radius: 8px;
    overflow: hidden;
    background: white;
}

.fb-image {
    width: 100%;
    height: 200px;
    background: var(--bg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--muted);
    font-size: 2rem;
}

.fb-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.fb-content {
    padding: 1rem;
}

.fb-title {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #1d2129;
}

.fb-desc {
    color: #606770;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.fb-url {
    color: #606770;
    font-size: 0.8rem;
    text-transform: uppercase;
}

/* Twitter Preview */
.twitter-preview {
    border: 1px solid #e1e8ed;
    border-radius: 8px;
    overflow: hidden;
    background: white;
    display: flex;
}

.tw-content {
    flex: 1;
    padding: 1rem;
}

.tw-title {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #0f1419;
}

.tw-desc {
    color: #536471;
    font-size: 0.9rem;
    line-height: 1.4;
}

.tw-image {
    width: 120px;
    height: 120px;
    background: var(--bg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--muted);
}

.tw-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    color: var(--muted);
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state h3 {
    margin: 0 0 1rem 0;
    color: var(--text);
}

.empty-state p {
    margin-bottom: 2rem;
    line-height: 1.6;
}

/* Sistema de Toast */
.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    background: white;
    border-radius: 8px;
    padding: 1rem 1.5rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    display: flex;
    align-items: center;
    gap: 1rem;
    min-width: 300px;
    transform: translateX(100%);
    transition: transform 0.3s ease;
}

.toast.show {
    transform: translateX(0);
}

.toast-success {
    border-left: 4px solid #28a745;
}

.toast-error {
    border-left: 4px solid #dc3545;
}

.toast-info {
    border-left: 4px solid #17a2b8;
}

/* Responsividade */
@media (max-width: 1200px) { 
    .grid-container { 
        grid-template-columns: 1fr; 
    } 
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .input-group {
        flex-direction: column;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .form-section {
        padding: 1rem;
    }
    
    .seo-stats {
        grid-template-columns: 1fr;
    }
    
    .modal-content {
        width: 95%;
        margin: 10% auto;
    }
    
    .twitter-preview {
        flex-direction: column;
    }
    
    .tw-image {
        width: 100%;
        height: 200px;
    }
}

/* Animações */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Acessibilidade */
.btn-primary:focus,
.btn-secondary:focus,
.btn-icon:focus {
    outline: 2px solid var(--brand);
    outline-offset: 2px;
}

.form-control:focus {
    outline: 2px solid var(--brand);
    outline-offset: 2px;
}

/* Contraste aprimorado */
@media (prefers-contrast: high) {
    .card {
        border: 2px solid var(--text);
    }
    
    .form-control {
        border: 2px solid var(--text);
    }
}
</style>
