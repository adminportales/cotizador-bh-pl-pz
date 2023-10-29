<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;

class CountCartQuote extends Component
{
    public $total = 0;


    protected $listeners = ['currentQuoteAdded'];

    public function currentQuoteAdded()
    {
        if (count(auth()->user()->currentQuotes) > 0) {
            $this->total = count(auth()->user()->currentQuoteActive->currentQuoteDetails);
        } else {
            $this->total;
        }
    }

    public function mount()
    {
        if (count(auth()->user()->currentQuotes) > 0) {
            $this->total = count(auth()->user()->currentQuoteActive->currentQuoteDetails);
        }
    }
    public function render()
    {
        return view('components.cotizador.count-cart-quote');
    }
}
