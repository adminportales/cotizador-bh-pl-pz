<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Size;

use Illuminate\Support\Str as Str;

/**
 * Clase Sizes
 *
 * Esta clase es responsable de manejar la lógica relacionada con los tamaños en el sistema de cotización.
 */
class Sizes extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $nombre;
    public $updateMode = false;

    /**
     * Renderiza la vista de tamaños con la lista de tamaños disponibles.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $keyWord = '%' . $this->keyWord . '%';
        return view('admin.personalizacion.sizes.sizes.view', [
            'sizes' => Size::latest()
                ->orWhere('nombre', 'LIKE', $keyWord)
                ->paginate(10),
        ]);
    }

    /**
     * Cancela la edición o creación de un tamaño y reinicia los valores de entrada.
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
     * Almacena un nuevo tamaño en la base de datos.
     *
     * @return void
     */
    public function store()
    {
        $this->validate([
            'nombre' => 'required',
        ]);

        Size::create([
            'nombre' => $this->nombre,
            'slug' => Str::slug($this->nombre)
        ]);

        $this->resetInput();
        $this->emit('closeModal');
        session()->flash('message', 'Tamaño creado exitosamente.');
    }

    /**
     * Prepara los datos para editar un tamaño existente.
     *
     * @param  int  $id
     * @return void
     */
    public function edit($id)
    {
        $record = Size::findOrFail($id);

        $this->selected_id = $id;
        $this->nombre = $record->nombre;

        $this->updateMode = true;
    }

    /**
     * Actualiza un tamaño existente en la base de datos.
     *
     * @return void
     */
    public function update()
    {
        $this->validate([
            'nombre' => 'required',
        ]);

        if ($this->selected_id) {
            $record = Size::find($this->selected_id);
            $record->update([
                'nombre' => $this->nombre,
                'slug' => Str::slug($this->nombre)
            ]);

            $this->resetInput();
            $this->updateMode = false;
            session()->flash('message', 'Tamaño actualizado exitosamente.');
        }
    }

    /**
     * Elimina un tamaño de la base de datos.
     *
     * @param  int  $id
     * @return void
     */
    public function destroy($id)
    {
        if ($id) {
            $record = Size::where('id', $id);
            $record->delete();
        }
    }
}
