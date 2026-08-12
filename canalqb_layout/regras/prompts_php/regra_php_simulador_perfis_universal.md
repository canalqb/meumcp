# 🎭 Regra Universal: Simulador de Perfis de Usuário

## 📋 Objetivo
Este documento ensina todos os LLMs a criar sistemas de simulação de perfis de usuário universais que funcionem em QUALQUER tipo de site, sem dependências específicas do projeto pizzaria.

## 🎭 Página de Referência (Apenas para Estudo)

### Página Original
- **Funcionalidade:** Simulação de perfis para testes de permissão
- **Conceitos:** Sistema de simulação aplicável a qualquer site com múltiplos níveis de acesso

## 🏗️ Estrutura Universal

### 📁 Padrão de Arquivos
```
views/admin/
├── simulador_perfis.php          # Página principal de simulação
└── {projeto}_simulador.php     # Versão personalizada
```

### 🗄️ Padrão de Banco de Dados
```sql
-- Tabela de Tipos de Usuário
CREATE TABLE {prefixo}_user_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_key VARCHAR(50) NOT NULL UNIQUE,
    type_name VARCHAR(100) NOT NULL,
    description TEXT,
    icon VARCHAR(100),
    color VARCHAR(7),
    permissions JSON,
    is_active BOOLEAN DEFAULT TRUE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de Sessões de Simulação
CREATE TABLE {prefixo}_simulation_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    original_profile VARCHAR(50) NOT NULL,
    simulated_profile VARCHAR(50) NOT NULL,
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ended_at NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    FOREIGN KEY (admin_id) REFERENCES {prefixo}_users(id)
);
```

## 🎯 Template Universal: Simulador de Perfis

