<?php

namespace App\Http\Livewire;

use Livewire\Component;

class EditarCotizacionComponent extends Component
{
    public $quote, $puedeEditar = false, $inputDiscount;

    protected $listeners = ['productAdded' => 'addProducto', 'puedeEditar' => 'editar', 'productUpdate' => 'updateProduct'];

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
        $productEdit = '';
        if ($isNew) {
            foreach ($this->listNewProducts as $newProduct) {
                if ($newProduct['idNewQuote'] == $product) {
                    $productEdit = $newProduct;
                }
            }
        } else {
            $productEdit = $product;
        }
        $this->emit('editProduct', ['productEdit' => $productEdit, 'isNew' => $isNew]);
        $this->dispatchBrowserEvent('showModalEditar');
    }

    public function addProducto($productAdded)
    {
        array_push($this->listNewProducts, $productAdded);
    }
    public function updateProduct($productUpdate)
    {
        array_push($this->listUpdateCurrent, $productUpdate);
    }
    public function deleteProducto($productDeleted)
    {
        array_push($this->listDeleteCurrent, $productDeleted);
    }
    public function deleteNewProducto($productDeleted)
    {
        $newArray = [];
        foreach ($this->listNewProducts as $newProduct) {
            if ($newProduct['idNewQuote'] != $productDeleted) {
                array_push($newArray, $newProduct);
            }
        }
        $this->listNewProducts = $newArray;
    }

    public function guardar()
    {
        dd($this->listNewProducts, $this->listUpdateCurrent, $this->listDeleteCurrent);
    }
}
