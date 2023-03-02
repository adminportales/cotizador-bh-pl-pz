<?php

namespace App\Http\Livewire;

use App\Models\Catalogo\GlobalAttribute;
use App\Models\Material;
use App\Models\MaterialTechnique;
use App\Models\SizeMaterialTechnique;
use App\Models\Technique;
use Exception;
use Illuminate\Validation\Rule;
use Livewire\Component;


class FormularioDeCotizacion extends Component
{
    public $product;

    public $precio, $precioCalculado, $precioTotal = 0;

    public $tecnica = null, $colores = null, $operacion = null, $utilidad = null, $entrega = null, $cantidad = null, $priceTechnique, $newPriceTechnique = null, $newDescription = null, $imageSelected;

    public $priceScales, $infoScales = [], $priceScalesComplete = [],  $cantidadEscala, $precioTecnicaEscala, $editScale = false, $itemEditScale = null;

    public $materialSeleccionado;
    public $tecnicaSeleccionada;
    public $sizeSeleccionado;

    public function mount()
    {
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
        $this->priceScales = false;
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

        // Calculo de Precio
        if (!is_numeric($this->colores))
            $this->colores = null;
        if (!is_numeric($this->operacion))
            $this->operacion = null;
        if (!is_numeric($this->utilidad))
            $this->utilidad = null;
        if ($this->utilidad > 99)
            $this->utilidad = 99;

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
        if (!$this->priceScales) {
            if (!is_numeric($this->cantidad))
                $this->cantidad = null;

            if (!is_numeric($this->newPriceTechnique))
                $this->newPriceTechnique = null;
            $precioDeTecnicaUsado = $precioDeTecnica;
            if ($this->newPriceTechnique != null && $this->newPriceTechnique >= 0)
                $precioDeTecnicaUsado = $this->newPriceTechnique;

            $nuevoPrecio = round(($this->precio + ($precioDeTecnicaUsado * $this->colores) + $this->operacion) / ((100 - $this->utilidad) / 100), 2);

            $this->precioCalculado = $nuevoPrecio;
            $this->precioTotal = $nuevoPrecio * $this->cantidad;
        } else {
            $this->priceScalesComplete = [];
            foreach ($this->infoScales as $info) {
                if ((int)$info['quantity'] > 0 && $preciosDisponibles && $this->sizeSeleccionado !== null) {
                    foreach ($preciosDisponibles as $precioDisponible) {
                        if ($precioDisponible->escala_final != null) {
                            if ((int)$info['quantity'] >= $precioDisponible->escala_inicial  &&  (int)$info['quantity'] <= $precioDisponible->escala_final) {
                                $this->priceTechnique = $precioDisponible;
                                $precioDeTecnica = $precioDisponible->tipo_precio == "D" ? round($precioDisponible->precio / (int)$info['quantity'], 2) : $precioDisponible->precio;
                            }
                        } else if ($precioDisponible->escala_final == null) {
                            if ((int)$info['quantity'] >= $precioDisponible->escala_inicial) {
                                $this->priceTechnique = $precioDisponible;
                                $precioDeTecnica = $precioDisponible->tipo_precio == "D" ? round($precioDisponible->precio / (int)$info['quantity'], 2) : $precioDisponible->precio;
                            }
                        }
                    }
                } else {
                    $precioDeTecnica = 0;
                    $this->priceTechnique = null;
                }

                if (!is_numeric($info['quantity']))
                    $info['quantity'] = null;

                if (!is_numeric($info['tecniquePrice']))
                    $info['tecniquePrice'] = null;
                $precioDeTecnicaUsado = $precioDeTecnica;
                if ($info['tecniquePrice'] != null && $info['tecniquePrice'] >= 0)
                    $precioDeTecnicaUsado = $info['tecniquePrice'];

                $nuevoPrecio = round(($this->precio + ($precioDeTecnicaUsado * $this->colores) + $this->operacion) / ((100 - $this->utilidad) / 100), 2);

                array_push($this->priceScalesComplete, [
                    'quantity' => $info['quantity'],
                    'tecniquePrice' => $info['tecniquePrice'] ?: $precioDeTecnica,
                    'unit_price' => $nuevoPrecio,
                    'total_price' => $nuevoPrecio * $info['quantity'],
                ]);
            }
        }

        return view('pages.catalogo.formulario-de-cotizacion', [
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
            'entrega' => 'required|numeric|min:0',
            'priceTechnique' => 'required',
        ]);
        if (!$this->priceScales) {
            $this->validate([
                'cantidad' => 'required|numeric|min:1',
            ]);
            $this->infoScales = [];
        } else {
            $this->validate([
                'infoScales' => 'array|required',
                'infoScales.*.quantity' => 'required|numeric|min:1',
            ]);
            $this->cantidad = 0;
        }

        $currentQuote = auth()->user()->currentQuote;

        if ($currentQuote === null) {
            $currentQuote = auth()->user()->currentQuote()->create([
                'discount' => false
            ]);
        } else {
            if (auth()->user()->currentQuote && $this->priceScales == true) {
                auth()->user()->currentQuote->discount = false;
                auth()->user()->currentQuote->type = null;
                auth()->user()->currentQuote->value = null;
                auth()->user()->currentQuote->save();
            }
        }
        if (!is_numeric($this->newPriceTechnique))
            $this->newPriceTechnique = null;
        if (trim($this->newDescription) == "")
            $this->newDescription = null;

        $dataQuote = [
            'product_id' => $this->product->id,
            'prices_techniques_id' => $this->priceTechnique->id,
            'new_description' => $this->newDescription,
            'color_logos' => $this->colores,
            'costo_indirecto' => $this->operacion,
            'utilidad' => $this->utilidad,
            'dias_entrega' => $this->entrega,
            'images_selected' => $this->imageSelected
        ];

        if (!$this->priceScales) {
            $dataQuote['new_price_technique'] = $this->newPriceTechnique;
            $dataQuote['cantidad'] = $this->cantidad;
            $dataQuote['precio_unitario'] = $this->precioCalculado;
            $dataQuote['precio_total'] = $this->precioTotal;
            $dataQuote['quote_by_scales'] = false;
            $dataQuote['scales_info'] = null;
        } else {
            $dataQuote['new_price_technique'] = null;
            $dataQuote['cantidad'] = null;
            $dataQuote['precio_unitario'] = null;
            $dataQuote['precio_total'] = null;
            $dataQuote['quote_by_scales'] = true;
            $dataQuote['scales_info'] = json_encode($this->priceScalesComplete);
        }

        $currentQuote->currentQuoteDetails()->create($dataQuote);
        session()->flash('message', 'Se ha agregado este producto a la cotizacion.');
        $this->emit('currentQuoteAdded');
        $this->resetData();
    }

