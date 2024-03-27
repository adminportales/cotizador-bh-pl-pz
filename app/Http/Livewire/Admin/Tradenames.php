<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tradename;

/**
 * Clase Tradenames
 *
 * Esta clase es responsable de manejar la lógica relacionada con los nombres comerciales en el sistema.
 */
class Tradenames extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $client_id, $name;
    public $updateMode = false;

    /**
     * Renderiza la vista de nombres comerciales.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $keyWord = '%'.$this->keyWord .'%';
        return view('admin.tradenames.view', [
            'tradenames' => Tradename::latest()
                ->orWhere('client_id', 'LIKE', $keyWord)
                ->orWhere('name', 'LIKE', $keyWord)
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
        $this->client_id = null;
        $this->name = null;
    }

    /**
     * Almacena un nuevo nombre comercial en la base de datos.
     *
     * @return void
     */
    public function store()
    {
        $this->validate([
            'client_id' => 'required',
            'name' => 'required',
        ]);

        Tradename::create([
            'client_id' => $this->client_id,
            'name' => $this->name
        ]);

        $this->resetInput();
        $this->emit('closeModal');
        session()->flash('message', 'Nombre comercial creado exitosamente.');
    }

    /**
     * Prepara los datos para editar un nombre comercial.
     *
     * @param int $id El ID del nombre comercial a editar.
     * @return void
     */
    public function edit($id)
    {
        $record = Tradename::findOrFail($id);

        $this->selected_id = $id;
        $this->client_id = $record->client_id;
        $this->name = $record->name;

        $this->updateMode = true;
    }

    /**
     * Actualiza un nombre comercial existente en la base de datos.
     *
     * @return void
     */
    public function update()
    {
        $this->validate([
            'client_id' => 'required',
            'name' => 'required',
        ]);

        if ($this->selected_id) {
            $record = Tradename::find($this->selected_id);
            $record->update([
                'client_id' => $this->client_id,
                'name' => $this->name
            ]);

            $this->resetInput();
            $this->updateMode = false;
            session()->flash('message', 'Nombre comercial actualizado exitosamente.');
        }
    }

    /**
     * Elimina un nombre comercial de la base de datos.
     *
     * @param int $id El ID del nombre comercial a eliminar.
     * @return void
     */
    public function destroy($id)
    {
        if ($id) {
            $record = Tradename::where('id', $id);
            $record->delete();
        }
    }
}
