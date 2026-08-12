<?php
/**
 * Template Universal: Componente Columns with Icons
 * 
 * Este template serve como base para criar layouts de colunas com ícones
 * em QUALQUER tipo de site (e-commerce, blog, SaaS, institucional, etc.)
 * 
 * @version 1.0 - Universal
 * @author Sistema IA
 * 
 * Como usar:
 * 1. Copiar este arquivo para views/components/columns_with_icons.php
 * 2. Substituir variáveis entre {chaves}
 * 3. Ajustar conteúdo conforme necessidade do site
 * 4. Criar tabela no banco de dados (opcional)
 */

if (!defined('APP')) { http_response_code(403); exit('Acesso direto negado'); }

// Carregar features do banco (opcional)
$features = [];
if (isset($_GET['from_db']) && $_GET['from_db'] === 'true') {
    $features = db()->query("SELECT * FROM {tabela_features} WHERE ativo = 1 ORDER BY ordem, titulo")->fetchAll();
} else {
    // Features estáticas (personalizar conforme necessidade)
    $features = [
        [
            'titulo' => '{titulo_feature_1}',
            'descricao' => '{descricao_feature_1}',
            'icone' => '{icone_feature_1}',
            'cor_fundo' => '{cor_fundo_1}',
            'texto_botao' => '{texto_botao_1}',
            'url_botao' => '{url_botao_1}'
        ],
        [
            'titulo' => '{titulo_feature_2}',
            'descricao' => '{descricao_feature_2}',
            'icone' => '{icone_feature_2}',
            'cor_fundo' => '{cor_fundo_2}',
            'texto_botao' => '{texto_botao_2}',
            'url_botao' => '{url_botao_2}'
        ],
        [
            'titulo' => '{titulo_feature_3}',
            'descricao' => '{descricao_feature_3}',
            'icone' => '{icone_feature_3}',
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
    'gradiente_padrao' => '{gradiente_padrao}'
];
?>

<!-- HTML do Componente -->
<section class="{classe_custom}" aria-labelledby="columns-icons-title">
    <div class="container px-4 py-5">
        <h2 id="columns-icons-title" class="pb-2 border-bottom">
            <?php echo htmlspecialchars($config_component['titulo_secao']); ?>
        </h2>
        
        <?php if (!empty($config_component['descricao_secao'])): ?>
            <p class="lead mb-4">
                <?php echo htmlspecialchars($config_component['descricao_secao']); ?>
            </p>
        <?php endif; ?>
        
        <div class="row g-4 py-5 <?php echo $config_component['colunas_grid']; ?>">
            <?php foreach ($features as $index => $feature): ?>
                <div class="feature-col">
                    <div class="feature-icon bg-primary bg-gradient" 
                         style="background: <?php echo $feature['cor_fundo'] ?: $config_component['gradiente_padrao']; ?>;">
                        <i class="bi <?php echo $feature['icone']; ?>"></i>
                    </div>
                    <h3><?php echo htmlspecialchars($feature['titulo']); ?></h3>
                    <p><?php echo htmlspecialchars($feature['descricao']); ?></p>
                    
                    <?php if ($config_component['mostrar_botao'] && !empty($feature['texto_botao'])): ?>
                        <a href="<?php echo htmlspecialchars($feature['url_botao'] ?: '#'); ?>" 
                           class="icon-link">
                            <?php echo htmlspecialchars($feature['texto_botao']); ?>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CSS Universal -->
<style>
.feature-col {
    text-align: center;
}

.feature-icon {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.feature-icon:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}

.feature-col h3 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--text);
}

.feature-col p {
    color: var(--muted);
    margin-bottom: 1rem;
    line-height: 1.6;
}

.icon-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--brand);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease, transform 0.3s ease;
}

.icon-link:hover {
    color: var(--brand-dark);
    transform: translateX(3px);
}

.icon-link i {
    transition: transform 0.3s ease;
}

.icon-link:hover i {
    transform: translateX(2px);
}

/* Gradientes padrão */
.bg-gradient {
    background: linear-gradient(135deg, var(--brand) 0%, var(--brand-dark) 100%);
}

/* Responsividade */
@media (max-width: 768px) {
    .feature-icon {
        width: 56px;
        height: 56px;
        font-size: 1.25rem;
    }
    
    .feature-col h3 {
        font-size: 1.1rem;
    }
}

/* Acessibilidade */
.feature-icon:focus {
    outline: 2px solid var(--brand);
    outline-offset: 2px;
}

.icon-link:focus {
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
                entry.target.style.opacity = '0';
                entry.target.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    entry.target.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, 100);
                
                observer.unobserve(entry.target);
            }
        });
    });
    
    document.querySelectorAll('.feature-col').forEach(col => {
        observer.observe(col);
    });
    
    // Click nos ícones (opcional)
    document.querySelectorAll('.feature-icon').forEach(icon => {
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