### 📋 Estrutura Base PHP
```php
<?php
/**
 * Template Universal: Simulador de Perfis de Usuário
 * 
 * Este template serve como base para criar sistemas de simulação
 * em qualquer tipo de site, seguindo o padrão do projeto original
 * 
 * @version 1.0
 * @author Sistema IA
 * 
 * Como usar:
 * 1. Copiar este arquivo para views/admin/simulador_perfis.php
 * 2. Substituir variáveis entre {chaves}
 * 3. Ajustar perfis conforme necessidade
 * 4. Criar tabela de tipos de usuário
 */

declare(strict_types=1);

if (!defined('APP')) { http_response_code(403); exit('Acesso direto negado'); }
require_once __DIR__ . '/../../config/security.php';

// Verificar permissão de administrador
if (currentUserRole() !== '{tipo_admin}') {
    header('Location: index.php?page=login');
    exit;
}

// Nota: ?restaurar=1 é tratado em index.php (antes de qualquer HTML).
// Aqui chegamos apenas quando o admin quer VISUALIZAR a página do simulador.
$u = currentUser();
if (!$u || $u['tipo'] !== '{tipo_admin}') {
    // Se ainda estiver simulando e clicar no menu, redireciona silenciosamente
    echo "<script>window.location.href='" . APP_URL . "/index.php';</script>";
    exit;
}

// POST e restauração são tratados em index.php antes de qualquer HTML.
$perfilAtual = $_SESSION['user']['tipo'];
$estaSimulando = $_SESSION['simulando'] ?? false;

// Carregar tipos de usuário disponíveis
$userTypes = db()->query("SELECT * FROM {tabela_user_types} WHERE is_active = 1 ORDER BY sort_order, type_name")->fetchAll();
?>

<section aria-labelledby="simulador-title">
    <div class="admin-header">
        <h1 id="simulador-title">
            <i class="fas fa-{icone_principal}"></i> 
            {titulo_pagina}
        </h1>
        <p class="subtitle">{descricao_pagina}</p>
    </div>

    <div class="grid-container">
        <!-- Card do Perfil Atual -->
        <div class="card perfil-card">
            <div class="card-header">
                <h2><i class="fas fa-user"></i> Perfil Atual</h2>
            </div>
            <div class="card-body">
                <div class="perfil-atual">
                    <div class="perfil-info">
                        <div class="perfil-avatar">
                            <i class="fas fa-{icone_usuario_atual}"></i>
                        </div>
                        <div class="perfil-detalhes">
                            <strong><?= htmlspecialchars($perfilAtual) ?></strong>
                            <small class="perfil-descricao">{descricao_perfil_atual}</small>
                        </div>
                    </div>
                    <?php if ($estaSimulando): ?>
                        <div class="simulacao-indicator">
                            <span class="badge-simulando">
                                <i class="fas fa-theater-masks"></i> Simulando
                            </span>
                            <small class="tempo-simulacao">
                                Iniciado: <?= date('H:i', $_SESSION['simulacao_inicio'] ?? time()) ?>
                            </small>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if ($estaSimulando): ?>
                    <div class="acoes-perfil">
                        <a href="?restaurar=1" class="btn btn-danger btn-full">
                            <i class="fas fa-undo"></i> Restaurar Perfil {tipo_admin}
                        </a>
                        <button type="button" class="btn btn-secondary btn-full" onclick="verPermissoes()">
                            <i class="fas fa-key"></i> Ver Permissões
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Card de Simulação -->
        <div class="card simulacao-card">
            <div class="card-header">
                <h2><i class="fas fa-theater-masks"></i> Simular Outro Perfil</h2>
            </div>
            <div class="card-body">
                <form method="post" class="form-simulacao">
                    <?= csrf_input() ?>
                    
                    <div class="form-group">
                        <label for="perfil">
                            <i class="fas fa-users"></i> Escolha o perfil para simular:
                        </label>
                        <div class="select-wrapper">
                            <select name="perfil" id="perfil" required class="form-control">
                                <option value="">Selecione o perfil a simular...</option>
                                <?php foreach ($userTypes as $type): ?>
                                    <?php if ($type['type_key'] !== $perfilAtual): ?>
                                        <option value="<?= htmlspecialchars($type['type_key']) ?>" 
                                                data-icon="<?= htmlspecialchars($type['icon']) ?>"
                                                data-color="<?= htmlspecialchars($type['color']) ?>"
                                                data-description="<?= htmlspecialchars($type['description']) ?>">
                                            <?= htmlspecialchars($type['icon']) ?> <?= htmlspecialchars($type['type_name']) ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                            <div class="select-icon" id="selected-icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="duracao">
                            <i class="fas fa-clock"></i> Duração da simulação (opcional):
                        </label>
                        <select name="duracao" id="duracao" class="form-control">
                            <option value="">Indeterminada</option>
                            <option value="300">5 minutos</option>
                            <option value="900">15 minutos</option>
                            <option value="1800">30 minutos</option>
                            <option value="3600">1 hora</option>
                            <option value="7200">2 horas</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="log_acoes" id="log_acoes" checked>
                            <span class="checkmark"></span>
                            Registrar todas as ações durante simulação
                        </label>
                        <small class="form-help">Útil para auditoria e testes de segurança</small>
                    </div>

                    <button type="submit" name="simular_perfil" class="btn-primary btn-full">
                        <i class="fas fa-theater-masks"></i> Simular Perfil
                    </button>
                </form>
            </div>
        </div>

        <!-- Card de Informações -->
        <div class="card info-card">
            <div class="card-header">
                <h2><i class="fas fa-info-circle"></i> Informações do Sistema</h2>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <i class="fas fa-user-shield"></i>
                        <div class="info-content">
                            <strong>Admin original:</strong>
                            <span><?= htmlspecialchars($_SESSION['admin_original']['nome'] ?? 'N/A') ?></span>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-unlock"></i>
                        <div class="info-content">
                            <strong>Acesso total:</strong>
                            <span>Sim, você tem acesso a 100% do sistema</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-vial"></i>
                        <div class="info-content">
                            <strong>Testes:</strong>
                            <span>Use para validar permissões e funcionalidades</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-shield-alt"></i>
                        <div class="info-content">
                            <strong>Segurança:</strong>
                            <span>Perfil admin sempre preservado</span>
                        </div>
                    </div>
                </div>

                <div class="estatisticas-simulacao">
                    <h3><i class="fas fa-chart-bar"></i> Estatísticas de Uso</h3>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <strong><?= getTotalSimulacoesHoje() ?></strong>
                            <span>Simulações hoje</span>
                        </div>
                        <div class="stat-item">
                            <strong><?= getUsuariosAtivos() ?></strong>
                            <span>Usuários ativos</span>
                        </div>
                        <div class="stat-item">
                            <strong><?= getPerfisDisponiveis() ?></strong>
                            <span>Perfis disponíveis</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Permissões -->
    <div id="permissoes-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-key"></i> Permissões do Perfil</h3>
                <button class="modal-close" onclick="fecharModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="permissoes-content">
                    <!-- Permissões serão carregadas aqui -->
                </div>
            </div>
        </div>
    </div>
</section>

<!-- JavaScript -->
<script>
// Configurações do simulador
const configSimulador = {
    adminType: '{tipo_admin}',
    userTypes: <?= json_encode($userTypes) ?>
};

// Funções principais
function verPermissoes() {
    const perfil = document.getElementById('perfil').value;
    if (!perfil) {
        showToast('Selecione um perfil primeiro', 'error');
        return;
    }
    
    const userType = configSimulador.userTypes.find(type => type.type_key === perfil);
    if (userType && userType.permissions) {
        const permissions = JSON.parse(userType.permissions);
        let html = '<div class="permissoes-list">';
        
        Object.entries(permissions).forEach(([module, perms]) => {
            html += `
                <div class="permission-group">
                    <h4><i class="fas fa-folder"></i> ${module}</h4>
                    <ul>
                        ${perms.map(perm => `
                            <li class="${perm.allowed ? 'allowed' : 'denied'}">
                                <i class="fas fa-${perm.allowed ? 'check' : 'times'}"></i>
                                ${perm.description}
                            </li>
                        `).join('')}
                    </ul>
                </div>
            `;
        });
        
        html += '</div>';
        document.getElementById('permissoes-content').innerHTML = html;
        document.getElementById('permissoes-modal').style.display = 'block';
    }
}

function fecharModal() {
    document.getElementById('permissoes-modal').style.display = 'none';
}

// Atualizar ícone do select
document.getElementById('perfil').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const icon = selected.getAttribute('data-icon');
    const iconElement = document.querySelector('#selected-icon i');
    
    if (icon && iconElement) {
        iconElement.className = `fas fa-${icon}`;
    }
});

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

// Fechar modal ao clicar fora
window.onclick = function(event) {
    const modal = document.getElementById('permissoes-modal');
    if (event.target === modal) {
        fecharModal();
    }
}

// Funções de estatísticas (exemplos - implementar conforme necessário)
function getTotalSimulacoesHoje() {
    return <?= getTotalSimulacoesHoje() ?>;
}

function getUsuariosAtivos() {
    return <?= getUsuariosAtivos() ?>;
}

function getPerfisDisponiveis() {
    return <?= getPerfisDisponiveis() ?>;
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
    grid-template-columns: 1fr 1fr; 
    gap: 2rem; 
    margin-bottom: 2rem;
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

/* Card de Perfil Atual */
.perfil-card {
    grid-column: span 1;
}

.perfil-atual {
    padding: 1.5rem;
    background: var(--bg);
    border-radius: 12px;
    border: 2px solid var(--border);
    margin-bottom: 1.5rem;
}

.perfil-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.perfil-avatar {
    width: 60px;
    height: 60px;
    background: var(--brand);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.perfil-detalhes strong {
    display: block;
    font-size: 1.2rem;
    color: var(--text);
    margin-bottom: 0.25rem;
}

.perfil-detalhes small {
    color: var(--muted);
    font-style: italic;
}

.simulacao-indicator {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border: 1px solid #ffeaa7;
    border-radius: 8px;
}

.badge-simulando {
    background: #ff6b35;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.tempo-simulacao {
    color: #856404;
    font-size: 0.8rem;
}

.acoes-perfil {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

/* Card de Simulação */
.simulacao-card {
    grid-column: span 1;
}

.form-simulacao .form-group {
    margin-bottom: 1.5rem;
}

.form-simulacao label {
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

.select-wrapper {
    position: relative;
}

.select-wrapper .select-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: var(--brand);
    color: white;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    position: relative;
    padding-left: 1.5rem;
}

.checkbox-label input[type="checkbox"] {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.checkmark {
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    height: 20px;
    width: 20px;
    background-color: var(--bg);
    border: 2px solid var(--border);
    border-radius: 4px;
    transition: all 0.3s ease;
}

.checkbox-label input:checked ~ .checkmark {
    background-color: var(--brand);
    border-color: var(--brand);
}

.checkbox-label input:checked ~ .checkmark:after {
    content: "";
    position: absolute;
    display: block;
    left: 6px;
    top: 2px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

.form-help {
    display: block;
    margin-top: 0.5rem;
    font-size: 0.8rem;
    color: var(--muted);
    font-style: italic;
}

/* Card de Informações */
.info-card {
    grid-column: span 2;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg);
    border-radius: 8px;
    border: 1px solid var(--border);
}

.info-item i {
    font-size: 1.5rem;
    color: var(--brand);
    width: 40px;
    text-align: center;
}

.info-content {
    flex: 1;
}

.info-content strong {
    display: block;
    color: var(--text);
    margin-bottom: 0.25rem;
}

.info-content span {
    color: var(--muted);
    font-size: 0.9rem;
}

.estatisticas-simulacao h3 {
    margin: 0 0 1rem 0;
    color: var(--brand);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.stat-item {
    text-align: center;
    padding: 1rem;
    background: linear-gradient(135deg, var(--brand) 0%, var(--brand-dark) 100%);
    color: white;
    border-radius: 8px;
}

.stat-item strong {
    display: block;
    font-size: 1.8rem;
    margin-bottom: 0.25rem;
}

.stat-item span {
    font-size: 0.8rem;
    opacity: 0.9;
}

/* Modal */
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
    max-width: 600px;
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

.permissoes-list {
    max-height: 400px;
    overflow-y: auto;
}

.permission-group {
    margin-bottom: 1.5rem;
}

.permission-group h4 {
    margin: 0 0 1rem 0;
    color: var(--text);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.1rem;
}

.permission-group ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.permission-group li {
    padding: 0.5rem;
    margin-bottom: 0.25rem;
    border-radius: 4px;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.permission-group li.allowed {
    background: #d4edda;
    color: #155724;
}

.permission-group li.denied {
    background: #f8d7da;
    color: #721c24;
}

/* Botões */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-primary {
    background: var(--brand);
    color: white;
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
}

.btn-secondary:hover {
    background: var(--border);
    border-color: var(--brand);
    color: var(--brand);
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #c82333;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
}

.btn-full {
    width: 100%;
    justify-content: center;
}

/* Toast */
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
@media (max-width: 1024px) {
    .grid-container {
        grid-template-columns: 1fr;
    }
    
    .info-card {
        grid-column: span 1;
    }
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .perfil-info {
        flex-direction: column;
        text-align: center;
    }
    
    .simulacao-indicator {
        flex-direction: column;
        text-align: center;
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
.btn:focus,
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

<?php
// Funções auxiliares (implementar conforme necessário)
function getTotalSimulacoesHoje() {
    // Implementar contagem de simulações hoje
    return rand(5, 15);
}

function getUsuariosAtivos() {
    // Implementar contagem de usuários ativos
    return rand(20, 50);
}

function getPerfisDisponiveis() {
    // Implementar contagem de perfis disponíveis
    return count($userTypes);
}
?>
```

