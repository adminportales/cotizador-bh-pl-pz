<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SizeMaterialTechnique;

class SizeMaterialTechniques extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $size_id, $material_technique_id;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.sizeMaterialTechniques.view', [
            'sizeMaterialTechniques' => SizeMaterialTechnique::latest()
						->orWhere('size_id', 'LIKE', $keyWord)
						->orWhere('material_technique_id', 'LIKE', $keyWord)
						->paginate(10),
        ]);
    }
	
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }
	
    private function resetInput()
    {		
		$this->size_id = null;
		$this->material_technique_id = null;
    }

    public function store()
    {
        $this->validate([
		'size_id' => 'required',
		'material_technique_id' => 'required',
        ]);

        SizeMaterialTechnique::create([ 
			'size_id' => $this-> size_id,
			'material_technique_id' => $this-> material_technique_id
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'SizeMaterialTechnique Successfully created.');
    }

    public function edit($id)
    {
        $record = SizeMaterialTechnique::findOrFail($id);

        $this->selected_id = $id; 
		$this->size_id = $record-> size_id;
		$this->material_technique_id = $record-> material_technique_id;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'size_id' => 'required',
		'material_technique_id' => 'required',
        ]);

        if ($this->selected_id) {
			$record = SizeMaterialTechnique::find($this->selected_id);
            $record->update([ 
			'size_id' => $this-> size_id,
			'material_technique_id' => $this-> material_technique_id
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'SizeMaterialTechnique Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = SizeMaterialTechnique::where('id', $id);
            $record->delete();
        }
    }
}
