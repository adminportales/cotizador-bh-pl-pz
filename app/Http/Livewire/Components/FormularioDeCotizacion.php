<?php

namespace App\Http\Livewire\Components;

use App\Models\Catalogo\GlobalAttribute;
use App\Models\Catalogo\Product;
use App\Models\Material;
use App\Models\MaterialTechnique;
use App\Models\PricesTechnique;
use App\Models\SizeMaterialTechnique;
use App\Models\Technique;
use Exception;
use Livewire\Component;


/**
 * Clase que representa el componente de formulario de cotización.
 */
class FormularioDeCotizacion extends Component
{
    /**
     * Producto actual.
     *
     * @var mixed
     */
    public $product;

    /**
     * Cotización actual.
     *
     * @var mixed
     */
    public $currentQuote;

    /**
     * Producto en edición.
     *
     * @var mixed
     */
    public $productEdit;

    /**
     * ID de la cotización actual.
     *
     * @var mixed
     */
    public $currentQuote_id;

    /**
     * Nuevo producto agregado.
     *
     * @var mixed
     */
    public $productNewAdd;

    /**
     * Precio del producto.
     *
     * @var float
     */
    public $precio;

    /**
     * Precio calculado.
     *
     * @var float
     */
    public $precioCalculado;

    /**
     * Precio total.
     *
     * @var float
     */
    public $precioTotal = 0;

    /**
     * Técnica seleccionada.
     *
     * @var mixed
     */
    public $tecnica = null;

    /**
     * Colores seleccionados.
     *
     * @var mixed
     */
    public $colores = null;

    /**
     * Operación seleccionada.
     *
     * @var mixed
     */
    public $operacion = null;

    /**
     * Utilidad seleccionada.
     *
     * @var mixed
     */
    public $utilidad = null;

    /**
     * Entrega seleccionada.
     *
     * @var mixed
     */
    public $entrega = null;

    /**
     * Cantidad seleccionada.
     *
     * @var mixed
     */
    public $cantidad = null;

    /**
     * Precio de la técnica.
     *
     * @var mixed
     */
    public $priceTechnique;

    /**
     * Nuevo precio de la técnica.
     *
     * @var mixed
     */
    public $newPriceTechnique = null;

    /**
     * Nueva descripción.
     *
     * @var mixed
     */
    public $newDescription = null;

    /**
     * Imagen seleccionada.
     *
     * @var mixed
     */
    public $imageSelected;

    /**
     * Indica si se utiliza el precio por escalas.
     *
     * @var bool
     */
    public $priceScales;

    /**
     * Información de las escalas de precios.
     *
     * @var array
     */
    public $infoScales = [];

    /**
     * Precios de las escalas completas.
     *
     * @var array
     */
    public $priceScalesComplete = [];

    /**
     * Cantidad de la escala.
     *
     * @var mixed
     */
    public $cantidadEscala;

    /**
     * Precio de la técnica en la escala.
     *
     * @var mixed
     */
    public $precioTecnicaEscala;

    /**
     * Indica si se está editando una escala.
     *
     * @var bool
     */
    public $editScale = false;

    /**
     * Ítem de la escala en edición.
     *
     * @var mixed
     */
    public $itemEditScale;

    /**
     * Tipo de días.
     *
     * @var mixed
     */
    public $typeDays;

    /**
     * Material seleccionado.
     *
     * @var mixed
     */
    public $materialSeleccionado;

    /**
     * Técnica seleccionada.
     *
     * @var mixed
     */
    public $tecnicaSeleccionada;

    /**
     * Tamaño seleccionado.
     *
     * @var mixed
     */
    public $sizeSeleccionado;

