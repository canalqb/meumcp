/**
 * HTTP Handler (Streamable HTTP transport)
 * Provides a Streamable HTTP endpoint for the MCP server.
 *
 * Padrão session-aware: cada cliente obtem uma sessão via initialize handshake.
 * O session ID é retornado no header mcp-session-id e usado em requests subsequentes.
 */
import * as http from 'http';
import { Server as HttpServer, IncomingMessage, ServerResponse } from 'http';
import { StreamableHTTPServerTransport } from '@modelcontextprotocol/sdk/server/streamableHttp.js';
import { logger } from './core/logger';
import type { Server } from '@modelcontextprotocol/sdk/server/index.js';

// Session management: map session ID -> StreamableHTTPServerTransport
const transports = new Map<string, StreamableHTTPServerTransport>();

// Simple session ID generator
function generateSessionId(): string {
  return 'session-' + Date.now() + '-' + Math.random().toString(36).substring(2, 11);
}

export async function setupHTTPServer(mcpServer: Server): Promise<HttpServer> {
  const httpServer = http.createServer(async (req: IncomingMessage, res: ServerResponse) => {
    // Health check
    if (req.url === '/health' && req.method === 'GET') {
      res.writeHead(200, { 'Content-Type': 'application/json' });
      res.end(JSON.stringify({
        status: 'ok',
        timestamp: new Date().toISOString(),
        server: 'meumcp',
        version: '1.0.0',
      }));
      return;
    }

    // MCP endpoint
    if (req.url === '/mcp') {
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

  logger.info('MCP HTTP transport configured on /mcp (session-aware)');
  return httpServer;
}

export async function startHTTPServer(port: number, host: string, mcpServer: Server): Promise<HttpServer> {
  const httpServer = await setupHTTPServer(mcpServer);
  httpServer.listen(port, host);
  logger.info(`meumcp HTTP server listening on http://${host}:${port}`);
  return httpServer;
}
