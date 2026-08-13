# Meumcp — Conflitos de Regras: diagnóstico e como corrigir

Data: 2026-08-13

## O problema
O `meumcp doctor` reporta `Conflicts: 10 detected`, mas NÃO são conflitos reais
de conteúdo. São **falsos positivos** causados por uma heurística ingênua de
detecção baseada em TAGS.

## Onde está o código
Arquivo: `/root/meumcp/src/rules/rules-engine.ts`, método `detectConflicts()`
(linha ~141).

Código atual:
```ts
private detectConflicts(): void {
  const rules = Array.from(this.rules.values());
  for (const rule of rules) {
    const conflicts = rules
      .filter((r) => r.id !== rule.id && r.tags.some((t) => rule.tags.includes(t)))
      .map((r) => r.id);
    if (conflicts.length > 0) {
      this.conflictLog.push({ ruleId: rule.id, conflicts });
      logger.warn({ ruleId: rule.id, conflicts }, 'Conflict detected between rules');
    }
  }
}
```

## Por que acontece
A regra compara apenas **sobreposição de tags**: se duas regras compartilham
pelo menos 1 tag, vira "conflito".

As 10 regras envolvidas são:
- `master_rules` (tags: master, governance, compliance, quality, global)
- `regra_llms_chrome_extension_manifest_v3`
- `regra_llms_comandos_proibidos`
- `regra_llms_explicacoes_leitura_obrigatoria`
- `regra_llms_handoff_continuidade_projeto`
- `regra_llms_log_decisoes_tecnicas`
- `regra_llms_plano_trabalho`
- `regra_llms_seo_avancado_2026`
- `regra_llms_windows_admin_layout`
- `regra_llms_windows_admin_seo`

Todas as `regra_llms_*` compartilham as tags `["llm","rule","compliance"]` e
a `master_rules` compartilha `compliance` — logo TODAS conflitam entre si, mesmo
sem ter nenhuma instrução contraditória. É um falso positivo em cadeia.

## Como corrigir (instrução para o agente)
Alterar `detectConflicts()` para comparar **conteúdo/hash** em vez de tags.

Passos:
1. Abrir `/root/meumcp/src/rules/rules-engine.ts`.
2. Substituir o filtro por tags por um filtro por similaridade de conteúdo.
   Sugestão (mínimo viável): só marcar conflito quando o `hashContent()` dos
   frontmatters/diretrizes forem iguais OU quando houver sobreposição real de
   escopo (ex.: mesmo `id`, `scope` com restrições opostas).
3. Exemplo mínimo de correção (usar hash do conteúdo + tags SEM tag de
   classificação genérica):
   ```ts
   private detectConflicts(): void {
     const rules = Array.from(this.rules.values());
     const GENERIC = new Set(['llm', 'rule', 'compliance', 'governance', 'quality']);
     for (const rule of rules) {
       const conflicts = rules
         .filter((r) => r.id !== rule.id)
         .filter((r) => {
           // ignora tags genéricas de classificação
           const shared = r.tags.filter((t) => rule.tags.includes(t) && !GENERIC.has(t));
           if (shared.length > 0) return true;
           // conflito real: mesmo conteúdo (hash igual) com ids diferentes
           return r.provenance.hash === rule.provenance.hash && r.id !== rule.id;
         })
         .map((r) => r.id);
       if (conflicts.length > 0) {
         this.conflictLog.push({ ruleId: rule.id, conflicts });
         logger.warn({ ruleId: rule.id, conflicts }, 'Conflict detected between rules');
       }
     }
   }
   ```
4. Rebuild: `cd /root/meumcp && npx tsc` (o `prepare` do package.json já roda tsc).
5. Validar: `node dist/cli.js doctor` → esperado `Conflicts: 0 detected` (ou
   apenas conflitos reais, se houver).

Observação: esse fix é LOCAL e não está no upstream
(https://github.com/canalqb/meumcp) — como os fixes de logger/toSchema/wizard,
precisa ser reaplicado após `git pull`/reinstall.

## Nota sobre "está só no celular?"
Não. Os arquivos de regras e o código ficam dentro do MCP em
`/root/meumcp/rules/canonical/llm/` e `/root/meumcp/src/`. O servidor meumcp
roda no opencode da máquina; o celular (proot) é só onde o terminal/opencode
está rodando.