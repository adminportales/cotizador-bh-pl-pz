<?php

namespace App\Http\Livewire\Components;

use Exception;
use Livewire\Component;

/**
 * Clase CheckPhoneComponent
 *
 * Esta clase representa un componente Livewire para verificar y cambiar el número de teléfono de un usuario.
 */
class CheckPhoneComponent extends Component
{
    /**
     * El número de teléfono actual del usuario.
     *
     * @var string
     */
    public $phone;

    /**
     * Renderiza el componente.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('components.cotizador.check-phone-component');
    }

    /**
     * Cambia el número de teléfono del usuario.
     *
     * @return int
     */
    public function changePhone()
    {
        try {
            $user = auth()->user();
            $user->phone = $this->phone;
            $user->save();
            return 1;
        } catch (Exception $th) {
            $th->getMessage();
            return 2;
        }
    }
}
