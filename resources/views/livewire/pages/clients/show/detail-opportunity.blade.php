<div>
    <div class="bs-canvas bs-canvas-right position-fixed bg-light h-100" wire:ignore.self>
        <header class="bs-canvas-header p-3 bg-primary overflow-auto">
            <button type="button" class="bs-canvas-close float-left close" aria-label="Close"><span aria-hidden="true"
                                                                                                    class="text-light">&times;</span>
            </button>
            <h4 class="d-inline-block text-light mb-0 float-right">Detalhes</h4>
        </header>
        <div class="bs-canvas-content px-1">
            <div class="invoice-col p-2">
                <address>
                    <strong>{{@$opportunity->client->name}}</strong><br/>
                    <b>{{@$opportunity->operadora->name}} - {{@$opportunity->ordem->name}}</b><br/>
                    Criado em: <b>{{\Carbon\Carbon::parse(@$opportunity->created_at)->format('d/m/Y')}}</b><br/>
                    Qtd Linhas: <b>{{@$opportunity->qty}}</b><br/>
                    Receita: <b>R$ {{number_format(@$opportunity->total, 2, ',', '.')}}</b><br>
                    @if(@$opportunity->activate)
                        Ativação: <b>{{\Carbon\Carbon::parse(@$opportunity->activate)->format('d/m/Y')}}</b>
                    @endif
                </address>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
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
                        @foreach($items as $item)
                            <tr>
                                <th class="align-middle"><small>{{@$item->type->name}}</small></th>
                                <th class="text-center align-middle"><small>{{$item->number}}</small></th>
                                <th class="align-middle"><small>{{$item->product->name}}</small></th>
                                <td class="text-center align-middle">
                                    <small>R$ {{number_format($item->price, 2, ',','.')}}
                                        <br>( {{$item->qty}} )</small></td>
                            </tr>
                        @endforeach
                    @endif

                    </tbody>
                </table>
                <div class="d-flex justify-content-center pagination-sm">
{{--                    @if ($items instanceof \Illuminate\Pagination\LengthAwarePaginator)--}}
{{--                        {!!  $items->links('vendor.pagination.bootstrap-4') !!}--}}
{{--                    @endif--}}
                </div>
            </div>
            <p class="text-muted small">Enviar Proposta</p>
            @php
                $message = urlencode("Segue proposta " . (@$opportunity->operadora->name ?? '')  . " EMPRESAS: \nhttps://mysales.42telecom.com.br/my-proposal/" . @$opportunity->uuid);
            @endphp
            <p class="text-right mt-3 mb-0">
                <a href="https://wa.me/55{{ @$opportunity->client->persons[0]->phone }}?text={{ $message }}"
                   target="_blank" class="btn btn-outline-dark">Enviar WhatsApp</a>
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

