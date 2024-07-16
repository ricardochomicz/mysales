<div>
    <div class="card">

        <div class="card-body">

            <h6><i class="fas fa-filter"></i> Filtros</h6>
            <div class="row">
                <div class="form-group col-sm-6 has-search">
                    <input wire:model.live="search" class="form-control" name="search"
                           placeholder="pesquisa por nome...">
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-secondary" wire:click="clearFilter">Limpar Filtros</button>
                </div>
            </div>

            <div class="small-box shadow-none">
                <x-loader wire:loading.delay wire:loading.class="overlay"></x-loader>

                <div class="table-responsive rounded">

                    <table class="table table-borderless table-hover">
                        <caption><small>Módulos cadastrados <b>{{$modules->count()}}</b></small></caption>
                        <thead class="bg-gray-light">
                        <tr>
                            <th>#</th>
                            <th>Label</th>
                            <th class="text-center">Módulo</th>
                            <th>Descrição</th>
                            <th class="text-center" width="15%">...</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($modules as $d)
                            <tr>
                                <td>{{$d->id}}</td>
                                <td>{{$d->label}}</td>
                                <td class="text-center">{{$d->module}}</td>
                                <td>{{$d->description}}</td>
                                <td class="text-center">
                                    <a href="{{route('plans.modules.detach', [$plan->id, $d->id])}}" class="btn btn-sm btn-danger"><i class="fas fa-unlink mr-1"></i> Desvincular</a>
                                </td>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center pagination-sm">
                        {!!  $modules->links('vendor.pagination.bootstrap-4') !!}
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

