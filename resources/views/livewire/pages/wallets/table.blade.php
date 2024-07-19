<div>
    <div class="card">

        <div class="card-body">

            <h6><i class="fas fa-filter"></i> Filtros</h6>
            <div class="row">
                <div class="form-group col-sm-4 has-search">
                    <input wire:model.live="search" class="form-control" name="search"
                           placeholder="pesquisa por cliente...">
                </div>
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
                        <caption><small>Clientes na Carteira <b>{{$data->count()}}</b></small></caption>
                        <thead class="bg-gray-light">
                        <tr>
                            <th>Cliente</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Classificação</th>
                            <th class="text-center">Ativação</th>
                            <th class="text-center">Renovação</th>
                            <th class="text-center">M</th>
                            <th class="text-center" width="5%">...</th>
                        </tr>
                        </thead>
                        <tbody>

                        @forelse($data as $d)
                            <tr>
                                <td>
                                    {{$d->client->name}}<br><small>{{$d->client->document}}</small>
                                </td>
                                <td class="text-center">
                                    R$ {{number_format($d->total, 2, ',', '.')}}<br>
                                      {{$d->qty}}
                                </td>
                                <td class="text-center">
                                    {{$d->client->classification->name}}-{{$d->client->classification->months}}
                                </td>

                                <td class="text-center">
                                    {{Carbon\Carbon::parse($d->activate)->format('d/m/Y')}}

                                </td>
                                <td class="text-center">
                                    {{Carbon\Carbon::parse($d->renew_date)->format('d/m/Y')}}

                                </td>
                                <td class="text-center">
                                    {{ floor(\Carbon\Carbon::now()->diffInMonths(\Carbon\Carbon::parse($d->renew_date))+1) }}
                                </td>

                                <td class="text-center">
                                    <a href="javascript:void(0)" onclick="ativaDesativa('{{route('wallets.clone',  $d->id)}}', 'renovar', '{{$d->client->name}}')"
                                       class="btn btn-primary btn-sm tooltips" data-text="Renovar Pedido">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">
                                    Carteira vazia!
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



