# 🎯 Regra Universal: Criação de Páginas Administrativas

## 📋 Objetivo
Este documento ensina todos os LLMs a criar páginas administrativas universais que funcionem em QUALQUER tipo de site, sem dependências específicas do projeto pizzaria.

## 🎨 Páginas de Referência (Apenas para Estudo)

### 1. Página de Configuração de Layout (CSS Dinâmico)
- **Funcionalidade:** Configuração de estilos CSS com autocomplete e preview
- **Conceitos:** Sistema de configuração visual aplicável a qualquer site

### 2. Página de Configuração de SEO
- **Funcionalidade:** Gerenciamento de meta tags SEO por página
- **Conceitos:** Sistema de otimização aplicável a qualquer site

## 🏗️ Estrutura Universal

### 📁 Padrão de Arquivos (ADAPTÁVEL)
```
views/admin/
├── {nome_modulo}_config.php          # Configuração principal
├── {nome_modulo}_seo.php           # Configuração SEO
└── {nome_modulo}_avancadas.php     # Configurações avançadas (opcional)
```

### 🗄️ Padrão de Banco de Dados (UNIVERSAL)
```sql
-- Tabela de Configurações (ADAPTÁVEL)
CREATE TABLE {prefixo_config} (
    id INT AUTO_INCREMENT PRIMARY KEY,
    elemento_classe VARCHAR(255) NOT NULL,
    cor_fundo VARCHAR(7),
    cor_texto VARCHAR(7),
    fonte_familia VARCHAR(255),
    fonte_tamanho VARCHAR(50),
    borda_raio VARCHAR(50),
    custom_css TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY (elemento_classe)
);

-- Tabela SEO (ADAPTÁVEL)
CREATE TABLE {prefixo_seo_config} (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page_slug VARCHAR(255) NOT NULL UNIQUE,
    title VARCHAR(255),
    description TEXT,
    keywords TEXT,
    og_image VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## 🎯 Template Universal: Página de Configuração

### 📋 Estrutura Base PHP (TOTALMENTE UNIVERSAL)
```php
<?php
/**
 * Template Universal: Página Administrativa de Configuração
 * 
 * Este template serve como base para criar páginas de configuração
 * em QUALQUER tipo de site (e-commerce, blog, SaaS, institucional, etc.)
 * 
 * @version 2.0 - Universal
 * @author Sistema IA
 * 
 * Como usar:
 * 1. Copiar este arquivo para views/admin/{nome_modulo}_config.php
 * 2. Substituir variáveis entre {chaves}
 * 3. Ajustar campos conforme necessidade do site
 * 4. Criar tabela no banco de dados (adaptar prefixo)
 */

if (!defined('APP')) { http_response_code(403); exit('Acesso direto negado'); }
require_once __DIR__ . '/../../config/security.php';

// Verificar permissão de administrador (ADAPTÁVEL)
$tipo_admin = '{tipo_admin}'; // Ex: 'administrador', 'admin', 'superuser'
if (currentUserRole() !== $tipo_admin) {
    header('Location: index.php?page=login');
    exit;
}

// Processamento do formulário
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    csrf_require();
    if (isset($_POST['save_{modulo}'])) {
        $stmt = db()->prepare("INSERT INTO {tabela_config} ({campos}) 
            VALUES ({placeholders}) 
            ON DUPLICATE KEY UPDATE {updates}");
        
        $stmt->execute([
            // Array de dados dinâmico (adaptar conforme necessidade)
            ':elemento' => $_POST['elemento_classe'],
            ':cor_fundo' => $_POST['cor_fundo'] ?: null,
            ':cor_texto' => $_POST['cor_texto'] ?: null,
            ':fonte_familia' => $_POST['fonte_familia'] ?: null,
            ':fonte_tamanho' => $_POST['fonte_tamanho'] ?: null,
            ':borda_raio' => $_POST['borda_raio'] ?: null,
            ':custom_css' => $_POST['custom_css'] ?: null
        ]);
        echo '<div class="alert alert-success"><i class="fas fa-check-circle"></i> {mensagem_sucesso}</div>';
    }
}

