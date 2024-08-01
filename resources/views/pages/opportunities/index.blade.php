@extends('adminlte::page')

@section('title', 'Oportunidades')

@section('content_header')
    <div class="d-flex bd-highlight">
        <div class="mr-auto p-1 bd-highlight">
            <h4>Oportunidades</h4>
            <select id="view-selector" class="selectpicker" onchange="changeView()">
                <option value="kanban">Kanban</option>
                <option value="table">Tabela</option>
            </select>
        </div>
        <div class="p-1 bd-highlight">

            @can('manage-users')
                <a href="{{ route('opportunities.create') }}" class="btn btn-dark">Nova Oportunidade</a>
            @endcan
        </div>
    </div>
@stop

@section('content')

    <div id="table-view" style="display: block;">
        <livewire:pages.opportunities.table>
    </div>
    <div id="kanban-view" style="display: none;">
        <livewire:pages.opportunities.kan-ban>
    </div>

    <x-modal_ativo_inativo titulo="Oportunidade" />

    <x-modal_gain titulo="Negócio Fechado" />

@stop

@push('scripts')
    <script>
        function changeView() {
            const view = document.getElementById('view-selector').value;
            document.getElementById('table-view').style.display = view === 'table' ? 'block' : 'none';
            document.getElementById('kanban-view').style.display = view === 'kanban' ? 'block' : 'none';

            localStorage.setItem('viewPreference', view);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Recuperar a preferência do localStorage
            const savedView = localStorage.getItem('viewPreference') || 'kanban';
            document.getElementById('view-selector').value = savedView;
            changeView();
        });
    </script>
@endpush
