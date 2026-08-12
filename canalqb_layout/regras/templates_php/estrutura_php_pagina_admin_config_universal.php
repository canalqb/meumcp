<?php
/**
 * Template Universal: Página Administrativa de Configuração
 * 
 * Este template serve como base para criar páginas de configuração
 * em qualquer tipo de site, seguindo o padrão do projeto pizzaria
 * 
 * @version 1.0
 * @author Sistema IA
 * 
 * Como usar:
 * 1. Copiar este arquivo para views/admin/{nome}_config.php
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
    if (isset($_POST['save_{modulo}'])) {
        $stmt = db()->prepare("INSERT INTO {tabela} ({campos}) 
            VALUES ({placeholders}) 
            ON DUPLICATE KEY UPDATE {updates}");
        
        $stmt->execute([
            // Array de dados dinâmico
        ]);
        echo '<div class="alert alert-success"><i class="fas fa-check-circle"></i> {mensagem_sucesso}</div>';
    }
}

$dados = db()->query("SELECT * FROM {tabela}")->fetchAll();

// Configurações do módulo (personalizar conforme necessidade)
$config_modulo = [
    'nome' => '{nome_modulo}',
    'titulo' => '{titulo_pagina}',
    'descricao' => '{descricao_pagina}',
    'icone' => '{icone_principal}',
    'prefixo_tabela' => '{prefixo_tabela}',
    'campos' => [
        // Definir campos dinamicamente
    ]
];
?>

<div class="admin-header">
    <h1><i class="fas fa-{icone_principal}"></i> <?php echo $config_modulo['titulo']; ?></h1>
    <p class="subtitle"><?php echo $config_modulo['descricao']; ?></p>
</div>

<div class="grid-container">
    <div class="col-main">
        <div class="card shadow">
            <div class="card-header">
                <h2><i class="fas fa-{icone_lista}"></i> {titulo_lista}</h2>
            </div>
            <div class="card-body">
                <?php if ($dados): ?>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>{coluna1_titulo}</th>
                                    <th>{coluna2_titulo}</th>
                                    <th>{coluna3_titulo}</th>
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
                                            <button class="btn-icon" onclick="fillForm(<?php echo htmlspecialchars(json_encode($item)); ?>)" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <?php if (isset($item['id'])): ?>
                                                <button class="btn-icon" onclick="deleteItem(<?php echo $item['id']; ?>)" title="Excluir" style="color: #dc3545;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-{icone_vazio}"></i>
                        <p>{mensagem_vazio}</p>
                        <small>{mensagem_dica}</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-side">
        <div class="card shadow">
            <div class="card-header">
                <h2><i class="fas fa-{icone_form}"></i> {titulo_form}</h2>
            </div>
            <div class="card-body">
                <form method="post" id="{form_id}" class="form-config">
                    <?php echo csrf_input(); ?>
                    
                    <!-- Campo Principal (Identificador) -->
                    <div class="form-group">
                        <label for="campo_principal">
                            <i class="fas fa-{icone_campo_principal}"></i> {label_campo_principal}
                        </label>
                        <div class="input-group">
                            <input type="text" 
                                   name="{nome_campo_principal}" 
                                   id="{id_campo_principal}" 
                                   placeholder="{placeholder_campo_principal}" 
                                   required
                                   class="form-control">
                            <button type="button" class="btn-help" onclick="showHelp('{nome_campo_principal}')" title="Ajuda">
                                <i class="fas fa-question-circle"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Campos de Configuração -->
                    <div class="config-section">
                        <h3><i class="fas fa-palette"></i> Aparência</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="cor_fundo">Cor de Fundo:</label>
                                <div class="color-input-group">
                                    <input type="color" name="cor_fundo" id="cor_fundo" value="#ffffff" class="color-picker">
                                    <input type="text" name="cor_fundo_hex" id="cor_fundo_hex" value="#ffffff" class="color-hex" placeholder="#ffffff">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cor_texto">Cor do Texto:</label>
                                <div class="color-input-group">
                                    <input type="color" name="cor_texto" id="cor_texto" value="#333333" class="color-picker">
                                    <input type="text" name="cor_texto_hex" id="cor_texto_hex" value="#333333" class="color-hex" placeholder="#333333">
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="fonte_familia">Fonte:</label>
                                <select name="fonte_familia" id="fonte_familia" class="form-control">
                                    <option value="">Padrão do Sistema</option>
                                    <option value="Arial, sans-serif">Arial</option>
                                    <option value="Helvetica, sans-serif">Helvetica</option>
                                    <option value="Georgia, serif">Georgia</option>
                                    <option value="Times New Roman, serif">Times New Roman</option>
                                    <option value="Courier New, monospace">Courier New</option>
                                    <option value="Verdana, sans-serif">Verdana</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="fonte_tamanho">Tamanho:</label>
                                <input type="text" name="fonte_tamanho" id="fonte_tamanho" placeholder="16px" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="borda_raio">Border Radius:</label>
                            <div class="slider-group">
                                <input type="range" name="borda_raio" id="borda_raio" min="0" max="50" value="8" class="slider">
                                <span id="borda_raio_value">8px</span>
                            </div>
                        </div>
                    </div>

                    <!-- CSS Personalizado -->
                    <div class="config-section">
                        <h3><i class="fas fa-code"></i> CSS Personalizado</h3>
                        <div class="form-group">
                            <label for="custom_css">CSS Adicional:</label>
                            <textarea name="custom_css" 
                                      id="custom_css" 
                                      rows="6" 
                                      placeholder="/* CSS personalizado */&#10;padding: 10px;&#10;border: 2px solid #ccc;"
                                      class="form-control code-editor"></textarea>
                            <div class="editor-toolbar">
                                <button type="button" class="btn-tool" onclick="formatCSS()" title="Formatar CSS">
                                    <i class="fas fa-magic"></i>
                                </button>
                                <button type="button" class="btn-tool" onclick="validateCSS()" title="Validar CSS">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button type="button" class="btn-tool" onclick="clearCSS()" title="Limpar">
                                    <i class="fas fa-eraser"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Área de Preview -->
                    <div class="config-section">
                        <h3><i class="fas fa-eye"></i> Pré-visualização</h3>
                        <div class="preview-container">
                            <div id="preview-element" class="preview-element">
                                {texto_preview}
                            </div>
                        </div>
                        <div class="preview-controls">
                            <button type="button" class="btn-preview" onclick="resetPreview()">
                                <i class="fas fa-undo"></i> Resetar
                            </button>
                            <button type="button" class="btn-preview" onclick="togglePreviewMode()">
                                <i class="fas fa-exchange-alt"></i> Modo
                            </button>
                        </div>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="form-actions">
                        <button type="submit" name="save_{modulo}" class="btn-primary btn-full">
                            <i class="fas fa-save"></i> {texto_botao_salvar}
                        </button>
                        <button type="button" class="btn-secondary btn-full" onclick="resetForm()">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Card de Dicas -->
        <div class="card shadow card-tips">
            <div class="card-header">
                <h3><i class="fas fa-lightbulb"></i> Dicas Rápidas</h3>
            </div>
            <div class="card-body">
                <ul class="tips-list">
                    <li><i class="fas fa-check text-success"></i> Use cores com bom contraste para acessibilidade</li>
                    <li><i class="fas fa-check text-success"></i> Teste diferentes tamanhos de fonte</li>
                    <li><i class="fas fa-check text-success"></i> Valide o CSS antes de salvar</li>
                    <li><i class="fas fa-check text-success"></i> Use o preview para visualizar mudanças</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
