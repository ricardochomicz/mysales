<div class="card border-left-primary">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-muted">
            {{ Route::currentRouteNamed('tags.create') ? __('Nova Tag') : __('Editar Tag') }}
        </h6>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="form-group col-sm-3">

{{--                <label for="type">Funil</label>--}}
{{--                <select class="form-control selectpicker" id="type" name="type" >--}}

{{--                    <option value="comment"--}}
{{--                            @if(@$data->type == 'comment') selected @endif>COMENTÁRIO--}}
{{--                    </option>--}}

{{--                    <option value="opportunity"--}}
{{--                            @if(@$data->type == 'opportunity') selected @endif>OPORTUNIDADE--}}
{{--                    </option>--}}

{{--                    <option value="order"--}}
{{--                            @if(@$data->type == 'order') selected @endif>PEDIDO--}}
{{--                    </option>--}}
{{--                    <option value="proposal"--}}
{{--                            @if(@$data->type == 'proposal') selected @endif>PROPOSTA--}}
{{--                    </option>--}}
{{--                    <option value="protocol"--}}
{{--                            @if(@$data->type == 'protocol') selected @endif>PROTOCOLO--}}
{{--                    </option>--}}

{{--                </select>--}}
                <x-select id="type" label="Tipos" title="Selecione" :options="$types" name="type" value="{{old('type') ?? @$data->type}}" />
            </div>
            <div class="form-group col-sm-6">
                <x-input label="Nome" name="name" value="{{old('name') ?? @$data->name}}"/>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('tags.index') }}" class="btn btn-secondary">Voltar</a>
    </div>
</div>

@push('script')
    <script>
        $(function(){
            $('.selectpicker').selectpicker();
        })
    </script>
@endpush

