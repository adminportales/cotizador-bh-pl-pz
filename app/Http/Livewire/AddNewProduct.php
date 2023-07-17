<?php

namespace App\Http\Livewire;

use App\Http\Controllers\CotizadorController;
use App\Models\Catalogo\Color;
use App\Models\Catalogo\Product;
use App\Models\Catalogo\Provider;
use App\Models\UserProduct;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddNewProduct extends Component
{
    use WithFileUploads;

    public $nombre, $descripcion, $precio, $stock, $color, $proveedor, $imagen;

    public $inicial, $final, $costo;

    public $priceScales, $infoScales = [], $priceScalesComplete = [],  $cantidadEscala, $precioTecnicaEscala, $editScale = false, $itemEditScale = null;

    public function render()
    {
        return view('livewire.add-new-product');
    }

    public function guardar()
    {
        $this->validate([
            'nombre' => 'required|max:100',
            'descripcion' => 'required',
            'precio' => 'required',
            'stock' => 'required',
            'color' => 'required',
            'proveedor' => 'required',
            'imagen' => 'required|image|max:1536',
        ]);

        $maxSKU = Product::max('internal_sku');
        $idSku = null;
        if (!$maxSKU) {
            $idSku = 1;
        } else {
            $idSku = (int) explode('-', $maxSKU)[1];
            $idSku++;
        }
        $color = null;
        $slug = mb_strtolower(str_replace(' ', '-', $this->color));
        $color = Color::where("slug", $slug)->first();
        if (!$color) {
            $color = Color::create([
                'color' => ucfirst($this->color), 'slug' => $slug,
            ]);
        }

        $proveedor = Provider::firstOrCreate([
            'company' => "Registro Personal"
        ], [
            'email' => "no-mail",
            'phone' => 000,
            'contact' => "False",
            'discount' => 0
        ]);

        $pathImagen = null;
        if ($this->imagen != null) {
            $name = time() . $this->nombre . '.' .  $this->imagen->getClientOriginalExtension();
            $pathImagen = url('') . '/storage/media/' . $name;
            $this->imagen->storeAs('public/media', $name);
            // Guardar La cotizacion
        }

        $newProduct = Product::create([
            'internal_sku' => "PROM-" . str_pad($idSku, 7, "0", STR_PAD_LEFT),
            'sku' => 0000,
            'name' => $this->nombre,
            'price' =>   $this->precio,
            'description' =>  $this->descripcion,
            'stock' => $this->stock,
            'producto_promocion' => false,
            'descuento' => 0,
            'producto_nuevo' =>  false,
            'precio_unico' => true,
            'type_id' => 3,
            'color_id' => $color->id,
            'provider_id' => $proveedor->id,
            'visible' => true,
        ]);

        $newProduct->images()->create([
            'image_url' =>   $pathImagen
        ]);

        $newProduct->productAttributes()->create([
            'attribute' => 'Proveedor',
            'slug' => 'proveedor',
            'value' => $this->proveedor,
        ]);

        UserProduct::create([
            'user_id' => auth()->user()->id,
            'product_id' => $newProduct->id,
        ]);

        return redirect()->action([CotizadorController::class, 'verProducto'], ['product' => $newProduct->id]);
    }

    public function limpiarImagen()
    {
        $this->imagen = null;
    }


    public function openScale()
    {
        $this->editScale = false;
        $this->itemEditScale = null;
        $this->cantidad = null;
        $this->precioTecnicaEscala = null;
        $this->dispatchBrowserEvent('showModalScales');
    }

    public function closeScale()
    {
        $this->editScale = false;
        $this->itemEditScale = null;
        $this->cantidad = null;
        $this->precioTecnicaEscala = null;
        $this->dispatchBrowserEvent('hideModalScales');
    }

    public function addScale()
    {
        $this->itemEditScale = null;
        $this->validate([
            'cantidad' => 'required|numeric|min:1',
            'utilidad' => 'required|numeric|min:0|max:99',
        ]);
        if ($this->precioTecnicaEscala != "0") {
            if (!is_numeric($this->precioTecnicaEscala))
                $this->precioTecnicaEscala = null;
        }
        array_push($this->infoScales, [
            'quantity' => $this->cantidad,
            'utility' => $this->utilidad,
            'tecniquePrice' => $this->precioTecnicaEscala,
        ]);
        $this->cantidad = 1;
        $this->precioTecnicaEscala = null;
        $this->utilidad = null;
        $this->dispatchBrowserEvent('hideModalScales');
    }

    public function deleteScale($scale_id)
    {
        try {
            unset($this->infoScales[$scale_id]);
            $newScales = [];
            foreach ($this->infoScales as $v) {
                array_push($newScales, $v);
            }
            $this->infoScales = $newScales;
            return 1;
        } catch (Exception $e) {
            return json_encode($e->getMessage());
        }
    }

    public function openDeleteScale($scale_id)
    {
        $this->dispatchBrowserEvent('openConfirmDelete', ['id' => $scale_id]);
    }

    public function editScale($scale_id)
    {
        $this->editScale =  true;
        $this->itemEditScale = $scale_id;
        $this->cantidad = $this->infoScales[$scale_id]['quantity'];
        $this->utilidad = $this->infoScales[$scale_id]['utility'];
        $this->precioTecnicaEscala = $this->infoScales[$scale_id]['tecniquePrice'];
        $this->dispatchBrowserEvent('showModalScales');
    }

    public function updateScale()
    {
        $this->validate([
            'cantidad' => 'required|numeric|min:1',
        ]);
        if (!is_numeric($this->precioTecnicaEscala))
            $this->precioTecnicaEscala = null;
        $this->infoScales[$this->itemEditScale] = [
            'quantity' => $this->cantidad,
            'utility' => $this->utilidad,
            'tecniquePrice' => $this->precioTecnicaEscala >= 0 ? $this->precioTecnicaEscala : null,
        ];
        $this->cantidad = 1;
        $this->precioTecnicaEscala = null;
        $this->utilidad = null;
        $this->editScale =  false;
        $this->itemEditScale = null;
        $this->dispatchBrowserEvent('hideModalScales');
    }
    public function changeTypePrice()
    {
        $this->priceScales = !$this->priceScales;
    }
}
