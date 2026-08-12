# 💳 Padrão e Estrutura de Pagamento Pagar.me (V5)
> Unificado: Regra + Template de Implementação | Pix, Débito e Crédito (até 3x)

## 📋 Visão Geral
Este documento define o padrão técnico obrigatório para integração com Pagar.me via cURL (PHP Puro).

## 🧱 Arquitetura de Módulos
1. `modulos/config_pagarme.php`: Credenciais (`PAGARME_SECRET_KEY`, `PAGARME_URL`).
2. `modulos/checkout.php`: Interface de coleta e Pagar.me JS SDK (Tokenização obrigatória).
3. `modulos/processar_pagamento.php`: Lógica cURL. Valores sempre em **centavos**.
4. `modulos/webhook.php`: Ouvinte de status (usa `Ngrok` para testes locais).
5. `modulos/obrigado.php` / `modulos/erro.php`: Telas de conversão ou erro.

## 🛡️ Segurança Obrigatória
- **Tokenização**: NUNCA envie dados de cartão crus. Use o JS SDK para gerar `card_token`.
- **CVV**: Proibido armazenar.
- **HTTPS**: Obrigatório para transações reais.
- **Webhooks**: Validar assinatura para evitar falsificações.

## 🔑 Exemplo de Payload (PHP)
```php
$data = [
    "items" => [["amount" => 19700, "description" => "Pedido #1", "quantity" => 1]],
    "customer" => ["name" => $nome, "email" => $email, "document" => $cpf, "type" => "individual"],
    "payments" => [
        [
            "payment_method" => $metodo_escolhido, // 'pix', 'credit_card', 'debit_card'
            "credit_card" => [
                "installments" => $parcelas, // max 3
                "card" => ["number" => $num, "holder_name" => $nome, "exp_month" => $m, "exp_year" => $y, "cvv" => $cvv]
            ]
        ]
    ]
];
```

## 🛡️ Segurança Obrigatória
1. **Tokenização**: Em produção, NUNCA envie os dados do cartão em texto puro para o seu servidor. Use o `pagarme-js` no frontend para gerar um `card_token`.
2. **HTTPS**: Obrigatório para transações de cartão.
3. **Webhooks**: Valide a assinatura do Pagar.me para evitar falsificações de status.

---
*Regra master: Sempre utilize centavos para valores monetários (Ex: R$ 1,00 = 100).*
