<div class="mt-3 mb-3" >
{{--    <button type="button" wire:click="openModal"--}}
{{--            class="btn btn-primary">--}}
{{--        Adicionar Produto--}}
{{--    </button>--}}
{{--    <button type="button" class="btn btn-secondary" wire:click="clearItems">Limpar Tudo</button>--}}
{{--    <div class="form-group col-sm-3 has-search float-right">--}}
{{--        <input wire:model.live="search" class="form-control" name="search"--}}
{{--               placeholder="pesquisa por linha...">--}}
{{--    </div>--}}

{{--    <div wire:ignore.self class="modal fade" id="itemForm" aria-labelledby="exampleModalLabel"--}}
{{--         aria-hidden="true">--}}
{{--        <div class="modal-dialog ">--}}
{{--            <div class="modal-content">--}}

{{--                <div class="modal-header">--}}
{{--                    <h5 class="modal-title">{{ $editIndex ? 'Editar Item' : 'Adicionar Item' }}</h5>--}}
{{--                    <button type="button" class="close" wire:click="closeModal" aria-label="Close">--}}
{{--                        <span aria-hidden="true">&times;</span>--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--                <div class="modal-body">--}}
                    <div class="row">
                        <div class="form-group col-sm-6">

                            <x-select :options="$operators" id="operator_id" class="sel" label="Operadora"
                                      wire:model="operator_id"
                                      title="Selecione"/>
                        </div>

                        <div class="form-group col-sm-6">
                            <x-select :options="$order_types" id="order_type" class="sel" label="Tipo Pedido"
                                      wire:model="order_type_id"
                                      title="Selecione"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-12">
                            <x-select :options="$products" id="product_id" label="Produto" wire:model="product_id"
                                      title="Selecione"/>


                        </div>
                    </div>
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
                    {{--                   / <button type="button" class="btn btn-secondary" wire:click="$dispatch('closeModal')">Fechar</button>--}}
                </div>

{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}


    <!-- Tabela Temporária -->
    <div class="mt-4">
        @if($totalQty)
            <div class="description-block col-sm-12">
                <span class="description-percentage text-success"> {{ $totalQty }}</span>
                <h5 class="description-header">R$ {{ $totalValue }}</h5>
                <span class="description-text">TOTAL OPORTUNIDADE</span>
            </div>

        @endif
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
            @foreach ($items as $index => $item)
                <tr>
                    <td>
                        {{ $item['number'] }}<br>
                        <small>{{ $this->getOrderTypeName($item['order_type_id']) }}</small>
                        <input type="hidden" name="dynamicFields[{{ $index }}][number]" value="{{$item['number']}}">
                        <input type="hidden" name="dynamicFields[{{ $index }}][type_id]"
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
                        <input type="hidden" name="total" value="{{$totalValue}}">
                        <input type="hidden" name="lines" value="{{$totalQty}}">
                    </td>
                    <td class="text-center">
                        @if(isset($item['total']))
                            R$ {{ number_format($item['total'],2,',', '.') }}
                        @else
                            0,00
                        @endif
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
    </div>
</div>

@push('scripts')
    <script>

        Livewire.on('openModal', () => {
            $('#itemForm').modal('show');
            $('#product_id').selectpicker('refresh');

        });

        Livewire.on('closeModal', () => {
            $('#itemForm').modal('hide');
        });

        document.addEventListener('DOMContentLoaded', function () {
            $('#operator_id').on('change', function () {
            @this.set('operator', $(this).val());
            });
            $('#product_id').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
                let selectedValue = $(this).val();
            @this.set('product_id', selectedValue)
                ;
            })
        });


        Livewire.on('itemAdded', () => {
            $('#operator_id').selectpicker('refresh');
            $('#product_id').selectpicker('refresh');
            $('#order_type').selectpicker('refresh');
            // @this.set('operator_id', null);
            // @this.set('product_id', null);
            // @this.set('order_type_id', null);
        });


        Livewire.on('refreshSelect', () => {
            $('#product_id').val().selectpicker('refresh');

        })



    </script>
@endpush
