<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;

/**
 * Clase CountCartQuote
 *
 * Esta clase es responsable de contar y mostrar el total de elementos en el carrito de cotización.
 */
class CountCartQuote extends Component
{
    /**
     * @var int $total El total de elementos en el carrito de cotización.
     */
    public $total = 0;

    /**
     * Los listeners para el evento 'currentQuoteAdded'.
     *
     * @var array
     */
    protected $listeners = ['currentQuoteAdded'];

    /**
     * Método que se ejecuta cuando se agrega una cotización actual.
     * Actualiza el total de elementos en el carrito de cotización.
     *
     * @return void
     */
    public function currentQuoteAdded()
    {
        if (count(auth()->user()->currentQuotes) > 0) {
            $this->total = count(auth()->user()->currentQuoteActive->currentQuoteDetails);
        } else {
            $this->total;
        }
    }

    /**
     * Método que se ejecuta al inicializar el componente.
     * Actualiza el total de elementos en el carrito de cotización y establece una cotización activa si no existe.
     *
     * @return void
     */
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

    /**
     * Método que renderiza el componente.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('components.cotizador.count-cart-quote');
    }
}
