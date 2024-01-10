<?php

namespace App\Http\Livewire\Admin;

use App\Models\Company;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Notifications\RegisteredUser;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Clase Users
 *
 * Esta clase representa el componente Livewire para la gestión de usuarios en el panel de administración.
 */
class Users extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $email, $company_id, $companies, $keySearch, $allUsers, $user, $proveder,
        // $id_odoo,
        $password;
    public $updateMode = false;

    /**
     * Renderiza la vista del componente Users.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $this->companies = Company::all();
        //dd($this->companies);
        $keyWord = '%' . $this->keyWord . '%';
        $this->allUsers = User::where('visible', 1)->get();
        return view('admin.users.view', [
            'users' => User::latest()
                ->orWhere('name', 'LIKE', $keyWord)
                ->orWhere('email', 'LIKE', $keyWord)
                ->paginate(50),
        ]);
    }

    /**
     * Envía el acceso al usuario especificado.
     *
     * @param int $id El ID del usuario
     * @return void
     */
    public function sendAccess($id)
    {
        $user = User::find($id);
        $pass = Str::random(8);
        $user->password = Hash::make($pass);
        $user->save();
        $dataNotification = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $pass,
            'urlEmail' => url('/loginEmail?email=' . $user->email . '&password=' . $pass)
        ];
        $user->notify(new RegisteredUser($dataNotification));
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
        $this->name = null;
        $this->email = null;
        $this->company_id = null;
        // $this->id_odoo = null;
        $this->password = null;
    }

    /**
     * Almacena un nuevo usuario en la base de datos.
     *
     * @return void
     */
    public function store()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'company_id' => $this->company_id
        ]);
        // $user->info()->create([
        //     'odoo_id' => $this->id_odoo,
        //     'company_id' => $this->company_id,
        // ]);

        $this->resetInput();
        $this->emit('closeModal');
        session()->flash('message', 'User Successfully created.');
    }

    /**
     * Edita el usuario especificado.
     *
     * @param int $id El ID del usuario
     * @return void
     */
    public function edit($id)
    {
        $record = User::findOrFail($id);
        $this->user = $record;
        $this->selected_id = $id;
        $this->name = $record->name;
        $this->email = $record->email;
        $this->company_id = $record->company_id;
        // if ($record->info) {
        //     $this->id_odoo = $record->info->odoo_id;
        // }

        $this->updateMode = true;
    }

    /**
     * Actualiza el usuario especificado en la base de datos.
     *
     * @return void
     */
    public function update()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

        if ($this->selected_id) {
            $record = User::find($this->selected_id);
            $record->update([
                'name' => $this->name,
                'email' => $this->email,
                'company_id' => $this->company_id
            ]);

            // if (!$record->info) {
            //     $record->info()->create([
            //         'odoo_id' => $this->id_odoo,
            //         'company_id' => $this->company_id,
            //     ]);
            // } else {
            //     $record->info()->update([
            //         'odoo_id' => $this->id_odoo,
            //         'company_id' => $this->company_id,
            //     ]);
            // }

            $this->resetInput();
            $this->updateMode = false;
            session()->flash('message', 'User Successfully updated.');
        }
    }

    /**
     * Elimina el usuario especificado de la base de datos.
     *
     * @param int $id El ID del usuario
     * @return void
     */
    public function destroy($id)
    {
        if ($id) {
            $record = User::where('id', $id);
            $record->delete();
        }
    }

    /**
     * Envía el acceso a todos los usuarios visibles.
     *
     * @return int|string
     */
    public function sendAccessAll()
    {
        $users = User::where('visible', true)->get();
        $errors = [];
        foreach ($users as $user) {
            $pass = Str::random(8);
            $user->password = Hash::make($pass);
            $user->save();
            $dataNotification = [
                'name' => $user->name . ' ' . $user->lastname,
                'email' => $user->email,
                'password' => $pass,
                'urlEmail' => url('/loginEmail?email=' . $user->email . '&password=' . $pass)
            ];
            try {
                $user->notify(new RegisteredUser($dataNotification));
            } catch (Exception $e) {
                array_push($errors, [$user->email, json_encode($e->getMessage())]);
            }
        }
        if (count($errors) == 0) {
            return 1;
        } else {
            return json_encode($errors);
        }
    }

    /**
     * Actualiza el asistente del usuario especificado.
     *
     * @param int $user_id El ID del usuario
     * @return void
     */
    public function updateAssistant($user_id)
    {
        $user = User::find($user_id);
        $this->user->assistants()->toggle($user);
        $this->user = $this->user;
        $this->user->assistants;

        session()->flash('updateSites', 'Actualizacion correcta.');
    }
}