## 🔧 Parâmetros de Configuração

### 📋 Variáveis Universal
| Variável | Descrição | Exemplo |
|----------|-----------|---------|
| `{tipo_admin}` | Tipo de usuário administrador | `administrador`, `admin`, `superuser` |
| `{tabela_user_types}` | Tabela de tipos de usuário | `user_types`, `tipos_usuario` |
| `{prefixo}` | Prefixo das tabelas | `site_`, `loja_`, `blog_` |
| `{icone_principal}` | Ícone principal da página | `theater-masks`, `users` |
| `{titulo_pagina}` | Título da página | "Simulador de Perfis" |

### 🎭 Tipos de Perfil Padrão
| Tipo | Chave | Ícone | Descrição |
|------|-------|------|-----------|
| Cliente | `cliente` | `user` | Acesso ao site público |
| Atendente | `atendente` | `headset` | Atendimento ao cliente |
| Vendedor | `vendedor` | `handshake` | Vendas e negociações |
| Gerente | `gerente` | `user-tie` | Gestão de equipe |
| Suporte | `suporte` | `life-ring` | Suporte técnico |
| Moderador | `moderador` | `shield-alt` | Moderação de conteúdo |

## 🚀 Como Usar

### 1. Para LLMs
1. **Leitura Obrigatória:** Ler este documento antes de criar simulador
2. **Identificar Sistema:** Determinar estrutura de usuários do projeto
3. **Aplicar Template:** Usar template universal como base
4. **Personalizar:** Ajustar perfis e permissões conforme necessidade

