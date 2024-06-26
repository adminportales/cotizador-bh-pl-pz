<?php

namespace App\Http\Livewire\Cotizador;

use App\Models\Client;
use App\Models\QuoteInformation;
use App\Models\User;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\WithFileUploads;

/**
 * Clase que representa el componente de edición de información del cliente en el cotizador.
 */
class EditInformationClientComponent extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;

    public $quoteInfo, $quote;
    public $tipoCliente, $clienteSeleccionado = '', $nombre, $empresa, $email, $telefono, $celular, $oportunidad, $rank = '', $departamento, $informacion, $clients,  $logo,  $ivaByItem, $showTotal, $typeDays, $taxFee, $shelfLife;

    /**
     * Método que se ejecuta al inicializar el componente.
     * Asigna los valores correspondientes a las propiedades del componente
     * utilizando los datos de la cotización y la información del cliente.
     */
    public function mount()
    {
        $this->ivaByItem = $this->quote->iva_by_item;
        $this->showTotal = $this->quote->show_total;
        $this->typeDays = $this->quote->type_days;
        $this->nombre = $this->quoteInfo->name;
        $this->empresa = $this->quoteInfo->company;
        $this->email = $this->quoteInfo->email;
        $this->telefono = $this->quoteInfo->landline;
        $this->celular = $this->quoteInfo->cell_phone;
        $this->oportunidad = $this->quoteInfo->oportunity;
        $this->rank = $this->quoteInfo->rank;
        $this->departamento = $this->quoteInfo->department;
        $this->informacion = $this->quoteInfo->information;
        $this->taxFee = $this->quoteInfo->taxFee;
        $this->shelfLife = $this->quoteInfo->shelfLife;
    }

    /**
     * Renderiza el componente de edición de información del cliente.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        if ($this->taxFee > 99)
            $this->taxFee = 99;
        return view('cotizador.ver_cotizacion.edit-information-client-component');
    }

    /**
     * Guarda la cotización y la información del cliente.
     *
     * Este método guarda la cotización y la información del cliente en la base de datos.
     * Realiza validaciones de los campos requeridos y actualiza los datos de la cotización.
     * También guarda la información del cliente y los productos de la cotización.
     * Finalmente, emite un evento para actualizar la información de la cotización y muestra un modal al cliente.
     *
     * @return void
     */
    public function guardarCotizacion()
    {
        $this->authorize('update', $this->quote);
        $this->validate([
            // 'tipoCliente' => 'rsequired',
            'email' => 'required|email',
            'telefono' => 'required|numeric',
            'celular' => 'required|numeric',
            'oportunidad' => 'required',
            'rank' => 'required',
        ]);

        $pathLogo = null;
        if ($this->logo != null) {
            $name = time() . $this->empresa .  $this->logo->getClientOriginalExtension();
            $pathLogo = 'storage/logos/' . $name;
            $this->logo->storeAs('public/logos', $name);
            // Guardar La cotizacion
        }

        $this->quote->update([
            'iva_by_item' => boolval($this->ivaByItem),
            'show_total' => boolval($this->showTotal),
            'type_days' => boolval($this->typeDays),
            'logo' => $pathLogo,
        ]);
        // Guardar la Info de la cotizacion
        $quoteInfo = QuoteInformation::create([
            'name' => $this->nombre,
            'company' => $this->quoteInfo->company,
            'email' => $this->email,
            'landline' => $this->telefono,
            'cell_phone' => $this->celular,
            'oportunity' => $this->oportunidad,
            'rank' => $this->rank,
            'department' => $this->departamento,
            'information' => $this->informacion,
            'tax_fee' => $this->taxFee > 0 ? $this->taxFee : null,
            'shelf_life' => trim($this->shelfLife) == "" ? null : $this->shelfLife,
        ]);


        $latestProductos = $this->quote->latestQuotesUpdate->quoteProducts;
        $newQuoteUpdate =  $this->quote->quotesUpdate()->create([
            'quote_information_id' => $quoteInfo->id,
            'quote_discount_id' => $this->quote->latestQuotesUpdate->quote_discount_id,
            'type' => "information"
        ]);
        foreach ($latestProductos as $product) {
            $newQuoteUpdate->quoteProducts()->create([
                "product" => $product->product,
                "technique" => $product->technique,
                "prices_techniques" => $product->prices_techniques,
                "new_description" => $product->new_description,
                "color_logos" => $product->color_logos,
                "costo_indirecto" => $product->costo_indirecto,
                "utilidad" => $product->utilidad,
                "dias_entrega" => $product->dias_entrega,
                "type_days" => $product->type_days,
                "cantidad" => $product->cantidad,
                "precio_unitario" => $product->precio_unitario,
                "precio_total" => $product->precio_total,
                "quote_by_scales" => $product->quote_by_scales,
                "scales_info" => $product->scales_info,
            ]);
        }
        $this->emit('updateQuoteInfo', $quoteInfo);
        $this->dispatchBrowserEvent('showModalInfoClient');
    }
}