    /**
     * Método que se ejecuta al inicializar el componente FormularioDeCotizacion.
     * Se encarga de asignar los valores iniciales a las propiedades del componente
     * basados en la cotización actual o en la edición de un producto.
     */
    public function mount()
    {
        // Asignar valores iniciales si existe una cotización actual
        $this->priceScales = false;
        if ($this->currentQuote) {
            // Obtener el producto de la cotización actual
            $this->product = Product::find($this->currentQuote->product_id);

            // Asignar valores de la cotización actual a las propiedades del componente
            $this->colores = $this->currentQuote->color_logos;
            $this->operacion = $this->currentQuote->costo_indirecto;
            $this->cantidad = $this->currentQuote->cantidad ?: 1;
            $this->utilidad = $this->currentQuote->utilidad;
            $this->entrega = $this->currentQuote->dias_entrega;
            $this->typeDays = $this->currentQuote->type_days;
            $this->newPriceTechnique = $this->currentQuote->new_price_technique;
            $this->newDescription = $this->currentQuote->new_description;
            $this->imageSelected = $this->currentQuote->images_selected;

            // Obtener el precio y la técnica seleccionada de la cotización actual
            $prices_techniques = PricesTechnique::find($this->currentQuote->prices_techniques_id);
            $this->materialSeleccionado = $prices_techniques->sizeMaterialTechnique->materialTechnique->material->id;
            $this->tecnicaSeleccionada = $prices_techniques->sizeMaterialTechnique->materialTechnique->technique->id;
            $this->sizeSeleccionado = $prices_techniques->sizeMaterialTechnique->size->id;

            // Verificar si la cotización se realiza por escalas de precio
            $this->priceScales = $this->currentQuote->quote_by_scales;
            if ($this->priceScales) {
                // Obtener la información de las escalas de precio y asignarla a la propiedad infoScales
                $this->priceScalesComplete = json_decode($this->currentQuote->scales_info);
                $this->infoScales = array_map(function ($scale) {
                    $costoIndirecto = (property_exists($scale, 'operacion') && $scale->operacion) ? $scale->operacion : $this->operacion;
                    return ['quantity' => $scale->quantity, 'utility' => $scale->utility, 'operacion' => $costoIndirecto, 'tecniquePrice' => $scale->tecniquePrice];
                }, $this->priceScalesComplete);
            }
        }

        // Asignar valores iniciales si se está editando un producto
        if ($this->productEdit) {
            // Obtener el producto a editar
            $product = json_decode($this->productEdit['product']);
            $this->product = Product::find($product->id);
            $this->product->price = $product->price;

            // Obtener la información de las técnicas del producto a editar
            $techniquesInfo = (object) json_decode($this->productEdit['technique']);

            // Asignar valores del producto a editar a las propiedades del componente
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
            $this->newDescription = $this->productEdit['new_description'];

            // Verificar si la cotización se realiza por escalas de precio
            $this->priceScales = $this->productEdit['quote_by_scales'];
            if ($this->priceScales) {
                // Obtener la información de las escalas de precio y asignarla a la propiedad infoScales
                $this->priceScalesComplete = json_decode($this->productEdit['scales_info']);
                $this->infoScales = array_map(function ($scale) {
                    $costoIndirecto = (property_exists($scale, 'operacion') && $scale->operacion) ? $scale->operacion : $this->operacion;
                    return ['quantity' => $scale->quantity, 'utility' => $scale->utility, 'operacion' => $costoIndirecto, 'tecniquePrice' => $scale->tecniquePrice];
                }, $this->priceScalesComplete);
            }
        }

        // Asignar valor inicial si se está agregando un nuevo producto
        if ($this->productNewAdd) {
            $this->product = $this->productNewAdd;
        }

        // Obtener la utilidad global
        $utilidad = GlobalAttribute::find(1);
        $utilidad = (float) $utilidad->value;

        // Calcular el precio del producto
        $priceProduct = $this->product->price;
        // Verifica si existe el atributo 'Outlet' y hace el 30 de descuento
        $productType = $this->product->productAttributes->where('attribute', 'Tipo Descuento')->first();

        if ($productType && $productType->value == 'Normal') {
            $priceProduct = round($priceProduct - $priceProduct * (30 / 100), 2);
        }else if($productType && ($productType->value == 'Outlet' || $productType->value == 'Unico')){
            $priceProduct = round($priceProduct - $priceProduct * (0 / 100), 2);
        }else{
            if ($this->product->producto_promocion) {
                $priceProduct = round($priceProduct - $priceProduct * ($this->product->descuento / 100), 2);
            } else {
                $priceProduct = round($priceProduct - $priceProduct * ($this->product->provider->discount / 100), 2);
            }
        }

        $this->precio = round($priceProduct + $priceProduct * ($utilidad / 100), 2);
        $this->precioCalculado = $this->precio;
    }
    

