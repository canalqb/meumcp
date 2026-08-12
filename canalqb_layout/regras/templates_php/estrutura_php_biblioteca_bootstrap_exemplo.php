<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{titulo_pagina}}</title>
    
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/yourkitid.js" crossorigin="anonymous"></script>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- CSS customizado -->
    <link href="{{css_customizado_url}}" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" href="{{favicon_url}}" type="image/x-icon">
    
    <!-- CSS interno complementar -->
    <style>
        body { font-family: Arial, sans-serif; }
        header { background-color: #f8f9fa; }
        nav .nav-link { margin-right: 1rem; }
        .hero { background-color: #e9ecef; }
        .card { border-radius: 0.5rem; }
        footer { background-color: #f8f9fa; }
        .dropdown-menu { min-width: 10rem; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <header>
        <nav class="navbar navbar-expand-lg {{navbar_classe}}">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">{{navbar_logo_texto}}</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        {% for item in menu %}
                        <li class="nav-item {% if item.dropdown %}dropdown{% endif %}">
                            <a class="nav-link {% if item.active %}active{% endif %}" 
                               href="{{item.url}}" 
                               {% if item.dropdown %}role="button" data-bs-toggle="dropdown"{% endif %}
                               onclick="carregar2('{{item.url}}', event)">
                               {{item.texto}}
                            </a>
                            {% if item.dropdown %}
                            <ul class="dropdown-menu">
                                {% for sub in item.submenu %}
                                <li><a class="dropdown-item" href="{{sub.url}}" onclick="carregar2('{{sub.url}}', event)">{{sub.texto}}</a></li>
                                {% endfor %}
                            </ul>
                            {% endif %}
                        </li>
                        {% endfor %}
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero / Banner -->
    <section class="hero text-center py-5 {{hero_classe}}">
        <div class="container">
            <h1 class="display-4">{{hero_titulo}}</h1>
            <p class="lead">{{hero_descricao}}</p>
            <a href="{{hero_botao_url}}" class="btn {{hero_botao_classe}} btn-lg mt-3">{{hero_botao_texto}}</a>
        </div>
    </section>

    <!-- Conteúdo principal -->
    <main class="container my-5">
        <div id="content">
            <div class="container-fluid">
                <div class="post_container" id="up" style="position: relative;">
                    <?php
                    if ($usuarioLogado) {
                        include('pages/descobrir-canais.php');
                    } else {
                        include('pages/inicio-visitante.php');
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Seção Sobre -->
        <section id="sobre" class="mb-5">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2>{{sobre_titulo}}</h2>
                    <p>{{sobre_descricao}}</p>
                    <ul>
                        {% for item in sobre_lista %}
                        <li>{{item}}</li>
                        {% endfor %}
                    </ul>
                </div>
                <div class="col-lg-6">
                    <img src="{{sobre_imagem_url}}" alt="{{sobre_titulo}}" class="img-fluid rounded">
                </div>
            </div>
        </section>

        <!-- Seção Serviços -->
        <section id="servicos" class="mb-5">
            <h2 class="text-center mb-4">{{servicos_titulo}}</h2>
            <div class="row g-4">
                {% for servico in servicos %}
                <div class="col-md-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <h5 class="card-title">{{servico.titulo}}</h5>
                            <p class="card-text">{{servico.descricao}}</p>
                        </div>
                    </div>
                </div>
                {% endfor %}
            </div>
        </section>

        <!-- Seção Depoimentos -->
        <section id="depoimentos" class="py-5 {{depoimentos_classe}}">
            <div class="container">
                <h2 class="text-center mb-4">{{depoimentos_titulo}}</h2>
                <div class="row">
                    {% for depoimento in depoimentos %}
                    <div class="col-md-4">
                        <div class="card p-3">
                            <p>"{{depoimento.texto}}"</p>
                            <h6 class="text-end">— {{depoimento.autor}}</h6>
                        </div>
                    </div>
                    {% endfor %}
                </div>
            </div>
        </section>

        <!-- Seção Contato -->
        <section id="contato" class="my-5">
            <h2 class="text-center mb-4">{{contato_titulo}}</h2>
            <form class="row g-3" action="{{contato_form_action}}" method="post">
                <div class="col-md-6">
                    <label for="nome" class="form-label">{{contato_label_nome}}</label>
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="{{contato_placeholder_nome}}">
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">{{contato_label_email}}</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="{{contato_placeholder_email}}">
                </div>
                <div class="col-12">
                    <label for="mensagem" class="form-label">{{contato_label_mensagem}}</label>
                    <textarea class="form-control" id="mensagem" name="mensagem" rows="4" placeholder="{{contato_placeholder_mensagem}}"></textarea>
                </div>
                <div class="col-12 text-center">
                    <button type="submit" class="btn {{contato_botao_classe}} btn-lg">{{contato_botao_texto}}</button>
                </div>
            </form>
        </section>
    </main>

    <!-- Footer -->
    <footer class="{{footer_classe}} py-4">
        <div class="container text-center">
            <p>&copy; {{ano_atual}} {{footer_texto}}. Todos os direitos reservados.</p>
            <ul class="list-inline">
                {% for link in footer_links %}
                <li class="list-inline-item"><a href="{{link.url}}" class="{{link.classe}}">{{link.texto}}</a></li>
                {% endfor %}
            </ul>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery (necessário para $("#up").load) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Script de carregamento dinâmico -->
    <script>
        function carregar2(pagina, event) { 
            if (event) event.preventDefault();
            
            const isSearch = pagina.includes('busca=');
            const searchInput = document.getElementById('cqb-search-input');
            
            if (searchInput && !isSearch) {
                searchInput.value = '';
                const clearBtn = document.getElementById('cqb-search-clear');
                if (clearBtn) clearBtn.style.display = 'none';
            }
            
            $("#up").load(pagina);
        }
    </script>
</body>
</html>