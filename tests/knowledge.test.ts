/**
 * Tests for KnowledgeManager
 */
import { KnowledgeManager } from '../src/knowledge/knowledge-manager';
import { promises as fs } from 'fs';
import * as path from 'path';
import * as os from 'os';

describe('KnowledgeManager', () => {
  let tmpDir: string;
  let km: KnowledgeManager;

  beforeEach(async () => {
    tmpDir = await fs.mkdtemp(path.join(os.tmpdir(), 'meumcp-test-'));
    km = new KnowledgeManager({
      canonicalDir: path.join(tmpDir, 'canonical'),
      importedDir: path.join(tmpDir, 'imported'),
      indexDir: path.join(tmpDir, 'index'),
    });
  });

  afterEach(async () => {
    await fs.rm(tmpDir, { recursive: true, force: true });
  });

  it('should load knowledge from canonical and imported dirs', async () => {
    const canonicalDir = path.join(tmpDir, 'canonical');
    await fs.mkdir(canonicalDir, { recursive: true });
    await fs.writeFile(
      path.join(canonicalDir, 'entry1.md'),
      '---\ntitle: Entry 1\ncategory: tech\n---\nThis is about YouTube automation.',
      'utf-8',
    );

    const importedDir = path.join(tmpDir, 'imported');
    await fs.mkdir(importedDir, { recursive: true });
    await fs.writeFile(
      path.join(importedDir, 'entry2.md'),
      '---\ntitle: Entry 2\ncategory: finance\n---\nCrypto airdrop guide for beginners.',
      'utf-8',
    );

    await km.load();
    expect(km.getAll().length).toBe(2);
  });

  it('should search knowledge by query', async () => {
    const canonicalDir = path.join(tmpDir, 'canonical');
    await fs.mkdir(canonicalDir, { recursive: true });
    await fs.writeFile(
      path.join(canonicalDir, 'yt1.md'),
      '---\ntitle: YouTube Guide\ntags: [youtube, seo]\n---\nHow to grow on YouTube.',
      'utf-8',
    );
    await fs.writeFile(
      path.join(canonicalDir, 'yt2.md'),
      '---\ntitle: Airdrop Guide\ntags: [crypto]\n---\nHow to claim airdrops.',
      'utf-8',
    );

    await km.load();
    const results = km.search('youtube');
    expect(results.length).toBe(1);
    expect(results[0].title).toBe('YouTube Guide');
  });

  it('should return empty when no matches', async () => {
    await km.load();
    const results = km.search('nonexistent-term-xyz');
    expect(results.length).toBe(0);
  });
});