### 2. Para Desenvolvedores
1. **Copiar Template:** Usar arquivo como base
2. **Ajustar Parâmetros:** Modificar variáveis `{...}`
3. **Criar Tabela:** Implementar tabela de tipos de usuário
4. **Integrar:** Adicionar ao sistema de autenticação

### 3. Para Qualquer Site
1. **Adaptar Prefixo:** Mudar `{prefixo}` para nome do projeto
2. **Personalizar Perfis:** Definir tipos conforme necessidade
3. **Ajustar Design:** Modificar CSS conforme branding
4. **Implementar:** Integrar ao sistema existente

## 📋 Funcionalidades Essenciais

### ✅ Segurança
- [ ] Preservação do perfil admin original
- [ ] Registro de sessões de simulação
- [ ] Timeout automático da simulação
- [ ] Log de ações durante simulação

### ✅ Interface
- [ ] Visualização clara do perfil atual
- [ ] Indicador de simulação ativa
- [ ] Restauração fácil do perfil original
- [ ] Preview de permissões

### ✅ Usabilidade
- [ ] Seleção intuitiva de perfis
- [ ] Feedback visual claro
- [ ] Estatísticas de uso
- [ ] Design responsivo

## 📊 Exemplos Práticos

### 🛒 E-commerce
```php
// Tipos de usuário
{tipo_admin} = 'administrador'
{tabela_user_types} = 'loja_user_types'
$perfis = [
    ['type_key' => 'cliente', 'type_name' => 'Cliente'],
    ['type_key' => 'vendedor', 'type_name' => 'Vendedor'],
    ['type_key' => 'gerente', 'type_name' => 'Gerente']
];
```

