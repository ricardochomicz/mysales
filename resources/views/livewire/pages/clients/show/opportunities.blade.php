<div>
    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th class="text-center">Tipo</th>
            <th class="text-center">Receita</th>
            <th class="text-center">Ativação</th>
            <th class="text-center">Tempo Contrato(M)</th>
            <th class="text-center">...</th>
        </tr>
        </thead>
        <tbody>

        @foreach($orders as $order)
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
                    <br> {{$order->qty}}
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
                        {{floor(\Carbon\Carbon::parse($order->activate)->diffInMonths(\Carbon\Carbon::now()))}}
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
                        <a href="javascript:void(0)" wire:click="loadComments({{$order->id}})"
                           data-toggle="modal" data-target="#modalComments"
                           class="btn btn-success btn-sm tooltips" data-text="Observações">
                            <i class="fas fa-comments"></i>
                        </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center pagination-sm">
        {!!  $orders->links('vendor.pagination.bootstrap-4') !!}
    </div>

    <div class="modal fade" id="modalComments"  aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Comentários Oportunidade
                        - {{@$opportunity->client->name}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class=" card-widget">
                        <div class="card-footer card-comments" style="margin: 0 auto; max-height: calc(50vh - 50px); overflow-y: auto;">
                            @foreach($comments as $comment)
                                <div class="card-comment">
                                    <div class="comment-text">
                                    <span class="username">{{$comment->user->name}}
                                        <span class="text-muted float-right">{{Carbon\Carbon::parse($comment->created_at)->format('d/m/Y H:i')}}</span>
                                    </span>
                                        @if($comment->type == 'order')
                                            <small class="font-italic">(Pedido)</small> {{$comment->content}}
                                        @else
                                            <small class="font-italic">(Oportunidade)</small> {{$comment->content}}
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>
