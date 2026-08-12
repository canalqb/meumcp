# Prompt para LLM: Reescrever Template HTML Completo com Bootstrap 5 (Mobile-Friendly)

## Objetivo

Você é um assistente que precisa * * reescrever completamente um template HTML* *  fornecido, mantendo todos os placeholders dinâmicos, loops e estruturas do template original, mas garantindo:

*  Nunca Altere nada da pasta templates, estes arquivos são necessários para novos projetos.
*  Todo arquivo de teste/debug ou similares, devem ser criados na pasta raiz/debug.
*  Crie o index.php e os demais arquivos sempre na raiz da pasta, lembre que o APP_URL deve ser valido para o desenvolvimento em pasta como c:\xampp\htdocs\{pastadesenvolvimento}, quanto para o site oficial hospedado na web
*  Criar uma página 404 personalizada e adicionar uma variável de controle de tempo no index.php, controlada também pelo administrador.
*  Sempre tenha uma pagina para configuração de variaveis para o administrador baseado no template disponivel em templates/cms-saas-template/README.MD E templates/cms-saas-template/STRUCTURE.MD, inclua o acesso na tabela menu como subitem de administração.
*  O menu deve ser responsivo e funcional, com dropdowns e comportamento mobile aprimorado, devendo utilizar o template dentro da pasta template_menu_pacote/template-menu.md
*  Windows 10 com xampp 8.2.12 / PHP 8.0.30
*  Mysql nome da tabela deve ser "airdrop", login: root, senha: "semsenha"
*  HTML5 válido e semanticamente correto
*  Responsividade completa usando * * Bootstrap 5* * 
*  Navbar funcional com links, dropdowns e comportamento mobile aprimorado
*  Script de carregamento dinâmico (`carregar2`) funcionando em todos os links do menu
*  Inclusão de CSS interno complementar e links para CSS externo
*  Inclusão de scripts necessários (Bootstrap JS, jQuery, Font Awesome)
*  Boas práticas de acessibilidade e clareza de código
*  Paginas do site, exceto o index.php, não deve ter <html>, <head>, <body>
*  Todos os elementos devem possuir mesmos padrões entre pagina, ou seja <h1> da paginaX.php tem que ter a mesma configuração da <h1> da paginaY.php
*  O Site deve ter um controle de login (visitante - não precisa logar, usuario - precisa logar, administrador - precisa logar)
*  Criar tabela de usuarios, com nome, email, senha, tipo (usuario, administrador)
*  Criar administrador (Rodrigo, qrodrigob@gmail.com, admin123)
*  Precisa enviar um email de validação com link de token para o administrador logar, com validade de 15 minutos criar tabela `config_smtp`, e um formulário para o admin modificar com maior facilidade, inclua o acesso na tabela menu como subitem de administração:
  * id:	
  * host:	smtp.gmail.com
  * port:	465
  * username:	Rodrigo
  * password:	uijmarabiwfwqeot
  * from_email:	rodrigoqbqb@gmail.com
  * from_name:	@CanalQb
  * created_at: agora de criação
*  Tabela de log de acesso.
*  Formulario para preenchimento de dados SEO completo pelo administrador, banner, imagem, favicon, (tipo de fonte, tamanho para fonte) de titulo, subtitulo, corpo do email, e outros dados.
*  Sempre que solicitado combobox, usar template template_combobox.html
*  Usar Icons 100% compativeis com bootstrap 5
*  Crie a tabela menu, e um formulário para o admin modificar com maior facilidade, inclua o acesso na tabela menu como subitem de administração:
  * id 	automatico						
  * nome	Nome do item do menu						
  * href	link padrão						
  * target	sempre _blank						
  * onclick	javascript:carregar2('{link}');						
  * icone	apenas icone fas						
  * logado	S,N,SN	S apenas para logado, N apenas para não logado, SN para ambos					
  * indice	se diferente de 0, define subitem de ID pai						

