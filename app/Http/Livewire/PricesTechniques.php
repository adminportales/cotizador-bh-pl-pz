<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PricesTechnique;

class PricesTechniques extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $size_material_technique_id, $escala_inicial, $escala_final, $precio, $tipo_precio;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.pricesTechniques.view', [
            'pricesTechniques' => PricesTechnique::latest()
						->orWhere('size_material_technique_id', 'LIKE', $keyWord)
						->orWhere('escala_inicial', 'LIKE', $keyWord)
						->orWhere('escala_final', 'LIKE', $keyWord)
						->orWhere('precio', 'LIKE', $keyWord)
						->orWhere('tipo_precio', 'LIKE', $keyWord)
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
		$this->size_material_technique_id = null;
		$this->escala_inicial = null;
		$this->escala_final = null;
		$this->precio = null;
		$this->tipo_precio = null;
    }

    public function store()
    {
        $this->validate([
		'size_material_technique_id' => 'required',
		'escala_inicial' => 'required',
		'precio' => 'required',
		'tipo_precio' => 'required',
        ]);

        PricesTechnique::create([ 
			'size_material_technique_id' => $this-> size_material_technique_id,
			'escala_inicial' => $this-> escala_inicial,
			'escala_final' => $this-> escala_final,
			'precio' => $this-> precio,
			'tipo_precio' => $this-> tipo_precio
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'PricesTechnique Successfully created.');
    }

    public function edit($id)
    {
        $record = PricesTechnique::findOrFail($id);

        $this->selected_id = $id; 
		$this->size_material_technique_id = $record-> size_material_technique_id;
		$this->escala_inicial = $record-> escala_inicial;
		$this->escala_final = $record-> escala_final;
		$this->precio = $record-> precio;
		$this->tipo_precio = $record-> tipo_precio;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'size_material_technique_id' => 'required',
		'escala_inicial' => 'required',
		'precio' => 'required',
		'tipo_precio' => 'required',
        ]);

        if ($this->selected_id) {
			$record = PricesTechnique::find($this->selected_id);
            $record->update([ 
			'size_material_technique_id' => $this-> size_material_technique_id,
			'escala_inicial' => $this-> escala_inicial,
			'escala_final' => $this-> escala_final,
			'precio' => $this-> precio,
			'tipo_precio' => $this-> tipo_precio
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'PricesTechnique Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = PricesTechnique::where('id', $id);
            $record->delete();
        }
    }
}
