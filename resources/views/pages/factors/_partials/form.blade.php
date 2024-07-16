<div class="card border-left-primary">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-muted">
            {{ Route::currentRouteNamed('factors.create') ? __('Novo Fator') : __('Editar Fator') }}
        </h6>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="form-group col-sm-3">
                <x-select label="Operadora" name="operator_id" :options="$operators" value="{{old('operator_id') ?? @$data->operator_id}}" title="Selecione" />
            </div>
            <div class="form-group col-sm-3">
                <x-select label="Tipo Pedido" name="order_type_id" :options="$order_types" value="{{old('order_type_id') ?? @$data->order_type_id}}" title="Selecione" />
            </div>
        </div>

        <div class="row">
            <div class="form-group col-sm-6">
                <x-input label="Nome" name="name" value="{{old('name') ?? @$data->name}}" oninput="formatFactor(this)" />
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('factors.index') }}" class="btn btn-secondary">Voltar</a>
    </div>
</div>

@pushonce('scripts')
    <script src="{{ asset('assets/plugins/inputmask/inputmask.js') }}"></script>
    <script>
        function formatFactor(input) {
            let valor = input.value;

            valor = valor.replace(/\D/g, '');

            let valorNumerico = (parseInt(valor) / 100).toFixed(2);

            valor = valorNumerico.replace(',', '.');

            if (isNaN(valorNumerico)) {
                input.value = '';
            } else {
                input.value = valor;
            }
        }

    </script>
@endpushonce

