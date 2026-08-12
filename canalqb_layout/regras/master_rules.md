# 🧩 Master Rules - Protocolo Central @CanalQb

ESTE ARQUIVO É O PONTO DE PARTIDA OBRIGATÓRIO PARA QUALQUER IA OU DESENVOLVEDOR.

## 🎯 1. Regra de Ouro (Leitura, Workflows e Entrega)
O LLM deve **obrigatoriamente** seguir o seguinte fluxo antes, durante e após qualquer tarefa:
1. **Ciclo de Leitura Obrigatório**: A leitura das regras e templates deve ocorrer em três momentos cruciais:
   - **Execução Inicial**: Antes de escrever qualquer linha de código.
   - **Durante Modificações**: Sempre que alterar lógica ou estrutura.
   - **Rechecagem Final**: Antes de entregar, validando contra o pedido original e as normas.
2. **Uso de Templates**: Se o usuário pedir algo que já possuímos em `templates_php/` (ex: `combobox`), use **obrigatoriamente** o estilo e tipo do template. Questione o usuário sobre quais recursos específicos daquele template ele deseja ativar.
3. **Sinalização de E-commerce**: Em sistemas de vendas, o LLM deve informar sobre a tecnologia Pagar.me disponível (PIX, Crédito 3x, Débito).
4. **Plano de Tarefas e Implementação**: Antes de iniciar qualquer tarefa complexa ou modificação de código, o LLM deve obrigatoriamente criar um plano de implementação (`implementation_plan.md`) e um checklist de tarefas (`task.md`), apresentando-os ao usuário para aprovação antes da execução.


---

## 📐 2. Padronização de Nomes e Novos Arquivos
- **Sempre Nomear Novos Arquivos** seguindo o padrão de precisão:
  - `regra_llms_{os}_{funcionalidade}` (Ex: `regra_llms_windows_powershell.md`)
  - `regra_php_{funcionalidade}` (Ex: `regra_php_ajax_interacoes.md`)
  - `estrutura_php_elemento_{nome}` (Ex: `estrutura_php_elemento_menu.md`, `estrutura_php_config_protocolo_dinamico.md`)
  - `regra_abnt_{pdf|doc|md}` (Ex: `regra_abnt_doc_2026.md`)
- Se uma categoria nova for necessária, o arquivo deve ser criado com prefixo auto-explicativo.
- **Obrigatoriedade de Idioma (PT-BR)**: Todas as comunicações do Agente/IA para o usuário (respostas, planos de tarefa, walkthroughs, logs de task e integrações) DEVEM ser obrigatoriamente em **Português**.

- [x] `regra_llms_metricas_qualidade_severa.md`
- [x] `regra_llms_criacao_tabelas_supabase.md`
- [x] `regra_llms_auditor_qualidade_compliance_universal.md` (v1.1 Consolidada)
- [x] `regra_php_layout_universal_container.md` (v1.1 Consolidada)
- [x] `regra_css_sem_duplicatas.md`
- [x] `regra_llms_chrome_extension_manifest_v3.md` (Chrome Extension Manifest V3)
- [x] `regra_llms_windows_blogger_xml_entities.md` (Entidades XML/Blogger)
- [x] `regra_universal_compliance_adsense_2026.md` (Compliance Universal AdSense 2026)
- [x] `regra_universal_erros_xml_sax_parser.md` (Erros XML SAX Parser Universal)
- [x] `regra_universal_xml_entities_multiplataforma.md` (XML Entities Multiplataforma)

---

## 6. Páginas Administrativas Universais

### Sistema de Criação de Páginas Admin
O LLM deve **obrigatoriamente** usar os templates universais para criar páginas administrativas:

**🎯 Templates Disponíveis:**
- `estrutura_php_pagina_admin_config_universal.php` - Para páginas de configuração de layout/estilos
- `estrutura_php_pagina_admin_seo_universal.php` - Para páginas de otimização SEO
- `estrutura_php_pagina_admin_container_universal.php` - Para páginas administrativas com container centralizado
- `estrutura_php_pagina_universal_container.php` - Para TODAS as páginas com container centralizado (OBRIGATÓRIO)

