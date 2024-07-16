@extends('adminlte::page')

@section('title', 'Cadastrar Empresa')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h4>Cadastrar Empresa</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('tenants.index')}}">Home</a></li>
                <li class="breadcrumb-item active">Empresas</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <form action="{{route('tenants.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        @include('pages.tenants._partials.form')
    </form>
@stop

