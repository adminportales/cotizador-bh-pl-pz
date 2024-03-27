<?php

namespace App\Http\Livewire\Cotizador;

use App\Mail\SendQuote;
use App\Mail\SendQuoteBH;
use App\Mail\SendQuoteGeneric;
use App\Mail\SendQuotePL;
use App\Mail\SendQuotePZ;
use App\Models\Client;
use App\Notifications\SendQuoteByEmail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/*
* Clase VerCotizacionComponent
* Esta clase se encarga de manejar la lógica y presentación de la vista de ver cotización.
*/
class VerCotizacionComponent extends Component
{

    use AuthorizesRequests;

    public $quote, $puedeEditar = false;

    public $newQuoteInfo = [];

    protected $listeners = ['updateQuoteInfo' => 'updateQuoteInfo'];
    /**
     * Renderiza el componente de visualización de cotización.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        if (!auth()->user()->hasRole('admin')) {
            $this->authorize('view', $this->quote);
        }
        $empresa = Client::where("name", $this->quote->latestQuotesUpdate->quotesInformation->company)->first();
        $nombreComercial = null;
        if ($empresa) {
            $nombreComercial = $empresa->firstTradename;
        }
        return view('cotizador.ver_cotizacion.ver-cotizacion-component', ['nombreComercial' => $nombreComercial]);
    }

    /**
     * Envia la cotización por correo electrónico.
     *
     * @return void
     */
    public function enviar()
    {
        // Verifica si el usuario tiene permiso para actualizar la cotización
        $this->authorize('update', $this->quote);

        // Obtiene la empresa del cliente
        $empresa = Client::where("name", $this->quote->latestQuotesUpdate->quotesInformation->company)->first();
        $nombreComercial = null;
        if ($empresa) {
            $nombreComercial = $empresa->firstTradename;
        }

        $errors = false;
        $message = '';
        $pdf = '';

        // Genera el PDF según la compañía de la cotización
        switch ($this->quote->company->name) {
            case 'PROMO LIFE':
                $pdf = \PDF::loadView('pdf.promolife', ['quote' => $this->quote, 'nombreComercial' => $nombreComercial]);
                break;
            case 'BH TRADEMARKET':
                $pdf = \PDF::loadView('pdf.bh', ['quote' => $this->quote, 'nombreComercial' => $nombreComercial]);
                break;
            case 'PROMO ZALE':
                $pdf = \PDF::loadView('pdf.promozale', ['quote' => $this->quote, 'nombreComercial' => $nombreComercial]);
                break;
            default:
                break;
        }

        // Configura el tamaño y orientación del papel del PDF
        $pdf->setPaper('Letter', 'portrait');

        // Genera el PDF y lo guarda en el servidor
        $pdf = $pdf->stream($this->quote->lead . ".pdf");
        $path =  "/storage/quotes/" . time() . $this->quote->lead . ".pdf";
        file_put_contents(public_path() . $path, $pdf);

        try {
            // Obtiene el dominio del correo electrónico del usuario autenticado
            $data = explode('@', auth()->user()->email);
            $domain = $data[count($data) - 1];
            $mailer = '';

            // Configura el mailer según el dominio del correo electrónico
            switch ($domain) {
                case 'promolife.com.mx':
                    $mailer = 'smtp_pl';
                    break;
                case 'trademarket.com.mx':
                    $mailer = 'smtp_bh';
                    break;
                case 'bhtrademarket.com':
                    $mailer = 'smtp_bh_usa';
                    break;
                default:
                    $mailer = 'smtp';
                    break;
            }

            $mailSend = '';

            // Envía el correo electrónico con la cotización adjunta según la compañía de la cotización
            switch ($this->quote->company->name) {
                case 'PROMO LIFE':
                    $nameFile = "QS-" . $this->quote->id . " " . $this->quote->latestQuotesUpdate->quotesInformation->oportunity . ' ' . $this->quote->updated_at->format('d/m/Y') . '.pdf';
                    $mailSend = new SendQuotePL(auth()->user()->name, $this->quote->latestQuotesUpdate->quotesInformation->name, $nameFile, $path);
                    Mail::mailer($mailer)->to($this->quote->latestQuotesUpdate->quotesInformation->email)->send($mailSend);
                    break;
                case 'BH TRADEMARKET':
                    $nameFile = "QS-" . $this->quote->id . " " . $this->quote->latestQuotesUpdate->quotesInformation->oportunity . ' ' . $this->quote->updated_at->format('d/m/Y') . '.pdf';
                    $mailSend = new SendQuoteBH(auth()->user()->name, $this->quote->latestQuotesUpdate->quotesInformation->name, $nameFile, $path);
                    Mail::mailer('smtp_bh')->to($this->quote->latestQuotesUpdate->quotesInformation->email)->send($mailSend);
                    break;
                case 'PROMO ZALE':
                    $nameFile = "QS-" . $this->quote->id . " " . $this->quote->latestQuotesUpdate->quotesInformation->oportunity . ' ' . $this->quote->updated_at->format('d/m/Y') . '.pdf';
                    $mailSend = new SendQuotePZ(auth()->user()->name, $this->quote->latestQuotesUpdate->quotesInformation->name, $nameFile, $path);
                    Mail::mailer('smtp_bh')->to($this->quote->latestQuotesUpdate->quotesInformation->email)->send($mailSend);
                    break;
                default:
                    break;
            }

            // Elimina el archivo PDF guardado en el servidor
            unlink(public_path() . $path);
        } catch (Exception $exception) {
            $errors = true;
            $message = $exception->getMessage();
            $this->dispatchBrowserEvent('errorSendMail', ['message' => $message]);
        }

        if ($errors) {
            return;
        }

        // Dispara un evento de navegador para indicar que se ha enviado el correo electrónico
        $this->dispatchBrowserEvent('Enviar cliente y oddo');
    }
    /**
     * Método para editar la cotización.
     *
     * Este método verifica si el usuario tiene permisos para actualizar la cotización y cambia el estado de la variable $puedeEditar.
     * Además, emite un evento 'puedeEditar' con el valor actualizado de $puedeEditar.
     */
    public function editar()
    {
        $this->authorize('update', $this->quote);
        $this->puedeEditar = !$this->puedeEditar;
        $this->emit('puedeEditar', ['puedeEditar' => $this->puedeEditar]);
    }