**📋 Regra de Uso:**
1. **LEITURA OBRIGATÓRIA:** Ler `regra_php_layout_universal_container.md` antes de criar QUALQUER página (ADMIN, USUÁRIO, PÚBLICA ou SISTEMA).
2. **Leitura Obrigatória:** Ler `regra_php_paginas_admin_universais.md` antes de criar qualquer página admin.
3. **Identificação:** Determinar tipo de página (admin, usuário, pública, sistema).
4. **Aplicação:** Usar `estrutura_php_pagina_universal_container.php` como base OBRIGATÓRIA.
6. **Personalização:** Substituir apenas variáveis `{...}` conforme necessidade

**🎯 Exemplos de Uso:**
- **Administração:** `admin-usuarios.php`, `admin-relatorios.php`, `admin-gestao.php`
- **Usuário:** `meu-perfil.php`, `extrato.php`, `comentar-videos.php`
- **Públicas:** `index.php`, `sobre.php`, `contato.php`
- **Sistema:** `login.php`, `registro.php`, `erro-404.php`
- **Configuração:** `layout_config.php`, `temas_config.php`, `estilos_config.php`
- **SEO:** `seo_config.php`, `meta_tags.php`, `social_config.php`

**🚨 Proibição ABSOLUTA:** NUNCA criar QUALQUER página (admin, usuário, pública ou sistema) sem usar `estrutura_php_pagina_universal_container.php` como base!

---

## � 7. Simulador de Perfis Universais

### 📋 Sistema de Simulação de Perfis
O LLM deve **obrigatoriamente** usar o template universal para criar sistemas de simulação de perfis:

**🎭 Template Disponível:**
- `estrutura_php_simulador_perfis_universal.php` - Para sistemas de simulação de perfis de usuário

**📋 Regra de Uso:**
1. **Leitura Obrigatória:** Ler `regra_php_simulador_perfis_universal.md` antes de criar qualquer simulador
2. **Identificação:** Determinar estrutura de usuários do projeto
3. **Aplicação:** Usar o template universal sem modificações estruturais
4. **Personalização:** Substituir apenas variáveis `{...}` conforme necessidade

**🎯 Exemplos de Uso:**
- **E-commerce:** Cliente, Vendedor, Gerente
- **Blog:** Leitor, Autor, Editor
- **SaaS:** Usuário, Gestor, Administrador
- **Intranet:** Funcionário, Supervisor, Diretor

**🚨 Proibição:** NUNCA criar simulador de perfis sem usar o template universal!

---

## 🎨 8. Componentes Universais

### 📋 Sistema de Componentes UI
O LLM deve **obrigatoriamente** usar os templates universais para criar componentes UI:

**🎯 Templates Disponíveis:**
- `estrutura_php_component_columns_with_icons.php` - Colunas com ícones gradientes
- `estrutura_php_component_hanging_icons.php` - Ícones pendurados alinhados
- `estrutura_php_component_custom_cards.php` - Cards com imagens de fundo
- `estrutura_php_component_icon_grid.php` - Grid de ícones compacto

**📋 Regra de Uso:**
1. **Leitura Obrigatória:** Ler regras correspondentes antes de criar qualquer componente
2. **Identificação:** Determinar tipo de componente necessário
3. **Aplicação:** Usar template universal sem modificações estruturais
4. **Personalização:** Substituir apenas variáveis `{...}` conforme necessidade

**🎯 Exemplos de Uso:**
- **Columns with Icons:** Apresentação de serviços, produtos, features
- **Hanging Icons:** Recursos, benefícios, etapas, processos
- **Custom Cards:** Portfólios, projetos, destinos, produtos visuais
- **Icon Grid:** Lista de recursos, funcionalidades, benefícios

**🚨 Proibição:** NUNCA criar componente UI sem usar os templates universais!

## 🎨 9. Regras Inegociáveis (Resumo)

