# 🎯 Regra Universal: Componente Icon Grid

## 📋 Objetivo
Este documento ensina todos os LLMs a criar grids de ícones universais que funcionem em QUALQUER tipo de site, baseados no padrão Bootstrap Features.

## 🎨 Componente de Referência (Apenas para Estudo)

### Icon Grid
- **Funcionalidade:** Grid de ícones com títulos e descrições
- **Conceitos:** Sistema de apresentação de múltiplos recursos em formato compacto
- **Aplicação:** Ideal para apresentar features, serviços, benefícios, recursos

## 🏗️ Estrutura Universal

### 📁 Padrão de Arquivos (ADAPTÁVEL)
```
views/components/
├── icon_grid.php              # Componente principal
└── {projeto}_icon_grid.php     # Versão personalizada
```

### 🗄️ Padrão de Banco de Dados (OPCIONAL)
```sql
-- Tabela de Ícones (se necessário)
CREATE TABLE {prefixo_icons} (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    icone VARCHAR(100),
    cor_icone VARCHAR(7),
    categoria VARCHAR(100),
    ordem INT DEFAULT 0,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## 🎯 Template Universal: Icon Grid

### 📋 Estrutura Base PHP (TOTALMENTE UNIVERSAL)
```php
<?php
/**
 * Template Universal: Componente Icon Grid
 * 
 * Este template serve como base para criar grids de ícones
 * em QUALQUER tipo de site (e-commerce, blog, SaaS, institucional, etc.)
 * 
 * @version 1.0 - Universal
 * @author Sistema IA
 * 
 * Como usar:
 * 1. Copiar este arquivo para views/components/icon_grid.php
 * 2. Substituir variáveis entre {chaves}
 * 3. Ajustar conteúdo conforme necessidade do site
 * 4. Criar tabela no banco de dados (opcional)
 */

if (!defined('APP')) { http_response_code(403); exit('Acesso direto negado'); }

// Carregar ícones do banco (opcional)
$icones = [];
if (isset($_GET['from_db']) && $_GET['from_db'] === 'true') {
    $icones = db()->query("SELECT * FROM {tabela_icons} WHERE ativo = 1 ORDER BY ordem, titulo")->fetchAll();
} else {
    // Ícones estáticos (personalizar conforme necessidade)
    $icones = [
        [
            'titulo' => '{titulo_icone_1}',
            'descricao' => '{descricao_icone_1}',
            'icone' => '{icone_1}',
            'cor_icone' => '{cor_icone_1}'
        ],
        [
            'titulo' => '{titulo_icone_2}',
            'descricao' => '{descricao_icone_2}',
            'icone' => '{icone_2}',
            'cor_icone' => '{cor_icone_2}'
        ],
        [
            'titulo' => '{titulo_icone_3}',
            'descricao' => '{descricao_icone_3}',
            'icone' => '{icone_3}',
            'cor_icone' => '{cor_icone_3}'
        ],
        [
            'titulo' => '{titulo_icone_4}',
            'descricao' => '{descricao_icone_4}',
            'icone' => '{icone_4}',
            'cor_icone' => '{cor_icone_4}'
        ],
        [
            'titulo' => '{titulo_icone_5}',
            'descricao' => '{descricao_icone_5}',
            'icone' => '{icone_5}',
            'cor_icone' => '{cor_icone_5}'
        ],
        [
            'titulo' => '{titulo_icone_6}',
            'descricao' => '{descricao_icone_6}',
            'icone' => '{icone_6}',
            'cor_icone' => '{cor_icone_6}'
        ],
        [
            'titulo' => '{titulo_icone_7}',
            'descricao' => '{descricao_icone_7}',
            'icone' => '{icone_7}',
            'cor_icone' => '{cor_icone_7}'
        ],
        [
            'titulo' => '{titulo_icone_8}',
            'descricao' => '{descricao_icone_8}',
            'icone' => '{icone_8}',
            'cor_icone' => '{cor_icone_8}'
        ]
    ];
}

