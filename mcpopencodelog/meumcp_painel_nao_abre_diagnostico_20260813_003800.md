# Diagnóstico — por que "meumcp install" não mostra o painel de seleção

Data: 2026-08-13 00:38

## Sintoma
Ao digitar `meumcp install`, espera-se um painel interativo para marcar quais
MCPs/tecnologias instalar — mas nada aparece (ou o comando simplesmente
termina sem o painel).

## Causas encontradas (2)

### 1. Comando `meumcp` não existia no PATH (causa principal)
- O `node dist/cli.js` funciona, mas o binário `meumcp` não estava linkado.
- `which meumcp` → não encontrado. Digitar `meumcp install` dava
  "command not found" — nada era exibido.
- **Correção aplicada:** `npm link` em /root/meumcp → criou
  `/usr/local/bin/meumcp` apontando para `dist/cli.js`.
- Agora `meumcp --help` mostra o comando `install`.

### 2. O painel interativo exige um terminal real (TTY)
- O wizard usa a lib `prompts` (multiselect), que só desenha o painel quando
  stdin/stdout são um TTY interativo de verdade.
- Quando a execução acontece sem TTY (ambiente de agente/openai codepuge,
  shell capturado, `piped`, ssh sem terminal), a lib não renderiza o painel:
  imprime só o cabeçalho "Selecione os MCPs..." e encerra (exit 0) ou ignora
  a entrada.
- Teste: `meumcp install </dev/null` → imprime só o cabeçalho, sem lista/box.
- Teste com TTY (`script`/terminal real) → painel completo com lista, checkboxes,
  navegação e relatório final.
- **Isso não é bug do source**: é limitação de apps que rodam sem terminal
  interativo. Em Android Termux/app com TTY, o painel abre normalmente.

## Como usar corretamente
- Rodar `meumcp install` em um terminal interativo real (ex.: Termux, ssh com
  `-t`, ou shell do app que fornece TTY).
- Se o painel não abrir, usar a alternativa não-interativa:
  `meumcp mcps` para listar, e editar manualmente `mcps/enabled.json`
  (array de ids, ex.: `["keyhunter:BryanSapo/info-pulse"]`).

## Estado após correção
- `which meumcp` → /usr/local/bin/meumcp ✓
- `meumcp --help` → comando `install` presente ✓
- `meumcp install` em TTY → painel funciona ✓ (validado com `script`)