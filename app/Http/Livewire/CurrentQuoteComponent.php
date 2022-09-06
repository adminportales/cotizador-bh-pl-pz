<?php

namespace App\Http\Livewire;

use App\Models\Catalogo\Product;
use App\Models\CurrentQuote;
use App\Models\CurrentQuoteDetails;
use Livewire\Component;

class CurrentQuoteComponent extends Component
{
    public $cotizacionActual, $totalQuote;
    public $discountMount = 0;

    public $value, $type;

    protected $listeners = ['updateProductCurrent' => 'resetData'];

    public function mount()
    {
        $this->cotizacionActual = auth()->user()->currentQuote->currentQuoteDetails;
        $this->totalQuote = $this->cotizacionActual->sum('precio_total');
        if (auth()->user()->currentQuote->discount) {
            $this->value = auth()->user()->currentQuote->value;
            $this->type = auth()->user()->currentQuote->type;
        } else {
            $this->value = 0;
            $this->type = '';
        }
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

    public function eliminarDescuento()
    {
        auth()->user()->currentQuote->type = '';
        auth()->user()->currentQuote->value = 0;
        auth()->user()->currentQuote->discount = false;
        auth()->user()->currentQuote->save();
        if (auth()->user()->currentQuote->discount) {
            $this->value = auth()->user()->currentQuote->value;
            $this->type = auth()->user()->currentQuote->type;
        } else {
            $this->value = 0;
            $this->type = '';
        }
        $this->dispatchBrowserEvent('hide-modal-discount');
    }

    public function eliminar(CurrentQuoteDetails $cqd)
    {
        $cqd->delete();
        if (count(auth()->user()->currentQuote->currentQuoteDetails) < 1) {
            auth()->user()->currentQuote->delete();
        }
    }
    public function resetData()
    {
        $this->cotizacionActual = auth()->user()->currentQuote->currentQuoteDetails;
    }
}