$dados = db()->query("SELECT * FROM {tabela_config}")->fetchAll();

// Configurações do módulo (PERSONALIZÁVEL)
$config_modulo = [
    'nome' => '{nome_modulo}',
    'titulo' => '{titulo_pagina}',
    'descricao' => '{descricao_pagina}',
    'icone' => '{icone_principal}',
    'prefixo_tabela' => '{prefixo_tabela}',
    'tipo_admin' => $tipo_admin,
    'campos_personalizados' => [
        // Adicionar campos específicos do site aqui
    ]
];
?>

## 🎯 Template Universal: Página de Configuração

### 📋 Estrutura Base PHP
```php
<?php if (!defined('APP')) { http_response_code(403); exit('Acesso direto negado'); }
require_once __DIR__ . '/../../config/security.php';

// Verificar permissão de administrador
if (currentUserRole() !== 'administrador') {
    header('Location: index.php?page=login');
    exit;
}

// Processamento do formulário
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    csrf_require();
    if (isset($_POST['save_{modulo}'])) {
        $stmt = db()->prepare("INSERT INTO {tabela} ({campos}) 
            VALUES ({placeholders}) 
            ON DUPLICATE KEY UPDATE {updates}");
        
        $stmt->execute($dados);
        echo '<div class="alert alert-success"><i class="fas fa-check-circle"></i> {mensagem_sucesso}</div>';
    }
}

$dados = db()->query("SELECT * FROM {tabela}")->fetchAll();
?>

<!-- HTML Structure -->
<div class="admin-header">
    <h1><i class="fas fa-{icone}"></i> {titulo_pagina}</h1>
    <p class="subtitle">{descricao_pagina}</p>
</div>

<div class="grid-container">
    <div class="col-main">
        <!-- Listagem de Configurações -->
        <div class="card shadow">
            <div class="card-header">
                <h2><i class="fas fa-{icone_lista}"></i> {titulo_lista}</h2>
            </div>
            <div class="card-body">
                <?php if ($dados): ?>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>{coluna1}</th>
                                <th>{coluna2}</th>
                                <th>{coluna3}</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dados as $item): ?>
                                <tr>
                                    <td>{conteudo_coluna1}</td>
                                    <td>{conteudo_coluna2}</td>
                                    <td>{conteudo_coluna3}</td>
                                    <td>
                                        <button class="btn-icon" onclick="fillForm(<?= htmlspecialchars(json_encode($item)) ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="empty-msg">{mensagem_vazio}</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-side">
        <!-- Formulário de Edição -->
        <div class="card shadow">
            <div class="card-header">
                <h2><i class="fas fa-{icone_form}"></i> {titulo_form}</h2>
            </div>
            <div class="card-body">
                <form method="post" id="{form_id}">
                    <?= csrf_input() ?>
                    
                    <!-- Campos do Formulário -->
                    <div class="form-group">
                        <label>{label_campo1}:</label>
                        <input type="text" name="{nome_campo1}" id="{id_campo1}" placeholder="{placeholder_campo1}" required>
                    </div>
                    
                    <!-- Mais campos... -->
                    
                    <button type="submit" name="save_{modulo}" class="btn-full btn-primary">
                        <i class="fas fa-save"></i> {texto_botao}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
function fillForm(data) {
    // Preencher formulário com dados
    Object.keys(data).forEach(key => {
        const element = document.getElementById(key);
        if (element) {
            element.value = data[key];
        }
    });
}
</script>

<!-- CSS -->
<style>
/* Estilos universais */
.admin-header { margin-bottom: 2rem; border-bottom: 2px solid var(--brand); padding-bottom: 1rem; }
.subtitle { color: var(--muted); margin-top: 0.5rem; }
.grid-container { display: grid; grid-template-columns: 1fr 350px; gap: 2rem; }
.card { background: var(--card); border-radius: 12px; border: 1px solid var(--border); }
.card-header { background: rgba(0,0,0,0.03); padding: 1rem; border-bottom: 1px solid var(--border); }
.card-body { padding: 1.5rem; }
.admin-table { width: 100%; border-collapse: collapse; }
.admin-table th { text-align: left; padding: 1rem; background: var(--bg); font-size: 0.8rem; }
.admin-table td { padding: 1rem; border-bottom: 1px solid var(--border); }
.form-group { margin-bottom: 1rem; }
.form-group label { display: block; margin-bottom: 0.4rem; font-weight: 600; }
.form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px; }
.btn-primary { background: var(--brand); color: white; border: none; padding: 12px; border-radius: 6px; cursor: pointer; }
.btn-full { width: 100%; }
.btn-icon { background: none; border: 1px solid var(--border); padding: 8px; border-radius: 6px; cursor: pointer; }
@media (max-width: 1024px) { .grid-container { grid-template-columns: 1fr; } }
</style>
```

