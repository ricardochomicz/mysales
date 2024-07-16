@props([
'label'     => '',
'value'     => '',
'name'      => '',
'id'        => uniqid(),
'options'   => [],
'textoSelecione'   => 'Selecione',
'obs'       => '',
'idOriginal' => false,
'style' => '',
])
@pushOnce('styles')
    <link href="{{asset('assets/css/select2.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/css/select2-bootstrap4.css')}}" rel="stylesheet"/>
@endPushOnce

<div>
    @if($label!='')
        <label for="{{$id}}" class="form-label">{{$label}}</label>
    @endif
    <select id="{{$id}}"
            name="{{$name}}" {{ $attributes->class([ 'is-invalid' => $errors->has(str_replace('[]', '', $name))]) }}  @if($style!='') style="{{$style}}" @endif>
        @if(!isset($attributes['multiple'])  && !isset($attributes['NaoTextoSelecione']) )
            <option value="">{{$textoSelecione}}</option>
        @endif
        @foreach($options as $op)
            @php
                $idOP = $op->id;
                if($idOriginal && isset($op->id_original) ){
                    $idOP .= ';'.$op->id_original;
                }
            @endphp
            @if(isset($attributes['multiple']) )
                <option value="{{$idOP}}" @selected(in_array($idOP,$value))>{{$op->name}}</option>
            @else
                <option value="{{$idOP}}" @selected($value == $idOP)>{{$op->name}}</option>
            @endif
        @endforeach
    </select>
    @if($obs != '')
        <small class="text-secondary">{{$obs}}</small>
    @endif
    @error(str_replace('[]', '', $name))
    <div class="invalid-feedback" style="display: block">{{ $message }}</div>
    @enderror
</div>
@pushOnce('scripts')
    <script src="{{asset('assets/js/select2.min.js')}}"></script>
@endpushonce
@push('scripts')
    <script>

        @if(isset($attributes['wire_var']) )
        $('#{{$id}}').on('change', function (e) {
        @if(isset($attributes['multiple']) )
            @this.set('{{$attributes['wire_var']}}', $('#{{$id}}').val());
            @else
            var data = $('#{{$id}}').select2("data");
        @this.dispatch('{{$attributes['wire_var']}}_change', data[0].id, data[0].text);
            @endif

        });
        @endif
        function ativaSelect2{{$id}}() {
            $('#{{$id}}').select2({
                theme: 'bootstrap4',
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                placeholder: $(this).data('placeholder'),
                allowClear: Boolean($(this).data('allow-clear')),
            });

        }

        @if(isset($attributes['wire_var']) )
        document.addEventListener("livewire:load", () => {
            Livewire.hook('message.processed', (message, component) => {
                ativaSelect2{{$id}}();
            });
        });
        @endif
        $(document).ready(function () {
            ativaSelect2{{$id}}();
        });
    </script>
@endpush
