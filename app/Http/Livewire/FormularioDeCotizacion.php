<?php

namespace App\Http\Livewire;

use App\Models\Catalogo\GlobalAttribute;
use App\Models\Catalogo\Product;
use App\Models\Material;
use App\Models\MaterialTechnique;
use App\Models\PricesTechnique;
use App\Models\SizeMaterialTechnique;
use App\Models\Technique;
use Exception;
use Livewire\Component;


class FormularioDeCotizacion extends Component
{
    public $product, $currentQuote, $productEdit, $currentQuote_id, $productNewAdd;

    public $precio, $precioCalculado, $precioTotal = 0;

    public $tecnica = null, $colores = null, $operacion = null, $utilidad = null, $entrega = null, $cantidad = null, $priceTechnique, $newPriceTechnique = null, $newDescription = null, $imageSelected;

    public $priceScales, $infoScales = [], $priceScalesComplete = [],  $cantidadEscala, $precioTecnicaEscala, $editScale = false, $itemEditScale = null;

    public $typeDays;

    public $materialSeleccionado;
    public $tecnicaSeleccionada;
    public $sizeSeleccionado;

    public function mount()
    {
        $this->priceScales = false;
        if ($this->currentQuote) {
            $this->product = Product::find($this->currentQuote->product_id);
            // Calculo de Precio
            $this->colores = $this->currentQuote->color_logos;
            $this->operacion = $this->currentQuote->costo_indirecto;
            $this->cantidad =  $this->currentQuote->cantidad ?: 1;
            $this->utilidad =  $this->currentQuote->utilidad;
            $this->entrega =  $this->currentQuote->dias_entrega;
            $this->typeDays =  $this->currentQuote->type_days;
            $this->newPriceTechnique =  $this->currentQuote->new_price_technique;
            $this->newDescription =  $this->currentQuote->new_description;
            $this->imageSelected =  $this->currentQuote->images_selected;

            $prices_techniques = PricesTechnique::find($this->currentQuote->prices_techniques_id);
            $this->materialSeleccionado = $prices_techniques->sizeMaterialTechnique->materialTechnique->material->id;
            $this->tecnicaSeleccionada = $prices_techniques->sizeMaterialTechnique->materialTechnique->technique->id;
            $this->sizeSeleccionado = $prices_techniques->sizeMaterialTechnique->size->id;
            $this->priceScales = $this->currentQuote->quote_by_scales;
            if ($this->priceScales) {
                $this->priceScalesComplete = json_decode($this->currentQuote->scales_info);
                $this->infoScales = array_map(function ($scale) {
                    return ['quantity' => $scale->quantity, 'utility' => $scale->utility, 'tecniquePrice' => $scale->tecniquePrice];
                }, $this->priceScalesComplete);
            }
        }
        if ($this->productEdit) {
            $product = json_decode($this->productEdit['product']);
            $this->product = Product::find($product->id);
            $techniquesInfo =  (object) json_decode($this->productEdit['technique']);

            $this->materialSeleccionado = $techniquesInfo->material_id;
            $this->tecnicaSeleccionada = $techniquesInfo->tecnica_id;
            $this->sizeSeleccionado = $techniquesInfo->size_id;
            $this->currentQuote_id = $this->productEdit['id'];
            $this->imageSelected = $product->image;


            $this->colores = $this->productEdit['color_logos'];
            $this->operacion = $this->productEdit['costo_indirecto'];
            $this->cantidad = $this->productEdit['cantidad'] ?: 1;
            $this->utilidad = $this->productEdit['utilidad'];
            $this->entrega = $this->productEdit['dias_entrega'];
            $this->typeDays = $this->productEdit['type_days'];
            $this->newPriceTechnique = $this->productEdit['prices_techniques'];
            $this->newDescription =  $this->productEdit['new_description'];

            $this->priceScales = $this->productEdit['quote_by_scales'];
            if ($this->priceScales) {
                $this->priceScalesComplete = json_decode($this->productEdit['scales_info']);
                $this->infoScales = array_map(function ($scale) {
                    return ['quantity' => $scale->quantity, 'utility' => $scale->utility, 'tecniquePrice' => $scale->tecniquePrice];
                }, $this->priceScalesComplete);
            }
        }
        if ($this->productNewAdd) {
            $this->product = $this->productNewAdd;
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

        if (!$this->priceScales) {
            if (!is_numeric($this->cantidad))
                $this->cantidad = null;

            if (!is_numeric($this->newPriceTechnique))
                $this->newPriceTechnique = null;

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

            $precioDeTecnicaUsado = $precioDeTecnica;
            if ($this->newPriceTechnique != null && $this->newPriceTechnique >= 0)
                $precioDeTecnicaUsado = $this->newPriceTechnique;

            if (!$this->product->precio_unico) {
                $escalasDeProductoDisponibles = $this->product->precios;
                if ((int)$this->cantidad > 0 && $escalasDeProductoDisponibles) {
                    foreach ($escalasDeProductoDisponibles as $escalaDeCosto) {
                        if ($escalaDeCosto->escala_final != null) {
                            if ((int)$this->cantidad >= $escalaDeCosto->escala_inicial  &&  (int)$this->cantidad <= $escalaDeCosto->escala_final) {
                                $this->precio = $escalaDeCosto->price;
                            }
                        } else if ($escalaDeCosto->escala_final == null) {
                            if ((int)$this->cantidad >= $escalaDeCosto->escala_inicial) {
                                $this->precio = $escalaDeCosto->price;
                            }
                        }
                    }
                }
            }

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

                if (!$this->product->precio_unico) {
                    $escalasDeProductoDisponibles = $this->product->precios;
                    if ((int)$info['quantity'] > 0 && $escalasDeProductoDisponibles) {
                        foreach ($escalasDeProductoDisponibles as $escalaDeCosto) {
                            if ($escalaDeCosto->escala_final != null) {
                                if ((int)$info['quantity'] >= $escalaDeCosto->escala_inicial  &&  (int)$info['quantity'] <= $escalaDeCosto->escala_final) {
                                    $this->precio = $escalaDeCosto->price;
                                }
                            } else if ($escalaDeCosto->escala_final == null) {
                                if ((int)$info['quantity'] >= $escalaDeCosto->escala_inicial) {
                                    $this->precio = $escalaDeCosto->price;
                                }
                            }
                        }
                    }
                }
                // dd($this->infoScales);
                $nuevoPrecio = round(($this->precio + ($precioDeTecnicaUsado * $this->colores) + $this->operacion) / ((100 - $info['utility']) / 100), 2);
                array_push($this->priceScalesComplete, [
                    'quantity' => $info['quantity'],
                    'tecniquePrice' => $info['tecniquePrice'] != null ? floatval($info['tecniquePrice'])  : $precioDeTecnica,
                    'utility' => $info['utility'],
                    'unit_price' => $nuevoPrecio,
                    'total_price' => $nuevoPrecio * $info['quantity'],
                ]);
            }
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
        $this->validarFormulario();

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
            'dias_entrega' => $this->entrega,
            'images_selected' => $this->imageSelected,
            'type_days' => $this->typeDays
        ];

        if (!$this->priceScales) {
            $dataQuote['utilidad'] = $this->utilidad;
            $dataQuote['new_price_technique'] = $this->newPriceTechnique;
            $dataQuote['cantidad'] = $this->cantidad;
            $dataQuote['precio_unitario'] = $this->precioCalculado;
            $dataQuote['precio_total'] = $this->precioTotal;
            $dataQuote['quote_by_scales'] = false;
            $dataQuote['scales_info'] = null;
        } else {
            $dataQuote['utilidad'] = null;
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
        //$this->resetData();
    }

    public function editarCurrentCotizacion()
    {
        $this->validarFormulario();

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
            'dias_entrega' => $this->entrega,
            'images_selected' => $this->imageSelected,
            'type_days' => $this->typeDays
        ];

        if (!$this->priceScales) {
            $dataQuote['utilidad'] = $this->utilidad;
            $dataQuote['new_price_technique'] = $this->newPriceTechnique;
            $dataQuote['cantidad'] = $this->cantidad;
            $dataQuote['precio_unitario'] = $this->precioCalculado;
            $dataQuote['precio_total'] = $this->precioTotal;
            $dataQuote['quote_by_scales'] = false;
            $dataQuote['scales_info'] = null;
        } else {
            $dataQuote['utilidad'] = null;
            $dataQuote['new_price_technique'] = null;
            $dataQuote['cantidad'] = null;
            $dataQuote['precio_unitario'] = null;
            $dataQuote['precio_total'] = null;
            $dataQuote['quote_by_scales'] = true;
            $dataQuote['scales_info'] = json_encode($this->priceScalesComplete);
        }

        $this->currentQuote->update($dataQuote);
        $this->resetData();
        $this->dispatchBrowserEvent('closeModal', ['currentQuote' => $this->currentQuote->id]);
        $this->emit('updateProductCurrent');
    }