// Configurações do componente
$config_component = [
    'titulo_secao' => '{titulo_secao}',
    'descricao_secao' => '{descricao_secao}',
    'colunas_grid' => '{colunas_grid}', // Ex: 'row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4'
    'classe_custom' => '{classe_custom}',
    'cor_icone_padrao' => '{cor_icone_padrao}'
];
?>

<!-- HTML do Componente -->
<section class="{classe_custom}" aria-labelledby="icon-grid-title">
    <div class="container px-4 py-5">
        <h2 id="icon-grid-title" class="pb-2 border-bottom">
            <?php echo htmlspecialchars($config_component['titulo_secao']); ?>
        </h2>
        
        <?php if (!empty($config_component['descricao_secao'])): ?>
            <p class="lead mb-4">
                <?php echo htmlspecialchars($config_component['descricao_secao']); ?>
            </p>
        <?php endif; ?>
        
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 py-5">
            <?php foreach ($icones as $index => $icone): ?>
                <div class="col d-flex align-items-start">
                    <svg class="bi text-muted flex-shrink-0 me-3" 
                         width="1.75em" height="1.75em"
                         style="color: <?php echo $icone['cor_icone'] ?: $config_component['cor_icone_padrao']; ?>;">
                        <use xlink:href="#<?php echo $icone['icone']; ?>"/>
                    </svg>
                    <div>
                        <h4 class="fw-bold mb-0"><?php echo htmlspecialchars($icone['titulo']); ?></h4>
                        <p><?php echo htmlspecialchars($icone['descricao']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- SVG Icons (FontAwesome) -->
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="bootstrap" viewBox="0 0 118 94">
        <title>Bootstrap</title>
        <path fill-rule="evenodd" clip-rule="evenodd" d="M24.509 0c-6.733 0-11.715 5.893-11.492 12.284.214 6.14-.064 14.092-2.066 20.577C8.943 39.365 5.547 43.485 0 44.014v5.972c5.547.529 8.943 4.649 10.951 11.153 2.002 6.485 2.28 14.437 2.066 20.577C12.794 88.106 17.776 94 24.51 94H93.5c6.733 0 11.714-5.893 11.491-12.284-.214-6.14.064-14.092 2.066-20.577 2.009-6.504 5.396-10.624 10.943-11.153v-5.972c-5.547-.529-8.934-4.649-10.943-11.153-2.002-6.484-2.28-14.437-2.066-20.577C105.214 5.894 100.233 0 93.5 0H24.508zM80 57.863C80 66.663 73.436 72 62.543 72H44a2 2 0 01-2-2V24a2 2 0 012-2h18.437c9.083 0 15.044 4.92 15.044 12.474 0 5.302-4.01 10.049-9.119 10.88v.277C75.317 46.394 80 51.21 80 57.863zM60.521 28.34H49.948v14.934h8.905c6.884 0 10.68-2.772 10.68-7.727 0-4.643-3.264-7.207-9.012-7.207zM49.948 49.2v16.458H60.91c7.167 0 10.964-2.876 10.964-8.281 0-5.406-3.903-8.178-11.425-8.178H49.948z"></path>
    </symbol>
    <symbol id="home" viewBox="0 0 16 16">
        <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5z"/>
    </symbol>
    <symbol id="speedometer2" viewBox="0 0 16 16">
        <path d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4zM3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 10a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 10zm9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5zm.754-4.246a.389.389 0 0 0-.527-.02L7.547 9.31a.91.91 0 1 0 1.302 1.258l3.434-4.297a.389.389 0 0 0-.029-.518z"/>
        <path fill-rule="evenodd" d="M0 10a8 8 0 1 1 15.547 2.661c-.442 1.253-1.845 1.602-2.932 1.25C11.309 13.488 9.475 13 8 13c-1.474 0-3.31.488-4.615.911-1.087.352-2.49.003-2.932-1.25A7.988 7.988 0 0 1 0 10zm8-7a7 7 0 0 0-6.603 9.329c.203.575.923.876 1.68.63C4.397 12.533 6.358 12 8 12s3.604.532 4.923.96c.757.245 1.477-.056 1.68-.631A7 7 0 0 0 8 3z"/>
    </symbol>
    <symbol id="table" viewBox="0 0 16 16">
        <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm15 2h-4v3h4V4zm0 4h-4v3h4V8zm0 4h-4v3h3a1 1 0 0 0 1-1v-2zm-5 3v-3H6v3h4zm-5 0v-3H1v2a1 1 0 0 0 1 1h3zm-4-4h4V8H1v3zm0-4h4V4H1v3zm5-3v3h4V4H6zm4 4H6v3h4V8z"/>
    </symbol>
    <symbol id="people-circle" viewBox="0 0 16 16">
        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
    </symbol>
    <symbol id="grid" viewBox="0 0 16 16">
        <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3A1.5 1.5 0 0 1 13.5 15h-3A1.5 1.5 0 0 1 9 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z"/>
    </symbol>
    <symbol id="collection" viewBox="0 0 16 16">
        <path d="M2.5 3.5a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-11zm2-2a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1h-7zM0 13a1.5 1.5 0 0 0 1.5 1.5h13A1.5 1.5 0 0 0 16 13V6a1.5 1.5 0 0 0-1.5-1.5h-13A1.5 1.5 0 0 0 0 6v7zm1.5.5A.5.5 0 0 1 1 13V6a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-13z"/>
    </symbol>
    <symbol id="calendar3" viewBox="0 0 16 16">
        <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z"/>
        <path d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
    </symbol>
    <symbol id="chat-quote-fill" viewBox="0 0 16 16">
        <path d="M16 8c0 3.866-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.584.296-1.925.864-4.181 1.234-.2.032-.352-.176-.273-.362.354-.836.674-1.95.77-2.966C.744 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7zM7.194 6.766a1.688 1.688 0 0 0-.227-.272 1.467 1.467 0 0 0-.469-.324l-.008-.004A1.785 1.785 0 0 0 5.734 6C4.776 6 4 6.746 4 7.667c0 .92.776 1.666 1.734 1.666.343 0 .662-.095.931-.26-.137.389-.39.804-.81 1.22a.405.405 0 0 0 .011.59c.173.16.447.155.614-.01 1.334-1.329 1.37-2.758.941-3.706a2.461 2.461 0 0 0-.227-.4zM11 9.073c-.136.389-.39.804-.81 1.22a.405.405 0 0 0 .012.59c.172.16.446.155.613-.01 1.334-1.329 1.37-2.758.942-3.706a2.466 2.466 0 0 0-.228-.4 1.686 1.686 0 0 0-.227-.273 1.466 1.466 0 0 0-.469-.324l-.008-.004A1.785 1.785 0 0 0 10.07 6c-.957 0-1.734.746-1.734 1.667 0 .92.777 1.666 1.734 1.666.343 0 .662-.095.931-.26z"/>
    </symbol>
    <symbol id="cpu-fill" viewBox="0 0 16 16">
        <path d="M6.5 6a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z"/>
        <path d="M5.5.5a.5.5 0 0 0-1 0V2A2.5 2.5 0 0 0 2 4.5H.5a.5.5 0 0 0 0 1H2v1H.5a.5.5 0 0 0 0 1H2v1H.5a.5.5 0 0 0 0 1H2v1H.5a.5.5 0 0 0 0 1H2A2.5 2.5 0 0 0 4.5 14v1.5a.5.5 0 0 0 1 0V14h1v1.5a.5.5 0 0 0 1 0V14h1v1.5a.5.5 0 0 0 1 0V14h1v1.5a.5.5 0 0 0 1 0V14a2.5 2.5 0 0 0 2.5-2.5h1.5a.5.5 0 0 0 0-1H14v-1h1.5a.5.5 0 0 0 0-1H14v-1h1.5a.5.5 0 0 0 0-1H14A2.5 2.5 0 0 0 11.5 2V.5a.5.5 0 0 0-1 0V2h-1V.5a.5.5 0 0 0-1 0V2h-1V.5a.5.5 0 0 0-1 0V2h-1V.5zm1 4.5h3A1.5 1.5 0 0 1 11 6.5v3A1.5 1.5 0 0 1 9.5 11h-3A1.5 1.5 0 0 1 5 9.5v-3A1.5 1.5 0 0 1 6.5 5z"/>
    </symbol>
    <symbol id="gear-fill" viewBox="0 0 16 16">
        <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
    </symbol>
    <symbol id="speedometer" viewBox="0 0 16 16">
        <path d="M8 2a.5.5 0 0 1 .5.5V4a.5.5 0 0 1-1 0V2.5A.5.5 0 0 1 8 2zM3.732 3.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 8a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 8zm9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5zm.754-4.246a.389.389 0 0 0-.527-.02L7.547 7.31A.91.91 0 1 0 8.85 8.569l3.434-4.297a.389.389 0 0 0-.029-.518z"/>
        <path fill-rule="evenodd" d="M6.664 15.889A8 8 0 1 1 9.336.11a8 8 0 0 1-2.672 15.78zm-4.665-4.283A11.945 11.945 0 0 1 8 10c2.186 0 4.236.585 6.001 1.606a7 7 0 1 0-12.002 0z"/>
    </symbol>
    <symbol id="toggles2" viewBox="0 0 16 16">
        <path d="M9.465 10H12a2 2 0 1 1 0 4H9.465c.34-.588.535-1.271.535-2 0-.729-.195-1.412-.535-2z"/>
        <path d="M6 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 1a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm.535-10a3.975 3.975 0 0 1-.409-1H4a1 1 0 0 1 0-2h2.126c.091-.355.23-.69.41-1H4a2 2 0 1 0 0 4h2.535z"/>
        <path d="M14 4a4 4 0 1 1-8 0 4 4 0 0 1 8 0z"/>
    </symbol>
    <symbol id="tools" viewBox="0 0 16 16">
        <path d="M1 0L0 1l2.2 3.081a1 1 0 0 0 .815.419h.07a1 1 0 0 1 .708.293l2.675 2.675-2.617 2.654A3.003 3.003 0 0 0 0 13a3 3 0 1 0 5.878-.851l2.654-2.617.968.968-.305.914a1 1 0 0 0 .242 1.023l3.356 3.356a1 1 0 0 0 1.414 0l1.586-1.586a1 1 0 0 0 0-1.414l-3.356-3.356a1 1 0 0 0-1.023-.242L10.5 9.5l-.96-.96 2.68-2.643A3.005 3.005 0 0 0 16 3c0-.269-.035-.53-.102-.777l-2.14 2.141L12 4l-.364-1.757L13.777.102a3 3 0 0 0-3.675 3.68L7.462 6.46 4.793 3.793a1 1 0 0 1-.293-.707v-.071a1 1 0 0 0-.419-.814L1 0zm9.646 10.646a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708zM3 11l.471.242.529.026.287.445.445.287.026.529L5 13l-.242.471-.026.529-.445.287-.287.445-.529.026L3 11z"/>
    </symbol>
    <symbol id="chevron-right" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
    </symbol>
    <symbol id="geo-fill" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M4 4a4 4 0 1 1 4.5 3.969V13.5a.5.5 0 0 1-1 0V7.97A4 4 0 0 1 4 3.999zm2.493 8.574a.5.5 0 0 1-.411.575c-.712.118-1.28.295-1.655.493a1.319 1.319 0 0 0-.37.265.301.301 0 0 0-.057.09V14l.002.008a.147.147 0 0 0 .016.033.617.617 0 0 0 .145.15c.165.13.435.27.813.395.751.25 1.82.414 3.024.414s2.273-.163 3.024-.414c.378-.126.648-.265.813-.395a.619.619 0 0 0 .146-.15.148.148 0 0 0 .015-.033L12 14v-.004a.301.301 0 0 0-.057-.09 1.318 1.318 0 0 0-.37-.264c-.376-.198-.943-.375-1.655-.493a.5.5 0 1 1 .164-.986c.77.127 1.452.328 1.957.594C12.5 13 13 13.4 13 14c0 .426-.26.752-.544.977-.29.228-.68.413-1.116.558-.878.293-2.059.465-3.34.465-1.281 0-2.462-.172-3.34-.465-.436-.145-.826-.33-1.116-.558C3.26 14.752 3 14.426 3 14c0-.599.5-1 .961-1.243.505-.266 1.187-.467 1.957-.594a.5.5 0 0 1 .575.411z"/>
    </symbol>
</svg>

<!-- CSS Universal -->
<style>
.icon-grid h4 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--text);
}

