/**
 * Tests for RulesEngine
 */
import { RulesEngine } from '../src/rules/rules-engine';
import { promises as fs } from 'fs';
import * as path from 'path';
import * as os from 'os';

describe('RulesEngine', () => {
  let tmpDir: string;

  beforeEach(async () => {
    tmpDir = await fs.mkdtemp(path.join(os.tmpdir(), 'meumcp-test-'));
  });

  afterEach(async () => {
    await fs.rm(tmpDir, { recursive: true, force: true });
  });

  it('should load rules from directory', async () => {
    const rulesDir = path.join(tmpDir, 'rules');
    await fs.mkdir(rulesDir, { recursive: true });
    await fs.writeFile(
      path.join(rulesDir, 'test_rule.md'),
      '---\nid: test-rule\ntitle: Test Rule\npriority: 100\nallowOverride: false\nenforced: true\ntags: [test]\n---\nThis is a test rule.' +
        '\n\n---\n\nAnother rule without frontmatter.',
      'utf-8',
    );
    await fs.writeFile(
      path.join(rulesDir, 'no_frontmatter.md'),
      '---\nid: nf-rule\ntitle: No Frontmatter Rule\npriority: 50\n---\nRule with frontmatter.',
      'utf-8',
    );

    const engine = new RulesEngine([rulesDir]);
    await engine.load();
    expect(engine.getAll().length).toBe(2);
  });

  it('should get rules for a specific agent', async () => {
    const rulesDir = path.join(tmpDir, 'rules');
    await fs.mkdir(rulesDir, { recursive: true });
    await fs.writeFile(
      path.join(rulesDir, 'agent_rule.md'),
      '---\nid: agent-rule\ntitle: Agent Rule\npriority: 100\nscope:\n  agent: [content-creator]\n---\nFor content creators.',
      'utf-8',
    );
    await fs.writeFile(
      path.join(rulesDir, 'global_rule.md'),
      '---\nid: global-rule\ntitle: Global Rule\npriority: 90\n---\nGlobal rule.',
      'utf-8',
    );

    const engine = new RulesEngine([rulesDir]);
    await engine.load();

    const agentRules = engine.getRulesForAgent('content-creator');
    expect(agentRules.length).toBe(2);
    expect(agentRules.find((r) => r.id === 'agent-rule')).toBeDefined();
    expect(agentRules.find((r) => r.id === 'global-rule')).toBeDefined();

    const otherRules = engine.getRulesForAgent('other-agent');
    expect(otherRules.length).toBe(1); // Only global rules for non-targeted agents
    expect(otherRules.find((r) => r.id === 'global-rule')).toBeDefined();
    expect(otherRules.find((r) => r.id === 'agent-rule')).toBeUndefined();
  });

  it('should detect conflicts when rules share tags', async () => {
    const rulesDir = path.join(tmpDir, 'rules');
    await fs.mkdir(rulesDir, { recursive: true });
    await fs.writeFile(
      path.join(rulesDir, 'conflict1.md'),
      '---\nid: rule-a\ntitle: Rule A\npriority: 100\nenforced: true\nallowOverride: false\ntags: [security, compliance]\n---\nContent one.',
      'utf-8',
    );
    await fs.writeFile(
      path.join(rulesDir, 'conflict2.md'),
      '---\nid: rule-b\ntitle: Rule B\npriority: 50\nenforced: true\nallowOverride: true\ntags: [security, experimental]\n---\nContent two.',
      'utf-8',
    );

    const engine = new RulesEngine([rulesDir]);
    await engine.load();
    const conflicts = engine.getConflicts();
    expect(conflicts.length).toBeGreaterThan(0);
    // Both rules should be flagged as conflicting (share 'security' tag)
    expect(conflicts[0].ruleId).toBe('rule-a');
    expect(conflicts[1].ruleId).toBe('rule-b');
  });

  it('should return empty array when no rules found', async () => {
    const engine = new RulesEngine([path.join(tmpDir, 'nonexistent')]);
    await engine.load();
    expect(engine.getAll()).toEqual([]);
  });
});
