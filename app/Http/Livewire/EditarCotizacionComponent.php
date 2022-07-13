<?php

namespace App\Http\Livewire;

use Livewire\Component;

class EditarCotizacionComponent extends Component
{
    public $quote, $puedeEditar = false;
    public function render()
    {
        return view('livewire.editar-cotizacion-component');
    }
    public function editar()
    {
        $this->puedeEditar = !$this->puedeEditar;
    }
    public function agregarProducto()
    {

    }
}
