<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Client;

/**
 * Clase Clients
 *
 * Esta clase es responsable de manejar la lógica relacionada con los clientes en el sistema.
 * Contiene métodos para renderizar la vista de clientes, almacenar, editar, actualizar y eliminar clientes.
 */
class Clients extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $user_id, $client_odoo_id, $name, $contact, $email, $phone;
    public $updateMode = false;

    /**
     * Renderiza la vista de clientes con los datos filtrados según la palabra clave ingresada.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $keyWord = '%'.$this->keyWord .'%';
        return view('admin.clients.view', [
            'clients' => Client::latest()
                        ->orWhere('user_id', 'LIKE', $keyWord)
                        ->orWhere('client_odoo_id', 'LIKE', $keyWord)
                        ->orWhere('name', 'LIKE', $keyWord)
                        ->orWhere('contact', 'LIKE', $keyWord)
                        ->orWhere('email', 'LIKE', $keyWord)
                        ->orWhere('phone', 'LIKE', $keyWord)
                        ->paginate(50),
        ]);
    }

    /**
     * Restablece los valores de las propiedades relacionadas con la entrada de datos.
     *
     * @return void
     */
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    /**
     * Restablece los valores de las propiedades relacionadas con la entrada de datos.
     *
     * @return void
     */
    private function resetInput()
    {
        $this->user_id = null;
        $this->client_odoo_id = null;
        $this->name = null;
        $this->contact = null;
        $this->email = null;
        $this->phone = null;
    }

    /**
     * Valida los datos ingresados y crea un nuevo cliente en la base de datos.
     *
     * @return void
     */
    public function store()
    {
        $this->validate([
            'client_odoo_id' => 'required',
            'name' => 'required',
            'contact' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);

        Client::create([
            'user_id' => $this->user_id,
            'client_odoo_id' => $this->client_odoo_id,
            'name' => $this->name,
            'contact' => $this->contact,
            'email' => $this->email,
            'phone' => $this->phone
        ]);

        $this->resetInput();
        $this->emit('closeModal');
        session()->flash('message', 'Cliente creado exitosamente.');
    }

    /**
     * Obtiene los datos de un cliente existente y los asigna a las propiedades relacionadas con la entrada de datos.
     *
     * @param int $id El ID del cliente a editar.
     * @return void
     */
    public function edit($id)
    {
        $record = Client::findOrFail($id);

        $this->selected_id = $id;
        $this->user_id = $record->user_id;
        $this->client_odoo_id = $record->client_odoo_id;
        $this->name = $record->name;
        $this->contact = $record->contact;
        $this->email = $record->email;
        $this->phone = $record->phone;

        $this->updateMode = true;
    }

    /**
     * Valida los datos ingresados y actualiza un cliente existente en la base de datos.
     *
     * @return void
     */
    public function update()
    {
        $this->validate([
            'client_odoo_id' => 'required',
            'name' => 'required',
            'contact' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);

        if ($this->selected_id) {
            $record = Client::find($this->selected_id);
            $record->update([
                'user_id' => $this->user_id,
                'client_odoo_id' => $this->client_odoo_id,
                'name' => $this->name,
                'contact' => $this->contact,
                'email' => $this->email,
                'phone' => $this->phone
            ]);

            $this->resetInput();
            $this->updateMode = false;
            session()->flash('message', 'Cliente actualizado exitosamente.');
        }
    }

    /**
     * Elimina un cliente existente de la base de datos.
     *
     * @param int $id El ID del cliente a eliminar.
     * @return void
     */
    public function destroy($id)
    {
        if ($id) {
            $record = Client::where('id', $id);
            $record->delete();
        }
    }
}
