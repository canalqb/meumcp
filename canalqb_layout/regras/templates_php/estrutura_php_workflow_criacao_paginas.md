# 📋 Guia de Criação de Páginas - Sistema Modular

## 🎯 **Objetivo**

Estabelecer padrões e regras para criação de páginas no diretório `pages/` que sejam carregadas no container principal, garantindo consistência, responsividade e experiência unificada em todo o sistema.

---

## 🏗️ **Estrutura Base do Sistema**

### 📁 **Organização de Diretórios:**
```
pages/
├── admin/           # Páginas administrativas
├── user/            # Páginas de usuário
├── public/          # Páginas públicas
├── api/             # Endpoints de API
├── auth/            # Autenticação e login
└── components/       # Componentes reutilizáveis
```

### 🎯 **Arquivo Principal (index.php):**
```php
<?php
require_once 'config.php';

// Estrutura obrigatória do index.php
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Meta tags, CSS, recursos globais -->
</head>
<body>
    <!-- Navegação principal -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <!-- Conteúdo do menu -->
    </nav>
    
    <!-- Hero section (opcional) -->
    <section class="hero">
        <!-- Conteúdo principal -->
    </section>
    
    <!-- Conteúdo principal -->
    <main class="main-content">
        <!-- Páginas são carregadas aqui -->
    </main>
    
    <!-- Rodapé -->
    <footer class="footer">
        <!-- Conteúdo do footer -->
    </footer>
    
    <!-- Scripts globais -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

---

## 📝 **Padrões Obrigatórios**

### ✅ **Estrutura de Páginas em `pages/`:**

#### **🔹 NUNCA incluir tags HTML completas (Regra Estrutural PHP_UI_RULES):**
- Conforme o documento `regras/php_ui_rules.md`, o ÚNICO arquivo autorizado a declarar `<html>`, `<head>`, e `<body>` é o `index.php`.
- Todos os arquivos destas pastas são componentes e páginas injetadas no meio do `<main>`.

```php
<?php
// ❌ ERRADO - Não fazer isso (Quebra o DOM principal)
?>
<!DOCTYPE html>
<html>
<head>
    <!-- ... -->
</head>
<body>
    <!-- Conteúdo -->
</body>
</html>

// ✅ CORRETO - Apenas o conteúdo validado para ser injetado.
?>
<div class="container py-4">
    <!-- Conteúdo da página -->
</div>
```

#### **🔹 SEMPRE usar `container` para conteúdo principal:**
```php
<?php
if (!defined('APP_URL')) {
    require_once(realpath(__DIR__ . '/../config.php'));
}

// Verificação de permissões (se necessário)
if (!isAdmin()) {
    echo "<div class='alert alert-danger'>Acesso negado.</div>";
    exit;
}
?>
<div class="container py-4">
    <!-- Todo o conteúdo da página -->
</div>
```

#### **🔹 Para sub-containers, usar prefixo `sub_`:**
```php
<div class="container py-4">
    <h2>Página Principal</h2>
    
    <!-- Container principal -->
    <div class="row">
        <div class="col-md-8">
            <!-- Conteúdo principal -->
        </div>
        <div class="col-md-4">
            <!-- Sidebar -->
            
            <!-- Sub-container com prefixo -->
            <div class="sub_container bg-light p-3 rounded">
                <h5>Filtros</h5>
                <!-- Conteúdo do sub-container -->
            </div>
        </div>
    </div>
</div>
```

---

## 🎨 **Classes Bootstrap 5 Obrigatórias**

### 📋 **Estrutura Responsiva:**
```php
<div class="container py-4">
    <!-- Sistema de grid responsivo -->
    <div class="row g-4">
        <div class="col-lg-8 col-md-7">
            <!-- Conteúdo principal -->
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Título</h3>
                    <p class="card-text">Conteúdo...</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-5">
            <!-- Sidebar -->
            <div class="sub_container">
                <!-- Conteúdo secundário -->
            </div>
        </div>
    </div>
</div>
```

### 🎯 **Componentes Padrão:**
```php
<!-- Cards -->
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Título do Card</h5>
    </div>
    <div class="card-body">
        <!-- Conteúdo -->
    </div>
