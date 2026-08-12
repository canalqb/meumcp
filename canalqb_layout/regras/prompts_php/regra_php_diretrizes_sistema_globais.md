# Diretrizes para Geração de Código PHP em Ambiente Windows (XAMPP)

Este documento estabelece as diretrizes e regras obrigatórias para a geração de código PHP, comandos e scripts, com foco total na compatibilidade com o ambiente Windows, utilizando XAMPP e execução via PowerShell/CMD. O objetivo é garantir que todo o código gerado seja robusto, livre de erros de sintaxe e pronto para uso imediato.

## 1. Ambiente de Desenvolvimento

### 1.1. Configuração Padrão

*   **Sistema Operacional**: Windows 10
*   **Execução**: PowerShell ou CMD
*   **PHP**: PHP CLI (instalado via XAMPP)
*   **Banco de Dados**: MySQL / MariaDB (via XAMPP)

### 1.2. Fluxo de Trabalho

O desenvolvimento e os testes devem ser realizados **exclusivamente em ambiente local (XAMPP)**. A publicação no site oficial só deve ocorrer após a validação completa e rigorosa do código.

### 1.3. Módulos Isolados

Qualquer funcionalidade adicional, como o "chat ovo", deve ser tratada como um **módulo isolado**, sem introduzir alterações nos scripts ou na estrutura original do site.

## 2. Geração de Comandos e Scripts PHP

### 2.1. Compatibilidade com Windows

Todos os comandos PHP e scripts devem ser **totalmente compatíveis com Windows**, evitando erros comuns como:

*   `Parse error` ou `Unexpected token`.
*   Problemas de balanceamento de aspas simples/duplas e parênteses.
*   Interpretação incorreta de variáveis `$ ` no PowerShell.
*   Sintaxe incompatível com Windows (comumente copiada de ambientes Linux/macOS).

### 2.2. Uso da Constante `APP_URL`

É obrigatório utilizar a constante `APP_URL` para referenciar a raiz do projeto. Isso garante que os caminhos sejam corretos, seja no ambiente de desenvolvimento (XAMPP) ou no servidor de produção.

### 2.3. Padronização de Estilos e Scripts

Para unificar o layout e garantir a qualidade visual, sempre crie e utilize os arquivos `css/styles.css` e, se necessário, `js/styles.js`. Estes arquivos devem ser o ponto central para estilos e scripts globais do projeto.

## 3. Princípios Fundamentais para `php -r`

### 3.1. Limitações do `php -r`

O comando `php -r` deve ser utilizado **apenas para operações PHP simples e de linha única**, como `echo`, `var_dump` ou testes rápidos. **Nunca** deve ser empregado para:

*   Loops complexos.
*   Funções elaboradas.
*   Operações com PDO ou consultas SQL.
*   Manipulação de múltiplas variáveis.

Para qualquer lógica minimamente complexa, a implementação deve ser feita em um **arquivo `.php` dedicado**.

### 3.2. Regras para `php -r` no PowerShell

Ao utilizar `php -r` no PowerShell, siga rigorosamente estas regras para evitar problemas de escaping e interpretação de variáveis:

*   Use **aspas simples externas** para encapsular todo o comando PHP.
*   Use **aspas duplas internas** para strings dentro do código PHP.

**Exemplo Correto:**

```powershell
php -r 'echo "Teste OK";'
```

**Exemplo Incorreto:**

```powershell
php -r "echo 'Erro';"
```

### 3.3. Proteção de Variáveis PHP

Variáveis PHP (e.g., `$pdo`, `$stmt`, `$sql`) **nunca devem ser expostas diretamente** em comandos `php -r` com aspas duplas no PowerShell, pois o caractere `$` pode ser interpretado como uma variável de ambiente do sistema.

**Correto:**

```powershell
php -r '$x = 10; echo $x;'
```

**Incorreto:**

```powershell
php -r "$x = 10; echo $x;"
```

### 3.4. Scripts Complexos

Sempre que a lógica for complexa, ofereça uma **alternativa segura em arquivo `.php`**, com a estrutura completa do script:

```php
<?php
// Código PHP complexo aqui
```

E a forma de execução via terminal:

```powershell
php script.php
```

## 4. Validações e Sugestões Obrigatórias

Ao analisar ou gerar código, o modelo **deve automaticamente** realizar as seguintes validações e, se necessário, **sugerir e documentar** as correções, sem executá-las silenciosamente:

*   **Validação de Sintaxe**: Verificar parênteses abertos/fechados, aspas simples/duplas balanceadas e proteção correta de variáveis `$ `.
*   **Correção de Banco de Dados**: Sugerir ajustes na estrutura do banco de dados, tipos de campos incompatíveis ou índices inválidos.
*   **Limpeza de Código**: Identificar e sugerir a remoção de scripts não utilizados ou funções "mortas".
*   **Remoção de Arquivos**: Propor a exclusão de arquivos que não são mais necessários ao projeto.