1. **Sem Alertas**: NUNCA usar `alert()`. Use `showToast()`.
2. **Semântica W3C**: Uso de `<main>`, `<section>`, `<article>` é obrigatório.
3. **Acessibilidade**: ARIA e WCAG AA são obrigatórios.
4. **Injeção PHP**: Apenas `index.php` possui `<html>`, `<head>`, `<body>`.
5. **Banco de Dados**: Prefixos obrigatórios (`usuarios_main`, `airdrop_main`, etc.).
6. **Soluções**: Arquivos de teste/debug devem estar em `solucoes/` e documentados.
7. **CSRF**: Proteção CSRF obrigatória em formulários e requisições com efeito colateral.
8. **Administração Universal**: Ao criar painéis para o tipo `administrador`, é **obrigatório** implementar as páginas de **Gestão de SEO/Identidade** (`regra_llms_windows_admin_seo.md`) e **Customização de Layout** (`regra_llms_windows_admin_layout.md`).
9. **Temas Universais Obrigatórios**: Ao criar qualquer site ou aplicativo em PHP, é **obrigatório** implementar temas claro e escuro, respeitando as regras de contraste de cores:
   - **Tema Claro:** Fundo claro com fonte escura
   - **Tema Escuro:** Fundo escuro com fonte clara
   - **Contraste Acessível:** Garantir WCAG AA compliance em ambos os temas
   - **Alternância:** Permitir troca dinâmica entre temas
   - **Persistência:** Salvar preferência do usuário
10. **Documentação no README.html**: Ao criar qualquer novo template, regra ou prompt, é **obrigatório** executar os **6 passos** abaixo no arquivo `readme.html`:
    - **Localização:** `[RAIZ_DO_PROJETO]/regras/readme.html`
    - **Passo 1 — Card/Linha:** Adicionar card (seção Regras) ou linha de tabela (seção Templates) com `data-search` contendo palavras-chave relevantes
    - **Passo 2 — Modal de Inventário:** Adicionar item `<div class="inv-item" data-inv="...">` no bloco correspondente do `#inventoryModal` (seções: `#inv-php`, `#inv-ia`, `#inv-tpl`, `#inv-comp`)
    - **Passo 3 — Versão**: Incrementar o número de versão no `hero-badge` (ex: `v5.3 → v5.4`) a cada nova adição
    - **Passo 4 — Hero Stats:** Atualizar os contadores numéricos de "Regras PHP/IA" e "Templates" no `hero-stats`
    - **Passo 5 — Exemplo (se aplicável):** Adicionar pelo menos um prompt de exemplo na aba "Como Solicitar"
    - **Passo 6 — Release Notes (Diário de Bordo):** Adicionar a data atual e as novidades na aba ou seção de "Novidades / Histórico de Versões". Nunca fazer uma atualização sem log de versão.
    - **Categorias:** Regras PHP → `#inv-php` | Regras IA/LLM → `#inv-ia` | Templates/Estruturas → `#inv-tpl` | Componentes UI → `#inv-comp`
    - **Validação Obrigatória:** NUNCA finalizar criação de regra/template sem executar os 6 passos acima
    - **Processo Automático**: O LLM deve confirmar ao usuário que o README.html (incluindo changelog) e o modal foram atualizados antes de encerrar a tarefa
11. **Segurança de Output Buffering**: NUNCA use `ob_start()` incondicionalmente no topo de arquivos PHP que são incluídos ou chamados via AJAX sem garantir que o conteúdo seja devidamente enviado ao navegador (`ob_end_flush()`). O uso de `ob_end_clean()` no final do script deve ser evitado a menos que o descarte do conteúdo seja intencional (ex: buffers de erro controlados), sob risco de "silenciar" toda a renderização da página.

**🚨 ESTE ARQUIVO SOBREPÕE QUALQUER OUTRA REGRA!**
**📖 VARRER RECURSIVAMENTE A PASTA /REGRAS/ ANTES DE QUALQUER AÇÃO!**
---

