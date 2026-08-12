import { PetProfile } from './Personality.js';
import { EmotionEngine } from './EmotionEngine.js';
import { MemoryManager } from './MemoryManager.js';

export class PetAgent {
  public readonly profile: PetProfile;
  public readonly emotions: EmotionEngine;
  public readonly memory: MemoryManager;

  constructor(profile: PetProfile, initialState?: Parameters<typeof EmotionEngine.prototype.updateEmotions>[0]) {
    this.profile = profile;
    this.emotions = new EmotionEngine();
    if (initialState) {
      this.emotions.updateEmotions(initialState);
    }
    this.memory = new MemoryManager();
  }

  public getFullSystemPrompt(): string {
    const memoryContext = this.memory.getFormattedMemoryContext();
    const tonePrompt = this.emotions.getTonePrompt();

    return `${this.profile.baseSystemPrompt}

Estilo de fala: ${this.profile.speakingStyle}
Preferências: ${this.profile.preferences.join(', ')}
Medos: ${this.profile.fears.join(', ')}

${tonePrompt}

${memoryContext}`;
  }

  public generateResponseSnippet(userPrompt: string, peerContext?: string): string {
    const emotionState = this.emotions.getState();
    this.memory.addTurn('user', 'User', userPrompt);

    // Efeito emocional da interação
    this.emotions.updateEmotions({
      curiosidade: 2,
      energia: -1
    });

    let prefix = `[${this.profile.name} (${this.profile.avatar}) - Humor: ${emotionState.humor}]: `;
    if (peerContext) {
      prefix += `(Considerando o comentário anterior: "${peerContext}") `;
    }

    return prefix;
  }
}
