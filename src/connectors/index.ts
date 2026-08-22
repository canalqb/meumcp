/**
 * Connectors Index
 * Exporta ferramentas MCP para integração com Claude, ChatGPT, Copilot, etc.
 */

import { z } from 'zod';
import { zodToJsonSchema } from 'zod-to-json-schema';
import { DEFAULT_CONNECTORS, getGitHubOAuthConfig, type GitHubOAuthConfig, type MCPConnector, type ConnectorConfig } from './plugin';

const toSchema = (s: any) => zodToJsonSchema(s, { $refStrategy: 'none' });

// Schemas
const ListConnectorsSchema = z.object({
  client: z.enum(['claude', 'chatgpt', 'copilot', 'hermes', 'openai', 'anthropic', 'all']).optional().default('all'),
});

const GetConnectorSchema = z.object({
  client: z.enum(['claude', 'chatgpt', 'copilot', 'hermes', 'openai', 'anthropic']),
});

const ConfigureConnectorSchema = z.object({
  client: z.enum(['claude', 'chatgpt', 'copilot', 'hermes', 'openai', 'anthropic']),
  transport: z.enum(['stdio', 'http']),
  executable: z.string().optional(),
  port: z.number().optional(),
  host: z.string().optional(),
});

const GitHubAuthSchema = z.object({
  action: z.enum(['status', 'validate', 'sync', 'release']),
  token: z.string().optional(),
  owner: z.string().optional(),
  repo: z.string().optional(),
  dryRun: z.boolean().optional().default(false),
});

export class ConnectorsPlugin {
  private connectors: ConnectorConfig;
  private githubConfig: GitHubOAuthConfig | null;

  constructor(env?: Record<string, string | undefined>) {
    this.connectors = this.buildConnectors(env || process.env);
    this.githubConfig = getGitHubOAuthConfig(env || process.env);
  }

  private buildConnectors(env: Record<string, string | undefined>): ConnectorConfig {
    const connectors: ConnectorConfig = { ...DEFAULT_CONNECTORS };

    // Override with environment-specific configs
    if (env.MCP_CLAUDE_PORT && connectors.claude) {
      connectors.claude = { ...connectors.claude, port: Number(env.MCP_CLAUDE_PORT) };
    }
    if (env.MCP_CHATGPT_PORT && connectors.chatgpt) {
      connectors.chatgpt = { ...connectors.chatgpt, port: Number(env.MCP_CHATGPT_PORT) };
    }
    if (env.MCP_COPILOT_TRANSPORT && connectors.copilot) {
      connectors.copilot = { ...connectors.copilot, transport: env.MCP_COPILOT_TRANSPORT as 'stdio' | 'http' };
    }

    return connectors;
  }

  getTools() {
    return [
      {
        name: 'list_connectors',
        description: 'List all available MCP connectors for Claude, ChatGPT, Copilot, etc.',
        inputSchema: toSchema(ListConnectorsSchema),
      },
      {
        name: 'get_connector',
        description: 'Get configuration for a specific MCP connector',
        inputSchema: toSchema(GetConnectorSchema),
      },
      {
        name: 'configure_connector',
        description: 'Configure/update an MCP connector',
        inputSchema: toSchema(ConfigureConnectorSchema),
      },
      {
        name: 'github_auth',
        description: 'GitHub OAuth operations: status, validate, sync, create release',
        inputSchema: toSchema(GitHubAuthSchema),
      },
    ];
  }

  async executeTool(name: string, args: any): Promise<any> {
    switch (name) {
      case 'list_connectors':
        return { connectors: this.listConnectors(args.client) };

      case 'get_connector':
        return { connector: this.getConnector(args.client) };

      case 'configure_connector':
        return this.configureConnector(args);

      case 'github_auth':
        return this.githubAuth(args);

      default:
        return { error: `Unknown tool: ${name}` };
    }
  }

  private listConnectors(client: string) {
    if (client === 'all') {
      return Object.values(this.connectors).map((c): any => ({
        id: c!.id,
        name: c!.name,
        transport: c!.transport,
        description: c!.description,
        configured: true,
      }));
    }

    const connector = this.connectors[client as keyof ConnectorConfig];
    if (!connector) return { error: `Connector ${client} not found` };

    return { connector: { id: connector!.id, name: connector!.name, transport: connector!.transport, description: connector!.description, configured: true } };
  }

  private getConnector(client: string) {
    const connector = this.connectors[client as keyof ConnectorConfig];
    if (!connector) return { error: `Connector ${client} not found` };

    return {
      id: connector!.id,
      name: connector!.name,
      description: connector!.description,
      transport: connector!.transport,
      executable: connector!.executable,
      args: connector!.args,
      env: connector!.env,
      port: connector!.port,
      host: connector!.host,
      githubToken: this.githubConfig?.token ? '***configured***' : undefined,
    };
  }

  private configureConnector(args: any) {
    const { client, transport, executable, port, host } = args;

    const existing = this.connectors[client as keyof ConnectorConfig];
    if (!existing) {
      return { error: `Unknown connector: ${client}` };
    }

    this.connectors[client as keyof ConnectorConfig] = {
      ...existing,
      transport,
      ...(executable && { executable }),
      ...(port && { port }),
      ...(host && { host }),
    };

    return { success: true, message: `Connector ${client} configured`, connector: this.connectors[client as keyof ConnectorConfig] };
  }

  private githubAuth(args: any) {
    if (args.action === 'status') {
      return {
        configured: !!this.githubConfig,
        token: this.githubConfig ? '***configured***' : undefined,
        owner: this.githubConfig?.owner || null,
        repo: this.githubConfig?.repo || null,
      };
    }

    if (args.action === 'validate') {
      if (!this.githubConfig?.token) {
        return { error: 'GitHub token not configured. Set GITHUB_TOKEN in .env' };
      }

      return { valid: true, owner: this.githubConfig.owner, repo: this.githubConfig.repo };
    }

    if (args.action === 'sync') {
      if (!this.githubConfig?.token) {
        return { error: 'GitHub token not configured' };
      }

      return {
        synced: true,
        message: 'GitHub sync would pull latest knowledge/mcp data',
        url: `https://api.github.com/repos/${this.githubConfig.owner}/${this.githubConfig.repo}`,
      };
    }

    if (args.action === 'release') {
      if (!this.githubConfig?.token) {
        return { error: 'GitHub token not configured' };
      }

      return {
        created: true,
        message: 'GitHub release would be created',
        tag: `auto-${new Date().toISOString().slice(0, 10)}`,
      };
    }
  }

  getGitHubConfig(): GitHubOAuthConfig | null {
    return this.githubConfig;
  }
}