// Variáveis de configuração (personalizar)
const configModulo = {
    nome: '<?php echo $config_modulo['nome']; ?>',
    tabela: '{tabela}',
    prefixo: '{prefixo_tabela}'
};

// Funções principais
function fillForm(data) {
    // Preencher formulário com dados
    Object.keys(data).forEach(key => {
        const element = document.getElementById(key);
        if (element) {
            element.value = data[key];
            
            // Trigger para atualizar elementos relacionados
            if (element.type === 'color') {
                updateColorHex(element);
            } else if (element.type === 'range') {
                updateSliderValue(element);
            }
        }
    });
    
    // Atualizar preview
    updatePreview();
    
    // Scroll para o formulário
    document.getElementById('{form_id}').scrollIntoView({ behavior: 'smooth' });
}

function deleteItem(id) {
    if (confirm('Tem certeza que deseja excluir este item?')) {
        // Implementar exclusão
        window.location.href = `?page={modulo}_config&action=delete&id=${id}`;
    }
}

function resetForm() {
    document.getElementById('{form_id}').reset();
    updatePreview();
}

function updatePreview() {
    const preview = document.getElementById('preview-element');
    
    // Obter valores do formulário
    const bgColor = document.getElementById('cor_fundo').value;
    const textColor = document.getElementById('cor_texto').value;
    const fontFamily = document.getElementById('fonte_familia').value;
    const fontSize = document.getElementById('fonte_tamanho').value;
    const borderRadius = document.getElementById('borda_raio').value;
    const customCSS = document.getElementById('custom_css').value;
    
    // Aplicar estilos ao preview
    preview.style.backgroundColor = bgColor;
    preview.style.color = textColor;
    preview.style.fontFamily = fontFamily || 'inherit';
    preview.style.fontSize = fontSize || '16px';
    preview.style.borderRadius = borderRadius + 'px';
    
    // Aplicar CSS personalizado
    if (customCSS) {
        try {
            // Aplicar CSS personalizado de forma segura
            const styleSheet = document.createElement('style');
            styleSheet.textContent = `#preview-element { ${customCSS} }`;
            document.head.appendChild(styleSheet);
        } catch (error) {
            console.warn('CSS personalizado inválido:', error);
        }
    }
}

