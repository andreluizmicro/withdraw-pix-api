<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Saque PIX realizado com sucesso</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #333333;
            background-color: #f8f9fa;
            padding: 24px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            border-radius: 8px;
            padding: 32px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h2 {
            color: #2563eb;
            margin-bottom: 16px;
        }
        p {
            line-height: 1.5;
            margin: 8px 0;
        }
        .info {
            background-color: #f1f5f9;
            padding: 12px;
            border-radius: 6px;
            margin: 16px 0;
        }
        .footer {
            margin-top: 32px;
            font-size: 13px;
            color: #666666;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Olá, {{ $account_name }} 👋</h2>

    <p>Seu <strong>saque via PIX</strong> foi processado com sucesso!</p>

    <div class="info">
        <p><strong>Valor:</strong> {{ $amount }}</p>
        <p><strong>Chave PIX:</strong> {{ $pix_key }}</p>
        <p><strong>Tipo:</strong> {{ $pix_type }}</p>
        <p><strong>Data/Hora:</strong> {{ $date_time }}</p>
    </div>

    <p>Obrigado por utilizar nossos serviços. O valor estará disponível na conta de destino conforme o horário bancário.</p>

    <div class="footer">
        <p>Atenciosamente,<br>Equipe Financeira</p>
    </div>
</div>
</body>
</html>