</div>

<!-- Tabelas -->
<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>Coluna 1</th>
                <th>Coluna 2</th>
            </tr>
        </thead>
        <tbody>
            <!-- Dados -->
        </tbody>
    </table>
</div>

<!-- Botões -->
<button class="btn btn-primary">
    <i class="fas fa-save me-2"></i>Salvar
</button>

<!-- Formulários -->
<form class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Campo</label>
        <input type="text" class="form-control" required>
    </div>
</form>
```

---

## 📋 **Template de Página Padrão**

### 🎯 **Estrutura Completa:**
```php
<?php
/**
 * PÁGINA: [Nome da Página]
 * DESCRIÇÃO: [Descrição detalhada]
 * AUTOR: [Nome do autor]
 * DATA: [Data de criação]
 * VERSÃO: [Versão da página]
 */

// Verificação de constantes e segurança
if (!defined('APP_URL')) {
    require_once(realpath(__DIR__ . '/../config.php'));
}

// Verificação de permissões (ajustar conforme necessário)
if (!isAdmin()) {
    echo "<div class='alert alert-danger m-3'>Acesso negado.</div>";
    exit;
}

// Título da página (para o navegador)
$page_title = "Título da Página";
$page_description = "Descrição para SEO";
?>

<!-- Conteúdo da página -->
<div class="container py-4">
    <!-- Header da página -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Título Principal <span class="text-primary">Destaque</span></h2>
            <p class="text-muted small mb-0">Descrição secundária</p>
        </div>
        <button class="btn btn-premium" data-bs-toggle="modal" data-bs-target="#modalAcao">
            <i class="fas fa-plus me-2"></i> Nova Ação
        </button>
    </div>

    <!-- Conteúdo principal -->
    <div class="row g-4">
        <!-- Coluna principal -->
        <div class="col-lg-8">
            <div class="card shadow-sm animate-fade">
                <div class="card-header bg-transparent border-secondary">
                    <h5 class="mb-0 fw-bold">Conteúdo Principal</h5>
                </div>
                <div class="card-body">
                    <!-- Conteúdo específico da página -->
                    <p>Conteúdo da página vai aqui...</p>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Sub-container com prefixo -->
            <div class="sub_container bg-light p-3 rounded mb-3">
                <h6 class="fw-bold mb-3">Informações</h6>
                <div class="list-group list-group-flush">
                    <div class="list-group-item px-0">
                        <small class="text-muted">Total de itens:</small>
                        <strong>0</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal (se necessário) -->
