/**
 * Validation script for MCP server over Streamable HTTP transport.
 */
const { Client } = require('@modelcontextprotocol/sdk/client/index.js');
const { StreamableHTTPClientTransport } = require('@modelcontextprotocol/sdk/client/streamableHttp.js');

async function testHTTP() {
  const transport = new StreamableHTTPClientTransport(
    new URL('http://localhost:8080/mcp')
  );
  const client = new Client({
    name: 'meumcp-validator',
    version: '1.0.0',
  }, {
    capabilities: { tools: {} },
  });

  await client.connect(transport);
  console.log('Connected via Streamable HTTP');

  const tools = await client.listTools();
  console.log('Tools:', tools.tools.map(t => t.name).join(', '));

  const sysResult = await client.callTool({
    name: 'get_system',
    arguments: {},
  });
  console.log('System:', JSON.parse(sysResult.content[0].text).server);

  const searchResult = await client.callTool({
    name: 'search_knowledge',
    arguments: { query: 'YouTube' },
  });
  const searchData = JSON.parse(searchResult.content[0].text);
  console.log('Search result count:', searchData.count);

  await client.close();
  console.log('HTTP transport validation: PASSED');
}

testHTTP().catch(err => {
  console.error('HTTP validation failed:', err.message);
  process.exit(1);
});
