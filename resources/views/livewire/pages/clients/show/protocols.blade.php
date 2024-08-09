<div>
    <table class="table">
        <thead>
            <tr>
                <th>Protocolo</th>
                <th class="text-center">Operadora</th>
                <th class="text-center">Tipo Protocolo</th>
                <th class="text-center">Status</th>
                <th class="text-center">Arquivo</th>
                <th class="text-center">Criado em</th>
                <th class="text-center">Finalizado em</th>
                <th class="text-center">...</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($protocols as $protocol)
                <tr>
                    <td>
                        {{ $protocol->number }}

                    </td>
                    <td class="text-center">
                        {{ $protocol->operadora->name }}
                    </td>
                    <td class="text-center">
                        {{ $protocol->tag->name }}
                    </td>
                    <td class="text-center">
                        {!! $this->getNameStatus($protocol->status) !!}
                    </td>
                    <td class="text-center">
                        @if ($d->archive)
                            <a href="{{ asset('storage/' . $protocol->archive) }}" target="_blank">Download</a>
                        @else
                            <small>Sem anexo.</small>
                        @endif

                    </td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($protocol->created_at)->format('d/m/Y') }}<br>
                        @if (!$protocol->closure)
                            <small class="text-danger">Expira
                                em {{ Carbon\Carbon::parse($protocol->prompt)->format('d/m/Y') }}</small>
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($protocol->closure)
                            {{ Carbon\Carbon::parse($protocol->closure)->format('d/m/Y') }}
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="javascript:void(0)" class="btn btn-circle btn-sm btn-secondary" data-toggle="modal"
                            data-target="#modalProtocol{{ $protocol->id }}" title="Ver Protocolo"><i
                                class="fas fa-eye"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center pagination-sm">
        {!! $protocols->links('vendor.pagination.bootstrap-4') !!}
    </div>

    @foreach ($protocols as $protocol)
        <div class="modal fade" id="modalProtocol{{ $protocol->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detalhes Protocolo
                            <br><small class="font-weight-bold">#{{ $protocol->number }}</small><br>
                            @if (!$protocol->closure)
                                <small class="text-danger">Expira
                                    em {{ Carbon\Carbon::parse($protocol->prompt)->format('d/m/Y') }}</small>
                            @endif
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <b>Aberto em:</b> {{ Carbon\Carbon::parse($protocol->created_at)->format('d/m/Y H:i') }} (
                        {{ $protocol->user->name }} )<br>
                        <b>Operadora:</b> {{ $protocol->operadora->name }}<br>
                        <b>Linhas:</b> {{ $protocol->lines }}<br>
                        <b>Tipo:</b> {{ $protocol->tag->name }}<br>
                        <b>Status:</b>
                        {!! $this->getNameStatus($protocol->status) !!}<br>
                        <b>Descrição:</b> {{ $protocol->description }}<br>
                        <b>Resposta:</b> {{ $protocol->answer }}<br>
                        @if ($protocol->closure)
                            <b>Finalizado em:</b> {{ Carbon\Carbon::parse($protocol->closure)->format('d/m/Y') }}<br>
                        @endif

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary" data-dismiss="modal"
                            wire:click="sendProtocolEmail({{ $protocol->id }})">Enviar E-mail
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
