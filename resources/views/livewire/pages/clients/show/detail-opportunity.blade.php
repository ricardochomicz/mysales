<div>
    <div class="bs-canvas bs-canvas-right position-fixed bg-light h-100" wire:ignore.self>
        <header class="bs-canvas-header p-3 bg-primary overflow-auto">
            <button type="button" class="bs-canvas-close float-left close" aria-label="Close"><span aria-hidden="true"
                                                                                                    class="text-light">&times;</span>
            </button>
            <h4 class="d-inline-block text-light mb-0 float-right">Detalhes</h4>
        </header>
        <div class="bs-canvas-content px-1">
            <div class="list-group my-5">
                <a href="#" class="list-group-item list-group-item-action">{{@$items->client->name}}</a>
                <a href="#" class="list-group-item list-group-item-action">{{@$items->operadora->name}}
                    - {{@$items->ordem->name}}</a>
                <a href="#" class="list-group-item list-group-item-action">Criado em:
                    <b>{{\Carbon\Carbon::parse(@$items->created_at)->format('d/m/Y')}}</b></a>
                <a href="#" class="list-group-item list-group-item-action">Qtd Linhas: <b>{{@$items->qty}}</b></a>
                <a href="#" class="list-group-item list-group-item-action">Receita:
                    <b>R$ {{number_format(@$items->total, 2, ',', '.')}}</b></a>
                <a href="#" class="list-group-item list-group-item-action">Ativação: <b>
                        @if(@$items->activate)
                            {{\Carbon\Carbon::parse(@$items->activate)->format('d/m/Y')}}
                        @else
                            -
                        @endif
                    </b>
                </a>
            </div>
            <table class="table">
                <thead class="thead-light">
                <tr>
                    <th>Tipo</th>
                    <th class="text-center">Número</th>
                    <th>Item</th>
                    <th class="text-center">Valor/Qtd</th>
                </tr>
                </thead>
                <tbody>

                @if(isset($items))
                    @foreach($items->items_opportunity as $item)
                        <tr>
                            <th class="align-middle"><small>{{@$item->type}}</small></th>
                            <th class="text-center align-middle"><small>{{$item->number}}</small></th>
                            <th class="align-middle"><small>{{$item->product->name}}</small></th>
                            <td class="text-center align-middle"><small>R$ {{number_format($item->price, 2, ',','.')}}<br>( {{$item->qty}} )</small></td>
                        </tr>
                    @endforeach
                @endif

                </tbody>
            </table>


            <p class="text-muted small">Enviar Proposta</p>
            <div class="input-group flex-nowrap">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="addon-wrapping">@</span>
                </div>
                <input type="text" class="form-control" placeholder="Email" aria-label="Username"
                       aria-describedby="addon-wrapping">
            </div>
            <p class="text-right mt-3 mb-0">
                <button type="button" class="btn btn-outline-dark">Enviar</button>
            </p>
        </div>
    </div>
</div>

@push('scripts')

    <script>
        jQuery(document).ready(function ($) {
            $(document).on('click', '.pull-bs-canvas-right, .pull-bs-canvas-left', function () {
                $('body').prepend('<div class="bs-canvas-overlay bg-dark position-fixed w-100 h-100"></div>');
                if ($(this).hasClass('pull-bs-canvas-right'))
                    $('.bs-canvas-right').addClass('mr-0');
                else
                    $('.bs-canvas-left').addClass('ml-0');
                return false;
            });

            $(document).on('click', '.bs-canvas-close, .bs-canvas-overlay', function () {
                var elm = $(this).hasClass('bs-canvas-close') ? $(this).closest('.bs-canvas') : $('.bs-canvas');
                elm.removeClass('mr-0 ml-0');
                $('.bs-canvas-overlay').remove();
                return false;
            });
        });
    </script>
@endpush

