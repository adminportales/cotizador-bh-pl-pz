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
use Exception;
use Illuminate\Support\Facades\Mail;

class FinalizarCotizacion extends Component
{
    public $tipoCliente, $clienteSeleccionado = '', $isClient, $nombre, $empresa, $email, $telefono, $celular, $oportunidad, $rank = '', $departamento, $informacion;
    public function render()
    {
        $userClients = auth()->user()->clients;
        return view('pages.catalogo.finalizar-cotizacion', compact('userClients'));
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
            $this->isClient = false;
        } else {
            $this->validate([
                'clienteSeleccionado' => 'required',
            ]);
            $this->isClient = true;
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
            'lead' => 'No Definido',
            'client' => $this->isClient,
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
            $product = Product::find($item->product_id);
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

            // Agregar la URL de la Imagen
            // dd($product->firstImage);
            $product->image = $product->firstImage == null ? '' : $product->firstImage->image_url;
            $quoteUpdate->quoteProducts()->create([
                'product' => json_encode($product->toArray()),
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
        file_put_contents(public_path() . "/storage/quotes/" . time() . $quote->lead . ".pdf", $pdf);
        // try {
        //     $url = 'https://api-promolife.vde/suite.com:5030/custom/Promolife/V2/crm-lead/create';
        //     $data =  [
        //         'Name' => $this->oportunidad,
        //         'Partner' => [
        //             'Name' => $this->empresa,
        //             'Email' => $this->email,
        //             'Phone' => $this->celular,
        //             'Contact' => $this->nombre,
        //         ],
        //         "Estimated" => (floatval($quoteUpdate->quoteProducts()->sum('precio_total'))),
        //         "Rating" => 1,
        //         "UserID" => 1250,
        //         "File" => [
        //             'Data' => base64_encode($pdf),
        //             'Name' => 'Ninguno',
        //         ]
        //     ];
        //     $curl = curl_init($url);
        //     // 1. Set the CURLOPT_RETURNTRANSFER option to true
        //     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //     // 2. Set the CURLOPT_POST option to true for POST request
        //     curl_setopt($curl, CURLOPT_POST, true);
        //     // 3. Set the request data as JSON using json_encode function
        //     curl_setopt($curl, CURLOPT_POSTFIELDS,  json_encode(['Opportunities' => $data]));
        //     curl_setopt($curl, CURLOPT_HTTPHEADER, [
        //         'Content-Type: application/json',
        //         'X-VDE-APIKEY: cd78567e59e016e964cdcc1bd99367c6',
        //         'X-VDE-TYPE: Ambos',
        //     ]);
        //     $response = curl_exec($curl);
        //     dd($response);
        // } catch (Exception $exception) {
        //     dd($exception->getMessage());
        // }
        // return;
        // Mail::to($quote->latestQuotesUpdate->quotesInformation->email)->send(new SendQuote(auth()->user()->name, $quote->latestQuotesUpdate->quotesInformation->name, '/storage/quotes/' . $quote->lead . ".pdf"));

        Mail::to('adminportales@promolife.com.mx')->send(new SendQuote(auth()->user()->name, $quote->latestQuotesUpdate->quotesInformation->name, '/storage/quotes/' . $quote->lead . ".pdf"));
        Mail::mailer(env('MAIL_MAILER_ALT', 'smtpalt'))->to('adminportales@promolife.com.mx')->send(new SendQuote(auth()->user()->name, $quote->latestQuotesUpdate->quotesInformation->name, '/storage/quotes/' . $quote->lead . ".pdf"));
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
