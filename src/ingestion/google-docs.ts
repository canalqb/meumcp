/**
 * Google Docs Ingestion
 * Carrega conteúdo do Google Doc de regras canônicas e normaliza para o formato meumcp.
 *
 * Usa a API pública de exportação (pub=True) quando o Doc for público.
 * Para docs privados, usa o token do service account ou OAuth.
 */
import { promises as fs } from 'fs';
import * as path from 'path';
import { logger } from '../core/logger';
import type { Provenance } from '../core/types';
const matter = require('gray-matter');

export interface GoogleDocSource {
  documentId: string;
  url: string;
  title?: string;
}

export class GoogleDocsIngest {
  private sourceUrl: string;
  private outputDir: string;
  private githubToken?: string;

  constructor(opts: {
    sourceUrl?: string;
    outputDir: string;
    githubToken?: string;
  }) {
    this.sourceUrl =
      opts.sourceUrl ||
      'https://docs.google.com/document/d/1sTsRoAEWrU-1ltOMmUWyQ-18DFTmYl0R5UZc-QnNtCs/edit';
    this.outputDir = opts.outputDir;
    this.githubToken = opts.githubToken;
  }

  /**
   * Extrai o document ID da URL do Google Doc.
   */
  getDocumentId(): string {
    const match = this.sourceUrl.match(/\/d\/([a-zA-Z0-9-_]+)/);
    if (!match) {
      throw new Error(`Could not extract document ID from URL: ${this.sourceUrl}`);
    }
    return match[1];
  }

  /**
   * Tenta carregar o conteúdo do Google Doc via exportação pública.
   * Se falhar, retorna conteúdo mínimo com instruções.
   */
  async ingest(): Promise<{ entries: number; outputPath: string }> {
    const docId = this.getDocumentId();
    const exportUrl = `https://docs.google.com/document/d/${docId}/export?format=markdown`;

    logger.info({ docId }, 'Ingesting Google Doc content');

    let content: string;
    try {
      // Use HTTPS fetch
      const response = await fetch(exportUrl, {
        headers: {
          'User-Agent': 'meumcp-agent/1.0',
          Authorization: this.githubToken ? `Bearer ${this.githubToken}` : '',
        },
      });
      if (!response.ok) {
        throw new Error(`HTTP ${response.status}`);
      }
      content = await response.text();
    } catch (err) {
      logger.warn(`Could not fetch Google Doc: ${(err as Error).message}`);
      logger.info('Creating stub content for manual review');
      content = `# Master Rules (Google Doc)

> **IMPORTANTE**: Este conteúdo foi extraído do Google Doc de regras canônicas.
> Documento: ${this.sourceUrl}
>
> Faça o download manualmente em: https://docs.google.com/document/d/${docId}/export?format=markdown
> e cole o conteúdo acima.

## Próximos passos
1. Baixe o conteúdo do Google Doc
2. Salve como \`master_rules.md\` nesta pasta
3. Execute \`meumcp ingest:rules\` para processar

## Estrutura esperada
- Regras organizadas por categoria
- Prioridades numéricas
- Metadados YAML frontmatter
`;
    }

    const now = new Date();
    const provenance: Provenance = {
      source: this.sourceUrl,
      sourceType: 'google-doc',
      version: '1.0.0',
      createdAt: now,
      updatedAt: now,
      priority: 100,
      scope: 'global',
      authority: 'canonical',
      status: 'active',
      hash: this.hashContent(content),
      author: '@CanalQb',
    };

    // Save raw content
    await fs.mkdir(this.outputDir, { recursive: true });
    const outputPath = path.join(this.outputDir, 'master_rules.md');
    await fs.writeFile(outputPath, content, 'utf-8');

    // Parse and extract individual rules if possible
    const rules = await this.parseRules(content, provenance);

    logger.info({ entries: rules.length }, 'Google Doc ingestion complete');
    return { entries: rules.length, outputPath };
  }

  /**
   * Parse content e extrai regras individuais.
   */
  private async parseRules(
    content: string,
    provenance: Provenance,
  ): Promise<string[]> {
    const rules: string[] = [];
    // Simple heuristic: split by headers that look like rules
    const lines = content.split('\n');
    let currentRule: string[] = [];
    let inRule = false;
    const rulePattern = /^(#{1,3}\s+\[?(\d+)[^\n]*|#+\s+[R-r]egras?)/;

    for (const line of lines) {
      if (rulePattern.test(line)) {
        if (inRule && currentRule.length > 0) {
          rules.push(currentRule.join('\n').trim());
        }
        currentRule = [line];
        inRule = true;
      } else if (inRule) {
        currentRule.push(line);
      }
    }
    if (inRule && currentRule.length > 0) {
      rules.push(currentRule.join('\n').trim());
    }

    return rules;
  }

  private hashContent(content: string): string {
    const crypto = require('crypto');
    return crypto.createHash('sha256').update(content).digest('hex').slice(0, 16);
  }

  /**
   * Cria um arquivo de regras canônicas a partir do conteúdo do Google Doc.
   */
  async createCanonicalRuleFile(
    content: string,
    category: string,
    filename: string,
  ): Promise<string> {
    const now = new Date();
    const hash = this.hashContent(content);

    const frontmatter = {
      id: filename.replace('.md', ''),
      category,
      title: filename.replace('.md', '').replace(/_/g, ' '),
      version: now.toISOString().split('T')[0],
      source: this.sourceUrl,
      sourceType: 'google-doc',
      priority: 100,
      scope: 'global',
      authority: 'canonical',
      createdAt: now.toISOString(),
      updatedAt: now.toISOString(),
      hash,
      author: '@CanalQb',
    };

    const fileContent = matter.stringify(content, frontmatter);
    const outputPath = path.join(this.outputDir, category, filename);
    await fs.mkdir(path.dirname(outputPath), { recursive: true });
    await fs.writeFile(outputPath, fileContent, 'utf-8');

    return outputPath;
  }
}
