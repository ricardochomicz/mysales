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
                    <select name="trashed" data-live-search="true" title="Status" id="sel1"
                            wire:model.live="trashed"
                            class="selectpicker">
                        <option value="only">Inativos</option>
                    </select>
                </div>
                <div class="form-group col-sm-2" wire:ignore>
                    <x-select :options="$funnels" wire:model.live="funnel" title="Funil" id="sel2"/>
                </div>
                <div class="form-group col-sm-2" wire:ignore>
                    <select name="probability" data-live-search="true" title="Probabilidade" id="sel3"
                            wire:model.live="probability"
                            class="selectpicker">
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
                    <x-input type="date" wire:model.live.debounce.500ms="dt_ini" class="tooltips" data-text="Data Início"/>
                </div>
                <div class="form-group col-sm-2">
                    <x-input type="date" wire:model.live.debounce.500ms="dt_end" class="tooltips" data-text="Data Fim"/>
                </div>
                <div class="form-group">
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
                            <th>#</th>
                            <th>Cliente</th>
                            <th class="text-center">Solicitação</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Funil</th>
                            <th class="text-center">Previsão</th>
                            <th class="text-center" width="15%">...</th>
                        </tr>
                        </thead>
                        <tbody>


                        @foreach($data as $d)
                            <tr>
                                <td>{{$d->id}}</td>
                                <td>
                                    {{$d->client->name}}<br><small>{{$d->client->document}}</small>
                                </td>
                                <td class="text-center">
                                    {{$d->ordem->name}}<br><small>{{$d->operadora->name}}</small>
                                </td>
                                <td class="text-center">R$ {{number_format($d->total, 2, ',', '.')}}<br>{{( $d->qty )}}</td>
                                <td class="text-center">
                                    @switch($d->funnel)
                                        @case('prospect')
                                            <span class='badge badge-primary'>PROSPECT</span>
                                            @break
                                        @case('negotiation')
                                            <span class='badge badge-warning'>NEGOCIAÇÃO</span>
                                            @break
                                        @case('correction')
                                            <span class='badge badge-danger'>PARA CORREÇÃO</span>
                                            @break
                                        @case('closure')
                                            <span class='badge badge-success'>FECHAMENTO</span>
                                            @break
                                    @endswitch
                                    <br>
                                    <small class="text-blue tooltips" data-text="Probabilidade">{{$d->probability}}
                                        %</small>
                                </td>

                                <td class="text-center">
                                    {{Carbon\Carbon::parse($d->forecast)->format('d/m/Y')}}
                                    <br><small>{{$d->updated_at->diffForHumans()}}</small>
                                </td>

                                <td class="text-center">
                                    @if($d->deleted_at === null)

                                        <a href="{{route('opportunities.edit', $d->id)}}"
                                           class="btn btn-primary btn-sm tooltips" data-text="Editar">
                                            <i class="fas fa-sync-alt"></i>
                                        </a>
                                        <a href="javascript:void(0)" wire:click="loadComments({{$d->id}})"
                                           data-toggle="modal" data-target="#modalComments"
                                           class="btn btn-success btn-sm tooltips" data-text="Observações">
                                            <i class="fas fa-comments"></i>
                                        </a>
                                        <a href="javascript:void(0)"
                                           onclick="ativaDesativa('{{route('opportunities.destroy',  $d->id)}}', 'desativar', '{{$d->client->name}}')"
                                           class="btn btn-danger btn-sm tooltips" data-text="Desativar">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    @else
                                        <a href="javascript:void(0)"
                                           onclick="ativaDesativa('{{route('opportunities.destroy',  $d->id)}}', 'ativar', '{{$d->client->name}}')"
                                           class="btn btn-secondary btn-sm tooltips" data-text="Ativar">
                                            <i class="fas fa-trash-restore"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {!!  $data->links('vendor.pagination.bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
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
                                            {{$comment->content}}
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


