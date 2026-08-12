/**
 * MCP Server - meumcp
 * Servidor Model Context Protocol central para o ecossistema @CanalQb.
 *
 * Unifica conhecimento (Google Docs + regras locais), regras prescritivas,
 * perfis de agentes e MCPs existentes em uma única interface.
 */
import { Server } from '@modelcontextprotocol/sdk/server/index.js';
import { StdioServerTransport } from '@modelcontextprotocol/sdk/server/stdio.js';
import {
  CallToolRequestSchema,
  ListPromptsRequestSchema,
  ListResourcesRequestSchema,
  ListToolsRequestSchema,
  ReadResourceRequestSchema,
} from '@modelcontextprotocol/sdk/types.js';

import * as dotenv from 'dotenv';
dotenv.config();

import { KnowledgeManager } from './knowledge/knowledge-manager.js';
import { RulesEngine } from './rules/rules-engine.js';
import { AgentRegistry } from './agents/agent-registry.js';
import { MCPDiscovery } from './discovery/mcp-discovery.js';
import { ContextResolver } from './context/resolver.js';
import { GoogleDocsIngest } from './ingestion/google-docs.js';
import { logger } from './core/logger.js';
import { config } from './config/index.js';
import { z } from 'zod';

// Initialize managers
const knowledge = new KnowledgeManager({
  canonicalDir: config.knowledge.canonicalDir,
  importedDir: config.knowledge.importedDir,
  indexDir: config.knowledge.indexDir,
});

const rules = new RulesEngine([
  config.rules.canonicalDir,
  config.rules.securityDir,
  config.rules.agentsDir,
  config.rules.toolsDir,
  config.rules.workflowsDir,
]);

const agents = new AgentRegistry({
  registryFile: config.agents.registryFile,
  profilesDir: config.agents.profilesDir,
});

const discovery = new MCPDiscovery({
  registryFile: config.mcps.registryFile,
  enabledFile: config.mcps.enabledFile,
});

const resolver = new ContextResolver(knowledge, rules, agents, discovery);

// State
let initialized = false;

async function initialize(): Promise<void> {
  if (initialized) return;
  logger.info('Loading knowledge base...');
  await knowledge.load();
  await rules.load();
  await agents.load();
  await discovery.discover();
  // Ingest Google Doc if source URL is configured
  if (config.knowledge.googleDocsSourceUrl) {
    const ingest = new GoogleDocsIngest({
      sourceUrl: config.knowledge.googleDocsSourceUrl,
      outputDir: config.knowledge.canonicalDir,
      githubToken: config.external.githubToken,
    });
    await ingest.ingest();
    await knowledge.load();
  }
  initialized = true;
  logger.info('meumcp server initialized');
}

// ─── MCP Server setup ───────────────────────────────────────────────────────

const server = new Server(
  {
    name: config.server.name,
    version: config.server.version,
  },
  {
    capabilities: {
      tools: {},
      resources: {},
      prompts: {},
    },
  },
);

// ─── Tool schemas ───────────────────────────────────────────────────────────

const GetContextSchema = z.object({
  agentId: z.string().default('default'),
  project: z.string().optional(),
  task: z.string().optional(),
  query: z.string().optional(),
  knowledgeCategories: z.array(z.string()).optional(),
  knowledgeTags: z.array(z.string()).optional(),
});

const SearchKnowledgeSchema = z.object({
  query: z.string().min(1),
  category: z.string().optional(),
  tags: z.array(z.string()).optional(),
  limit: z.number().int().positive().default(20),
});

const GetRulesSchema = z.object({
  agentId: z.string().default('default'),
  projectId: z.string().optional(),
  taskId: z.string().optional(),
});

const CheckRuleSchema = z.object({
  ruleId: z.string(),
  action: z.string(),
});

const GetAgentSchema = z.object({
  agentId: z.string().optional(),
});

