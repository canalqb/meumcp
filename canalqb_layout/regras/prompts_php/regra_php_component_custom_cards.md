# 🎴 Regra Universal: Componente Custom Cards

## 📋 Objetivo
Este documento ensina todos os LLMs a criar cards personalizados universais que funcionem em QUALQUER tipo de site, baseados no padrão Bootstrap Features.

## 🎨 Componente de Referência (Apenas para Estudo)

### Custom Cards
- **Funcionalidade:** Cards com imagens de fundo e conteúdo sobreposto
- **Conceitos:** Sistema de apresentação visual com overlays de texto
- **Aplicação:** Ideal para apresentar produtos, portfólios, projetos, destinos

## 🏗️ Estrutura Universal

### 📁 Padrão de Arquivos (ADAPTÁVEL)
```
views/components/
├── custom_cards.php              # Componente principal
└── {projeto}_custom_cards.php     # Versão personalizada
```

### 🗄️ Padrão de Banco de Dados (OPCIONAL)
```sql
-- Tabela de Cards (se necessário)
CREATE TABLE {prefixo_cards} (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    imagem VARCHAR(500),
    texto_botao VARCHAR(255),
    url_botao VARCHAR(500),
    autor VARCHAR(255),
    localizacao VARCHAR(255),
    data DATE,
    categoria VARCHAR(100),
    ordem INT DEFAULT 0,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## 🎯 Template Universal: Custom Cards

### 📋 Estrutura Base PHP (TOTALMENTE UNIVERSAL)
```php
<?php
/**
 * Template Universal: Componente Custom Cards
 * 
 * Este template serve como base para criar cards personalizados
 * em QUALQUER tipo de site (e-commerce, blog, SaaS, institucional, etc.)
 * 
 * @version 1.0 - Universal
 * @author Sistema IA
 * 
 * Como usar:
 * 1. Copiar este arquivo para views/components/custom_cards.php
 * 2. Substituir variáveis entre {chaves}
 * 3. Ajustar conteúdo conforme necessidade do site
 * 4. Criar tabela no banco de dados (opcional)
 */

if (!defined('APP')) { http_response_code(403); exit('Acesso direto negado'); }

// Carregar cards do banco (opcional)
$cards = [];
if (isset($_GET['from_db']) && $_GET['from_db'] === 'true') {
    $cards = db()->query("SELECT * FROM {tabela_cards} WHERE ativo = 1 ORDER BY ordem, titulo")->fetchAll();
} else {
    // Cards estáticos (personalizar conforme necessidade)
    $cards = [
        [
            'titulo' => '{titulo_card_1}',
            'descricao' => '{descricao_card_1}',
            'imagem' => '{imagem_card_1}',
            'texto_botao' => '{texto_botao_1}',
            'url_botao' => '{url_botao_1}',
            'autor' => '{autor_card_1}',
            'localizacao' => '{localizacao_card_1}',
            'data' => '{data_card_1}'
        ],
        [
            'titulo' => '{titulo_card_2}',
            'descricao' => '{descricao_card_2}',
            'imagem' => '{imagem_card_2}',
            'texto_botao' => '{texto_botao_2}',
            'url_botao' => '{url_botao_2}',
            'autor' => '{autor_card_2}',
            'localizacao' => '{localizacao_card_2}',
            'data' => '{data_card_2}'
        ],
        [
            'titulo' => '{titulo_card_3}',
            'descricao' => '{descricao_card_3}',
            'imagem' => '{imagem_card_3}',
            'texto_botao' => '{texto_botao_3}',
            'url_botao' => '{url_botao_3}',
            'autor' => '{autor_card_3}',
            'localizacao' => '{localizacao_card_3}',
            'data' => '{data_card_3}'
        ]
    ];
}

