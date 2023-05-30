<?php

namespace App\Http\Livewire;

use App\Models\Catalogo\Color;
use App\Models\Catalogo\Product;
use App\Models\Catalogo\Provider;
use App\Models\UserProduct;
use Exception;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class CatalogoPersonal extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $nombre, $descripcion, $precio, $stock, $color, $proveedor, $imagen, $product_id, $keyWord;
    public $productEdit = false;
    public function render()
    {

        $keyWord = '%' . $this->keyWord . '%';
        $nameDB = (new Product())->getConnection()->getDatabaseName();
        $products = UserProduct::join($nameDB . '.products', 'products.id', 'user_products.product_id')
            ->where('user_products.user_id', auth()->user()->id)
            ->where($nameDB . '.products.name', 'LIKE', $keyWord)
            ->where($nameDB . '.products.visible', true)
            ->simplePaginate(32);
        return view('livewire.catalogo-personal', ['products' => $products,]);
    }

    public function editProduct($product_id)
    {
        $product = Product::find($product_id);
        $this->nombre = $product->name;
        $this->descripcion = $product->description;
        $this->precio = $product->price;
        $this->stock = $product->stock;
        $this->color = $product->color->color;
        $this->proveedor = $product->provider->company;
        $this->imagen = $product->firstImage->url;
        $this->product_id = $product->id;
        $this->productEdit = true;
        $this->dispatchBrowserEvent('showModalEditar');
    }

    public function guardar($product_id)
    {
        $this->validate([
            'nombre' => 'required|max:100',
            'descripcion' => 'required',
            'precio' => 'required',
            'stock' => 'required',
            'color' => 'required',
            'proveedor' => 'required',
        ]);

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
            'company' => $this->proveedor
        ], [
            'email' => "no-mail",
            'phone' => 000,
            'contact' => "False",
            'discount' => 0
        ]);
        $product = Product::find($product_id);
        $product->update([
            'name' => $this->nombre,
            'price' =>   $this->precio,
            'description' =>  $this->descripcion,
            'stock' => $this->stock,
            'color_id' => $color->id,
            'provider_id' => $proveedor->id,
        ]);
        if ($this->imagen != null) {
            $product->images()->delete();
            $product->images()->create([
                'image_url' =>   $pathImagen
            ]);
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
}
