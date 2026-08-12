import { GeneratedScript } from './ScriptGenerator.js';

export interface ScheduledTask {
  script: GeneratedScript;
  active: boolean;
  lastRun?: number;
}

export class Scheduler {
  private tasks: Map<string, ScheduledTask> = new Map();

  public scheduleTask(script: GeneratedScript): ScheduledTask {
    const task: ScheduledTask = {
      script,
      active: true
    };
    this.tasks.set(script.id, task);
    return task;
  }

  public getActiveTasks(): ScheduledTask[] {
    return Array.from(this.tasks.values()).filter(t => t.active);
  }

  public toggleTask(scriptId: string, active: boolean): boolean {
    const task = this.tasks.get(scriptId);
    if (!task) return false;
    task.active = active;
    return true;
  }
}
