

@extends('adminlte::page')

@section('title', "Cadastrar Produto")

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h4>Cadastrar Produto</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('products.index')}}">Home</a></li>
                <li class="breadcrumb-item active">Produtos</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <form action="{{route('products.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        @include('pages.products._partials.form')
    </form>
@stop
