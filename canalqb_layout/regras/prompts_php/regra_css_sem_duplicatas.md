# 🚫 Regra: CSS Sem Duplicatas — Validação Obrigatória

> **LEITURA OBRIGATÓRIA antes de criar ou modificar qualquer bloco CSS, em qualquer arquivo do projeto.**

---

## 🎯 Problema Documentado

O `styles.css` acumulou **blocos duplicados** para as mesmas classes. O segundo bloco (com `!important`) **sobrescredia silenciosamente** o primeiro, causando bugs visuais difíceis de rastrear. Exemplo real:

- Bloco 1 (linha ~758): `left: 60%` → avatar à direita ✅  
- Bloco 2 (linha ~4405): `left: 14px !important` → avatar à esquerda ❌ (este vencia)

**310 linhas duplicadas foram removidas em 22/03/2026.**

---

## ✅ Protocolo Obrigatório — ANTES de Escrever Qualquer CSS

### 1️⃣ Buscar o seletor no arquivo inteiro

```powershell
# PowerShell
Select-String -Path 'css\styles.css' -Pattern '\.nome-da-classe' | ForEach-Object { "[$($_.LineNumber)]: $($_.Line.Trim())" }
```

```bash
# bash/grep
grep -n "nome-da-classe" css/styles.css
```

Se encontrar **mais de 1 ocorrência** do mesmo seletor: **CONSOLIDAR antes de continuar.**

---

### 2️⃣ Regras de Consolidação

1. **Identificar qual bloco vence**: o que vier mais depois no arquivo E/OU usar `!important` vence.
2. **Mesclar propriedades únicas** do bloco antigo no bloco definitivo.
3. **Apagar o bloco antigo inteiramente** — não comentar, apagar.
4. **Verificar conflito** entre propriedades mescladas.

---

### 3️⃣ O que é permitido ter duplicado (exceções válidas)

| Tipo | Exemplo | Permitido? |
|---|---|---|
| Dark mode | `[data-theme="dark"] .classe` | ✅ Sim |
| Media query | `@media (max-width: 768px) .classe` | ✅ Sim |
| Pseudo-classe | `.classe:hover`, `.classe:focus` | ✅ Sim |
| Modificador | `.classe.active`, `.classe.disabled` | ✅ Sim |
| **Seletor idêntico** | `.classe { }` aparecendo 2x sem diferença | ❌ Nunca |

---

### 4️⃣ Tipos de duplicata a procurar ativamente

- Mesmo seletor em seções diferentes do CSS
- Mesmo seletor com prefixo de página diferente mas classe igual
- Bloco adicionado via "append" ao final do arquivo sem verificar existência
- Bloco copiado de outro projeto/componente sem limpeza

---

### 5️⃣ Checklist de Validação (executar ANTES de entregar)

```
[ ] Busquei cada seletor modificado no CSS completo
[ ] Não existe mais de uma definição idêntica de cada seletor
[ ] !important foi usado apenas quando estritamente necessário
[ ] Verifiquei no DevTools que o estilo correto está sendo aplicado (não riscado)
[ ] Nenhum bloco foi apenas "adicionado ao final" sem verificar duplicata
```

---

## 🔍 Sinais de Alerta de CSS Duplicado

- Propriedade no CSS mas **não aparece no browser**
- Estilo só funciona adicionando `!important` (sinal de que outro bloco vence)
- DevTools mostra a propriedade **riscada** (sobrescrita por outro seletor)
- Mesmo seletor com **valores contraditórios** no DevTools

---

## 🗂️ Arquivos CSS do Projeto — Verificar Duplicatas

| Arquivo | Escopo |
|---|---|
| `css/styles.css` | CSS global principal — maior risco de duplicatas |
| `css/topbar.css` | Topbar/header |
| Qualquer `<style>` inline em `.php` | Risco de conflito com `styles.css` |

> 🚨 **CSS inline em arquivos `.php`** pode sobrescrever `styles.css` — sempre verificar se o estilo já existe no CSS global antes de adicionar inline.

---

## 📁 Referência

- **Arquivo CSS principal:** `css/styles.css`
- **Regra geral:** `regras/master_rules.md` — item 18 (CSS Sem Duplicatas)