const ListMCPsSchema = z.object({
  enabled: z.boolean().default(false).optional(),
});

const GetSystemSchema = z.object({
  includeStats: z.boolean().default(true).optional(),
});

const GetLLMRulesSchema = z.object({
  ruleId: z.string().optional(),
  tags: z.array(z.string()).optional(),
  includeContent: z.boolean().default(true).optional(),
});

// ─── Tool handlers ──────────────────────────────────────────────────────────

server.setRequestHandler(ListToolsRequestSchema, async () => ({
  tools: [
    {
      name: 'get_context',
      description: 'Resolve context for an agent/task, unifying knowledge, rules, and MCPs into a single context package.',
      inputSchema: GetContextSchema,
    },
    {
      name: 'search_knowledge',
      description: 'Search the central knowledge base for descriptive facts by query, category, or tags.',
      inputSchema: SearchKnowledgeSchema,
    },
    {
      name: 'get_rules',
      description: 'Get resolved rules for a specific agent, respecting precedence hierarchy (global > org > project > agent).',
      inputSchema: GetRulesSchema,
    },
    {
      name: 'check_rule',
      description: 'Check whether a specific action is allowed under a specific rule.',
      inputSchema: CheckRuleSchema,
    },
    {
      name: 'get_agent',
      description: 'Get agent profile and identify appropriate agent from request context.',
      inputSchema: GetAgentSchema,
    },
    {
      name: 'list_mcps',
      description: 'List discovered MCPs from all configured clients (Claude, Cursor, LM Studio, Hermes, npm).',
      inputSchema: ListMCPsSchema,
    },
    {
      name: 'get_system',
      description: 'Get system information, configuration, and statistics.',
      inputSchema: GetSystemSchema,
    },
    {
      name: 'get_llm_rules',
      description: 'Get LLM rules from the Canonical rules (master_rules + regra_llms_*). Pass ruleId for a specific rule, or tags to filter.',
      inputSchema: GetLLMRulesSchema,
    },
  ],
}));

