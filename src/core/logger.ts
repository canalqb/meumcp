/**
 * Logger centralizado usando pino.
 * @module logger
 */
const pino = require('pino');

// Read env vars at module load time
const isCLI = process.env.MEUMCP_CLI === 'true';
const isServer = !isCLI;
const resolvedLevel = isCLI
  ? 'silent'
  : (process.env.LOG_LEVEL || 'info');

export const logger = pino(
  {
    level: resolvedLevel,
    transport:
      process.env.NODE_ENV === 'development' && !isCLI
        ? { target: 'pino-pretty', options: { colorize: true } }
        : undefined,
    redact: ['token', 'password', 'secret', 'key', 'authorization'],
  },
  // SERVER: stderr dest (avoids corrupting MCP stdio stdout)
  // CLI: stdout (default, human-readable)
  isServer ? pino.destination({ dest: 2, sync: false }) : undefined,
);
