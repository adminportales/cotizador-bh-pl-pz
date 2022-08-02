<?php

namespace App\Http\Livewire;

use App\Mail\SendQuote;
use App\Notifications\SendQuoteByEmail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
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

    public function enviar()
    {
        $pdf = '';
        switch (auth()->user()->company->name) {
            case 'PROMO LIFE':
                # code...
                $pdf = PDF::loadView('pages.pdf.promolife', ['quote' => $this->quote]);
                break;
            case 'BH TRADEMARKET':
                # code...
                $pdf = PDF::loadView('pages.pdf.bh', ['quote' => $this->quote]);
                break;
            case 'PROMO ZALE':
                # code...
                $pdf = PDF::loadView('pages.pdf.promozale', ['quote' => $this->quote]);
                break;

            default:
                # code...
                break;
        }
        $pdf = PDF::loadView('pages.pdf.promolife', ['quote' => $this->quote]);
        $pdf->setPaper('Letter', 'portrait');
        $pdf = $pdf->stream($this->quote->lead . ".pdf");
        file_put_contents(public_path() . "/storage/quotes/" . $this->quote->lead . ".pdf", $pdf);
        // Mail::to($this->quote->latestQuotesInformation->email)->send(new SendQuote(auth()->user()->name, $this->quote->latestQuotesInformation->name, '/storage/quotes/'.$this->quote->lead . ".pdf"));
        Mail::to('antoniotd87@gmail.com')->send(new SendQuote(auth()->user()->name, $this->quote->latestQuotesInformation->name, '/storage/quotes/' . $this->quote->lead . ".pdf"));
        dd('Enviado');
    }
}
