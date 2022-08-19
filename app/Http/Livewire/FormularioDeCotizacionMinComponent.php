<?php

namespace App\Http\Livewire;

use App\Models\Catalogo\GlobalAttribute;
use App\Models\Material;
use App\Models\MaterialTechnique;
use App\Models\SizeMaterialTechnique;
use App\Models\Technique;
use Livewire\Component;


class FormularioDeCotizacionMinComponent extends Component
{
    public $product, $currentQuote;

    public $precio, $precioCalculado, $precioTotal = 0;

    public $tecnica = 0, $colores = 0, $operacion = 0, $utilidad = 0, $entrega = 0, $cantidad = 0, $priceTechnique;

    public $materialSeleccionado;
    public $tecnicaSeleccionada;
    public $sizeSeleccionado;

    public function mount()
    {
        if ($this->currentQuote) {
            // Calculo de Precio
            $this->colores = $this->currentQuote->color_logos;
            $this->operacion = $this->currentQuote->costo_indirecto;
            $this->cantidad =  $this->currentQuote->cantidad;
            $this->utilidad =  $this->currentQuote->utilidad;
            $this->entrega =  $this->currentQuote->dias_entrega;
            // $nuevoPrecio = round(($this->precio + ($precioDeTecnica * $this->colores) + $this->operacion) / ((100 - $this->utilidad) / 100), 2);

            // $this->precioCalculado = $nuevoPrecio;
            // $this->precioTotal = $nuevoPrecio * $this->cantidad;
        }
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
        if ($this->sizeSeleccionado !== null && $this->sizeSeleccionado !== "") {
            $preciosDisponibles = SizeMaterialTechnique::where('material_technique_id', $materialTechnique->id)->where('size_id', (int)$this->sizeSeleccionado)->first()->pricesTechniques;
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
            $this->colores = 0;
        if (!is_numeric($this->operacion))
            $this->operacion = 0;
        if (!is_numeric($this->cantidad))
            $this->cantidad = 0;
        if (!is_numeric($this->utilidad))
            $this->utilidad = 0;
        $nuevoPrecio = round(($this->precio + ($precioDeTecnica * $this->colores) + $this->operacion) / ((100 - $this->utilidad) / 100), 2);

        $this->precioCalculado = $nuevoPrecio;
        $this->precioTotal = $nuevoPrecio * $this->cantidad;
        return view('pages.catalogo.formulario-de-cotizacion-min', ['materiales' => $materiales, 'techniquesAvailables' => $techniquesAvailables, 'sizesAvailables' => $sizesAvailables, 'preciosDisponibles' => $preciosDisponibles]);
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
            'idNewQuote' => time(),
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

        $this->emit('productAdded', $newQuote);
        $this->emit('addProductNewQuote');
        $this->dispatchBrowserEvent('closeModal');
        $this->resetData();
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
    }
}
