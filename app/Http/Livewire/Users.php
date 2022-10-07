<?php

namespace App\Http\Livewire;

use App\Models\Company;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Users extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $email, $company_id, $companies, $id_odoo, $password;
    public $updateMode = false;

    public function render()
    {
        $this->companies = Company::all();
        $keyWord = '%' . $this->keyWord . '%';
        return view('livewire.users.view', [
            'users' => User::latest()
                ->orWhere('name', 'LIKE', $keyWord)
                ->orWhere('email', 'LIKE', $keyWord)
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
        $this->name = null;
        $this->email = null;
        $this->company_id = null;
        $this->id_odoo = null;
        $this->password = null;
    }

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
        $user->info()->create([
            'odoo_id' => $this->id_odoo,
            'company_id' => $this->company_id,
        ]);

        $this->resetInput();
        $this->emit('closeModal');
        session()->flash('message', 'User Successfully created.');
    }

    public function edit($id)
    {
        $record = User::findOrFail($id);

        $this->selected_id = $id;
        $this->name = $record->name;
        $this->email = $record->email;
        $this->company_id = $record->company_id;
        if ($record->info) {
            $this->id_odoo = $record->info->odoo_id;
        }

        $this->updateMode = true;
    }

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

            if (!$record->info) {
                $record->info()->create([
                    'odoo_id' => $this->id_odoo,
                    'company_id' => $this->company_id,
                ]);
            } else {
                $record->info()->update([
                    'odoo_id' => $this->id_odoo,
                    'company_id' => $this->company_id,
                ]);
            }

            $this->resetInput();
            $this->updateMode = false;
            session()->flash('message', 'User Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = User::where('id', $id);
            $record->delete();
        }
    }
}
