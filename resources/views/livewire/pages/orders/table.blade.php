<div>
    <div class="card">

        <div class="card-body">

            <h6><i class="fas fa-filter"></i> Filtros</h6>
            <div class="row">
                <div class="form-group col-sm-4 has-search">
                    <input wire:model.live="search" class="form-control" name="search"
                           placeholder="pesquisa por cliente...">
                </div>

                <div class="form-group col-sm-3" wire:ignore>
                    <x-select :options="$order_type" wire:model.live="type" title="Tipo" id="sel1"/>
                </div>

                <div class="form-group col-sm-4" wire:ignore>
                    <x-select :options="$tags" wire:model.live="status" multiple title="Status" id="sel2"
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
                        <caption><small>Oportunidades cadastradas <b>{{$data->count()}}</b></small></caption>
                        <thead class="bg-gray-light">
                        <tr>
                            <th>Cliente</th>
                            <th class="text-center">Solicitação</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Ativação</th>
                            <th class="text-center" width="5%">...</th>
                        </tr>
                        </thead>
                        <tbody>

                        @forelse($data as $d)
                            <tr>
                                <td class="align-middle">
                                    {{$d->client->name}}<br><small>{{$d->client->document}}</small>
                                </td>
                                <td class="text-center align-middle">
                                    {{$d->ordem->name}}<br><small>{{$d->operadora->name}}</small>
                                </td>
                                <td class="text-center align-middle">R$ {{number_format($d->total, 2, ',', '.')}}<br>
                                    {{( $d->qty )}}
                                </td>
                                <td class="text-center align-middle">
                                    @switch($d->status_id)
                                        @case(1)
                                            <span class='badge badge-primary'>{{$d->status->name}}</span>
                                            @break
                                        @case(2)
                                            <span class='badge badge-primary'>{{$d->status->name}}</span>
                                            @break
                                        @case(5)
                                            <span class='badge badge-danger'>{{$d->status->name}}</span>
                                            @break
                                        @case(6)
                                            <span class='badge badge-danger'>{{$d->status->name}}</span>
                                            @break
                                        @case(3)
                                            <span class='badge badge-success'>{{$d->status->name}}</span>
                                            @break
                                        @case(4)
                                            <span class='badge badge-success'>{{$d->status->name}}</span>
                                            @break
                                        @default()
                                            <span class='badge badge-secondary'>{{$d->status->name}}</span>
                                            @break
                                    @endswitch

                                    <br>
                                    @if($d->status->id != 3 && $d->status->id != 4)
                                        <small
                                            class="font-italic">Atualizado {{$d->updated_at->diffForHumans()}}</small>
                                    @endif
                                </td>

                                <td class="text-center align-middle">
                                    @if($d->activate)
                                        {{Carbon\Carbon::parse($d->activate)->format('d/m/Y')}}
                                    @else
                                        -
                                    @endif

                                </td>

                                <td class="text-center align-middle">


                                    <a href="{{route('orders.edit', $d->id)}}"
                                       class="btn btn-primary btn-sm tooltips" data-text="Editar">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>


                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center align-middle">
                                    Nenhum pedido cadastrado!
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center pagination-sm">
                        {!!  $data->links('vendor.pagination.bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
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
            $("#sel1, #sel2, #sel3").selectpicker('val', '') // Ou qualquer outra lógica de redefinição necessária
        });
    </script>
@endpush



