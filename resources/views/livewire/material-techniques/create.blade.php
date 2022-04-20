<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModalMT" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalMTLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDataModalMTLabel">Create New Material Technique</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
            <div class="form-group">
                <label for="technique_id"></label>
                <input wire:model="technique_id" type="text" class="form-control" id="technique_id" placeholder="Technique Id">@error('technique_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="material_id"></label>
                <input wire:model="material_id" type="text" class="form-control" id="material_id" placeholder="Material Id">@error('material_id') <span class="error text-danger">{{ $message }}</span> @enderror
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
