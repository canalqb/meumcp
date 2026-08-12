<?php
/**
 * Master Rules - Sistema de Geração de Páginas Administrativas
 * 
 * Este arquivo contém prompts e templates para criação de páginas administrativas
 * com sistema completo de configuração de CSS, seguindo as regras do master_rules.md
 * 
 * @version 1.0
 * @author Sistema IA
 */

/**
 * 📋 PROMPT PARA CRIAÇÃO DE PÁGINAS ADMINISTRATIVAS
 * 
 * Nome do prompt: prompt_criacao_pagina_admin_css
 * 
 * Descrição: Prompt para IA criar páginas administrativas completas com sistema
 * de configuração de CSS similar à página de configuração de layout
 */
return [
    'prompt_criacao_pagina_admin_css' => [
        'nome' => 'prompt_criacao_pagina_admin_css',
        'descricao' => 'Prompt para criação de páginas administrativas com sistema de configuração CSS',
        'prompt' => "
# 🎯 MISSÃO: Criar Página Administrativa com Sistema de Configuração CSS

Você é um desenvolvedor PHP especialista em criar páginas administrativas completas com sistema de configuração de CSS dinâmico, seguindo as regras do master_rules.md.

## 📋 REQUISITOS OBRIGATÓRIOS:

### 1. Estrutura da Página
- Criar arquivo PHP completo em `views/admin/`
- Seguir padrão de injeção (apenas index.php tem html/head/body)
- Usar semântica W3C (main, section, article)
- Implementar acessibilidade WCAG AA + ARIA
- Proteção CSRF em todos os formulários

### 2. Sistema de Configuração CSS
- Implementar autocomplete personalizado para classes CSS
- Buscar classes em: styles.css + tags <style> da página
- Parser CSS completo para extrair propriedades
- Pré-visualização em tempo real
- Salvamento em banco de dados (tabela layout_config)

### 3. Funcionalidades Essenciais
- Lista de classes configuradas (tabela admin)
- Formulário de edição/criação de estilos
- Preview dinâmico com atualização em tempo real
- Carregamento automático de CSS original
- Sistema de toast notifications

### 4. Design e UX
- FontAwesome 6 para todos os ícones
- Rich Aesthetics (sombras, gradientes)
- Contraste acessível (fundo escuro = fonte clara)
- Layout responsivo e moderno
- Cores consistentes com tema do sistema

### 5. Integração com Sistema
- Verificação de permissão (tipo: administrador)
- Integração com variáveis de sistema (APP_URL)
- Uso de helpers existentes (csrf_input, db())
- Segurança contra XSS e SQL Injection

## 🎨 TEMPLATE DE REFERÊNCIA:

Basear-se na estrutura de `views/admin/config.php`:
- Header com título e subtítulo
- Grid layout (col-main + col-side)
- Tabela de configurações existentes
- Formulário lateral com autocomplete
- Sistema de preview

## 📁 NOMENCLATURA OBRIGATÓRIA:

### Arquivo PHP:
- `views/admin/nova_pagina_config.php`
- Seguir padrão snake_case para nomes
- Incluir comentários PHPDoc

### Classes CSS:
- Prefixo específico da página (ex: .config-pagina-*)
- Nomes descritivos em inglês
- Organização lógica de componentes

## 🚫 PROIBIÇÕES ESPECÍFICAS:
- ❌ NUNCA usar alert() - usar showToast()
- ❌ NUNCA usar tipo 'admin' - usar 'administrador'
- ❌ NUNCA ignorar validação CSRF
- ❌ NUNCA usar HTML sem semântica
- ❌ NUNCA ignorar acessibilidade

## ✅ VALIDAÇÃO FINAL:
- [ ] Leitura 100% de regras/ (master_rules.md)
- [ ] Conformidade com prompts_php/
- [ ] Segurança CSRF implementada
- [ ] Acessibilidade WCAG AA
- [ ] Design Rich Aesthetics
- [ ] Sistema CSS completo
- [ ] Integração com banco de dados

## 📤 ENTREGA:
Entregar o código PHP completo com:
1. Estrutura da página
2. Sistema de configuração CSS
3. Banco de dados (se necessário)
4. CSS completo
5. JavaScript dinâmico
6. Documentação

---

# 💡 INSTRUÇÕES DE USO:
1. Ler recursivamente pasta regras/
2. Aplicar todas as diretrizes do master_rules.md
3. Seguir templates_php/ para estrutura
4. Validar com checklists de prompts_php/
5. Entregar somente após conformidade total
"
    ],
    
    /**
     * 🎨 TEMPLATE PARA PÁGINAS ADMINISTRATIVAS
     * 
     * Nome do template: template_pagina_admin_css_config
     * 
     * Descrição: Template base para páginas de configuração de CSS
     */
    'template_pagina_admin_css_config' => [
        'nome' => 'template_pagina_admin_css_config',
        'descricao' => 'Template base para páginas administrativas com configuração CSS',
        'template' => "
<?php if (!defined('APP')) { http_response_code(403); exit('Acesso direto negado'); }
require_once __DIR__ . '/../../config/security.php';

// Verificar permissão de administrador
if (currentUserRole() !== 'administrador') {
    header('Location: index.php?page=login');
    exit;
}

// Processamento do formulário
if ((isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '') === 'POST') {
    csrf_require();
    if (isset($_POST['save_layout'])) {
        $stmt = db()->prepare("INSERT INTO layout_config (elemento_classe, cor_fundo, cor_texto, fonte_familia, fonte_tamanho, borda_raio, custom_css) 
            VALUES (:cls, :bg, :tx, :ff, :fz, :br, :css) 
            ON DUPLICATE KEY UPDATE cor_fundo=VALUES(cor_fundo), cor_texto=VALUES(cor_texto), fonte_familia=VALUES(fonte_familia), 
            fonte_tamanho=VALUES(fonte_tamanho), borda_raio=VALUES(borda_raio), custom_css=VALUES(custom_css)");
        
        $stmt->execute([
            ':cls' => $_POST['elemento_classe'],
            ':bg' => $_POST['cor_fundo'] ?: null,
            ':tx' => $_POST['cor_texto'] ?: null,
            ':ff' => $_POST['fonte_familia'] ?: null,
            ':fz' => $_POST['fonte_tamanho'] ?: null,
            ':br' => $_POST['borda_raio'] ?: null,
            ':css' => $_POST['custom_css'] ?: null
        ]);
        echo '<div class=\"alert alert-success\">Configuração de Layout salva!</div>';
    }
}

$configs = db()->query("SELECT * FROM layout_config")->fetchAll();

// Classes sugeridas para o admin
$sugestoes = [
    '.btn-primary' => 'Botão Principal',
    '.btn-secondary' => 'Botão Secundário',
    '.card' => 'Card Genérico',
    '.admin-header' => 'Cabeçalho Administrativo',
    '.form-group' => 'Grupo de Formulário',
    '.admin-table' => 'Tabela Administrativa'
];
?>

<div class=\"admin-header\">
    <h1><i class=\"fas fa-paint-brush\"></i> Configuração de Estilos - [NOME_DA_PAGINA]</h1>
    <p class=\"subtitle\">Personalize cores, fontes e estilos dos componentes do sistema.</p>
</div>

<div class=\"grid-container\">
    <div class=\"col-main\">
        <div class=\"card shadow\">
            <div class=\"card-header\">
                <h2><i class=\"fas fa-layer-group\"></i> Estilos Configurados</h2>
            </div>
            <div class=\"card-body\">
                <?php if ($configs): ?>
                    <table class=\"admin-table\">
                        <thead>
                            <tr>
                                <th>Classe/Seletor</th>
                                <th>Visual</th>
                                <th>Fonte</th>
                                <th>CSS Extra</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($configs as $c): ?>
                                <tr>
                                    <td><code><?= htmlspecialchars($c['elemento_classe']) ?></code></td>
                                    <td>
                                        <div style=\"width: 24px; height: 24px; background: <?= $c['cor_fundo'] ?>; border: 1px solid #ccc; border-radius: <?= $c['borda_raio'] ?>; display: inline-block;\"></div>
                                        <span style=\"color: <?= $c['cor_texto'] ?>;\">Aa</span>
                                    </td>
                                    <td><?= htmlspecialchars($c['fonte_familia']) ?> (<?= $c['fonte_tamanho'] ?>)</td>
                                    <td><small><?= $c['custom_css'] ? 'Sim' : 'Não' ?></small></td>
                                    <td>
                                        <button class=\"btn-icon\" onclick=\"fillForm(<?= htmlspecialchars(json_encode($c)) ?>)\"><i class=\"fas fa-edit\"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class=\"empty-msg\">Nenhuma regra de estilo personalizada ainda.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class=\"col-side\">
        <div class=\"card shadow\">
            <div class=\"card-header\">
                <h2><i class=\"fas fa-plus-square\"></i> Editar/Novo Estilo</h2>
            </div>
            <div class=\"card-body\">
                <form method=\"post\" id=\"layoutForm\">
                    <?= csrf_input() ?>
                    <div class=\"form-group\">
                        <label>Classe CSS:</label>
                        <div class=\"autocomplete-container\">
                            <input type=\"text\" name=\"elemento_classe\" id=\"f_classe\" placeholder=\".classe-exemplo\" required autocomplete=\"off\">
                            <div class=\"autocomplete-dropdown\" id=\"class-dropdown\">
                                <div class=\"dropdown-content\" id=\"class-list\">
                                    <!-- Classes serão carregadas aqui -->
                                </div>
                            </div>
                        </div>
                        <button type=\"button\" class=\"btn-secondary btn-sm\" onclick=\"loadOriginalCSS()\" style=\"margin-top: 0.5rem;\">
                            <i class=\"fas fa-search\"></i> Carregar CSS Original
                        </button>
                    </div>
                    
                    <!-- Área de Pré-visualização -->
                    <div class=\\\"preview-section\\\" id=\\\"preview-section\\\" style=\\\"display: none;\\\">
                        <h4><i class=\\\"fas fa-eye\\\"></i> Pré-visualização</h4>
                        <div class=\\\"preview-container\\\">
                            <div id=\\\"preview-element\\\" class=\\\"preview-element\\\">Exemplo de Elemento</div>
                        </div>
                        <div class=\\\"preview-info\\\">
                            <div class=\\\"info-item\\\">
                                <strong>CSS Original:</strong>
                                <code id=\\\"original-css\\\">Carregando...</code>
                            </div>
                            <div class=\\\"info-item\\\">
                                <strong>Arquivo:</strong>
                                <span id=\\\"css-file\\\">Detectando...</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class=\\\"form-row\\\">
                        <div class=\\\"form-group\\\">
                            <label>Fundo:</label>
                            <input type=\\\"color\\\" name=\\\"cor_fundo\\\" id=\\\"f_bg\\\" value=\\\"#ffffff\\\">
                        </div>
                        <div class=\\\"form-group\\\">
                            <label>Texto:</label>
                            <input type=\\\"color\\\" name=\\\"cor_texto\\\" id=\\\"f_tx\\\" value=\\\"#333333\\\">
                        </div>
                    </div>
                    <div class=\\\"form-group\\\">
                        <label>Fonte (Família):</label>
                        <input type=\\\"text\\\" name=\\\"fonte_familia\\\" id=\\\"f_ff\\\" placeholder=\\\"Arial, sans-serif\\\">
                    </div>
                    <div class=\\\"form-row\\\">
                        <div class=\\\"form-group\\\">
                            <label>Tamanho:</label>
                            <input type=\\\"text\\\" name=\\\"fonte_tamanho\\\" id=\\\"f_fz\\\" placeholder=\\\"16px\\\">
                        </div>
                        <div class=\\\"form-group\\\">
                            <label>Border Radius:</label>
                            <input type=\\\"text\\\" name=\\\"borda_raio\\\" id=\\\"f_br\\\" placeholder=\\\"8px\\\">
                        </div>
                    </div>
                    <div class=\\\"form-group\\\">
                        <label>CSS Personalizado Adicional:</label>
                        <textarea name=\\\"custom_css\\\" id=\\\"f_css\\\" style=\\\"width:100%; height:80px;\\\" placeholder=\\\"padding: 10px; border: 2px solid red;\\\"></textarea>
                    </div>
                    <button type=\\\"submit\\\" name=\\\"save_layout\\\" class=\\\"btn-full btn-primary\\\"><i class=\\\"fas fa-save\\\"></i> Salvar Layout</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript para autocomplete e preview -->
<script>
// Sistema de autocomplete e carregamento de CSS
document.addEventListener('DOMContentLoaded', function() {
    const classInput = document.getElementById('f_classe');
    const dropdown = document.getElementById('class-dropdown');
    const classList = document.getElementById('class-list');
    
    // Carregar classes disponíveis
    loadAvailableClasses();
    
    // Event listeners do autocomplete
    classInput.addEventListener('focus', function() {
        showDropdown();
    });
    
    classInput.addEventListener('input', filterClasses);
    classInput.addEventListener('click', function() {
        showDropdown();
        filterClasses();
    });
    
    classInput.addEventListener('blur', hideDropdown);
    
    // Carregar classes do CSS
    async function loadAvailableClasses() {
        try {
            // Carregar CSS principal
            const cssResponse = await fetch('<?= APP_URL ?>/css/styles.css');
            const cssText = await cssResponse.text();
            
            // Extrair classes
            const classes = extractAllClasses(cssText);
            
            // Classes sugeridas
            const suggestedClasses = [
                { name: '.btn-primary', description: 'Botão Principal' },
                { name: '.btn-secondary', description: 'Botão Secundário' },
                { name: '.card', description: 'Card Genérico' },
                { name: '.admin-header', description: 'Cabeçalho Administrativo' },
                { name: '.form-group', description: 'Grupo de Formulário' },
                { name: '.admin-table', description: 'Tabela Administrativa' }
            ];
            
            // Combinar classes
            const allClasses = [...suggestedClasses, ...classes];
            const uniqueClasses = allClasses.filter((item, index, self) => 
                index === self.findIndex(t => t.name === item.name)
            );
            
            uniqueClasses.sort((a, b) => a.name.localeCompare(b.name));
            renderClassList(uniqueClasses);
            
        } catch (error) {
            console.error('Erro ao carregar classes:', error);
        }
    }
    
    // Funções para autocomplete e preview
    function extractAllClasses(cssText) {
        const classRegex = /\\.([a-zA-Z][\\w-]*)/g;
        const classes = [];
        const matches = cssText.match(classRegex);
        
        if (matches) {
            const uniqueClasses = [...new Set(matches)];
            uniqueClasses.forEach(className => {
                classes.push({
                    name: '.' + className,
                    description: 'Classe CSS'
                });
            });
        }
        
        return classes;
    }
    
    function renderClassList(classes) {
        classList.innerHTML = '';
        
        classes.forEach(classItem => {
            const item = document.createElement('div');
            item.className = 'class-item';
            item.innerHTML = `
                <span class=\\\"class-name\\\">\${classItem.name}</span>
                <span class=\\\"class-description\\\">\${classItem.description}</span>
            `;
            
            item.addEventListener('click', function() {
                selectClass(classItem.name);
            });
            
            classList.appendChild(item);
        });
    }
    
    function selectClass(className) {
        classInput.value = className;
        hideDropdown();
        loadOriginalCSS();
    }
    
    function showDropdown() {
        dropdown.classList.add('show');
        const items = classList.querySelectorAll('.class-item');
        items.forEach(item => {
            item.style.display = 'flex';
        });
    }
    
    function hideDropdown() {
        setTimeout(() => {
            dropdown.classList.remove('show');
        }, 200);
    }
    
    function filterClasses() {
        const filter = classInput.value.toLowerCase();
        const items = classList.querySelectorAll('.class-item');
        
        if (filter === '') {
            items.forEach(item => {
                item.style.display = 'flex';
            });
            return;
        }
        
        items.forEach(item => {
            const className = item.querySelector('.class-name').textContent.toLowerCase();
            const description = item.querySelector('.class-description').textContent.toLowerCase();
            
            if (className.includes(filter) || description.includes(filter)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }
    
    // Carregar CSS original
    async function loadOriginalCSS() {
        const className = document.getElementById('f_classe').value.trim();
        
        if (!className) {
            showToast('Digite uma classe CSS primeiro', 'error');
            return;
        }
        
        const previewSection = document.getElementById('preview-section');
        const originalCSS = document.getElementById('original-css');
        const cssFile = document.getElementById('css-file');
        
        previewSection.style.display = 'block';
        originalCSS.textContent = 'Carregando...';
        cssFile.textContent = 'Detectando...';
        
        try {
            const cssResponse = await fetch('<?= APP_URL ?>/css/styles.css');
            const cssText = await cssResponse.text();
            
            // Buscar classe no CSS
            const cssRules = parseCSS(cssText);
            const targetRule = findCSSRule(cssRules, className);
            
            if (targetRule) {
                fillFormFromCSS(targetRule);
                originalCSS.textContent = targetRule.css;
                cssFile.textContent = 'css/styles.css';
                originalCSS.style.color = '#28a745';
                cssFile.style.color = '#28a745';
                applyCSSToPreview(targetRule);
            } else {
                originalCSS.textContent = 'Classe não encontrada';
                cssFile.textContent = 'N/A';
                originalCSS.style.color = '#dc3545';
                cssFile.style.color = '#dc3545';
            }
        } catch (error) {
            console.error('Erro ao carregar CSS:', error);
            originalCSS.textContent = 'Erro ao carregar CSS';
            cssFile.textContent = 'Erro';
            originalCSS.style.color = '#dc3545';
            cssFile.style.color = '#dc3545';
        }
    }
    
    // Funções auxiliares
    function parseCSS(cssText) {
        const rules = [];
        const ruleRegex = /([^{]+)\\{([^}]*)\\}/g;
        let match;
        
        while ((match = ruleRegex.exec(cssText)) !== null) {
            const selector = match[1].trim();
            const properties = match[2].trim();
            
            rules.push({
                selector: selector,
                properties: parseProperties(properties),
                css: match[0]
            });
        }
        
        return rules;
    }
    
    function parseProperties(propText) {
        const props = {};
        const lines = propText.split(';');
        
        lines.forEach(line => {
            const colonIndex = line.indexOf(':');
            if (colonIndex > 0) {
                const prop = line.substring(0, colonIndex).trim();
                const value = line.substring(colonIndex + 1).trim();
                props[prop] = value;
            }
        });
        
        return props;
    }
    
    function findCSSRule(rules, className) {
        let rule = rules.find(r => r.selector === className);
        if (rule) return rule;
        
        rule = rules.find(r => r.selector.includes(className));
        if (rule) return rule;
        
        const cleanClass = className.replace(/^\\./, '');
        rule = rules.find(r => r.selector.includes(cleanClass));
        
        return rule || null;
    }
    
    function fillFormFromCSS(rule) {
        const props = rule.properties;
        
        if (props['background'] || props['background-color']) {
            const bgColor = extractColor(props['background'] || props['background-color']);
            if (bgColor) document.getElementById('f_bg').value = bgColor;
        }
        
        if (props['color']) {
            const textColor = extractColor(props['color']);
            if (textColor) document.getElementById('f_tx').value = textColor;
        }
        
        if (props['font-family']) {
            document.getElementById('f_ff').value = props['font-family'];
        }
        
        if (props['font-size']) {
            document.getElementById('f_fz').value = props['font-size'];
        }
        
        if (props['border-radius']) {
            document.getElementById('f_br').value = props['border-radius'];
        }
    }
    
    function extractColor(colorValue) {
        const hexMatch = colorValue.match(/#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})/);
        if (hexMatch) return hexMatch[0];
        
        const rgbMatch = colorValue.match(/rgb\\((\\d+),\\s*(\\d+),\\s*(\\d+)\\)/);
        if (rgbMatch) {
            const r = parseInt(rgbMatch[1]);
            const g = parseInt(rgbMatch[2]);
            const b = parseInt(rgbMatch[3]);
            return '#' + ((r << 16) | (g << 8) | b).toString(16).padStart(6, '0');
        }
        
        return null;
    }
    
    function applyCSSToPreview(rule) {
        const previewElement = document.getElementById('preview-element');
        
        previewElement.className = 'preview-element';
        previewElement.style.cssText = '';
        
        if (rule.selector.startsWith('.')) {
            previewElement.className = 'preview-element ' + rule.selector.substring(1);
        }
        
        Object.entries(rule.properties).forEach(([prop, value]) => {
            previewElement.style[prop] = value;
        });
        
        if (rule.selector.includes('btn')) {
            previewElement.textContent = 'Botão Exemplo';
            previewElement.style.display = 'inline-block';
            previewElement.style.padding = '10px 20px';
            previewElement.style.textAlign = 'center';
            previewElement.style.cursor = 'pointer';
        } else if (rule.selector.includes('card')) {
            previewElement.textContent = 'Card Exemplo';
            previewElement.style.display = 'block';
            previewElement.style.padding = '20px';
            previewElement.style.margin = '10px 0';
        } else {
            previewElement.textContent = 'Elemento Exemplo';
        }
    }
    
    function fillForm(data) {
        document.getElementById('f_classe').value = data.elemento_classe;
        document.getElementById('f_bg').value = data.cor_fundo || '#ffffff';
        document.getElementById('f_tx').value = data.cor_texto || '#333333';
        document.getElementById('f_ff').value = data.fonte_familia || '';
        document.getElementById('f_fz').value = data.fonte_tamanho || '';
        document.getElementById('f_br').value = data.borda_raio || '';
        document.getElementById('f_css').value = data.custom_css || '';
        
        updatePreview();
    }
    
    function updatePreview() {
        const previewElement = document.getElementById('preview-element');
        
        const bgColor = document.getElementById('f_bg').value;
        const textColor = document.getElementById('f_tx').value;
        const fontFamily = document.getElementById('f_ff').value;
        const fontSize = document.getElementById('f_fz').value;
        const borderRadius = document.getElementById('f_br').value;
        const customCSS = document.getElementById('f_css').value;
        
        previewElement.style.backgroundColor = bgColor;
        previewElement.style.color = textColor;
        previewElement.style.fontFamily = fontFamily;
        previewElement.style.fontSize = fontSize;
        previewElement.style.borderRadius = borderRadius;
        
        if (customCSS) {
            try {
                const props = parseProperties(customCSS);
                Object.entries(props).forEach(([prop, value]) => {
                    previewElement.style[prop] = value;
                });
            } catch (error) {
                console.warn('CSS personalizado inválido:', error);
            }
        }
    }
    
    // Event listeners para atualização em tempo real
    const inputs = ['f_bg', 'f_tx', 'f_ff', 'f_fz', 'f_br', 'f_css'];
    inputs.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('input', updatePreview);
            element.addEventListener('change', updatePreview);
        }
    });
    
    // Fechar dropdown ao clicar fora
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.autocomplete-container')) {
            hideDropdown();
        }
    });
    
    // Sistema de toast
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = \`toast \${type}\`;
        toast.innerHTML = \`
            <i class=\\\"fas fa-\${type === 'success' ? 'check-circle' : 'exclamation-circle'}\\\"></i>
            <span>\${message}</span>
        \`;
        toast.style.cssText = \`
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
            animation: slideIn 0.3s ease;
        \`;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }
});
</script>

<style>
/* Estilos específicos da página */
.admin-header { 
    margin-bottom: 2rem; 
    border-bottom: 2px solid var(--brand); 
    padding-bottom: 1rem; 
}

.subtitle { 
    color: var(--muted); 
    margin-top: 0.5rem; 
}

.grid-container { 
    display: grid; 
    grid-template-columns: 1fr 350px; 
    gap: 2rem; 
}

.card { 
    background: var(--card); 
    border-radius: 12px; 
    border: 1px solid var(--border); 
}

.card-header { 
    background: rgba(0,0,0,0.03); 
    padding: 1rem; 
    border-bottom: 1px solid var(--border); 
}

.card-body { 
    padding: 1.5rem; 
}

.admin-table { 
    width: 100%; 
    border-collapse: collapse; 
}

.admin-table th { 
    text-align: left; 
    padding: 1rem; 
    background: var(--bg); 
    font-size: 0.8rem; 
}

.admin-table td { 
    padding: 1rem; 
    border-bottom: 1px solid var(--border); 
}

.form-group { 
    margin-bottom: 1rem; 
}

.form-group label { 
    display: block; 
    margin-bottom: 0.4rem; 
    font-weight: 600; 
}

.form-group input, .form-group select { 
    width: 100%; 
    padding: 8px; 
    border: 1px solid var(--border); 
    border-radius: 6px; 
}

.form-row { 
    display: grid; 
    grid-template-columns: 1fr 1fr; 
    gap: 1rem; 
}

.btn-primary { 
    background: var(--brand); 
    color: white; 
    border: none; 
    padding: 12px; 
    border-radius: 6px; 
    cursor: pointer; 
}

.btn-secondary { 
    background: #6c757d; 
    color: white; 
    border: none; 
    padding: 8px 16px; 
    border-radius: 4px; 
    cursor: pointer; 
    font-size: 0.85rem; 
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
}

.text-muted { 
    color: #888; 
    font-size: 0.8rem; 
}

/* Estilos do autocomplete */
.autocomplete-container {
    position: relative;
    width: 100%;
}

.autocomplete-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid var(--border);
    border-top: none;
    border-radius: 0 0 6px 6px;
    max-height: 300px;
    overflow-y: auto;
    z-index: 1000;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    display: none;
}

.autocomplete-dropdown.show {
    display: block;
}

.dropdown-content {
    padding: 0;
}

.class-item {
    padding: 12px 16px;
    cursor: pointer;
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.2s ease;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.class-item:hover {
    background: var(--bg);
    color: var(--brand);
}

.class-name {
    font-family: 'Courier New', monospace;
    font-weight: 600;
    font-size: 0.9rem;
}

.class-description {
    font-size: 0.8rem;
    color: var(--muted);
    font-style: italic;
}

/* Estilos da pré-visualização */
.preview-section {
    margin: 1.5rem 0;
    padding: 1rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 1px solid #dee2e6;
    border-radius: 8px;
}

.preview-section h4 {
    margin: 0 0 1rem 0;
    color: var(--brand);
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.preview-container {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 2rem;
    margin-bottom: 1rem;
    text-align: center;
    min-height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.preview-element {
    transition: all 0.3s ease;
    max-width: 100%;
    word-wrap: break-word;
}

.preview-info {
    background: rgba(255, 255, 255, 0.8);
    border-radius: 6px;
    padding: 1rem;
}

.info-item {
    margin-bottom: 0.5rem;
    font-size: 0.85rem;
}

.info-item strong {
    color: var(--text);
    display: block;
    margin-bottom: 0.25rem;
}

.info-item code {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 4px;
    padding: 0.25rem 0.5rem;
    font-family: 'Courier New', monospace;
    font-size: 0.8rem;
    color: #e83e8c;
    display: block;
    word-break: break-all;
    max-height: 100px;
    overflow-y: auto;
}

.info-item span {
    color: #28a745;
    font-weight: 600;
}

/* Responsividade */
@media (max-width: 1024px) { 
    .grid-container { 
        grid-template-columns: 1fr; 
    } 
}

/* Animações */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideOut {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateX(100%);
    }
}
</style>
"
    ]
];

?>
