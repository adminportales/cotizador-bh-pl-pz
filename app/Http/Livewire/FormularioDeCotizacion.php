<?php

namespace App\Http\Livewire;

use App\Models\Material;
use App\Models\MaterialTechnique;
use App\Models\SizeMaterialTechnique;
use App\Models\Technique;
use Livewire\Component;


class FormularioDeCotizacion extends Component
{
    public $product;

    public $precio, $precioCalculado, $precioTotal = 0;

    public $tecnica = 0, $colores = 0, $operacion = 0, $utilidad = 0, $entrega = 0, $cantidad = 0;

    public $materialSeleccionado;
    public $tecnicaSeleccionada;
    public $sizeSeleccionado;

    public function mount()
    {
        $utilidad = 10;
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
            $techniquesAvailables = Material::find((int)$this->materialSeleccionado)->materialTechniques;
        }
        $sizesAvailables = [];
        if ($this->tecnicaSeleccionada !== null && $this->tecnicaSeleccionada !== "") {
            $sizesAvailables = MaterialTechnique::find((int)$this->tecnicaSeleccionada)->sizeMaterialTechniques;
        }

        $preciosDisponibles = [];
        if ($this->sizeSeleccionado !== null && $this->sizeSeleccionado !== "") {
            $preciosDisponibles = SizeMaterialTechnique::find((int)$this->sizeSeleccionado)->pricesTechniques;
        }

        $precioDeTecnica = 0;

        if ((int)$this->cantidad > 0 && $preciosDisponibles) {
            foreach ($preciosDisponibles as $precioDisponible) {
                if ($precioDisponible->escala_final != null) {
                    if ((int)$this->cantidad >= $precioDisponible->escala_inicial  &&  (int)$this->cantidad <= $precioDisponible->escala_final) {
                        $precioDeTecnica = $precioDisponible->tipo_precio == "D" ? round($precioDisponible->precio / (int)$this->cantidad, 2) : $precioDisponible->precio;
                    }
                } else if ($precioDisponible->escala_final == null) {
                    if ((int)$this->cantidad >= $precioDisponible->escala_inicial) {
                        $precioDeTecnica = $precioDisponible->tipo_precio == "D" ? round($precioDisponible->precio / (int)$this->cantidad, 2) : $precioDisponible->precio;
                    }
                }
            }
        } else {
            $precioDeTecnica = 0;
        }

        // Calculo de Precio
        if (!is_numeric($this->colores))
            $this->colores = 0;
        if (!is_numeric($this->operacion))
            $this->operacion = 0;
        if (!is_numeric($this->cantidad))
            $this->cantidad = 0;
        if (!is_numeric($this->cantidad))
            $this->cantidad = 0;
        $nuevoPrecio = round(($this->precio + ($precioDeTecnica * $this->colores) + $this->operacion) / ((100 - $this->utilidad) / 100), 2);

        $this->precioCalculado = $nuevoPrecio;
        $this->precioTotal = $nuevoPrecio * $this->cantidad;

        return view('pages.catalogo.formulario-de-cotizacion', ['materiales' => $materiales, 'techniquesAvailables' => $techniquesAvailables, 'sizesAvailables' => $sizesAvailables, 'preciosDisponibles' => $preciosDisponibles]);
    }

    public function agregarCotizacion()
    {
        auth()->user()->currentQuote;
    }
}
