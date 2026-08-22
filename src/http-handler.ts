/**
 * HTTP Handler (Streamable HTTP transport)
 * Provides a Streamable HTTP endpoint for the MCP server.
 *
 * Padrão session-aware: cada cliente obtem uma sessão via initialize handshake.
 * O session ID é retornado no header mcp-session-id e usado em requests subsequentes.
 *
 * Suporte OAuth 2.0 para integração com Claude.ai
 */
import * as http from 'http';
import { Server as HttpServer, IncomingMessage, ServerResponse } from 'http';
import { StreamableHTTPServerTransport } from '@modelcontextprotocol/sdk/server/streamableHttp.js';
import { logger } from './core/logger.js';
import type { Server } from '@modelcontextprotocol/sdk/server/index.js';

// Session management: map session ID -> StreamableHTTPServerTransport
const transports = new Map<string, StreamableHTTPServerTransport>();

// Simple session ID generator
function generateSessionId(): string {
  return 'session-' + Date.now() + '-' + Math.random().toString(36).substring(2, 11);
}

// OAuth Protected Resource Metadata
const protectedResourceMetadata = {
  resource: process.env.MCP_SERVER_URL || 'https://claude.ai/mcp/meumcp',
  authorization_servers: [
    'https://auth.claude.ai'
  ],
  scopes_supported: ['read', 'write', 'tools'],
  bearer_methods_supported: ['header'],
  resource_signing_alg_values_supported: ['RS256', 'ES256']
};

export async function setupHTTPServer(mcpServer: Server): Promise<HttpServer> {
  const httpServer = http.createServer(async (req: IncomingMessage, res: ServerResponse) => {
    const url = req.url || '/mcp';

    // Health check
    if (url === '/health' && req.method === 'GET') {
      res.writeHead(200, { 'Content-Type': 'application/json' });
      res.end(JSON.stringify({
        status: 'ok',
        timestamp: new Date().toISOString(),
        server: 'meumcp',
        version: '1.0.0',
        authenticated: false
      }));
      return;
    }

    // OAuth Protected Resource Metadata - Claude.ai discovery
    if (req.method === 'GET' && (url === '/.well-known/oauth-protected-resource' || url === '/.well-known/oauth-protected-resource/')) {
      res.writeHead(200, {
        'Content-Type': 'application/json',
        'Cache-Control': 'no-store'
      });
      res.end(JSON.stringify(protectedResourceMetadata, null, 2));
      return;
    }

    // OAuth Protected Resource Metadata (without trailing slash)
    if (req.method === 'GET' && url === '/.well-known/oauth-protected-resource') {
      res.writeHead(200, {
        'Content-Type': 'application/json',
        'Cache-Control': 'no-store'
      });
      res.end(JSON.stringify(protectedResourceMetadata, null, 2));
      return;
    }

    // MCP endpoint
    if (url === '/mcp') {
      const sessionId = req.headers['mcp-session-id'] as string | undefined;

      // Get existing transport by session ID, or create new one
      let transport: StreamableHTTPServerTransport;
      if (sessionId && transports.has(sessionId)) {
        transport = transports.get(sessionId)!;
      } else {
        transport = new StreamableHTTPServerTransport({ sessionIdGenerator: generateSessionId });
        transports.set(transport.sessionId!, transport);
        await mcpServer.connect(transport);
        logger.info({ sessionId: transport.sessionId }, 'New MCP HTTP session created');
      }

      // Collect body
      let body = '';
      req.on('data', (chunk) => { body += chunk; });
      req.on('end', async () => {
        const parsedBody = body ? JSON.parse(body) : undefined;
        try {
          await transport.handleRequest(req, res, parsedBody);
        } catch (err) {
          logger.error({ err, body: parsedBody }, 'Error handling MCP HTTP request');
          if (!res.writableEnded) {
            res.writeHead(500, { 'Content-Type': 'application/json' });
            res.end(JSON.stringify({
              jsonrpc: '2.0',
              id: null,
              error: { code: -32603, message: 'Internal error' },
            }));
          }
        }
      });
      return;
    }

    // 404
    res.writeHead(404, { 'Content-Type': 'application/json' });
    res.end(JSON.stringify({ error: 'Not found' }));
  });

  // Clean up transports on server close
  httpServer.on('close', () => {
    transports.forEach((t) => t.close());
    transports.clear();
  });

  logger.info('MCP HTTP transport configured on /mcp (session-aware, OAuth enabled)');
  return httpServer;
}

export async function startHTTPServer(port: number, host: string, mcpServer: Server): Promise<HttpServer> {
  const httpServer = await setupHTTPServer(mcpServer);
  httpServer.listen(port, host);
  logger.info(`meumcp HTTP server listening on http://${host}:${port}`);
  return httpServer;
}