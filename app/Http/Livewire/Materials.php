<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Material;

class Materials extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $nombre, $extras, $slug;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.materials.view', [
            'materials' => Material::latest()
						->orWhere('nombre', 'LIKE', $keyWord)
						->orWhere('extras', 'LIKE', $keyWord)
						->orWhere('slug', 'LIKE', $keyWord)
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
		$this->nombre = null;
		$this->extras = null;
		$this->slug = null;
    }

    public function store()
    {
        $this->validate([
		'nombre' => 'required',
		'extras' => 'required',
		'slug' => 'required',
        ]);

        Material::create([ 
			'nombre' => $this-> nombre,
			'extras' => $this-> extras,
			'slug' => $this-> slug
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Material Successfully created.');
    }

    public function edit($id)
    {
        $record = Material::findOrFail($id);

        $this->selected_id = $id; 
		$this->nombre = $record-> nombre;
		$this->extras = $record-> extras;
		$this->slug = $record-> slug;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'nombre' => 'required',
		'extras' => 'required',
		'slug' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Material::find($this->selected_id);
            $record->update([ 
			'nombre' => $this-> nombre,
			'extras' => $this-> extras,
			'slug' => $this-> slug
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Material Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Material::where('id', $id);
            $record->delete();
        }
    }
}
