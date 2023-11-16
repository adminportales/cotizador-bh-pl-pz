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

        //dd(auth()->user()->currentQuotes);

        if (count(auth()->user()->currentQuotes) > 0) {
            if (!auth()->user()->currentQuoteActive) {
                $quote = auth()->user()->currentQuotes()->first();
                $quote->active = 1;
                $quote->save();
            }
            $this->total = count(auth()->user()->currentQuoteActive->currentQuoteDetails);
        }
    }
    public function render()
    {
        return view('components.cotizador.count-cart-quote');
    }
}
