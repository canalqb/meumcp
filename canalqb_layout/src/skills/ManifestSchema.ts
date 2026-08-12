export interface SkillPermission {
  name: string;
  required: boolean;
}

export interface SkillManifest {
  id: string;
  name: string;
  version: string;
  description: string;
  permissions: SkillPermission[];
  inputSchema: Record<string, unknown>;
  outputSchema?: Record<string, unknown>;
}

export interface SkillExecutor {
  manifest: SkillManifest;
  execute: (args: Record<string, unknown>) => Promise<{ success: boolean; result?: unknown; error?: string }>;
}
