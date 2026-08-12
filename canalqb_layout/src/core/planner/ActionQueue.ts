import { Plan, PlanStep } from './ActionPlanner.js';

export class ActionQueue {
  private currentPlan: Plan | null = null;

  public loadPlan(plan: Plan): void {
    this.currentPlan = plan;
  }

  public getNextStep(): PlanStep | null {
    if (!this.currentPlan) return null;
    return this.currentPlan.steps.find(s => s.status === 'pending') || null;
  }

  public updateStepStatus(stepId: string, status: PlanStep['status'], result?: unknown): void {
    if (!this.currentPlan) return;
    const step = this.currentPlan.steps.find(s => s.id === stepId);
    if (step) {
      step.status = status;
      if (result !== undefined) step.result = result;
    }
  }

  public getPlanSummary(): string {
    if (!this.currentPlan) return 'Nenhum plano ativo.';
    return `Plano [${this.currentPlan.goal}]: ${this.currentPlan.steps.filter(s => s.status === 'completed').length}/${this.currentPlan.steps.length} passos concluídos.`;
  }
}
