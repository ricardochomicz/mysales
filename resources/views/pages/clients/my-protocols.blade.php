<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <title>Meus Protocolos</title>
</head>

<body>
<div class="container mt-5">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h4 mb-0 text-gray-800"><i class="fas fa-store mr-2"></i> Cliente: <b>{{ $client->name }}</b></h1>

    </div>


    <div class="card mb-4">
        <div class="card-header">Histórico de Protocolos

        </div>
        <div class="card-body p-0">
            <!-- Billing history table-->
            <div class="table-responsive table-billing-history">
                <table class="table mb-0">
                    <thead>
                    <tr>
                        <th class="border-gray-200" scope="col">Operadora</th>
                        <th class="border-gray-200" scope="col">Número Protocolo</th>
                        <th class="border-gray-200" scope="col">Tipo</th>
                        <th class="border-gray-200" scope="col">Status</th>
                        <th class="border-gray-200" scope="col">Aberto em</th>
                        <th class="border-gray-200" scope="col">Finalizado em</th>
                        <th class="border-gray-200" scope="col">Arquivo</th>
                        <th class="border-gray-200 text-center" scope="col">Ação</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($protocols as $protocol)
                        <tr>
                            <td class="align-middle">{{ $protocol->operadora->name }}</td>
                            <td class="align-middle">{{ $protocol->number }}</td>

                            <td class="align-middle">{{ $protocol->tag->name }}</td>
                            <td class="align-middle">
                                @if($protocol->status == 1)
                                    <span
                                        class="badge {{ 'bg-secondary' }} text-dark">EM TRATAMENTO</span>
                                @elseif($protocol->status == 2)
                                    <span
                                        class="badge {{ 'bg-danger' }} text-dark">CANCELADO</span>
                                @else
                                    <span
                                        class="badge {{ 'bg-success' }} text-dark">FINALIZADO</span>
                                @endif
                            </td>

                            <td class="align-middle">
                                {{ Carbon\Carbon::parse($protocol->created_at)->format('d/m/Y H:i') }}<br>
                            </td>
                            <td class="align-middle">{{ Carbon\Carbon::parse($protocol->prompt)->format('d/m/Y') }}</td>
                            <td class="align-middle">
                                @if($protocol->archive)
                                    <a href="{{ asset('storage/' . $protocol->archive) }}"
                                       target="_blank">Download</a>
                                @else
                                    <small>Sem anexo.</small>
                                @endif

                            </td>
                            <td class="align-middle text-center">
                                <a href="javascript:void(0)" class="btn btn-sm btn-primary" data-toggle="modal"
                                   data-target="#exampleModal{{ $protocol->id }}"
                                   title="Ver Protocolo">Visualizar</a>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                Nenhum protocolo encontrado...
                            </td>
                        </tr>
                    @endforelse


                    </tbody>
                </table>
                <div class="pagination pagination-sm">
                    {{ $protocols->links() }}
                </div>
            </div>

        </div>
    </div>
    <button class="btn btn-info" onclick="window.print()">Imprimir</button>

    @foreach ($protocols as $protocol)
        <div class="modal fade" id="exampleModal{{ $protocol->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detalhes Protocolo
                            <br><small class="font-weight-bold">#{{ $protocol->number }}</small>

                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <b>Aberto em:</b> {{ Carbon\Carbon::parse($protocol->created_at)->format('d/m/Y H:i') }} <br>
                        @if($protocol->status == 1)
                            <b>Prazo
                                tratamento: </b> {{ Carbon\Carbon::parse($protocol->prompt)->format('d/m/Y') }}</br>
                        @endif
                        <b>Operadora:</b> {{ $protocol->operadora->name }}<br>
                        <b>Linhas:</b> {{ $protocol->lines }}<br>
                        <b>Tipo:</b> {{ $protocol->tag->name }}<br>
                        <b>Status:</b>
                        @if($protocol->status == 1)
                            <span
                                class="badge {{ 'bg-secondary' }} text-dark">EM TRATAMENTO</span>
                        @elseif($protocol->status == 2)
                            <span
                                class="badge {{ 'bg-danger' }} text-dark">CANCELADO</span>
                        @else
                            <span
                                class="badge {{ 'bg-success' }} text-dark">FINALIZADO</span>
                        @endif
                       <br>
                        <b>Descrição:</b> {{ $protocol->description }}<br>

                        @if ($protocol->status == 3)
                            <b>Resposta: </b> {{ $protocol->answer }}</br>
                            <b>Finalizado em: </b>
                            {{ Carbon\Carbon::parse($protocol->closure)->format('d/m/Y') }}

                        @endif

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
</script>
</body>

</html>
