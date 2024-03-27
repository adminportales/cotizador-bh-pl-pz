<?php

namespace App\Http\Livewire\Cotizador;

use App\Models\Catalogo\Color;
use App\Models\Catalogo\Product;
use App\Models\Catalogo\Provider;
use App\Models\UserProduct;
use Exception;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

/**
 * Clase CatalogoPersonal
 *
 * Esta clase es un componente Livewire que se utiliza para manejar la funcionalidad del catálogo personal de productos.
 * Contiene métodos para renderizar la vista del catálogo, editar un producto, guardar los cambios realizados en un producto,
 * eliminar un producto, agregar una escala de precios, eliminar una escala de precios, editar una escala de precios y cambiar
 * el tipo de precio (escalado o único).
 */
class CatalogoPersonal extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $nombre, $descripcion, $precio, $stock, $color, $proveedor, $imagen, $product_id, $keyWord;
    public $productEdit = false;

    public $inicial, $final, $costo;
    public $priceScales, $infoScales = [], $editScale = false, $itemEditScale = null;

    public function render()
    {

        $keyWord = '%' . $this->keyWord . '%';
        $nameDB = (new Product())->getConnection()->getDatabaseName();
        $products = UserProduct::join($nameDB . '.products', 'products.id', 'user_products.product_id')
            ->where('user_products.user_id', auth()->user()->id)
            ->where($nameDB . '.products.name', 'LIKE', $keyWord)
            ->where($nameDB . '.products.visible', true)
            ->simplePaginate(32);
        return view('cotizador.mis_productos.catalogo-personal', ['products' => $products,]);
    }

    public function editProduct($product_id)
    {
        $product = Product::find($product_id);
        $this->nombre = $product->name;
        $this->descripcion = $product->description;
        $this->precio = $product->price;
        $this->stock = $product->stock;
        $this->color = $product->color->color;
        $this->proveedor = count($product->productAttributes) > 0
            ?  $product->productAttributes()->where('slug', 'proveedor')->first()->value
            : $product->provider->company;
        $this->imagen = $product->firstImage->url;
        $this->product_id = $product->id;
        $this->productEdit = true;
        $this->priceScales = !$product->precio_unico;
        if ($this->priceScales) {
            $this->infoScales = [];
            foreach ($product->precios as $value) {
                array_push($this->infoScales, [
                    'initial' => $value->escala_inicial,
                    'final' => $value->escala_final,
                    'cost' => $value->price,
                ]);
            }
        }
        $this->dispatchBrowserEvent('showModalEditar');
    }

    public function guardar($product_id)
    {
        $this->validate([
            'nombre' => 'required|max:100',
            'descripcion' => 'required',
            'stock' => 'required',
            'color' => 'required',
            'proveedor' => 'required',
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

        $pathImagen = null;
        if ($this->imagen != null) {
            $this->validate([
                'imagen' => 'required|image|max:512',
            ]);
            $name = time() . $this->nombre . '.' .  $this->imagen->getClientOriginalExtension();
            $pathImagen = url('') . '/storage/media/' . $name;
            $this->imagen->storeAs('public/media', $name);
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

        $product = Product::find($product_id);
        $dataProduct = [
            'name' => $this->nombre,
            'description' =>  $this->descripcion,
            'stock' => $this->stock,
            'color_id' => $color->id,
            'provider_id' => $proveedor->id,
        ];

        if ($this->priceScales) {
            $dataProduct['precio_unico'] = false;
            $dataProduct['price'] = $this->infoScales[0]['cost'];
        } else {
            $dataProduct['precio_unico'] = true;
            $dataProduct['price'] = $this->precio;
        }

        $product->update($dataProduct);
        if ($this->imagen != null) {
            $product->images()->delete();
            $product->images()->create([
                'image_url' =>   $pathImagen
            ]);
        }
        $product->productAttributes()->delete();
        $product->productAttributes()->create([
            'attribute' => 'Proveedor',
            'slug' => 'proveedor',
            'value' => $this->proveedor,
        ]);

        if ($this->priceScales) {
            $product->precios()->delete();
            foreach ($this->infoScales as $value) {
                $product->precios()->create([
                    'price' => $value['cost'],
                    'escala_inicial' => $value['initial'],
                    'escala_final' => $value['final'],
                ]);
            }
        }

        $this->dispatchBrowserEvent('hideModalEditar');
        session()->flash('message', 'Actualizacion completa');
        $this->productEdit = false;
        // return redirect()->action([CotizadorController::class, 'verProducto'], ['product' => $newProduct->id]);
    }

    public function deleteProduct($product_id)
    {
        try {
            $product = Product::find($product_id);
            $product->visible = 0;
            $product->save();
            return 1;
        } catch (Exception $e) {
            return json_encode($e->getMessage());
        }
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
