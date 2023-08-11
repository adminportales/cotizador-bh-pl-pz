<?php

namespace App\Http\Livewire;

use App\Http\Controllers\CotizadorController;
use App\Models\Catalogo\Color;
use App\Models\Catalogo\Product;
use App\Models\Catalogo\Provider;
use App\Models\UserProduct;
use Exception;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddNewProduct extends Component
{
    use WithFileUploads;

    public $nombre, $descripcion, $precio, $stock, $color, $proveedor, $imagen;

    public $inicial, $final, $costo;

    public $priceScales, $infoScales = [], $editScale = false, $itemEditScale = null;

    public function render()
    {
        for ($i = 0; $i < count($this->infoScales); $i++) {
            for ($j = 0; $j <  count($this->infoScales); $j++) {
                if ($this->infoScales[$i]['initial'] < $this->infoScales[$j]['initial']) {
                    $aux = $this->infoScales[$i];
                    $this->infoScales[$i] = $this->infoScales[$j];
                    $this->infoScales[$j] = $aux;
                }
            }
        }
        $errors = [];
        // Revisar si las escalas son correctas
        for ($i = 0; $i < count($this->infoScales) - 1; $i++) {
            if ($this->infoScales[$i]['final'] + 1 != $this->infoScales[$i + 1]['initial']) {
                $error = "Las escalas no son correctas: " . $this->infoScales[$i]['final'] . " != " . $this->infoScales[$i + 1]['initial'];
                array_push($errors, $error);
            }
        }
        return view('livewire.add-new-product');
    }

    public function guardar()
    {
        $this->validate([
            'nombre' => 'required|max:100',
            'descripcion' => 'required',
            'stock' => 'required',
            'color' => 'required',
            'proveedor' => 'required',
            'imagen' => 'required|image|max:1536',
        ]);
        if ($this->priceScales) {
            $this->validate([
                "infoScales" => "required|array|min:1",
            ]);
        } else {
            $this->validate([
                "precio" => "required|numeric|min:0",
            ]);
        }
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
            $name = time() . str_replace("%", " ", str_replace("/", " ", str_replace(",", " ", $this->nombre))) . '.' .  $this->imagen->getClientOriginalExtension();
            $pathImagen = url('') . '/storage/media/' . $name;
            $this->imagen->storeAs('public/media', $name);
            // Guardar La cotizacion
        }

        $dataProduct = [
            'internal_sku' => "PROM-" . str_pad($idSku, 7, "0", STR_PAD_LEFT),
            'sku' => 0000,
            'name' => $this->nombre,
            'description' =>  $this->descripcion,
            'stock' => $this->stock,
            'producto_promocion' => false,
            'descuento' => 0,
            'producto_nuevo' =>  false,
            'type_id' => 3,
            'color_id' => $color->id,
            'provider_id' => $proveedor->id,
            'visible' => true,
        ];

        if ($this->priceScales) {
            $dataProduct['precio_unico'] = false;
            $dataProduct['price'] = $this->infoScales[0]['cost'];
        } else {
            $dataProduct['precio_unico'] = true;
            $dataProduct['price'] = $this->precio;
        }

        $newProduct = Product::create($dataProduct);

        $newProduct->images()->create([
            'image_url' =>   $pathImagen
        ]);

        $newProduct->productAttributes()->create([
            'attribute' => 'Proveedor',
            'slug' => 'proveedor',
            'value' => $this->proveedor,
        ]);

        if ($this->priceScales) {
            foreach ($this->infoScales as $value) {
                $newProduct->precios()->create([
                    'price' => $value['cost'],
                    'escala_inicial' => $value['initial'],
                    'escala_final' => $value['final'],
                ]);
            }
        }

        UserProduct::create([
            'user_id' => auth()->user()->id,
            'product_id' => $newProduct->id,
        ]);

        return redirect()->action([CotizadorController::class, 'verProducto'], ['product' => $newProduct->id]);
    }

    public function openScale()
    {
        $this->editScale = false;
        $this->itemEditScale = null;
        $this->dispatchBrowserEvent('showModalScales');
    }

    public function closeScale()
    {
        $this->editScale = false;
        $this->itemEditScale = null;
        $this->dispatchBrowserEvent('hideModalScales');
    }

    public function addScale()
    {
        $this->itemEditScale = null;
        // Final tiene que estar vacio o ser mayor que inicial
        if ($this->final != null && $this->final <= $this->inicial) {
            $this->dispatchBrowserEvent('showError', ['message' => 'El valor final debe ser mayor que el inicial']);
            return;
        }
        $this->validate([
            'inicial' => 'required|numeric|min:1',
            'costo' => 'required|numeric|min:0',
        ]);
        array_push($this->infoScales, [
            'initial' => $this->inicial,
            'final' => $this->final,
            'cost' => $this->costo,
        ]);
        $this->inicial = null;
        $this->final = null;
        $this->costo = null;
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

    public function editScale($scale_id)
    {
        $this->editScale =  true;
        $this->itemEditScale = $scale_id;
        $this->inicial = $this->infoScales[$scale_id]['initial'];
        $this->final = $this->infoScales[$scale_id]['final'];
        $this->costo = $this->infoScales[$scale_id]['cost'];
        $this->dispatchBrowserEvent('showModalScales');
    }

    public function updateScale()
    {
        if ($this->final != null && $this->final <= $this->inicial) {
            $this->dispatchBrowserEvent('showError', ['message' => 'El valor final debe ser mayor que el inicial']);
            return;
        }
        $this->validate([
            'inicial' => 'required|numeric|min:1',
            'costo' => 'required|numeric|min:0',
        ]);
        $this->infoScales[$this->itemEditScale] =  [
            'initial' => $this->inicial,
            'final' => $this->final,
            'cost' => $this->costo,
        ];
        $this->inicial = null;
        $this->final = null;
        $this->costo = null;
        $this->editScale =  false;
        $this->itemEditScale = null;
        $this->dispatchBrowserEvent('hideModalScales');
    }
    public function changeTypePrice()
    {
        $this->priceScales = !$this->priceScales;
    }
}
