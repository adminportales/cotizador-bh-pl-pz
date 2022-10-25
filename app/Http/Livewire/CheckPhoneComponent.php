<?php

namespace App\Http\Livewire;

use Exception;
use Livewire\Component;

class CheckPhoneComponent extends Component
{
    public function render()
    {
        return view('livewire.check-phone-component');
    }

    public function changePhone($phone)
    {
        try {
            $user = auth()->user();
            $user->phone = $phone;
            $user->save();
            return 1;
        } catch (Exception $th) {
            $th->getMessage();
            return 2;
        }
    }
}
