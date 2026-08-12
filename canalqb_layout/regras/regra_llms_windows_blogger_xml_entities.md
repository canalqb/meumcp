# 📜 Regra de Entidades XML/Blogger — Protocolo de Conversão @CanalQb

ESTE ARQUIVO DEFINE AS NORMAS PARA O USO DE ENTIDADES HTML EM TEMPLATES XML DO BLOGGER.

## 🎯 1. Objetivo
Garantir que caracteres especiais sejam corretamente convertidos para entidades XML, evitando erros de renderização ou falhas ao salvar o template no painel do Blogger.

## 📐 2. Entidades Obrigatórias (XML/Blogger)
Estas entidades são essenciais para evitar que o código do Blogger quebre.

| Caractere | Nome da Entidade | Descrição |
|-----------|------------------|-----------|
| `&`       | `&amp;`          | E comercial (obrigatório em links e scripts) |
| `<`       | `&lt;`           | Menor que (início de tags) |
| `>`       | `&gt;`           | Maior que (fim de tags) |
| `"`       | `&quot;`         | Aspas duplas |
| `'`       | `&apos;`         | Aspas simples / Apóstrofo |

## 🎨 3. Símbolos de Texto e Formatação
| Símbolo | Entidade | Descrição |
|---------|----------|-----------|
| Espaço  | `&nbsp;` | Espaço inquebrável |
| ©       | `&copy;` | Copyright |
| ®       | `&reg;`  | Marca Registrada |
| ™       | `&trade;`| Marca Comercial |
| —       | `&mdash;`| Travessão |
| …       | `&hellip;`| Reticências |

## 📐 4. Símbolos Matemáticos e Setas
| Símbolo | Entidade | Descrição |
|---------|----------|-----------|
| ×       | `&times;`| Multiplicação |
| ÷       | `&divide;`| Divisão |
| ≠       | `&ne;`    | Diferente |
| ∞       | `&infin;` | Infinito |
| →       | `&rarr;`  | Seta para Direita |
| ←       | `&larr;`  | Seta para Esquerda |

## 🌍 5. Acentuação (Caso o template não seja UTF-8)
| Caractere | Entidade |
|-----------|----------|
| á         | `&aacute;`|
| é         | `&eacute;`|
| ã         | `&atilde;`|
| ç         | `&ccedil;`|

## 🧪 6. Protocolo de Uso no Blogger
1. **Conversão de Links**: Todo link dentro do XML deve ter o `&` convertido para `&amp;`.
2. **Scripts e Estilos**: Caracteres como `<` e `>` dentro de blocos de script ou estilo devem ser escapados ou envoltos em `<![CDATA[ ... ]]>`.
3. **Atributos de Texto**: Aspas em atributos de texto devem usar `&quot;`.
4. **Validação**: Antes de entregar qualquer código de template Blogger, o LLM deve validar se todas as entidades foram aplicadas corretamente conforme este protocolo.

---
**🚨 LEITURA OBRIGATÓRIA PARA CRIAÇÃO E EDIÇÃO DE TEMPLATES BLOGGER!**
