<!-- Template: estrutura_php_footer_pagina_modal.php -->
<!-- Descrição: Corpo de texto legal a ser carregado via requisição assíncrona para dentro de um Modal Principal de Footer -->

<div class="legal-content-wrapper">
    <!-- O Título da Modal é definido via JS, mas mantemos o H2 semântico aqui caso acessado diretamente -->
    <h2 class="sr-only" aria-hidden="true">{NOME_DA_PAGINA}</h2>
    
    <div class="legal-section">
        <h3>1. Introdução e Propósito</h3>
        <p>
            Bem-vindo à página de {NOME_DA_PAGINA} da plataforma {NOME_DO_SISTEMA}. 
            A presente documentação foi redigida sob estritas avaliações de qualidade, visando
            oferecer a você, nosso usuário, total clareza, transparência e controle sobre sua jornada
            e engajamento em nossas dependências digitais.
            Nós reiteramos nosso profundo comprometimento com as diretrizes do Google AdSense, 
            e os regulamentos internacionais de privacidade como a GDPR e a LGPD.
        </p>
    </div>

    <div class="legal-section">
        <h3>2. Coleta, Escopo e E-E-A-T</h3>
        <p>
            {DETALHE_DESCRITIVO_Obrigatorio_500_palavras_MINIMO}
            Para assegurarmos a Expertise, Confiabilidade e Autoridade (E-E-A-T) de nossos serviços, 
            coletamos e gerenciamos apenas os escopos estritamente necessários para a operação.
            [INSERIR DETALHES ESPECIFICADOS DE PELO MENOS 3 PARÁGRAFOS EXPLICATIVOS NESTA SEÇÃO SOBRE O TEMA,
            SEJA ELE DMCA, COOKIES OU TRANSPARÊNCIA]
        </p>
    </div>

    <div class="legal-section">
        <h3>3. Direitos e Contato Direto</h3>
        <p>
            Garantimos a todos os membros e visitantes o direito de opt-out (saída), solicitação de 
            remoção de arquivos (no caso de DMCA), e total acesso aos logs e informações custodiadas.
            Para exercer seu direito imediato de retificação ou solicitar moderação, entre em contato
            diretamente por meio do nosso canal oficial:
        </p>
        <p>
            <strong>E-mail Administrativo Oficial e DPO:</strong> {EMAIL_DE_CONTATO}
        </p>
        <p>
            Atenderemos a sua solicitação em tempo hábil e de acordo com a base descrita em nossos termos fundamentais.
        </p>
    </div>
</div>

<style>
    /* Estilos base isolados para garantir ótima renderização dentro da janela Modal AJAX */
    .legal-content-wrapper {
        font-family: var(--font-primary, system-ui, -apple-system, sans-serif);
        color: var(--text-color, #333);
        line-height: 1.6;
        padding: 10px;
    }
    
    .legal-content-wrapper .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        border: 0;
    }

    .legal-section {
        margin-bottom: 25px;
    }

    .legal-section h3 {
        font-size: 1.25rem;
        color: var(--primary-color, #2c3e50);
        margin-bottom: 12px;
        border-bottom: 2px solid var(--border-color, #eee);
        padding-bottom: 5px;
    }

    .legal-section p {
        font-size: 0.95rem;
        margin-bottom: 15px;
        text-align: justify;
    }

    .legal-section strong {
        font-weight: 600;
        color: var(--primary-dark, #1a252f);
    }
</style>
