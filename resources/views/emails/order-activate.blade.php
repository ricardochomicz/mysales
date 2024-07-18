<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500&display=swap" rel="stylesheet">
    <title>Pedido Faturado</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito&display=swap');
        html, body{
            font-family: 'Nunito', sans-serif;
        }
        .table td, .table th {
            padding: .75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            background-color: transparent;
        }
    </style>

</head>

<body style="font-family: 'Verdana', sans-serif; font-size: 15px">


<h3>Olá, <b>{{$order->client->persons[0]->name}}.</b><br>
    {{$order->client->name}}
</h3>
<p>
    Seu pedido com a <b>{{ $order->operadora->name }}</b> faturou :)
</p>


<div style="border:1px solid #cccc; border-radius: 6px; padding: 5px">
    <p><b>Consultor: </b> {{ $order->user->name }} - {{$order->user->phone}}</p>
    <p><b>Número Pedido: </b> {{ $order->identify }}</p>
    <p><b>Criado em: </b> {{ Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }} </p>
    <p><b>Qtd linhas: </b> {{ $order->qty }} </p>
    <p><b>Total Pedido: </b> R$ {!! number_format($order->total, 2, ',', '.') !!} </p>
    <p><b>Data Ativação: </b> {{ Carbon\Carbon::parse($order->activate)->format('d/m/Y') }}</p>


    <table class="table table-borderless" style="font-size: 15px">
        <thead>
        <tr>
            <th style="text-align: center">Tipo</th>
            <th class="text-center" style="text-align: center">Linha</th>
            <th style="text-align: left">Plano</th>
            <th class="text-center" style="text-align: center">Qtd</th>
            <th class="text-center" style="text-align: center">Valor Unit</th>
            <th class="text-center" style="text-align: center">Valor Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->items_opportunity as $item)
            <tr>
                <td style="text-align: center">{{$item->type->name}}</td>
                <td class="text-center"  >{{$item->number}}</td>
                <td style="text-align: left">{{$item->product->name}}</td>
                <td class="text-center" style="text-align: center">{{$item->qty}}</td>
                <td class="text-center" style="text-align: center">R$ {!! number_format($item->price, 2, ',', '.') !!}</td>
                <td class="text-center" style="text-align: center">R$ {!! number_format($item->qty * $item->price, 2, ',', '.') !!}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>


<p>
    Att.<br>
    <b>Ricardo Chomicz</b> - 42 98808-0544
    <br>
    Consultor de Negócios B2B
    42Telecom - Excêlencia em atendimento corporativo

</p>

</body>

</html>