* Crie uma tabela de nome airdrop_cadastrados e tabelas de ligação (tabela inicial do registro do airdrop), crie uma pagina para o administrador criar os airdrops, usando todas as tabelas airdrop_cadastrados*, inclua o acesso na tabela menu como subitem de administração:
  * id_airdrop = auto increment
  * titulo_airdrop
  * slug_airdrop
  * short_description 
  * full_description 
  * logo_url
  * banner_url

* airdrop_cadastrados_indicacao (tabela para salvar meu link de indicacao do airdrop)
  * id = auto increment
  * id_airdrop
  * link_indicacao

* airdrop_cadastrados_redessociais (tabela para salvar redes sociais do airdrop)
  * id = auto increment
  * id_airdrop
  * nome_da_redesocial
  * link_da_redesocial

* airdrop_cadastrados_backers (tabela para salvar patrocinadores do airdrop)
  * id = auto increment
  * id_airdrop
  * nome_do_patrocinador
  * link_do_patrocinador

* airdrop_cadastrados_sites (tabela para salvar sites do airdrop)
  * id = auto increment
  * id_airdrop
  * nome_do_site
  * link_do_site

* airdrop_cadastrados_tarefas (tabela para salvar tarefas do airdrop)
  * id = auto increment
  * id_airdrop
  * titulo_tarefas
  * descricao_tarefas
  * ponto_tarefas

* airdrop_cadastrados_cronologia (tabela para salvar datas criadas para o airdrop) para cada data uma nova linha
  * id = auto increment
  * id_airdrop
  * nome_da_data
  * data_de_entrega

* airdrop_cadastrados_itensadicionais (tabela para salvar itens adicionais do airdrop) para cada item uma noma linha
  * id_itensadicionais
  * id_airdrop

* airdrop_cadastrados_blockchain (tabela para salvar blockchain do airdrop) para cada blockchain uma nova linha
  * id_blockchain
  * id_airdrop

* airdrop_cadastrados_exchange (tabela para salvar do airdrop) para cada exchange uma nova linha
  * id_exchange
  * id_airdrop

* airdrop_cadastrados_wallets (tabela para salvar carteiras do airdrop) para cada carteira uma nova linha
  * id_wallets
  * id_airdrop

* airdrop_cadastrados_categorias (tabela pra informar categorias do airdrop) para cada categoria uma nova linha
  * id_categorias
  * id_airdrop

* airdrop_cadastrados_tags (tabela para salvar id de tag do airdrop) para cada tag uma nova linha 
  * id_tags
  * id_airdrop

* airdrop_cadastrados_videos (tabela para salvar id de video do youtube do airdrop)
  * id_video_yt exemplo https://www.youtube.com/watch?v=RV-Z1YwaOiw só vai registrar o RV-Z1YwaOiw
  * id_airdrop
  
* Outras tabelas de origem dos dados para alimentar o formulario do airdrop, crie uma unica pagina que carregue modals para que eu possa cadastrar itens nas tabelas cadastro_* abaixo, inclua o acesso na tabela menu como subitem de administração:
* cadastro_categorias
  * id
  * nome_categorias
  
* cadastro_itensadicionais
  * id
  * nome
  
* cadastro_blockchain
  * id
  * nome
  * link
  
* cadastro_exchange 
  * id
  * nome
  * link
  
* cadastro_wallets
  * id
  * nome
  * link
  
* cadastro_tags
  * id
  * nome
  
O template reescrito deve ser * * 100% funcional, organizado, comentado e pronto para uso em produção* * .

---

## Instruções detalhadas para reescrita

### 1. `<head>` do HTML

*  Incluir meta tags essenciais (`charset`, `viewport`)
*  `<title>` com placeholder `{{titulo_pagina}}`
*  Bootstrap CSS via CDN
*  Font Awesome via `<script crossorigin="anonymous">`
*  CSS customizado via `{{css_customizado_url}}`
*  Favicon via `{{favicon_url}}`
*  CSS interno complementar com:

  *  Tipografia básica
  *  Cores básicas do `header`, `.hero` e `footer`
  *  Bordas e sombras de `.card`
  *  Ajustes de dropdown (`.dropdown-menu { min-width: 10rem; }`)
  *  Margens e espaçamentos de links de navegação
  *  Comentários explicativos