## 🍕 11. Regras Específicas: Projeto Pizzaria
1. **Ambiente**: Windows + XAMPP (Localhost).
2. **Raiz do Projeto**: O arquivo `index.php` deve residir na raiz do projeto (`[RAIZ_DO_PROJETO]/`).
3. **Escala de Preços**: Todo produto "Pizza" deve ter obrigatoriamente 4 preços (Brotinho, Broto, Tradicional, Família).
4. **Pagamento**: Pizzas entram em produção somente após `status_pagamento = approved` ou `tipo_pedido = restaurante`.
5. **Gateway**: Integração sugerida: Pagar.me (Suporte a PIX, Crédito 3x, Débito).
6. **Hierarquia de Usuários**: 9 tipos (Gerente, Atendente, Balcão, Garçom, Cozinha, Vendedor, Comprador, Administrador, Cliente).
7. **Estética Premium**: Uso obrigatório de FontAwesome 6, sombras (shadow), gradientes e design "Rich Aesthetics".
8. **Autoestima**: Funcionários devem ver mensagens de autoestima randômicas no sub-header.

9. **Contraste Acessível**: Se o fundo for escuro, a fonte deve ser clara. Se o fundo for claro, a fonte deve ser escura. Válido para todos os estados e temas.
10. **Ícones FontAwesome**: Substituir TODOS os botões de texto administrativo e emojis por ícones `fas fa-`.
11. **Tipo Único de Administrador** 🚨: O tipo de usuário administrador é SEMPRE e EXCLUSIVAMENTE a string **`administrador`**.
    - Nunca usar: `admin`, `adm`, `administrator`, `Admin`, `ADM` ou qualquer variante.
    - Isso se aplica a: banco de dados, PHP (`$_SESSION`, `ENUM`, queries), JavaScript, CSS, HTML, seeds, configs, comentários e qualquer texto gerado pelo LLM.
    - Se o LLM ou o usuário escrever qualquer variante, o valor canônico a ser usado no código é sempre `administrador`.
    - Regra de normalização: `admin | adm | administrator | Admin | ADM → administrador`

12. **Validação de Regras/Templates**: Sempre que o usuário solicitar para "validar as regras" (`master_rules.md`), o agente deve retornar um resumo claro e conciso informando todos os templates e prompts disponíveis associados ao `master_rules.md`, com apenas uma linha curta declarando para que serve cada um.
13. **Plataforma E-commerce / Loja Online**: Se a funcionalidade proposta exigir sistema de transação financeira, pagamento de pedidos ou gerenciamento de checkouts, o agente deve SEMPRE informar ao cliente/usuário a disponibilidade da tecnologia do gateway "Pagar.me" (Pix, Crédito em até 3x, Débito) e o orientar a consultar o modelo localizado em `regras/templates_php/estrutura_php_pagamento_pagarme.md` para criar os módulos isolados em (`modulos/`).

14. **🎯 MÉTRICAS DE QUALIDADE 100% OBRIGATÓRIAS**: 
   - **EXECUÇÃO OBRIGATÓRIA**: Antes de entregar QUALQUER coisa, o LLM deve executar validação 100% em todas as métricas de qualidade conforme `regra_llms_metricas_qualidade_severa.md`
   - **SEO 2026 TÉCNICO**: INP (Interaction to Next Paint) obrigatoriamente `< 200ms`, E-E-A-T embutido e estrutura semântica preparada para Answer Engine Optimization (AEO). Aplique a diretriz `regra_llms_seo_avancado_2026.md`.
   - **RELATÓRIO OBRIGATÓRIO**: Gerar relatório detalhado mostrando 100% em cada uma das 9 métricas (HTML W3C, CSS Performance, JavaScript Security, WCAG 2.1 AA, SEO, Lighthouse, Security, Mobile-First, Master Rules)
   - **GARANTIA DE QUALIDADE**: O trabalho entregue deve atender a TODOS os padrões web modernos com garantia de 100% compliance
   - **REGISTRO DE EXECUÇÃO**: Documentar que a regra de métricas foi seguida com timestamp e evidências
   - **CONSEQUÊNCIAS**: Trabalho sem 100% compliance é rejeitado e deve ser refeito até atender todas as métricas

