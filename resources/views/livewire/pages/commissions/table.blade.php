<div>
    <div class="card">

        <div class="row">
            <div class="col-md-3 col-sm-6 col-12 mt-2">
                <div class="info-box ml-2 p-2">
                    <span class="info-box-icon bg-success"><i class="fas fa-file-invoice-dollar"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Comissão</span>
                        <span class="info-box-number">R$ {{ number_format($totalAmount, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">

            <h6><i class="fas fa-filter"></i> Filtros</h6>
            <div class="row">
                <div class="col-sm-2" wire:ignore>
                    <select wire:model.change="groupBy" class="selectpicker">
                        <option value="groupBy">Resumido</option>
                        <option value="detail">Detalhado</option>
                    </select>
                </div>
                <div class="form-group col-sm-3 has-search">
                    <input wire:model.live="search" class="form-control" name="search"
                           placeholder="pesquisa por cliente...">
                </div>
                <div class="form-group col-sm-2">
                    <x-input type="month" wire:model.lazy="month" class="tooltips" data-text="Por mês"/>
                </div>
                <div class="form-group col-sm-2" wire:ignore>
                    <x-select :options="$operators" wire:model.live="operator" title="Operadora" id="sel1"/>
                </div>
                <div class="form-group col-sm-2">
                    <button type="button" class="btn btn-secondary" wire:click="clearFilter">Limpar Filtros</button>
                </div>

            </div>


            <div class="small-box shadow-none">
                <x-loader wire:loading.delay wire:loading.class="overlay"></x-loader>

                <div class="table-responsive rounded">

                    <table class="table table-borderless table-striped table-hover" style="font-size: 0.938rem">

                        <thead class="bg-gray-light">
                        <tr>
                            <th>Cliente</th>
                            <th class="text-center">Tipo</th>
                            @if($groupBy === 'detail')
                                <th class="text-center">Número</th>
                            @endif
                            <th>Plano</th>
                            <th class="text-center">Qtd</th>
                            <th class="text-center">Valor</th>
                            <th class="text-center">Fator</th>
                            <th class="text-center">Comissão</th>
                        </tr>
                        </thead>
                        <tbody>

                        @if ($groupBy === 'detail')
                            @foreach($commissions as $commission)
                                @foreach($commission['items_opportunity'] as $item)

                                    <tr>
                                        <td>{{ $commission['client']['name'] }}<br><small><b>#{{$item->opportunity->identify}}</b></small></td>

                                        <td class="text-center">{{ $item['type']['name'] }}</td>
                                        <td class="text-center">{{ $item['number'] }}</td>
                                        <td>{{ $item['product']['name'] }}</td>
                                        <td class="text-center">{{ $item['qty'] }}</td>
                                        <td class="text-center">R$ {{ number_format($item['price'], 2, ',', '.') }}</td>
                                        <td class="text-center">{{ $item['factor'] }}%</td>
                                        <td>
                                            R$ {{ number_format($item['qty'] * $item['price'] * $item['factor'], 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            @endforeach

                        @else
                            @foreach($items as $group)
                                @foreach($group as $item)
                                    <tr>
                                        <td>{{ $item['client'] }}<br><small><b>#{{$item['identify']}}</b></small></td>
                                        <td class="text-center">{{ $item['type'] }}<br>{{ $item['operator'] }}</td>
                                        <td>{{ $item['product'] }}</td>
                                        <td class="text-center">{{ $item['qty'] }}</td>
                                        <td class="text-center">R$ {{ number_format($item['price'], 2, ',', '.') }}</td>
                                        <td class="text-center">{{ $item['factor'] }}%</td>
                                        <td class="text-center">R$ {{ number_format($item['amount'], 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{--                        {!!  $commissions->links('vendor.pagination.bootstrap-4') !!}--}}
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
            $("#sel1").selectpicker('val', '') // Ou qualquer outra lógica de redefinição necessária
        });
    </script>
@endpush



