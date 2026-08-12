# 📜 Regra UI — Ações em Tabelas via AJAX (sem recarregar página)

## 🎯 Objetivo
Garantir que as ações dentro de cada linha de tabela (modificar, atualizar, editar, excluir, remover) operem via AJAX afetando apenas a linha alvo, sem refresh da página inteira e sem reconstruir toda a tabela.

## 🧭 Escopo
- Páginas administrativas e listagens que possuam ícones de ação na linha.
- Operações: modificar, atualizar, editar, excluir, remover.

## ✅ Regras Obrigatórias
- Ícones de ação devem enviar requisições AJAX para o endpoint correspondente.
- Após sucesso, atualize apenas a linha:
  - Excluir: remover a `<tr>` específica.
  - Editar/Atualizar: atualizar células e badges da `<tr>`.
  - Modificar status: alternar badge ou ícone apenas na `<tr>`.
- Não recarregar toda a página ou toda a tabela.
- Manter posição de scroll e estado de filtros.
- Exibir feedback com toast (sucesso/erro) e nunca usar `alert()`.
- Identificar a linha com atributo `data-*-id` coerente (ex.: `data-token-id`).
- Endpoints devem responder JSON padronizado: `{ success: boolean, message: string, data?: any }`.
- Em erro, exibir toast e preservar o estado visual atual (sem re-render geral).

## 🔒 Segurança
- Validar permissão no backend (ex.: `isAdmin()`).
- Sanitizar IDs recebidos.
- Não expor tokens completos em respostas JSON.

## 🧩 Padrões de Implementação
- Adotar event listeners delegados (evitam rebind após atualizações).
- Encapsular manipulação da linha (buscar `<tr>` por seletor `tr[data-*-id="ID"]`).
- Ajustar vazio da tabela: se não houver linhas, inserir uma linha de estado “Nenhum registro”.

## 🔎 Checklist de Conformidade
- [ ] Ações de linha usam AJAX
- [ ] Atualiza apenas a `<tr>` alvo
- [ ] Sem reload e sem reconstrução total
- [ ] Toasts em todas as respostas
- [ ] Seletores `data-*-id` consistentes
- [ ] Endpoint retorna JSON padronizado

---

Versão: 1.0.0  
Data: 28/02/2026  
Status: ✅ Regra Ativa
