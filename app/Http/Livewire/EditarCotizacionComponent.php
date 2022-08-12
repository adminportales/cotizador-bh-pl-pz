<?php

namespace App\Http\Livewire;

use Livewire\Component;

class EditarCotizacionComponent extends Component
{
    public $quote, $puedeEditar = false, $inputDiscount;

    protected $listeners = ['productAdded' => 'addProducto', 'puedeEditar' => 'editar'];

    // Variables Editables
    public $listNewProducts = [], $listUpdateCurrent = [], $listDeleteCurrent = [], $newDiscount;

    public function render()
    {
        return view('livewire.editar-cotizacion-component');
    }

    public function editar()
    {
        $this->puedeEditar = !$this->puedeEditar;
        // $this->dispatchBrowserEvent('show-modal')
    }

    public function editarProducto($product, $isNew = false)
    {
        dd($product);
    }

    public function addProducto($productAdded)
    {
        array_push($this->listNewProducts, $productAdded);
    }
    public function deleteProducto($productDeleted)
    {
        array_push($this->listDeleteCurrent, $productDeleted);
    }
}