    public function editarCotizacion()
    {
        $this->validarFormulario();
        $product = $this->product->toArray();
        $product['image'] = $this->imageSelected ?: ($this->product->firstImage ? $this->product->firstImage->image_url : '');
        unset($this->product->firstImage);
        unset($this->product->images);
        if (!is_numeric($this->newPriceTechnique))
            $this->newPriceTechnique = null;
        if (trim($this->newDescription) == "")
            $this->newDescription = null;

        $newQuote = [
            'currentQuote_id' => $this->currentQuote_id,
            'product' => json_encode($product),
            'prices_techniques_id' => $this->priceTechnique->id,
            'new_description' => $this->newDescription,
            'color_logos' => $this->colores,
            'costo_indirecto' => $this->operacion,
            'dias_entrega' => $this->entrega,
            'type_days' => $this->typeDays
        ];

        if (!$this->priceScales) {
            $newQuote['utilidad'] = $this->utilidad;
            $newQuote['newPriceTechnique'] = $this->newPriceTechnique;
            $newQuote['cantidad'] = $this->cantidad;
            $newQuote['precio_unitario'] = $this->precioCalculado;
            $newQuote['precio_total'] = $this->precioTotal;
            $newQuote['quote_by_scales'] = false;
            $newQuote['scales_info'] = null;
        } else {
            $newQuote['utilidad'] = null;
            $newQuote['newPriceTechnique'] = null;
            $newQuote['cantidad'] = null;
            $newQuote['precio_unitario'] = null;
            $newQuote['precio_total'] = null;
            $newQuote['quote_by_scales'] = true;
            $newQuote['scales_info'] = json_encode($this->priceScalesComplete);
        }

        $this->emit('productUpdate', $newQuote);
        $this->dispatchBrowserEvent('closeModal');
        $this->resetData();
    }