server.setRequestHandler(CallToolRequestSchema, async (request) => {
  await initialize();

  const { name, arguments: args } = request.params;

  try {
    switch (name) {
      case 'get_context': {
        const parsed = GetContextSchema.parse(args);
        const context = await resolver.resolve(parsed.agentId, {
          project: parsed.project,
          task: parsed.task,
          query: parsed.query,
          knowledgeCategories: parsed.knowledgeCategories,
          knowledgeTags: parsed.knowledgeTags,
        });
        return {
          content: [
            {
              type: 'text',
              text: JSON.stringify(context, null, 2),
            },
          ],
        };
      }

      case 'search_knowledge': {
        const parsed = SearchKnowledgeSchema.parse(args);
        const results = knowledge.search(parsed.query, {
          category: parsed.category,
          tags: parsed.tags,
        });
        const limited = results.slice(0, parsed.limit);
        return {
          content: [
            {
              type: 'text',
              text: JSON.stringify(
                {
                  query: parsed.query,
                  count: results.length,
                  limit: parsed.limit,
                  results: limited.map((r) => ({
                    id: r.id,
                    category: r.category,
                    title: r.title,
                    tags: r.tags,
                    content: r.content.substring(0, 500) + (r.content.length > 500 ? '...' : ''),
                    provenance: r.provenance,
                  })),
                },
                null,
                2,
              ),
            },
          ],
        };
      }

      case 'get_rules': {
        const parsed = GetRulesSchema.parse(args);
        const rulesList = rules.getRulesForAgent(parsed.agentId, {
          project: parsed.projectId,
          taskId: parsed.taskId,
        });
        return {
          content: [
            {
              type: 'text',
              text: JSON.stringify(
                {
                  agentId: parsed.agentId,
                  project: parsed.projectId,
                  task: parsed.taskId,
                  count: rulesList.length,
                  rules: rulesList.map((r) => ({
                    id: r.id,
                    category: r.category,
                    title: r.title,
                    priority: r.priority,
                    allowOverride: r.allowOverride,
                    enforced: r.enforced,
                    tags: r.tags,
                    provenance: r.provenance,
                  })),
                },
                null,
                2,
              ),
            },
          ],
        };
      }

      case 'check_rule': {
        const parsed = CheckRuleSchema.parse(args);
        const result = rules.check(parsed.ruleId, parsed.action);
        return {
          content: [
            {
              type: 'text',
              text: JSON.stringify(result, null, 2),
            },
          ],
        };
      }

      case 'get_agent': {
        const parsed = GetAgentSchema.parse(args);
        const agent = agents.identify(parsed.agentId);
        return {
          content: [
            {
              type: 'text',
              text: JSON.stringify(
                {
                  id: agent.id,
                  name: agent.name,
                  version: agent.version,
                  capabilities: agent.capabilities,
                  knowledge: agent.knowledge,
                  rules: agent.rules,
                  tools: agent.tools,
                  provenance: agent.provenance,
                },
                null,
                2,
              ),
            },
          ],
        };
      }

      case 'list_mcps': {
        const parsed = ListMCPsSchema.parse(args);
        const mcps = parsed.enabled
          ? discovery.getEnabled()
          : discovery.getAll();
        return {
          content: [
            {
              type: 'text',
              text: JSON.stringify(
                {
                  count: mcps.length,
                  enabled: discovery.getEnabled().length,
                  mcps: mcps.map((m) => ({
                    id: m.id,
                    name: m.name,
                    status: m.status,
                    enabled: m.enabled,
                    trust: m.trust,
                    capabilities: m.capabilities,
                    source: m.source,
                  })),
                },
                null,
                2,
              ),
            },
          ],
        };
      }

      case 'get_system': {
        const parsed = GetSystemSchema.parse(args);
        const allKnowledge = knowledge.getAll();
        const allRules = rules.getAll();
        const allAgents = agents.getAll();
        const allMCPs = discovery.getAll();
        const conflicts = rules.getConflicts();

        return {
          content: [
            {
              type: 'text',
              text: JSON.stringify(
                {
                  server: {
                    name: config.server.name,
                    version: config.server.version,
                    transport: config.server.transport,
                  },
                  stats: parsed.includeStats
                    ? {
                        knowledge: { total: allKnowledge.length, sources: knowledge.getSources() },
                        rules: { total: allRules.length, conflicts },
                        agents: { total: allAgents.length },
                        mcps: {
                          total: allMCPs.length,
                          enabled: discovery.getEnabled().length,
                        },
                      }
                    : undefined,
                  config: {
                    knowledge: config.knowledge,
                    rules: config.rules,
                    agents: config.agents,
                    mcps: config.mcps,
                  },
                },
                null,
                2,
              ),
            },
          ],
        };
      }

      case 'get_llm_rules': {
        const parsed = GetLLMRulesSchema.parse(args);
        const allRules = rules.getAll();

        // Filter rules from canonical LLM category
        let filtered = allRules.filter(
          (r) => r.provenance.authority === 'canonical' && (r.category === 'llm-governance' || r.category === 'llm-rule'),
        );

        // Filter by ruleId if provided
        if (parsed.ruleId) {
          const ruleId = parsed.ruleId as string;
          const byId = filtered.filter((r) => r.id === ruleId || r.id.includes(ruleId));
          if (byId.length > 0) filtered = byId;
        }

        // Filter by tags if provided
        if (parsed.tags && parsed.tags.length > 0) {
          const tagSet = new Set(parsed.tags);
          filtered = filtered.filter((r) => r.tags.some((t) => tagSet.has(t)));
        }

        // Strip content if not requested
        let output: unknown[];
        if (!parsed.includeContent) {
          output = filtered.map((r) => ({
            id: r.id,
            category: r.category,
            title: r.title,
            priority: r.priority,
            enforced: r.enforced,
            allowOverride: r.allowOverride,
            tags: r.tags,
            provenance: {
              source: r.provenance.source,
              version: r.provenance.version,
              authority: r.provenance.authority,
            },
          }));
        } else {
          output = filtered;
        }

        return {
          content: [
            {
              type: 'text',
              text: JSON.stringify(
                {
                  count: filtered.length,
                  requestedId: parsed.ruleId,
                  requestedTags: parsed.tags,
                  includeContent: parsed.includeContent,
                  rules: output,
                },
                null,
                2,
              ),
            },
          ],
        };
      }

      default:
        return {
          content: [{ type: 'text', text: `Unknown tool: ${name}` }],
          isError: true,
        };
    }
  } catch (error) {
    logger.error({ error }, 'Tool execution failed');
    return {
      content: [
        {
          type: 'text',
          text: `Error: ${(error as Error).message}`,
        },
      ],
      isError: true,
    };
  }
});

