export interface PlanStep {
  id: string;
  description: string;
  toolName?: string;
  args?: Record<string, unknown>;
  status: 'pending' | 'executing' | 'completed' | 'failed';
  result?: unknown;
}

export interface Plan {
  id: string;
  goal: string;
  steps: PlanStep[];
  createdAt: number;
}

export class ActionPlanner {
  public createPlan(goal: string, requiredTools: { name: string; args?: Record<string, unknown> }[]): Plan {
    const steps: PlanStep[] = requiredTools.map((tool, idx) => ({
      id: `step_${idx + 1}`,
      description: `Executar ferramenta ${tool.name}`,
      toolName: tool.name,
      args: tool.args,
      status: 'pending'
    }));

    // Adiciona passo final de resposta/validação
    steps.push({
      id: `step_${steps.length + 1}`,
      description: 'Validar resultados e formatar resposta final para o usuário',
      status: 'pending'
    });

    return {
      id: `plan_${Date.now()}`,
      goal,
      steps,
      createdAt: Date.now()
    };
  }
}
