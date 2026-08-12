/**
 * Context Resolver
 * Resolve contexto para uma tarefa específica, unificando conhecimento, regras,
 * agentes, e MCPs habilitados.
 */
import { logger } from '../core/logger';
import type { KnowledgeManager } from '../knowledge/knowledge-manager';
import type { RulesEngine } from '../rules/rules-engine';
import type { AgentRegistry } from '../agents/agent-registry';
import type { MCPDiscovery } from '../discovery/mcp-discovery';
import type { ContextPackage, KnowledgeEntry, Rule, MCPRecord } from '../core/types';

export class ContextResolver {
  constructor(
    private knowledge: KnowledgeManager,
    private rules: RulesEngine,
    private agents: AgentRegistry,
    private discovery: MCPDiscovery,
  ) {}

  async resolve(
    agentId: string,
    opts?: {
      project?: string;
      task?: string;
      query?: string;
      knowledgeCategories?: string[];
      knowledgeTags?: string[];
      toolIds?: string[];
    },
  ): Promise<ContextPackage> {
    logger.info({ agentId, ...opts }, 'Resolving context');

    // Identify agent
    const agent = this.agents.identify(agentId);

    // Get knowledge
    let knowledgeEntries: KnowledgeEntry[] = [];
    if (opts?.query) {
      knowledgeEntries = this.knowledge.search(opts.query, {
        category: opts.knowledgeCategories?.[0],
        tags: opts.knowledgeTags,
      });
    } else {
      const all = this.knowledge.getAll();
      knowledgeEntries = all.slice(0, 50);
    }

    // Apply agent knowledge constraints
    if (agent.knowledge.required.length > 0) {
      knowledgeEntries = knowledgeEntries.filter(
        (k) =>
          agent.knowledge.required.includes(k.id) ||
          agent.knowledge.required.includes(k.category),
      );
    }
    if (agent.knowledge.forbidden.length > 0) {
      const forbidden = new Set(agent.knowledge.forbidden);
      knowledgeEntries = knowledgeEntries.filter((k) => !forbidden.has(k.id));
    }

    // Get rules
    let agentRules: Rule[] = [];
    if (opts?.task) {
      agentRules = this.rules.getRulesForAgent(agentId, {
        project: opts.project,
        taskId: opts.task,
      });
    } else {
      agentRules = this.rules.getRulesForAgent(agentId);
    }

    // Apply agent rule constraints
    if (agent.rules.required.length > 0) {
      const required = new Set(agent.rules.required);
      agentRules = agentRules.filter((r) => required.has(r.id));
    }

    // Get enabled MCPs
    const enabledMCPs: MCPRecord[] = this.discovery.getEnabled();

    // Build allowed tools list
    const allowedTools: string[] = [];
    for (const mcp of enabledMCPs) {
      allowedTools.push(...mcp.capabilities.tools);
    }

    // Apply agent tool constraints
    if (agent.tools.allow.length > 0) {
      const allowed = new Set(agent.tools.allow);
      for (let i = allowedTools.length - 1; i >= 0; i--) {
        if (!allowed.has(allowedTools[i])) {
          allowedTools.splice(i, 1);
        }
      }
    }
    if (agent.tools.deny.length > 0) {
      const denied = new Set(agent.tools.deny);
      for (let i = allowedTools.length - 1; i >= 0; i--) {
        if (denied.has(allowedTools[i])) {
          allowedTools.splice(i, 1);
        }
      }
    }

    // Get capabilities
    const capabilities: string[] = [];
    for (const mcp of enabledMCPs) {
      capabilities.push(...mcp.capabilities.tools, ...mcp.capabilities.resources);
    }

    // Build restrictions based on rules
    const restrictions: string[] = [];
    for (const rule of agentRules) {
      if (!rule.allowOverride) {
        restrictions.push(`enforced:${rule.id}:${rule.title}`);
      }
      if (rule.enforced) {
        restrictions.push(`enforced:${rule.id}`);
      }
    }

    // Build provenance from all sources
    const provenance = [
      ...knowledgeEntries.map((k) => k.provenance),
      ...agentRules.map((r) => r.provenance),
    ];

    const context: ContextPackage = {
      agent: agentId,
      project: opts?.project,
      task: opts?.task,
      knowledge: knowledgeEntries,
      rules: agentRules,
      capabilities,
      allowedTools,
      restrictions,
      references: enabledMCPs.map((m) => ({
        id: m.id,
        name: m.name,
        source: m.source,
        capabilities: m.capabilities,
      })),
      provenance,
      version: '1.0.0',
    };

    logger.info(
      {
        agentId,
        knowledgeCount: knowledgeEntries.length,
        rulesCount: agentRules.length,
        mcpCount: enabledMCPs.length,
        toolsCount: allowedTools.length,
      },
      'Context resolved',
    );

    return context;
  }

  getMCPs(): MCPRecord[] {
    return this.discovery.getAll();
  }
}
