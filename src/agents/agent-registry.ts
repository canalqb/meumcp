/**
 * Agent Registry
 * Gerencia perfis de agentes e suas capacidades.
 */
import { promises as fs } from 'fs';
import * as path from 'path';
import { logger } from '../core/logger';
import type { AgentProfile, Provenance } from '../core/types';

export class AgentRegistry {
  private registryFile: string;
  private profilesDir: string;
  private profiles: Map<string, AgentProfile> = new Map();

  constructor(dirs: { registryFile: string; profilesDir: string }) {
    this.registryFile = dirs.registryFile;
    this.profilesDir = dirs.profilesDir;
  }

  async load(): Promise<void> {
    // Load profiles from directory
    try {
      const files = await this.walkDir(this.profilesDir);
      for (const file of files) {
        if (!file.endsWith('.yaml') && !file.endsWith('.yml') && !file.endsWith('.json')) continue;
        await this.loadProfile(file);
      }
    } catch {
      logger.warn(`Agent profiles directory not found: ${this.profilesDir}`);
    }

    // Load agent registry
    try {
      const content = await fs.readFile(this.registryFile, 'utf-8');
      const data = JSON.parse(content);
      if (Array.isArray(data)) {
        for (const item of data) {
          if (!this.profiles.has(item.id)) {
            this.profiles.set(item.id, item as AgentProfile);
          }
        }
      }
    } catch {
      logger.warn(`Agent registry file not found: ${this.registryFile}`);
    }

    logger.info({ count: this.profiles.size }, 'Agent profiles loaded');
  }

  private async walkDir(dir: string): Promise<string[]> {
    const entries = await fs.readdir(dir, { withFileTypes: true });
    const files: string[] = [];
    for (const entry of entries) {
      const full = path.join(dir, entry.name);
      if (entry.isDirectory()) {
        files.push(...(await this.walkDir(full)));
      } else {
        files.push(full);
      }
    }
    return files;
  }

  private async loadProfile(file: string): Promise<void> {
    const content = await fs.readFile(file, 'utf-8');
    let data: Record<string, unknown>;

    if (file.endsWith('.json')) {
      data = JSON.parse(content);
    } else {
      const yaml = require('js-yaml');
      data = yaml.load(content) as Record<string, unknown>;
    }

    const profile = data as unknown as AgentProfile;
    if (profile.id) {
      this.profiles.set(profile.id, profile);
      logger.debug(`Loaded agent profile: ${profile.id}`);
    }
  }

  get(id: string): AgentProfile | undefined {
    return this.profiles.get(id);
  }

  getAll(): AgentProfile[] {
    return Array.from(this.profiles.values());
  }

  /**
   * Identify an agent from request context.
   * Returns the best matching profile or a default.
   */
  identify(agentId?: string): AgentProfile {
    if (agentId && this.profiles.has(agentId)) {
      return this.profiles.get(agentId)!;
    }

    // Return default profile
    const defaultProfile: AgentProfile = {
      id: 'default',
      name: 'Default Agent',
      version: '1.0.0',
      capabilities: ['knowledge', 'rules'],
      knowledge: { required: [], forbidden: [] },
      rules: { required: [], forbidden: [] },
      tools: { allow: [], deny: [] },
      provenance: {
        source: 'system',
        sourceType: 'manual',
        version: '1.0.0',
        createdAt: new Date(),
        updatedAt: new Date(),
        priority: 0,
        scope: 'global',
        authority: 'canonical',
        status: 'active',
        hash: 'default',
      },
    };
    return defaultProfile;
  }
}
