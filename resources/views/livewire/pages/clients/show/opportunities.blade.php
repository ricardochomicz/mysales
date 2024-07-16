<div>
    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th class="text-center">Tipo</th>
            <th class="text-center">Receita</th>
            <th class="text-center">Ativação</th>
            <th class="text-center">Próx. Renovação</th>
            <th class="text-center">...</th>
        </tr>
        </thead>
        <tbody>

        @foreach($clients->orders->sortBy('updated_at') as $order)
            <tr>
                <td>
                    {{$order->identify}}<br>
                    @switch($order->type)
                        @case('opportunity')
                            <small class="text-danger">Oportunidade</small>
                            @break
                        @case('order')
                            <small class="text-danger">Pedido em Andamento</small>
                            @break
                        @case('wallet')
                            <small class="text-danger">Pedido Finalizado</small>
                            @break
                    @endswitch
                </td>
                <td class="text-center">{{$order->ordem->name}}
                    <br><small>{{$order->operadora->name}}</small></td>
                <td class="text-center">R$ {{number_format($order->total, 2, ',', '.')}}
                    <br>( {{$order->qty}} )
                </td>
                <td class="text-center">
                    @if($order->activate)
                        {{\Carbon\Carbon::parse($order->activate)->format('d/m/Y')}}
                    @else
                        -
                    @endif
                </td>
                <td class="text-center">
                    @if($order->activate)
                        {{\Carbon\Carbon::parse($order->renew_date)->format('d/m/Y')}}
                    @else
                        -
                    @endif
                </td>
                <td class="text-center">
                    @if($order->type == 'opportunity')
                        <a href="{{route('opportunities.edit', $order->id)}}"
                           class="btn btn-primary btn-sm tooltips" data-text="Editar">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    @endif
                    <a href="javascript:void(0)" wire:click="emitOrderId({{$order->id}})"
                       class="btn btn-secondary btn-sm tooltips pull-bs-canvas-right" data-text="Detalhes">
                        <i class="fas fa-binoculars"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
