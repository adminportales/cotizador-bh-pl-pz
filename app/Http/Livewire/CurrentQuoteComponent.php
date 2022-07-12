<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CurrentQuoteComponent extends Component
{
    public $cotizacionActual, $totalQuote;
    public $discountMount = 0;

    public function mount()
    {
        $this->cotizacionActual = auth()->user()->currentQuote->currentQuoteDetails;
        $this->totalQuote = $this->cotizacionActual->sum('precio_total');
    }

    public function render()
    {
        $total = $this->totalQuote;
        $discount = $this->discountMount;

        return view('pages.catalogo.current-quote-component', ['total' => $total, 'discount' => $discount]);
    }

    /* public function agregarDescuento()
    {
        if (auth()->user()->temporalDiscount) {
            auth()->user()->temporalDiscount()->update(['mount' => (floatval($this->discountMount))]);
        } else {
            auth()->user()->temporalDiscount()->create(['mount' => (floatval($this->discountMount))]);
        }
    } */
}
