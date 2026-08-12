/**
 * MCP Discovery Scanner
 * Encontra MCPs instalados/configurados na máquina.
 * IMPORTANTE: descoberta != confiança. Tudo passa por um pipeline de validação.
 */
import { promises as fs } from 'fs';
import * as path from 'path';
import { logger } from '../core/logger';
import type { MCPRecord } from '../core/types';

interface MCPDiscoveryResult {
  mcps: MCPRecord[];
  summary: {
    total: number;
    enabled: number;
    disabled: number;
    discovered: number;
    broken: number;
  };
}

export class MCPDiscovery {
  private registryFile: string;
  private enabledFile: string;
  private records: Map<string, MCPRecord> = new Map();

  constructor(dirs: { registryFile: string; enabledFile: string }) {
    this.registryFile = dirs.registryFile;
    this.enabledFile = dirs.enabledFile;
  }

  async discover(): Promise<MCPDiscoveryResult> {
    const sources: MCPRecord[][] = [
      await this.scanClaudeDesktop(),
      await this.scanCursor(),
      await this.scanLmStudio(),
      await this.scanGlobalNodeModules(),
      await this.scanHermesConfig(),
      await this.scanLocalConfig(),
    ];

    for (const source of sources) {
      for (const mcp of source) {
        const existing = this.records.get(mcp.id);
        if (existing) {
          existing.capabilities.tools = Array.from(
            new Set([...existing.capabilities.tools, ...mcp.capabilities.tools]),
          );
          existing.capabilities.resources = Array.from(
            new Set([...existing.capabilities.resources, ...mcp.capabilities.resources]),
          );
          existing.capabilities.prompts = Array.from(
            new Set([...existing.capabilities.prompts, ...mcp.capabilities.prompts]),
          );
          existing.updatedAt = new Date();
        } else {
          this.records.set(mcp.id, mcp);
        }
      }
    }

    await this.loadEnabledList();
    await this.saveRegistry();

    const allRecords = Array.from(this.records.values());
    const summary = {
      total: allRecords.length,
      enabled: allRecords.filter((m) => m.enabled).length,
      disabled: allRecords.filter((m) => !m.enabled && m.status === 'approved').length,
      discovered: allRecords.filter((m) => m.status === 'discovered').length,
      broken: allRecords.filter((m) => m.status === 'broken').length,
    };

    logger.info(summary, 'MCP discovery complete');
    return { mcps: allRecords, summary };
  }

  private async scanClaudeDesktop(): Promise<MCPRecord[]> {
    const records: MCPRecord[] = [];
    const configPaths = [
      path.join(process.env.HOME || '', '.config', 'claude', 'claude_desktop_config.json'),
      path.join(process.env.APPDATA || '', 'Claude', 'claude_desktop_config.json'),
    ];

    for (const configPath of configPaths) {
      try {
        const content = await fs.readFile(configPath, 'utf-8');
        const config = JSON.parse(content);
        const mcpServers = config.mcpServers || config.mcp_servers || {};

        const entries = Object.entries(mcpServers);
        for (const [name, server] of entries) {
          const s = server as { command?: string; args?: string[]; url?: string };
          records.push({
            id: `claude-desktop:${name}`,
            name,
            source: { type: 'config', command: s.command, url: s.url, path: configPath },
            status: 'discovered',
            enabled: true,
            trust: 'unknown',
            capabilities: { tools: [], resources: [], prompts: [] },
            metadata: { s },
            discoveredAt: new Date(),
            updatedAt: new Date(),
          });
        }
      } catch {
        // Not found
      }
    }
    return records;
  }

  private async scanCursor(): Promise<MCPRecord[]> {
    const records: MCPRecord[] = [];
    const configPaths = [
      path.join(process.env.HOME || '', '.cursor', 'mcp.json'),
      path.join(process.env.HOME || '', '.config', 'cursor', 'mcp.json'),
      path.join(process.cwd(), '.cursor', 'mcp.json'),
      path.join(process.cwd(), '.mcp.json'),
    ];

    for (const configPath of configPaths) {
      try {
        const content = await fs.readFile(configPath, 'utf-8');
        const config = JSON.parse(content);
        const mcpServers = config.mcpServers || {};

        const entries = Object.entries(mcpServers);
        for (const [name, server] of entries) {
          const s = server as { command?: string; args?: string[]; url?: string };
          records.push({
            id: `cursor:${name}`,
            name,
            source: { type: 'config', command: s.command, url: s.url, path: configPath },
            status: 'discovered',
            enabled: true,
            trust: 'unknown',
            capabilities: { tools: [], resources: [], prompts: [] },
            metadata: { s },
            discoveredAt: new Date(),
            updatedAt: new Date(),
          });
        }
      } catch {
        // Not found
      }
    }
    return records;
  }

