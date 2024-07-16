<div class="card border-left-primary">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-muted">
            {{ Route::currentRouteNamed('operators.create') ? __('Nova Operadora') : __('Editar Operadora') }}
        </h6>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="form-group col-sm-6">
                <x-input label="Nome" name="name" value="{{old('name') ?? @$data->name}}"/>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('operators.index') }}" class="btn btn-secondary">Voltar</a>
    </div>
</div>

