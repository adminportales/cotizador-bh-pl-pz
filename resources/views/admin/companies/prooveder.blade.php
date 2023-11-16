<!-- Modal -->
<div wire:ignore.self class="modal fade" id="proovedores" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="proovedoresLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="proovedoresLabel">Create New Client</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">


                    </div>
                    <div class="form-group">
                        <label for="client_odoo_id"></label>
                        <input wire:model="client_odoo_id" type="text" class="form-control" id="client_odoo_id"
                            placeholder="Client Odoo Id">
                        @error('client_odoo_id')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                       <label>{{$companies}}</label>
                        @error('name')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="contact"></label>
                        <input wire:model="contact" type="text" class="form-control" id="contact"
                            placeholder="Contact">
                        @error('contact')
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
                        <label for="phone"></label>
                        <input wire:model="phone" type="text" class="form-control" id="phone"
                            placeholder="Phone">
                        @error('phone')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
                <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">Save</button>
            </div>
        </div>
    </div>
</div>
