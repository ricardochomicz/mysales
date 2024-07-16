<div>
    <div class="card">
        <div class="card-header">
            <div class="card-tools">
                Selecionar todos
                <label class="switch mr-2">
                    <input type="checkbox" class="default module-checkbox" id="select-all-checkbox">
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
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

                    <table class="table table-borderless">
                        <thead>
                        <tr>
                            <th width="15%"></th>
                            <th>Módulo</th>
                            <th width="60%">Descrição</th>
                        </tr>
                        </thead>
                        <tbody>
                        <form action="{{route('plans.modules.attach', $plan->id)}}" method="POST">
                            @csrf
                            @forelse($modules as $m)
                                <tr>
                                    <td class="text-center">
                                        <label class="switch ">
                                            <input type="checkbox" class="primary module-checkbox" name="modules[]"
                                                   value="{{$m->id}}">
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>{{$m->label}}</td>
                                    <td>{{$m->description}}</td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-danger"><small>Nenhum módulo
                                            disponível</small></td>
                                </tr>
                            @endforelse
                            <tr>
                                <td colspan="2">
                                    @if(count($modules))
                                        <button class="btn btn-primary"><i class="fas fa-link mr-1"></i> Vincular</button>
                                    @endif
                                    <a href="{{route('plans.modules', $plan->id)}}" class="btn btn-secondary"><i class="fas fa-angle-left mr-1"></i> Voltar</a>
                                </td>
                            </tr>
                        </form>
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{$modules->links('vendor.pagination.bootstrap-4')}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Obtem o checkbox "Selecionar Todos"
            var selectAllCheckbox = document.getElementById('select-all-checkbox');

            // Obtem todos os outros checkboxes
            var moduleCheckboxes = document.querySelectorAll('.module-checkbox');

            // Adiciona um evento de clique ao checkbox "Selecionar Todos"
            selectAllCheckbox.addEventListener('click', function () {
                // Verifique se o checkbox "Selecionar Todos" está marcado
                var isChecked = selectAllCheckbox.checked;

                // Atualiza o estado de todos os outros checkboxes
                moduleCheckboxes.forEach(function (checkbox) {
                    checkbox.checked = isChecked;
                });
            });
        });
    </script>
@endpush