    /**
     * Renderiza el formulario de cotización y calcula el precio total.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $this->precioTotal = 0;
        // Obtener precios de las tecnicas

        // Obtengo Materiales
        $materiales = Material::where('active', true)->get();

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

        if ($this->tecnicaSeleccionada == 8) {
            $this->colores = 1;
        }


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

            $nuevoPrecio = round(($this->precio + ($precioDeTecnicaUsado * $this->colores) + $this->operacion) / ((100 - $this->utilidad) / 100),2);

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
                $nuevoPrecio = round(($this->precio + ($precioDeTecnicaUsado * $this->colores) + $info['operacion']) / ((100 - $info['utility']) / 100), 2);

                array_push($this->priceScalesComplete, [
                    'quantity' => $info['quantity'],
                    'tecniquePrice' => $info['tecniquePrice'] != null ? floatval($info['tecniquePrice'])  : $precioDeTecnica,
                    'utility' => $info['utility'],
                    'unit_price' => $nuevoPrecio,
                    'operacion' => $info['operacion'],
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

        return view('components.cotizador.formulario-de-cotizacion', [
            'materiales' => $materiales,
            'techniquesAvailables' => $techniquesAvailables,
            'sizesAvailables' => $sizesAvailables,
            'preciosDisponibles' => $preciosDisponibles,
            "precioDeTecnica" => $precioDeTecnica
        ]);
    }

    /**
     * Agrega una cotización al formulario de cotización.
     *
     * Este método se encarga de agregar una cotización al formulario de cotización.
     * Realiza validaciones del formulario y guarda los datos de la cotización en la base de datos.
     * Si el usuario no tiene una cotización activa, crea una nueva cotización por defecto.
     * Si el usuario tiene varias cotizaciones pero ninguna por defecto, muestra un mensaje de error.
     * Si el usuario tiene una cotización activa y la opción de escalas de precios está activada, reinicia los valores de descuento, tipo y valor de la cotización activa.
     * Luego, guarda los datos de la cotización en la base de datos.
     * Finalmente, muestra un mensaje de éxito y reinicia los datos del formulario.
     *
     * @return void
     */
    public function agregarCotizacion()
    {
        $this->validarFormulario();

        $currentQuoteActive = auth()->user()->currentQuoteActive;
        $currentQuotes = auth()->user()->currentQuotes;

        if ($currentQuoteActive === null) {
            if (count($currentQuotes) <= 0) {
                $currentQuoteActive = auth()->user()->currentQuotes()->create([
                    'discount' => false,
                    'active' => true
                ]);
            } else {
                dd('Tienes varias cotizaciones pero no tienes ninguna por defecto, ve a tus cotizaciones y selecciona a donde quieres cotizar');
            }
        } else {
            if (auth()->user()->currentQuoteActive && $this->priceScales == true) {
                auth()->user()->currentQuoteActive->discount = false;
                auth()->user()->currentQuoteActive->type = null;
                auth()->user()->currentQuoteActive->value = null;
                auth()->user()->currentQuoteActive->save();
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
        $currentQuoteActive->currentQuoteDetails()->create($dataQuote);
        session()->flash('message', 'Se ha agregado este producto a la cotizacion.');
        $this->emit('currentQuoteAdded');
        $this->resetData();
    }

    /**
     * Actualiza la cotización actual con los datos del formulario.
     *
     * Valida el formulario antes de realizar la actualización.
     * Si el nuevo precio de la técnica no es numérico, se establece como nulo.
     * Si la nueva descripción está vacía, se establece como nula.
     *
     * Los datos de la cotización se asignan a un arreglo y se actualiza la cotización actual con estos datos.
     *
     * Si no se utilizan escalas de precios, se asignan los valores correspondientes al arreglo de datos.
     * Si se utilizan escalas de precios, se asigna null a los valores correspondientes al arreglo de datos y se codifica en JSON la información de las escalas de precios.
     *
     * Se reinician los datos del formulario y se emiten eventos para cerrar el modal y actualizar el producto actual.
     */
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
            $dataQuote['operacion'] = null;
            $dataQuote['precio_total'] = null;
            $dataQuote['quote_by_scales'] = true;
            $dataQuote['scales_info'] = json_encode($this->priceScalesComplete);
        }

        $this->currentQuote->update($dataQuote);
        $this->resetData();
        $this->dispatchBrowserEvent('closeModal', ['currentQuote' => $this->currentQuote->id]);
        $this->emit('updateProductCurrent');
    }