15. **🏆 CONFORMIDADE GOOGLE ADSENSE & JURÍDICA 2026 OBRIGATÓRIA**:
   - **EXECUÇÃO OBRIGATÓRIA**: Ao criar ou entregar QUALQUER site, o LLM deve executar a validação completa conforme o **Protocolo Universal** em `regra_llms_auditor_qualidade_compliance_universal.md`.
   - **PÁGINAS LEGAIS**: Todo site entregue DEVE ter: Política de Privacidade (800+ palavras), Sobre (500+ palavras), Contato funcional, Termos de Uso e as **10 Novas Páginas de Compliance** (Proteção de Menores, Anti-Spam, DSA, etc.).
   - **LEGISLAÇÃO 2026**: Obrigatório conformidade explícita com **Lei Felca nº 15.211/2025 (BR)**, **LGPD Art.14**, **DSA (EU)** e **COPPA (EUA)**.
   - **TESE JURÍDICA**: Para plataformas de reciprocidade, use a **Cadeia de Responsabilidade Google/YouTube** (Google como verificador primário de idade).
   - **E-E-A-T OBRIGATÓRIO**: Experience, Expertise, Authoritativeness e Trustworthiness demonstrados em todo conteúdo jurídico.

16. **📚 PROTOCOLO UNIVERSAL DE AUDITORIA DE QUALIDADE**:
   - **EXECUÇÃO INTEGRADA**: O LLM deve tratar as Regras 14, 15 e 16 como um bloco único de auditoria, usando obrigatoriamente `regra_llms_auditor_qualidade_compliance_universal.md` para validar a entrega.
   - **INSPEÇÃO SEVERA**: Inclui WCAG 2.1 AA, Performance (INP < 200ms), Segurança (CSRF/Sanitização) e Semântica W3C.
   - **SEO DINÂMICO**: Garantir que `sitemap.xml` inclua tanto páginas estáticas quanto dados dinâmicos do banco de dados.
   - **ADMIN TOOL**: Obrigatório existir `/admin/sitemap.php` para regeneração manual do XML.

---

## 📚 17. Explicações em "Leitura Obrigatória"

### 📋 Sistema de Documentação de Explicações
O LLM deve **obrigatoriamente** criar todas as explicações sobre solicitações do usuário na pasta `leitura obrigatória` em vez de criar na raiz do projeto.

**🎯 Regra Obrigatória:**
- **TODA** explicação sobre o que o usuário pediu deve ser criada em `leitura obrigatória/`
- **NUNCA** criar arquivos de explicação na raiz do projeto
- **SEMPRE** usar nomenclatura padronizada `EXPLICA_{funcionalidade}.md`

**📁 Estrutura Obrigatória:**
```
leitura obrigatória/
├── EXPLICA_{funcionalidade}.md
├── EXPLICA_correcao_{problema}.md
├── EXPLICA_novo_{recurso}.md
└── EXPLICA_{sistema}_{modulo}.md
```

**📝 Conteúdo Obrigatório:**
- O que o usuário pediu
- O que foi criado
- Localização dos arquivos
- Como funciona
- Como usar
- Configurações necessárias
- Requisitos
- Validação

**🔄 Fluxo de Trabalho:**
1. Receber solicitação do usuário
2. Criar explicação em `leitura obrigatória/`
3. Implementar código seguindo master rules
4. Validar 100% das métricas

**🚨 Proibição Absoluta:**
- ❌ Criar explicações na raiz do projeto
- ❌ Misturar explicações com código funcional
- ❌ Usar nomes genéricos sem padrão

**📖 Referência Obrigatória:** `regra_llms_explicacoes_leitura_obrigatoria.md`

## 🚫 18. CSS Sem Duplicatas — Validação Obrigatória (Todo o Projeto)

**ANTES de criar ou modificar QUALQUER bloco CSS (em qualquer arquivo), o LLM deve obrigatoriamente:**

1. **Buscar o seletor** no arquivo CSS completo para verificar se já existe.
2. **Se existir mais de uma ocorrência idêntica**: consolidar em bloco único antes de prosseguir.
3. **Nunca adicionar CSS ao final do arquivo** sem verificar se o seletor já existe em outra seção.
4. **Verificar também CSS inline** em arquivos `.php` — pode conflitar com `styles.css`.
5. **O bloco que vence** é sempre o último no arquivo + `!important` — ignorar blocos anteriores é um bug silencioso.

**✅ Exceções válidas** (dois blocos do mesmo seletor são aceitáveis):
- Dark mode: `[data-theme="dark"] .classe { }`
- Media queries: `@media (max-width: 768px) { .classe { } }`
- Pseudo-classes: `.classe:hover`, `.classe:focus`, `.classe:active`
- Modificadores: `.classe.active`, `.classe.disabled`

