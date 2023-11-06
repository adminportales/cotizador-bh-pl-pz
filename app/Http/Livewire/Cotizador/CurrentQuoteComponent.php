<?php

namespace App\Http\Livewire\Cotizador;

use App\Models\CurrentQuoteDetails;
use Exception;
use Livewire\Component;

class CurrentQuoteComponent extends Component
{
    public $allQuotes, $cotizacionActual;

    public $listaProductos, $totalQuote;
    public $discountMount = 0;

    public $value, $type;
    public $quoteEdit, $quoteShow;

    public $nameQuote;

    protected $listeners = ['updateProductCurrent' => 'resetData'];

    public function mount()
    {
        $this->allQuotes = auth()->user()->currentQuotes;
    }

    public function render()
    {
        $this->cotizacionActual = auth()->user()->currentQuoteActive;
        $this->listaProductos = $this->cotizacionActual ?  $this->cotizacionActual->currentQuoteDetails : [];
        $this->totalQuote = 0;

        if ($this->cotizacionActual) {
            if ($this->cotizacionActual->discount) {
                $this->value = auth()->user()->currentQuote->value;
                $this->type = auth()->user()->currentQuote->type;
            } else {
                $this->value = 0;
                $this->type = '';
            }


            foreach ($this->listaProductos as $productToSum) {
                if ($productToSum->quote_by_scales) {
                    try {
                        $this->totalQuote = $this->totalQuote + floatval(json_decode($productToSum->scales_info)[0]->total_price);
                    } catch (Exception $e) {
                        $this->totalQuote = $this->totalQuote + 0;
                    }
                } else {
                    $this->totalQuote = $this->totalQuote + $productToSum->precio_total;
                }
            }

            if ($this->cotizacionActual->type == 'Fijo') {
                $this->discountMount = $this->cotizacionActual->value;
            } else {
                $this->discountMount = round((($this->totalQuote / 100) * $this->cotizacionActual->value), 2);
            }
        }
        $total = $this->totalQuote;
        $discount = $this->discountMount;

        return view('cotizador.cotizacion_actual.current-quote-component', ['total' => $total, 'discount' => $discount, 'cotizacion' => $this->cotizacionActual]);
    }

    public function edit($quote_id)
    {
        $this->quoteEdit = CurrentQuoteDetails::find($quote_id);
        $this->dispatchBrowserEvent('show-modal-edit');
    }

    public function show($quote_id)
    {
        $this->quoteShow = CurrentQuoteDetails::find($quote_id);
        $this->dispatchBrowserEvent('show-modal-show');
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
            auth()->user()->currentQuotes->delete();
        }
        $this->resetData();
        $this->emit('currentQuoteAdded');
    }
    public function resetData()
    {
        $this->listaProductos = auth()->user()->currentQuote->currentQuoteDetails;
        $this->quoteEdit = null;
        $this->quoteShow = null;
    }

    public function addQuote()
    {
        $this->validate(['nameQuote' => 'required']);
        $currentQuote = auth()->user()->currentQuotes()->create([
            'discount' => false,
            'name' => $this->nameQuote
        ]);
        foreach (auth()->user()->currentQuotes as $i) {
            $i->active = 0;
            $i->save();
        }
        $currentQuote->active = 1;
        $currentQuote->save();
        $this->nameQuote = '';
        $this->dispatchBrowserEvent('hideModalAddQuote');
        $this->allQuotes = auth()->user()->currentQuotes;
    }

    public function selectQuoteActive($cqid)
    {
        foreach (auth()->user()->currentQuotes as $i) {
            $i->active = $i->id == $cqid ? 1 : 0;
            $i->save();
        }
        $this->allQuotes = auth()->user()->currentQuotes;
    }
}