  private async scanLmStudio(): Promise<MCPRecord[]> {
    const records: MCPRecord[] = [];
    const configPath = path.join(process.env.HOME || '', '.lmstudio', 'mcp.json');

    try {
      const content = await fs.readFile(configPath, 'utf-8');
      const config = JSON.parse(content);
      const mcpServers = config.mcpServers || {};

      const entries = Object.entries(mcpServers);
      for (const [name, server] of entries) {
        const s = server as { command?: string; args?: string[]; url?: string };
        records.push({
          id: `lmstudio:${name}`,
          name,
          source: { type: 'config', command: s.command, url: s.url, path: configPath },
          status: 'discovered',
          enabled: true,
          trust: 'unknown',
          capabilities: { tools: [], resources: [], prompts: [] },
          metadata: { s },
          discoveredAt: new Date(),
          updatedAt: new Date(),
        });
      }
    } catch {
      // Not found
    }
    return records;
  }

  private async scanGlobalNodeModules(): Promise<MCPRecord[]> {
    const records: MCPRecord[] = [];
    const knownMCPs = [
      { name: '@brightdata/mcp', package: '@brightdata/mcp' },
      { name: '@supabase/mcp-server-supabase', package: '@supabase/mcp-server-supabase' },
    ];

    for (const { name, package: pkg } of knownMCPs) {
      try {
        require.resolve(pkg);
        records.push({
          id: `npm:${name}`,
          name,
          source: { type: 'local', command: 'npx' },
          status: 'discovered',
          enabled: false,
          trust: 'low',
          capabilities: { tools: [], resources: [], prompts: [] },
          metadata: { package: pkg },
          discoveredAt: new Date(),
          updatedAt: new Date(),
        });
      } catch {
        // Not installed globally
      }
    }
    return records;
  }

  private async scanHermesConfig(): Promise<MCPRecord[]> {
    const records: MCPRecord[] = [];
    const configPaths = [
      path.join(process.env.HOME || '', '.hermes', 'config.yaml'),
      path.join(process.env.HOME || '', '.config', 'hermes', 'config.yaml'),
    ];

    const yaml = require('js-yaml');

    for (const configPath of configPaths) {
      try {
        const content = await fs.readFile(configPath, 'utf-8');
        const config = yaml.load(content) as Record<string, unknown>;
        const mcpConfig = (config.mcp || config.mcps) as Record<string, unknown> | undefined;

        if (mcpConfig && typeof mcpConfig === 'object') {
          const entries = Object.entries(mcpConfig);
          for (const [name, server] of entries) {
            const s = server as Record<string, unknown>;
            const command = s.command as string | undefined;
            const url = s.url as string | undefined;
            records.push({
              id: `hermes:${name}`,
              name,
              source: { type: 'config', command, url, path: configPath },
              status: 'discovered',
              enabled: true,
              trust: 'unknown',
              capabilities: { tools: [], resources: [], prompts: [] },
              metadata: { s },
              discoveredAt: new Date(),
              updatedAt: new Date(),
            });
          }
        }
      } catch {
        // Not found or invalid
      }
    }
    return records;
  }

  private async scanLocalConfig(): Promise<MCPRecord[]> {
    const records: MCPRecord[] = [];
    const localConfig = path.join(process.cwd(), 'mcps', 'local.json');

    try {
      const content = await fs.readFile(localConfig, 'utf-8');
      const config = JSON.parse(content) as { servers?: Array<Record<string, unknown>> };
      const servers = config.servers || [];
      for (const server of servers) {
        const s = server as Record<string, unknown>;
        const cmd = s.command as string | undefined;
        const url = s.url as string | undefined;
        const filePath = s.path as string | undefined;
        records.push({
          id: (s.id || s.name) as string,
          name: s.name as string,
          source: { type: 'local', command: cmd, url: url, path: filePath },
          status: 'discovered',
          enabled: s.enabled !== false,
          trust: 'unknown',
          capabilities: { tools: [], resources: [], prompts: [] },
          metadata: { s },
          discoveredAt: new Date(),
          updatedAt: new Date(),
        });
      }
    } catch {
      // Not found
    }
    return records;
  }

  private async loadEnabledList(): Promise<void> {
    try {
      const content = await fs.readFile(this.enabledFile, 'utf-8');
      const enabled = JSON.parse(content) as string[];
      for (const id of enabled) {
        const record = this.records.get(id);
        if (record) {
          record.enabled = true;
          record.status = 'enabled';
        }
      }
    } catch {
      // No enabled list
    }
  }

  private async saveRegistry(): Promise<void> {
    await fs.mkdir(path.dirname(this.registryFile), { recursive: true });
    const data = Array.from(this.records.values());
    await fs.writeFile(this.registryFile, JSON.stringify(data, null, 2));
  }

  getAll(): MCPRecord[] {
    return Array.from(this.records.values());
  }

  get(id: string): MCPRecord | undefined {
    return this.records.get(id);
  }

  getEnabled(): MCPRecord[] {
    return Array.from(this.records.values()).filter((m) => m.enabled);
  }
}

export type { MCPDiscoveryResult };
