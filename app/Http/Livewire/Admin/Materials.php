<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Material;
use App\Models\Technique;
use Illuminate\Support\Str as Str;

/**
 * Clase Materials
 *
 * Esta clase es responsable de manejar la lógica relacionada con los materiales en el sistema de cotización.
 */
class Materials extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $nombre, $extras, $material;
    public $updateMode = false;

    /**
     * Renderiza la vista de materiales y técnicas.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $keyWord = '%' . $this->keyWord . '%';
        $techniques = Technique::all();
        return view('admin.personalizacion.material_y_tecnica.materials.view', [
            'materials' => Material::latest()
                ->orWhere('nombre', 'LIKE', $keyWord)
                ->orWhere('extras', 'LIKE', $keyWord)
                ->paginate(10),
            'techniques' => $techniques
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
        $this->nombre = null;
        $this->extras = null;
        $this->material = null;
    }

    /**
     * Almacena un nuevo material en la base de datos.
     *
     * @return void
     */
    public function store()
    {
        $this->validate([
            'nombre' => 'required',
            'extras' => 'required',
        ]);

        Material::create([
            'nombre' => $this->nombre,
            'extras' => $this->extras,
            'slug' => Str::slug($this->nombre)
        ]);

        $this->resetInput();
        $this->emit('closeModal');
        session()->flash('message', 'Material creado exitosamente.');
    }

    /**
     * Prepara los datos para editar un material.
     *
     * @param  int  $id
     * @return void
     */
    public function edit($id)
    {
        $record = Material::findOrFail($id);

        $this->selected_id = $id;
        $this->nombre = $record->nombre;
        $this->extras = $record->extras;

        $this->updateMode = true;
    }

    /**
     * Actualiza un material existente en la base de datos.
     *
     * @return void
     */
    public function update()
    {
        $this->validate([
            'nombre' => 'required',
            'extras' => 'required',
        ]);

        if ($this->selected_id) {
            $record = Material::find($this->selected_id);
            $record->update([
                'nombre' => $this->nombre,
                'extras' => $this->extras,
                'slug' => Str::slug($this->nombre)
            ]);

            $this->resetInput();
            $this->updateMode = false;
            session()->flash('message', 'Material actualizado exitosamente.');
        }
    }

    /**
     * Elimina un material de la base de datos.
     *
     * @param  int  $id
     * @return void
     */
    public function destroy($id)
    {
        if ($id) {
            $record = Material::where('id', $id);
            $record->delete();
        }
    }

    /**
     * Prepara los datos para editar la lista de técnicas de un material.
     *
     * @param  int  $id
     * @return void
     */
    public function editListTechniques($id)
    {
        $record = Material::findOrFail($id);
        $this->material = $record;
        $this->updateMode = true;
    }

    /**
     * Actualiza la lista de técnicas de un material.
     *
     * @param  int  $technique_id
     * @return void
     */
    public function updateListTechniques($technique_id)
    {
        $technique = Technique::find($technique_id);
        $this->material->materialTechniques()->toggle($technique);
        session()->flash('updateSites', 'Actualización correcta.');
    }
}
