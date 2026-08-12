import { PetAgent } from '../pet/PetAgent.js';

export interface InteractionResult {
  primaryResponder: string;
  thoughtProcess: { agent: string; thought: string }[];
  finalResponse: string;
  peerReaction?: { agent: string; comment: string };
}

export class InterPetCoordinator {
  constructor(
    public readonly boris: PetAgent,
    public readonly mini: PetAgent
  ) {}

  public processUserRequest(
    userPrompt: string,
    flowMode: 'boris_leads' | 'mini_leads' | 'collaborative' = 'collaborative'
  ): InteractionResult {
    const thoughts: { agent: string; thought: string }[] = [];

    if (flowMode === 'boris_leads') {
      // Flow 1: Boris pensa, Mini opina, Boris responde
      const borisThought = `Analisando a tarefa "${userPrompt}". O que é necessário para executar?`;
      thoughts.push({ agent: this.boris.profile.name, thought: borisThought });

      const miniOpinion = `Achei interessante! Podemos checar se há alguma preferência salva primeiro?`;
      thoughts.push({ agent: this.mini.profile.name, thought: miniOpinion });

      const finalResponse = `${this.boris.profile.avatar} ${this.boris.profile.name}: Perfeito, Mini. Entendido o pedido "${userPrompt}". Vou preparar o plano de ação imediatamente.`;

      this.boris.memory.addTurn('assistant', this.boris.profile.name, finalResponse);
      this.mini.memory.addTurn('pet', this.mini.profile.name, miniOpinion);

      return {
        primaryResponder: this.boris.profile.name,
        thoughtProcess: thoughts,
        finalResponse,
        peerReaction: { agent: this.mini.profile.name, comment: miniOpinion }
      };
    } else if (flowMode === 'mini_leads') {
      // Flow 2: Mini chama Boris, Boris executa, Mini comenta
      const miniThought = `Entendi a dúvida "${userPrompt}". Vou chamar o Boris para cuidar da automação!`;
      thoughts.push({ agent: this.mini.profile.name, thought: miniThought });

      const borisExecution = `Executando verificação para a solicitação...`;
      thoughts.push({ agent: this.boris.profile.name, thought: borisExecution });

      const finalResponse = `${this.mini.profile.avatar} ${this.mini.profile.name}: Olá! O Boris já cuidou dos detalhes técnicos para você. Ficou tudo pronto!`;

      this.mini.memory.addTurn('assistant', this.mini.profile.name, finalResponse);
      this.boris.memory.addTurn('pet', this.boris.profile.name, borisExecution);

      return {
        primaryResponder: this.mini.profile.name,
        thoughtProcess: thoughts,
        finalResponse,
        peerReaction: { agent: this.boris.profile.name, comment: borisExecution }
      };
    } else {
      // Flow 3: Colaborativo
      const borisThought = `Boris avaliaviabilidade técnica de: "${userPrompt}"`;
      thoughts.push({ agent: this.boris.profile.name, thought: borisThought });

      const miniThought = `Mini avalia impacto e experiência do usuário em: "${userPrompt}"`;
      thoughts.push({ agent: this.mini.profile.name, thought: miniThought });

      const finalResponse = `${this.boris.profile.avatar} ${this.boris.profile.name}: "${userPrompt}" recebido. Eu assumo a parte pesada.\n${this.mini.profile.avatar} ${this.mini.profile.name}: E eu fico de olho pra garantir que dê tudo certo!`;

      return {
        primaryResponder: 'Boris & Mini',
        thoughtProcess: thoughts,
        finalResponse
      };
    }
  }
}