<div class="modal fade" id="modalAcao" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nova Ação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Formulário do modal -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript específico da página -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializações específicas da página
    console.log('Página carregada com sucesso');
});
</script>
```

---

## 🎨 **CSS Padrão e Animações**

### 📋 **Estilos Obrigatórios:**
```php
<style>
/* Animação padrão */
.animate-fade {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Cards padrão */
.card {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

/* Botões personalizados */
.btn-premium {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border: none;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-premium:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
</style>
```

---

## 📊 **Checklist de Validação**

### ✅ **Antes de Criar:**
- [ ] Diretório correto identificado (`pages/`, `pages/admin/`, etc.)
- [ ] Permissões de acesso definidas
- [ ] Estrutura base planejada
- [ ] Componentes Bootstrap selecionados

### ✅ **Durante Criação:**
- [ ] Usar `container py-4` para conteúdo principal
- [ ] Usar `sub_container` para containers internos
- [ ] Incluir classes responsivas do Bootstrap 5
- [ ] Adicionar animações padrão

### ✅ **Após Criar:**
- [ ] Testar responsividade (mobile, tablet, desktop)
- [ ] Verificar espaçamento lateral adequado
- [ ] Validar consistência visual
- [ ] Testar funcionalidades JavaScript

---

## 🎯 **Exemplos Práticos**

### 📋 **Página de Listagem:**
```php
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Gerenciar <span class="text-primary">Itens</span></h2>
        <button class="btn btn-premium" data-bs-toggle="modal" data-bs-target="#modalNovo">
            <i class="fas fa-plus me-2"></i> Novo Item
        </button>
    </div>

    <div class="card animate-fade">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dados da tabela -->
                </tbody>
            </table>
        </div>
    </div>
</div>
```

### 📝 **Página de Formulário:**
```php
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm animate-fade">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Formulário</h5>
                </div>
                <div class="card-body">
                    <form class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Campo</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Salvar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
```

---

## 🚀 **Boas Práticas**

### ✅ **Performance:**
- **Carregar CSS/JS** apenas quando necessário
- **Otimizar imagens** e recursos
- **Usar cache** quando possível
- **Minificar código** em produção

### 📱 **Responsividade:**
- **Mobile-first** no design
- **Testar em múltiplos** dispositivos
- **Usar breakpoints** do Bootstrap
- **Garantir usabilidade** em telas pequenas

### 🔒 **Segurança:**
- **Validar todos** os inputs
- **Escapar dados** de usuário
- **Usar prepared statements** no BD
- **Implementar CSRF** tokens

### ♿ **Acessibilidade e Design (OBRIGATÓRIO):**
- **Consultar obrigatoriamente** `regras/php_ui_rules.md`
- **Usar tags semânticas** corretas (`<main>`, `<article>`, `<section>`, etc.)
- **Foco visível** e interação via teclado (Nunca remova outline)
- **Contraste de cores** mínimo WCAG 2.2 AA (4.5:1 ou 3:1)
- **Tema escuro/claro** implementado corretamente nos containers

---

## 📋 **Integração com Sistema Existente**

### 🔗 **Navegação:**
- **Links relativos** ao sistema
- **Usar constantes** APP_URL
- **Manter consistência** no menu
- **Breadcrumb** para hierarquia

### 🎨 **Temas:**
- **Respeitar variáveis** CSS do sistema
- **Usar classes** temáticas
- **Manter coerência** visual
- **Testar modo** claro/escuro

### 📊 **Dados:**
- **Usar PDO** para banco
- **Tratar erros** adequadamente
- **Validar dados** antes de salvar
- **Logar atividades** importantes

---

## 🎯 **Recursos Úteis**

### 📚 **Documentação Bootstrap 5:**
- [Grid System](https://getbootstrap.com/docs/5.3/layout/grid/)
- [Components](https://getbootstrap.com/docs/5.3/components/)
- [Utilities](https://getbootstrap.com/docs/5.3/utilities/)
- [Forms](https://getbootstrap.com/docs/5.3/forms/)

### 🎨 **Ferramentas:**
- **Bootstrap Icons** para ícones
- **Font Awesome** para ícones adicionais
- **Google Fonts** para tipografia
- **Animate.css** para animações extras

### 🔧 **Debug:**
- **DevTools** do navegador
- **Bootstrap Validator** para forms
- **Responsive Tester** para mobile
- **Performance Monitor** para otimização

---

## 📊 **Padrão de Qualidade**

| Característica | Requisito | Status |
|----------------|------------|---------|
| Espaçamento lateral | ✅ Obrigatório | `container py-4` |
| Responsividade | ✅ Obrigatório | Bootstrap 5 |
| Consistência visual | ✅ Obrigatório | Classes padrão e Tema (Claro/Escuro) |
| Sem tags HTML/BODY | ✅ CRÍTICO | Exclusividade do `index.php` |
| Conformidade WCAG 2.2 | ✅ Obrigatório | Seguir `php_ui_rules.md` |
| Performance | ✅ Recomendado | Otimizado |
| Segurança | ✅ Obrigatório | Validação |

---

## 🎉 **Conclusão**

### ✅ **Sistema Padronizado:**
- **Estrutura consistente** em todas as páginas
- **Experiência unificada** para usuários
- **Manutenção simplificada** para desenvolvedores
- **Escalabilidade garantida** para o futuro

### 🚀 **Pronto para Uso:**
- **Templates disponíveis** para início rápido
- **Regras claras** para desenvolvimento
- **Exemplos práticos** para consulta
- **Qualidade garantida** para produção

---

**Versão:** 1.0.0  
**Data:** 26/02/2026  
**Responsável:** Sistema Modular  
**Status:** ✅ **PADRÃO ESTABELECIDO**