// Configurações do componente
$config_component = [
    'titulo_secao' => '{titulo_secao}',
    'descricao_secao' => '{descricao_secao}',
    'colunas_grid' => '{colunas_grid}', // Ex: 'row-cols-1 row-cols-lg-3'
    'classe_custom' => '{classe_custom}',
    'mostrar_meta' => {mostrar_meta}, // true/false
    'altura_cards' => '{altura_cards}' // Ex: 'h-100'
];
?>

<!-- HTML do Componente -->
<section class="{classe_custom}" aria-labelledby="custom-cards-title">
    <div class="container px-4 py-5">
        <h2 id="custom-cards-title" class="pb-2 border-bottom">
            <?php echo htmlspecialchars($config_component['titulo_secao']); ?>
        </h2>
        
        <?php if (!empty($config_component['descricao_secao'])): ?>
            <p class="lead mb-4">
                <?php echo htmlspecialchars($config_component['descricao_secao']); ?>
            </p>
        <?php endif; ?>
        
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 align-items-stretch g-4 py-5">
            <?php foreach ($cards as $index => $card): ?>
                <div class="col">
                    <div class="card card-cover <?php echo $config_component['altura_cards']; ?> overflow-hidden text-white bg-dark rounded-5 shadow-lg" 
                         style="background-image: url('<?php echo htmlspecialchars($card['imagem']); ?>');">
                        <div class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                            <h3 class="pt-5 mt-5 mb-4 display-6 lh-1 fw-bold">
                                <?php echo htmlspecialchars($card['titulo']); ?>
                            </h3>
                            
                            <?php if ($config_component['mostrar_meta']): ?>
                                <ul class="d-flex list-unstyled mt-auto">
                                    <li class="me-auto">
                                        <?php if (!empty($card['autor'])): ?>
                                            <img src="<?php echo htmlspecialchars($card['autor']); ?>" 
                                                 alt="<?php echo htmlspecialchars($card['titulo']); ?>" 
                                                 width="32" height="32" 
                                                 class="rounded-circle border border-white">
                                        <?php endif; ?>
                                    </li>
                                    <?php if (!empty($card['localizacao'])): ?>
                                        <li class="d-flex align-items-center me-3">
                                            <i class="fas fa-map-marker-alt me-2"></i>
                                            <small><?php echo htmlspecialchars($card['localizacao']); ?></small>
                                        </li>
                                    <?php endif; ?>
                                    <?php if (!empty($card['data'])): ?>
                                        <li class="d-flex align-items-center">
                                            <i class="fas fa-calendar me-2"></i>
                                            <small><?php echo htmlspecialchars($card['data']); ?></small>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CSS Universal -->
<style>
.card-cover {
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    position: relative;
    overflow: hidden;
}

.card-cover::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.7) 100%);
    z-index: 1;
}

.card-cover .d-flex {
    position: relative;
    z-index: 2;
}

.card-cover h3 {
    color: white;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    margin-bottom: 1rem;
    font-weight: 700;
}

.card-cover p {
    color: rgba(255,255,255,0.9);
    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    margin-bottom: 1rem;
    line-height: 1.6;
}

