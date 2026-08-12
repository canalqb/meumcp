/**
 * Configuração central do meumcp.
 */
import { z } from 'zod';
import { logger } from '../core/logger';

export const ConfigSchema = z.object({
  server: z
    .object({
      name: z.string().default('meumcp'),
      version: z.string().default('1.0.0'),
      transport: z.enum(['stdio', 'http']).default('stdio'),
      port: z.coerce.number().default(8765),
      host: z.string().default('127.0.0.1'),
    })
    .default({}),
  knowledge: z
    .object({
      googleDocsSourceUrl: z.string().url().optional(),
      localRulesDir: z.string().default('./regras'),
      canonicalDir: z.string().default('./knowledge/canonical'),
      importedDir: z.string().default('./knowledge/imported'),
      generatedDir: z.string().default('./knowledge/generated'),
      indexDir: z.string().default('./knowledge/index'),
    })
    .default({}),
  rules: z
    .object({
      canonicalDir: z.string().default('./rules/canonical'),
      agentsDir: z.string().default('./rules/agents'),
      toolsDir: z.string().default('./rules/tools'),
      securityDir: z.string().default('./rules/security'),
      workflowsDir: z.string().default('./rules/workflows'),
      priorityOverride: z.boolean().default(true),
    })
    .default({}),
  agents: z
    .object({
      registryFile: z.string().default('./agents/agents.json'),
      profilesDir: z.string().default('./agents/profiles'),
    })
    .default({}),
  mcps: z
    .object({
      discoveredDir: z.string().default('./mcps/discovered'),
      enabledFile: z.string().default('./mcps/enabled.json'),
      registryFile: z.string().default('./mcps/registry.json'),
      keyhunterRegistryFile: z.string().default('./knowledge/generated/mcp_registry.json'),
    })
    .default({}),
  external: z
    .object({
      supabaseUrl: z.string().url().optional(),
      supabaseKey: z.string().optional(),
      githubToken: z.string().optional(),
      githubOwner: z.string().default('canalqb'),
      githubRepo: z.string().default('meumcp'),
      brightdataToken: z.string().optional(),
    })
    .default({}),
  logging: z
    .object({
      level: z.string().default('info'),
    })
    .default({}),
});

export type Config = z.infer<typeof ConfigSchema>;

export const config: Config = ConfigSchema.parse({
  server: {
    name: process.env.MCP_SERVER_NAME || 'meumcp',
    version: process.env.MCP_SERVER_VERSION || '1.0.0',
    transport: (process.env.MCP_TRANSPORT as 'stdio' | 'http') || 'stdio',
    port: Number(process.env.MCP_PORT) || 8765,
    host: process.env.MCP_HOST || '127.0.0.1',
  },
  knowledge: {
    googleDocsSourceUrl: process.env.GOOGLE_DOCS_SOURCE_URL,
    localRulesDir: process.env.LOCAL_RULES_DIR || './regras',
  },
  external: {
    githubToken: process.env.GITHUB_TOKEN,
    githubOwner: process.env.GITHUB_OWNER || 'canalqb',
    githubRepo: process.env.GITHUB_REPO || 'meumcp',
  },
  logging: {
    level: process.env.LOG_LEVEL || 'info',
  },
});

logger.info(
  { name: config.server.name, version: config.server.version },
  'Configuration loaded',
);
