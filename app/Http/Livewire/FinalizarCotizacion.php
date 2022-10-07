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
use Illuminate\Support\Facades\DB;
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
        $keyOdoo = '';
        $errors = false;
        $message = '';

        switch (auth()->user()->company->name) {
            case 'PROMO LIFE':
                # code...
                $keyOdoo = 'cd78567e59e016e964cdcc1bd99367c6';
                $pdf = PDF::loadView('pages.pdf.promolife', ['quote' => $quote]);
                break;
            case 'BH TRADEMARKET':
                # code...
                $keyOdoo = 'e877f47a2a844ded99004e444c5a9797';
                $pdf = PDF::loadView('pages.pdf.bh', ['quote' => $quote]);
                break;
            case 'PROMO ZALE':
                # code...
                $keyOdoo = '0e31683a8597606123ff4fcfab772ed7';
                $pdf = PDF::loadView('pages.pdf.promozale', ['quote' => $quote]);
                break;
            default:
                # code...
                break;
        }
        $pdf->setPaper('Letter', 'portrait');
        $pdf = $pdf->stream($quote->lead . ".pdf");
        $path =  "/storage/quotes/" . time() . $quote->lead . ".pdf";
        file_put_contents(public_path() . $path, $pdf);
        $newPath = "";
        try {
            $url = 'https://api-promolife.vde-suite.com:5030/custom/Promolife/V2/crm-lead/create';
            $data =  [
                'Opportunities' => [
                    [
                        "CodeLead" => "",
                        'Name' =>  $this->oportunidad,
                        'Partner' => [
                            'Name' => $this->empresa,
                            'Email' => $this->email,
                            'Phone' => $this->celular,
                            'Contact' => $this->nombre,
                        ],
                        "Estimated" => floatval($quoteUpdate->quoteProducts()->sum('precio_total')),
                        "Rating" => (int) $this->rank,
                        "UserID" => 5324312,
                        "File" => [
                            'Name' => $this->oportunidad,
                            'Data' => base64_encode($pdf),
                        ]
                    ]
                ]
            ];

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS,  json_encode($data));
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'X-VDE-APIKEY: ' . $keyOdoo,
                'X-VDE-TYPE: Ambos',
            ]);
            $response = curl_exec($curl);
            if ($response !== false) {
                $dataResponse = json_decode($response);
                if ($dataResponse->message) {
                    if ($dataResponse->message == 'Internal Server Error') {
                        $message = 'Error en servidor de Odoo';
                        $errors = true;
                        return;
                    }
                };
                if ($dataResponse->success) {
                    $listElementsOpportunities = $dataResponse->listElementsOpportunities;
                    if ($listElementsOpportunities[0]->success) {
                        $codeLead = $listElementsOpportunities[0]->CodeLead;
                        $quote->lead = $codeLead;
                        $quote->save();
                        $newPath = "/storage/quotes/" . time() . $quote->lead . ".pdf";
                        rename(public_path() . $path, public_path() . $newPath);
                    } else {
                        $errors = true;
                        $message = $listElementsOpportunities[0]->message;
                    }
                }
            } else {
                $errors = true;
                $message = "Error al enviar la cotizacion a odoo";
            }
        } catch (Exception $exception) {
            $message = $exception->getMessage();
        }
        try {
            if (!$errors) {
                Mail::to($quote->latestQuotesUpdate->quotesInformation->email)->send(new SendQuote(auth()->user()->name, $quote->latestQuotesUpdate->quotesInformation->name, $newPath));
                unlink(public_path() . $newPath);
                auth()->user()->currentQuote->currentQuoteDetails()->delete();
                auth()->user()->currentQuote()->delete();
            }
        } catch (Exception $exception) {
            $errors = true;
            $message = $exception->getMessage();
        }
        if ($errors) {
            DB::delete('delete from quote_update_product where quote_update_id = ' . $quoteUpdate->id);
            $quoteUpdate->quoteProducts();
            foreach ($quoteUpdate->quoteProducts as $qp) {
                $qp->delete();
            }
            $quoteUpdate->delete();
            $quoteInfo->delete();
            $quoteDiscount->delete();
            $quote->delete();
            dd($message);
            return;
        }
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
