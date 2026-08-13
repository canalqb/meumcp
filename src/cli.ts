#!/usr/bin/env node
/**
 * CLI - meumcp
 * Interface de linha de comando para gerenciar o MCP central.
 */

// Silence logger in CLI mode - MUST be set before requiring any module that loads config/logger
process.env.MEUMCP_CLI = 'true';

// Load config and logger AFTER env var is set
const { config } = require('./config/index');
const { logger } = require('./core/logger');
logger.level = 'silent';

// Now load the rest
const { Command } = require('commander');
const { promises: fs } = require('fs');
const path = require('path');
const picocolors = require('picocolors');
const prompts = require('prompts');
const { KnowledgeManager } = require('./knowledge/knowledge-manager');
const { RulesEngine } = require('./rules/rules-engine');
const { AgentRegistry } = require('./agents/agent-registry');
const { MCPDiscovery } = require('./discovery/mcp-discovery');
const { ContextResolver } = require('./context/resolver');
const { GoogleDocsIngest } = require('./ingestion/google-docs');

const program = new Command();

program
  .name('meumcp')
  .description('Central MCP server for the @CanalQb ecosystem')
  .version(config.server.version);

// ─── Commands ────────────────────────────────────────────────────────────────

async function initProject(): Promise<void> {
  const dirs = [
    'knowledge/canonical', 'knowledge/imported', 'knowledge/generated', 'knowledge/index',
    'rules/canonical', 'rules/agents', 'rules/tools', 'rules/security', 'rules/workflows',
    'agents/profiles', 'mcps/discovered', 'tests', 'data',
  ];
  for (const dir of dirs) {
    await fs.mkdir(path.resolve(dir), { recursive: true });
    console.log(picocolors.green(`  Created ${dir}/`));
  }

  const agentsFile = path.resolve('agents/agents.json');
  await fs.writeFile(
    agentsFile,
    JSON.stringify([
      { id: 'default', name: 'Default Agent', version: '1.0.0',
        capabilities: ['knowledge', 'rules'],
        knowledge: { required: [], forbidden: [] },
        rules: { required: [], forbidden: [] },
        tools: { allow: [], deny: [] } },
      { id: 'content-creator', name: 'Content Creator', version: '1.0.0',
        capabilities: ['knowledge', 'rules', 'web', 'image'],
        knowledge: { required: ['seo', 'blogging', 'youtube'], forbidden: ['internal-tools'] },
        rules: { required: ['master_rules'], forbidden: [] },
        tools: { allow: ['search'], deny: ['destructive'] } },
    ], null, 2),
    'utf-8',
  );
  console.log(picocolors.green('  Created agents/agents.json'));

  await fs.writeFile(path.resolve('mcps/enabled.json'), JSON.stringify([], null, 2), 'utf-8');
  console.log(picocolors.green('  Created mcps/enabled.json'));

  try {
    await fs.access(path.resolve('.env'));
    console.log(picocolors.yellow('  .env already exists, skipping'));
  } catch {
    await fs.copyFile(path.resolve('.env.example'), path.resolve('.env'));
    console.log(picocolors.green('  Created .env (from .env.example)'));
  }

  console.log(picocolors.bold('\n  Next steps:'));
  console.log(`    1. ${picocolors.cyan('meumcp ingest')} - import Google Doc + regras locais`);
  console.log(`    2. ${picocolors.cyan('meumcp discover')} - scan for installed MCPs`);
  console.log(`    3. ${picocolors.cyan('meumcp serve')} - start the MCP server`);
}

async function ingestSource(source: string | undefined, outputDir?: string): Promise<void> {
  const ingest = new GoogleDocsIngest({
    outputDir: outputDir || config.knowledge.canonicalDir,
    githubToken: config.external.githubToken,
  });

  if (!source || source === 'google-doc') {
    console.log(picocolors.cyan('  Ingesting Google Doc...'));
    const result = await ingest.ingest();
    console.log(picocolors.green(`  Ingested ${result.entries} entries to ${result.outputPath}`));
  }

  if (!source || source === 'rules' || source === 'all') {
    const rulesDir = config.knowledge.localRulesDir;
    console.log(picocolors.cyan(`  Ingesting local rules from ${rulesDir}...`));
    const engine = new RulesEngine([path.resolve(rulesDir)]);
    await engine.load();
    console.log(picocolors.green(`  Loaded ${engine.getAll().length} rules`));
  }

  if (!source || source === 'all') {
    console.log(picocolors.green('  Full ingestion complete'));
  }
}

