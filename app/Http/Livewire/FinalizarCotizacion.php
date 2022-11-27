<?php

namespace App\Http\Livewire;

use App\Models\Catalogo\Product;
use App\Models\PricesTechnique;
use Livewire\Component;
use App\Http\Controllers\CotizadorController;
use App\Mail\SendDataErrorCreateQuote;
use App\Mail\SendDataOdoo;
use App\Mail\SendQuote;
use App\Mail\SendQuoteBH;
use App\Mail\SendQuoteGeneric;
use App\Mail\SendQuotePL;
use App\Mail\SendQuotePZ;
use App\Models\Client;
use App\Models\QuoteDiscount;
use App\Models\QuoteInformation;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class FinalizarCotizacion extends Component
{
    use WithFileUploads;
    public $tipoCliente, $clienteSeleccionado = '', $isClient, $nombre, $empresa, $email, $telefono, $celular, $oportunidad, $rank = '', $departamento, $informacion, $ivaByItem, $logo;

    public function mount()
    {
        $this->ivaByItem = false;
    }

    public function render()
    {
        $userClients = [];
        foreach (auth()->user()->info as $info) {
            if ($info->company_id == auth()->user()->company_session) {
                $userClients = $info->clients()->where('company_id', $info->company_id)->get();
            }
        }
        return view('pages.catalogo.finalizar-cotizacion', compact('userClients'));
    }

    public function limpiarLogo()
    {
        $this->logo = null;
    }

    public function guardarCotizacion()
    {
        if (count(auth()->user()->info) < 1) {
            $this->dispatchBrowserEvent('isntCompany');
            return;
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
            $price_tecnica = $item->new_price_technique != null ?
                $item->new_price_technique
                : ($tecnica->tipo_precio == 'D'
                    ? round($tecnica->precio / $item->cantidad, 2)
                    : $tecnica->precio);

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
            $product->provider;
            $quoteUpdate->quoteProducts()->create([
                'product' => json_encode($product->toArray()),
                'technique' =>  json_encode($infoTecnica),
                'prices_techniques' => $price_tecnica,
                'new_description' => $item->new_description,
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

/*         $pdf = '';
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
        $subtotal = floatval($quoteUpdate->quoteProducts()->sum('precio_total'));
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
                        "UserID" => (int) auth()->user()->info->odoo_id,
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
                    $mailSend = new SendQuotePL(auth()->user()->name, $quote->latestQuotesUpdate->quotesInformation->name, $newPath);
                    Mail::mailer($mailer)->to($quote->latestQuotesUpdate->quotesInformation->email)->send($mailSend);
                    break;
                case 'BH TRADEMARKET':
                    $datosEmail = [
                        "clienteEmail" => $quote->latestQuotesUpdate->quotesInformation->email,
                        "nameSeller" => auth()->user()->name,
                        "client" => $quote->latestQuotesUpdate->quotesInformation->name,
                        // "fileUrl" => "https://scielo.conicyt.cl/pdf/ijmorphol/v31n4/art56.pdf",
                        "fileUrl" => str_replace(' ', '%20', url("/") . $newPath),
                        "emailSeller" => auth()->user()->email
                    ];
                    $curl = curl_init("https://api-promoconnect-sendmails.tonytd.xyz/sendMailBH");
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS,  json_encode($datosEmail));
                    curl_setopt($curl, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json',
                        'token: FJRIOFJIEOFNC',
                    ]);
                    $response = curl_exec($curl);
                    if (json_decode($response)->msg == 1) {
                        $this->dispatchBrowserEvent('errorSendMail', ['message' => json_decode($response)->msg]);
                    } else {
                        $errorsMail = true;
                        $messageMail = json_decode($response)->msg;
                    }
                    // $mailSend = new SendQuoteGeneric(auth()->user()->name, $this->quote->latestQuotesUpdate->quotesInformation->name, $path, auth()->user()->email);
                    // Mail::mailer($mailer)->to($quote->latestQuotesUpdate->quotesInformation->email)->send($mailSend);
                    break;
                case 'PROMO ZALE':
                    $datosEmail = [
                        "clienteEmail" => $quote->latestQuotesUpdate->quotesInformation->email,
                        "nameSeller" => auth()->user()->name,
                        "client" => $quote->latestQuotesUpdate->quotesInformation->name,
                        // "fileUrl" => "https://scielo.conicyt.cl/pdf/ijmorphol/v31n4/art56.pdf",
                        "fileUrl" => str_replace(' ', '%20', url("/") . $newPath),
                        "emailSeller" => auth()->user()->email
                    ];
                    $curl = curl_init("https://api-promoconnect-sendmails.tonytd.xyz/sendMailPZ");
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS,  json_encode($datosEmail));
                    curl_setopt($curl, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json',
                        'token: FJRIOFJIEOFNC',
                    ]);
                    $response = curl_exec($curl);
                    if (json_decode($response)->msg == 1) {
                        $this->dispatchBrowserEvent('errorSendMail', ['message' => json_decode($response)->msg]);
                    } else {
                        $errorsMail = true;
                        $message = json_decode($response)->msg;
                    }
                    // $mailSend = new SendQuoteGeneric(auth()->user()->name, $this->quote->latestQuotesUpdate->quotesInformation->name, $path, auth()->user()->email);
                    // Mail::mailer($mailer)->to($quote->latestQuotesUpdate->quotesInformation->email)->send($mailSend);
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
            Storage::put('/public/dataErrorToCreateQuote.txt',   json_encode(["messageMail" => $messageMail, "messageOdoo" => $message, 'responseOdoo' => $responseOdoo, 'user' => auth()->user()]));
            Mail::to('adminportales@promolife.com.mx')->send(new SendDataErrorCreateQuote('adminportales@promolife.com.mx', '/storage/dataErrorToCreateQuote.txt'));
            return redirect()->action([CotizadorController::class, 'verCotizacion'], ['quote' => $quote->id])->with('messageMail', json_encode($message) . ' ' . json_encode($messageMail))
                ->with('messageError', 'Tu cotizacion se ha guardado exitosamente. ' .
                    ($errorsMail ? "No se pudo enviar el email debido a problemas tecnicos. " : "") .
                    ($errors ? "No se puedo guardar el lead debido a problemas en la conexion con Odoo, lo intentaremos nuevamente mas tarde" : ""));
        } */
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
