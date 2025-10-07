<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Withdraw PIX</title>
</head>
<body>
<p>Olá {{ $account_name }},</p>
<p>Lamentamos, Houve um erro ao tentar realizar o PIX no valor de {{ $amount }}.</p>
<p>Chave PIX: {{ $pixKey }}</p>
<p>Tipo: {{ $type }}</p>
<p>Data/Hora: {{ $date_time }}</p>
</body>
</html>
