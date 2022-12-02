<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\Catalogo\GlobalAttribute;
use App\Models\Catalogo\Product;
use App\Models\Material;
use App\Models\MaterialTechnique;
use App\Models\PricesTechnique;
use App\Models\SizeMaterialTechnique;

class FormularioDeCotizacionCurrentMin extends Component
{
    public $product, $currentQuote;
    public $precio, $precioCalculado, $precioTotal = 0;
    public $tecnica = null, $colores = null, $operacion = null, $utilidad = null, $entrega = null, $cantidad = null, $priceTechnique, $newPriceTechnique = null, $newDescription = null;
    public $materialSeleccionado;
    public $tecnicaSeleccionada;
    public $sizeSeleccionado;

    public function mount()
    {
        $this->product = Product::find($this->currentQuote->product_id);
        if ($this->currentQuote) {
            // Calculo de Precio
            $this->colores = $this->currentQuote->color_logos;
            $this->operacion = $this->currentQuote->costo_indirecto;
            $this->cantidad =  $this->currentQuote->cantidad;
            $this->utilidad =  $this->currentQuote->utilidad;
            $this->entrega =  $this->currentQuote->dias_entrega;
            $this->newPriceTechnique =  $this->currentQuote->new_price_technique;
            $this->newDescription =  $this->currentQuote->new_description;

            $prices_techniques = PricesTechnique::find($this->currentQuote->prices_techniques_id);
            $this->materialSeleccionado = $prices_techniques->sizeMaterialTechnique->materialTechnique->material->id;
            $this->tecnicaSeleccionada = $prices_techniques->sizeMaterialTechnique->materialTechnique->technique->id;
            $this->sizeSeleccionado = $prices_techniques->sizeMaterialTechnique->size->id;
            // dd( $prices_techniques->sizeMaterialTechnique);
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
        if ($this->newPriceTechnique != null && $this->newPriceTechnique >= 0)
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
        $this->validate([
            'colores' => 'required|numeric|min:1',
            'operacion' => 'required|numeric|min:0',
            'utilidad' => 'required|numeric|min:0|max:99',
            'cantidad' => 'required|numeric|min:1',
            'entrega' => 'required|numeric|min:0',
            'priceTechnique' => 'required',
        ]);
        if (!is_numeric($this->newPriceTechnique))
            $this->newPriceTechnique = null;
        if (trim($this->newDescription) == "")
            $this->newDescription = null;
        if (trim($this->newDescription) == "")
            $this->newDescription = null;
        $this->currentQuote->update([
            'product_id' => $this->product->id,
            'prices_techniques_id' => $this->priceTechnique->id,
            'new_price_technique' => $this->newPriceTechnique,
            'new_description' => $this->newDescription,
            'color_logos' => $this->colores,
            'costo_indirecto' => $this->operacion,
            'utilidad' => $this->utilidad,
            'dias_entrega' => $this->entrega,
            'cantidad' => $this->cantidad,
            'precio_unitario' => $this->precioCalculado,
            'precio_total' => $this->precioTotal,
        ]);

        $this->dispatchBrowserEvent('closeModal', ['currentQuote' => $this->currentQuote->id]);
        $this->emit('updateProductCurrent');
    }
    public function resetSizes()
    {
        $this->sizeSeleccionado = null;
    }
}
