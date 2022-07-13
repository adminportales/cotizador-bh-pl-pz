<?php

namespace App\Http\Livewire;

use App\Models\Catalogo\Product;
use App\Models\PricesTechnique;
use Livewire\Component;
use App\Http\Controllers\CotizadorController;

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

        $this->validate([
            'tipoCliente' => 'required',
            'email' => 'required|email',
            'telefono' => 'required|numeric',
            'celular' => 'required|numeric',
            'oportunidad' => 'required',
            'rank' => 'required',
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

            $product = Product::find($item->product_id)->toArray();
            $tecnica = PricesTechnique::find($item->prices_techniques_id);
            $price_tecnica =  $tecnica->precio;
            $material = $tecnica->sizeMaterialTechnique->materialTechnique->material->nombre;
            $tecnica_nombre = $tecnica->sizeMaterialTechnique->materialTechnique->technique->nombre;
            $size = $tecnica->sizeMaterialTechnique->size->nombre;
            $infoTecnica = [
                'tecnica' => $tecnica_nombre,
                'material' => $material,
                'size' => $size,
            ];

            $quoteInfo->quotesProducts()->create([
                'product' => json_encode($product),
                'technique' =>  json_encode($infoTecnica),
                'prices_techniques' => $price_tecnica,
                'color_logos' => $item->color_logos,
                'costo_indirecto' => $item->costo_indirecto,
                'utilidad' => $item->utilidad,
                'dias_entrega' => $item->dias_entrega,
                'cantidad' => $item->cantidad,
                'precio_unitario' => $item->precio_unitario,
                'precio_total' => $item->precio_total
            ]);
        }
        // Eliminar los datos de la cotizacion actual
        auth()->user()->currentQuote->currentQuoteDetails()->delete();
        auth()->user()->currentQuote()->delete();
        return redirect()->action([CotizadorController::class, 'verCotizacion'], ['quote' => $quote->id])->with('message', 'Tu cotizacion se ha guardado exitosamente.');
    }

    public function resetData()
    {
        $this->nombre = '';
        $this->empresa = '';
        $this->email = '';
        $this->telefono = '';
        $this->celular = '';
        $this->oportunidad = '';
        $this->rank = '';
    }
}
