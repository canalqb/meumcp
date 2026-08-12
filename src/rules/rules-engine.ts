/**
 * Rules Engine
 * Gerencia regras prescritivas (políticas), com hierarquia de precedência.
 *
 * Ordem de precedência: GLOBAL → ORGANIZATION → PROJECT → AGENT → TASK → TOOL
 */
import { promises as fs } from 'fs';
import path = require('path');
const matter = require('gray-matter');
import { logger } from '../core/logger';
import type { Rule, Provenance } from '../core/types';

export class RulesEngine {
  private rules: Map<string, Rule> = new Map();
  private readonly directories: string[];
  private conflictLog: Array<{ ruleId: string; conflicts: string[] }> = [];

  constructor(directories: string[]) {
    this.directories = directories;
  }

  async load(): Promise<void> {
    for (const dir of this.directories) {
      await this.loadDirectory(dir);
    }
    this.detectConflicts();
    logger.info({ count: this.rules.size }, 'Rules loaded');
  }

  private async loadDirectory(dir: string): Promise<void> {
    let files: string[];
    try {
      files = await this.walkDir(dir);
    } catch {
      logger.warn(`Rules directory not found: ${dir}`);
      return;
    }

    const authority = dir.includes('canonical') || dir.includes('global')
      ? 'canonical'
      : 'imported';

    for (const file of files) {
      if (!file.endsWith('.md')) continue;
      await this.loadRule(file, authority);
    }
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

  private parseScope(raw: unknown): { global: boolean; project: string[]; agent: string[] } {
    if (!raw || typeof raw === 'string') {
      return {
        global: raw === 'global',
        project: [],
        agent: [],
      };
    }
    const obj = raw as Record<string, unknown>;
    return {
      global: obj.global === true || obj.global === 'true',
      project: Array.isArray(obj.project) ? obj.project as string[] : [],
      agent: Array.isArray(obj.agent) ? obj.agent as string[] : [],
    };
  }

  private async loadRule(file: string, authority: string): Promise<void> {
    const content = await fs.readFile(file, 'utf-8');
    const parsed = matter(content);
    const data = parsed.data as Record<string, unknown>;
    const body = parsed.content;

    const id = (data.id as string) || path.basename(file, '.md');
    const relPath = path.relative(process.cwd(), file);

    const provenance: Provenance = {
      source: relPath,
      sourceType: 'local-file',
      version: (data.version as string) || '1.0.0',
      createdAt: data.createdAt ? new Date(data.createdAt as string) : new Date(),
      updatedAt: data.updatedAt ? new Date(data.updatedAt as string) : new Date(),
      priority: (data.priority as number) || 50,
      scope: typeof data.scope === 'string' ? (data.scope as Provenance['scope']) : 'global',
      authority: authority as Provenance['authority'],
      status: 'active',
      hash: this.hashContent(body),
      author: data.author as string | undefined,
    };

    // Priority hierarchy: GLOBAL (100) > ORG (80) > PROJECT (60) > AGENT (40) > TASK (20) > TOOL (10)
    const scopePriority = {
      global: 100,
      project: 60,
      agent: 40,
    };
    const basePriority = scopePriority[provenance.scope as keyof typeof scopePriority] || 50;
    provenance.priority = basePriority + (provenance.priority || 0);

    const rule: Rule = {
      id,
      category: (data.category as string) || 'uncategorized',
      title: (data.title as string) || id,
      description: (data.description as string) || '',
      content: body || '',
      priority: provenance.priority,
      scope: this.parseScope(data.scope),
      allowOverride: data.allowOverride !== false,
      enforced: data.enforced !== false,
      tags: (data.tags as string[]) || [],
      provenance,
    };

    const existing = this.rules.get(id);
    if (existing) {
      if (existing.provenance.priority < rule.provenance.priority) {
        this.rules.set(id, rule);
        logger.info(`Rule ${id} upgraded (priority ${existing.provenance.priority} -> ${rule.provenance.priority})`);
      }
    } else {
      this.rules.set(id, rule);
    }
  }

  private hashContent(content: string): string {
    const crypto = require('crypto');
    return crypto.createHash('sha256').update(content).digest('hex').slice(0, 16);
  }

  private detectConflicts(): void {
    const rules = Array.from(this.rules.values());
    for (const rule of rules) {
      const conflicts = rules
        .filter((r) => r.id !== rule.id && r.tags.some((t) => rule.tags.includes(t)))
        .map((r) => r.id);
      if (conflicts.length > 0) {
        this.conflictLog.push({ ruleId: rule.id, conflicts });
        logger.warn({ ruleId: rule.id, conflicts }, 'Conflict detected between rules');
      }
    }
  }

  /**
   * Resolva quais regras aplicam para um agente em uma tarefa.
   * Global rules sempre vêm primeiro (maior prioridade).
   */
  getRulesForAgent(
    agentId: string,
    opts?: { project?: string; taskId?: string; toolId?: string },
  ): Rule[] {
    const results: Rule[] = [];
    const allRules = Array.from(this.rules.values());

    for (const rule of allRules) {
      // Skip if rule is scoped to a specific agent not matching
      if (rule.scope.agent.length > 0 && !rule.scope.agent.includes(agentId)) continue;

      // Security rules with allowOverride=false cannot be relaxed
      if (rule.allowOverride === false) {
        results.push(rule);
        continue;
      }

      // Add applicable rules
      results.push(rule);
    }

    // Sort: higher priority first, canonical before imported
    results.sort((a, b) => {
      if (b.provenance.priority !== a.provenance.priority) {
        return b.provenance.priority - a.provenance.priority;
      }
      const authOrder = { canonical: 0, imported: 1, draft: 2 };
      return authOrder[a.provenance.authority] - authOrder[b.provenance.authority];
    });

    return results;
  }

  getAll(): Rule[] {
    return Array.from(this.rules.values());
  }

  get(id: string): Rule | undefined {
    return this.rules.get(id);
  }

  /**
   * Verifica se uma regra específica permite uma ação.
   */
  check(ruleId: string, action: string): { allowed: boolean; rule?: Rule; reason?: string } {
    const rule = this.rules.get(ruleId);
    if (!rule) {
      return { allowed: true, reason: `Rule ${ruleId} not found — default allow` };
    }

    const content = rule.content.toLowerCase();
    const actionLower = action.toLowerCase();

    if (content.includes('never') || content.includes('proibido') || content.includes('forbidden')) {
      if (content.includes(actionLower)) {
        return { allowed: false, rule, reason: 'Explicitly prohibited by rule' };
      }
    }

    return { allowed: true, rule };
  }

  getConflicts(): Array<{ ruleId: string; conflicts: string[] }> {
    return this.conflictLog;
  }
}
