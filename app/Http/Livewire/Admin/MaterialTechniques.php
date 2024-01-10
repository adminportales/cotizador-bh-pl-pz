<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MaterialTechnique;

/**
 * Clase MaterialTechniques
 * NO TIENE USO ACTUALMENTE
 *
 * Esta clase es responsable de manejar la lógica y la presentación de la página de técnicas de materiales en el panel de administración.
 */
class MaterialTechniques extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $technique_id, $material_id;
    public $updateMode = false;

    /**
     * Renderiza la vista de la página de técnicas de materiales.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $keyWord = '%' . $this->keyWord . '%';
        return view('admin.personalizacion.sizes.size-material-techniques.view', [
            'materialTechniques' => MaterialTechnique::orWhere('technique_id', 'LIKE', $keyWord)
                ->orWhere('material_id', 'LIKE', $keyWord)
                ->paginate(10),
        ]);
    }

    /**
     * Cancela la edición o creación de una técnica de material.
     *
     * @return void
     */
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    /**
     * Reinicia los valores de los campos de entrada.
     *
     * @return void
     */
    private function resetInput()
    {
        $this->technique_id = null;
        $this->material_id = null;
    }

    /**
     * Almacena una nueva técnica de material en la base de datos.
     *
     * @return void
     */
    public function store()
    {
        $this->validate([
            'technique_id' => 'required',
            'material_id' => 'required',
        ]);

        MaterialTechnique::create([
            'technique_id' => $this->technique_id,
            'material_id' => $this->material_id
        ]);

        $this->resetInput();
        $this->emit('closeModal');
        session()->flash('message', 'Técnica de material creada exitosamente.');
    }

    /**
     * Prepara los campos de entrada para editar una técnica de material existente.
     *
     * @param int $id El ID de la técnica de material a editar.
     * @return void
     */
    public function edit($id)
    {
        $record = MaterialTechnique::findOrFail($id);

        $this->selected_id = $id;
        $this->technique_id = $record->technique_id;
        $this->material_id = $record->material_id;

        $this->updateMode = true;
    }

    /**
     * Actualiza una técnica de material existente en la base de datos.
     *
     * @return void
     */
    public function update()
    {
        $this->validate([
            'technique_id' => 'required',
            'material_id' => 'required',
        ]);

        if ($this->selected_id) {
            $record = MaterialTechnique::find($this->selected_id);
            $record->update([
                'technique_id' => $this->technique_id,
                'material_id' => $this->material_id
            ]);

            $this->resetInput();
            $this->updateMode = false;
            session()->flash('message', 'Técnica de material actualizada exitosamente.');
        }
    }

    /**
     * Elimina una técnica de material de la base de datos.
     *
     * @param int $id El ID de la técnica de material a eliminar.
     * @return void
     */
    public function destroy($id)
    {
        if ($id) {
            $record = MaterialTechnique::where('id', $id);
            $record->delete();
        }
    }
}
