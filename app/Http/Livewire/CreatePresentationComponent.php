<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CreatePresentationComponent extends Component
{
    public $quote;
    public function render()
    {
        return view('livewire.create-presentation-component');
    }
}
