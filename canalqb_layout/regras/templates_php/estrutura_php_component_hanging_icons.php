<?php
/**
 * Template Universal: Componente Hanging Icons
 * 
 * Este template serve como base para criar layouts com ícones pendurados
 * em QUALQUER tipo de site (e-commerce, blog, SaaS, institucional, etc.)
 * 
 * @version 1.0 - Universal
 * @author Sistema IA
 * 
 * Como usar:
 * 1. Copiar este arquivo para views/components/hanging_icons.php
 * 2. Substituir variáveis entre {chaves}
 * 3. Ajustar conteúdo conforme necessidade do site
 * 4. Criar tabela no banco de dados (opcional)
 */

if (!defined('APP')) { http_response_code(403); exit('Acesso direto negado'); }

// Carregar recursos do banco (opcional)
$recursos = [];
if (isset($_GET['from_db']) && $_GET['from_db'] === 'true') {
    $recursos = db()->query("SELECT * FROM {tabela_recursos} WHERE ativo = 1 ORDER BY ordem, titulo")->fetchAll();
} else {
    // Recursos estáticos (personalizar conforme necessidade)
    $recursos = [
        [
            'titulo' => '{titulo_recurso_1}',
            'descricao' => '{descricao_recurso_1}',
            'icone' => '{icone_recurso_1}',
            'cor_fundo' => '{cor_fundo_1}',
            'texto_botao' => '{texto_botao_1}',
            'url_botao' => '{url_botao_1}'
        ],
        [
            'titulo' => '{titulo_recurso_2}',
            'descricao' => '{descricao_recurso_2}',
            'icone' => '{icone_recurso_2}',
            'cor_fundo' => '{cor_fundo_2}',
            'texto_botao' => '{texto_botao_2}',
            'url_botao' => '{url_botao_2}'
        ],
        [
            'titulo' => '{titulo_recurso_3}',
            'descricao' => '{descricao_recurso_3}',
            'icone' => '{icone_recurso_3}',
            'cor_fundo' => '{cor_fundo_3}',
            'texto_botao' => '{texto_botao_3}',
            'url_botao' => '{url_botao_3}'
        ]
    ];
}

// Configurações do componente
$config_component = [
    'titulo_secao' => '{titulo_secao}',
    'descricao_secao' => '{descricao_secao}',
    'colunas_grid' => '{colunas_grid}', // Ex: 'row-cols-1 row-cols-lg-3'
    'classe_custom' => '{classe_custom}',
    'mostrar_botao' => true, // true/false
    'cor_fundo_padrao' => '{cor_fundo_padrao}'
];
?>

<!-- HTML do Componente -->
<section class="{classe_custom}" aria-labelledby="hanging-icons-title">
    <div class="container px-4 py-5">
        <h2 id="hanging-icons-title" class="pb-2 border-bottom">
            <?php echo htmlspecialchars($config_component['titulo_secao']); ?>
        </h2>
        
        <?php if (!empty($config_component['descricao_secao'])): ?>
            <p class="lead mb-4">
                <?php echo htmlspecialchars($config_component['descricao_secao']); ?>
            </p>
        <?php endif; ?>
        
        <div class="row g-4 py-5 <?php echo $config_component['colunas_grid']; ?>">
            <?php foreach ($recursos as $index => $recurso): ?>
                <div class="col d-flex align-items-start">
                    <div class="icon-square bg-light text-dark flex-shrink-0 me-3" 
                         style="background: <?php echo $recurso['cor_fundo'] ?: $config_component['cor_fundo_padrao']; ?>;">
                        <i class="bi <?php echo $recurso['icone']; ?>"></i>
                    </div>
                    <div>
                        <h3><?php echo htmlspecialchars($recurso['titulo']); ?></h3>
                        <p><?php echo htmlspecialchars($recurso['descricao']); ?></p>
                        
                        <?php if ($config_component['mostrar_botao'] && !empty($recurso['texto_botao'])): ?>
                            <a href="<?php echo htmlspecialchars($recurso['url_botao'] ?: '#'); ?>" 
                               class="btn btn-primary">
                                <?php echo htmlspecialchars($recurso['texto_botao']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CSS Universal -->
<style>
.icon-square {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text);
    font-size: 1.25rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    flex-shrink: 0;
}

.icon-square:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.icon-square i {
    transition: transform 0.3s ease;
}

.icon-square:hover i {
    transform: scale(1.1);
}

.hanging-icons h3 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--text);
}

.hanging-icons p {
    color: var(--muted);
    margin-bottom: 1rem;
    line-height: 1.6;
}

.hanging-icons .btn {
    margin-top: 0.5rem;
    transition: all 0.3s ease;
}

.hanging-icons .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Cores de fundo padrão */
.bg-light {
    background-color: var(--bg);
    border: 1px solid var(--border);
}

/* Responsividade */
@media (max-width: 768px) {
    .icon-square {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .hanging-icons h3 {
        font-size: 1.1rem;
    }
    
    .icon-square.me-3 {
        margin-right: 1rem;
    }
}

/* Acessibilidade */
.icon-square:focus {
    outline: 2px solid var(--brand);
    outline-offset: 2px;
}

.btn:focus {
    outline: 2px solid var(--brand);
    outline-offset: 2px;
}
</style>

<!-- JavaScript (Opcional) -->
<script>
// Interações dinâmicas
document.addEventListener('DOMContentLoaded', function() {
    // Animação ao entrar no viewport
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const icon = entry.target.querySelector('.icon-square');
                const content = entry.target.querySelector('div:last-child');
                
                if (icon) {
                    icon.style.opacity = '0';
                    icon.style.transform = 'translateX(-20px)';
                    
                    setTimeout(() => {
                        icon.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                        icon.style.opacity = '1';
                        icon.style.transform = 'translateX(0)';
                    }, 100);
                }
                
                if (content) {
                    content.style.opacity = '0';
                    content.style.transform = 'translateY(20px)';
                    
                    setTimeout(() => {
                        content.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                        content.style.opacity = '1';
                        content.style.transform = 'translateY(0)';
                    }, 200);
                }
                
                observer.unobserve(entry.target);
            }
        });
    });
    
    document.querySelectorAll('.col').forEach(col => {
        observer.observe(col);
    });
    
    // Click nos ícones (opcional)
    document.querySelectorAll('.icon-square').forEach(icon => {
        icon.addEventListener('click', function() {
            // Adicionar interação personalizada aqui
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });
});
</script>