// ─── Resource handlers ──────────────────────────────────────────────────────

server.setRequestHandler(ListResourcesRequestSchema, async () => ({
  resources: [
    {
      uri: 'knowledge://index',
      name: 'Knowledge Index',
      description: 'Index of all knowledge entries by tag and category',
      mimeType: 'application/json',
    },
    {
      uri: 'rules://list',
      name: 'Rules List',
      description: 'All rules with precedence hierarchy',
      mimeType: 'application/json',
    },
    {
      uri: 'agents://registry',
      name: 'Agent Registry',
      description: 'All agent profiles',
      mimeType: 'application/json',
    },
    {
      uri: 'mcps://registry',
      name: 'MCP Registry',
      description: 'All discovered MCPs',
      mimeType: 'application/json',
    },
  ],
}));

server.setRequestHandler(ReadResourceRequestSchema, async (request) => {
  await initialize();
  const { uri } = request.params;

  switch (uri) {
    case 'knowledge://index':
      return {
        contents: [
          {
            uri,
            mimeType: 'application/json',
            text: JSON.stringify(knowledge.getSources(), null, 2),
          },
        ],
      };

    case 'rules://list':
      return {
        contents: [
          {
            uri,
            mimeType: 'application/json',
            text: JSON.stringify(rules.getAll(), null, 2),
          },
        ],
      };

    case 'agents://registry':
      return {
        contents: [
          {
            uri,
            mimeType: 'application/json',
            text: JSON.stringify(agents.getAll(), null, 2),
          },
        ],
      };

    case 'mcps://registry':
      return {
        contents: [
          {
            uri,
            mimeType: 'application/json',
            text: JSON.stringify(discovery.getAll(), null, 2),
          },
        ],
      };

    default:
      throw new Error(`Resource not found: ${uri}`);
  }
});

// ─── Prompt handlers ────────────────────────────────────────────────────────

server.setRequestHandler(ListPromptsRequestSchema, async () => ({
  prompts: [
    {
      name: 'resolve_context',
      description: 'Generate a context resolution prompt for an agent',
      arguments: [
        { name: 'agentId', type: 'string', description: 'Agent identifier', required: true },
        { name: 'task', type: 'string', description: 'Task description', required: true },
        { name: 'project', type: 'string', description: 'Project identifier', required: false },
      ],
    },
  ],
}));

// ─── Start server ───────────────────────────────────────────────────────────

async function main() {
  await initialize();

  if (config.server.transport === 'http') {
    // Streamable HTTP transport
    const { startHTTPServer } = await import('./http-handler');
    const port = config.server.port;
    const host = config.server.host;
    await startHTTPServer(port, host, server);
  } else {
    // stdio transport
    const transport = new StdioServerTransport();
    await server.connect(transport);
    logger.info('meumcp stdio server connected');
  }
}

main().catch((err) => {
  logger.error({ err }, 'Failed to start server');
  process.exit(1);
});
