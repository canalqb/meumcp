export interface MCPServerInfo {
  id: string;
  name: string;
  type: 'local' | 'url' | 'github' | 'filesystem';
  location: string;
  toolsCount: number;
  status: 'active' | 'inactive';
  description?: string;
}

export class MCPDiscovery {
  private knownServers: MCPServerInfo[] = [
    {
      id: 'mcp_filesystem',
      name: 'FileSystem MCP Server',
      type: 'local',
      location: '/data/data/com.termux/files/home/.opencode/mcp/filesystem',
      toolsCount: 5,
      status: 'active',
      description: 'Acesso direto e seguro ao sistema de arquivos'
    },
    {
      id: 'mcp_prompts',
      name: 'Prompts & Templates MCP',
      type: 'local',
      location: '/root/boris-mcp.json',
      toolsCount: 3,
      status: 'active',
      description: 'Gerenciador de templates de sistema para Boris & Mini'
    },
    {
      id: 'mcp_vtube_studio',
      name: 'VTube Studio Plugin MCP Server',
      type: 'github',
      location: 'https://github.com/hkopenai/vtube-studio-plugin-mcp-server',
      toolsCount: 8,
      status: 'active',
      description: 'Controle de avatares VTuber (movimentos, expressões, fala)'
    },
    {
      id: 'mcp_android_remote',
      name: 'Android Remote Control MCP',
      type: 'github',
      location: 'https://github.com/danielealbano/android-remote-control-mcp',
      toolsCount: 12,
      status: 'active',
      description: 'Controle remoto total do dispositivo Android via comandos de voz/IA'
    },
    {
      id: 'mcp_claude_controller',
      name: 'Claude Code MCP Controller',
      type: 'github',
      location: 'https://github.com/mostafa-drz/claude-code-mcp-controller',
      toolsCount: 7,
      status: 'active',
      description: 'Orquestração avançada de agentes e execução de código'
    }
  ];

  public async discoverLocal(): Promise<MCPServerInfo[]> {
    return this.knownServers.filter(s => s.type === 'local');
  }

  public async registerFromUrl(url: string, description?: string): Promise<MCPServerInfo> {
    const newServer: MCPServerInfo = {
      id: `mcp_url_${Date.now()}`,
      name: `MCP Server (${new URL(url).hostname})`,
      type: 'url',
      location: url,
      toolsCount: 4,
      status: 'active',
      description: description || 'Servidor MCP externo cadastrado por URL'
    };
    this.knownServers.push(newServer);
    return newServer;
  }

  public async registerFromGithub(repo: string, description?: string): Promise<MCPServerInfo> {
    const newServer: MCPServerInfo = {
      id: `mcp_gh_${Date.now()}`,
      name: `GitHub MCP (${repo})`,
      type: 'github',
      location: `https://github.com/${repo}`,
      toolsCount: 6,
      status: 'active',
      description: description || 'Servidor MCP importado do GitHub'
    };
    this.knownServers.push(newServer);
    return newServer;
  }

  public getAllDiscovered(): readonly MCPServerInfo[] {
    return this.knownServers;
  }
}
