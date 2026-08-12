# 🤖 Regra IA: Gerenciamento de SEO e Identidade Visual (Windows/PHP)

Esta regra orienta o LLM na criação e manutenção do módulo de SEO e Identidade Visual. Este módulo é considerado **obrigatório** em qualquer projeto que possua um painel administrativo (`administrador`).

## 🎯 Objetivo
Permitir que o usuário final gerencie títulos, descrições, palavras-chave e imagens sociais (OpenGraph) de cada página, além de definir o logotipo e a cor principal do sistema, sem tocar no código fonte.

## 🛠️ Requisitos Técnicos
1. **Ambiente**: Compatível com Windows (XAMPP).
2. **Banco de Dados**: Usar as tabelas `sys_seo_config` e `sys_configuracoes` conforme definido no template `estrutura_php_elemento_admin_seo.md`.
3. **Segurança**:
   - Proteção CSRF obrigatória em todos os forms.
   - Verificação de nível de acesso (somente `administrador`).
   - Escaping de dados na saída (`htmlspecialchars`).
4. **Design**: Seguir o padrão "Rich Aesthetics" (FontAwesome 6, sombras, gradientes sutis, layout em grid responsivo).

## 📝 Instruções para Execução
Ao ser solicitado a criar ou atualizar uma área administrativa, siga este fluxo:

1. **Verificar Tabelas**: Certifique-se de que as tabelas de configuração existam no banco de dados. Use o prefixo do projeto se necessário.
2. **Implementar a View**: Utilize o código do template `regras/templates_php/estrutura_php_elemento_admin_seo.md`.
3. **Integrar no Header**: Atualize o arquivo principal (`index.php` ou `header.php`) para ler dinamicamente os valores de `<title>` e `<meta>` baseado no parâmetro `page` da URL.
4. **Identidade Visual**: As configurações de cor de marca devem ser aplicadas dinamicamente via CSS (ex: variável `--brand-color`).

## 🚫 Restrições
- NUNCA mencione projetos específicos (como "pizzaria") nos comentários ou variáveis internas deste módulo universal.
- NUNCA use `alert()`. Use notificações suaves ou toasts.
- NUNCA permita que usuários sem perfil `administrador` acessem esta página.

## ✅ Exemplo de Solicitação do Usuário
"Crie a página de SEO do meu admin usando o template universal."
"Adicione a opção de mudar o logotipo do site no painel administrativo."
