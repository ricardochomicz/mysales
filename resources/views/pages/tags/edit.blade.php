

@extends('adminlte::page')

@section('title', "Editar Tag")

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h4>Editar Tag</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('tags.index')}}">Home</a></li>
                <li class="breadcrumb-item active">Tags</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <form action="{{route('tags.update', $data->id)}}" method="post">
        @csrf
        @method('PUT')
        @include('pages.tags._partials.form')
    </form>
@stop

