<?php

namespace App\Http\Livewire;

use App\Models\Catalogo\Provider;
use App\Models\CompaniePro;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Company;
use Illuminate\Http\Request;


class Companies extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $image, $manager, $email, $phone;
    public $selectedProveedores = [];
    public $companySelected = [];
    public $companyId;
    public $updateMode = false;
    public $companySelectedIds = [];

    public function render(Request $request)
    {

        $keyWord = '%' . $this->keyWord . '%';
        $companies = Company::latest()
            ->orWhere('name', 'LIKE', $keyWord)
            ->orWhere('image', 'LIKE', $keyWord)
            ->orWhere('manager', 'LIKE', $keyWord)
            ->orWhere('email', 'LIKE', $keyWord)
            ->orWhere('phone', 'LIKE', $keyWord)
            ->paginate(10);



        $proveders = Provider::limit(15)->get();
        $companiesPro = [];

        foreach ($companies as $company) {
            // Get the selected providers for the current company and store them in the array using the company ID as the key.
            $companiesPro[$company->id] = CompaniePro::where('companie_id', $company->id)->pluck('provider_id')->toArray();
        }

        return view('livewire.companies.view', [
            'companies' => $companies,
            'proveders' => $proveders,
            'companiesPro' => $companiesPro
        ]);
    }
    public function setCompanyId($companyId)
    {

        $this->companyId = $companyId;
    }
    public function saveSelectedProveedores($selectedProveedores)
    {
        $companiePro = CompaniePro::where('companie_id', $this->companyId)
            ->where('provider_id', $selectedProveedores)
            ->first();

        if ($companiePro) {
            $companiePro->delete();
            session()->flash('message', 'Proveedor eliminado correctamente.');
        } else {
            $companiePro = new CompaniePro();
            $companiePro->companie_id = $this->companyId;
            $companiePro->provider_id = $selectedProveedores;
            $companiePro->save();
            session()->flash('message', 'Proveedor asociado correctamente.');
        }
    }
    public function isProviderSelected($providerId)
    {
        return $this->providerIds->contains($providerId);
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
