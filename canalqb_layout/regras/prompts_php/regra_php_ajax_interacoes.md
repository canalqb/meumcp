# ⚡ Diretrizes de Interação AJAX e Performance

**Versão:** 1.0
**Obrigatório:** Este documento define as regras absolutas para qualquer interação com o servidor, manipulação de DOM e carregamento de dados no sistema. Nenhum submit tradicional ou recarregamento de página é tolerado para operações de persistência.

---

## 🔁 1. CRUD Cirúrgico e Manipulação Direta no DOM

Todas as operações de persistência (INSERT, UPDATE, DELETE) e formulários devem ser realizados via requisições assíncronas (AJAX / Fetch API). A atualização da interface deve ser cirúrgica, afetando exclusivamente o elemento modificado, sem renderizar novamente estruturas pai ou a tela inteira.

*   **Exclusão (DELETE):** Ao excluir uma linha em uma tabela, remova apenas a `<tr>` ou o `card` correspondente, preferencialmente com uma transição/animação de saída.
*   **Edição (UPDATE):** Atualize as informações em modo *inline* ou no modal, injetando o novo dado apenas na célula/elemento modificado.
*   **Inclusão (INSERT):** Insira o novo elemento diretamente no DOM (na tabela ou lista) assim que o retorno de sucesso for recebido.
*   **Toggle de Status:** Ao ativar/desativar um recurso, altere somente a classe do botão, cor do ícone ou *badge*, mantendo a posição e estado da restante do DOM.
*   **Submissão de Formulário:** Retorne o feedback (sucesso ou erros de validação) de forma *inline*, destacando campos ou exibindo *toasts*, sem *reload*.

---

## ⚡ 2. Estratégias de Carregamento Inteligente

A performance percebida deve ser mantida rápida sob qualquer cenário de conexão e dispositivo.

*   **Lazy Loading:** Imagens, iframes e seções pesadas devem ser carregadas sob demanda apenas quando próximos de entrarem na área visível do navegador (*viewport*).
*   **Skeleton Screens:** Durante o carregamento assíncrono, exiba blocos de esqueleto (*skeleton loaders*) estilizados no local do conteúdo. Áreas em branco vazias ou *spinners* genéricos bloqueando a tela inteira não devem ser utilizados.
*   **Infinite Scroll:** Substitua paginações tradicionais por carregamento contínuo automático. Ao rolar até perto do rodapé do contêiner da lista, a próxima página de resultados deve ser extraída e concatenada à lista existente.
*   **Above the Fold First:** Priorize os elementos que o usuário vê inicialmente e delegue o processamento/fetch do restante para logo após a montagem principal da view.

---

## 🧠 3. Otimização de Rede e Inteligência de Requisição

Nenhuma requisição deve ser feita de forma inconsequente ou redundante.

*   **Busca com Debounce:** Qualquer campo de busca interativa tipo "typeahead" deve implementar um *debounce* rigoroso de no mínimo `400ms`. O *fetch* só pode disparar quando o usuário parar de digitar por essa janela de tempo.
*   **Requisições Canceláveis (AbortController):** Interações concorrentes devem cancelar requisições passadas usando `AbortController`. Por exemplo, se o usuário voltar a digitar no botão de busca enquanto uma consulta anterior não terminou, aborte-a;
*   **Cache Local Estratégico:** Utilize `localStorage` ou `sessionStorage` (com controle de expiração/TTL) para catálogos fixos: listas de estados, categorias, tipos de configuração, para evitar refetching o tempo todo.
*   **Prefetching:** Caso haja alta intenção de clique (exemplo, evento `mouseenter` no link principal de paginação), faça o *prefetch* da rota nos bastidores para que a resposta já esteja em memória técnica quando ocorrer o clique.

---

## 📊 4. Consumo de Dados em Tempo Real

*   **Polling Inteligente:** Consumo para Dashboards estatísticos devem usar *polling* temporizado, mas o ciclo **deve ser pausado automaticamente** quando o usuário mudar de aba ou minimizar a janela, verificando através da `Page Visibility API` (`document.hidden`).
*   **Eventos Contínuos (SSE / WebSockets):** Para dados como progresso de lotes de scripts, atualizações de carteiras, logs ao vivo e balanços de tokens, prefira *Server-Sent Events (SSE)* para streams unidirecionais.

