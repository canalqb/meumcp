import { PetAgent } from '../pet/PetAgent.js';
import { BORIS_PROFILE, MINI_PROFILE } from '../pet/Personality.js';
import { InterPetCoordinator } from '../communication/InterPetCoordinator.js';
import { ActionPlanner } from '../planner/ActionPlanner.js';
import { ActionQueue } from '../planner/ActionQueue.js';
import { SkillRegistry } from '../../skills/SkillRegistry.js';
import { TelegramSendSkill, TelegramReadSkill, WhatsAppSendSkill, InstagramSkill, FacebookSkill } from '../../skills/modules/social/SocialSkills.js';
import { CameraPhotoSkill, VolumeControlSkill, GenerateMediaSkill } from '../../skills/modules/media/MediaSkills.js';
import { KnowledgeWikidataSkill, KnowledgeSearchSkill, KnowledgeFetchSkill, KnowledgeTimeSkill, KnowledgeMemorySkill } from '../../skills/modules/knowledge/KnowledgeSkills.js';
import { MCPDiscovery } from '../../mcp/MCPDiscovery.js';
import { AndroidService } from '../../services/AndroidService.js';
import { ScriptGenerator } from '../../scripting/ScriptGenerator.js';
import { Scheduler } from '../../scripting/Scheduler.js';

export class AgentExecutor {
  public boris: PetAgent;
  public mini: PetAgent;
  public coordinator: InterPetCoordinator;
  public planner: ActionPlanner;
  public queue: ActionQueue;
  public skillRegistry: SkillRegistry;
  public mcpDiscovery: MCPDiscovery;
  public androidService: AndroidService;
  public scriptGenerator: ScriptGenerator;
  public scheduler: Scheduler;

  constructor() {
    this.boris = new PetAgent(BORIS_PROFILE, { energia: 90 });
    this.mini = new PetAgent(MINI_PROFILE, { energia: 85 });
    this.coordinator = new InterPetCoordinator(this.boris, this.mini);
    this.planner = new ActionPlanner();
    this.queue = new ActionQueue();
    this.skillRegistry = new SkillRegistry();
    this.mcpDiscovery = new MCPDiscovery();
    this.androidService = new AndroidService();
    this.scriptGenerator = new ScriptGenerator();
    this.scheduler = new Scheduler();

    this.registerDefaultSkills();
  }

  private registerDefaultSkills(): void {
    this.skillRegistry.registerSkill(TelegramSendSkill);
    this.skillRegistry.registerSkill(TelegramReadSkill);
    this.skillRegistry.registerSkill(WhatsAppSendSkill);
    this.skillRegistry.registerSkill(InstagramSkill);
    this.skillRegistry.registerSkill(FacebookSkill);
    this.skillRegistry.registerSkill(CameraPhotoSkill);
    this.skillRegistry.registerSkill(VolumeControlSkill);
    this.skillRegistry.registerSkill(GenerateMediaSkill);
    this.skillRegistry.registerSkill(KnowledgeWikidataSkill);
    this.skillRegistry.registerSkill(KnowledgeSearchSkill);
    this.skillRegistry.registerSkill(KnowledgeFetchSkill);
    this.skillRegistry.registerSkill(KnowledgeTimeSkill);
    this.skillRegistry.registerSkill(KnowledgeMemorySkill);
  }

  public async handleUserCommand(prompt: string): Promise<{
    thoughtProcess: { agent: string; thought: string }[];
    finalResponse: string;
    executedSkills: string[];
    emotionalSummary: string;
  }> {
    const executedSkills: string[] = [];

    // 1. Decisão Inter-PET (Boris pensa -> Mini opina -> Boris responde)
    const interaction = this.coordinator.processUserRequest(prompt, 'collaborative');

    // 2. Análise de intenção para execução de habilidades
    const lower = prompt.toLowerCase();
    let selectedSkill: string | null = null;
    let skillArgs: Record<string, unknown> = {};

    if (lower.includes('telegram')) {
      if (lower.includes('enviar') || lower.includes('manda')) {
        selectedSkill = 'telegram_send';
        skillArgs = { target: 'Grupo Principal', message: prompt };
      } else {
        selectedSkill = 'telegram_read';
        skillArgs = { target: 'Canal Noticias', limit: 5 };
      }
    } else if (lower.includes('whatsapp')) {
      selectedSkill = 'whatsapp_send';
      skillArgs = { contact: 'Contato VIP', message: prompt };
    } else if (lower.includes('volume')) {
      selectedSkill = 'volume_control';
      skillArgs = { level: 75 };
    } else if (lower.includes('câmera') || lower.includes('foto')) {
      selectedSkill = 'camera_photo';
      skillArgs = { facing: 'back' };
    } else if (lower.includes('imagem') || lower.includes('vídeo') || lower.includes('gerar')) {
      selectedSkill = 'generate_media';
      skillArgs = { prompt, type: lower.includes('vídeo') ? 'video' : 'image' };
    }

    // 3. Execução do plano de ação se houver skill selecionada
    if (selectedSkill) {
      const plan = this.planner.createPlan(prompt, [{ name: selectedSkill, args: skillArgs }]);
      this.queue.loadPlan(plan);

      const res = await this.skillRegistry.executeSkill(selectedSkill, skillArgs);
      if (res.success) {
        executedSkills.push(`${selectedSkill}: ${String(res.result)}`);
      }
    }

    // Atualização do resumo emocional
    const borisEm = this.boris.emotions.getState();
    const miniEm = this.mini.emotions.getState();
    const emotionalSummary = `Boris (${borisEm.humor.toUpperCase()}, E:${borisEm.energia}%) | Mini (${miniEm.humor.toUpperCase()}, E:${miniEm.energia}%)`;

    return {
      thoughtProcess: interaction.thoughtProcess,
      finalResponse: interaction.finalResponse + (executedSkills.length ? `\n\n[Execuções Realizadas]:\n${executedSkills.join('\n')}` : ''),
      executedSkills,
      emotionalSummary
    };
  }
}
