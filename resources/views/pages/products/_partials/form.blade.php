<div class="card border-left-primary">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-muted">
            {{ Route::currentRouteNamed('products.create') ? __('Nova Produto') : __('Editar Produto') }}
        </h6>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="form-group col-sm-3">
                <x-select label="Operadora" name="operator_id" :options="$operators" title="Selecione" value="{{old('operator_id') ?? @$data->operator_id}}"/>
            </div>
            <div class="form-group col-sm-3">
                <x-select label="Categoria" name="category_id" :options="$categories" title="Selecione" value="{{old('category_id') ?? @$data->category_id}}"/>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-sm-6">
                <x-input label="Nome" name="name" value="{{old('name') ?? @$data->name}}"/>
            </div>
            <div class="form-group col-sm-2">
                <x-input label="Valor" name="price" value="{{old('price') ?? @$data->price}}" oninput="formatCurrency(this)"/>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-6">
                <label for="formFile" class="form-label">Escolha uma imagem:</label>
                <input type="file" id="input-id-prod" name="image" class="form-control">

            </div>
            <div class="form-group col-sm-1 text-center mt-4">
                @if(@$data->image)
                    <img src="{{@$data->image_url}}" width="40px" alt="Imagem">
                @endif
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-12">
                <x-text-area label="Descrição" name="description" value="{{old('description') ?? @$data->description}}" />

            </div>
        </div>


        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Voltar</a>
    </div>
</div>

@pushonce('scripts')
    <script src="{{ asset('assets/plugins/inputmask/inputmask.js') }}"></script>
    <script src="{{asset('/assets/plugins/inputfile/js/inputfile.min.js')}}"></script>
    <script>
        function formatCurrency(input) {
            let valor = input.value;
            valor = valor.replace(/\D/g, '');
            valor = (parseInt(valor) / 100).toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            if (valor === 'NaN') {
                input.value = '';
            } else {
                input.value = valor;
            }
        }
        $(document).ready(function () {
            $("#input-id-prod").fileinput({
                showUpload: false,
                dropZoneEnabled: false,
                maxFileCount: 10,
                inputGroupClass: "input-group"
            });
        });


    </script>
@endpushonce

