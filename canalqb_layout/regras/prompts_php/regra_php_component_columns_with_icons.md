# 📊 Regra Universal: Componente Columns with Icons

## 📋 Objetivo
Este documento ensina todos os LLMs a criar layouts de colunas com ícones universais que funcionem em QUALQUER tipo de site, baseados no padrão Bootstrap Features.

## 🎨 Componente de Referência (Apenas para Estudo)

### Columns with Icons
- **Funcionalidade:** Layout em colunas com ícones destacados
- **Conceitos:** Sistema de apresentação de features com ícones circulares gradientes
- **Aplicação:** Ideal para apresentar serviços, produtos, features, benefícios

## 🏗️ Estrutura Universal

### 📁 Padrão de Arquivos (ADAPTÁVEL)
```
views/components/
├── columns_with_icons.php          # Componente principal
└── {projeto}_columns_icons.php     # Versão personalizada
```

### 🗄️ Padrão de Banco de Dados (OPCIONAL)
```sql
-- Tabela de Features (se necessário)
CREATE TABLE {prefixo_features} (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    icone VARCHAR(100),
    cor_fundo VARCHAR(7),
    texto_botao VARCHAR(255),
    url_botao VARCHAR(500),
    ordem INT DEFAULT 0,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## 🎯 Template Universal: Columns with Icons

### 📋 Estrutura Base PHP (TOTALMENTE UNIVERSAL)
```php
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
    'mostrar_botao' => {mostrar_botao}, // true/false
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
```

## 🔧 Parâmetros de Configuração

### 📋 Variáveis Universal (ADAPTÁVEIS)
| Variável | Descrição | Exemplo |
|----------|-----------|---------|
| `{titulo_secao}` | Título da seção | "Nossos Serviços", "Nossos Produtos" |
| `{descricao_secao}` | Descrição da seção | "Conheça nossas soluções" |
| `{colunas_grid}` | Classes Bootstrap grid | `row-cols-1 row-cols-lg-3` |
| `{classe_custom}` | Classe CSS customizada | `services-section`, `products-section` |
| `{mostrar_botao}` | Mostrar botões | `true` ou `false` |
| `{gradiente_padrao}` | Gradiente padrão | `linear-gradient(135deg, #667eea 0%, #764ba2 100%)` |

### 🎨 Ícones Disponíveis (FontAwesome)
```php
// Ícones mais comuns
'fa-star'           // Estrela
'fa-heart'          // Coração
'fa-rocket'         // Foguete
'fa-shield-alt'     // Escudo
'fa-cog'           // Engrenagem
'fa-lightbulb'     // Lâmpada
'fa-chart-line'     // Gráfico
'fa-users'         // Usuários
'fa-shopping-cart'  // Carrinho
'fa-credit-card'    // Cartão
'fa-globe'         // Globo
'fa-envelope'       // Envelope
'fa-phone'          // Telefone
'fa-map-marker-alt' // Mapa
'fa-clock'          // Relógio
'fa-calendar'       // Calendário
'fa-camera'         // Câmera
'fa-video'          // Vídeo
'fa-music'          // Música
'fa-book'           // Livro
'fa-graduation-cap' // Formatura
'fa-briefcase'      // Maleta
'fa-home'           // Casa
'fa-building'       // Prédio
'fa-car'            // Carro
'fa-plane'          // Avião
'fa-ship'           // Navio
'fa-train'          // Trem
```

### 🎨 Cores de Fundo (Gradientes)
```php
// Gradientes populares
'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'  // Roxo
'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)'  // Rosa
'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)'  // Azul
'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)'  // Verde
'linear-gradient(135deg, #fa709a 0%, #fee140 100%)'  // Laranja
'linear-gradient(135deg, #30cfd0 0%, #330867 100%)'  // Azul escuro
'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)'  // Azul claro
'linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)'  // Rosa claro
'linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)'  // Laranja claro
```

## 🚀 Como Usar

### 1. Para LLMs
1. **Leitura Obrigatória:** Ler este documento antes de criar qualquer componente
2. **Identificar Tipo:** Determinar tipo de conteúdo (serviços, produtos, features)
3. **Aplicar Template:** Usar template universal como base
4. **Personalizar:** Ajustar variáveis conforme necessidade

### 2. Para Desenvolvedores
1. **Copiar Template:** Usar arquivo como base
2. **Ajustar Parâmetros:** Modificar variáveis `{...}`
3. **Criar Tabela:** Implementar tabela opcional
4. **Integrar:** Adicionar ao sistema existente

### 3. Para Qualquer Site
1. **Adaptar Conteúdo:** Definir features conforme necessidade
2. **Personalizar Estilo:** Modificar cores e gradientes
3. **Ajustar Layout:** Modificar grid conforme design
4. **Implementar:** Integrar ao sistema existente

## 📋 Exemplos Práticos

### 🛒 E-commerce
```php
$config_component = [
    'titulo_secao' => 'Nossos Produtos',
    'descricao_secao' => 'Conheça nossa linha completa de produtos',
    'colunas_grid' => 'row-cols-1 row-cols-md-2 row-cols-lg-3',
    'classe_custom' => 'products-section',
    'mostrar_botao' => true,
    'gradiente_padrao' => 'linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)'
];

$features = [
    [
        'titulo' => 'Smartphones',
        'descricao' => 'Os melhores modelos com tecnologia de ponta',
        'icone' => 'fa-mobile-alt',
        'cor_fundo' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        'texto_botao' => 'Ver Produtos',
        'url_botao' => '/produtos/smartphones'
    ]
];
```

### 📝 Blog
```php
$config_component = [
    'titulo_secao' => 'Categorias',
    'descricao_secao' => 'Explore nossos principais temas',
    'colunas_grid' => 'row-cols-1 row-cols-lg-4',
    'classe_custom' => 'categories-section',
    'mostrar_botao' => true,
    'gradiente_padrao' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)'
];
```

### 🏢 SaaS
```php
$config_component = [
    'titulo_secao' => 'Recursos',
    'descricao_secao' => 'Ferramentas para otimizar seu negócio',
    'colunas_grid' => 'row-cols-1 row-cols-md-2 row-cols-lg-3',
    'classe_custom' => 'features-section',
    'mostrar_botao' => true,
    'gradiente_padrao' => 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)'
];
```

### 🏢 Institucional
```php
$config_component = [
    'titulo_secao' => 'Serviços',
    'descricao_secao' => 'Conheça nossas áreas de atuação',
    'colunas_grid' => 'row-cols-1 row-cols-lg-2',
    'classe_custom' => 'services-section',
    'mostrar_botao' => true,
    'gradiente_padrao' => 'linear-gradient(135deg, #30cfd0 0%, #330867 100%)'
];
```

## 📋 Checklist de Validação

### ✅ Antes de Entregar
- [ ] Leitura completa deste documento
- [ ] Template aplicado corretamente
- [ ] Variáveis substituídas
- [ ] Conteúdo personalizado conforme tipo de site
- [ ] Ícones FontAwesome implementados
- [ ] Responsividade testada

### ✅ Funcionalidades Essenciais
- [ ] Layout responsivo
- [ ] Gradientes visuais
- [ ] Animações suaves
- [ ] Links funcionais
- [ ] Acessibilidade WCAG AA

---

**🚨 IMPORTANTE:** Este documento deve ser lido OBRIGATORIAMENTE antes de criar qualquer componente columns with icons em qualquer projeto!
