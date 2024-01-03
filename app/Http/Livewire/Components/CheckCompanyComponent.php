<?php

namespace App\Http\Livewire\Components;

use Exception;
use Livewire\Component;

/**
 * Clase CheckCompanyComponent
 *
 * Esta clase representa un componente Livewire para verificar y cambiar la compañía de un usuario.
 */
class CheckCompanyComponent extends Component
{
    /**
     * @var string $company
     * La compañía seleccionada por el usuario.
     */
    public $company;

    /**
     * Renderiza la vista del componente.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('components.cotizador.check-company-component');
    }

    /**
     * Cambia la compañía del usuario actual.
     *
     * @return int
     * Retorna 1 si el cambio de compañía fue exitoso, o 2 si ocurrió un error.
     */
    public function changeCompany()
    {
        try {
            $user = auth()->user();
            $user->company_session = $this->company;
            $user->save();
            return 1;
        } catch (Exception $th) {
            $th->getMessage();
            return 2;
        }
    }
}
