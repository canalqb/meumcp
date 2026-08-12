/**
 * Tests for MCPDiscovery
 */
import { MCPDiscovery } from '../src/discovery/mcp-discovery';
import { promises as fs } from 'fs';
import * as path from 'path';
import * as os from 'os';
import * as process from 'process';

describe('MCPDiscovery', () => {
  let tmpDir: string;
  let originalCwd: string;

  beforeEach(async () => {
    tmpDir = await fs.mkdtemp(path.join(os.tmpdir(), 'meumcp-test-'));
    originalCwd = process.cwd();
    process.chdir(tmpDir);
  });

  afterEach(async () => {
    process.chdir(originalCwd);
    await fs.rm(tmpDir, { recursive: true, force: true });
  });

  it('should parse MCP config from local.json', async () => {
    const mcpsDir = path.join(tmpDir, 'mcps');
    await fs.mkdir(mcpsDir, { recursive: true });
    await fs.writeFile(
      path.join(mcpsDir, 'local.json'),
      JSON.stringify({
        servers: [
          {
            id: 'test-mcp',
            name: 'Test MCP',
            command: 'npx',
            url: undefined,
            path: undefined,
            enabled: true,
          },
        ],
      }),
      'utf-8',
    );

    const discovery = new MCPDiscovery({
      registryFile: path.join(tmpDir, 'registry.json'),
      keyhunterRegistryFile: path.join(tmpDir, 'keyhunter.json'),
      enabledFile: path.join(tmpDir, 'enabled.json'),
    });
    await discovery.discover();

    const all = discovery.getAll();
    expect(all.length).toBe(1);
    expect(all[0].name).toBe('Test MCP');
    expect(all[0].source.type).toBe('local');
  });

  it('should handle empty config gracefully', async () => {
    const mcpsDir = path.join(tmpDir, 'mcps');
    await fs.mkdir(mcpsDir, { recursive: true });
    await fs.writeFile(
      path.join(mcpsDir, 'local.json'),
      JSON.stringify({ servers: [] }),
      'utf-8',
    );

    const discovery = new MCPDiscovery({
      registryFile: path.join(tmpDir, 'registry.json'),
      keyhunterRegistryFile: path.join(tmpDir, 'keyhunter.json'),
      enabledFile: path.join(tmpDir, 'enabled.json'),
    });
    await discovery.discover();
    expect(discovery.getAll().length).toBe(0);
  });

  it('should save and load enabled MCPs', async () => {
    const mcpsDir = path.join(tmpDir, 'mcps');
    await fs.mkdir(mcpsDir, { recursive: true });
    await fs.writeFile(
      path.join(mcpsDir, 'local.json'),
      JSON.stringify({
        servers: [
          { id: 'mcp1', name: 'MCP One', command: 'node', enabled: false },
          { id: 'mcp2', name: 'MCP Two', command: 'node', enabled: false },
        ],
      }),
      'utf-8',
    );
    const enabledFile = path.join(tmpDir, 'enabled.json');
    await fs.writeFile(enabledFile, JSON.stringify(['mcp1']), 'utf-8');

    const discovery = new MCPDiscovery({
      registryFile: path.join(tmpDir, 'registry.json'),
      keyhunterRegistryFile: path.join(tmpDir, 'keyhunter.json'),
      enabledFile,
    });
    await discovery.discover();

    const enabled = discovery.getEnabled();
    expect(enabled.length).toBe(1);
    expect(enabled[0].name).toBe('MCP One');
    expect(enabled[0].enabled).toBe(true);
  });
});
