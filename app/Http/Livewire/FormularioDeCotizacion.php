<?php

namespace App\Http\Livewire;

use Livewire\Component;

use function PHPSTORM_META\type;

class FormularioDeCotizacion extends Component
{
    public $product;

    public $precio, $precioCalculado;

    public $tecnica = 0, $colores = 0, $operacion = 0, $utilidad = 0, $entrega = 0, $cantidad = 0;

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
        if (!is_numeric($this->tecnica))
            $this->tecnica = 0;
        if (!is_numeric($this->colores))
            $this->colores = 0;
        if (!is_numeric($this->operacion))
            $this->operacion = 0;
        if (!is_numeric($this->utilidad))
            $this->utilidad = 0;
        $nuevoPrecio = round(($this->precio + $this->tecnica + $this->operacion) / ((100 - $this->utilidad) / 100), 2);

        $this->precioCalculado = $nuevoPrecio;
        return view('pages.catalogo.formulario-de-cotizacion');
    }
}
