/**
 * Knowledge Manager
 * Gerencia conhecimento canônico, importado e gerado.
 * Fonte única de verdade para fatos descritivos.
 */
import { promises as fs } from 'fs';
import path = require('path');
const matter = require('gray-matter');
import { logger } from '../core/logger';
import type { KnowledgeEntry, Provenance } from '../core/types';

export class KnowledgeManager {
  private canonicalDir: string;
  private importedDir: string;
  private indexDir: string;
  private entries: Map<string, KnowledgeEntry> = new Map();
  private index: Map<string, string[]> = new Map(); // tag -> entry ids

  constructor(dirs: {
    canonicalDir: string;
    importedDir: string;
    indexDir: string;
  }) {
    this.canonicalDir = dirs.canonicalDir;
    this.importedDir = dirs.importedDir;
    this.indexDir = dirs.indexDir;
  }

  async load(): Promise<void> {
    await this.loadDirectory(this.canonicalDir, 'canonical');
    await this.loadDirectory(this.importedDir, 'imported');
    await this.buildIndex();
    logger.info({ count: this.entries.size }, 'Knowledge entries loaded');
  }

  private async loadDirectory(dir: string, authority: string): Promise<void> {
    let files: string[];
    try {
      files = await this.walkDir(dir);
    } catch {
      logger.warn(`Directory not found: ${dir}`);
      return;
    }

    for (const file of files) {
      if (!file.endsWith('.md')) continue;
      await this.loadFile(file, authority);
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

  private async loadFile(file: string, authority: string): Promise<void> {
    const content = await fs.readFile(file, 'utf-8');
    const parsed = matter(content);
    const data = parsed.data as Record<string, unknown>;
    const body = parsed.content;

    const id = (data.id as string) || path.basename(file, '.md');
    const relPath = path.relative(this.canonicalDir, file);

    const provenance: Provenance = {
      source: relPath,
      sourceType: 'local-file',
      version: (data.version as string) || '1.0.0',
      createdAt: data.createdAt ? new Date(data.createdAt as string) : new Date(),
      updatedAt: data.updatedAt ? new Date(data.updatedAt as string) : new Date(),
      priority: (data.priority as number) || (authority === 'canonical' ? 100 : 50),
      scope: (data.scope as Provenance['scope']) || 'global',
      authority: authority as Provenance['authority'],
      status: 'active',
      hash: (data.hash as string) || this.hashContent(body),
      author: data.author as string | undefined,
    };

    const entry: KnowledgeEntry = {
      id,
      category: (data.category as string) || 'uncategorized',
      title: (data.title as string) || id,
      content: body || '',
      tags: (data.tags as string[]) || [],
      provenance,
      relationships: (data.relationships as string[]) || [],
      schema: data.schema as string | undefined,
    };

    // Canonical wins over imported
    const existing = this.entries.get(id);
    if (existing) {
      if (existing.provenance.authority === 'imported' && authority === 'canonical') {
        this.entries.set(id, entry);
        logger.info(`Replaced imported entry with canonical: ${id}`);
      } else if (existing.provenance.priority < provenance.priority) {
        this.entries.set(id, entry);
      }
    } else {
      this.entries.set(id, entry);
    }
  }

  private hashContent(content: string): string {
    const crypto = require('crypto');
    return crypto.createHash('sha256').update(content).digest('hex').slice(0, 16);
  }

  private async buildIndex(): Promise<void> {
    const entries = Array.from(this.entries.values());
    for (const entry of entries) {
      for (const tag of entry.tags) {
        if (!this.index.has(tag)) this.index.set(tag, []);
        this.index.get(tag)!.push(entry.id);
      }
      if (!this.index.has(entry.category)) this.index.set(entry.category, []);
      this.index.get(entry.category)!.push(entry.id);
    }

    await fs.mkdir(this.indexDir, { recursive: true });
    const indexData: Record<string, string[]> = {};
    const indexEntries = Array.from(this.index.entries());
    for (const [key, ids] of indexEntries) {
      indexData[key] = ids;
    }
    await fs.writeFile(
      path.join(this.indexDir, 'knowledge-index.json'),
      JSON.stringify(indexData, null, 2),
    );
  }

  search(query: string, opts?: { category?: string; tags?: string[] }): KnowledgeEntry[] {
    const results: KnowledgeEntry[] = [];
    const q = query.toLowerCase();

    const entries = Array.from(this.entries.values());
    for (const entry of entries) {
      const matchesText =
        entry.title.toLowerCase().includes(q) ||
        entry.content.toLowerCase().includes(q);
      if (!matchesText) continue;

      const matchesCategory = opts?.category ? entry.category === opts.category : true;
      if (!matchesCategory) continue;

      const matchesTags = opts?.tags
        ? opts.tags.some((t) => entry.tags.includes(t))
        : true;
      if (!matchesTags) continue;

      results.push(entry);
    }

    results.sort((a, b) => b.provenance.priority - a.provenance.priority);
    return results;
  }

  get(id: string): KnowledgeEntry | undefined {
    return this.entries.get(id);
  }

  getAll(): KnowledgeEntry[] {
    return Array.from(this.entries.values());
  }

  getSources(): Array<{ source: string; sourceType: string; count: number }> {
    const sourceMap = new Map<string, { sourceType: string; count: number }>();
    const entries = Array.from(this.entries.values());
    for (const entry of entries) {
      const key = entry.provenance.source;
      const existing = sourceMap.get(key) || { sourceType: entry.provenance.sourceType, count: 0 };
      existing.count++;
      sourceMap.set(key, existing);
    }
    const result: Array<{ source: string; sourceType: string; count: number }> = [];
    const sources = Array.from(sourceMap.entries());
    for (const [source, info] of sources) {
      result.push({ source, sourceType: info.sourceType, count: info.count });
    }
    return result;
  }
}
