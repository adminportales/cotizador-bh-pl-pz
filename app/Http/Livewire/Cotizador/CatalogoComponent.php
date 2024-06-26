<?php

namespace App\Http\Livewire\Cotizador;

use App\Models\Catalogo\GlobalAttribute;
use App\Models\Catalogo\Product as CatalogoProduct;
use App\Models\Catalogo\Type;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

/**
 * Clase CatalogoComponent
 *
 * Componente de Livewire que maneja la lógica y la presentación de la página de catálogo.
 */
class CatalogoComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $nombre, $sku, $proveedor, $color, $category, $type, $precioMax, $precioMin, $stockMax, $stockMin, $orderStock = '', $orderPrice = '';

    public $proveedores;

    public function __construct()
    {
        $utilidad = GlobalAttribute::find(1);
        $utilidad = (float) $utilidad->value;
        $price = DB::connection('mysql_catalogo')->table('products')->max('price');
        $this->precioMax = round($price + $price * ($utilidad / 100), 2);
        $this->precioMin = 0;
        $stock = DB::connection('mysql_catalogo')->table('products')->max('stock');
        $this->stockMax = $stock;
        $this->stockMin = 0;
    }

    public function mount()
    {
        $this->proveedores = auth()->user()->companySession == null ? [] :  auth()->user()->companySession->providers;
        try {
            $filtros = session()->get('filtros', []);
            $this->setFiltros($filtros);
            session()->put('filtros', []);
        } catch (Exception $th) {
            //throw $th;
        }

        $utilidad = GlobalAttribute::find(1);
        $utilidad = (float) $utilidad->value;

        if (auth()->user()->settingsUser) {
            $utilidad = (float)(auth()->user()->settingsUser->utility > 0 ?  auth()->user()->settingsUser->utility :  $utilidad);
        }

        $price = DB::connection('mysql_catalogo')->table('products')->max('price');
        $this->precioMax = round($price + $price * ($utilidad / 100), 2);
        $this->precioMin = 0;
        $stock = DB::connection('mysql_catalogo')->table('products')->max('stock');
        $this->stockMax = $stock;
        $this->stockMin = 0;
        $this->type = 1;
    }

    public function render()
    {
        $utilidad = GlobalAttribute::find(1);
        $utilidad = (float) $utilidad->value;

        if (auth()->user()->settingsUser) {
            $utilidad = (float)(auth()->user()->settingsUser->utility > 0 ?  auth()->user()->settingsUser->utility :  $utilidad);
        }
        // Agrupar Colores similares
        $types = Type::find([1, 2]);
        $price = DB::connection('mysql_catalogo')->table('products')->max('price');
        $price = round($price + $price * ($utilidad / 100), 2);
        $stock = DB::connection('mysql_catalogo')->table('products')->max('stock');
        $nombre = '%' . $this->nombre . '%';
        $sku = '%' . $this->sku . '%';
        $color = $this->color;
        $category = $this->category;
        $type =  $this->type == null ? "" : $this->type;
        $precioMax = $price;
        if ($this->precioMax != null) {
            $precioMax =  round($this->precioMax / (($utilidad / 100) + 1), 2);
        }
        $precioMin = 0;
        if ($this->precioMin != null) {
            $precioMin =  round($this->precioMin / (($utilidad / 100) + 1), 2);
        }
        $stockMax =  $this->stockMax;
        $stockMin =  $this->stockMin;
        if ($stockMax == null) {
            $stockMax = $stock;
        }
        if ($stockMin == null) {
            $stockMin = 0;
        }
        $orderPrice = $this->orderPrice;
        $orderStock = $this->orderStock;

        if ($type == '2') {
            $this->proveedor = null;
        }
        if (trim($this->proveedor) == "")
            $this->proveedor = null;
        $products  = CatalogoProduct::leftjoin('product_category', 'product_category.product_id', 'products.id')
            ->leftjoin('categories', 'product_category.category_id', 'categories.id')
            ->leftjoin('colors', 'products.color_id', 'colors.id')
            // Buscar por nombre o descripcion
            ->where(function ($query) use ($nombre) {
                $query->where('products.name', 'LIKE', $nombre)
                    ->orWhere('products.description', 'LIKE', $nombre);
            })
            ->where('products.visible', '=', true)
            ->where('products.sku', 'LIKE', $sku)
            ->whereBetween('products.price', [$precioMin, $precioMax])
            ->whereBetween('products.stock', [$stockMin, $stockMax])
            ->when($type == '1', function ($query, $proveedor) {
                $query->where('products.provider_id', 'LIKE', $this->proveedor);
                // $query->orderBy('products.stock', $this->orderStock);
            })
            ->when($this->proveedor == null, function ($query) {
                $query->whereIn('products.provider_id', count($this->proveedores) ? $this->proveedores->pluck('id') : []);
                // $query->orderBy('products.stock', $this->orderStock);
            })
            ->where('products.type_id', 'LIKE', $type)
            ->when($orderStock !== '', function ($query, $orderStock) {
                $query->orderBy('products.stock', $this->orderStock);
            })
            ->when($orderPrice !== '', function ($query, $orderPrice) {
                $query->orderBy('products.price', $this->orderPrice);
            })
            ->where('products.price', '>', 0)
            ->when($color !== '' && $color !== null, function ($query, $color) {
                $newColor  = '%' . $this->color . '%';
                $query->where('colors.color', 'LIKE', $newColor);
            })
            ->when($category !== '' && $category !== null, function ($query, $category) {
                $newCat  = '%' . $this->category . '%';
                $query->where('categories.family', 'LIKE', $newCat);
            })
            // Order by case
            ->orderByRaw("CASE WHEN products.name LIKE '$nombre%' THEN 1 WHEN products.description LIKE '$nombre%' then 2 ELSE 3 END")
            ->select('products.*')
            ->paginate(16);
        // Dispacher
        return view('cotizador.catalogo.catalogoComponent', [
            'products' => $products,
            'utilidad' => $utilidad,
            'types' => $types,
            'price' => $price,
            'priceMax' => $precioMax,
            'priceMin' => $precioMin,
            'stock' => $stock,
            'stockMax' => $stockMax,
            'stockMin' => $stockMin,
            'orderStock' => $orderStock,
            // 'proveedores' => $proveedores,
        ]);
    }

    public function updated()
    {
        $this->resetPage();
    }

    public function hydrate()
    {
        $this->dispatchBrowserEvent('scroll-to-top');
    }

    public function limpiar()
    {
        $this->nombre = '';
        $this->sku = '';
        $this->color = '';
        $this->category = '';
        $this->proveedor = null;
        $this->type = 1;
        $this->orderPrice = '';
        $this->orderStock = '';
        $this->precioMax = null;
        $this->precioMin = null;
        $this->stockMax = null;
        $this->stockMin = null;
    }

    public function setFiltros($filtros)
    {
        $this->nombre = $filtros["nombre"];
        $this->sku = $filtros["sku"];
        $this->color = $filtros["color"];
        $this->category = $filtros["category"];
        $this->proveedor = $filtros["proveedor"];
        $this->type = $filtros["type"];
        $this->orderPrice = $filtros["orderPrice"];
        $this->orderStock = $filtros["orderStock"];
        $this->precioMax = $filtros["precioMax"];
        $this->precioMin = $filtros["precioMin"];
        $this->stockMax = $filtros["stockMax"];
        $this->stockMin = $filtros["stockMin"];
    }

    public function getFiltros()
    {
        return [
            "nombre" => $this->nombre,
            "sku" => $this->sku,
            "color" => $this->color,
            "category" => $this->category,
            "proveedor" => $this->proveedor,
            "type" => $this->type,
            "orderPrice" => $this->orderPrice,
            "orderStock" => $this->orderStock,
            "precioMax" => $this->precioMax,
            "precioMin" => $this->precioMin,
            "stockMax" => $this->stockMax,
            "stockMin" => $this->stockMin,
        ];
    }

    public function cotizar($id)
    {
        session()->put('filtros', $this->getFiltros());
        redirect("catalogo/" . $id);
    }

    // showPreview
    public function showPreview($id)
    {
        $product = CatalogoProduct::find($id);
        $this->dispatchBrowserEvent('showPreview', ['images' => $product->images]);
    }
}
