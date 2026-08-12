# Arquitetura e Especificação do Sistema PETs AI (Boris & Mini)

> **Documento de Integração para OpenCode, Termux e APK QBagente**
> **Gerado por Antigravity AI** - Projeto PETs AI v2.0

---

## 1. Diagnóstico do Sistema Anterior

O diagnóstico realizado nos arquivos do celular em `/root/phone-chat-server.js` revelou por que o Boris e a Mini estavam operando de forma genérica ("tocos"):
- **Prompt Estático**: O servidor apenas repassava mensagens ao `opencode run` usando uma string fixa (`QBAGENTE_PERSONA`).
- **Sem Motor Emocional**: Não havia variação de humor, energia ou atitude.
- **Sem Memória Persistente Categorizada**: Falta de separação entre memória de curto prazo (conversas) e longo prazo (fatos, preferências do usuário e relações).
- **Falta de Protocolo Inter-PET**: Não existia ciclo de colaboração (`Boris pensa → Mini opina → Boris responde`).
- **Ferramentas Acopladas**: Chamadas de terminal diretas em vez de um sistema dinâmico de `Skills` e servidores `MCP`.

---

## 2. Arquitetura PETs AI em 17 Módulos

```
PET (Boris / Mini)
 ├── 1. Personalidade Permanente (System Prompt & Traços)
 ├── 2. Memória de Longo Prazo (Fatos, Preferências, Relações)
 ├── 3. Memória de Curto Prazo (Buffer de histórico)
 ├── 4. Emoções Dinâmicas (Energia, Felicidade, Curiosidade, Confiança, Preguiça, Humor)
 ├── 5. Objetivos & Necessidades
 ├── 6. Conhecimento Acumulado
 ├── 7. Planejador de Ações (ActionPlanner & ActionQueue)
 ├── 8. Executor de Agente (AgentExecutor)
 ├── 9. Ferramentas & MCP (MCPDiscovery & Registry)
 └── 10. Modelo LLM Próprio (Boris: Llama 3 / DeepSeek | Mini: Qwen 2.5 / Gemini)
```

### Módulos Principais Desenvolvidos:

1. **`EmotionEngine.ts`**: Atualiza métricas (0-100) e calcula humores: `focado`, `curioso`, `animado`, `cansado`, `irritado`, `relaxado`.
2. **`Personality.ts`**: Perfis inconfundíveis para Boris (prático, sarcástico, focado em execução) e Mini (curiosa, empática, questionadora).
3. **`MemoryManager.ts`**: Memória dividida em buffer de curto prazo e repositório de longo prazo categorizado (`preference`, `fact`, `relation`, `learned_skill`).
4. **`InterPetCoordinator.ts`**: Fluxos multi-agente de conversa e colaboração.
5. **`ActionPlanner.ts` & `ActionQueue.ts`**: Ciclo de raciocínio `Pergunta → Planejamento → Seleção de Tools → Execução → Validação → Resposta`.
6. **`SkillRegistry.ts`**: Cadastro e execução de habilidades para **Telegram** (enviar/ler), **WhatsApp** (texto/áudio), **Instagram** (DM/posts), **Facebook**, **Câmera**, **Volume** e **Mídia Generativa**.
7. **`MCPDiscovery.ts`**: Auto-descoberta de MCPs locais e integração dos repositórios:
   - *VTube Studio Plugin MCP Server* (`hkopenai/vtube-studio-plugin-mcp-server`)
   - *Android Remote Control MCP* (`danielealbano/android-remote-control-mcp`)
   - *Open-LLM-VTuber* (`Open-LLM-VTuber`)
   - *Claude Code MCP Controller* (`mostafa-drz/claude-code-mcp-controller`)
8. **`AndroidService.ts`**: Abstração unificada para controle do sistema (App launch/close, screenshot, volume, wifi, bluetooth, sensores, notificações).
9. **`ScriptGenerator.ts` & `Scheduler.ts`**: Automação autônoma onde os PETs criam e agendam scripts shell.
10. **Dashboard UI Moderno (`src/ui/`)**: Interface web em Dark Mode com glassmorphism, gauges emocionais em tempo real, toggles de skills e terminal inter-PET.

---

## 3. Instruções para Execução no OpenCode e Termux

### Executar no Celular (Termux / Ubuntu proot):
```bash
# 1. Navegar até a pasta do projeto no celular ou clonar
cd /root/boris # ou /data/data/com.termux/files/home/boris

# 2. Instalar dependências
npm install

# 3. Rodar a suíte de testes para garantir integridade
npm test

# 4. Iniciar o servidor web dashboard
npm run dev -- --host 0.0.0.0 --port 3000
```

### Comandos de Conexão OpenCode Attach:
```bash
# Iniciar o servidor opencode na porta 4000
proot-distro login ubuntu -- opencode serve --hostname 0.0.0.0 --port 4000

# Conectar a partir do PC ou do app APK:
opencode run --attach http://127.0.0.1:4000
```
