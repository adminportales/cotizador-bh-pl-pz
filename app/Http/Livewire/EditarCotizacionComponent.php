<?php

namespace App\Http\Livewire;

use App\Models\PricesTechnique;
use App\Models\Quote;
use App\Models\QuoteDiscount;
use Livewire\Component;

class EditarCotizacionComponent extends Component
{
    // Informaicon de los descuentos
    public $discount, $value, $type, $discountStatus;
    // Informaicon de los descuentos

    public $quote, $puedeEditar = false, $inputDiscount;

    protected $listeners = ['productAdded' => 'addProducto', 'puedeEditar' => 'editar', 'productUpdate' => 'updateProduct'];

    // Variables Editables
    public $listNewProducts = [], $listUpdateCurrent = [], $listDeleteCurrent = [], $newDiscount;

    public function mount()
    {
        $discount = $this->quote->latestQuotesUpdate->quoteDiscount;
        $this->value = $discount->value;
        $this->type = $discount->type;
        $this->discountStatus = $discount->discount;
        // dd($this->type, $this->value, $this->discountStatus);
    }

    public function render()
    {
        $quote = Quote::find($this->quote->id);
        return view('livewire.editar-cotizacion-component', ['quote' => $quote]);
    }

    public function editar()
    {
        $this->puedeEditar = !$this->puedeEditar;
        if (!$this->puedeEditar) {
            $this->listNewProducts = [];
            $this->listUpdateCurrent = [];
            $this->listDeleteCurrent = [];
        }
        // $this->dispatchBrowserEvent('show-modal')
    }

    public function editarProducto($product, $isNew = false)
    {
        $productEdit = '';
        if ($isNew) {
            foreach ($this->listNewProducts as $newProduct) {
                if ($newProduct['idNewQuote'] == $product) {
                    $productEdit = $newProduct;
                }
            }
        } else {
            $productEdit = $product;
        }
        $this->emit('editProduct', ['productEdit' => $productEdit, 'isNew' => $isNew]);
        $this->dispatchBrowserEvent('showModalEditar');
    }

    public function addProducto($productAdded)
    {
        array_push($this->listNewProducts, $productAdded);
    }
    public function updateProduct($productUpdate)
    {
        array_push($this->listUpdateCurrent, $productUpdate);
    }
    public function deleteProducto($productDeleted)
    {
        array_push($this->listDeleteCurrent, $productDeleted);
    }
    public function deleteNewProducto($productDeleted)
    {
        $newArray = [];
        foreach ($this->listNewProducts as $newProduct) {
            if ($newProduct['idNewQuote'] != $productDeleted) {
                array_push($newArray, $newProduct);
            }
        }
        $this->listNewProducts = $newArray;
    }

    public function guardar()
    {
        // Obtener los productos
        $latestProductos = $this->quote->latestQuotesUpdate->quoteProducts;

        // Crear la nueva cotizacion
        $newQuoteUpdate =  $this->quote->quotesUpdate()->create([
            'quote_information_id' => $this->quote->latestQuotesUpdate->quote_information_id,
            'quote_discount_id' => $this->quote->latestQuotesUpdate->quote_discount_id,
        ]);

        // Buscar que productos fueron elimiandos y sarcarlos de la lista de productos
        if (count($this->listDeleteCurrent) > 0) {
            foreach ($latestProductos as $key => $productQuote) {
                for ($j = 0; $j < count($this->listDeleteCurrent); $j++) {
                    if ($productQuote->id == $this->listDeleteCurrent[$j]['id']) {
                        unset($latestProductos[$key]);
                        break;
                    }
                }
            }
        }

        // Actualizar las cotizaciones editadas

        // Agregar los productos de la nueva lista que tiene los productos editados y no estan los elimiandos
        foreach ($latestProductos as $productQuote) {
            $newQuoteUpdate->quoteProducts()->create([
                "product" => $productQuote->product,
                "technique" => $productQuote->technique,
                "prices_techniques" => $productQuote->prices_techniques,
                "color_logos" => $productQuote->color_logos,
                "costo_indirecto" => $productQuote->costo_indirecto,
                "utilidad" => $productQuote->utilidad,
                "dias_entrega" => $productQuote->dias_entrega,
                "cantidad" => $productQuote->cantidad,
                "precio_unitario" => $productQuote->precio_unitario,
                "precio_total" => $productQuote->precio_total,
            ]);
        }

        // Agregar los productos nuevos
        if (count($this->listNewProducts) > 0) {
            foreach ($this->listNewProducts as $item) {
                $tecnica = PricesTechnique::find($item['prices_techniques_id']);
                $price_tecnica =  $tecnica->precio;
                $material = $tecnica->sizeMaterialTechnique->materialTechnique->material->nombre;
                $material_id = $tecnica->sizeMaterialTechnique->materialTechnique->material->id;
                $tecnica_nombre = $tecnica->sizeMaterialTechnique->materialTechnique->technique->nombre;
                $tecnica_id = $tecnica->sizeMaterialTechnique->materialTechnique->technique->id;
                $size = $tecnica->sizeMaterialTechnique->size->nombre;
                $size_id = $tecnica->sizeMaterialTechnique->size->id;
                $infoTecnica = [
                    'material_id' => $material_id,
                    'material' => $material,
                    'tecnica' => $tecnica_nombre,
                    'tecnica_id' => $tecnica_id,
                    'size' => $size,
                    'size_id' => $size_id,
                ];
                $newQuoteUpdate->quoteProducts()->create([
                    'product' => $item['product'],
                    'technique' =>  json_encode($infoTecnica),
                    'prices_techniques' => $price_tecnica,
                    'color_logos' => $item['color_logos'],
                    'costo_indirecto' => $item['costo_indirecto'],
                    'utilidad' => $item['utilidad'],
                    'dias_entrega' => $item['dias_entrega'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'precio_total' => $item['precio_total']
                ]);
            }
        }
        $this->quote = Quote::find($this->quote->id);
        $this->puedeEditar = false;
        $this->listNewProducts = [];
        $this->listUpdateCurrent = [];
        $this->listDeleteCurrent = [];
        // dd('Guardado');
    }

    public function updateDiscount()
    {
        $dataDiscount = [
            'discount' => $this->discountStatus,
            'type' => $this->type,
            'value' => $this->value,
        ];

        $quoteDiscount = QuoteDiscount::create($dataDiscount);

        $latestProductos = $this->quote->latestQuotesUpdate->quoteProducts;
        $newQuoteUpdate =  $this->quote->quotesUpdate()->create([
            'quote_information_id' => $this->quote->latestQuotesUpdate->quote_information_id,
            'quote_discount_id' => $quoteDiscount->id,
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
        $this->value = $quoteDiscount->value;
        $this->type = $quoteDiscount->type;
        $this->discountStatus = $quoteDiscount->discount;

        $this->dispatchBrowserEvent('hideModalDiscount');
        session()->flash('message', 'Por favor espere...');
    }
}