// Funções auxiliares
function updateColorHex(colorInput) {
    const hexInput = document.getElementById(colorInput.name + '_hex');
    if (hexInput) {
        hexInput.value = colorInput.value;
    }
}

function updateSliderValue(slider) {
    const valueSpan = document.getElementById(slider.name + '_value');
    if (valueSpan) {
        valueSpan.textContent = slider.value + 'px';
    }
}

function formatCSS() {
    const textarea = document.getElementById('custom_css');
    // Implementar formatação CSS básica
    let css = textarea.value;
    css = css.replace(/;/g, ';\n');
    css = css.replace(/{/g, ' {\n  ');
    css = css.replace(/}/g, '\n}\n');
    textarea.value = css;
}

function validateCSS() {
    const css = document.getElementById('custom_css').value;
    // Implementar validação CSS básica
    try {
        // Tentar aplicar CSS a um elemento oculto
        const testElement = document.createElement('div');
        testElement.style.cssText = css;
        document.body.appendChild(testElement);
        document.body.removeChild(testElement);
        
        showToast('CSS válido!', 'success');
    } catch (error) {
        showToast('CSS inválido: ' + error.message, 'error');
    }
}

function clearCSS() {
    if (confirm('Limpar CSS personalizado?')) {
        document.getElementById('custom_css').value = '';
        updatePreview();
    }
}

function resetPreview() {
    const preview = document.getElementById('preview-element');
    preview.style.cssText = '';
    updatePreview();
}

function togglePreviewMode() {
    const preview = document.getElementById('preview-element');
    // Implementar diferentes modos de preview
    preview.classList.toggle('preview-mode-card');
    preview.classList.toggle('preview-mode-button');
}

function showHelp(fieldName) {
    // Implementar sistema de ajuda
    showToast(`Ajuda para ${fieldName}: ${getHelpText(fieldName)}`, 'info');
}

