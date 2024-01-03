<?php

namespace App\Http\Livewire\Cotizador;

use App\Models\Catalogo\GlobalAttribute;
use App\Models\Catalogo\Product as CatalogoProduct;
use App\Models\Catalogo\Provider as CatalogoProvider;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use App\Models\Catalogo\Product;

/**
 * Clase CatalogoMinComponent
 *
 * Componente Livewire que representa el catálogo mínimo de productos.
 */
class CatalogoMinComponent extends Component
{
    use WithPagination;

    public $nombre, $producto = '', $proveedores;

    protected $listeners = ['addProductNewQuote' => 'regresar'];

    /**
     * Método mount
     *
     * Se ejecuta al inicializar el componente.
     * Obtiene los proveedores de la sesión de la compañía del usuario autenticado.
     */
    public function mount()
    {
        $this->proveedores = auth()->user()->companySession == null ? [] :  auth()->user()->companySession->providers;
    }

    /**
     * Método render
     *
     * Renderiza la vista del catálogo mínimo de productos.
     * Obtiene los productos que coinciden con el nombre de búsqueda y los filtros aplicados.
     * Ordena los productos según la coincidencia con el nombre de búsqueda.
     * Pagina los resultados y los pasa a la vista.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $utilidad = GlobalAttribute::find(1);
        $utilidad = (float) $utilidad->value;

        $nombre = '%' . $this->nombre . '%';

        $products  = CatalogoProduct::where(function ($query) use ($nombre) {
            $query->where('products.name', 'LIKE', $nombre)
                ->orWhere('products.description', 'LIKE', $nombre);
        })
            ->where('products.visible', '=', true)
            ->whereIn('products.type_id', [1, 2])
            ->where('products.price', '>', 0)
            ->whereIn('products.provider_id', count($this->proveedores) ? $this->proveedores->pluck('id') : [])
            ->orderByRaw("CASE WHEN products.name LIKE '$nombre%' THEN 1 WHEN products.description LIKE '$nombre%' then 2 ELSE 3 END")
            ->paginate(9);

        return view('cotizador.ver_cotizacion.catalogo-min-component', [
            'products' => $products,
            'utilidad' => $utilidad,
        ]);
    }

    /**
     * Método seleccionarProducto
     *
     * Selecciona un producto y realiza acciones adicionales si el proveedor es el número 5.
     *
     * @param Product $product El producto seleccionado.
     * @return void
     */
    public function seleccionarProducto(Product $product)
    {
        if ($product->provider_id == 5) {
            $cliente = new \nusoap_client('http://srv-datos.dyndns.info/doblevela/service.asmx?wsdl', 'wsdl');
            $error = $cliente->getError();
            if ($error) {
                echo 'Error' . $error;
            }
            //agregamos los parametros, en este caso solo es la llave de acceso
            $parametros = array('Key' => 't5jRODOUUIoytCPPk2Nd6Q==', 'codigo' => $product->sku_parent);
            //hacemos el llamado del metodo
            $resultado = $cliente->call('GetExistencia', $parametros);
            $msg = '';
            if (array_key_exists('GetExistenciaResult', $resultado)) {
                $informacionExistencias = json_decode(utf8_encode($resultado['GetExistenciaResult']))->Resultado;
                if (count($informacionExistencias) > 1) {
                    foreach ($informacionExistencias as $productExistencia) {
                        if ($product->sku == $productExistencia->CLAVE) {
                            $product->stock = $productExistencia->EXISTENCIAS;
                            $product->save();
                            break;
                        }
                        $msg = "Este producto no se encuentra en el catalogo que esta enviado DV via Servicio WEB";
                    }
                } else {
                    $msg = "Este producto no se encuentra en el catalogo que esta enviado DV via Servicio WEB";
                }
            } else {
                $msg = "No se obtuvo informacion acerca del Stock de este producto. Es posible que los datos sean incorrectos";
            }
        }
        $this->producto = $product;
    }

    /**
     * Método regresar
     *
     * Restablece el producto seleccionado.
     *
     * @return void
     */
    public function regresar()
    {
        $this->producto = '';
    }
}