async function discoverMCPs(): Promise<void> {
  const discovery = new MCPDiscovery({
    registryFile: path.resolve(config.mcps.registryFile),
    keyhunterRegistryFile: path.resolve(config.mcps.keyhunterRegistryFile),
    enabledFile: path.resolve(config.mcps.enabledFile),
  });
  const result = await discovery.discover();

  console.log(picocolors.bold('\n  MCP Discovery Results'));
  console.log(`    Total:      ${picocolors.cyan(result.summary.total)}`);
  console.log(`    Enabled:    ${picocolors.green(result.summary.enabled)}`);
  console.log(`    Discovered: ${picocolors.yellow(result.summary.discovered)}`);
  console.log(`    Broken:     ${picocolors.red(result.summary.broken)}`);

  const mcps = discovery.getAll();
  if (mcps.length > 0) {
    console.log(picocolors.bold('\n  Discovered MCPs:'));
    for (const mcp of mcps) {
      const status = mcp.enabled ? picocolors.green('enabled') : picocolors.gray('disabled');
      const trust = picocolors.yellow(`[${mcp.trust}]`);
      console.log(`    ${picocolors.cyan(mcp.name)} ${trust} ${status} (${mcp.source.type})`);
    }
  }
}

async function resolveContext(opts: { agent: string; project?: string; task?: string; query?: string }): Promise<void> {
  const knowledge = new KnowledgeManager({
    canonicalDir: path.resolve(config.knowledge.canonicalDir),
    importedDir: path.resolve(config.knowledge.importedDir),
    indexDir: path.resolve(config.knowledge.indexDir),
  });
  const rulesEngine = new RulesEngine([
    path.resolve(config.rules.canonicalDir),
    path.resolve(config.rules.securityDir),
  ]);
  const agentReg = new AgentRegistry({
    registryFile: path.resolve(config.agents.registryFile),
    profilesDir: path.resolve(config.agents.profilesDir),
  });
  const mcps = new MCPDiscovery({
    registryFile: path.resolve(config.mcps.registryFile),
    keyhunterRegistryFile: path.resolve(config.mcps.keyhunterRegistryFile),
    enabledFile: path.resolve(config.mcps.enabledFile),
  });

  await knowledge.load();
  await rulesEngine.load();
  await agentReg.load();
  await mcps.discover();

  const resolver = new ContextResolver(knowledge, rulesEngine, agentReg, mcps);
  const context = await resolver.resolve(opts.agent, {
    project: opts.project,
    task: opts.task,
    query: opts.query,
  });

  console.log(JSON.stringify(context, replacer, 2));
}

function replacer(key: string, value: unknown): unknown {
  return value instanceof Date ? value.toISOString() : value;
}

async function listRules(opts: { agent?: string }): Promise<void> {
  const engine = new RulesEngine([
    path.resolve(config.rules.canonicalDir),
    path.resolve(config.rules.securityDir),
  ]);
  await engine.load();
  const allRules = opts.agent ? engine.getRulesForAgent(opts.agent) : engine.getAll();

  console.log(JSON.stringify(
    allRules.map((r: any) => ({
      id: r.id, category: r.category, title: r.title, priority: r.priority,
      allowOverride: r.allowOverride, enforced: r.enforced, tags: r.tags,
      provenance: r.provenance,
    })),
    replacer, 2,
  ));
}

async function searchKnowledge(opts: { query: string; category?: string; limit: string }): Promise<void> {
  const knowledge = new KnowledgeManager({
    canonicalDir: path.resolve(config.knowledge.canonicalDir),
    importedDir: path.resolve(config.knowledge.importedDir),
    indexDir: path.resolve(config.knowledge.indexDir),
  });
  await knowledge.load();
  const results = knowledge.search(opts.query, { category: opts.category });
  const limited = results.slice(0, Number(opts.limit));

  console.log(JSON.stringify({
    query: opts.query, count: results.length,
    results: limited.map((r: any) => ({
      id: r.id, category: r.category, title: r.title, tags: r.tags,
      excerpt: r.content.substring(0, 300), provenance: r.provenance,
    })),
  }, replacer, 2));
}

