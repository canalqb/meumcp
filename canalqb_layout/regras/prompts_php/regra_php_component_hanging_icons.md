# 🎯 Regra Universal: Componente Hanging Icons

## 📋 Objetivo
Este documento ensina todos os LLMs a criar layouts com ícones pendurados universais que funcionam em QUALQUER tipo de site, baseados no padrão Bootstrap Features.

## 🎨 Componente de Referência (Apenas para Estudo)

### Hanging Icons
- **Funcionalidade:** Layout com ícones alinhados à esquerda do conteúdo
- **Conceitos:** Sistema de apresentação com ícones quadrados e conteúdo textual
- **Aplicação:** Ideal para apresentar recursos, benefícios, etapas, processos

## 🏗️ Estrutura Universal

### 📁 Padrão de Arquivos (ADAPTÁVEL)
```
views/components/
├── hanging_icons.php              # Componente principal
└── {projeto}_hanging_icons.php     # Versão personalizada
```

### 🗄️ Padrão de Banco de Dados (OPCIONAL)
```sql
-- Tabela de Recursos (se necessário)
CREATE TABLE {prefixo_recursos} (
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

## 🎯 Template Universal: Hanging Icons

### 📋 Estrutura Base PHP (TOTALMENTE UNIVERSAL)
```php
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
    'mostrar_botao' => {mostrar_botao}, // true/false
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
```

## 🔧 Parâmetros de Configuração

### 📋 Variáveis Universal (ADAPTÁVEIS)
| Variável | Descrição | Exemplo |
|----------|-----------|---------|
| `{titulo_secao}` | Título da seção | "Nossos Recursos", "Nossos Benefícios" |
| `{descricao_secao}` | Descrição da seção | "Conheça nossas vantagens" |
| `{colunas_grid}` | Classes Bootstrap grid | `row-cols-1 row-cols-lg-3` |
| `{classe_custom}` | Classe CSS customizada | `resources-section`, 'benefits-section' |
| `{mostrar_botao}` | Mostrar botões | `true` ou `false` |
| `{cor_fundo_padrao}` | Cor de fundo padrão | `#f8f9fa` |

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
'fa-check-circle'    // Check
'fa-times-circle'    // X
'fa-plus-circle'     // Plus
'fa-minus-circle'    // Minus
'fa-info-circle'     // Info
'fa-exclamation-circle' // Exclamação
'fa-question-circle'  // Questão
```

### 🎨 Cores de Fundo
```php
// Cores populares
'#f8f9fa'           // Cinza claro
'#e9ecef'           // Cinza médio
'#dee2e6'           // Cinza escuro
'#fff3cd'           // Amarelo claro
'#ffeaa7'           // Amarelo médio
'#fff'              // Branco
'#d4edda'           // Verde claro
'#c3e6cb'           // Verde médio
'#28a745'           // Verde escuro
'#d1ecf1'           // Azul claro
'#bee5eb'           // Azul médio
'#17a2b8'           // Azul escuro
'#f8d7da'           // Rosa claro
'#f5c6cb'           // Rosa médio
'#dc3545'           // Rosa escuro
'#e2e3e5'           // Cinza
'#d6d8db'           // Cinza
'#ced4da'           // Cinza
'#adb5bd'           // Cinza
'#6c757d'           // Cinza escuro
'#495057'           // Cinza muito escuro
'#343a40'           // Preto
```

## 🚀 Como Usar

### 1. Para LLMs
1. **Leitura Obrigatória:** Ler este documento antes de criar qualquer componente
2. **Identificar Tipo:** Determinar tipo de conteúdo (recursos, benefícios, etapas)
3. **Aplicar Template:** Usar template universal como base
4. **Personalizar:** Ajustar variáveis conforme necessidade

### 2. Para Desenvolvedores
1. **Copiar Template:** Usar arquivo como base
2. **Ajustar Parâmetros:** Modificar variáveis `{...}`
3. **Criar Tabela:** Implementar tabela opcional
4. **Integrar:** Adicionar ao sistema existente

### 3. Para Qualquer Site
1. **Adaptar Conteúdo:** Definir recursos conforme necessidade
2. **Personalizar Estilo:** Modificar cores e ícones
3. **Ajustar Layout:** Modificar grid conforme design
4. **Implementar:** Integrar ao sistema existente

## 📋 Exemplos Práticos

### 🛒 E-commerce
```php
$config_component = [
    'titulo_secao' => 'Vantagens',
    'descricao_secao' => 'Por que escolher nossos produtos',
    'colunas_grid' => 'row-cols-1 row-cols-md-2 row-cols-lg-3',
    'classe_custom' => 'benefits-section',
    'mostrar_botao' => true,
    'cor_fundo_padrao' => '#f8f9fa'
];

$recursos = [
    [
        'titulo' => 'Frete Grátis',
        'descricao' => 'Entrega rápida e segura para todo Brasil',
        'icone' => 'fa-truck',
        'cor_fundo' => '#d4edda',
        'texto_botao' => 'Saiba Mais',
        'url_botao' => '/entrega'
    ]
];
```

### 📝 Blog
```php
$config_component = [
    'titulo_secao' => 'Recursos',
    'descricao_secao' => 'Ferramentas para seu blog',
    'colunas_grid' => 'row-cols-1 row-cols-lg-2',
    'classe_custom' => 'resources-section',
    'mostrar_botao' => true,
    'cor_fundo_padrao' => '#e9ecef'
];
```

### 🏢 SaaS
```php
$config_component = [
    'titulo_secao' => 'Funcionalidades',
    'descricao_secao' => 'Recursos disponíveis em nosso plano',
    'colunas_grid' => 'row-cols-1 row-cols-md-2 row-cols-lg-3',
    'classe_custom' => 'features-section',
    'mostrar_botao' => true,
    'cor_fundo_padrao' => '#e2e3e5'
];
```

### 🏢 Institucional
```php
$config_component = [
    'titulo_secao' => 'Serviços',
    'descricao_secao' => 'Conheça nossas especialidades',
    'colunas_grid' => 'row-cols-1 row-cols-lg-2',
    'classe_custom' => 'services-section',
    'mostrar_botao' => true,
    'cor_fundo_padrao' => '#f8f9fa'
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
- [ ] Alinhamento correto dos ícones
- [ ] Animações suaves
- [ ] Botões funcionais
- [ ] Acessibilidade WCAG AA

---

**🚨 IMPORTANTE:** Este documento deve ser lido OBRIGATORIAMENTE antes de criar qualquer componente hanging icons em qualquer projeto!
