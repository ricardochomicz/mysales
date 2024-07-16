@props([
'label' => '',
'name'  => '',
'conteudo' => '',
'id'    => uniqid(),
])
<div>
    <div class="form-check-danger form-check form-switch">
        <input id="{{$id}}" name="{{$name}}" {{ $attributes->class(['form-check-input', 'is-invalid' => $errors->has($name)]) }} type="checkbox" @checked( $conteudo == 1)>
        <label class="form-check-label" for="{{$id}}">{{$label}}</label>
    </div>
    @error($name)
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