---

## 🎯 5. Interação com Formulários Avançados

*   **Validação Assíncrona no Blur:** Checagens no servidor (como conferir se e-mail/token/usuário já estão em uso) devem dispartar no evento `onblur` individual de cada campo restrito, demonstrando erro na cor da borda bem antes do envio real do form completo.
*   **Auto-save Sustentável:** Formulários densos e longos (como a criação detalhada de um airdrop) devem salvar metadados e "rascunhos" contextuais a cada x tempo utilizando lógicas de submissões parciais ocultas.
*   **Uploads Progressivos Reais:** Manipulação de arquivos binários pesados e imagens deve utilizar `XMLHttpRequest` ou Fetch lendo instâncias FormData que ativamente reportam a porcentagem do envio através da barra de progresso visual, de forma não-bloqueante na UI.
*   **Combobox/Selects Dependentes em Cascata:** Se um campo depender de outro (ex: Categoria Pai -> Sub-categoria), não pré-carregue todo o json em background na inicialização da página; em vez disso, busque localmente por requisição GET na API assim que a primeira variável for imputada.

---

## 🔔 6. UX, Estado Otimista e Micro-Interações

*   **Optimistic UI:** Ao ativar favoritos ("coração"), alternar status (ON/OFF), curtir, ou reordenar dados, **a interface deve aplicar e pintar a nova alteração no mesmo milissegundo** do clique (feedback instantâneo otimista). A camada AJAX atua em background avisando o servidor. Apenas se o servidor retornar código de falha `(500/400)`, você reverte o visual para o estado anterior exibindo alerta.
*   **Persistência Drag-and-Drop:** Se tabelas de "ordem" forem movimentadas usando mouse/toque, envie um lote dos objetos ordenados no background ao evento *drop*, aplicando imediatamente a nova ordem.
*   **Notificações em Background:** Exibir alterações da quantidade numérica nos "badges" do sino no header baseadas em varredura rápida AJAX em background assíncrona.

---

## ✅ 7. Padrões Obrigatórios de Feedback Visual

Para absolutamente qualquer rotina AJAX assíncrona manual engatilhada pelo usuário (como Envio de form):
1. **Loading (Chamada da requisição):** Desabilitar botão principal e injetar spinner no seu interior (ou opacidade nas tabelas/skeleton na view alvo).
2. **Sucesso (Código 200/201):** Fechar modal/reverter estado de carregamento e avisar o usuário usando `toasts` flutuantes automáticos com papel `role="alert"`. O dom deve sofrer manipulação cirúrgica.
3. **Erro Oculto ou Tratado:** Excluir loaders, aplicar *border-danger* e classificar mensagem por pequeno texto em caixa vermelha no elemento alvo nativamente;

---

## 🚫 8. PROIBIÇÕES ABSOLUTAS (Ações Fatais)

Sob risco de corrupção da performance da SPA, **NUNCA** implemente as atitudes abaixo:

1. ❌ **Recarregamento de Página:** Uso de `window.location.reload()`, atribuição brutal estilo `location.href = href` ou enviar requisições pelo form nativo POST com transição de tela.
2. ❌ **Limpagem Macro do DOM:** Apagar a estrutura HTML de uma listagem ou recriar toda uma tabela com 50 linhas em string via JavaScript apenas porque 1 linha mudou seu valor ou 1 novo elemento foi engajado.
3. ❌ **Funções Nativas de Bloqueio:** O uso literal do `alert("cadastrado com sucesso")` ou `confirm("deseja deletar?")` do navegador bloqueiam renderizações, disparam threads mortas temporais e quebram UX. Utilize SweetAlert2 ou modais internos estilizados do Bootstrap.
4. ❌ **DDoS Acidental por Busca:** Acionar um endpoint/SQL na base de dados no evento de teclado onKeyUp para cada letra sem que o *debounce* amorteça.
5. ❌ **Megalomania de Carga Inicial:** Fazer selects de tabelas gigantes de dependências (todas as cidades do país) na carga do HTML da página sem que o usuário tenha pedido ainda.
