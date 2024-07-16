<div class="card">
    <h4 class="card-header">
        {{$data->name}}
    </h4>
    <livewire:pages.teams.members.form :team="$data->id" />
</div>
