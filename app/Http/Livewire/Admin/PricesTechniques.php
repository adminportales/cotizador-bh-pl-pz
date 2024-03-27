<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PricesTechnique;
use App\Models\SizeMaterialTechnique;

/**
 * Clase PricesTechniques
 *
 * Esta clase es responsable de manejar la lógica relacionada con los precios y técnicas en la sección de administración.
 */
class PricesTechniques extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $size_material_technique_id, $escala_inicial, $escala_final, $precio, $tipo_precio;
    public $updateMode = false;

    /**
     * Renderiza la vista de precios y técnicas.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $keyWord = '%'.$this->keyWord .'%';
        return view('admin.personalizacion.prices.view', [
            'sizeMaterialTechniques' => SizeMaterialTechnique::paginate(20),
        ]);
    }

    /**
     * Cancela la operación actual y reinicia los valores de entrada.
     *
     * @return void
     */
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    /**
     * Reinicia los valores de entrada.
     *
     * @return void
     */
    private function resetInput()
    {
        $this->size_material_technique_id = null;
        $this->escala_inicial = null;
        $this->escala_final = null;
        $this->precio = null;
        $this->tipo_precio = null;
    }

    /**
     * Almacena un nuevo precio y técnica en la base de datos.
     *
     * @return void
     */
    public function store()
    {
        $this->validate([
            'size_material_technique_id' => 'required',
            'escala_inicial' => 'required',
            'precio' => 'required',
            'tipo_precio' => 'required',
        ]);

        PricesTechnique::create([
            'size_material_technique_id' => $this->size_material_technique_id,
            'escala_inicial' => $this->escala_inicial,
            'escala_final' => $this->escala_final,
            'precio' => $this->precio,
            'tipo_precio' => $this->tipo_precio
        ]);

        $this->resetInput();
        $this->emit('closeModal');
        session()->flash('message', 'Precio y técnica creados exitosamente.');
    }

    /**
     * Prepara los datos para la edición de un precio y técnica existente.
     *
     * @param int $id El ID del precio y técnica a editar.
     * @return void
     */
    public function edit($id)
    {
        $record = PricesTechnique::findOrFail($id);

        $this->selected_id = $id;
        $this->size_material_technique_id = $record->size_material_technique_id;
        $this->escala_inicial = $record->escala_inicial;
        $this->escala_final = $record->escala_final;
        $this->precio = $record->precio;
        $this->tipo_precio = $record->tipo_precio;

        $this->updateMode = true;
    }

    /**
     * Actualiza un precio y técnica existente en la base de datos.
     *
     * @return void
     */
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
                'size_material_technique_id' => $this->size_material_technique_id,
                'escala_inicial' => $this->escala_inicial,
                'escala_final' => $this->escala_final,
                'precio' => $this->precio,
                'tipo_precio' => $this->tipo_precio
            ]);

            $this->resetInput();
            $this->updateMode = false;
            session()->flash('message', 'Precio y técnica actualizados exitosamente.');
        }
    }

    /**
     * Elimina un precio y técnica de la base de datos.
     *
     * @param int $id El ID del precio y técnica a eliminar.
     * @return void
     */
    public function destroy($id)
    {
        if ($id) {
            $record = PricesTechnique::where('id', $id);
            $record->delete();
        }
    }
}