### 📝 Blog
```php
// Tipos de usuário
{tipo_admin} = 'admin'
{tabela_user_types} = 'blog_user_types'
$perfis = [
    ['type_key' => 'leitor', 'type_name' => 'Leitor'],
    ['type_key' => 'autor', 'type_name' => 'Autor'],
    ['type_key' => 'editor', 'type_name' => 'Editor']
];
```

### 🏢 SaaS
```php
// Tipos de usuário
{tipo_admin} = 'superuser'
{tabela_user_types} = 'saas_user_types'
$perfis = [
    ['type_key' => 'usuario', 'type_name' => 'Usuário'],
    ['type_key' => 'gestor', 'type_name' => 'Gestor'],
    ['type_key' => 'admin', 'type_name' => 'Administrador']
];
```

## 📋 Checklist de Validação

### ✅ Antes de Entregar
- [ ] Leitura completa deste documento
- [ ] Template aplicado corretamente
- [ ] Variáveis substituídas
- [ ] Segurança implementada
- [ ] Permissões verificadas
- [ ] Responsividade testada

### ✅ Funcionalidades Essenciais
- [ ] Preservação do perfil admin
- [ ] Sistema de simulação funcional
- [ ] Registro de sessões
- [ ] Interface intuitiva
- [ ] Design responsivo

---

**🚨 IMPORTANTE:** Este documento deve ser lido OBRIGATORIAMENTE antes de criar qualquer sistema de simulação de perfis em qualquer projeto!
