/**
 * meumcp - Index principal
 * Exporta todos os módulos do MCP central.
 */
export { config } from './config/index';
export { logger } from './core/logger';
export type {
  Provenance,
  KnowledgeEntry,
  Rule,
  AgentProfile,
  MCPRecord,
  ContextPackage,
} from './core/types';

export { KnowledgeManager } from './knowledge/knowledge-manager';
export { RulesEngine } from './rules/rules-engine';
export { AgentRegistry } from './agents/agent-registry';
export { MCPDiscovery } from './discovery/mcp-discovery';
export { ContextResolver } from './context/resolver';
export { GoogleDocsIngest } from './ingestion/google-docs';

// Version
export const VERSION = '1.0.0';