    public function addNewProductToQuote()
    {
        $this->validarFormulario();

        $product = $this->product->toArray();
        $product['image'] = $this->imageSelected ?: ($this->product->firstImage ? $this->product->firstImage->image_url : '');
        unset($this->product->firstImage);
        unset($this->product->images);
        if (!is_numeric($this->newPriceTechnique))
            $this->newPriceTechnique = null;
        if (trim($this->newDescription) == "")
            $this->newDescription = null;
        $newQuote = [
            'idNewQuote' => time(),
            'product' => json_encode($product),
            'prices_techniques_id' => $this->priceTechnique->id,
            'new_description' => $this->newDescription,
            'color_logos' => $this->colores,
            'costo_indirecto' => $this->operacion,
            'dias_entrega' => $this->entrega,
            'type_days' => $this->typeDays,
        ];

        if (!$this->priceScales) {
            $newQuote['utilidad'] = $this->utilidad;
            $newQuote['newPriceTechnique'] = $this->newPriceTechnique;
            $newQuote['cantidad'] = $this->cantidad;
            $newQuote['precio_unitario'] = $this->precioCalculado;
            $newQuote['precio_total'] = $this->precioTotal;
            $newQuote['quote_by_scales'] = false;
            $newQuote['scales_info'] = null;
        } else {
            $newQuote['utilidad'] = null;
            $newQuote['newPriceTechnique'] = null;
            $newQuote['cantidad'] = null;
            $newQuote['precio_unitario'] = null;
            $newQuote['precio_total'] = null;
            $newQuote['quote_by_scales'] = true;
            $newQuote['scales_info'] = json_encode($this->priceScalesComplete);
        }

        $this->emit('productAdded', $newQuote);
        $this->dispatchBrowserEvent('closeModal');
        $this->resetData();
    }

