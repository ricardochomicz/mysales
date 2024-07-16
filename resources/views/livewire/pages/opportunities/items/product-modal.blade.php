<div>

    <div wire:ignore.self class="modal fade" data-backdrop="static" id="itemForm" aria-labelledby="itemForm">
        <div class="modal-dialog ">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Adicionar Produto</h5>
                    <button type="button" class="close" wire:click="closeModal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-sm-6">

                            <label for="operator_id">Operadora</label>
                            <select id="operator_id" class="form-control" wire:model.change="operator">
                                <option value="">Selecione</option>
                                @foreach($operators as $o)
                                    <option value="{{$o->id}}">{{$o->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="order_type_id">Solicitação</label>
                            <select id="order_type_id" class="form-control" wire:model="order_type_id">
                                <option value="">Selecione</option>
                                @foreach($order_types as $ot)
                                    <option value="{{$ot->id}}">{{$ot->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-12">
                            <input type="search" class="form-control" placeholder="Buscar Produto"
                                   wire:model.live="productSearch" id="product-search">
                            @if($products->isNotEmpty())
                                <ul id="product-list" class="list-group mt-2">
                                    @foreach($products as $product)
                                        <li class="list-group-item list-group-item-action" style="cursor:pointer"
                                            aria-current="true"
                                            wire:click="selectProduct({{ $product->id }})">
                                            <div class="d-flex w-100 justify-content-between">
                                                <span class="mb-1">
                                                    {{$product->name}}
                                                </span>
                                                <span>{{$product->price}}</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" wire:model="product_id" value="{{$product_id}}">
                    <input type="hidden" wire:model="itemId" value="{{$itemId}}">
                    <input type="hidden" wire:model="opportunity_id" value="{{$opportunity_id}}">


                    <div class="row">
                        <div class="form-group col-sm-6">
                            <x-input label="Valor" wire:model="price"/>
                        </div>
                        <div class="form-group col-sm-6">
                            <x-input label="Qtd" wire:model="qty"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-12">
                            <x-input wire:model="number" label="Número Linha"/>
                            <small>Para inserir vários números acrescente vírgula.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="addItem" class="btn btn-primary">Salvar</button>

                    <button type="button" class="btn btn-secondary" wire:click="$dispatch('closeModal')">Fechar
                    </button>
                </div>

            </div>
        </div>
    </div>
    <div class="">

        <input type="hidden" name="total" value="{{$totalValue}}">


        <button type="button" wire:click="openModal"
                class="btn btn-sm btn-primary mt-3 tooltips" data-text="Adicionar Produto">
            <i class="fas fa-plus"></i>
        </button>

        {{--            <button type="button" class="btn btn-sm btn-danger" wire:click="clearItems">--}}
        {{--                <i class="fas fa-times"></i>--}}
        {{--            </button>--}}
        <div class="form-group col-sm-3 float-right has-search">
            <input wire:model.live="search" class="form-control" name="search"
                   placeholder="pesquisa por linha...">
        </div>

        @if($data)
            <table class="table caption-top">
                <thead>
                <tr>
                    <th>Nr. Linha</th>
                    <th>Plano</th>
                    <th>Valor Unit</th>
                    <th class="text-center">SubTotal</th>
                    <th class="text-center">...</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($data as $index => $item)
                    @php
                        $subtotal = isset($item['subtotal']) ? $item['subtotal'] : $item['price'] * $item['qty'];
                    @endphp
                    <tr>
                        <td>
                            <input type="hidden" name="dynamicFields[{{ $index }}][id]" value="{{$item['id'] ?? ''}}">

                            {{ $item['number'] }}<br>
                            <small>{{ $this->getOrderTypeName($item['order_type_id']) }}</small>
                            <input type="hidden" name="dynamicFields[{{ $index }}][number]" value="{{$item['number']}}">
                            <input type="hidden" name="dynamicFields[{{ $index }}][order_type_id]"
                                   value="{{$item['order_type_id']}}">
                        </td>

                        <td>
                            {{$this->getProductName($item['product_id'])}}
                            <input type="hidden" name="dynamicFields[{{ $index }}][product_id]"
                                   value="{{$item['product_id']}}">
                        </td>
                        <td>
                            {{ $item['price'] }} ( x {{ $item['qty'] }} )
                            <input type="hidden" name="dynamicFields[{{ $index }}][qty]" value="{{$item['qty']}}">
                            <input type="hidden" name="dynamicFields[{{ $index }}][price]" value="{{$item['price']}}">
                            <input type="hidden" name="dynamicFields[{{ $index }}][factor]" value="{{$item['factor']}}">

                            <input type="hidden" name="qty" value="{{$totalQty}}">
                        </td>
                        <td class="text-center">


                            R$ {{ number_format($subtotal,2,',', '.') }}

                        </td>
                        <td class="text-center">
                            <button class="btn btn-primary btn-sm" type="button" wire:click="editItem({{ $index }})"><i
                                    class="fas fa-sync-alt"></i>
                            </button>
                            <button type="button" wire:click="removeItem({{ $index }})" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            <div class="pagination justify-content-center pagination-sm">
                {!!  $data->links('vendor.pagination.bootstrap-4') !!}
            </div>

            <div class="col-6 border rounded mb-2 bg-gradient-light">
                <p class="lead">Resumo Oportunidade</p>
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>Linhas:</th>
                            <td class="text-center">{{$totalQty}}</td>
                        </tr>
                        <tr>
                            <th>Total Oportunidade:</th>
                            <td class="text-center">R$ {{ number_format($totalValue, 2, ',', '.') }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>

@pushonce('scripts')
    <script src="{{ asset('assets/plugins/inputmask/inputmask.js') }}"></script>
    <script>
        function formatCurrency(input) {
            let valor = input.value;
            valor = valor.replace(/\D/g, '');
            valor = (parseInt(valor) / 100).toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            if (valor === 'NaN') {
                input.value = '';
            } else {
                input.value = valor;
            }
        }

        Livewire.on('openModal', () => {
            $('#itemForm').modal('show');
        });

        Livewire.on('closeModal', () => {
            $('#itemForm').modal('hide');
        });
    </script>
@endpushonce