    /**
     * Método para editar una cotización.
     *
     * Realiza la validación del formulario y actualiza los datos de la cotización.
     * Se encarga de preparar los datos necesarios y emitir eventos para actualizar la interfaz.
     * También se encarga de cerrar el modal y reiniciar los datos del formulario.
     */
    public function editarCotizacion()
    {
        // Validar el formulario
        $this->validarFormulario();

        // Preparar los datos del producto
        $product = $this->product->toArray();
        $product['image'] = $this->imageSelected ?: ($this->product->firstImage ? $this->product->firstImage->image_url : '');
        unset($this->product->firstImage);
        unset($this->product->images);

        // Verificar y ajustar los valores de precio y descripción
        if (!is_numeric($this->newPriceTechnique))
            $this->newPriceTechnique = null;
        if (trim($this->newDescription) == "")
            $this->newDescription = null;

        // Crear el nuevo objeto de cotización
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

        // Verificar si se utiliza la escala de precios
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

        // Emitir evento para actualizar la interfaz
        $this->emit('productUpdate', $newQuote);

        // Cerrar el modal
        $this->dispatchBrowserEvent('closeModal');

        // Reiniciar los datos del formulario
        $this->resetData();
    }

    /**
     * Agrega un nuevo producto a la cotización.
     *
     * Este método valida el formulario y agrega un nuevo producto a la cotización actual.
     * Se encarga de preparar los datos del producto y generar un nuevo registro de cotización.
     * Luego, emite un evento para notificar que se ha agregado un producto y cierra el modal.
     * Finalmente, reinicia los datos del formulario.
     */
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

    /**
     * Selecciona una imagen.
     *
     * @param string $image La imagen seleccionada.
     * @return void
     */
    public function seleccionarImagen($image)
    {
        $this->imageSelected = $image;
        $this->dispatchBrowserEvent('hideModalImage');
    }

    /**
     * Elimina la imagen seleccionada.
     *
     * @return void
     */
    public function eliminarImagen()
    {
        $this->imageSelected = null;
    }

    /**
     * Cambia el tipo de precio.
     *
     * @param bool $isIscale Indica si el precio es escalado.
     * @return void
     */
    public function changeTypePrice($isIscale)
    {
        $this->priceScales = $isIscale;
    }

    /**
     * Abre el modal de escalas.
     *
     * @return void
     */
    public function openScale()
    {
        $this->editScale = false;
        $this->itemEditScale = null;
        $this->cantidad = null;
        $this->precioTecnicaEscala = null;
        $this->dispatchBrowserEvent('showModalScales');
    }

    /**
     * Cierra el modal de escalas.
     *
     * @return void
     */
    public function closeScale()
    {
        $this->editScale = false;
        $this->itemEditScale = null;
        $this->cantidad = null;
        $this->precioTecnicaEscala = null;
        $this->dispatchBrowserEvent('hideModalScales');
    }

    /**
     * Abre el modal de imagen.
     *
     * @return void
     */
    public function openModalImage()
    {
        $this->dispatchBrowserEvent('showModalImage');
    }

    /**
     * Cierra el modal de imagen.
     *
     * @return void
     */
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
            'operacion' => $this->operacion

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
            'operacion' => $this->operacion
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
                $materialTechnique = MaterialTechnique::where('technique_id', (int)$this->tecnicaSeleccionada)->where('material_id', (int)$this->materialSeleccionado)->first();
                $preciosDisponibles = SizeMaterialTechnique::where('material_technique_id', $materialTechnique->id)->where('size_id', (int)$this->sizeSeleccionado)->first()->pricesTechniques;
            }
            $this->priceTechnique = $preciosDisponibles[0];
            $this->cantidad = 0;
            $this->utilidad = 0;
        }
    }
}