function getHelpText(fieldName) {
    const helpTexts = {
        'campo_principal': 'Este é o identificador principal do elemento',
        'cor_fundo': 'Cor de fundo do elemento em formato hexadecimal',
        'cor_texto': 'Cor do texto do elemento em formato hexadecimal',
        'fonte_familia': 'Família de fontes para o texto',
        'fonte_tamanho': 'Tamanho da fonte (ex: 16px, 1.2rem)',
        'borda_raio': 'Arredondamento das bordas em pixels',
        'custom_css': 'CSS adicional para personalização avançada'
    };
    return helpTexts[fieldName] || 'Ajuda não disponível';
}

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
    // Sincronizar inputs de cor
    document.querySelectorAll('input[type="color"]').forEach(input => {
        input.addEventListener('input', function() {
            updateColorHex(this);
            updatePreview();
        });
    });
    
    // Sincronizar inputs hex
    document.querySelectorAll('.color-hex').forEach(input => {
        input.addEventListener('input', function() {
            const colorInput = document.getElementById(this.name.replace('_hex', ''));
            if (colorInput && /^#[0-9A-F]{6}$/i.test(this.value)) {
                colorInput.value = this.value;
                updatePreview();
            }
        });
    });
    
    // Sincronizar sliders
    document.querySelectorAll('.slider').forEach(slider => {
        slider.addEventListener('input', function() {
            updateSliderValue(this);
            updatePreview();
        });
    });
    
    // Atualizar preview em mudanças
    document.querySelectorAll('#{form_id} input, #{form_id} select, #{form_id} textarea').forEach(input => {
        input.addEventListener('change', updatePreview);
    });
    
    // Preview inicial
    updatePreview();
});
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
}

.card-header h2, .card-header h3 {
    margin: 0;
    color: var(--brand);
    display: flex;
    align-items: center;
    gap: 0.5rem;
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
}

.input-group .form-control {
    flex: 1;
}

.btn-help {
    background: var(--bg);
    border: 1px solid var(--border);
    padding: 12px;
    border-radius: 8px;
    cursor: pointer;
    color: var(--muted);
    transition: all 0.3s ease;
}

.btn-help:hover {
    background: var(--brand);
    color: white;
    border-color: var(--brand);
}

.form-row {
    display: grid; 
    grid-template-columns: 1fr 1fr; 
    gap: 1rem; 
}

.config-section {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: var(--bg);
    border-radius: 8px;
    border: 1px solid var(--border);
}

.config-section h3 {
    margin: 0 0 1rem 0;
    color: var(--brand);
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--border);
}

.color-input-group {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.color-picker {
    width: 50px;
    height: 40px;
    border: 2px solid var(--border);
    border-radius: 8px;
    cursor: pointer;
}

.color-hex {
    flex: 1;
}

.slider-group {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.slider {
    flex: 1;
    height: 6px;
    border-radius: 3px;
    background: var(--border);
    outline: none;
    -webkit-appearance: none;
}

.slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: var(--brand);
    cursor: pointer;
}

.slider::-moz-range-thumb {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: var(--brand);
    cursor: pointer;
    border: none;
}

.code-editor {
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
    line-height: 1.5;
    resize: vertical;
}

.editor-toolbar {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.btn-tool {
    background: var(--bg);
    border: 1px solid var(--border);
    padding: 8px 12px;
    border-radius: 6px;
    cursor: pointer;
    color: var(--muted);
    transition: all 0.3s ease;
}

.btn-tool:hover {
    background: var(--brand);
    color: white;
    border-color: var(--brand);
}

.preview-container {
    background: white;
    border: 2px dashed var(--border);
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    min-height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.preview-element {
    transition: all 0.3s ease;
    max-width: 100%;
    word-wrap: break-word;
    padding: 1rem;
    border-radius: 4px;
}

.preview-element.preview-mode-card {
    background: var(--card);
    border: 1px solid var(--border);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.preview-element.preview-mode-button {
    background: var(--brand);
    color: white;
    padding: 12px 24px;
    border-radius: 6px;
    cursor: pointer;
    display: inline-block;
}

.preview-controls {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
    justify-content: center;
}

.btn-preview {
    background: var(--bg);
    border: 1px solid var(--border);
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    color: var(--muted);
    transition: all 0.3s ease;
}

.btn-preview:hover {
    background: var(--brand);
    color: white;
    border-color: var(--brand);
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

.empty-state p {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

.card-tips {
    background: linear-gradient(135deg, #fffde7 0%, #fff9c4 100%);
    border-color: #fdd835;
}

.tips-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.tips-list li {
    padding: 0.5rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
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
    
    .preview-controls {
        flex-direction: column;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .config-section {
        padding: 1rem;
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

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeIn 0.5s ease;
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