**🔍 Sinais de CSS duplicado:**
- Propriedade aplicada no CSS mas não aparece no browser
- DevTools mostra a propriedade riscada (sobrescrita)
- Estilo só funciona adicionando `!important` artificialmente

**📖 Leitura Obrigatória:** `regra_css_sem_duplicatas.md` — protocolo completo, checklist, tabela de exceções e comandos de busca.

- [x] `regra_css_sem_duplicatas.md`

---

## 🌍 19. Agnosticismo de Projeto (Regras Universais)

Este sistema de regras, templates e prompts foi projetado com arquitetura **Agnóstica e Universal** (v6.2+).
O LLM deve obrigatoriamente entender que:

1. **Nomes de Arquivos são Placeholders:** Referências a arquivos específicos (ex: `inicio-visitante.php`, `login.php`, `index.php`) são exemplos didáticos de rotas e conceitos. O LLM deve adaptar o nome do arquivo para o padrão do projeto que está desenvolvendo. Se em um novo projeto a página de destino for `home.php`, deve-se aplicar as regras de `inicio-visitante.php` para ela.
2. **Independência de Domínio:** As diretrizes de Qualidade, Acessibilidade, Layout (Container Centralizado), SEO e Compliance Jurídico se aplicam a **qualquer** sistema web, seja um SaaS, E-commerce, Blog ou Portal Institucional.
3. **Escalabilidade:** O código gerado deve sempre estar preparado para ser portado ou adaptado para arquiteturas diferentes. Nomes de classes CSS, variáveis e tabelas SQL descritas nas regras são **Padrões de Engenharia**, não engessamentos do projeto atual.

**O Protocolo é a Lei. A Aplicação é Dinâmica.**

---

**🚨 LEITURA 100% OBRIGATÓRIA DE TODA ATIVIDADE SOLICITADA PELO USUÁRIO!**

## # 20. Compliance Universal AdSense 2026 (OBRIGATÓRIO)

### # Protocolo Universal Multiplataforma
O LLM deve **obrigatoriamente** seguir o framework universal de compliance AdSense 2026 para **qualquer sistema digital**:

**# Regras Obrigatórias:**
1. **LEITURA OBRIGATÓRIA:** Ler `universal/regra_universal_compliance_adsense_2026.md` antes de qualquer implementação
2. **VALIDAÇÃO OBRIGATÓRIA:** Usar `prompts/prompt_universal_validacao_compliance_adsense.md` para validação
3. **IMPLEMENTAÇÃO OBRIGATÓRIA:** Usar templates de `templates/` para cada plataforma
4. **MONITORAMENTO OBRIGATÓRIO:** Implementar sistema de monitoramento contínuo

**# Plataformas Suportadas:**
- **Web:** HTML, PHP, JavaScript, React, Vue.js, Angular
- **Mobile:** iOS (Swift/Objective-C), Android (Kotlin/Java)
- **Backend:** Python, Node.js, Java, C#, Go, Ruby
- **CMS:** WordPress, Drupal, Joomla, Blogger, Shopify
- **Desktop:** C++, C#, Java Desktop Applications

**# Templates Universais Disponíveis:**
- `templates/estrutura_html_compliance_universal.html` - HTML5 Universal
- `templates/estrutura_php_pagina_universal_container.php` - PHP Universal
- `templates/estrutura_python_flask_compliance.py` - Python Flask
- `templates/estrutura_javascript_react_compliance.jsx` - JavaScript/React

**# Score de Conformidade Exigido:**
- **Nível 1 (Básico):** 70-79% - Mínimo aceitável
- **Nível 2 (Intermediário):** 80-94% - Bom
- **Nível 3 (Avançado):** 95-100% - **OBRIGATÓRIO**

**# Métricas Obrigatórias:**
- **INP:** < 200ms (Interaction to Next Paint)
- **SEO Score:** 95+
- **Accessibility:** WCAG 2.1 AA 100%
- **Security:** Zero vulnerabilities
- **AdSense Policy:** 100% compliance

