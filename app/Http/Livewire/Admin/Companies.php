<?php

namespace App\Http\Livewire\Admin;

use App\Models\Catalogo\Provider;
use App\Models\CompaniePro;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Company;
use Illuminate\Http\Request;


/**
 * Clase Companies
 *
 * Esta clase es responsable de manejar la lógica relacionada con las empresas en el panel de administración.
 */
class Companies extends Component
{
    /**
     * Trait WithPagination
     *
     * Este trait proporciona métodos para la paginación de resultados.
     */
    use WithPagination;

    /**
     * Tema de paginación utilizado para la visualización de la paginación.
     */
    protected $paginationTheme = 'bootstrap';

    /**
     * ID seleccionado.
     */
    public $selected_id;

    /**
     * Palabra clave utilizada para la búsqueda de empresas.
     */
    public $keyWord;

    /**
     * Nombre de la empresa.
     */
    public $name;

    /**
     * Imagen de la empresa.
     */
    public $image;

    /**
     * Gerente de la empresa.
     */
    public $manager;

    /**
     * Correo electrónico de la empresa.
     */
    public $email;

    /**
     * Teléfono de la empresa.
     */
    public $phone;

    /**
     * Proveedores seleccionados.
     */
    public $selectedProveedores = [];

    /**
     * Empresa seleccionada.
     */
    public $companySelected = [];

    /**
     * ID de la empresa.
     */
    public $companyId;

    /**
     * Modo de actualización.
     */
    public $updateMode = false;

    /**
     * IDs de las empresas seleccionadas.
     */
    public $companySelectedIds = [];

    /**
     * Renderiza la vista de empresas.
     *
     * @param Request $request La solicitud HTTP.
     * @return \Illuminate\Contracts\View\View La vista de empresas.
     */
    public function render(Request $request)
    {
        $keyWord = '%' . $this->keyWord . '%';

        // Obtiene las empresas que coinciden con la palabra clave y las paginación.
        $companies = Company::latest()
            ->orWhere('name', 'LIKE', $keyWord)
            ->orWhere('image', 'LIKE', $keyWord)
            ->orWhere('manager', 'LIKE', $keyWord)
            ->orWhere('email', 'LIKE', $keyWord)
            ->orWhere('phone', 'LIKE', $keyWord)
            ->paginate(10);

        // Obtiene los proveedores limitados a 15.
        $proveders = Provider::limit(15)->get();

        $companiesPro = [];

        foreach ($companies as $company) {
            // Obtiene los proveedores seleccionados para la empresa actual y los almacena en un array utilizando el ID de la empresa como clave.
            $companiesPro[$company->id] = CompaniePro::where('companie_id', $company->id)->pluck('provider_id')->toArray();
        }

        return view('admin.companies.view', [
            'companies' => $companies,
            'proveders' => $proveders,
            'companiesPro' => $companiesPro
        ]);
    }

    /**
     * Establece el ID de la empresa seleccionada.
     *
     * @param int $companyId El ID de la empresa seleccionada.
     * @return void
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;
    }

    /**
     * Guarda los proveedores seleccionados.
     *
     * @param array $selectedProveedores Los proveedores seleccionados.
     * @return void
     */
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

    /**
     * Verifica si un proveedor está seleccionado.
     *
     * @param int $providerId El ID del proveedor.
     * @return bool True si el proveedor está seleccionado, de lo contrario False.
     */
    public function isProviderSelected($providerId)
    {
        return $this->providerIds->contains($providerId);
    }

    /**
     * Cancela la operación actual.
     *
     * @return void
     */
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    /**
     * Restablece los valores de entrada.
     *
     * @return void
     */
    private function resetInput()
    {
        $this->name = null;
        $this->image = null;
        $this->manager = null;
        $this->email = null;
        $this->phone = null;
    }

    /**
     * Edita una empresa.
     *
     * @param int $id El ID de la empresa a editar.
     * @return void
     */
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

    /**
     * Actualiza una empresa.
     *
     * @return void
     */
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
            session()->flash('message', 'Empresa actualizada correctamente.');
        }
    }
}
