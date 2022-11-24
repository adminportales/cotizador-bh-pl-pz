<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\Catalogo\GlobalAttribute;
use App\Models\Catalogo\Product;
use App\Models\Material;
use App\Models\MaterialTechnique;
use App\Models\PricesTechnique;
use App\Models\SizeMaterialTechnique;

class FormularioDeCotizacionEditMin extends Component
{
    public $product, $currentQuote_id = '', $idNewQuote = '';
    public $precio, $precioCalculado, $precioTotal = 0;
    public $tecnica = null, $colores = null, $operacion = null, $utilidad = null, $entrega = null, $cantidad = null, $priceTechnique, $newPriceTechnique = null;
    public $materialSeleccionado;
    public $tecnicaSeleccionada;
    public $sizeSeleccionado;

    protected $listeners = ['editProduct' => 'setProduct'];

    public function render()
    {
        $this->precioTotal = 0;
        // Obtener precios de las tecnicas

        // Obtengo Materiales
        $materiales = Material::all();

        // Obtengo las tenicas disponibles de acuerdo al material seleccionado
        $techniquesAvailables = [];
        if ($this->materialSeleccionado !== null && $this->materialSeleccionado !== "") {
            $techniques = Material::find((int)$this->materialSeleccionado);
            if ($techniques) {
                $techniquesAvailables = $techniques->materialTechniques;
            } else {
                $techniquesAvailables = [];
            }
        } else {
            $this->tecnicaSeleccionada = null;
            $techniquesAvailables = [];
        }
        $sizesAvailables = [];
        $materialTechnique = '';
        if ($this->tecnicaSeleccionada !== null && $this->tecnicaSeleccionada !== "") {
            $materialTechnique = MaterialTechnique::where('technique_id', (int)$this->tecnicaSeleccionada)->where('material_id', (int)$this->materialSeleccionado)->first();
            if ($materialTechnique) {
                $sizesAvailables = $materialTechnique->sizeMaterialTechniques;
            } else {
                $sizesAvailables = [];
            }
        } else {
            $sizesAvailables = [];
            $this->sizeSeleccionado = null;
        }

        $preciosDisponibles = [];
        if ($materialTechnique) {
            if ($this->sizeSeleccionado !== null && $this->sizeSeleccionado !== "") {
                $preciosDisponibles = SizeMaterialTechnique::where('material_technique_id', $materialTechnique->id)->where('size_id', (int)$this->sizeSeleccionado)->first()->pricesTechniques;
            } else {
                $preciosDisponibles = [];
                $this->sizeSeleccionado = null;
            }
        } else {
            $preciosDisponibles = [];
            $this->sizeSeleccionado = null;
        }

        $precioDeTecnica = 0;

        if ((int)$this->cantidad > 0 && $preciosDisponibles && $this->sizeSeleccionado !== null) {
            foreach ($preciosDisponibles as $precioDisponible) {
                if ($precioDisponible->escala_final != null) {
                    if ((int)$this->cantidad >= $precioDisponible->escala_inicial  &&  (int)$this->cantidad <= $precioDisponible->escala_final) {
                        $this->priceTechnique = $precioDisponible;
                        $precioDeTecnica = $precioDisponible->tipo_precio == "D" ? round($precioDisponible->precio / (int)$this->cantidad, 2) : $precioDisponible->precio;
                    }
                } else if ($precioDisponible->escala_final == null) {
                    if ((int)$this->cantidad >= $precioDisponible->escala_inicial) {
                        $this->priceTechnique = $precioDisponible;
                        $precioDeTecnica = $precioDisponible->tipo_precio == "D" ? round($precioDisponible->precio / (int)$this->cantidad, 2) : $precioDisponible->precio;
                    }
                }
            }
        } else {
            $precioDeTecnica = 0;
            $this->priceTechnique = null;
        }

        // Calculo de Precio
        if (!is_numeric($this->colores))
            $this->colores = null;
        if (!is_numeric($this->operacion))
            $this->operacion = null;
        if (!is_numeric($this->cantidad))
            $this->cantidad = null;
        if ($this->utilidad > 99)
            $this->utilidad = 99;
        if (!is_numeric($this->newPriceTechnique))
            $this->newPriceTechnique = null;
        $precioDeTecnicaUsado = $precioDeTecnica;
        if ($this->newPriceTechnique != null && $this->newPriceTechnique > 0)
            $precioDeTecnicaUsado = $this->newPriceTechnique;
        $nuevoPrecio = round(($this->precio + ($precioDeTecnicaUsado * $this->colores) + $this->operacion) / ((100 - $this->utilidad) / 100), 2);

        $this->precioCalculado = $nuevoPrecio;
        $this->precioTotal = $nuevoPrecio * $this->cantidad;
        return view('livewire.formulario-de-cotizacion-current-min', [
            'materiales' => $materiales,
            'techniquesAvailables' => $techniquesAvailables,
            'sizesAvailables' => $sizesAvailables,
            'preciosDisponibles' => $preciosDisponibles,
            "precioDeTecnica" => $precioDeTecnica
        ]);
    }

