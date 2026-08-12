# 🤖 Regra IA: Personalização Visual Dinâmica (Windows/PHP)

Esta regra orienta o LLM na criação e manutenção do módulo de Customização de Layout via banco de dados. Este módulo é considerado **obrigatório** em qualquer projeto que possua um painel administrativo (`administrador`).

## 🎯 Objetivo
Permitir que o administrador ajuste cores, fontes, arredondamentos e CSS extra de qualquer componente do site (botões, cards, menus, fundos) sem a necessidade de editar arquivos `.css` estáticos.

## 🛠️ Requisitos Técnicos
1. **Ambiente**: Compatível com Windows (XAMPP).
2. **Banco de Dados**: Usar a tabela `sys_layout_config` conforme definido no template `estrutura_php_elemento_admin_layout.md`.
3. **Injeção de Estilos**: Os estilos devem ser gerados dinamicamente em uma tag `<style>` no `<head>` do arquivo principal.
4. **Segurança**:
   - Proteção CSRF obrigatória.
   - Verificação de privilégios (`administrador`).
   - Limpeza de entrada básica (sanitização de seletores).

## 📝 Instruções para Execução
Ao implementar personalização visual em um projeto:

1. **Definição de Seletores**: Ao criar novos componentes no frontend, certifique-se de que eles possuam classes semânticas claras (ex: `.btn-primary`, `.card-product`, `.nav-main`) para facilitar a customização pelo administrador.
2. **Autocomplete (Opcional/Avançado)**: Se possível, implemente uma lista de sugestões de classes comuns no formulário de edição para ajudar o usuário.
3. **Prevalência**: Use `!important` na geração do CSS dinâmico para garantir que as configurações do banco de dados sobreponham o CSS padrão do arquivo `styles.css`.
4. **Live Preview**: Sempre mantenha uma área de pré-visualização no formulário para que o usuário veja o efeito das cores/fontes antes de salvar.

## 🚫 Restrições
- NUNCA mencione o projeto atual (ex: "pizzaria") nos nomes de variáveis ou tabelas deste módulo.
- NUNCA utilize IDs únicos do banco de dados como seletores principais, prefira classes CSS reutilizáveis.
- NUNCA subestime a hierarquia de seletores; oriente o usuário a usar seletores específicos se necessário.

## ✅ Exemplo de Solicitação do Usuário
"Quero poder mudar a cor dos botões do site pelo painel administrativo."
"Crie um editor de layout para o meu sistema usando o template de CSS dinâmico."
