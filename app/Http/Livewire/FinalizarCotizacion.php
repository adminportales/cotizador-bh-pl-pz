<?php

namespace App\Http\Livewire;

use App\Models\Catalogo\Product;
use App\Models\PricesTechnique;
use Livewire\Component;
use App\Http\Controllers\CotizadorController;
use App\Mail\SendDataErrorCreateQuote;
use App\Mail\SendQuoteBH;
use App\Mail\SendQuotePL;
use App\Mail\SendQuotePZ;
use App\Models\Client;
use App\Models\QuoteDiscount;
use App\Models\QuoteInformation;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class FinalizarCotizacion extends Component
{
    use WithFileUploads;
    public $tipoCliente, $clienteSeleccionado = '', $isClient, $nombre, $empresa, $email, $telefono, $celular, $oportunidad, $rank = '', $departamento, $informacion, $ivaByItem, $typeDays, $showTotal, $logo, $taxFee, $shelfLife;
    public $urlPDFPreview;
    public $ejecutivos, $ejecutivoSeleccionado = null, $selectEjecutivo;

    public $currency, $currency_type, $show_tax;

    public function mount()
    {
        $this->ejecutivos = auth()->user()->managments;
        if (count($this->ejecutivos) == 1) {
            $this->ejecutivoSeleccionado = $this->ejecutivos[0];
        }
        $this->shelfLife = trim($this->shelfLife) == "" ? null : $this->shelfLife;
        $this->ivaByItem = false;
        $this->typeDays = 0;
        $this->showTotal = true;
        $this->show_tax = true;
        $this->currency_type = 'MXN';
        // Consumir api para el tipo de cambio con curl
        $curl = curl_init('https://www.banxico.org.mx/SieAPIRest/service/v1/series/SF43787/datos/oportuno');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Bmx-Token: ' . 'd01cf1306eced862bc6eece145a3599bb1a62e5276009872139970849a93cf17',
        ]);
        $response = curl_exec($curl);
        // Convertir la respuesta de string a json
        $response = json_decode($response, true);
        $this->currency = number_format($response['bmx']['series'][0]['datos'][0]['dato'], 2, '.', '');
    }

    public function render()
    {
        $userClients = [];

        if ($this->ejecutivoSeleccionado) {
            foreach ($this->ejecutivoSeleccionado->info as $info) {
                if ($info->company_id == auth()->user()->company_session) {
                    foreach ($info->clients()->where('company_id', $info->company_id)->get() as $userClient) {
                        array_push($userClients, $userClient);
                    }
                }
            }
        }

        foreach (auth()->user()->info as $info) {
            if ($info->company_id == auth()->user()->company_session) {
                foreach ($info->clients()->where('company_id', $info->company_id)->get() as $userClient) {
                    array_push($userClients, $userClient);
                }
            }
        }

        if ($this->taxFee > 99)
            $this->taxFee = 99;
        return view('pages.catalogo.finalizar-cotizacion', compact('userClients'));
    }

    public function limpiarLogo()
    {
        $this->logo = null;
    }

    public function selectManagment()
    {
        $this->ejecutivoSeleccionado = User::find($this->selectEjecutivo);
    }

    public function guardarCotizacion()
    {
        if (count(auth()->user()->info) < 1) {
            $this->dispatchBrowserEvent('isntCompany');
            return;
        }
        // Revisar que la cotizacion tenga productos
        if (auth()->user()->currentQuote == null) {
            dd("No hay productos en la cotizacion");
        }

        // Revisar que los productos si sean de mis proveedores
        foreach (auth()->user()->currentQuote->currentQuoteDetails as $item) {
            $product = Product::find($item->product_id);
            if (!in_array($product->provider_id, (auth()->user()->companySession->providers->pluck('id'))->toArray())) {
                dd("El producto " . $product->name . " no es de tu proveedor");
            }
        }

        $this->validate([
            'tipoCliente' => 'required',
        ]);
        $nombreComercial = null;
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
            $empresa = Client::where("name", $this->empresa)->first();
            if ($empresa) {
                $nombreComercial = $empresa->firstTradename;
            }
        }

        $this->validate([
            'tipoCliente' => 'required',
            'email' => 'required|email',
            'telefono' => 'required|numeric',
            'celular' => 'required|numeric',
            'oportunidad' => 'required',
            'rank' => 'required',
        ]);
        $odoo_id_user = null;
        if (count(auth()->user()->info) > 0) {
            foreach (auth()->user()->info as $infoOdoo) {
                if ($infoOdoo->company_id == auth()->user()->company_session) {
                    $odoo_id_user = $infoOdoo->odoo_id;
                }
            }
        }
        if ($odoo_id_user == null) {
            dd("No tienes un id de Odoo Asignado");
            return;
        }
        if ((int)$odoo_id_user <= 0) {
            dd("El id de odoo no es valido");
            return;
        }
        $pathLogo = null;
        if ($this->logo != null) {
            $name = time() . $this->empresa .  $this->logo->getClientOriginalExtension();
            $pathLogo = 'storage/logos/' . $name;
            $this->logo->storeAs('public/logos', $name);
            // Guardar La cotizacion
        }
        $quote = auth()->user()->quotes()->create([
            'lead' => 'No Definido',
            'iva_by_item' => boolval($this->ivaByItem),
            'show_total' => boolval($this->showTotal),
            'type_days' => $this->typeDays,
            'logo' => $pathLogo,
            'pending_odoo' => true,
            "company_id" => auth()->user()->company_session
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
            'tax_fee' => (int)$this->taxFee > 0 ? $this->taxFee : null,
            'shelf_life' =>  trim($this->shelfLife) == "" ? null : $this->shelfLife,
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
            'type' => "created"
        ]);

        // Ligar Productos al update

        // Guardar los productos de la cotizacion
        foreach (auth()->user()->currentQuote->currentQuoteDetails as $item) {
            $product = Product::find($item->product_id);
            $tecnica = PricesTechnique::find($item->prices_techniques_id);

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
            $product->image = $item->images_selected == null ? ($product->firstImage == null ? '' : $product->firstImage->image_url) : $item->images_selected;
            unset($product->firstImage);
            $product->provider;

            $dataProduct = [
                'product' => json_encode($product->toArray()),
                'technique' =>  json_encode($infoTecnica),
                'new_description' => $item->new_description,
                'color_logos' => $item->color_logos,
                'costo_indirecto' => $item->costo_indirecto,
                'utilidad' => $item->utilidad,
                'dias_entrega' => $item->dias_entrega,
                'type_days' => $item->type_days,
            ];

            if (!$item->quote_by_scales) {
                $price_tecnica = $item->new_price_technique != null ?
                    $item->new_price_technique
                    : ($tecnica->tipo_precio == 'D'
                        ? round($tecnica->precio / $item->cantidad, 2)
                        : $tecnica->precio);
                $dataProduct['prices_techniques'] = $price_tecnica;
                $dataProduct['cantidad'] = $item->cantidad;
                $dataProduct['precio_unitario'] = $item->precio_unitario;
                $dataProduct['precio_total'] = $item->precio_total;
                $dataProduct['quote_by_scales'] = false;
                $dataProduct['scales_info'] = null;
            } else {
                $dataProduct['prices_techniques'] = null;
                $dataProduct['cantidad'] = null;
                $dataProduct['precio_unitario'] = null;
                $dataProduct['precio_total'] = null;
                $dataProduct['quote_by_scales'] = true;
                $dataProduct['scales_info'] = $item->scales_info;
            }

            $quoteUpdate->quoteProducts()->create($dataProduct);
        }

        // Enviar PDF

        $pdf = '';
        $keyOdoo = '';
        $errors = false;
        $message = '';
        $messageMail = '';

        switch (auth()->user()->companySession->name) {
            case 'PROMO LIFE':
                # code...
                $keyOdoo = 'cd78567e59e016e964cdcc1bd99367c6';
                $pdf = PDF::loadView('pages.pdf.promolife', ['quote' => $quote, 'nombreComercial' => $nombreComercial]);
                break;
            case 'BH TRADEMARKET':
                # code...
                $keyOdoo = 'e877f47a2a844ded99004e444c5a9797';
                $pdf = PDF::loadView('pages.pdf.bh', ['quote' => $quote, 'nombreComercial' => $nombreComercial]);
                break;
            case 'PROMO ZALE':
                # code...
                $keyOdoo = '0e31683a8597606123ff4fcfab772ed7';
                $pdf = PDF::loadView('pages.pdf.promozale', ['quote' => $quote, 'nombreComercial' => $nombreComercial]);
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
        // Obtener el subtotal con escalas o simple
        $subtotal = 0;
        foreach ($quoteUpdate->quoteProducts as $productToSum) {
            if ($productToSum->quote_by_scales) {
                try {
                    $subtotal = $subtotal + floatval(json_decode($productToSum->scales_info)[0]->total_price);
                } catch (Exception $e) {
                    $subtotal = $subtotal + 0;
                }
            } else {
                $subtotal = $subtotal + $productToSum->precio_total;
            }
        }
        $taxFee = round($subtotal * ($quoteUpdate->quotesInformation->tax_fee / 100), 2);
        $subtotal = $subtotal + $taxFee;
        $discountValue = 0;
        if ($type == 'Fijo') {
            $discountValue = floatval($quoteDiscount->value);
        } else {
            $discountValue = floatval(round(($subtotal / 100) * $quoteDiscount->value, 2));
        }
        $estimated = floatval($subtotal - $discountValue);
        $responseOdoo = '';
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
                        "Estimated" => $estimated,
                        "Rating" => (int) $this->rank,
                        "UserID" => (int) $odoo_id_user,
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
            $responseOdoo = $response;
            if ($response !== false) {
                $dataResponse = json_decode($response);
                if ($dataResponse->message) {
                    if ($dataResponse->message == 'Internal Server Error') {
                        $message = 'Error en servidor de Odoo';
                        $errors = true;
                    }
                };
                if (!$errors && $dataResponse->success) {
                    $listElementsOpportunities = $dataResponse->listElementsOpportunities;
                    if ($listElementsOpportunities[0]->success) {
                        $codeLead = $listElementsOpportunities[0]->CodeLead;
                        $quote->lead = $codeLead;
                        $quote->pending_odoo = false;
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
            $errors = true;
        }
        $errorsMail = false;
        try {
            if ($errors) {
                $newPath = $path;
            }
            $data = explode('@', auth()->user()->email);
            $domain = $data[count($data) - 1];
            $mailer = '';
            switch ($domain) {
                case 'promolife.com.mx':
                    $mailer = 'smtp_pl';
                    break;
                case 'trademarket.com.mx':
                    $mailer = 'smtp_bh';
                    // $mailer = 'smtp_bh_lat';
                    break;
                case 'bhtrademarket.com':
                    $mailer = 'smtp_bh_usa';
                    // $mailer = 'smtp_bh_lat';
                    break;
                default:
                    $mailer = 'smtp';
                    break;
            }
            $mailSend = '';
            switch (auth()->user()->companySession->name) {
                case 'PROMO LIFE':
                    $nameFile = "QS-" . $quote->id . " " . $quote->latestQuotesUpdate->quotesInformation->oportunity . ' ' . $quote->updated_at->format('d/m/Y') . '.pdf';
                    $mailSend = new SendQuotePL(auth()->user()->name, $quote->latestQuotesUpdate->quotesInformation->name, $nameFile, $newPath);
                    Mail::mailer($mailer)->to($quote->latestQuotesUpdate->quotesInformation->email)->send($mailSend);
                    break;
                case 'BH TRADEMARKET':
                    $nameFile = "QS-" . $quote->id . " " . $quote->latestQuotesUpdate->quotesInformation->oportunity . ' ' . $quote->updated_at->format('d/m/Y') . '.pdf';
                    $mailSend = new SendQuoteBH(auth()->user()->name, $quote->latestQuotesUpdate->quotesInformation->name, $nameFile, $newPath);
                    Mail::mailer('smtp_bh')->to($quote->latestQuotesUpdate->quotesInformation->email)->send($mailSend);
                    break;
                case 'PROMO ZALE':
                    $nameFile = "QS-" . $quote->id . " " . $quote->latestQuotesUpdate->quotesInformation->oportunity . ' ' . $quote->updated_at->format('d/m/Y') . '.pdf';
                    $mailSend = new SendQuotePZ(auth()->user()->name, $quote->latestQuotesUpdate->quotesInformation->name, $nameFile, $newPath);
                    Mail::mailer('smtp_bh')->to($quote->latestQuotesUpdate->quotesInformation->email)->send($mailSend);
                    break;
                default:
                    dd(1);
                    break;
            }
            unlink(public_path() . $newPath);
            auth()->user()->currentQuote->currentQuoteDetails()->delete();
            auth()->user()->currentQuote()->delete();
        } catch (Exception $exception) {
            $messageMail = $exception->getMessage();
            $errorsMail = true;
            auth()->user()->currentQuote->currentQuoteDetails()->delete();
            auth()->user()->currentQuote()->delete();
            unlink(public_path() . $newPath);
        }
        if ($errors || $errorsMail) {
            try {
                $name = '/dataErrorToCreateQuote' . auth()->user()->name . time() . '.txt';
                Storage::put('/public' . $name,   json_encode(["messageMail" => $messageMail, "messageOdoo" => $message, 'responseOdoo' => $responseOdoo, 'user' => auth()->user()]));
                Mail::to('adminportales@promolife.com.mx')->send(new SendDataErrorCreateQuote('adminportales@promolife.com.mx', '/storage' . $name));
            } catch (Exception $th) {
                //throw $th;
            }
            return redirect()->action([CotizadorController::class, 'verCotizacion'], ['quote' => $quote->id])->with('messageMail', json_encode($message) . ' ' . json_encode($messageMail))
                ->with('messageError', 'Tu cotizacion se ha guardado exitosamente. ' .
                    ($errorsMail ? "No se pudo enviar el email debido a problemas tecnicos. " : "") .
                    ($errors ? "No se puedo guardar el lead debido a problemas en la conexion con Odoo, lo intentaremos nuevamente mas tarde" : ""));
        }
        return redirect()->action([CotizadorController::class, 'verCotizacion'], ['quote' => $quote->id])->with('message', 'Tu cotizacion se ha guardado exitosamente y ya fue enviada al correo electronico establecido.');
    }

    public function cargarDatosCliente()
    {
        $client = Client::find($this->clienteSeleccionado);
        if ($client && $this->tipoCliente == "buscar") {
            $this->nombre =  $client->contact;
            $this->empresa = $client->name;
            $this->email = $client->email;
            $this->telefono = $client->phone;
            return;
        }
        $this->nombre = '';
        $this->empresa = '';
        $this->email = '';
        $this->telefono = '';
        $this->clienteSeleccionado = "";
    }
    public function previewQuote()
    {
        $this->urlPDFPreview = null;
        $type = 'Fijo';
        $value = 0;
        $discount = false;
        if (auth()->user()->currentQuote->discount) {
            $discount = true;
            $type = auth()->user()->currentQuote->type;
            $value = auth()->user()->currentQuote->value;
        }
        $products = [];

        foreach (auth()->user()->currentQuote->currentQuoteDetails as $item) {
            $product = Product::find($item->product_id);
            $tecnica = PricesTechnique::find($item->prices_techniques_id);

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
            $product->image = $item->images_selected ?: ($product->firstImage == null ? '' : $product->firstImage->image_url);
            $product->provider;

            $dataProduct = [
                'product' => json_encode($product->toArray()),
                'technique' =>  json_encode($infoTecnica),
                'new_description' => $item->new_description,
                'color_logos' => $item->color_logos,
                'costo_indirecto' => $item->costo_indirecto,
                'utilidad' => $item->utilidad,
                'dias_entrega' => $item->dias_entrega,
                'type_days' => $item->type_days,
            ];
            if (!$item->quote_by_scales) {
                $price_tecnica = $item->new_price_technique != null ?
                    $item->new_price_technique
                    : ($tecnica->tipo_precio == 'D'
                        ? round($tecnica->precio / $item->cantidad, 2)
                        : $tecnica->precio);
                $dataProduct['prices_techniques'] = $price_tecnica;
                $dataProduct['cantidad'] = $item->cantidad;
                $dataProduct['precio_unitario'] = $item->precio_unitario;
                $dataProduct['precio_total'] = $item->precio_total;
                $dataProduct['quote_by_scales'] = false;
                $dataProduct['scales_info'] = null;
            } else {
                $dataProduct['prices_techniques'] = null;
                $dataProduct['cantidad'] = null;
                $dataProduct['precio_unitario'] = null;
                $dataProduct['precio_total'] = null;
                $dataProduct['quote_by_scales'] = true;
                $dataProduct['scales_info'] = $item->scales_info;
            }

            array_push($products, (object) $dataProduct);
        }
        $precioTotal = 0;
        $productosTotal = 0;
        foreach ($products as $p) {
            $precioTotal = $precioTotal + $p->precio_total;
            $productosTotal = $productosTotal + $p->cantidad;
        }
        $quote = (object) [
            "id" => "NA",
            "user_id" => auth()->user()->id,
            "logo" => $this->logo ? $this->logo->temporaryUrl() : null,
            "created_at" => now(),
            "iva_by_item" => boolval($this->ivaByItem),
            'type_days' => $this->typeDays,
            "show_total" => boolval($this->showTotal),
            "precio_total" => $precioTotal,
            "productos_total" => $productosTotal,
            "shelf_life" => $this->shelfLife,
            "preview" => true,
            "company" => auth()->user()->companySession,
            "currency" => $this->currency,
            "currency_type" => $this->currency_type,
            "show_tax" => $this->show_tax,
            'latestQuotesUpdate' => (object)[
                "quotesInformation" => (object)[
                    "company" => $this->empresa,
                    "name" => $this->nombre,
                    "department" => $this->departamento,
                    "information" => $this->informacion,
                    'shelf_life' => $this->shelfLife,
                    "tax_fee" => (int)$this->taxFee > 0 ? $this->taxFee : null
                ],
                "quoteProducts" => (object)$products,
                "quoteDiscount" => (object)[
                    "type" => $type,
                    "value" => $value,
                    "discount" => $discount,
                ]
            ],
        ];
        $nombreComercial = null;
        if ($this->tipoCliente == 'crear') {
        } else {
            $empresa = Client::where("name", $this->empresa)->first();
            if ($empresa) {
                $nombreComercial = $empresa->firstTradename;
            }
        }
        switch (auth()->user()->companySession->name) {
            case 'PROMO LIFE':
                # code...
                $pdf = PDF::loadView('pages.pdf.promolife', ['quote' => $quote, 'nombreComercial' => $nombreComercial]);
                break;
            case 'BH TRADEMARKET':
                # code...
                $pdf = PDF::loadView('pages.pdf.bh', ['quote' => $quote, 'nombreComercial' => $nombreComercial]);
                break;
            case 'PROMO ZALE':
                # code...
                $pdf = PDF::loadView('pages.pdf.promozale', ['quote' => $quote, 'nombreComercial' => $nombreComercial]);
                break;
            default:
                # code...
                break;
        }
        $pdf->setPaper('Letter', 'portrait');
        $pdf = $pdf->stream("Preview " . $this->oportunidad . ".pdf");
        $path =  "/storage/quotes/tmp/" . time() . "Preview " . $this->oportunidad . ".pdf";
        file_put_contents(public_path() . $path, $pdf);
        $this->urlPDFPreview = url('') . $path;
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
