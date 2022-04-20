<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataSMTModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataSMTModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDataSMTModalLabel">Create New Size Material Technique</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
            <div class="form-group">
                <label for="size_id"></label>
                <input wire:model="size_id" type="text" class="form-control" id="size_id" placeholder="Size Id">@error('size_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="material_technique_id"></label>
                <input wire:model="material_technique_id" type="text" class="form-control" id="material_technique_id" placeholder="Material Technique Id">@error('material_technique_id') <span class="error text-danger">{{ $message }}</span> @enderror
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
