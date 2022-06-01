<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CurrentQuoteComponent extends Component
{
    public $cotizacionActual, $totalQuote;
    public $discountType, $discountMount = 0;

    public function mount()
    {
        $this->cotizacionActual = auth()->user()->currentQuote;
        $this->totalQuote = auth()->user()->currentQuote()->sum('precio_total');
    }

    public function render()
    {
        $total = $this->totalQuote;
        if ($this->discountType == 'f') {
            $total = $total - (float) $this->discountMount;
        }

        if ($this->discountType == 'p') {
            $total = round($total - ($total * ((float) $this->discountMount / 100)), 2);
        }

        return view('pages.catalogo.current-quote-component', ['total' => $total]);
    }

    public function saveQuote()
    {

    }
}
