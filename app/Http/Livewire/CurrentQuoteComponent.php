<?php

namespace App\Http\Livewire;

use App\Models\CurrentQuote;
use App\Models\CurrentQuoteDetails;
use Livewire\Component;

class CurrentQuoteComponent extends Component
{
    public $cotizacionActual, $totalQuote;
    public $discountMount = 0;

    public $value, $type, $currentQuoteEdit;

    public function mount()
    {
        $this->cotizacionActual = auth()->user()->currentQuote->currentQuoteDetails;
        $this->totalQuote = $this->cotizacionActual->sum('precio_total');
    }

    public function render()
    {
        $this->cotizacionActual = auth()->user()->currentQuote->currentQuoteDetails;
        $this->totalQuote = $this->cotizacionActual->sum('precio_total');

        $total = $this->totalQuote;
        if (auth()->user()->currentQuote->type == 'Fijo') {
            $this->discountMount = auth()->user()->currentQuote->value;
        } else {
            $this->discountMount = round((($this->totalQuote / 100) * auth()->user()->currentQuote->value), 2);
        }
        $discount = $this->discountMount;

        return view('pages.catalogo.current-quote-component', ['total' => $total, 'discount' => $discount]);
    }

    public function addDiscount()
    {
        if (strlen($this->type) > 0 && (int)$this->value > 0) {
            auth()->user()->currentQuote->type = $this->type;
            auth()->user()->currentQuote->value = $this->value;
            auth()->user()->currentQuote->discount = true;
            auth()->user()->currentQuote->save();

            $this->discountMount = 0;
            if (auth()->user()->currentQuote->type == 'Fijo') {
                $this->discountMount = auth()->user()->currentQuote->value;
            } else {
                $this->discountMount = round((($this->totalQuote / 100) * auth()->user()->currentQuote->value), 2);
            }

            $this->dispatchBrowserEvent('hide-modal-discount');
        }
    }

    public function editar(CurrentQuoteDetails $cqd)
    {
        $this->currentQuoteEdit = $cqd;
        $this->dispatchBrowserEvent('show-modal-edit-product');
    }

    public function eliminar(CurrentQuoteDetails $cqd)
    {
        $cqd->delete();
    }
}
