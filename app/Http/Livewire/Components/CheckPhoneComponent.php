<?php

namespace App\Http\Livewire\Components;

use Exception;
use Livewire\Component;

class CheckPhoneComponent extends Component
{
    public $phone;
    public function render()
    {
        return view('components.cotizador.check-phone-component');
    }

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