async function listMCPs(opts: { enabled?: boolean }): Promise<void> {
  const discovery = new MCPDiscovery({
    registryFile: path.resolve(config.mcps.registryFile),
    keyhunterRegistryFile: path.resolve(config.mcps.keyhunterRegistryFile),
    enabledFile: path.resolve(config.mcps.enabledFile),
  });
  await discovery.discover();
  const mcps = opts.enabled ? discovery.getEnabled() : discovery.getAll();

  console.log(JSON.stringify({
    count: mcps.length, enabled: discovery.getEnabled().length,
    mcps: mcps.map((m: any) => ({
      id: m.id, name: m.name, status: m.status, enabled: m.enabled, trust: m.trust,
      capabilities: m.capabilities, source: m.source,
    })),
  }, replacer, 2));
}

async function listAgents(): Promise<void> {
  const agentReg = new AgentRegistry({
    registryFile: path.resolve(config.agents.registryFile),
    profilesDir: path.resolve(config.agents.profilesDir),
  });
  await agentReg.load();
  console.log(JSON.stringify(agentReg.getAll(), replacer, 2));
}

async function startStdio(): Promise<void> {
  console.log(picocolors.cyan('Starting meumcp MCP server (stdio)...'));
  delete process.env.MEUMCP_CLI;  // Re-enable logger for server
  await import('./server');
}

async function startHTTP(port: number, host: string): Promise<void> {
  delete process.env.MEUMCP_CLI;
  process.env.MCP_TRANSPORT = 'http';
  process.env.MCP_PORT = port.toString();
  process.env.MCP_HOST = host;
  console.log(picocolors.cyan(`Starting meumcp MCP server (HTTP on ${host}:${port})...`));
  await import('./server');
}

async function runDoctor(): Promise<void> {
  console.log(picocolors.bold('\n=== meumcp Doctor ===\n'));

  const nodeVersion = process.version;
  const major = Number(nodeVersion.slice(1).split('.')[0]);
  console.log(`  Node.js: ${major >= 20 ? picocolors.green(nodeVersion) : picocolors.yellow(`${nodeVersion} (recommend >= v20)`)}`);

  try {
    require('@modelcontextprotocol/sdk');
    console.log(`  MCP SDK: ${picocolors.green('installed')}`);
  } catch {
    console.log(`  MCP SDK: ${picocolors.yellow('verify with: npm ls @modelcontextprotocol/sdk')}`);
  }

  const knowledge = new KnowledgeManager({
    canonicalDir: path.resolve(config.knowledge.canonicalDir),
    importedDir: path.resolve(config.knowledge.importedDir),
    indexDir: path.resolve(config.knowledge.indexDir),
  });
  try {
    await knowledge.load();
    console.log(`  Knowledge: ${picocolors.green(`${knowledge.getAll().length} entries`)}`);
  } catch {
    console.log(`  Knowledge: ${picocolors.yellow('not loaded')}`);
  }

  const rulesEngine = new RulesEngine([
    path.resolve(config.rules.canonicalDir),
    path.resolve(config.rules.securityDir),
  ]);
  try {
    await rulesEngine.load();
    console.log(`  Rules: ${picocolors.green(`${rulesEngine.getAll().length} rules`)}`);
    const conflicts = rulesEngine.getConflicts();
    if (conflicts.length > 0) {
      console.log(`  Conflicts: ${picocolors.yellow(`${conflicts.length} detected`)}`);
    }
  } catch {
    console.log(`  Rules: ${picocolors.yellow('not loaded')}`);
  }

  const discovery = new MCPDiscovery({
    registryFile: path.resolve(config.mcps.registryFile),
    keyhunterRegistryFile: path.resolve(config.mcps.keyhunterRegistryFile),
    enabledFile: path.resolve(config.mcps.enabledFile),
  });
  await discovery.discover();
  console.log(`  MCPs found: ${picocolors.green(discovery.getAll().length.toString())}`);

  console.log(picocolors.bold('\n=== Doctor Complete ===\n'));
}

// ─── Register commands ─────────────────────────────────────────────────────

program
  .command('init')
  .description('Initialize project folder structure')
  .action(initProject);

