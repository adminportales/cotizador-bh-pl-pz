<?php

namespace App\Http\Livewire;

use App\Models\Catalogo\GlobalAttribute;
use App\Models\Catalogo\Product as CatalogoProduct;
use App\Models\Catalogo\Provider as CatalogoProvider;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use App\Models\Catalogo\Product;

class CatalogoMinComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $nombre, $producto = '';


    public function render()
    {
        $utilidad = GlobalAttribute::find(1);
        $utilidad = (float) $utilidad->value;

        $nombre = '%' . $this->nombre . '%';

        $products  = CatalogoProduct::where('name', 'LIKE', $nombre)
            ->paginate(9);

        return view('livewire.catalogo-min-component', [
            'products' => $products,
            'utilidad' => $utilidad,
        ]);
    }
    public function seleccionarProducto(Product $product)
    {
        $this->producto = $product;
    }

    public function regresar()
    {
        $this->producto = '';
        dd($this->producto);
    }
}
