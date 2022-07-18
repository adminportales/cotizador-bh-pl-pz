<?php

namespace App\Http\Livewire;

use Livewire\Component;

class EditarCotizacionComponent extends Component
{
    public $quote, $puedeEditar = false, $inputDiscount;

    protected $listeners = ['productAdded' => 'addProducto'];

    // Variables Editables
    public $listNewProducts = [], $listUpdateCurrent, $listDeleteCurrent, $newDiscount;

    public function render()
    {
        return view('livewire.editar-cotizacion-component');
    }

    public function editar()
    {
        $this->puedeEditar = !$this->puedeEditar;
    }
    public function editarProducto($product, $isNew = false)
    {
        dd($product);
    }

    public function addProducto($productAdded)
    {
        array_push($this->listNewProducts, $productAdded);
        // dd($this->listNewProducts);
    }
}