.card-cover .list-unstyled {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.card-cover .list-unstyled li {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.card-cover .list-unstyled li img {
    border-radius: 50%;
    border: 2px solid white;
}

.card-cover .list-unstyled li i {
    color: rgba(255,255,255,0.9);
    font-size: 0.875rem;
}

.card-cover .list-unstyled small {
    color: rgba(255,255,255,0.8);
    font-size: 0.75rem;
}

.rounded-5 {
    border-radius: 0.5rem;
}

.shadow-lg {
    box-shadow: 0 1rem 3rem rgba(0,0,0,0.175) !important;
}

.text-shadow-1 {
    text-shadow: 1px 1px 2px rgba(0,0,0,0.5) !important;
}

/* Animações */
.card-cover {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card-cover:hover {
    transform: translateY(-5px);
    box-shadow: 0 1.5rem 4rem rgba(0,0,0,0.2);
}

/* Responsividade */
@media (max-width: 768px) {
    .card-cover h3 {
        font-size: 1.25rem;
    }
    
    .card-cover .list-unstyled {
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .card-cover .list-unstyled li {
        margin-bottom: 0.5rem;
    }
    
    .card-cover .list-unstyled li img {
        width: 24px;
        height: 24px;
    }
    
    .card-cover .list-unstyled li i {
        font-size: 0.75rem;
    }
    
    .card-cover .list-unstyled small {
        font-size: 0.7rem;
    }
}

/* Acessibilidade */
.card-cover:focus-within {
    outline: 2px solid rgba(255,255,255,0.5);
    outline-offset: 2px;
}

.card-cover h3:focus {
    outline: 2px solid rgba(255,255,255,0.5);
    outline-offset: 2px;
}
</style>

<!-- JavaScript (Opcional) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animação ao entrar no viewport
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '0';
                entry.target.style.transform = 'translateY(30px)';
                
                setTimeout(() => {
                    entry.target.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, 100);
                
                observer.unobserve(entry.target);
            }
        });
    });
    
    document.querySelectorAll('.card-cover').forEach(card => {
        observer.observe(card);
    });
    
    // Click nos cards (opcional)
    document.querySelectorAll('.card-cover').forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.closest('a')) {
                return; // Se clicou em um link, não faz nada
            }
            
            // Adicionar interação personalizada aqui
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
            }, 200);
        });
    });
    
    // Lazy loading das imagens
    const images = document.querySelectorAll('.card-cover');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                const bgStyle = window.getComputedStyle(img).backgroundImage;
                if (bgStyle && bgStyle !== 'none') {
                    // Imagem já está carregada
                    return;
                }
                
                // Carregar imagem
                const imageUrl = bgStyle.match(/url\(['"]?([^'"]+)['"]?\)/);
                if (imageUrl && imageUrl[1]) {
                    const tempImg = new Image();
                    tempImg.onload = () => {
                        img.style.backgroundImage = bgStyle;
                        observer.unobserve(img);
                    };
                    tempImg.src = imageUrl[1];
                }
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
});
</script>
```

## 🔧 Parâmetros de Configuração

### 📋 Variáveis Universal (ADAPTÁVEIS)
| Variável | Descrição | Exemplo |
|----------|-----------|---------|
| `{titulo_secao}` | Título da seção | "Nossos Projetos", "Portfólio" |
| `{descricao_secao}` | Descrição da seção | "Conheça nossos trabalhos" |
| `{colunas_grid}` | Classes Bootstrap grid | `row-cols-1 row-cols-sm-2 row-cols-md-3` |
| `{classe_custom}` | Classe CSS customizada | `portfolio-section`, 'projects-section' |
| `{mostrar_meta}` | Mostrar metadados | `true` ou `false` |
| `{altura_cards}` | Altura dos cards | `h-100` |

### 🎨 Tipos de Conteúdo
```php
// E-commerce
$cards = [
    [
        'titulo' => 'Produto Premium',
        'descricao' => 'Nosso melhor produto com garantia estendida',
        'imagem' => '/images/produtos/produto-1.jpg',
        'texto_botao' => 'Comprar Agora',
        'url_botao' => '/produtos/produto-1',
        'autor' => 'Marca XYZ',
        'localizacao' => 'São Paulo, SP',
        'data' => '2024-01-15'
    ]
];

// Blog/Portfólio
$cards = [
    [
        'titulo' => 'Projeto Web Moderno',
        'descricao' => 'Site institucional com design responsivo e moderno',
        'imagem' => '/images/projetos/projeto-1.jpg',
        'texto_botao' => 'Ver Projeto',
        'url_botao' => '/projetos/projeto-1',
        'autor' => 'João Designer',
        'localizacao' => 'Rio de Janeiro, RJ',
        'data' => '2024-01-15'
    ]
];

