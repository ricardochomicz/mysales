@props([
'label'     => '',
'value'     => '',
'name'      => '',
'id'        => uniqid(),
'options'   => [],
'textSelect'   => '',
'obs'       => '',
'idOrigin' => false,
'style' => '',
])

<div>
    @if($label!='')
        <label for="{{$id}}" class="form-label">{{$label}}</label>
    @endif
    <select id="{{$id}}" class="selectpicker" data-live-search="true"
            name="{{$name}}"
            {{ $attributes->class([ 'is-invalid' => $errors->has(str_replace('[]', '', $name))]) }}  @if($style!='') style="{{$style}}" @endif>
        @if(!isset($attributes['multiple'])  && !isset($attributes['NotTextSelect']) )
            {{--            <option value="">{{$textSelect}}</option>--}}
        @endif
        @foreach($options as $op)
            @php
                $idOP = $op->id;
                if($idOrigin && isset($op->id_origin) ){
                    $idOP .= ','.$op->id_origin;
                }
            @endphp
            @if(isset($attributes['multiple']) )
                @php
                    $selectedValues = is_array($value) ? $value : explode(';', $value);
                @endphp
                <option data-content="<span class='badge badge-light' style='font-size:14px'>{{$op['name']}}</span>" value="{{$idOP}}" @if(in_array($idOP, $selectedValues)) selected @endif>{{$op['name']}}</option>
            @else
                <option value="{{$idOP}}" @selected($value == $idOP)>{{$op->name}} @if(isset($op->text)) {{$op->text}} @endif</option>
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

@push('scripts')
    <script src="{{asset('assets/js/bootstrap-select.min.js')}}"></script>
    <script>

        @if(isset($attributes['wire_var']) )
        $('#{{$id}}').on('change', function (e) {
        @if(isset($attributes['multiple']) )
            @this.set('{{$attributes['wire_var']}}', $('#{{$id}}').val());
            @else
            var data = $('#{{$id}}').selectpicker("data");
        @this.dispatch('{{$attributes['wire_var']}}_change', data[0].id, data[0].text)
            ;
            @endif

        });
        @endif
        function activeSelect2{{$id}}() {
            $('#{{$id}}').selectpicker({
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                placeholder: $(this).data('placeholder'),
                allowClear: Boolean($(this).data('allow-clear')),
            })
        }

        @if(isset($attributes['wire_var']) )
        document.addEventListener("livewire:load", () => {
            Livewire.hook('message.processed', (message, component) => {
                activeSelect2{{$id}}();
            });
        });
        @endif
        $(document).ready(function () {
            activeSelect2{{$id}}();
        });
    </script>
@endpush
