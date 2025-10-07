<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Erro no Saque PIX</title>
</head>
<body>
<h2>Olá, {{ $account_name }}!</h2>

<p>
    Ocorreu um <strong>erro ao processar seu saque PIX</strong>.
</p>

<p>
    <strong>Valor:</strong> {{ $amount }}<br>
    <strong>Chave PIX:</strong> {{ $pix_key }}<br>
    <strong>Tipo:</strong> {{ $pix_type }}<br>
    <strong>Data/Hora:</strong> {{ $date_time }}
</p>

<p>
    Nossa equipe foi notificada e está analisando o problema.
    Tente novamente mais tarde.
</p>

<p>Atenciosamente,<br>Equipe Financeira</p>
</body>
</html>
