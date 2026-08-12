export interface MemoryItem {
  id: string;
  category: 'preference' | 'fact' | 'relation' | 'learned_skill' | 'event';
  content: string;
  importance: number; // 1-5
  createdAt: number;
}

export interface ConversationTurn {
  role: 'user' | 'assistant' | 'system' | 'pet';
  sender: string;
  content: string;
  timestamp: number;
}

export class MemoryManager {
  private shortTermHistory: ConversationTurn[] = [];
  private longTermMemories: Map<string, MemoryItem> = new Map();

  constructor(private maxShortTermTurns: number = 20) {}

  public addTurn(role: ConversationTurn['role'], sender: string, content: string): void {
    this.shortTermHistory.push({
      role,
      sender,
      content,
      timestamp: Date.now()
    });

    if (this.shortTermHistory.length > this.maxShortTermTurns) {
      this.shortTermHistory.shift();
    }
  }

  public getShortTermHistory(): readonly ConversationTurn[] {
    return this.shortTermHistory;
  }

  public addLongTermMemory(category: MemoryItem['category'], content: string, importance: number = 3): MemoryItem {
    const item: MemoryItem = {
      id: `mem_${Date.now()}_${Math.random().toString(36).substr(2, 5)}`,
      category,
      content,
      importance,
      createdAt: Date.now()
    };
    this.longTermMemories.set(item.id, item);
    return item;
  }

  public getLongTermMemories(category?: MemoryItem['category']): MemoryItem[] {
    const memories = Array.from(this.longTermMemories.values());
    if (!category) return memories;
    return memories.filter(m => m.category === category);
  }

  public getFormattedMemoryContext(): string {
    const longTermStr = Array.from(this.longTermMemories.values())
      .map(m => `- [${m.category.toUpperCase()}]: ${m.content}`)
      .join('\n');

    return `[Memórias de Longo Prazo Ativas]:\n${longTermStr || 'Nenhuma memória de longo prazo gravada ainda.'}`;
  }
}
