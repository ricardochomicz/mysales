<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500&display=swap" rel="stylesheet">
    <title>Protocolo de Atendimento</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito&display=swap');

        html, body {
            font-family: 'Nunito', sans-serif;
        }
    </style>

</head>

<body>

@if ($protocol->closure != '')
    <h3>Olá, <b>{{ $protocol->user->name }}.</b></h3>
    <p>
        Segue abaixo o retorno do protocolo de atendimento junto à operadora <b>{{ $protocol->operadora->name }}.</b>
    </p>
@else
    <h3>Olá, <b>{{ $protocol->user->name }}.</b></h3>
    <p>
        Segue abaixo seu protocolo de atendimento junto à operadora <b>{{ $protocol->operadora->name }}.</b>
    </p>
@endif

<div style="border:1px solid #cccc; border-radius: 6px; padding: 5px">
    <p><b>Número Protocolo: </b> {{ $protocol->number }}</p>
    <p><b>Aberto em: </b> {{ Carbon\Carbon::parse($protocol->created_at)->format('d/m/Y H:i') }} (
        {{ auth()->user()->name }} )</p>
    @if($protocol->status == 1)
        <p><b>Prazo tratamento: </b> {{ Carbon\Carbon::parse($protocol->prompt)->format('d/m/Y') }}</p>
    @endif
    <p><b>Operadora: </b> {{ $protocol->operadora->name }}</p>
    <p><b>Linhas: </b> {{ $protocol->lines }}</p>
    <p><b>Tipo: </b> {{ $protocol->tag->name }}</p>
    <p><b>Descrição: </b> {{ $protocol->description }}</p>

    @if ($protocol->closure && $protocol->status == 3)
        <p><b>Resposta: </b> {{ $protocol->answer }}</p>
        <p><b>Status: </b>
            @switch($protocol->status)
                @case(1)
                    EM TRATAMENTO
                    @break
                @case(2)
                    CANCELADO
                    @break
                @case(3)
                    FINALIZADO
                    @break
            @endswitch
        </p>
        <p><b>Finalizado em: </b>
            {{ Carbon\Carbon::parse($protocol->closure)->format('d/m/Y') }}
        </p>
    @endif

</div>

<h4>Consulte seus protocolos <a
        href="http://localhost:8083/protocols/my-protocols/client/{{$protocol->client->uuid}}">Aqui</a></h4>
<p>
    Att.<br>
    <b>Ricardo Chomicz</b> - 42 98808-0544
    <br>
    Consultor de Negócios B2B
    42Telecom - Excêlencia em atendimento corporativo

</p>

</body>

</html>
