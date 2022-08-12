<?php

namespace App\Http\Livewire;

use App\Models\QuoteDiscount;
use Livewire\Component;

class EditQuoteDiscountComponent extends Component
{
    public $quote, $subtotalAdded = 0, $subtotal, $discount;
    public $value, $type, $discountStatus;

    protected $listeners = ['updateSubtotal' => 'updateSubtotalAdded'];

    public function mount()
    {
        $discount = $this->quote->latestQuotesUpdate->quoteDiscount;
        $this->value = $discount->value;
        $this->type = $discount->type;
        $this->discountStatus = $discount->discount;


        $this->subtotal = $this->quote->latestQuotesUpdate->quoteProducts->sum('precio_total');
        $this->discount = 0;
        if ($this->quote->latestQuotesUpdate->quoteDiscount->type == 'Fijo') {
            $this->discount = $this->quote->latestQuotesUpdate->quoteDiscount->value;
        } else {
            $this->discount = round(($this->subtotal / 100) * $this->quote->latestQuotesUpdate->quoteDiscount->value, 2);
        }
    }

    public function render()
    {
        return view('livewire.edit-quote-discount-component');
    }

    public function updateSubtotalAdded($data)
    {
        if ($data['toAdd']) {
            $this->subtotalAdded = $this->subtotalAdded + $data['subtotal'];
        } else {
            $this->subtotalAdded = $this->subtotalAdded - $data['subtotal'];
        }
    }

    public function updateDiscount()
    {
        $dataDiscount = [
            'discount' => $this->discountStatus,
            'type' => $this->type,
            'value' => $this->value,
        ];

        $quoteDiscount = QuoteDiscount::create($dataDiscount);

        $latestProductos = $this->quote->latestQuotesUpdate->quoteProducts;
        $newQuoteUpdate =  $this->quote->quotesUpdate()->create([
            'quote_information_id' => $this->quote->latestQuotesUpdate->quote_information_id,
            'quote_discount_id' => $quoteDiscount->id,
        ]);
        foreach ($latestProductos as $product) {
            $newQuoteUpdate->quoteProducts()->create([
                "product" => $product->product,
                "technique" => $product->technique,
                "prices_techniques" => $product->prices_techniques,
                "color_logos" => $product->color_logos,
                "costo_indirecto" => $product->costo_indirecto,
                "utilidad" => $product->utilidad,
                "dias_entrega" => $product->dias_entrega,
                "cantidad" => $product->cantidad,
                "precio_unitario" => $product->precio_unitario,
                "precio_total" => $product->precio_total,
            ]);
        }

        $this->subtotal = $this->quote->latestQuotesUpdate->quoteProducts->sum('precio_total');
        $this->discount = 0;
        if ($this->quote->latestQuotesUpdate->quoteDiscount->type == 'Fijo') {
            $this->discount = $this->quote->latestQuotesUpdate->quoteDiscount->value;
        } else {
            $this->discount = round(($this->subtotal / 100) * $this->quote->latestQuotesUpdate->quoteDiscount->value, 2);
        }

        $this->emit('updateQuoteDiscount', $quoteDiscount);
        $this->dispatchBrowserEvent('hideModalDiscount');
    }
}
