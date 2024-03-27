<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Technique;

use Illuminate\Support\Str as Str;

/**
 * Clase Techniques
 * 
 * Esta clase es responsable de manejar la lógica relacionada con las técnicas en la sección de personalización del cotizador.
 */
class Techniques extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $nombre;
    public $updateMode = false;

    /**
     * Renderiza la vista de técnicas
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $keyWord = '%' . $this->keyWord . '%';

        return view('admin.personalizacion.material_y_tecnica.techniques.view', [
            'techniques' => Technique::latest()
                ->orWhere('nombre', 'LIKE', $keyWord)
                ->paginate(10),
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
    }

    /**
     * Almacena una nueva técnica en la base de datos.
     *
     * @return void
     */
    public function store()
    {
        $this->validate([
            'nombre' => 'required',
        ]);

        Technique::create([
            'nombre' => $this->nombre,
            'slug' => Str::slug($this->nombre)
        ]);

        $this->resetInput();
        $this->emit('closeModal');
        session()->flash('message', 'Técnica creada exitosamente.');
    }

    /**
     * Prepara los datos para editar una técnica existente.
     *
     * @param  int  $id
     * @return void
     */
    public function edit($id)
    {
        $record = Technique::findOrFail($id);

        $this->selected_id = $id;
        $this->nombre = $record->nombre;

        $this->updateMode = true;
    }

    /**
     * Actualiza una técnica existente en la base de datos.
     *
     * @return void
     */
    public function update()
    {
        $this->validate([
            'nombre' => 'required',
        ]);

        if ($this->selected_id) {
            $record = Technique::find($this->selected_id);
            $record->update([
                'nombre' => $this->nombre,
                'slug' => Str::slug($this->nombre)
            ]);

            $this->resetInput();
            $this->updateMode = false;
            session()->flash('message', 'Técnica actualizada exitosamente.');
        }
    }

    /**
     * Elimina una técnica de la base de datos.
     *
     * @param  int  $id
     * @return void
     */
    public function destroy($id)
    {
        if ($id) {
            $record = Technique::where('id', $id);
            $record->delete();
        }
    }
}
