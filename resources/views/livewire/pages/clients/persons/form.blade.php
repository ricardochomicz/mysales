<div class="mt-3">
    <button wire:click="$toggle('showModal')" data-toggle="modal" data-target="#personForm" class="btn btn-primary">Novo
        Contato
    </button>
    <table class="table mt-4">
        <thead>
        <tr>
            <th><i class="fas fa-sort-numeric-down"></i></th>
            <th>Nome</th>
            <th>E-mail</th>
            <th class="text-center">Telefone</th>
            <th>Cargo</th>
            <th>Aniversário</th>
            <th class="text-center">...</th>
        </tr>
        </thead>
        <tbody wire:sortable="updateOrder">
        @forelse ($persons as $person)
            <tr wire:sortable.handle wire:sortable.item="{{ $person->id }}" wire:key="person-{{ $person->id }}">
                <th class="align-middle">{{$person->order}}</th>
                <td class="align-middle">{{ $person->name }}<br><small>{{$person->document}}</small></td>
                <td class="align-middle">{{ $person->email }}</td>
                <td class="text-center align-middle">{{ $person->phone }}</td>
                <td class="align-middle">{{ $person->office }}</td>
                <td class="align-middle">
                    @if($person->birthday)
                        {{ \Carbon\Carbon::parse($person->birthday)->format('d/m/Y') }}
                    @endif
                </td>
                <td class="text-center align-middle">
                    <button wire:click="edit({{ $person->id }})" data-toggle="modal" data-target="#personForm"
                            class="btn btn-sm btn-primary">
                        Editar
                    </button>
                    <button wire:click="delete({{ $person->id }})"
                            onclick="confirm('Excluir Contato?') || event.stopImmediatePropagation()"
                            class="btn btn-sm btn-danger">
                        Deletar
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3">Nenhum contato cadastrado.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="personForm" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <form wire:submit.prevent="save">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $personId ? 'Editar Contato' : 'Adicionar Contato' }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="name" class="form-label">Name:</label>
                                <input wire:model="name" id="name"
                                       class="form-control @error('name') is-invalid @enderror"/>
                                @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="email" class="form-label">E-mail:</label>
                                <input wire:model="email" id="email"
                                       class="form-control @error('email') is-invalid @enderror"/>
                                @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="document" class="form-label">CPF:</label>
                                <input wire:model="document" id="document" class="form-control"/>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="phone" class="form-label">Telefone:</label>
                                <input wire:model="phone" id="phone" class="form-control"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="office" class="form-label">Cargo:</label>
                                <input wire:model="office" id="office" class="form-control"/>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="birthday" class="form-label">Aniversário:</label>
                                <input wire:model="birthday" type="date" id="birthday" class="form-control"/>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://unpkg.com/@nextapps-be/livewire-sortablejs@0.3.0/dist/livewire-sortable.js"></script>
@endpush