// SaaS
$cards = [
    [
        'titulo' => 'Dashboard Analytics',
        'descricao' => 'Painel completo de métricas e relatórios',
        'imagem' => '/images/screenshots/dashboard-1.jpg',
        'texto_botao' => 'Ver Demo',
        'url_botao' => '/demo/dashboard',
        'autor' => 'Tech Company',
        'localizacao' => 'São Paulo, SP',
        'data' => '2024-01-15'
    ]
];
```

### 🎨 Dimensões de Cards
```php
// Alturas disponíveis
'h-100'  // 400px altura
'h-150'  // 600px altura
'h-200'  800px altura
'h-250'   // 1000px altura
'h-300'  // 1200px altura
'h-400'  // 1600px altura
'h-500'  // 2000px altura
```

## 🚀 Como Usar

### 1. Para LLMs
1. **Leitura Obrigatória:** Ler este documento antes de criar qualquer componente
2. **Identificar Tipo:** Determinar tipo de conteúdo (produtos, portfólio, projetos)
3. **Aplicar Template:** Usar template universal como base
4. **Personalizar:** Ajustar variáveis conforme necessidade

### 2. Para Desenvolvedores
1. **Copiar Template:** Usar arquivo como base
2. **Ajustar Parâmetros:** Modificar variáveis `{...}`
3. **Criar Tabela:** Implementar tabela opcional
4. **Integrar:** Adicionar ao sistema existente

### 3. Para Qualquer Site
1. **Adaptar Conteúdo:** Definir cards conforme necessidade
2. **Personalizar Estilo:** Modificar cores e layout
3. **Ajustar Dimensões:** Modificar altura conforme design
4. **Implementar:** Integrar ao sistema existente

## 📋 Exemplos Práticos

### 🛒 E-commerce
```php
$config_component = [
    'titulo_secao' => 'Produtos em Destaque',
    'descricao_secao' => 'Nossos produtos mais populares',
    'colunas_grid' => 'row-cols-1 row-cols-md-2 row-cols-lg-3',
    'classe_custom' => 'featured-products',
    'mostrar_meta' => true,
    'altura_cards' => 'h-200'
];
```

### 📝 Blog/Portfólio
```php
$config_component = [
    'titulo_secao' => 'Portfólio',
    'descricao_secao' => 'Nossos trabalhos mais recentes',
    'colunas_grid' => 'row-cols-1 row-cols-md-2',
    'classe_custom' => 'portfolio-section',
    'mostrar_meta' => true,
    'altura_cards' => 'h-250'
];
```

### 🏢 SaaS
```php
$config_component = [
    'titulo_secao' => 'Cases de Sucesso',
    'descricao_secao' => 'Clientes que utilizam nossa plataforma',
    'colunas_grid' => 'row-cols-1 row-cols-lg-3',
    'classe_custom' => 'success-stories',
    'mostrar_meta' => true,
    'altura_cards' => 'h-200'
];
```

### 🏢 Institucional
```php
$config_component = [
    'titulo_secao' => 'Serviços',
    'descricao_secao' => 'Conheça nossas especialidades',
    'colunas_grid' => 'row-cols-1 row-cols-lg-2',
    'classe_custom' => 'services-section',
    'mostrar_meta' => false,
    'altura_cards' => 'h-150'
];
```

## 📋 Checklist de Validação

### ✅ Antes de Entregar
- [ ] Leitura completa deste documento
- [ ] Template aplicado corretamente
- [ ] Variáveis substituídas
- [ ] Conteúdo personalizado conforme tipo de site
- [ ] Imagens implementadas
- [ ] Responsividade testada

### ✅ Funcionalidades Essenciais
- [ ] Layout responsivo
- [ ] Overlay de texto legível
- [ ] Animações suaves
- [ ] Links funcionais
- [ ] Acessibilidade WCAG AA
- [ ] Lazy loading de imagens

---

**🚨 IMPORTANTE:** Este documento deve ser lido OBRIGATORIAMENTE antes de criar qualquer componente custom cards em qualquer projeto!