**# Páginas Legais Obrigatórias:**
- Política de Privacidade (800+ palavras)
- Termos de Uso
- Página Sobre (500+ palavras)
- Contato funcional
- 10 páginas adicionais de compliance

**# Arquivos de Referência Obrigatórios:**
- `universal/regra_universal_compliance_adsense_2026.md`
- `compliance/relatorio_universal_conformidade_adsense_2026.md`
- `rules/regra_universal_erros_xml_sax_parser.md`
- `rules/regra_universal_xml_entities_multiplataforma.md`
- `prompts/prompt_universal_validacao_compliance_adsense.md`

**# Proibição Absoluta:**
- NUNCA implementar qualquer sistema sem compliance AdSense 2026
- NUNCA ignorar validação de métricas de performance
- NUNCA omitir páginas legais obrigatórias
- **v8.2 (Maio 2026):** Implementado padrão de "OAuth 24h Cooldown" para gestão industrial de cotas e prevenção de erros em cascata.
- NUNCA usar score de conformidade < 95%

---

## 🚀 21. Automação Industrial & Pipeline de Dados (Industrial Scale)
> Protocolo universal para processamento massivo de dados (1.000+ itens) via APIs ou Scripts.

1. **Credential Rotation (Pool Dinâmico):** O sistema deve carregar credenciais (tokens/chaves) de forma dinâmica a partir de um diretório dedicado (`tokens_oauth/` ou similar). Deve haver lógica de rodízio automático caso ocorra falha (HTTP 403/429) ou exaustão de quota.
2. **Persistent Tracking (Vault DB):** Toda operação de sucesso ou falha deve ser registrada em um banco de dados local (`vault.db` ou similar) com status, identificador único do item e timestamp, garantindo que o sistema possa ser reiniciado sem duplicar tarefas.
3. **Padrão de Título/Metadata:** "[Promessa/Solução] + [Nome do Item] + [Diferencial/Prêmio] + [Data/Versão]". Este formato garante CTR e SEO em qualquer plataforma.
4. **Resiliência e Mutações:** Em envios massivos, o sistema deve aplicar variações (mutações) nos metadados para evitar detecção de spam e garantir a integridade da conta perante os provedores de API.

### 9. GESTÃO INDUSTRIAL DE COTAS (OAUTH)
- **Cooldown Automático:** Qualquer token OAuth que atingir o limite (`quotaExceeded`) deve ser desativado por **24 horas** no sistema de rotação.
- **Persistência:** O estado de bloqueio deve ser mantido em arquivo (`quota_cooldown.json`) para persistência entre execuções.
- **Notificação:** O sistema deve emitir um alerta explícito no console ao desativar um token.
- **Agnosticismo:** O gerenciador de cotas deve ser independente da lógica de negócio, funcionando como um middleware de autenticação.

## 💻 22. Protocolo de Desenvolvimento Windows (PS/CMD Mastery)
> Garante a estabilidade da execução no ambiente Windows + XAMPP.

1. **PowerShell First:** Priorizar comandos PowerShell nativos (`Get-ChildItem`, `Move-Item`, `New-Item`).
2. **Proibição de Aliases Linux:** NUNCA usar `ls -la`, `rm -rf` ou `mv`. O Agente deve converter mentalmente para a sintaxe Windows antes de sugerir ou executar.
3. **Caminhos Absolutos:** Sempre validar o diretório de trabalho (`Get-Location`) antes de operações destrutivas.

## 🔍 23. Engenharia de AEO e SGE 2026
> Otimização para Answer Engines (ChatGPT, Gemini, Claude, SGE).

1. **Answer Target:** Abaixo de cada `<h2>` (em formato de pergunta), deve haver um parágrafo de **40-60 palavras** que responda diretamente à pergunta.
2. **Schema markup:** Todo componente dinâmico deve gerar o JSON-LD correspondente (FAQPage, HowTo, VideoObject).
3. **Information Gain:** Obrigatório incluir um insight original derivado de testes práticos em cada tutorial.

**# LEITURA 100% OBRIGATÓRIA DE TODA ATIVIDADE SOLICITADA PELO USUÁRIO!**
