/**
 * Tipos e schemas canônicos do meumcp.
 * Todos os dados carregam proveniência: origem, versão, autoridade, timestamp.
 */
import { z } from 'zod';

// ─── Proveniência (origin metadata) ─────────────────────────────────────────

export const ProvenanceSchema = z.object({
  source: z.string().min(1),
  sourceType: z.enum(['google-doc', 'local-file', 'mcp', 'manual']),
  version: z.string().min(1).default('1.0.0'),
  createdAt: z.coerce.date(),
  updatedAt: z.coerce.date(),
  priority: z.number().int().min(0).default(0),
  scope: z.enum(['global', 'project', 'agent']).default('global'),
  authority: z.enum(['canonical', 'imported', 'draft']).default('imported'),
  status: z.enum(['active', 'deprecated', 'conflicting']).default('active'),
  hash: z.string().min(1),
  author: z.string().optional(),
});

export type Provenance = z.infer<typeof ProvenanceSchema>;

// ─── Knowledge Entry (fato descritivo) ──────────────────────────────────────

export const KnowledgeEntrySchema = z.object({
  id: z.string().min(1),
  category: z.string().min(1),
  title: z.string().min(1),
  content: z.string().min(1),
  tags: z.array(z.string()).default([]),
  provenance: ProvenanceSchema,
  relationships: z.array(z.string()).default([]),
  schema: z.string().optional(),
});

export type KnowledgeEntry = z.infer<typeof KnowledgeEntrySchema>;

// ─── Rule (política prescritiva) ────────────────────────────────────────────

export const RuleScopeSchema = z.object({
  global: z.boolean().default(true),
  project: z.array(z.string()).default([]),
  agent: z.array(z.string()).default([]),
});

export const RuleSchema = z.object({
  id: z.string().min(1),
  category: z.string().min(1),
  title: z.string().min(1),
  description: z.string().min(1),
  content: z.string().min(1),
  priority: z.number().int().min(0).default(0),
  scope: RuleScopeSchema.default({}),
  allowOverride: z.boolean().default(true),
  enforced: z.boolean().default(true),
  tags: z.array(z.string()).default([]),
  provenance: ProvenanceSchema,
});

export type Rule = z.infer<typeof RuleSchema>;

// ─── Agent Profile ──────────────────────────────────────────────────────────

export const AgentProfileSchema = z.object({
  id: z.string().min(1),
  name: z.string().min(1),
  version: z.string().min(1).default('1.0.0'),
  capabilities: z.array(z.string()).default([]),
  knowledge: z
    .object({
      required: z.array(z.string()).default([]),
      forbidden: z.array(z.string()).default([]),
    })
    .default({}),
  rules: z
    .object({
      required: z.array(z.string()).default([]),
      forbidden: z.array(z.string()).default([]),
    })
    .default({}),
  tools: z
    .object({
      allow: z.array(z.string()).default([]),
      deny: z.array(z.string()).default([]),
    })
    .default({}),
  provenance: ProvenanceSchema,
});

export type AgentProfile = z.infer<typeof AgentProfileSchema>;

// ─── MCP Discovery Record ───────────────────────────────────────────────────

export const MCPStatusSchema = z.enum([
  'discovered',
  'inspected',
  'validated',
  'approved',
  'enabled',
  'disabled',
  'broken',
]);

export const MCPRecordSchema = z.object({
  id: z.string().min(1),
  name: z.string().min(1),
  source: z
    .object({
      type: z.enum(['local', 'remote', 'config', 'global']),
      command: z.string().optional(),
      url: z.string().optional(),
      path: z.string().optional(),
    })
    .default({ type: 'local' }),
  status: MCPStatusSchema.default('discovered'),
  enabled: z.boolean().default(false),
  trust: z.enum(['unknown', 'low', 'medium', 'high']).default('unknown'),
  capabilities: z
    .object({
      tools: z.array(z.string()).default([]),
      resources: z.array(z.string()).default([]),
      prompts: z.array(z.string()).default([]),
    })
    .default({}),
  metadata: z.record(z.unknown()).default({}),
  discoveredAt: z.coerce.date(),
  updatedAt: z.coerce.date(),
});

export type MCPRecord = z.infer<typeof MCPRecordSchema>;

// ─── Context Package (resolved context) ─────────────────────────────────────

export const ContextPackageSchema = z.object({
  agent: z.string().min(1),
  project: z.string().optional(),
  task: z.string().optional(),
  knowledge: z.array(z.any()).default([]),
  rules: z.array(z.any()).default([]),
  capabilities: z.array(z.string()).default([]),
  allowedTools: z.array(z.string()).default([]),
  restrictions: z.array(z.string()).default([]),
  references: z.array(z.any()).default([]),
  provenance: z.array(ProvenanceSchema).default([]),
  version: z.string().min(1).default('1.0.0'),
});

export type ContextPackage = z.infer<typeof ContextPackageSchema>;
