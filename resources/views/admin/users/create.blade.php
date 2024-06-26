<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDataModalLabel">Create New User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form autocomplete="off">
                    <div class="form-group">
                        <label for="name"></label>
                        <input wire:model="name" type="text" class="form-control" id="name" placeholder="Name">
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
                        <label for="password"></label>
                        <input wire:model="password" type="password" class="form-control" id="password"
                            placeholder="Password">
                        @error('password')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="company_id"></label>
                        <select wire:model="company_id" class="form-control"
                            id="company_id">
                            <option value="">Seleccione...</option>
                            @foreach ($companies as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('company_id')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- <div class="form-group">
                        <label for="id_odoo"></label>
                        <input wire:model="id_odoo" type="number" class="form-control" id="id_odoo"
                            placeholder="Id de Odoo">
                        @error('id_odoo')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div> --}}
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
                <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">Save</button>
            </div>
        </div>
    </div>
</div>
