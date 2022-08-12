<?php

namespace App\Http\Livewire;

use App\Models\QuoteInformation;
use Livewire\Component;

class EditInformationClientComponent extends Component
{
    public $quoteInfo, $quote;
    public $tipoCliente, $clienteSeleccionado = '', $nombre, $empresa, $email, $telefono, $celular, $oportunidad, $rank = '', $departamento, $informacion;

    public function mount()
    {
        $this->nombre = $this->quoteInfo->name;
        $this->empresa = $this->quoteInfo->company;
        $this->email = $this->quoteInfo->email;
        $this->telefono = $this->quoteInfo->landline;
        $this->celular = $this->quoteInfo->cell_phone;
        $this->oportunidad = $this->quoteInfo->oportunity;
        $this->rank = $this->quoteInfo->rank;
        $this->departamento = $this->quoteInfo->department;
        $this->informacion = $this->quoteInfo->information;
    }

    public function render()
    {
        return view('livewire.edit-information-client-component');
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

        // Guardar la Info de la cotizacion
        $quoteInfo = QuoteInformation::create([
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

        $latestProductos = $this->quote->latestQuotesUpdate->quoteProducts;
        $newQuoteUpdate =  $this->quote->quotesUpdate()->create([
            'quote_information_id' => $quoteInfo->id,
            'quote_discount_id' => $this->quote->latestQuotesUpdate->quote_discount_id,
        ]);
        foreach ($latestProductos as $product) {
            $newQuoteUpdate->quoteProducts()->create([
                "product" => $product->product,
                "technique" => $product->technique,
                "prices_techniques" => $product->prices_techniques,
                "color_logos" => $product->color_logos,
                "costo_indirecto" => $product->costo_indirecto,
                "utilidad" => $product->utilidad,
                "dias_entrega" => $product->dias_entrega,
                "cantidad" => $product->cantidad,
                "precio_unitario" => $product->precio_unitario,
                "precio_total" => $product->precio_total,
            ]);
        }

        $this->emit('updateQuoteInfo', $quoteInfo);
        $this->dispatchBrowserEvent('showModalInfoClient');
    }
}