## 🎯 Template Universal: Página SEO

### 📋 Estrutura Base SEO
```php
<?php if (!defined('APP')) { http_response_code(403); exit('Acesso direto negado'); }
require_once __DIR__ . '/../../config/security.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    csrf_require();
    if (isset($_POST['save_seo'])) {
        $stmt = db()->prepare("INSERT INTO {prefixo}_seo_config (page_slug, title, description, keywords, og_image) 
            VALUES (:slug, :title, :desc, :keys, :img) 
            ON DUPLICATE KEY UPDATE title=VALUES(title), description=VALUES(description), keywords=VALUES(keywords), og_image=VALUES(og_image)");
        
        $stmt->execute([
            ':slug' => $_POST['page_slug'],
            ':title' => $_POST['title'],
            ':desc' => $_POST['description'],
            ':keys' => $_POST['keywords'],
            ':img' => $_POST['og_image'] ?: null
        ]);
        echo '<div class="alert alert-success"><i class="fas fa-search"></i> SEO atualizado!</div>';
    }
}

$seos = db()->query("SELECT * FROM {prefixo}_seo_config")->fetchAll();
?>

<!-- HTML Structure SEO -->
<div class="admin-header">
    <h1><i class="fas fa-bullhorn"></i> Otimização de SEO por Página</h1>
    <p class="subtitle">Gerencie como seu site aparece no Google e redes sociais para cada página específica.</p>
</div>

<div class="grid-container">
    <div class="col-main">
        <div class="card shadow">
            <div class="card-header">
                <h2><i class="fas fa-globe"></i> Configurações Ativas</h2>
            </div>
            <div class="card-body">
                <?php if ($seos): ?>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Página (Slug)</th>
                                <th>Título SEO</th>
                                <th>Descrição</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($seos as $s): ?>
                                <tr>
                                    <td><code><?= htmlspecialchars($s['page_slug']) ?></code></td>
                                    <td><strong><?= htmlspecialchars($s['title']) ?></strong></td>
                                    <td><small><?= mb_strimwidth(htmlspecialchars($s['description']), 0, 80, "...") ?></small></td>
                                    <td>
                                        <button class="btn-icon" onclick="fillSeo(<?= htmlspecialchars(json_encode($s)) ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="empty-msg">Nenhuma configuração SEO encontrada.</p>
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
                <form method="post">
                    <?= csrf_input() ?>
                    <div class="form-group">
                        <label>Identificador da Página (Slug):</label>
                        <input type="text" name="page_slug" id="seo_slug" placeholder="home, menu, contato..." required>
                    </div>
                    <div class="form-group">
                        <label>Título da Aba (Title Tag):</label>
                        <input type="text" name="title" id="seo_title" placeholder="Título para SEO" required>
                    </div>
                    <div class="form-group">
                        <label>Meta Descrição:</label>
                        <textarea name="description" id="seo_desc" rows="4" placeholder="Descrição para motores de busca..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Palavras-chave (separadas por vírgula):</label>
                        <input type="text" name="keywords" id="seo_keys" placeholder="palavra1, palavra2, palavra3">
                    </div>
                    <div class="form-group">
                        <label>URL da Imagem Social (OpenGraph):</label>
                        <input type="text" name="og_image" id="seo_img" placeholder="https://exemplo.com/social.jpg">
                    </div>
                    <button type="submit" name="save_seo" class="btn-full btn-primary">
                        <i class="fas fa-save"></i> Publicar SEO
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function fillSeo(data) {
    document.getElementById('seo_slug').value = data.page_slug;
    document.getElementById('seo_title').value = data.title;
    document.getElementById('seo_desc').value = data.description;
    document.getElementById('seo_keys').value = data.keywords;
    document.getElementById('seo_img').value = data.og_image || '';
}
</script>
```

