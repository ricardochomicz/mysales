<div>
    <div class="card">
        <div class="card-body">
            <h6><i class="fas fa-filter"></i> Filtros</h6>
            <div class="row">
                <div class="form-group col-sm-4 has-search">
                    <input wire:model.live="search" class="form-control" name="search"
                           placeholder="pesquisa por nome...">
                </div>
                <div class="form-group col-sm-2" wire:ignore>
                    <x-select :options="$order_type" wire:model.live="type" title="Tipo" id="sel4"/>
                </div>
                <div class="form-group col-sm-2" wire:ignore>
                    <select data-live-search="true" title="Status" id="sel1" wire:model.live="trashed"
                            class="selectpicker">
                        <option value="only">Inativos</option>
                    </select>
                </div>
                <div class="form-group col-sm-2" wire:ignore>
                    <x-select :options="$funnels" wire:model.live="funnel" title="Funil" id="sel2"/>
                </div>
                <div class="form-group col-sm-2" wire:ignore>
                    <select name="probability" data-live-search="true" title="Probabilidade" id="sel3"
                            wire:model.live="probability" class="selectpicker">
                        <option value="0">0%</option>
                        <option value="25">25%</option>
                        <option value="50">50%</option>
                        <option value="75">75%</option>
                        <option value="100">100%</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-2">
                    <x-input type="date" wire:model.live.debounce.500ms="dt_ini" class="tooltips"
                             data-text="Data Início"/>
                </div>
                <div class="form-group col-sm-2">
                    <x-input type="date" wire:model.live.debounce.500ms="dt_end" class="tooltips" data-text="Data Fim"/>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-secondary" wire:click="clearFilter">Limpar Filtros</button>
                </div>
            </div>
            <section class="row">
                <div class="col-md-3 py-1">
                    <div class="card shadow rounded-0 font-weight-bold text-muted border-secondary">
                        <div class="card-header alert-primary pt-3 pb-3">
                            <i class="fas fa-binoculars mr-1"></i>
                            Prospect
                        </div>
                    </div>
                </div>
                <div class="col-md-3 py-1">
                    <div class="card shadow rounded-0 font-weight-bold text-muted border-secondary">
                        <div class="card-header alert-secondary pt-3 pb-3">
                            <i class="fas fa-file-alt mr-1"></i>
                            Negociação
                        </div>
                    </div>
                </div>
                <div class="col-md-3 py-1">
                    <div class="card shadow rounded-0 font-weight-bold text-muted border-secondary">
                        <div class="card-header alert-success pt-3 pb-3">
                            <i class="fas fa-handshake mr-1"></i>
                            Fechamento
                        </div>
                    </div>
                </div>
                <div class="col-md-3 py-1">
                    <div class="card shadow rounded-0 font-weight-bold text-muted border-secondary">
                        <div class="card-header alert-danger pt-3 pb-3">
                            <i class="fas fa-eraser mr-1"></i>
                            Para Correção
                        </div>
                    </div>
                </div>
            </section>


            <section class="row">
                <div class="sortable-card col-md-3 py-1 ui-sortable panel-heading border-secondary" id="prospect">
                    @foreach($data->filter(fn($item) => $item->funnel == 'prospect') as $item)
                        <div class="card card-primary card-outline" data-id="{{ $item->id }}">
                            <small class="card-title p-2" style="font-size: 14px">
                                {{ $item->client->name }}
                            </small>
                            <div class="card-body p-2">
                                <small>{{ $item->ordem->name }} - {{$item->operadora->name}}</small><br>
                                <small class="font-weight-bold">R$ {{number_format($item->total, 2, ',','.')}}
                                    ( {{ $item->qty }} )</small><br>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small>Previsão:
                                        <b>{{Carbon\Carbon::parse($item->forecast)->format('d/m/Y')}}</b></small>
                                    <div>
                                        <a href="{{route('opportunities.edit', $item->id)}}"
                                           class="btn btn-sm btn-primary tooltips" data-text="Editar"><i
                                                class="fas fa-sync-alt"></i></a>
                                        <a href="javascript:void(0)" wire:click="loadCommentsKanban({{$item->id}})"
                                           data-toggle="modal" data-target="#modalCommentsKanban"
                                           class="btn btn-success btn-sm tooltips" data-text="Observações">
                                            <i class="fas fa-comments"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="sortable-card col-md-3 py-1 ui-sortable panel-heading border-secondary" id="negotiation">
                    @foreach($data->filter(fn($item) => $item->funnel == 'negotiation') as $item)
                        <div class="card card-secondary card-outline" data-id="{{ $item->id }}">
                            <small class="card-title p-2" style="font-size: 14px">
                                {{ $item->client->name }}
                            </small>
                            <div class="card-body p-2">
                                <small>{{ $item->ordem->name }} - {{$item->operadora->name}}</small><br>
                                <small class="font-weight-bold">R$ {{number_format($item->total, 2, ',','.')}}
                                    ( {{ $item->qty }} )</small><br>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small>Previsão:
                                        <b>{{Carbon\Carbon::parse($item->forecast)->format('d/m/Y')}}</b></small>
                                    <div>
                                        <a href="{{route('opportunities.edit', $item->id)}}"
                                           class="btn btn-sm btn-primary tooltips" data-text="Editar"><i
                                                class="fas fa-sync-alt"></i></a>
                                        <a href="javascript:void(0)" wire:click="loadCommentsKanban({{$item->id}})"
                                           data-toggle="modal" data-target="#modalCommentsKanban"
                                           class="btn btn-success btn-sm tooltips" data-text="Observações">
                                            <i class="fas fa-comments"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="sortable-card col-md-3 py-1 ui-sortable panel-heading border-secondary" id="closure">
                    @foreach($data->filter(fn($item) => $item->funnel == 'closure') as $item)
                        <div class="card card-success card-outline" data-id="{{ $item->id }}">
                            <small class="card-title p-2" style="font-size: 14px">
                                {{ $item->client->name }}
                            </small>
                            <div class="card-body p-2">
                                <small>{{ $item->ordem->name }} - {{$item->operadora->name}}</small><br>
                                <small class="font-weight-bold">R$ {{number_format($item->total, 2, ',','.')}}
                                    ( {{ $item->qty }} )</small><br>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small>Previsão:
                                        <b>{{Carbon\Carbon::parse($item->forecast)->format('d/m/Y')}}</b></small>
                                    <div>
                                        <a href="{{route('opportunities.edit', $item->id)}}"
                                           class="btn btn-sm btn-primary tooltips" data-text="Editar"><i
                                                class="fas fa-sync-alt"></i></a>
                                        <a href="javascript:void(0)" wire:click="loadCommentsKanban({{$item->id}})"
                                           data-toggle="modal" data-target="#modalCommentsKanban"
                                           class="btn btn-success btn-sm tooltips" data-text="Observações">
                                            <i class="fas fa-comments"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="sortable-card col-md-3 py-1 ui-sortable panel-heading border-secondary" id="correction">
                    @foreach($data->filter(fn($item) => $item->funnel == 'correction') as $item)
                        <div class="card card-danger card-outline" data-id="{{ $item->id }}">
                            <small class="card-title p-2" style="font-size: 14px">
                                {{ $item->client->name }}
                            </small>
                            <div class="card-body p-2">
                                <small>{{ $item->ordem->name }} - {{$item->operadora->name}}</small><br>
                                <small class="font-weight-bold">R$ {{number_format($item->total, 2, ',','.')}}
                                    ( {{ $item->qty }} )</small><br>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small>Previsão:
                                        <b>{{Carbon\Carbon::parse($item->forecast)->format('d/m/Y')}}</b></small>
                                    <div>
                                        <a href="{{route('opportunities.edit', $item->id)}}"
                                           class="btn btn-sm btn-primary tooltips" data-text="Editar"><i
                                                class="fas fa-sync-alt"></i></a>
                                        <a href="javascript:void(0)" wire:click="loadCommentsKanban({{$item->id}})"
                                           data-toggle="modal" data-target="#modalCommentsKanban"
                                           class="btn btn-success btn-sm tooltips" data-text="Observações">
                                            <i class="fas fa-comments"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>

    <div class="modal fade" id="modalCommentsKanban" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
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
                        <div class="card-footer card-comments"
                             style="margin: 0 auto; max-height: calc(50vh - 50px); overflow-y: auto;">
                            @forelse($comments as $comment)
                                <div class="card-comment">
                                    <div class="comment-text">
                                    <span class="username">{{$comment->user->name}}
                                        <span
                                            class="text-muted float-right">{{Carbon\Carbon::parse($comment->created_at)->format('d/m/Y H:i')}}</span>
                                    </span>
                                        @if($comment->type == 'order')
                                            <small class="font-italic">(Pedido)</small> {{$comment->content}}
                                        @else
                                            {{$comment->content}}
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <span>Nenhum comentário!!!</span>
                            @endforelse
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

@push('scripts')
    <script src="{{asset('assets/plugins/jquery-ui.js')}}"></script>
    <script>
        $(function () {
            $(".sortable-card").sortable({
                connectWith: ".sortable-card",
                cursor: "move",
                delay: 50,
                opacity: 0.5,
                distance: 5,
                // receive: function (event, ui) {
                //     idOp.value = ui.item.attr("id");
                //     const from = $(this).attr("id");
                //     idFunnel.value = getFunnel(from);
                //     console.log(idFunnel.value)
                //     updateKanban(idOp.value, idFunnel.value);
                // },
                stop: function (event, ui) {
                    let item = ui.item;
                    let newStatus = item.closest(".sortable-card").attr("id");
                    let itemId = item.data("id");

                @this.updateOpportunityStatus(itemId, newStatus)
                    ;
                }
            });
        });

    </script>
@endpush
