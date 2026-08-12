import { SkillExecutor, SkillManifest } from './ManifestSchema.js';

export class SkillRegistry {
  private skills: Map<string, SkillExecutor> = new Map();

  public registerSkill(executor: SkillExecutor): void {
    this.skills.set(executor.manifest.id, executor);
  }

  public getSkill(id: string): SkillExecutor | undefined {
    return this.skills.get(id);
  }

  public listSkills(): SkillManifest[] {
    return Array.from(this.skills.values()).map(s => s.manifest);
  }

  public async executeSkill(
    id: string,
    args: Record<string, unknown>
  ): Promise<{ success: boolean; result?: unknown; error?: string }> {
    const executor = this.skills.get(id);
    if (!executor) {
      return { success: false, error: `Skill '${id}' não encontrada no Registry.` };
    }

    try {
      return await executor.execute(args);
    } catch (err: unknown) {
      const msg = err instanceof Error ? err.message : String(err);
      return { success: false, error: `Falha na execução da skill '${id}': ${msg}` };
    }
  }
}
