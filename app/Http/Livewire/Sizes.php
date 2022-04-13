<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Size;

class Sizes extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $nombre, $slug;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.sizes.view', [
            'sizes' => Size::latest()
						->orWhere('nombre', 'LIKE', $keyWord)
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
		$this->slug = null;
    }

    public function store()
    {
        $this->validate([
		'nombre' => 'required',
		'slug' => 'required',
        ]);

        Size::create([ 
			'nombre' => $this-> nombre,
			'slug' => $this-> slug
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Size Successfully created.');
    }

    public function edit($id)
    {
        $record = Size::findOrFail($id);

        $this->selected_id = $id; 
		$this->nombre = $record-> nombre;
		$this->slug = $record-> slug;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'nombre' => 'required',
		'slug' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Size::find($this->selected_id);
            $record->update([ 
			'nombre' => $this-> nombre,
			'slug' => $this-> slug
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Size Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Size::where('id', $id);
            $record->delete();
        }
    }
}