---

### 2. Navbar / Header (atualizado para mobile)

1. Navbar * * expansível e colapsada por padrão em mobile* *  (`navbar-expand-lg`).
2. Logo usando `{{navbar_logo_texto}}`.
3. Botão * * toggle* *  para abrir/fechar a navbar em telas pequenas.
4. Menu alinhado à esquerda (`ms-auto`), mantendo loops `{% for item in menu %}` e `{% for sub in item.submenu %}`.
5. Aplicar `onclick="carregar2('{{url}}', event)"` em todos os links principais e sublinks.
6. * * Comportamento de colapso automático* * :

   *  Em mobile, * * quando um link sem submenu é clicado* * , o menu colapsa automaticamente.
   *  Se o link tiver submenu, * * não colapsa* *  ao clicar no item principal.
   *  Ao clicar em qualquer subitem, o menu * * colapsa automaticamente* * .
   *  Incluir botão adicional dentro do nav para * * colapsar manualmente* * .
7. Dropdowns funcionais via Bootstrap 5 (`data-bs-toggle="dropdown"`) com subitens placeholders `{{sub.texto}}` e `{{sub.url}}`.

---

### 3. Seção Hero / Banner

*  Classe `hero` e `text-center`
*  Título e descrição com placeholders `{{hero_titulo}}` e `{{hero_descricao}}`
*  Botão de chamada com `{{hero_botao_texto}}`, classe `{{hero_botao_classe}}` e URL `{{hero_botao_url}}`

---

### 4. Conteúdo principal `<main>`

*  Container principal: `<div id="content"><div class="container-fluid"><div class="post_container" id="up">`
*  PHP condicional:

```php
<?php
if ($usuarioLogado) {
    include('pages/inicio-logado.php');
} else {
    include('pages/inicio-visitante.php');
}
?>
```

*  Este container é o * * alvo do script `carregar2()`* * 

---

### 5. Seções internas

1. * * Sobre* * : título, descrição, lista `{% for item in sobre_lista %}` e imagem.
2. * * Serviços* * : grid responsivo `.row` e `.col-md-4`, cards com título/descrição.
3. * * Depoimentos* * : grid responsivo `.row` e `.col-md-4`, cards com texto e autor.
4. * * Contato* * : formulário com inputs, placeholders e botão de envio (`{{contato_botao_texto}}`, `{{contato_botao_classe}}`).

---

### 6. Footer

*  Rodapé com classe `{{footer_classe}}` e padding `py-4`
*  Copyright `&copy; {{ano_atual}} {{footer_texto}}`
*  Links do footer via loop `{% for link in footer_links %}`

---

### 7. Scripts JS (atualizado para mobile)

1. Bootstrap JS via CDN (`bootstrap.bundle.min.js`)
2. jQuery via CDN
3. Script `carregar2()`:

   *  Prevenir comportamento padrão
   *  Limpar campo de busca (`#cqb-search-input`) se necessário
   *  Carregar conteúdo dentro de `#up` via `.load()`
   *  Comentários explicativos
4. Script adicional para * * controle do colapso da navbar em mobile* * :

   *  Fechar navbar ao clicar em links sem submenu ou em subitens
   *  Manter navbar aberta ao clicar em itens com submenu
   *  Função para * * botão de colapso manual* *  dentro do menu

---

### 8. Boas práticas

*  Indentação consistente
*  `alt` em todas as imagens
*  Evitar tags depreciadas
*  Comentários explicativos em todas as seções
*  Responsividade completa em dispositivos móveis

---

### 9. Resultado esperado

*  HTML completo, pronto para produção
*  Navbar com * * comportamento mobile inteligente* * 
*  Placeholders e loops preservados
*  Dropdowns funcionais
*  Seções Hero, Sobre, Serviços, Depoimentos, Contato e Footer estruturadas
*  CSS interno e externo funcionando
*  Scripts JS funcionando e bem comentados 