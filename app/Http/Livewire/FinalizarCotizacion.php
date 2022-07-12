<?php

namespace App\Http\Livewire;

use App\Models\PricesTechnique;
use Livewire\Component;

class FinalizarCotizacion extends Component
{
    public $tipoCliente, $clienteSeleccionado = '', $nombre, $empresa, $email, $telefono, $celular, $oportunidad, $rank = '', $departamento, $informacion;
    public function render()
    {
        return view('pages.catalogo.finalizar-cotizacion');
    }
    public function guardarCotizacion()
    {
        $this->validate([
            'tipoCliente' => 'required',
        ]);

        if ($this->tipoCliente == 'crear') {
            $this->validate([
                'nombre' => 'required',
                'empresa' => 'required',
            ]);
        } else {
            $this->validate([
                'clienteSeleccionado' => 'required',
            ]);
            $this->nombre = 'Nombre de Oddo';
            $this->empresa = 'Empresa de Oddo';
        }

        $validatedData = $this->validate([
            'tipoCliente' => 'required',
            'email' => 'required|email',
            'telefono' => 'required|numeric',
            'celular' => 'required|numeric',
            'oportunidad' => 'required',
            'rank' => 'required',
            'departamento' => 'required',
            'informacion' => 'required',
        ]);
        // Guardar La cotizacion
        $quote = auth()->user()->quotes()->create([
            'lead' => '487'
        ]);

        $quoteInfo = $quote->quotesInformation()->create([
            'name' => $this->nombre,
            'company' => $this->empresa,
            'email' => $this->email,
            'landline' => $this->telefono,
            'cell_phone' => $this->celular,
            'oportunity' => $this->oportunidad,
            'rank' => $this->rank,
            'department' => $this->departamento,
            'information' => $this->informacion,
        ]);

        // Guardar los productos de la cotizacion
        foreach (auth()->user()->currentQuote->currentQuoteDetails as $item) {
            // TODO: Colocar un array con la data en las tecnicas y productos
            $quoteInfo->quotesProducts()->create([
                'product_id' => $item->product_id,
                'prices_techniques' => PricesTechnique::find($item->prices_techniques_id)->precio,
                'color_logos' => $item->color_logos,
                'costo_indirecto' => $item->costo_indirecto,
                'utilidad' => $item->utilidad,
                'dias_entrega' => $item->dias_entrega,
                'cantidad' => $item->cantidad,
                'precio_unitario' => $item->precio_unitario,
                'precio_total' => $item->precio_total
            ]);
        }
        $data = [
            $this->tipoCliente, $this->clienteSeleccionado, $this->nombre, $this->empresa, $this->email, $this->telefono, $this->celular, $this->oportunidad, $this->rank, $this->departamento, $this->informacion
        ];
    }
}
