<?php

namespace App\Livewire\Pages\Clients\Persons;

use App\Models\Person;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Form extends Component
{
    use WithPagination;

    public $client;
    #[Rule('required')]
    public string $name = '';
    public string $document = '';
    public string $phone = '';
    public $birthday;
    public string $office = '';
    #[Rule('required')]
    public string $email = '';

    public bool $showModal = false;
    #[Locked]
    public int $personId;
    public ?Person $person;

    protected string $paginationTheme = 'bootstrap';

    public function mount($client)
    {
        $this->client = $client;
    }

    public function edit(int $personId): void
    {
        $this->person = Person::where(['client_id' => $this->client, 'id' => $personId])->first();
        $this->showModal = true;
        $this->personId = $personId;

        $this->name = $this->person->name;
        $this->document = $this->person->document;
        $this->phone = $this->person->phone;
        $this->email = $this->person->email;
        $this->office = $this->person->office;
        $this->birthday = $this->person->birthday ?? null;
    }

    public function save(): void
    {
        $this->validate();

        if (empty($this->person)) {
            $maxOrder = Person::where('client_id', $this->client)->max('order');
            $nextOrder = $maxOrder ? $maxOrder + 1 : 1;

            Person::create([
                'client_id' => $this->client,
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'document' => $this->document,
                'office' => $this->office,
                'birthday' => $this->birthday ?? null,
                'order' => $nextOrder,

            ]);
            notyf()->success('Contato cadastrado com sucesso.');
        } else {
            $this->person->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'document' => $this->document,
                'office' => $this->office,
                'birthday' => $this->birthday ?? null,
            ]);
            notyf()->success('Contato atualizado com sucesso.');
        }

        $this->dispatch('closeModal', modalId: '#personForm');

        $this->reset('person', 'name', 'document', 'email', 'phone', 'office', 'birthday', 'showModal');
    }

    public function delete(int $personId): void
    {
        Person::where(['client_id' => $this->client, 'id' => $personId])->delete();
    }

    #[On('resetModal')]
    public function resetModal(): void
    {
        $this->reset('person', 'name', 'document', 'email', 'phone', 'office', 'birthday', 'showModal');
    }

    public function render(): View
    {
        return view('livewire.pages.clients.persons.form', [
            'persons' => Person::where('client_id', $this->client)->orderBy('order', 'asc')->paginate()
        ]);
    }

    public function updateOrder($list): void
    {
        foreach ($list as $item) {
            $person = Person::where('client_id', $this->client)->find($item['value']);

            if ($person && $person->order != $item['order']) {
                $person->update(['order' => $item['order']]);
            }
        }
    }
}
