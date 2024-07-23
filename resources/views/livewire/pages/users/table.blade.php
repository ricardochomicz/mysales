<div>
    <div class="card">

        <div class="card-body">

            <h6><i class="fas fa-filter"></i> Filtros</h6>
            <div class="row">
                <div class="form-group col-sm-6 has-search">
                    <input wire:model.live="search" class="form-control" name="search"
                           placeholder="pesquisa por nome...">
                </div>
                @can('manage-users')
                    <div class="form-group col-sm-3" wire:ignore>
                        <select name="trashed" data-live-search="true" title="Status" id="sel1"
                                wire:model.live="trashed"
                                class="selectpicker">
                            <option value="only">Inativos</option>
                        </select>
                    </div>
                @endcan
                <div class="form-group">
                    <button type="button" class="btn btn-secondary" wire:click="clearFilter">Limpar Filtros</button>
                </div>
            </div>

            <div class="small-box shadow-none">
                <x-loader wire:loading.delay wire:loading.class="overlay"></x-loader>

                <div class="table-responsive rounded">

                    <table class="table table-borderless table-hover">
                        <caption><small>Usuários cadastrados <b>{{$data->total()}}</b></small></caption>
                        <thead class="bg-gray-light">
                        <tr>
                            <th></th>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Telefone</th>
                            <th>E-mail</th>
                            <th class="text-center">Atualizado em</th>
                            <th class="text-center" width="15%">...</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $d)
                            <tr>
                                <td>
                                    <img src="{{$d->avatar_url}}" alt="Avatar do usuário" class="round-image">
                                </td>
                                <td>{{$d->id}}</td>
                                <td>{{$d->name}}</td>
                                <td>{{$d->phone}}</td>
                                <td>{{$d->email}}</td>
                                <td class="text-center">{{$d->updated_at}}</td>
                                <td class="text-center">

                                    @if($d->deleted_at === null)
                                        <a href="{{route('users.edit', $d->id)}}"
                                           class="btn btn-primary btn-sm tooltips" data-text="Editar">
                                            <i class="fas fa-sync-alt"></i>
                                        </a>
                                        @can('manage-users')
                                            <a href="javascript:void(0)"
                                               onclick="ativaDesativa('{{route('users.destroy',  $d->id)}}', 'desativar', '{{$d->label}}')"
                                               class="btn btn-danger btn-sm tooltips" data-text="Desativar">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        @endcan
                                    @else
                                        <a href="javascript:void(0)"
                                           onclick="ativaDesativa('{{route('users.destroy',  $d->id)}}', 'ativar', '{{$d->label}}')"
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
</div>

@push('scripts')
    <script>
        Livewire.on('resetSelectpicker', function () {
            $("#sel1").selectpicker('val', '') // Ou qualquer outra lógica de redefinição necessária
        });
    </script>
@endpush
