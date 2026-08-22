/**
 * Connectors Plugin
 * Conectores para Claude, ChatGPT, GitHub Copilot e outros agentes LLM
 */

export interface MCPConnector {
  id: string;
  name: string;
  description: string;
  transport: 'stdio' | 'http';
  executable?: string;
  args?: string[];
  env?: Record<string, string>;
  port?: number;
  host?: string;
}

export interface ConnectorConfig {
  claude?: MCPConnector;
  chatgpt?: MCPConnector;
  copilot?: MCPConnector;
  hermes?: MCPConnector;
  openai?: MCPConnector;
  anthropic?: MCPConnector;
}

const DEFAULT_CONNECTORS: ConnectorConfig = {
  claude: {
    id: 'claude-desktop',
    name: 'Claude Desktop',
    description: 'Conexão com Claude Desktop (stdio transport)',
    transport: 'stdio',
    executable: 'node',
    args: ['./dist/server.js'],
    env: { MEUMCP_CLI: 'true' },
  },
  chatgpt: {
    id: 'chatgpt-u',
    name: 'ChatGPT U',
    description: 'Conexão com ChatGPT via API',
    transport: 'http',
    port: 8765,
    host: '127.0.0.1',
  },
  copilot: {
    id: 'github-copilot',
    name: 'GitHub Copilot',
    description: 'Conexão via GitHub CLI (gh copilot)',
    transport: 'stdio',
    executable: 'gh',
    args: ['copilot', 'install'],
  },
  hermes: {
    id: 'hermes',
    name: 'Hermes Agent',
    description: 'Conexão com Hermes Agent via stdio',
    transport: 'stdio',
    executable: 'hermes',
  },
  openai: {
    id: 'openai-chat',
    name: 'OpenAI Chat',
    description: 'Conexão com OpenAI via API',
    transport: 'http',
    port: 8766,
    host: '127.0.0.1',
  },
  anthropic: {
    id: 'anthropic-claude',
    name: 'Anthropic Claude',
    description: 'Conexão direta com Anthropic via API',
    transport: 'http',
    port: 8767,
    host: '127.0.0.1',
  },
};

/**
 * GitHub OAuth Integration
 * Para conexão com recursos do GitHub (issues, PRs, releases)
 */
export interface GitHubOAuthConfig {
  clientId: string;
  clientSecret: string;
  token: string;
  owner: string;
  repo: string;
}

export const getGitHubOAuthConfig = (env: Record<string, string | undefined>): GitHubOAuthConfig | null => {
  if (!env.GITHUB_TOKEN) return null;
  
  return {
    clientId: env.GITHUB_CLIENT_ID || '',
    clientSecret: env.GITHUB_CLIENT_SECRET || '',
    token: env.GITHUB_TOKEN!,
    owner: env.GITHUB_OWNER || 'canalqb',
    repo: env.GITHUB_REPO || 'meumcp',
  };
};

export { DEFAULT_CONNECTORS };