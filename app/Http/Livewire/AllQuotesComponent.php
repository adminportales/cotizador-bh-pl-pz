<?php

namespace App\Http\Livewire;

use App\Models\Quote;
use Livewire\Component;

class AllQuotesComponent extends Component
{
    public $quotes;
    public function render()
    {
        $this->quotes = Quote::orderBy('created_at', 'ASC')->get();
        return view('livewire.all-quotes-component');
    }
}
