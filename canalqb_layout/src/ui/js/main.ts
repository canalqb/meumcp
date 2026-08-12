import { AgentExecutor } from '../../core/agent/AgentExecutor.js';

const agent = new AgentExecutor();

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('command-form') as HTMLFormElement;
  const input = document.getElementById('command-input') as HTMLInputElement;
  const chatLog = document.getElementById('chat-log') as HTMLDivElement;

  function appendMessage(sender: string, text: string, type: 'user' | 'pet' | 'system' = 'pet') {
    const msgDiv = document.createElement('div');
    msgDiv.className = `chat-message ${type}-msg`;

    const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    msgDiv.innerHTML = `<span class="msg-time">[${time}]</span> <strong>${sender}:</strong> ${text}`;

    chatLog.appendChild(msgDiv);
    chatLog.scrollTop = chatLog.scrollHeight;
  }

  function updateEmotionsUI() {
    const borisState = agent.boris.emotions.getState();
    const miniState = agent.mini.emotions.getState();

    // Boris UI
    const bHumor = document.getElementById('boris-humor');
    if (bHumor) bHumor.textContent = borisState.humor.toUpperCase();

    const bE = document.getElementById('boris-energia');
    const bEV = document.getElementById('boris-energia-val');
    if (bE && bEV) { bE.style.width = `${borisState.energia}%`; bEV.textContent = `${borisState.energia}%`; }

    const bF = document.getElementById('boris-felicidade');
    const bFV = document.getElementById('boris-felicidade-val');
    if (bF && bFV) { bF.style.width = `${borisState.felicidade}%`; bFV.textContent = `${borisState.felicidade}%`; }

    // Mini UI
    const mHumor = document.getElementById('mini-humor');
    if (mHumor) mHumor.textContent = miniState.humor.toUpperCase();

    const mE = document.getElementById('mini-energia');
    const mEV = document.getElementById('mini-energia-val');
    if (mE && mEV) { mE.style.width = `${miniState.energia}%`; mEV.textContent = `${miniState.energia}%`; }

    const mF = document.getElementById('mini-felicidade');
    const mFV = document.getElementById('mini-felicidade-val');
    if (mF && mFV) { mF.style.width = `${miniState.felicidade}%`; mFV.textContent = `${miniState.felicidade}%`; }
  }

  form?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const prompt = input.value.trim();
    if (!prompt) return;

    appendMessage('Você', prompt, 'user');
    input.value = '';

    const result = await agent.handleUserCommand(prompt);

    // Mostra o pensamento inter-PET
    for (const t of result.thoughtProcess) {
      appendMessage(`💭 Pensamento (${t.agent})`, t.thought, 'system');
    }

    // Resposta final
    appendMessage(result.thoughtProcess.length ? 'Boris & Mini' : 'PET', result.finalResponse, 'pet');

    updateEmotionsUI();
  });

  updateEmotionsUI();
});
