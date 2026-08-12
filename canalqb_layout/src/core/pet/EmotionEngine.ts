export interface EmotionState {
  energia: number;     // 0-100
  felicidade: number;  // 0-100
  curiosidade: number; // 0-100
  confianca: number;  // 0-100
  preguica: number;   // 0-100
  humor: 'animado' | 'focado' | 'irritado' | 'relaxado' | 'curioso' | 'cansado';
}

export class EmotionEngine {
  private state: EmotionState;

  constructor(initialState?: Partial<EmotionState>) {
    this.state = {
      energia: initialState?.energia ?? 80,
      felicidade: initialState?.felicidade ?? 75,
      curiosidade: initialState?.curiosidade ?? 85,
      confianca: initialState?.confianca ?? 70,
      preguica: initialState?.preguica ?? 20,
      humor: initialState?.humor ?? 'focado',
    };
    this.recalculateMood();
  }

  public getState(): Readonly<EmotionState> {
    return { ...this.state };
  }

  public updateEmotions(deltas: Partial<Omit<EmotionState, 'humor'>>): void {
    if (deltas.energia !== undefined) this.state.energia = this.clamp(this.state.energia + deltas.energia);
    if (deltas.felicidade !== undefined) this.state.felicidade = this.clamp(this.state.felicidade + deltas.felicidade);
    if (deltas.curiosidade !== undefined) this.state.curiosidade = this.clamp(this.state.curiosidade + deltas.curiosidade);
    if (deltas.confianca !== undefined) this.state.confianca = this.clamp(this.state.confianca + deltas.confianca);
    if (deltas.preguica !== undefined) this.state.preguica = this.clamp(this.state.preguica + deltas.preguica);

    this.recalculateMood();
  }

  private clamp(val: number): number {
    return Math.max(0, Math.min(100, Math.round(val)));
  }

  private recalculateMood(): void {
    if (this.state.energia < 30) {
      this.state.humor = 'cansado';
    } else if (this.state.preguica > 70) {
      this.state.humor = 'relaxado';
    } else if (this.state.curiosidade > 80 && this.state.energia > 50) {
      this.state.humor = 'curioso';
    } else if (this.state.felicidade > 80 && this.state.energia > 60) {
      this.state.humor = 'animado';
    } else if (this.state.felicidade < 30 || this.state.confianca < 30) {
      this.state.humor = 'irritado';
    } else {
      this.state.humor = 'focado';
    }
  }

  public getTonePrompt(): string {
    const { energia, felicidade, curiosidade, confianca, preguica, humor } = this.state;
    return `[Estado Emocional Interno: Humor=${humor.toUpperCase()} | Energia=${energia}% | Felicidade=${felicidade}% | Curiosidade=${curiosidade}% | Confiança=${confianca}% | Preguiça=${preguica}%]. Module seu tom de fala de acordo com esse estado emocional.`;
  }
}
