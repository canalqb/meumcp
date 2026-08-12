# Regra: Páginas Administrativas, Legais e Institucionais (Footer Genérico)

Esta regra complementa a **Regra Ouro 15 (AdSense e SEO 2026)** do Master Rules. Todo site gerado ou atualizado pelo sistema corporativo DEVE OBRIGATORIAMENTE conter a estrutura padrão de páginas institucionais exigidas pelos monitores de qualidade (como Google AdSense) carregadas em janelas modais (`modals`).

## 1. Localização e Escopo

Todo o conteúdo legal genérico deve estar alocado na pasta `raiz/footer/` (ou seja, `footer/` na raiz do sistema do usuário). Exemplo de estrutura física:
- `footer/`
  - `preferencias.php` (Gestão de Preferências)
  - `cookies.php` (Consentimento de Cookies)
  - `direitos.php` (Direitos do Usuário)
  - `dmca.php` (DMCA)
  - `sitemap_page.php` (Sitemap Visual)
  - `transparencia.php` (Transparência de Dados)
  - `disclaimer.php` (Disclaimer)
  - `privacidade.php` (Privacidade e Termos)
  - `contato.php` (Contatos Institucionais)

## 2. Inserção no Footer Padrão

O Rodapé Principal (`footer.php` ou `index.php` estático) DEVE conter hiperlinks para carregar cada um destes arquivos de maneira dinâmica num Modal interativo centrado (AJAX ou Iframe, de preferência AJAX renderizando HTML).

**Requisito de Front-End:**
O Template Universal gerado deve carregar a seguinte hierarquia visual na interface:
- **`HEAD` Modificado do Modal:** O título `<h2 class="modal-title">` deve referenciar perfeitamente o título do documento.
- **`BODY` de Conteúdo:** `<div class="modal-body">` contendo explicitamente o texto normatizado (mais de 500 palavras) com a aplicação da tag de semântica correta.

## 3. Conformidade AdSense, Qualidade e SEO (E-E-A-T)

Para preencher a estrutura e a narrativa com sucesso:
*   Os textos contidos nas páginas `/footer/` devem transparecer autoridade no tema (Expertise) e transmitir uma imagem clara e corporativa das operações normais (Confiabilidade).
*   Não possuir trechos genéricos sem sentido (texto "Lorem Ipsum" é terminantemente proibido nessas seções, gerando reprovação imediata).
*   Páginas de Privacidade e DMCA devem indicar explicitamente e-mails ou formulários claros para contato/denúncias.
*   Direitos dos usuários devem citar LGPD (no Brasil) e GDPR (Europa) garantindo acesso a deleção voluntária de conta.

## 4. O que o LLM Deve Produzir Automático

Sempre que a infraestrutura central de um site novo for montada, o modelo de inteligência artificial TEM A OBRIGAÇÃO DE:
1. Criar o subdiretório `footer/` (se não existir).
2. Gerar o layout e componente Modal que esculta os links desse rodapé.
3. Preencher no mínimo 3 parágrafos profissionais para cara uma destas páginas usando um arquivo de *Template* preestabelecido.
