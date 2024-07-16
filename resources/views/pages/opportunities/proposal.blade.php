@extends('adminlte::page')

@section('title', 'Proposta')

@section('content_header')
    <div class="d-flex bd-highlight">
        <div class="mr-auto p-1 bd-highlight"><h4>Proposta Comercial</h4></div>
    </div>
@stop

@section('content')

    <div class="invoice p-3 mb-3">

        <div class="row">
            <div class="col-12">
                <h4>
                    <i class="fas fa-globe"></i> Proposta Comercial {{$opportunity->operadora->name}} EMPRESAS
                    <small class="float-right">Data: {{Carbon\Carbon::now()->format('d/m/Y')}}</small>
                </h4>
            </div>

        </div>

        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                Cliente
                <address>
                    <strong>{{$opportunity->client->name}}</strong><br>
                    {{$opportunity->client->document}}<br>
                    {{$opportunity->client->city}}-{{$opportunity->client->state}} <br>
                </address>
            </div>

            <div class="col-sm-4 invoice-col">
                A/C
                <address>
                    <strong>{{@$opportunity->client->persons[0]->name}}</strong><br>
                    {{@$opportunity->client->persons[0]->email}}<br>
                    {{@$opportunity->client->persons[0]->phone}}<br>
                </address>
            </div>
        </div>

        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Nr.Linha</th>
                        <th>Plano</th>
                        <th>R$ Unit</th>
                        <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($opportunity->items_opportunity as $item)
                    <tr>
                        <td>{{$item->type->name}}</td>
                        <td>{{$item->number}}</td>
                        <td>{{$item->product->name}}</td>
                        <td>R$ {{number_format($item->price, 2, ',', '.')}}</td>
                        <td>R$ {{ number_format($item->price * $item->qty, 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>

        <div class="row">

            <div class="col-8">
                <p class="lead">Informações:</p>
                @if($opportunity->operadora->name == 'CLARO')
                    <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">

                        Ligações ilimitadas para qualquer operadora local e DDD usando
                        o 21<br>
                        Passaportes Américas e Mundo: Ligações ilimitadas e
                        5GB de internet para navegar no exterior.<br>
                        Banda larga de ultra velocidade com Wi-Fi Plus +
                        aparelhos conectados e maior alcance<br>
                        Bônus de 30GB de internet compartilhada na convergência<br>
                        Permenência do contrato: 24 meses
                    </p>
                @elseif($opportunity->operadora->name == 'TIM')
                    <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">

                        Ligações ilimitadas para qualquer operadora local e DDD usando
                        o 41<br>
                        Tim Fibra internet com ultra velocidade <br>
                        Permenência do contrato: 24 meses
                    </p>
                @elseif($opportunity->operadora->name == 'VIVO')
                    <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">

                        Ligações ilimitadas para qualquer operadora local e DDD usando
                        o 15<br>
                        Vivo Fibra internet com ultra velocidade <br>
                        Permenência do contrato: 24 meses
                    </p>
                @else
                    <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                        <b>Proposta válida por 5 dias.</b><br>
                    </p>
                @endif
                <b>Proposta válida por 5 dias.</b>
            </div>

            <div class="col-4">
                <p class="lead">Totais</p>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th style="width:50%">Proposta:</th>

                            <td>R${{ number_format($opportunity->total, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Aparelhos (24x)</th>
                            <td>
                                R$

                            </td>
                        </tr>
                        <tr>
                            <th>Total Mensal:</th>
                            <td>R${!! number_format($opportunity->total, 2,',','.') !!}</td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>

        <div class="row mt-5">
            <div class="col-12">
                <strong>{{ $opportunity->user->name }}</strong><br/>
                Cel: {{ $opportunity->user->phone }}<br>
                E-mail: {{ $opportunity->user->email }}<br>
                Ponta Grossa-PR<br>
                <img src="../../images/lt.png" alt="Logo" width="250px">
            </div>
        </div>
        <div class="row no-print">
            <div class="col-12">
                <a href="javascript:void(0)" onclick="printPage()" rel="noopener" class="btn btn-default float-right"><i
                        class="fas fa-print"></i> Print</a>
                <a href="javascript:void(0)" type="button" class="btn btn-primary float-right" style="margin-right: 5px;" onclick="getUrl('{{$opportunity->uuid}}')">
                    <i class="fas fa-link"></i> Link Proposta
                </a>
            </div>
        </div>
    </div>

@stop

@push('scripts')
    <script src="{{ asset('assets/js/notify.min.js') }}"></script>
    <script>
        function printPage() {
            window.print();
        }

        function getUrl(uuid){
            console.log(uuid)
            const url = `https://mysales.42telecom.com.br/my-proposal/${uuid}`;

            // Criar um elemento de input temporário
            const input = document.createElement('input');
            input.value = url;
            document.body.appendChild(input);

            // Selecionar e copiar o conteúdo do input
            input.select();
            document.execCommand('copy');

            // Remover o input temporário
            document.body.removeChild(input);
            $.notify('URL copiada para a área de transferência!',{
                position: 'bottom center',
                className: 'info',
            });
        }
    </script>
@endpush