.icon-grid p {
    color: var(--muted);
    margin-bottom: 0;
    line-height: 1.5;
    font-size: 0.9rem;
}

.icon-grid svg {
    transition: transform 0.3s ease, color 0.3s ease;
}

.icon-grid svg:hover {
    transform: scale(1.1);
    color: var(--brand);
}

.icon-grid .col {
    transition: transform 0.3s ease;
}

.icon-grid .col:hover {
    transform: translateY(-3px);
}

/* Cores personalizadas */
.icon-grid svg.text-muted {
    color: var(--muted);
}

/* Responsividade */
@media (max-width: 768px) {
    .icon-grid h4 {
        font-size: 1rem;
    }
    
    .icon-grid p {
        font-size: 0.85rem;
    }
    
    .icon-grid svg {
        width: 1.5em;
        height: 1.5em;
    }
}

/* Acessibilidade */
.icon-grid svg:focus {
    outline: 2px solid var(--brand);
    outline-offset: 2px;
}

.icon-grid h4:focus {
    outline: 2px solid var(--brand);
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
                const icon = entry.target.querySelector('svg');
                const content = entry.target.querySelector('div');
                
                if (icon) {
                    icon.style.opacity = '0';
                    icon.style.transform = 'scale(0.8)';
                    
                    setTimeout(() => {
                        icon.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                        icon.style.opacity = '1';
                        icon.style.transform = 'scale(1)';
                    }, 100);
                }
                
                if (content) {
                    content.style.opacity = '0';
                    content.style.transform = 'translateY(10px)';
                    
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
    
    document.querySelectorAll('.icon-grid .col').forEach(col => {
        observer.observe(col);
    });
    
    // Click nos ícones (opcional)
    document.querySelectorAll('.icon-grid svg').forEach(icon => {
        icon.addEventListener('click', function() {
            // Adicionar interação personalizada aqui
            this.style.transform = 'scale(0.9)';
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
| `{titulo_secao}` | Título da seção | "Nossos Recursos", "Funcionalidades" |
| `{descricao_secao}` | Descrição da seção | "Conheça todas as funcionalidades" |
| `{colunas_grid}` | Classes Bootstrap grid | `row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4` |
| `{classe_custom}` | Classe CSS customizada | `resources-section`, 'features-section' |
| `{cor_icone_padrao}` | Cor padrão dos ícones | `#6c757d` |

### 🎨 Ícones Disponíveis (Bootstrap Icons)
```php
// Ícones mais comuns
'bootstrap'           // Framework
'home'               // Casa
'speedometer2'       // Velocímetro
'table'              // Tabela
'people-circle'      // Pessoas
'grid'               // Grid
'collection'         // Coleção
'calendar3'          // Calendário
'chat-quote-fill'    // Chat
'cpu-fill'           // CPU
'gear-fill'          // Engrenagem
'speedometer'        // Velocímetro
'toggles2'          // Toggle
'tools'              // Ferramentas
'chevron-right'      // Seta direita
'geo-fill'           // Localização
```

### 🎨 Cores de Ícones
```php
// Cores populares
'#6c757d'           // Cinza (muted)
'#007bff'           // Azul (primary)
'#28a745'           // Verde (success)
'#dc3545'           // Vermelho (danger)
'#ffc107'           // Amarelo (warning)
'#17a2b8'           // Azul claro (info)
'#343a40'           // Preto (dark)
'#6f42c1'           // Roxo
'#fd7e14'           // Laranja
'#20c997'           // Verde água
'#e83e8c'           // Rosa
'#6c757d'           // Cinza
'#495057'           // Cinza escuro
'#adb5bd'           // Cinza claro
'#f8f9fa'           // Cinza muito claro
```

## 🚀 Como Usar

### 1. Para LLMs
1. **Leitura Obrigatória:** Ler este documento antes de criar qualquer componente
2. **Identificar Tipo:** Determinar tipo de conteúdo (recursos, features, serviços)
3. **Aplicar Template:** Usar template universal como base
4. **Personalizar:** Ajustar variáveis conforme necessidade

### 2. Para Desenvolvedores
1. **Copiar Template:** Usar arquivo como base
2. **Ajustar Parâmetros:** Modificar variáveis `{...}`
3. **Criar Tabela:** Implementar tabela opcional
4. **Integrar:** Adicionar ao sistema existente

### 3. Para Qualquer Site
1. **Adaptar Conteúdo:** Definir ícones conforme necessidade
2. **Personalizar Estilo:** Modificar cores e layout
3. **Ajustar Grid:** Modificar número de colunas conforme design
4. **Implementar:** Integrar ao sistema existente

## 📋 Exemplos Práticos

### 🛒 E-commerce
```php
$config_component = [
    'titulo_secao' => 'Vantagens',
    'descricao_secao' => 'Por que comprar conosco',
    'colunas_grid' => 'row-cols-1 row-cols-sm-2 row-cols-md-3',
    'classe_custom' => 'advantages-section',
    'cor_icone_padrao' => '#007bff'
];
```

### 📝 Blog
```php
$config_component = [
    'titulo_secao' => 'Recursos',
    'descricao_secao' => 'Ferramentas do blog',
    'colunas_grid' => 'row-cols-1 row-cols-lg-4',
    'classe_custom' => 'blog-features',
    'cor_icone_padrao' => '#28a745'
];
```

### 🏢 SaaS
```php
$config_component = [
    'titulo_secao' => 'Funcionalidades',
    'descricao_secao' => 'Recursos da plataforma',
    'colunas_grid' => 'row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4',
    'classe_custom' => 'platform-features',
    'cor_icone_padrao' => '#6f42c1'
];
```

### 🏢 Institucional
```php
$config_component = [
    'titulo_secao' => 'Serviços',
    'descricao_secao' => 'O que oferecemos',
    'colunas_grid' => 'row-cols-1 row-cols-lg-3',
    'classe_custom' => 'services-section',
    'cor_icone_padrao' => '#17a2b8'
];
```

## 📋 Checklist de Validação

### ✅ Antes de Entregar
- [ ] Leitura completa deste documento
- [ ] Template aplicado corretamente
- [ ] Variáveis substituídas
- [ ] Conteúdo personalizado conforme tipo de site
- [ ] Ícones Bootstrap implementados
- [ ] Responsividade testada

### ✅ Funcionalidades Essenciais
- [ ] Layout responsivo
- [ ] Ícones funcionais
- [ ] Animações suaves
- [ ] Hover effects
- [ ] Acessibilidade WCAG AA

---

**🚨 IMPORTANTE:** Este documento deve ser lido OBRIGATORIAMENTE antes de criar qualquer componente icon grid em qualquer projeto!
