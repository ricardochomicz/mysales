<?php

namespace App\Livewire\Pages\Users;

use App\Services\UserService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $trashed = '';

    protected function queryString(): array
    {
        return [
            'search' => ['except' => ''],
            'trashed' => ['except' => ''],
        ];
    }

    protected $listeners = ['resetSelectpicker' => '$refresh'];

    public function render()
    {
        $users = new UserService();

        $filters = [
            'search' => $this->search,
            'trashed' => $this->trashed,
        ];

        return view('livewire.pages.users.table',[
            'data' => $users->index($filters)
        ]);
    }

    public function clearFilter()
    {
        $this->search = '';
        $this->trashed = '';
        $this->dispatch('resetSelectpicker');
    }
}
