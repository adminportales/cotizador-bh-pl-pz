<?php

namespace App\Http\Livewire;

use App\Models\Catalogo\Color;
use App\Models\Catalogo\GlobalAttribute;
use App\Models\Catalogo\Product;
use App\Models\Catalogo\Provider;

use Livewire\Component;
use Livewire\WithFileUploads;

class AddNewProductToQuote extends Component
{
    use WithFileUploads;

    public $nombre, $descripcion, $precio, $stock, $color, $proveedor, $imagen,  $thereProduct = false, $producto;
    public function render()
    {
        $utilidad = GlobalAttribute::find(1);
        $utilidad = (float) $utilidad->value;

        return view('livewire.add-new-product-to-quote', [
            'utilidad' => $utilidad,
        ]);
    }
    public function guardar()
    {
        $this->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
            'precio' => 'required',
            'stock' => 'required',
            'color' => 'required',
            'proveedor' => 'required',
            'imagen' => 'required|image|max:512',
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

        $proveedor = null;
        $proveedor = Provider::create([
            'company' => $this->proveedor, 'email' => "no-mail",
            'phone' => 000,
            'contact' => "False",
            'discount' => 0
        ]);
        if (!$proveedor) {
            $proveedor = Color::create([
                'proveedor' => ucfirst($this->proveedor), 'slug' => $slug,
            ]);
        }

        $pathImagen = null;
        if ($this->imagen != null) {
            $name = time() . $this->nombre . '.' .  $this->imagen->getClientOriginalExtension();
            $pathImagen = url(''). '/storage/media/' . $name;
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
            'visible' => false,
        ]);
        $newProduct->images()->create([
            'image_url' =>   $pathImagen
        ]);

        $this->thereProduct = true;
        $this->producto = $newProduct;

        // return redirect()->action([CotizadorController::class, 'verProducto'], ['product' => $newProduct->id]);
    }

    public function limpiarImagen()
    {
        $this->imagen = null;
    }

}

