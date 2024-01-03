<?php

namespace App\Http\Livewire\Admin;

use App\Models\MaterialTechnique;
use App\Models\Size;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Clase SizeMaterialTechniques
 *
 * Esta clase es responsable de manejar la lógica y la presentación de la vista de tamaños, materiales y técnicas.
 * Utiliza la paginación de Laravel y tiene métodos para renderizar la vista, cancelar la edición, resetear los inputs,
 * editar un registro y actualizar los tamaños.
 */
class SizeMaterialTechniques extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $size_id, $material_technique_id, $material;
    public $updateMode = false;

    /**
     * Renderiza la vista de tamaños, materiales y técnicas.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        $keyWord = '%' . $this->keyWord . '%';
        return view('admin.personalizacion.sizes.size-material-techniques.view', [
            'sizeMaterialTechniques' => MaterialTechnique::orderby('material_id', 'asc')->paginate(10),
            'sizes' => Size::all(),
            'materialsTechniques' => MaterialTechnique::all()
        ]);
    }

    /**
     * Cancela la edición y resetea los inputs.
     *
     * @return void
     */
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    /**
     * Resetea los inputs.
     *
     * @return void
     */
    private function resetInput()
    {
        $this->size_id = null;
        $this->material_technique_id = null;
    }

    /**
     * Edita un registro.
     *
     * @param int $id El ID del registro a editar.
     * @return void
     */
    public function edit($id)
    {
        $record = MaterialTechnique::findOrFail($id);
        $this->material = $record;
        $this->updateMode = true;
    }

    /**
     * Actualiza los tamaños.
     *
     * @param int $size_id El ID del tamaño a actualizar.
     * @return void
     */
    public function updateSizes($size_id)
    {
        $size = Size::find($size_id);
        $this->material->sizeMaterialTechniques()->toggle($size);
        session()->flash('updateSites', 'Actualizacion correcta.');
    }
}
