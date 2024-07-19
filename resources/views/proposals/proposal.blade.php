<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/fontawesome.min.css"
          integrity="sha512-OdEXQYCOldjqUEsuMKsZRj93Ht23QRlhIb8E/X0sbwZhme8eUw6g8q7AdxGJKakcBbv7+/PX0Gc2btf7Ru8cZA=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/solid.min.css"
          integrity="sha512-jQqzj2vHVxA/yCojT8pVZjKGOe9UmoYvnOuM/2sQ110vxiajBU+4WkyRs1ODMmd4AfntwUEV4J+VfM6DkfjLRg=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <title>Proposta Comercial {{@$opportunity->operadora->name}} EMPRESAS</title>
</head>

<body>

<div class="wrapper">
    <div class="text-center" id="container-imagem1">
        <img src="{{ asset('../assets/images/capa.png') }}" class="rounded img-fluid" alt="...">
    </div>

    <br>
    <!-- Main content -->
    <section class="invoice p-2">
        <!-- title row -->
        <div class="row">
            <div class="col-12">
                <h2 class="page-header">
                    @if(@$opportunity->operator->name == 'CLARO')
                        Proposta Comercial Claro Empresas
                    @elseif(@$opportunity->operator->name == 'TIM')
                        Proposta Comercial Tim Empresas
                    @elseif(@$opportunity->operator->name == 'VIVO')
                        Proposta Comercial Vivo Empresas
                    @else
                        Proposta Comercial
                    @endif
                    <small class="float-right">Data: {{ date('d/m/Y') }}</small>
                </h2>
            </div>
            <!-- /.col -->
        </div>
        <br>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                Empresa
                <address>
                    <strong>{{ @$opportunity->client->name }}</strong><br/>
                    {{ @$opportunity->client->document }}<br/>
                    {{ @$opportunity->client->address }}, {{ @$opportunity->client->number }}<br/>
                    {{ @$opportunity->client->city }}-{{ @$opportunity->client->state }}<br/>
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                A/C
                <address>
                    <strong>{{ @@$opportunity->client->persons[0]->name }}</strong><br/>
                    Telefone: {{ @@$opportunity->client->persons[0]->phone }}<br/>
                    Email: {{ @@$opportunity->client->persons[0]->email }}
                </address>
            </div>

        </div>
        <!-- /.row -->

        <!-- Table row -->
        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Linha</th>
                        <th>Plano</th>
                        <th>Descrição</th>
                        <th class="text-center">Qtd</th>
                        <th>Valor Unit</th>
                        <th>Valor Total</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach (@$opportunity->items_opportunity as $item)
                        <tr>
                            <td>{{ $item->type->name }}</td>
                            <td>{{ $item->number }}</td>
                            <td>{{ $item->product->name }}</td>
                            <td>
                                {{ $item->product->description }}
                            </td>
                            <td class="text-center">{{ $item->qty }}</td>
                            <td>R$ {!! number_format($item->price, 2, ',', '.') !!}</td>
                            <td>R$ {{ $item->qty * $item->price }}</td>
                        </tr>

                    @endforeach

                    </tbody>
                </table>
                <br><br>

            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row ">
            <!-- accepted payments column -->
            <div class="col-8">
                <p class="lead">Informações:</p>
                @if(@$opportunity->operadora->name == 'CLARO')
                    <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                        Proposta válida por 5 dias.<br>
                        Ligações ilimitadas para qualquer operadora local e DDD usando
                        o 21<br>
                        Passaportes Américas e Mundo: Ligações ilimitadas e
                        5GB de internet para navegar no exterior.<br>
                        Banda larga de ultra velocidade com Wi-Fi Plus +
                        aparelhos conectados e maior alcance<br>
                        Bônus de 30GB de internet compartilhada na convergência<br>
                        Permenência do contrato: 24 meses
                    </p>
                @elseif(@$opportunity->operadora->name == 'TIM')
                    <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                        Proposta válida por 5 dias.<br>
                        Ligações ilimitadas para qualquer operadora local e DDD usando
                        o 41<br>
                        Tim Fibra internet com ultra velocidade <br>
                        Permenência do contrato: 24 meses
                    </p>
                @elseif(@$opportunity->operadora->name == 'VIVO')
                    <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                        Proposta válida por 5 dias.<br>
                        Ligações ilimitadas para qualquer operadora local e DDD usando
                        o 15<br>
                        Vivo Fibra internet com ultra velocidade <br>
                        Permenência do contrato: 24 meses
                    </p>
                @else
                    <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                        Proposta válida por 5 dias.<br>
                    </p>
                @endif
            </div>
            <!-- /.col -->
            <div class="col-4">

                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th style="width:50%">Proposta:</th>

                            <td>R${{ number_format(@$opportunity->total, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Aparelhos (24x)</th>
                            <td>
                                R$

                            </td>
                        </tr>
                        <tr>
                            <th>Total Mensal:</th>
                            <td>R${!! number_format(@$opportunity->total, 2,',','.') !!}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <br>
        <div class="row mt-5">
            <div class="col-12">
                <strong>{{ @$opportunity->client->user->name }}</strong><br/>
                Cel: <a href="https://wa.me/55{{ @$opportunity->client->user->phone }}">{{ @$opportunity->client->user->phone }} <i class="fas fa-comment-dots"></i></a> <br>
                E-mail: <a href="mailto:{{ @$opportunity->client->user->email }}"> {{ @$opportunity->client->user->email }} <i class="fas fa-envelope"></i></a> <br>
                Ponta Grossa-PR <br>
                <img src="{{ asset('../assets/images/lt.png') }}" alt="Logo" width="250px">

            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>

{{--<script>--}}
{{--    window.print()--}}

{{--</script>--}}

</body>

</html>
