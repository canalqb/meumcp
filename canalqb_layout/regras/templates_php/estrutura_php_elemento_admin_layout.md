# 🛠️ Estrutura PHP: Elemento Gerenciador de Estilos Dinâmicos (Universal)

Este template define a estrutura para uma página administrativa de customização de CSS em nível de componente, permitindo alterar cores, fontes e bordas de classes específicas diretamente pelo banco de dados.

## 🗄️ Esquema de Banco de Dados (MySQL/MariaDB)

```sql
CREATE TABLE IF NOT EXISTS sys_layout_config (
    elemento_classe VARCHAR(100) PRIMARY KEY,
    cor_fundo VARCHAR(20),
    cor_texto VARCHAR(20),
    fonte_familia VARCHAR(100),
    fonte_tamanho VARCHAR(20),
    borda_raio VARCHAR(20),
    custom_css TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_layout_classe ON sys_layout_config (elemento_classe(191));
```

## 📄 Estrutura da Página (`views/admin/layout_config.php`)

```php
<?php 
if (!defined('APP')) { http_response_code(403); exit('Acesso direto negado'); }
require_once __DIR__ . '/../../config/security.php';

// Processamento
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    csrf_require();
    if (isset($_POST['save_layout'])) {
        $stmt = db()->prepare("INSERT INTO sys_layout_config (elemento_classe, cor_fundo, cor_texto, fonte_familia, fonte_tamanho, borda_raio, custom_css) 
            VALUES (:cls, :bg, :tx, :ff, :fz, :br, :css) 
            ON DUPLICATE KEY UPDATE cor_fundo=VALUES(cor_fundo), cor_texto=VALUES(cor_texto), fonte_familia=VALUES(fonte_familia), 
            fonte_tamanho=VALUES(fonte_tamanho), borda_raio=VALUES(borda_raio), custom_css=VALUES(custom_css)");
        
        $stmt->execute([
            ':cls' => $_POST['elemento_classe'],
            ':bg'  => $_POST['cor_fundo'] ?: null,
            ':tx'  => $_POST['cor_texto'] ?: null,
            ':ff'  => $_POST['fonte_familia'] ?: null,
            ':fz'  => $_POST['fonte_tamanho'] ?: null,
            ':br'  => $_POST['borda_raio'] ?: null,
            ':css' => $_POST['custom_css'] ?: null
        ]);
        echo '<div class="alert alert-success"><i class="fas fa-magic"></i> Estilo salvo com sucesso!</div>';
    }
}

$configs = db()->query("SELECT * FROM sys_layout_config")->fetchAll();
?>

<div class="admin-header">
    <h1><i class="fas fa-paint-brush"></i> Personalização Visual Avançada</h1>
    <p>Ajuste os estilos de componentes específicos do site usando seletores CSS.</p>
</div>

<div class="grid-container">
    <div class="col-main">
        <div class="card shadow">
            <div class="card-header">
                <h2><i class="fas fa-layer-group"></i> Estilos Ativos</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Elemento (Classe/ID)</th>
                                <th>Visual</th>
                                <th>Detalhes</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($configs as $c): ?>
                                <tr>
                                    <td><code><?= htmlspecialchars($c['elemento_classe']) ?></code></td>
                                    <td>
                                        <div class="preview-dot" style="background: <?= $c['cor_fundo'] ?>; color: <?= $c['cor_texto'] ?>; border-radius: <?= $c['borda_raio'] ?>;">Aa</div>
                                    </td>
                                    <td>
                                        <small><?= htmlspecialchars($c['fonte_familia'] ?? 'Padrão') ?> (<?= htmlspecialchars($c['fonte_tamanho'] ?? '-') ?>)</small>
                                    </td>
                                    <td>
                                        <button class="btn-icon" onclick="editStyle(<?= htmlspecialchars(json_encode($c)) ?>)"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if(!$configs): ?>
                                <tr><td colspan="4" class="empty-msg">Nenhum estilo personalizado ainda.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-side">
        <div class="card shadow">
            <div class="card-header">
                <h2><i class="fas fa-vial"></i> Editor de Estilo</h2>
            </div>
            <div class="card-body">
                <form method="post" id="styleForm">
                    <?= csrf_input() ?>
                    <div class="form-group">
                        <label>Seletor CSS:</label>
                        <input type="text" name="elemento_classe" id="f_classe" placeholder=".btn-action, #header, body..." required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Cor de Fundo:</label>
                            <input type="color" name="cor_fundo" id="f_bg" value="#ffffff">
                        </div>
                        <div class="form-group">
                            <label>Cor do Texto:</label>
                            <input type="color" name="cor_texto" id="f_tx" value="#333333">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Fonte (Família):</label>
                        <input type="text" name="fonte_familia" id="f_ff" placeholder="Arial, sans-serif">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Tamanho:</label>
                            <input type="text" name="fonte_tamanho" id="f_fz" placeholder="16px">
                        </div>
                        <div class="form-group">
                            <label>Arredondamento:</label>
                            <input type="text" name="borda_raio" id="f_br" placeholder="8px">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>CSS Extra (Adicional):</label>
                        <textarea name="custom_css" id="f_css" rows="3" placeholder="padding: 10px; opacity: 0.9;"></textarea>
                    </div>

                    <div id="live-preview" class="live-preview-box">
                        Pré-visualização do Elemento
                    </div>

                    <button type="submit" name="save_layout" class="btn-primary btn-full"><i class="fas fa-save"></i> Salvar Estilo</button>
                    <button type="button" class="btn-secondary btn-full btn-sm" onclick="resetForm()" style="margin-top:0.5rem">Novo Estilo</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function editStyle(data) {
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
    const box = document.getElementById('live-preview');
    box.style.backgroundColor = document.getElementById('f_bg').value;
    box.style.color = document.getElementById('f_tx').value;
    box.style.fontFamily = document.getElementById('f_ff').value;
    box.style.fontSize = document.getElementById('f_fz').value;
    box.style.borderRadius = document.getElementById('f_br').value;
    
    // Simples tentativa de aplicar o custom css
    const custom = document.getElementById('f_css').value;
    box.style.cssText += custom;
}

document.querySelectorAll('#styleForm input, #styleForm textarea').forEach(el => {
    el.addEventListener('input', updatePreview);
});

function resetForm() {
    document.getElementById('styleForm').reset();
    updatePreview();
}
</script>

<style>
.grid-container { display: grid; grid-template-columns: 1fr 340px; gap: 2rem; }
.preview-dot { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 1px solid #ddd; font-size: 10px; }
.live-preview-box { border: 1px dashed #ccc; padding: 20px; margin-bottom: 1.5rem; text-align: center; border-radius: 8px; font-weight: 600; }
/* Ver estrutura_php_elemento_admin_seo.md para estilos de grid/card/table */
</style>
```

## 🛠️ Injeção Dinâmica no `index.php`

Para que os estilos sejam aplicados, adicione este bloco no `<head>` do seu `index.php`:

```php
<style id="dynamic-layouts">
    <?php
    try {
        $configs = db()->query("SELECT * FROM sys_layout_config")->fetchAll();
        foreach ($configs as $c) {
            echo $c['elemento_classe'] . " { \n";
            if ($c['cor_fundo'])   echo "  background-color: " . $c['cor_fundo'] . " !important;\n";
            if ($c['cor_texto'])   echo "  color: " . $c['cor_texto'] . " !important;\n";
            if ($c['fonte_familia']) echo "  font-family: " . $c['fonte_familia'] . " !important;\n";
            if ($c['fonte_tamanho']) echo "  font-size: " . $c['fonte_tamanho'] . " !important;\n";
            if ($c['borda_raio'])   echo "  border-radius: " . $c['borda_raio'] . " !important;\n";
            if ($c['custom_css'])   echo "  " . $c['custom_css'] . "\n";
            echo "} \n";
        }
    } catch (Exception $e) {}
    ?>
</style>
```
