<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CheckCompanyComponent extends Component
{
    public $company;
    public function render()
    {
        return view('components.cotizador.check-company-component');
    }

    public function changeCompany()
    {
        try {
            $user = auth()->user();
            $user->company_id = $this->company;
            $user->save();
            return 1;
        } catch (Exception $th) {
            $th->getMessage();
            return 2;
        }
    }
}
