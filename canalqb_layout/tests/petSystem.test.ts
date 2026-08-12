import test from 'node:test';
import assert from 'node:assert';
import { EmotionEngine } from '../src/core/pet/EmotionEngine.js';
import { MemoryManager } from '../src/core/pet/MemoryManager.js';
import { PetAgent } from '../src/core/pet/PetAgent.js';
import { BORIS_PROFILE, MINI_PROFILE } from '../src/core/pet/Personality.js';
import { InterPetCoordinator } from '../src/core/communication/InterPetCoordinator.js';
import { ActionPlanner } from '../src/core/planner/ActionPlanner.js';
import { SkillRegistry } from '../src/skills/SkillRegistry.js';
import { TelegramSendSkill } from '../src/skills/modules/social/SocialSkills.js';
import { AgentExecutor } from '../src/core/agent/AgentExecutor.js';

test('EmotionEngine calcula humor corretamente', () => {
  const engine = new EmotionEngine({ energia: 20 });
  assert.strictEqual(engine.getState().humor, 'cansado');

  engine.updateEmotions({ energia: 60, curiosidade: 85 });
  assert.strictEqual(engine.getState().humor, 'curioso');
});

test('MemoryManager armazena curto e longo prazo', () => {
  const mem = new MemoryManager();
  mem.addTurn('user', 'User', 'Olá Boris');
  assert.strictEqual(mem.getShortTermHistory().length, 1);

  mem.addLongTermMemory('preference', 'Usuário prefere respostas rápidas', 5);
  const preferences = mem.getLongTermMemories('preference');
  assert.strictEqual(preferences.length, 1);
  assert.strictEqual(preferences[0].content, 'Usuário prefere respostas rápidas');
});

test('InterPetCoordinator executa fluxo colaborativo Boris e Mini', () => {
  const boris = new PetAgent(BORIS_PROFILE);
  const mini = new PetAgent(MINI_PROFILE);
  const coordinator = new InterPetCoordinator(boris, mini);

  const res = coordinator.processUserRequest('Verificar mensagens do Telegram', 'collaborative');
  assert.strictEqual(res.thoughtProcess.length, 2);
  assert.strictEqual(res.primaryResponder, 'Boris & Mini');
});

test('ActionPlanner gera passos de plano de ação com validação', () => {
  const planner = new ActionPlanner();
  const plan = planner.createPlan('Enviar mensagem', [{ name: 'telegram_send', args: { message: 'teste' } }]);

  assert.strictEqual(plan.steps.length, 2);
  assert.strictEqual(plan.steps[0].toolName, 'telegram_send');
});

test('SkillRegistry cadastra e executa skill do Telegram', async () => {
  const registry = new SkillRegistry();
  registry.registerSkill(TelegramSendSkill);

  const res = await registry.executeSkill('telegram_send', { target: 'Grupo QA', message: 'Oi!' });
  assert.strictEqual(res.success, true);
  assert.match(String(res.result), /Mensagem enviada com sucesso/);
});

test('AgentExecutor orquestra comando completo do usuário', async () => {
  const agent = new AgentExecutor();
  const res = await agent.handleUserCommand('Boris, envie um Telegram para o Grupo Principal com a foto da câmera');

  assert.strictEqual(res.thoughtProcess.length, 2);
  assert.strictEqual(res.executedSkills.length, 1);
});
