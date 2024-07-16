<div class="card border-left-primary">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">
            {{ Route::currentRouteNamed('adm.modules.create') ? __('Novo Módulo') : __('Editar Módulo') }}
        </h6>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="form-group col-sm-6">
                <x-input name="module" label="Módulo" value="{{ old('module', @$module->module) }}"/>
            </div>
            <div class="form-group col-sm-6">
                <x-input name="label" label="Label" value="{{ old('label', @$module->label) }}"/>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-12">
                <x-input name="description" label="Descrição" value="{{ old('description', @$module->description) }}"/>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('modules.index') }}" class="btn btn-secondary">Voltar</a>
    </div>
</div>