## 🔧 Parâmetros de Configuração

### 📋 Variáveis Universal
| Variável | Descrição | Exemplo |
|----------|-----------|---------|
| `{prefixo}` | Prefixo da tabela | `pizzaria`, `loja`, `blog` |
| `{modulo}` | Nome do módulo | `layout`, `produto`, `usuario` |
| `{tabela}` | Nome da tabela | `layout_config`, `seo_config` |
| `{icone}` | Ícone FontAwesome | `fa-paint-brush`, `fa-bullhorn` |
| `{titulo_pagina}` | Título da página | "Configuração de Layout" |

### 🎨 Campos Customizáveis
- **Layout:** cor_fundo, cor_texto, fonte_familia, etc.
- **SEO:** title, description, keywords, og_image
- **Produto:** nome, preco, categoria, descricao
- **Usuario:** nome, email, tipo, permissao

## 🚀 Como Usar

### 1. Para LLMs
1. **Leitura Obrigatória:** Ler este arquivo antes de criar qualquer página admin
2. **Identificar Tipo:** Determinar se é página de configuração ou SEO
3. **Aplicar Template:** Usar o template correspondente
4. **Customizar:** Substituir variáveis conforme necessário

### 2. Para Desenvolvedores
1. **Copiar Template:** Escolher o template base
2. **Ajustar Parâmetros:** Modificar variáveis `{...}`
3. **Criar Tabela:** Usar SQL padrão
4. **Integrar:** Adicionar ao sistema de menu

### 3. Para Qualquer Site
1. **Adaptar Prefixo:** Mudar `{prefixo}` para nome do projeto
2. **Personalizar Campos:** Adicionar/remover campos específicos
3. **Ajustar Design:** Modificar CSS conforme branding
4. **Implementar:** Integrar ao sistema existente

## 📋 Checklist de Validação

### ✅ Antes de Entregar
- [ ] Leitura completa deste documento
- [ ] Template aplicado corretamente
- [ ] Variáveis substituídas
- [ ] Segurança CSRF implementada
- [ ] Permissões verificadas
- [ ] Responsividade testada
- [ ] Acessibilidade WCAG AA

### ✅ Funcionalidades Essenciais
- [ ] Listagem de dados
- [ ] Formulário de edição
- [ ] Validação de entrada
- [ ] Feedback visual (toast/alert)
- [ ] Navegação intuitiva
- [ ] Design responsivo

## 🎯 Exemplos Práticos

### E-commerce
```php
// Produto Config
{prefixo} = 'loja'
{modulo} = 'produto'
{tabela} = 'loja_produto_config'
```

### Blog
```php
// Artigo SEO
{prefixo} = 'blog'
{modulo} = 'artigo'
{tabela} = 'blog_seo_config'
```

### Sistema SaaS
```php
// Configuração Sistema
{prefixo} = 'saas'
{modulo} = 'sistema'
{tabela} = 'saas_layout_config'
```

---

**🚨 IMPORTANTE:** Este documento deve ser lido OBRIGATORIAMENTE antes de criar qualquer página administrativa em qualquer projeto!