    public function agregarCotizacion()
    {
        if (!(int)$this->colores > 0) {
            session()->flash('error', 'No se ha especificado la cantidad de logos.');
            return;
        }
        if (!(int)$this->operacion > 0) {
            session()->flash('error', 'No se ha especificado el costo de operacion.');
            return;
        }
        if (!(int)$this->utilidad > 0) {
            session()->flash('error', 'No se ha especificado el margen de utilidad.');
            return;
        }
        if (!(int)$this->cantidad > 0) {
            session()->flash('error', 'No se ha especificado la cantidad de productos.');
            return;
        }
        if (!(int)$this->entrega > 0) {
            session()->flash('error', 'No se ha especificado el tiempo de entrega.');
            return;
        }

        if (!$this->priceTechnique) {
            session()->flash('error', 'No se ha especificado la tecnica de personalizacion.');
            return;
        }
        $product = $this->product->toArray();
        $product['image'] = $this->product->firstImage ? $this->product->firstImage->image_url : '';
        $newQuote = [
            'currentQuote_id' => $this->currentQuote_id,
            'idNewQuote' => $this->idNewQuote,
            'product' => json_encode($product),
            'prices_techniques_id' => $this->priceTechnique->id,
            'color_logos' => $this->colores,
            'costo_indirecto' => $this->operacion,
            'utilidad' => $this->utilidad,
            'dias_entrega' => $this->entrega,
            'cantidad' => $this->cantidad,
            'precio_unitario' => $this->precioCalculado,
            'precio_total' => $this->precioTotal,
        ];


        $this->emit('productUpdate', $newQuote);

        $this->dispatchBrowserEvent('closeModal');

        $this->resetData();
    }

    public function setProduct($data)
    {
        $productEdit = $data['productEdit'];
        $isNew = $data['isNew'];

        if ($isNew) {
            $producto = json_decode($productEdit['product']);
            $this->product = Product::find($producto->id);
            $prices_techniques = PricesTechnique::find($productEdit['prices_techniques_id']);
            $this->materialSeleccionado = $prices_techniques->sizeMaterialTechnique->materialTechnique->material->id;
            $this->tecnicaSeleccionada = $prices_techniques->sizeMaterialTechnique->materialTechnique->technique->id;
            $this->sizeSeleccionado = $prices_techniques->sizeMaterialTechnique->size->id;
            $this->idNewQuote = $productEdit['idNewQuote'];
        } else {
            $product =  (object) json_decode($productEdit['product']);

            $this->product = Product::find($product->id);
            $techniquesInfo =  (object) json_decode($productEdit['technique']);

            $this->materialSeleccionado = $techniquesInfo->material_id;
            $this->tecnicaSeleccionada = $techniquesInfo->tecnica_id;
            $this->sizeSeleccionado = $techniquesInfo->size_id;
            $this->currentQuote_id = $productEdit['id'];
        }
        # code...
        // Calculo de Precio
        $this->colores = $productEdit['color_logos'];
        $this->operacion = $productEdit['costo_indirecto'];
        $this->cantidad = $productEdit['cantidad'];
        $this->utilidad = $productEdit['utilidad'];
        $this->entrega = $productEdit['dias_entrega'];
        $this->newPriceTechnique = $productEdit['prices_techniques'];


        $utilidad = GlobalAttribute::find(1);
        $utilidad = (float) $utilidad->value;
        $priceProduct = $this->product->price;
        if ($this->product->producto_promocion) {
            $priceProduct = round($priceProduct - $priceProduct * ($this->product->descuento / 100), 2);
        } else {
            $priceProduct = round($priceProduct - $priceProduct * ($this->product->provider->discount / 100), 2);
        }
        $this->precio = round($priceProduct + $priceProduct * ($utilidad / 100), 2);
        $this->precioCalculado = $this->precio;
    }

    public function resetData()
    {
        $this->priceTechnique = null;
        $this->colores = 0;
        $this->operacion = 0;
        $this->utilidad = 0;
        $this->entrega = 0;
        $this->cantidad = 0;
        $this->precioCalculado = 0;
        $this->precioTotal = 0;
        $this->currentQuote_id = '';
    }
    public function resetSizes()
    {
        $this->sizeSeleccionado = null;
    }
}
