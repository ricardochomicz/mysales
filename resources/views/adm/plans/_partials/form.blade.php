<div class="card border-left-primary">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-muted">
            {{ Route::currentRouteNamed('plans.create') ? __('Novo Plano') : __('Editar Plano') }}
        </h6>
    </div>
    <div class="card-body">
        @if (isset($oper['id']))
            <input hidden name="id" value="{{ $oper['id'] }}">
        @endif
        <div class="row">
            <div class="form-group col-sm-6">
                <label for="name">Nome</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', @$plan->name) }}">
                @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group col-sm-2">
                <label for="price">Nome</label>
                <input type="text" name="price" class="form-control @error('price') is-invalid @enderror"
                       value="{{ old('price', @$plan->price) }}">
                @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('plans.index') }}" class="btn btn-secondary">Voltar</a>
    </div>
</div>

