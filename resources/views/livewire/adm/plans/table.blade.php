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
                        <caption><small>Planos cadastrados <b>{{$data->count()}}</b></small></caption>
                        <thead class="bg-gray-light">
                        <tr>
                            <th>#</th>
                            <th>Plano</th>
                            <th class="text-center">Valor</th>
                            <th class="text-center">Criado em</th>
                            <th class="text-center" width="15%">...</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $d)
                            <tr>
                                <td>{{$d->id}}</td>
                                <td>{{$d->name}}</td>
                                <td class="text-center">R$ {{$d->price}}</td>
                                <td class="text-center">{{$d->created_at}}</td>
                                <td class="text-center">
                                    <a href="{{route('plans.edit', $d->id)}}"
                                       class="btn btn-primary btn-sm tooltips" data-text="Editar">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                    <a href="{{route('plans.modules', $d->id)}}"
                                       class="btn btn-warning btn-sm tooltips" data-text="Módulos">
                                        <i class="fas fa-sitemap"></i>
                                    </a>
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

{{--@push('scripts')--}}
{{--    <script>--}}
{{--        Livewire.on('resetSelectpicker', function () {--}}
{{--            $("#sel1").selectpicker('val', '') // Ou qualquer outra lógica de redefinição necessária--}}
{{--        });--}}
{{--    </script>--}}
{{--@endpush--}}
