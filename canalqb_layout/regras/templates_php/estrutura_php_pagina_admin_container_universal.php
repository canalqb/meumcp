<?php
/**
 * [Nome da Página] (Content Only)
 * Versão para carregamento via AJAX sem conflitos de scripts
 */

// Verificar se sessão já foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../config/database.php');

// Verificação básica de segurança (via AJAX)
if (!isset($_SESSION['tipo_usuario']) && !isset($_SESSION['canal_id'])) {
    echo '<div class="alert alert-warning">Sessão não encontrada! <a href="../index.php">Clique aqui para recarregar</a></div>';
    exit;
}

// Lógica específica da página aqui
$mensagem = '';
$tipo_mensagem = '';

// Processar formulários se necessário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lógica de processamento aqui
}
?>

<!-- Conteúdo para carregamento via AJAX -->
<div class="admin-content-wrapper">
    <style>
        .cqb-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        @media (min-width: 1400px) {
            .cqb-container {
                max-width: 1280px;
            }
        }
        
        @media (min-width: 1600px) {
            .cqb-container {
                max-width: 1440px;
            }
        }
    </style>
    
    <!-- Container Centralizado -->
    <div class="cqb-container">
    
    <!-- Header -->
    <div class="admin-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">
                <i class="fas fa-[icone] [cor]"></i>
                [Título da Página]
            </h4>
            <p class="text-muted mb-0">[Descrição da página]</p>
        </div>
        <button class="btn btn-outline-secondary btn-sm" onclick="carregar2('./pages/admin-dashboard-content.php')">
            <i class="fas fa-arrow-left"></i> Voltar
        </button>
    </div>
    
    <!-- Mensagens de Feedback -->
    <?php if ($mensagem): ?>
    <div class="alert alert-<?= $tipo_mensagem === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
        <i class="fas fa-<?= $tipo_mensagem === 'success' ? 'check-circle' : 'exclamation-triangle' ?>"></i>
        <?= htmlspecialchars($mensagem) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <!-- Conteúdo Principal -->
    <div class="card mb-4">
        <div class="card-header bg-gradient-primary">
            <h6 class="mb-0">
                <i class="fas fa-[icone]"></i>
                [Título do Card Principal]
            </h6>
        </div>
        <div class="card-body">
            <!-- Formulário ou conteúdo principal aqui -->
            <form method="POST" id="[form-id]">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="[input-id]" class="form-label">[Label do Input]</label>
                            <input type="text" class="form-control" id="[input-id]" name="[input-name]" required>
                            <div class="form-text">Texto de ajuda opcional</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="[select-id]" class="form-label">[Label do Select]</label>
                            <select class="form-select" id="[select-id]" name="[select-name]">
                                <option value="">Selecione...</option>
                                <!-- Opções aqui -->
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="[check-id]" name="[check-name]">
                                <label class="form-check-label" for="[check-id]">
                                    [Texto do checkbox]
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Salvar
                            </button>
                            <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-undo"></i> Limpar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Tabela de Dados -->
    <div class="card">
        <div class="card-header bg-gradient-info">
            <h6 class="mb-0">
                <i class="fas fa-table"></i>
                [Título da Tabela]
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Status</th>
                            <th>Data</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($dados) && count($dados) > 0): ?>
                            <?php foreach ($dados as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['id']) ?></td>
                                <td><?= htmlspecialchars($item['nome']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $item['ativo'] ? 'success' : 'danger' ?>">
                                        <?= $item['ativo'] ? 'Ativo' : 'Inativo' ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($item['data'])) ?></td>
                                <td class="text-end">
                                    <div class="btn-group" role="group">
                                        <button 
                                            type="button" 
                                            class="btn btn-sm btn-primary"
                                            onclick="editarItem(<?= $item['id'] ?>)"
                                            title="Editar item"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button 
                                            type="button" 
                                            class="btn btn-sm btn-warning"
                                            onclick="toggleStatus(<?= $item['id'] ?>)"
                                            title="<?= $item['ativo'] ? 'Desativar' : 'Ativar' ?> item"
                                        >
                                            <i class="fas fa-<?= $item['ativo'] ? 'pause' : 'play' ?>"></i>
                                        </button>
                                        <button 
                                            type="button" 
                                            class="btn btn-sm btn-danger"
                                            onclick="removerItem(<?= $item['id'] ?>)"
                                            title="Remover item permanentemente"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Nenhum item encontrado</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Cards Informativos -->
    <div class="row">
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <i class="fas fa-chart-line fa-2x text-primary mb-3"></i>
                    <h5 class="card-title">[Título da Estatística]</h5>
                    <p class="card-text text-muted">[Descrição da estatística]</p>
                    <h3 class="text-primary"><?= number_format($total, 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                    <h5 class="card-title">[Título da Estatística]</h5>
                    <p class="card-text text-muted">[Descrição da estatística]</p>
                    <h3 class="text-success"><?= number_format($ativos, 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <i class="fas fa-exclamation-triangle fa-2x text-warning mb-3"></i>
                    <h5 class="card-title">[Título da Estatística]</h5>
                    <p class="card-text text-muted">[Descrição da estatística]</p>
                    <h3 class="text-warning"><?= number_format($inativos, 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Alertas Informativos -->
    <div class="alert alert-info mt-4">
        <h6 class="alert-heading">
            <i class="fas fa-info-circle"></i> Informações Importantes
        </h6>
        <ul class="mb-0">
            <li>[Informação importante 1]</li>
            <li>[Informação importante 2]</li>
            <li>[Informação importante 3]</li>
        </ul>
    </div>

    </div> <!-- Fecha .cqb-container -->
</div> <!-- Fecha .admin-content-wrapper -->

<!-- CSS do Sistema de Confirmação Centralizado (se necessário) -->
<link rel="stylesheet" href="/css/confirmation-modal.css">

<script>
// Funções JavaScript padrão
function editarItem(id) {
    showConfirmation({
        title: 'Editar Item',
        message: 'Deseja editar este item?',
        type: 'info',
        confirmText: 'Editar',
        onConfirm: () => {
            // Lógica de edição aqui
            console.log('Editar item:', id);
        }
    });
}

function toggleStatus(id) {
    const status = event.target.closest('tr').querySelector('.badge').classList.contains('bg-success');
    
    showConfirmation({
        title: status ? 'Desativar Item' : 'Ativar Item',
        message: status ? 'Deseja desativar este item?' : 'Deseja ativar este item?',
        type: 'warning',
        confirmText: status ? 'Desativar' : 'Ativar',
        onConfirm: () => {
            const formData = new FormData();
            formData.append('acao', status ? 'desativar' : 'ativar');
            formData.append('id', id);
            
            fetch('./pages/[nome-da-pagina]-content.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // Recarregar apenas o conteúdo da página
                carregar2('./pages/[nome-da-pagina]-content.php');
                showToast(status ? 'Item desativado com sucesso!' : 'Item ativado com sucesso!', 'success');
            })
            .catch(error => {
                console.error('Erro:', error);
                showToast('Erro ao alterar status. Tente novamente.', 'error');
            });
        }
    });
}

function removerItem(id) {
    showConfirmation({
        title: 'Remover Item',
        message: 'Tem certeza que deseja remover este item permanentemente? Esta ação não pode ser desfeita.',
        type: 'danger',
        confirmText: 'Remover',
        onConfirm: () => {
            const formData = new FormData();
            formData.append('acao', 'remover');
            formData.append('id', id);
            
            fetch('./pages/[nome-da-pagina]-content.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // Remover a linha imediatamente
                const row = document.querySelector(`button[onclick="removerItem(${id})"]`).closest('tr');
                if (row) {
                    row.remove();
                }
                showToast('Item removido com sucesso!', 'success');
            })
            .catch(error => {
                console.error('Erro:', error);
                showToast('Erro ao remover item. Tente novamente.', 'error');
            });
        }
    });
}

// Validação de formulário
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('[form-id]');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            
            fetch('./pages/[nome-da-pagina]-content.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // Recarregar apenas o conteúdo da página
                carregar2('./pages/[nome-da-pagina]-content.php');
                showToast('Dados salvos com sucesso!', 'success');
            })
            .catch(error => {
                console.error('Erro:', error);
                showToast('Erro ao salvar dados. Tente novamente.', 'error');
            });
        });
    }
    
    // Estilização de inputs
    const inputs = document.querySelectorAll('input[type="text"], input[type="number"], textarea, select');
    inputs.forEach(input => {
        input.style.backgroundColor = 'var(--cqb-bg-white)';
        input.style.border = '1px solid var(--cqb-border)';
        input.style.padding = '8px';
        input.style.borderRadius = 'var(--cqb-border-radius)';
    });
});
</script>
