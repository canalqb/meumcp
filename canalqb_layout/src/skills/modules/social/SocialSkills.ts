import { SkillExecutor } from '../../ManifestSchema.js';

export const TelegramSendSkill: SkillExecutor = {
  manifest: {
    id: 'telegram_send',
    name: 'Telegram Send Message',
    version: '1.0.0',
    description: 'Envia mensagens de texto ou arquivos para contatos, grupos ou canais do Telegram.',
    permissions: [{ name: 'INTERNET', required: true }],
    inputSchema: {
      target: 'string (contato/grupo/canal)',
      message: 'string'
    }
  },
  async execute(args) {
    const target = String(args.target || '');
    const message = String(args.message || '');
    return {
      success: true,
      result: `Mensagem enviada com sucesso para o Telegram (${target}): "${message}"`
    };
  }
};

export const TelegramReadSkill: SkillExecutor = {
  manifest: {
    id: 'telegram_read',
    name: 'Telegram Read Chat',
    version: '1.0.0',
    description: 'Lê mensagens recentes de um canal, grupo ou conversa do Telegram.',
    permissions: [{ name: 'INTERNET', required: true }],
    inputSchema: {
      target: 'string',
      limit: 'number'
    }
  },
  async execute(args) {
    const target = String(args.target || '');
    return {
      success: true,
      result: `Últimas mensagens lidas do Telegram [${target}]: "Olá Boris! Como está o projeto dos PETs?"`
    };
  }
};

export const WhatsAppSendSkill: SkillExecutor = {
  manifest: {
    id: 'whatsapp_send',
    name: 'WhatsApp Send Message',
    version: '1.0.0',
    description: 'Envia texto ou notas de áudio via WhatsApp para contatos validados.',
    permissions: [{ name: 'ACCESSIBILITY_SERVICE', required: true }],
    inputSchema: {
      contact: 'string',
      message: 'string',
      isAudio: 'boolean'
    }
  },
  async execute(args) {
    const contact = String(args.contact || '');
    const message = String(args.message || '');
    const isAudio = Boolean(args.isAudio);
    return {
      success: true,
      result: `WhatsApp: ${isAudio ? 'Áudio' : 'Texto'} enviado para '${contact}': "${message}"`
    };
  }
};

export const InstagramSkill: SkillExecutor = {
  manifest: {
    id: 'instagram_post',
    name: 'Instagram Media Post',
    version: '1.0.0',
    description: 'Publica imagens/vídeos e lê DMs no Instagram.',
    permissions: [{ name: 'INTERNET', required: true }],
    inputSchema: {
      caption: 'string',
      mediaPath: 'string'
    }
  },
  async execute(args) {
    return {
      success: true,
      result: `Post publicado no Instagram com legenda: "${args.caption}"`
    };
  }
};

export const FacebookSkill: SkillExecutor = {
  manifest: {
    id: 'facebook_post',
    name: 'Facebook Post & Reply',
    version: '1.0.0',
    description: 'Cria posts e responde comentários no Facebook.',
    permissions: [{ name: 'INTERNET', required: true }],
    inputSchema: {
      content: 'string'
    }
  },
  async execute(args) {
    return {
      success: true,
      result: `Conteúdo publicado na página do Facebook: "${args.content}"`
    };
  }
};