    /**
     * Actualiza la información de la cotización.
     *
     * @param mixed $quoteInfo La información de la cotización actualizada.
     * @return void
     */
    public function updateQuoteInfo($quoteInfo)
    {
        $this->authorize('update', $this->quote);
        $this->newQuoteInfo = $quoteInfo;
        $this->dispatchBrowserEvent('Editarcliente');
    }

    /**
     * Envía la cotización a Odoo.
     *
     * Este método se encarga de enviar la cotización actual a Odoo, realizando las siguientes acciones:
     * - Verifica si el usuario tiene un ID de Odoo asignado.
     * - Genera el PDF de la cotización según la empresa.
     * - Guarda el PDF en el servidor.
     * - Calcula el subtotal de la cotización.
     * - Calcula el impuesto y lo suma al subtotal.
     * - Calcula el descuento y lo resta al subtotal.
     * - Calcula el monto estimado de la cotización.
     * - Prepara los datos de la cotización para enviar a Odoo.
     * - Realiza una solicitud HTTP a la API de Odoo para enviar la cotización.
     * - Maneja la respuesta de Odoo y muestra mensajes de error si corresponde.
     *
     * @return void
     */
    public function enviarOdoo()
    {
        $this->authorize('update', $this->quote);
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
        $empresa = Client::where("name", $this->quote->latestQuotesUpdate->quotesInformation->company)->first();
        $nombreComercial = null;
        if ($empresa) {
            $nombreComercial = $empresa->firstTradename;
        }
        $pdf = '';
        $keyOdoo = '';
        $errors = false;
        $message = '';
        switch ($this->quote->company->name) {
            case 'PROMO LIFE':
                $keyOdoo = 'cd78567e59e016e964cdcc1bd99367c6';
                $pdf = PDF::loadView('pdf.promolife', ['quote' => $this->quote, 'nombreComercial' => $nombreComercial]);
                break;
            case 'BH TRADEMARKET':
                $keyOdoo = 'e877f47a2a844ded99004e444c5a9797';
                $pdf = PDF::loadView('pdf.bh', ['quote' => $this->quote, 'nombreComercial' => $nombreComercial]);
                break;
            case 'PROMO ZALE':
                $keyOdoo = '0e31683a8597606123ff4fcfab772ed7';
                $pdf = PDF::loadView('pdf.promozale', ['quote' => $this->quote, 'nombreComercial' => $nombreComercial]);
                break;

            default:
                # code...
                break;
        }
        $pdf->setPaper('Letter', 'portrait');
        $pdf = $pdf->stream($this->quote->lead . ".pdf");
        $path = "/storage/quotes/" . time() . $this->quote->lead . ".pdf";
        file_put_contents(public_path() . $path, $pdf);

        $subtotal = 0;
        foreach ($this->quote->latestQuotesUpdate->quoteProducts as $productToSum) {
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
        $taxFee = round($subtotal * ($this->quote->latestQuotesUpdate->quotesInformation->tax_fee / 100), 2);
        $subtotal = $subtotal + $taxFee;
        $discountValue = 0;
        if ($this->quote->latestQuotesUpdate->quoteDiscount->type == 'Fijo') {
            $discountValue = floatval($this->quote->latestQuotesUpdate->quoteDiscount->value);
        } else {
            $discountValue = floatval(round(($subtotal / 100) * $this->quote->latestQuotesUpdate->quoteDiscount->value, 2));
        }
        $estimated = floatval($subtotal - $discountValue);

        try {
            $url = config('settings.api_odoo');
            $data =  [
                'Opportunities' => [
                    [
                        "CodeLead" => $this->quote->pending_odoo ? '' : $this->quote->lead,
                        'Name' => $this->quote->latestQuotesUpdate->quotesInformation->oportunity,
                        'Partner' => [
                            'Name' => $this->quote->latestQuotesUpdate->quotesInformation->company,
                            'Email' => $this->quote->latestQuotesUpdate->quotesInformation->email,
                            'Phone' => $this->quote->latestQuotesUpdate->quotesInformation->cell_phone,
                            'Contact' => $this->quote->latestQuotesUpdate->quotesInformation->name,
                        ],
                        "Estimated" => (floatval($estimated)),
                        "Rating" => (int) $this->quote->latestQuotesUpdate->quotesInformation->rank,
                        "UserID" => (int) $odoo_id_user,
                        "File" => [
                            'Name' => $this->quote->latestQuotesUpdate->quotesInformation->oportunity,
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
                        unlink(public_path() . $path);
                        $this->dispatchBrowserEvent('Enviar cliente y oddo');
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
            $errors = true;
            $message = "Error al enviar la cotizacion a odoo";
        }
        if ($errors) {
            dd($message);
        }
    }
}
