<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <form>
                            <input type="hidden" wire:model="selected_id">
                            <div class="form-group">
                                <label for="name"></label>
                                <input wire:model="name" type="text" class="form-control" id="name"
                                    placeholder="Name">
                                @error('name')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email"></label>
                                <input wire:model="email" type="text" class="form-control" id="email"
                                    placeholder="Email">
                                @error('email')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="company_id"></label>
                                <select wire:model="company_id" class="form-control" id="company_id">
                                    <option value="">Seleccione...</option>
                                    @foreach ($companies as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                @error('company_id')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Lista de Asistententes</label>
                                @if ($user)
                                    @foreach ($user->assistants as $assistant)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="{{ $assistant->id }}"
                                                id="check{{ $assistant->id }}"
                                                wire:click="updateAssistant({{ $assistant->id }})" checked>
                                            <label class="form-check-label" for="check{{ $assistant->id }}">
                                                {{ $assistant->name }} </label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </form>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for=""></label>
                            <input wire:model='keySearch' type="text" class="form-control" name="keySearch"
                                id="keySearch" placeholder="Buscar Asistente">
                            <ul class="list-group" style="max-height: 200px; overflow: auto">
                                @foreach ($allUsers as $newuser)
                                    @if (Str::contains(Str::lower($newuser->name), Str::lower($keySearch)))
                                        <li class="list-group-item">
                                            @php
                                                $check = false;
                                            @endphp
                                            @foreach ($user->assistants as $assistant)
                                                @php
                                                    if ($assistant->id == $newuser->id) {
                                                        $check = true;
                                                    }
                                                @endphp
                                            @endforeach

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    value="{{ $newuser->id }}" id="check{{ $newuser->id }}"
                                                    wire:click="updateAssistant({{ $newuser->id }})"
                                                    {{ $check ? 'checked' : '' }}>
                                                <label class="form-check-label" for="check{{ $newuser->id }}">
                                                    {{ $newuser->name }} </label>
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary"
                    data-dismiss="modal">Close</button>
                <button type="button" wire:click.prevent="update()" class="btn btn-primary"
                    data-dismiss="modal">Save</button>
            </div>
        </div>
    </div>
</div>
