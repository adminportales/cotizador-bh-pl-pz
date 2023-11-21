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
            $quote = null;
            if (!auth()->user()->currentQuoteActive) {
                $quote = auth()->user()->currentQuotes()->first();
                $quote->active = 1;
                $quote->save();
            } else {
                $quote = auth()->user()->currentQuoteActive;
            }
            $this->total = count($quote->currentQuoteDetails);
        }
    }
    public function render()
    {
        return view('components.cotizador.count-cart-quote');
    }
}