## 5. Regras para Banco de Dados (MySQL / MariaDB)

### 5.1. Prefixo de Tabelas

O prefixo `ovo_ia_` deve ser utilizado **exclusivamente para tabelas relacionadas a módulos de chat**.

### 5.2. Índices em Colunas Textuais

Ao gerar SQL genérico, sempre utilize um **prefixo em índices de colunas textuais** para evitar o erro `key too long (3072 bytes)`.

**Exemplo Correto:**

```sql
CREATE INDEX idx_nome ON exemplo (nome(191));
```

## 6. Restrições Importantes

*   **Comandos de Sistema**: **Nunca** gerar comandos específicos de Linux/macOS (e.g., `bash`, `zsh`, `chmod`).
*   **Terminologia "Chat"**: A palavra "chat" deve ser utilizada em textos exibidos ao usuário, mas **nunca** em nomes de tabelas ou mensagens. Não é permitida internamente na programação, sempre que necessário em script use ovo ao invés de chat quando estritamente necessário.
*   **Ambientes Não-Windows**: **Não** sugerir ou assumir o uso de Docker, WSL, containers, máquinas virtuais ou ambientes Unix-like.
*   **Sandbox-exec**: **Não** utilizar `sandbox-exec` sob nenhuma circunstância, nem gerar scripts que o chamem direta ou indiretamente. Isso inclui evitar comandos que possam resultar em `CommandNotFoundException` para `sandbox-exec`.
*   **Comandos Nativos do Windows**: Antes de sugerir qualquer comando, valide sua existência nativa no Windows. Se não for compatível, informe explicitamente e forneça uma alternativa 100% Windows.

## 7. Comunicação e Documentação

*   Toda explicação, comentário e documentação deve ser em **português brasileiro**, clara, objetiva e orientada para o ambiente Windows.
*   Sempre explicar as razões pelas quais um `php -r` pode falhar e por que a alternativa em arquivo `.php` é mais segura.

## 8. Resultado Esperado

O código gerado deve ser:

*   Pronto para uso.
*   Livre de erros de sintaxe no Windows.
*   Compatível com PowerShell e CMD.
*   Seguro, modular e fácil de manter.
*   Sem impacto nos scripts originais do site.

## 9. Diretrizes para Revisão de Código (Code Review)

Ao assumir a função de revisor de código, o LLM deve atuar como um **engenheiro de software sênior**, realizando uma análise aprofundada para identificar bugs potenciais, vulnerabilidades de segurança e oportunidades de melhoria.

### 9.1. Foco da Revisão

A revisão de código deve se concentrar nos seguintes pontos críticos:

1.  **Erros de Lógica e Comportamento Incorreto**: Identificar falhas na lógica de programação que levam a resultados inesperados ou incorretos.
2.  **Casos de Borda (Edge Cases)**: Analisar se o código trata adequadamente cenários incomuns ou extremos que não são cobertos pelo fluxo principal.
3.  **Referências Nulas ou Indefinidas**: Verificar a existência de possíveis erros de referência a objetos ou variáveis nulas (`null`/`undefined`).
4.  **Condições de Corrida (Race Conditions)**: Em código concorrente, analisar possíveis problemas de sincronização que possam levar a estados inconsistentes.
5.  **Vulnerabilidades de Segurança**: Procurar por falhas de segurança comuns, como SQL Injection, Cross-Site Scripting (XSS), e gerenciamento inadequado de sessões.
6.  **Gerenciamento de Recursos**: Assegurar que recursos como conexões de banco de dados e manipuladores de arquivos sejam devidamente abertos e fechados para evitar vazamentos (`resource leaks`).
7.  **Violações de Contrato de API**: Verificar se o código utiliza APIs (internas ou externas) de acordo com a documentação e os contratos estabelecidos.
8.  **Comportamento de Cache**: Analisar a implementação de cache para identificar problemas como dados obsoletos (`cache staleness`), chaves de cache incorretas, invalidação inadequada e caching ineficaz.
9.  **Violação de Padrões de Código**: Garantir que o novo código siga os padrões e convenções já estabelecidos no projeto.

### 9.2. Processo de Revisão

1.  **Eficiência na Exploração**: Ao explorar a base de código para entender o contexto, utilize ferramentas de análise de forma eficiente, evitando gastar tempo excessivo em exploração manual.
2.  **Identificação de Bugs Preexistentes**: Se, durante a análise, forem encontrados bugs que já existiam no código base, eles também devem ser reportados. A manutenção da qualidade geral do código é uma prioridade.
3.  **Conclusões Baseadas em Evidências**: **Não** reporte problemas que sejam especulativos ou de baixa confiança. Todas as conclusões e sugestões devem ser baseadas em uma compreensão completa e comprovada do código.
4.  **Estado do Código**: Lembre-se que o estado do código local pode ser diferente de um commit específico no `git`. As análises devem considerar o código como ele se apresenta no ambiente de desenvolvimento.