    public function seleccionarImagen($image)
    {
        $this->imageSelected = $image;
    }
    public function eliminarImagen()
    {
        $this->imageSelected = null;
    }

    public function changeTypePrice()
    {
        $this->priceScales = !$this->priceScales;
    }

    public function addScale()
    {
        $this->itemEditScale = null;
        $this->validate([
            'cantidad' => 'required|numeric|min:1',
        ]);
        if (!is_numeric($this->precioTecnicaEscala))
            $this->precioTecnicaEscala = null;
        array_push($this->infoScales, [
            'quantity' => $this->cantidad,
            'tecniquePrice' => $this->precioTecnicaEscala ?: null,
        ]);
        $this->cantidad = null;
        $this->precioTecnicaEscala = null;
        $this->dispatchBrowserEvent('hideModalScales');
    }

    public function deleteScale($scale_id)
    {
        try {
            unset($this->infoScales[$scale_id]);
            array_values($this->infoScales);
            return 1;
        } catch (Exception $e) {
            return json_encode($e->getMessage());
        }
    }
    public function editScale($scale_id)
    {
        $this->editScale =  true;
        $this->itemEditScale = $scale_id;
        $this->cantidad = $this->infoScales[$scale_id]['quantity'];
        $this->precioTecnicaEscala = $this->infoScales[$scale_id]['tecniquePrice'];
        $this->dispatchBrowserEvent('showModalScales');
    }

    public function updateScale()
    {
        $this->validate([
            'cantidad' => 'required|numeric|min:1',
        ]);
        if (!is_numeric($this->precioTecnicaEscala))
            $this->precioTecnicaEscala = null;
        $this->infoScales[$this->itemEditScale] = [
            'quantity' => $this->cantidad,
            'tecniquePrice' => $this->precioTecnicaEscala ?: null,
        ];
        $this->cantidad = null;
        $this->precioTecnicaEscala = null;
        $this->editScale =  false;
        $this->itemEditScale = null;
        $this->dispatchBrowserEvent('hideModalScales');
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
        $this->newPriceTechnique = 0;
        $this->newDescription = '';
        $this->infoScales = [];
        $this->priceScales = false;
    }

    public function resetSizes()
    {
        $this->sizeSeleccionado = null;
    }
}