program
  .command('ingest [source]')
  .description('Ingest and normalize knowledge sources (google-doc, rules, all)')
  .option('-o, --output <dir>', 'Output directory for canonical knowledge')
  .action((source: string | undefined, opts: { output?: string }) => ingestSource(source, opts.output));

program
  .command('discover')
  .description('Discover all MCPs configured on this machine')
  .action(discoverMCPs);

program
  .command('context')
  .description('Resolve context for an agent and task')
  .requiredOption('-a, --agent <id>', 'Agent ID')
  .option('-p, --project <name>', 'Project name')
  .option('-t, --task <name>', 'Task name')
  .option('-q, --query <text>', 'Knowledge query')
  .action((opts: any) => resolveContext(opts));

program
  .command('rules')
  .description('List all rules')
  .option('-a, --agent <id>', 'Filter by agent ID')
  .action((opts: { agent?: string }) => listRules(opts));

program
  .command('knowledge')
  .description('Search knowledge base')
  .requiredOption('-q, --query <text>', 'Search query')
  .option('-c, --category <name>', 'Category filter')
  .option('-l, --limit <n>', 'Max results', '20')
  .action((opts: any) => searchKnowledge(opts));

program
  .command('mcps')
  .description('List discovered MCPs')
  .option('-e, --enabled', 'Show only enabled MCPs')
  .action((opts: { enabled?: boolean }) => listMCPs(opts));

program
  .command('agents')
  .description('List agent profiles')
  .action(listAgents);

program
  .command('serve')
  .description('Start MCP server (stdio transport)')
  .action(startStdio);

program
  .command('serve:http')
  .description('Start MCP server (Streamable HTTP transport)')
  .option('-p, --port <n>', 'Port', config.server.port.toString())
  .option('-h, --host <addr>', 'Host', config.server.host)
  .action((opts: { port: string; host: string }) => startHTTP(Number(opts.port), opts.host));

program
  .command('doctor')
  .description('Validate installation and configuration')
  .action(runDoctor);

// ─── Install wizard (interactive MCP selection) ──────────────────────────────

async function installMCPs(): Promise<void> {
  const discovery = new MCPDiscovery({
    registryFile: path.resolve(config.mcps.registryFile),
    keyhunterRegistryFile: path.resolve(config.mcps.keyhunterRegistryFile),
    enabledFile: path.resolve(config.mcps.enabledFile),
  });
  await discovery.discover();
  const all = discovery.getAll();

  if (all.length === 0) {
    console.log(picocolors.yellow('  No MCPs discovered. Run `meumcp discover` + `npm run keyhunter` first.'));
    return;
  }

  const existing = new Set<string>();
  try {
    const content = await fs.readFile(path.resolve(config.mcps.enabledFile), 'utf-8');
    for (const id of JSON.parse(content) as string[]) existing.add(id);
  } catch {
    // no enabled list yet
  }

  const choices = all.map((mcp: any) => {
    const parts = mcp.id.split(':');
    const repo = parts[parts.length - 1] || mcp.id;
    const lic = mcp.license || 'unknown';
    const stars = typeof mcp.stars === 'number' ? mcp.stars : 0;
    const desc = mcp.description || mcp.metadata?.summary || '';
    return {
      title: `${repo} ${desc ? `— ${desc.slice(0, 60)}` : ''}`.trim(),
      description: `ID: ${mcp.id} | License: ${lic} | Stars: ${stars} | Status: ${mcp.status || 'n/a'}`,
      value: mcp.id,
      selected: existing.has(mcp.id),
    };
  });

  const resp = await prompts({
    type: 'multiselect',
    name: 'ids',
    message: 'Select MCPs to enable (space = toggle, enter = confirm):',
    instructions: '↑/↓ toggle, Space to mark, Enter to confirm',
    choices,
  });

  const toEnable = resp.ids || [];
  await fs.mkdir(path.dirname(path.resolve(config.mcps.enabledFile)), { recursive: true });
  await fs.writeFile(path.resolve(config.mcps.enabledFile), JSON.stringify(toEnable, null, 2), 'utf-8');
  console.log(picocolors.green(`  ${toEnable.length} MCPs enabled. Written to ${config.mcps.enabledFile}`));
}

program
  .command('install')
  .description('Install / activate MCPs via interactive selection (wizard)')
  .action(installMCPs);

program.parseAsync(process.argv);
