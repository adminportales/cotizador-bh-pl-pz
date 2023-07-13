<?php

namespace App\Http\Livewire;

use App\Models\Catalogo\Provider;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Company;
use Illuminate\Support\Facades\Request;

class Companies extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $image, $manager, $email, $phone;
    public $updateMode = false;

    public function render()
    {

        $keyWord = '%' . $this->keyWord . '%';
        $companies = Company::latest()
            ->orWhere('name', 'LIKE', $keyWord)
            ->orWhere('image', 'LIKE', $keyWord)
            ->orWhere('manager', 'LIKE', $keyWord)
            ->orWhere('email', 'LIKE', $keyWord)
            ->orWhere('phone', 'LIKE', $keyWord)
            ->paginate(10);
        $proveders = Provider::all();

        return view('livewire.companies.view', [
            'companies' => $companies, 'proveders' => $proveders
        ]);
    }
    public function guardarRespuesta(Request $request)
    {

    }
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    private function resetInput()
    {
        $this->name = null;
        $this->image = null;
        $this->manager = null;
        $this->email = null;
        $this->phone = null;
    }




    public function edit($id)
    {
        $record = Company::findOrFail($id);

        $this->selected_id = $id;
        $this->name = $record->name;
        $this->image = $record->image;
        $this->manager = $record->manager;
        $this->email = $record->email;
        $this->phone = $record->phone;

        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'image' => 'required',
            'manager' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);

        if ($this->selected_id) {
            $record = Company::find($this->selected_id);
            $record->update([
                'name' => $this->name,
                'image' => $this->image,
                'manager' => $this->manager,
                'email' => $this->email,
                'phone' => $this->phone
            ]);

            $this->resetInput();
            $this->updateMode = false;
            session()->flash('message', 'Company Successfully updated.');
        }
    }
}
