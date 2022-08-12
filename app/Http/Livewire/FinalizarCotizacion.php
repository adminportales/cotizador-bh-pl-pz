<?php

namespace App\Http\Livewire;

use App\Models\Catalogo\Product;
use App\Models\PricesTechnique;
use Livewire\Component;
use App\Http\Controllers\CotizadorController;
use App\Mail\SendQuote;
use App\Models\QuoteDiscount;
use App\Models\QuoteInformation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class FinalizarCotizacion extends Component
{
    public $tipoCliente, $clienteSeleccionado = '', $nombre, $empresa, $email, $telefono, $celular, $oportunidad, $rank = '', $departamento, $informacion;
    public function render()
    {
        return view('pages.catalogo.finalizar-cotizacion');
    }
    public function guardarCotizacion()
    {
        $this->validate([
            'tipoCliente' => 'required',
        ]);

        if ($this->tipoCliente == 'crear') {
            $this->validate([
                'nombre' => 'required',
                'empresa' => 'required',
            ]);
        } else {
            $this->validate([
                'clienteSeleccionado' => 'required',
            ]);
            $this->nombre = 'Nombre de Oddo';
            $this->empresa = 'Empresa de Oddo';
        }

        $this->validate([
            'tipoCliente' => 'required',
            'email' => 'required|email',
            'telefono' => 'required|numeric',
            'celular' => 'required|numeric',
            'oportunidad' => 'required',
            'rank' => 'required',
        ]);

        // Guardar La cotizacion
        $quote = auth()->user()->quotes()->create([
            'lead' => '487'
        ]);

        // Guardar la Info de la cotizacion
        $quoteInfo = QuoteInformation::create([
            'name' => $this->nombre,
            'company' => $this->empresa,
            'email' => $this->email,
            'landline' => $this->telefono,
            'cell_phone' => $this->celular,
            'oportunity' => $this->oportunidad,
            'rank' => $this->rank,
            'department' => $this->departamento,
            'information' => $this->informacion,
        ]);

        // Guardar descuento
        $type = 'Fijo';
        $value = 0;
        $discount = false;
        if (auth()->user()->currentQuote->discount) {
            $discount = true;
            $type = auth()->user()->currentQuote->type;
            $value = auth()->user()->currentQuote->value;
        }

        $dataDiscount = [
            'discount' => $discount,
            'type' => $type,
            'value' => $value,
        ];

        $quoteDiscount = QuoteDiscount::create($dataDiscount);



        // Guardar la actualizacion
        $quoteUpdate = $quote->quotesUpdate()->create([
            'quote_information_id' => $quoteInfo->id,
            'quote_discount_id' => $quoteDiscount->id,
        ]);

        // Ligar Productos al update

        // Guardar los productos de la cotizacion
        foreach (auth()->user()->currentQuote->currentQuoteDetails as $item) {
            // TODO: Colocar un array con la data en las tecnicas y productos

            $product = Product::find($item->product_id)->toArray();
            $tecnica = PricesTechnique::find($item->prices_techniques_id);
            $price_tecnica =  $tecnica->precio;
            $material = $tecnica->sizeMaterialTechnique->materialTechnique->material->nombre;
            $material_id = $tecnica->sizeMaterialTechnique->materialTechnique->material->id;
            $tecnica_nombre = $tecnica->sizeMaterialTechnique->materialTechnique->technique->nombre;
            $tecnica_id = $tecnica->sizeMaterialTechnique->materialTechnique->technique->id;
            $size = $tecnica->sizeMaterialTechnique->size->nombre;
            $size_id = $tecnica->sizeMaterialTechnique->size->id;
            $infoTecnica = [
                'material_id' => $material_id,
                'material' => $material,
                'tecnica' => $tecnica_nombre,
                'tecnica_id' => $tecnica_id,
                'size' => $size,
                'size_id' => $size_id,
            ];

            $quoteUpdate->quoteProducts()->create([
                'product' => json_encode($product),
                'technique' =>  json_encode($infoTecnica),
                'prices_techniques' => $price_tecnica,
                'color_logos' => $item->color_logos,
                'costo_indirecto' => $item->costo_indirecto,
                'utilidad' => $item->utilidad,
                'dias_entrega' => $item->dias_entrega,
                'cantidad' => $item->cantidad,
                'precio_unitario' => $item->precio_unitario,
                'precio_total' => $item->precio_total
            ]);
        }

        // Enviar PDF

        $pdf = '';
        switch (auth()->user()->company->name) {
            case 'PROMO LIFE':
                # code...
                $pdf = PDF::loadView('pages.pdf.promolife', ['quote' => $quote]);
                break;
            case 'BH TRADEMARKET':
                # code...
                $pdf = PDF::loadView('pages.pdf.bh', ['quote' => $quote]);
                break;
            case 'PROMO ZALE':
                # code...
                $pdf = PDF::loadView('pages.pdf.promozale', ['quote' => $quote]);
                break;

            default:
                # code...
                break;
        }
        $pdf->setPaper('Letter', 'portrait');
        $pdf = $pdf->stream($quote->lead . ".pdf");
        file_put_contents(public_path() . "/storage/quotes/" . $quote->lead . ".pdf", $pdf);

        // Enviar PDF a ODOO
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'http://localhost/api/v1', [
            'headers' => [
                'X-VDE-APIKEY' => 'cd78567e59e016e964cdcc1bd99367c6',
                'X-VDE-TYPE'     => 'Ambos',
            ],
            'form_params' => [
                'field_name' => 'abc',
                'other_field' => '123',
                'nested_field' => [
                    'nested' => 'hello'
                ]
            ]
        ]);


        // Mail::to($quote->latestQuotesInformation->email)->send(new SendQuote(auth()->user()->name, $quote->latestQuotesInformation->name, '/storage/quotes/'.$quote->lead . ".pdf"));
        Mail::to('antoniotd87@gmail.com')->send(new SendQuote(auth()->user()->name, $quote->latestQuotesUpdate->quotesInformation->name, '/storage/quotes/' . $quote->lead . ".pdf"));
        return;
        // Eliminar los datos de la cotizacion actual
        auth()->user()->currentQuote->currentQuoteDetails()->delete();
        auth()->user()->currentQuote()->delete();
        return redirect()->action([CotizadorController::class, 'verCotizacion'], ['quote' => $quote->id])->with('message', 'Tu cotizacion se ha guardado exitosamente.');
    }

    public function resetData()
    {
        $this->nombre = '';
        $this->empresa = '';
        $this->email = '';
        $this->telefono = '';
        $this->celular = '';
        $this->oportunidad = '';
        $this->rank = '';
    }
}
