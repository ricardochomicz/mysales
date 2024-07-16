<div class="card border-left-primary">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-muted">
            {{ Route::currentRouteNamed('classifications.create') ? __('Nova Classificação') : __('Editar Classificação') }}
        </h6>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="form-group col-sm-3">
                <x-input label="Nome" name="name" value="{{old('name') ?? @$data->name}}"/>
            </div>
            <div class="form-group col-sm-3">
                <x-input label="Meses" name="months" value="{{old('months') ?? @$data->months}}" placeholder="Somente números. Ex. 12"/>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('classifications.index') }}" class="btn btn-secondary">Voltar</a>
    </div>
</div>

