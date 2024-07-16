<div>
    <div class="card">

        <div class="card-body">

            <h6><i class="fas fa-filter"></i> Filtros</h6>
            <div class="row">
                <div class="form-group col-sm-4 has-search">
                    <input wire:model.live="search" class="form-control" name="search"
                           placeholder="pesquisa por cliente...">
                </div>

                <div class="form-group col-sm-4" wire:ignore>
                    <x-select :options="$tags" wire:model.live="tag" title="Tipo" id="sel1"
                              data-actions-box="true"/>
                </div>
                <div class="form-group col-sm-4" wire:ignore>
                    <x-select :options="$protocolStatus" wire:model.live="status" title="Status" id="sel2"
                              data-actions-box="true"/>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-2">
                    <x-input type="date" wire:model.lazy="dt_ini" class="tooltips" data-text="Data Início"/>
                </div>
                <div class="form-group col-sm-2">
                    <x-input type="date" wire:model.lazy="dt_end" class="tooltips" data-text="Data Fim"/>
                </div>
                <div class="form-group col-sm-2">
                    <button type="button" class="btn btn-secondary" wire:click="clearFilter">Limpar Filtros</button>
                </div>
            </div>

            <div class="small-box shadow-none">
                <x-loader wire:loading.delay wire:loading.class="overlay"></x-loader>

                <div class="table-responsive rounded">

                    <table class="table table-borderless table-striped table-hover">
                        <caption><small>Protocolos cadastrados <b>{{$data->count()}}</b></small></caption>
                        <thead class="bg-gray-light">

                        <tr>
                            <th>Cliente</th>
                            <th class="text-center">Protocolo</th>
                            <th class="text-center">Status</th>
                            <th>Criado em</th>
                            <th class="text-center">Ação</th>
                        </tr>

                        </thead>
                        <tbody>

                        @forelse($data as $d)
                            <tr @class(['text-success' => $d->status == 3, 'text-danger' => $d->status == 2])>
                                <td class="align-middle">
                                    {{ $d->client->name }}<br>
                                    <small>Aberto por: {{ $d->user->name }}</small>
                                </td>
                                <td class="align-middle text-center">
                                    {{ $d->number }}<br>
                                    @if($d->status == 1)
                                        <small>Prazo: {{ Carbon\Carbon::parse($d->prompt)->format('d/m/Y') }}</small>
                                    @endif
                                    @if (Carbon\Carbon::now() > $d->prompt && $d->status== 1)
                                        - <small class="text-danger"><i class="fas fa-exclamation-triangle"></i> Vencido</small>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    {!! $this->getNameStatus($d->status) !!}<br>
                                    <small>
                                        @if ($d->status == 2 || $d->status == 3)
                                            {{ Carbon\Carbon::parse($d->closure)->format('d/m/Y') }}
                                        @endif
                                    </small>
                                </td>

                                <td class="align-middle">
                                    {{ Carbon\Carbon::parse($d->closure)->format('d/m/Y H:i') }}<br>
                                    @if ($d->status == 1)
                                        <small
                                            class="font-italic {{ Carbon\Carbon::now() > $d->prompt ? 'text-danger' : '' }}"></small>
                                    @endif
                                </td>
                                <td class="text-center align-middle">

                                    <a href="{{ route('protocols.edit', $d->id) }}"
                                       class="btn btn-circle btn-sm btn-primary" title="Editar"><i
                                            class="fas fa-pen"></i></a>
                                    <a href="javascript:void(0)" class="btn btn-circle btn-sm btn-secondary"
                                       data-toggle="modal" data-target="#modalProtocol{{ $d->id }}"
                                       title="Ver Protocolo"><i class="fas fa-eye"></i></a>

                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    Nenhum protocolo cadastrado!
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {!!  $data->links('vendor.pagination.bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($data as $protocol)
        <div class="modal fade" id="modalProtocol{{ $protocol->id }}" tabindex="-1">
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

                        <b>Aberto em:</b> {{ Carbon\Carbon::parse($protocol->created_at)->format('d/m/Y H:i') }} (
                        {{ $protocol->user->name }} )<br>
                        <b>Operadora:</b> {{ $protocol->operadora->name }}<br>
                        <b>Linhas:</b> {{ $protocol->lines }}<br>
                        <b>Tipo:</b> {{ $protocol->tag->name }}<br>
                        <b>Status:</b>
                        {!! $this->getNameStatus($protocol->status) !!}<br>
                        <b>Descrição:</b> {{ $protocol->description }}<br>
                        <b>Resposta:</b> {{ $protocol->answer }}<br>
                        <b>Finalizado em:</b> {{ Carbon\Carbon::parse($protocol->closure)->format('d/m/Y') }}<br>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary" data-dismiss="modal"
                                wire:click="sendProtocolEmail({{ $protocol->id }})">Enviar E-mail</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>


@push('scripts')
    <script src="{{asset('assets/js/bootstrap-select.min.js')}}"></script>
    <script>
        document.addEventListener('livewire:init', function () {
            $('.selectpicker').selectpicker();

            Livewire.hook('message.processed', (message, component) => {
                $('.selectpicker').selectpicker('refresh');
            });
        });
        Livewire.on('resetSelectpicker', function () {
            $("#sel1, #sel2").selectpicker('val', '') // Ou qualquer outra lógica de redefinição necessária
        });
    </script>
@endpush




