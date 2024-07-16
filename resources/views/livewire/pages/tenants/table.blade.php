<div>
    <div class="card">

        <div class="card-body">

            <h6><i class="fas fa-filter"></i> Filtros</h6>
            <div class="row">
                <div class="form-group col-sm-6 has-search">
                    <input wire:model.live="search" class="form-control" name="search"
                           placeholder="pesquisa por nome...">
                </div>
                <div class="form-group col-sm-3" wire:ignore>
                    <select name="trashed" data-live-search="true" title="Status" id="sel1" wire:model.live="trashed"
                            class="selectpicker">
                        <option value="only">Inativos</option>
                    </select>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-secondary" wire:click="clearFilter">Limpar Filtros</button>
                </div>
            </div>

            <div class="small-box shadow-none">
                <x-loader wire:loading.delay wire:loading.class="overlay"></x-loader>

                <div class="table-responsive rounded">

                    <table class="table table-borderless table-hover">
                        @can('isSuperAdmin')
                            <caption><small>Empresas cadastradas <b>{{$data->count()}}</b></small></caption>
                        @endcan
                        <thead class="bg-gray-light">
                        <tr>
                            <th>#</th>
                            <th></th>
                            <th>Empresa</th>
                            <th>Plano</th>
                            <th>E-mail</th>
                            <th class="text-center">Atualizado em</th>
                            <th class="text-center" width="15%">...</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $d)
                            <tr>
                                <td class="align-middle">{{$d->id}}</td>
                                <td class="align-middle"><img src="{{$d->logo_url}}" alt="Logo Empresa"
                                                              class="round-image"></td>
                                <td class="align-middle">{{$d->name}}<br><small>{{$d->document}}</small></td>
                                <td class="align-middle">{{$d->plan->name}}<br>
                                    @if(!$d->subscription_suspended)
                                        <span class="badge badge-success">
                                        Ativo
                                    </span>
                                    @else
                                        <span class="badge badge-danger">
                                        Suspenso
                                    </span>
                                    @endif
                                </td>
                                <td class="align-middle">{{$d->email}}</td>
                                <td class="text-center align-middle">{{$d->updated_at}}</td>
                                <td class="text-center align-middle">
                                    @if($d->deleted_at === null)
                                        <a href="{{route('tenants.edit', $d->id)}}"
                                           class="btn btn-primary btn-sm tooltips" data-text="Editar">
                                            <i class="fas fa-sync-alt"></i>
                                        </a>
                                        @can('isSuperAdmin')
                                            <a href="javascript:void(0)"
                                               onclick="ativaDesativa('{{route('tenants.destroy',  $d->id)}}', 'desativar', '{{$d->label}}')"
                                               class="btn btn-danger btn-sm tooltips" data-text="Desativar">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        @endcan
                                    @else
                                        <a href="{{route('tenants.edit', $d->id)}}"
                                           class="btn btn-primary btn-sm tooltips" data-text="Editar">
                                            <i class="fas fa-sync-alt"></i>
                                        </a>
                                        @can('isSuperAdmin')
                                            <a href="javascript:void(0)"
                                               onclick="ativaDesativa('{{route('tenants.destroy',  $d->id)}}', 'ativar', '{{$d->label}}')"
                                               class="btn btn-secondary btn-sm tooltips" data-text="Ativar">
                                                <i class="fas fa-trash-restore"></i>
                                            </a>
                                        @endcan
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
