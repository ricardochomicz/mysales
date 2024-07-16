@extends('adminlte::auth.auth-page', ['auth_type' => 'register'])

@php( $login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login') )
@php( $register_url = View::getSection('register_url') ?? config('adminlte.register_url', 'register') )

@if (config('adminlte.use_route_url', false))
    @php( $login_url = $login_url ? route($login_url) : '' )
    @php( $register_url = $register_url ? route($register_url) : '' )
@else
    @php( $login_url = $login_url ? url($login_url) : '' )
    @php( $register_url = $register_url ? url($register_url) : '' )
@endif

@section('auth_header', 'Criar minha conta')

@section('auth_body')

    <p class="alert bg-success disabled color-palette text-center">
        Plano: <strong>{{session('plan')->name ?? ''}}</strong>
    </p>
    <form action="{{ $register_url }}" method="post">
        @csrf

        {{-- Document field --}}
        <div class="input-group mb-3">
            <input type="text" name="document" class="form-control @error('document') is-invalid @enderror" onkeyup="getClient(this.value)" maxlength="14"
                   value="{{ old('document') }}" placeholder="Informe o CNPJ (somente números)" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-file-csv {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('document')
            <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Tenant field --}}
        <div class="input-group mb-3">
            <input type="text" name="tenant" class="form-control @error('tenant') is-invalid @enderror"
                   value="{{ old('tenant') }}" placeholder="Razão Social" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-store {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('tenant')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Name field --}}
        <div class="input-group mb-3">
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}" placeholder="Nome Completo">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('name')
            <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Phone field --}}
        <div class="input-group mb-3">
            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                   value="{{ old('phone') }}" placeholder="Celular com DDD">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-phone {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('phone')
            <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="{{ __('adminlte::adminlte.email') }}">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                   placeholder="Sua senha">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Confirm password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password_confirmation"
                   class="form-control @error('password_confirmation') is-invalid @enderror"
                   placeholder="Repita sua senha">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('password_confirmation')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Register button --}}
        <button type="submit" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
            <span class="fas fa-user-plus"></span>
            {{ __('adminlte::adminlte.register') }}
        </button>

    </form>
@stop

@section('auth_footer')
    <p class="my-0">
        <a href="{{ $login_url }}">
            Login
        </a>
    </p>
@stop

@pushonce('scripts')
    <script>


        function getClient(doc) {
            if (doc.length === 14) {
                cnpj = doc.replace(/[^\d]+/g, "");
                $.ajax({
                    url: 'https://receitaws.com.br/v1/cnpj/' + cnpj,
                    method: "GET",
                    dataType: "jsonp",
                    complete: function(xhr) {
                        $("#phone").focus();
                        let retorno = xhr.responseJSON;
                        console.log(xhr)
                        if(xhr.status === 200){
                            $("input[name=tenant]").val(retorno.nome);
                        }else{
                            alert('CNPJ Inválido.')
                        }
                    }
                });
            }
        };
    </script>
@endpushonce
