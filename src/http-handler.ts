/**
 * HTTP Handler (Streamable HTTP transport)
 * Provides a Streamable HTTP endpoint for the MCP server.
 */
import Fastify, { FastifyInstance } from 'fastify';
import { logger } from './core/logger';

export async function createMCPHandler(): Promise<FastifyInstance> {
  const app = Fastify({
    logger: process.env.NODE_ENV === 'development' ? { level: 'debug' } : false,
  });

  // Health check
  app.get('/health', async () => ({
    status: 'ok',
    timestamp: new Date().toISOString(),
    server: 'meumcp',
    version: '1.0.0',
  }));

  // MCP Streamable HTTP endpoint
  app.post('/mcp', async (request, reply) => {
    const body = request.body as Record<string, unknown>;
    // The actual MCP protocol handling is done in server.ts
    // This endpoint is for health/transport setup
    return reply.send({
      jsonrpc: '2.0',
      id: (body?.id as string | number) || 0,
      result: 'meumcp HTTP transport ready',
    });
  });

  // Initialize endpoint
  app.post('/initialize', async (request, reply) => {
    const body = request.body as Record<string, unknown>;
    logger.info({ body }, 'MCP client initialized');

    return reply.send({
      jsonrpc: '2.0',
      id: body?.id || 0,
      result: {
        protocolVersion: '2024-11-05',
        capabilities: {
          tools: {},
          resources: {},
          prompts: {},
        },
        serverInfo: {
          name: 'meumcp',
          version: '1.0.0',
        },
      },
    });
  });

  return app;
}

export async function startHTTPServer(port: number, host: string): Promise<FastifyInstance> {
  const app = await createMCPHandler();

  await app.listen({ port, host });
  logger.info(`meumcp HTTP server listening on http://${host}:${port}`);

  return app;
}