    public function seleccionarImagen($image)
    {
        $this->imageSelected = $image;
        $this->dispatchBrowserEvent('hideModalImage');
    }

    public function eliminarImagen()
    {
        $this->imageSelected = null;
    }

    public function changeTypePrice()
    {
        $this->priceScales = !$this->priceScales;
    }

    public function openScale()
    {
        $this->editScale = false;
        $this->itemEditScale = null;
        $this->cantidad = null;
        $this->precioTecnicaEscala = null;
        $this->dispatchBrowserEvent('showModalScales');
    }

    public function closeScale()
    {
        $this->editScale = false;
        $this->itemEditScale = null;
        $this->cantidad = null;
        $this->precioTecnicaEscala = null;
        $this->dispatchBrowserEvent('hideModalScales');
    }

    public function openModalImage()
    {
        $this->dispatchBrowserEvent('showModalImage');
    }

    public function closeModalImage()
    {
        $this->dispatchBrowserEvent('hideModalImage');
    }

    public function addScale()
    {
        $this->itemEditScale = null;
        $this->validate([
            'cantidad' => 'required|numeric|min:1',
            'utilidad' => 'required|numeric|min:0|max:99',
        ]);
        if ($this->precioTecnicaEscala != "0") {
            if (!is_numeric($this->precioTecnicaEscala))
                $this->precioTecnicaEscala = null;
        }
        array_push($this->infoScales, [
            'quantity' => $this->cantidad,
            'utility' => $this->utilidad,
            'tecniquePrice' => $this->precioTecnicaEscala,
        ]);
        $this->cantidad = 1;
        $this->precioTecnicaEscala = null;
        $this->utilidad = null;
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

    public function openDeleteScale($scale_id)
    {
        $this->dispatchBrowserEvent('openConfirmDelete', ['id' => $scale_id]);
    }

    public function editScale($scale_id)
    {
        $this->editScale =  true;
        $this->itemEditScale = $scale_id;
        $this->cantidad = $this->infoScales[$scale_id]['quantity'];
        $this->utilidad = $this->infoScales[$scale_id]['utility'];
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
            'utility' => $this->utilidad,
            'tecniquePrice' => $this->precioTecnicaEscala >= 0 ? $this->precioTecnicaEscala : null,
        ];
        $this->cantidad = 1;
        $this->precioTecnicaEscala = null;
        $this->utilidad = null;
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
        $this->priceScalesComplete = [];
        $this->priceScales = false;
    }

    public function resetTecnique()
    {
        $this->sizeSeleccionado = null;
        $this->tecnicaSeleccionada = null;
    }

    public function resetSizes()
    {
        $this->sizeSeleccionado = null;
    }

    public function validarFormulario()
    {
        $this->validate([
            'colores' => 'required|numeric|min:1',
            'operacion' => 'required|numeric|min:0',
            'entrega' => 'required|numeric|min:0',
        ]);
        if (!$this->priceScales) {
            $this->validate([
                'priceTechnique' => 'required',
                'cantidad' => 'required|numeric|min:1',
                'utilidad' => 'required|numeric|min:0|max:99',
            ]);
            $this->infoScales = [];
        } else {
            $this->validate([
                'infoScales' => 'array|required',
                'infoScales.*.quantity' => 'required|numeric|min:1',
                'sizeSeleccionado' => 'required'
            ]);
            $preciosDisponibles = [];
            if ($this->sizeSeleccionado !== null && $this->sizeSeleccionado !== "") {
                $preciosDisponibles = SizeMaterialTechnique::where('material_technique_id', $this->tecnicaSeleccionada)->where('size_id', (int)$this->sizeSeleccionado)->first()->pricesTechniques;
            }
            $this->priceTechnique = $preciosDisponibles[0];
            $this->cantidad = 0;
            $this->utilidad = 0;
        }
    }
}
