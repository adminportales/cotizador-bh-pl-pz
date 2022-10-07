<?php

namespace App\Http\Livewire;

use App\Mail\SendQuote;
use App\Notifications\SendQuoteByEmail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Exception;

class VerCotizacionComponent extends Component
{
    public $quote, $puedeEditar = false;

    public $newQuoteInfo = [];

    protected $listeners = ['updateQuoteInfo' => 'updateQuoteInfo'];
    public function render()
    {
        return view('livewire.ver-cotizacion-component');
    }

    public function enviar()
    {
        $errors = false;
        $message = '';
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
        // $pdf = PDF::loadView('pages.pdf.promolife', ['quote' => $this->quote]);
        $pdf->setPaper('Letter', 'portrait');
        $pdf = $pdf->stream($this->quote->lead . ".pdf");
        $path =  "/storage/quotes/" . time() . $this->quote->lead . ".pdf";
        file_put_contents(public_path() . $path, $pdf);
        try {
            Mail::to($this->quote->latestQuotesUpdate->quotesInformation->email)->send(new SendQuote(auth()->user()->name, $this->quote->latestQuotesUpdate->quotesInformation->name, $path));
            unlink(public_path() . $path);
        } catch (Exception $exception) {
            $errors = true;
            $message = $exception->getMessage();
        }
        if ($errors) {
            dd($message);
        }
        // Mail::to('antoniotd87@gmail.com')->send(new SendQuote(auth()->user()->name, $this->quote->latestQuotesUpdate->quotesInformation->name, '/storage/quotes/' . $this->quote->lead . ".pdf"));
        dd('Enviado');
    }
    public function editar()
    {
        $this->puedeEditar = !$this->puedeEditar;
        $this->emit('puedeEditar', ['puedeEditar' => $this->puedeEditar]);
    }

    public function updateQuoteInfo($quoteInfo)
    {
        $this->newQuoteInfo = $quoteInfo;
    }
    public function enviarOdoo()
    {
        $odoo_id_user = null;
        if (auth()->user()->info) {
            $odoo_id_user = auth()->user()->info->odoo_id;
        }
        if ($odoo_id_user == null) {
            dd("No tienes un id de Odoo Asignado");
            return;
        }
        if ((int)$odoo_id_user <= 0) {
            dd("El id de odoo no es valido");
            return;
        }

        $pdf = '';
        $keyOdoo = '';
        $errors = false;
        $message = '';
        switch (auth()->user()->company->name) {
            case 'PROMO LIFE':
                $keyOdoo = 'cd78567e59e016e964cdcc1bd99367c6';
                $pdf = PDF::loadView('pages.pdf.promolife', ['quote' => $this->quote]);
                break;
            case 'BH TRADEMARKET':
                $keyOdoo = 'e877f47a2a844ded99004e444c5a9797';
                $pdf = PDF::loadView('pages.pdf.bh', ['quote' => $this->quote]);
                break;
            case 'PROMO ZALE':
                $keyOdoo = '0e31683a8597606123ff4fcfab772ed7';
                $pdf = PDF::loadView('pages.pdf.promozale', ['quote' => $this->quote]);
                break;

            default:
                # code...
                break;
        }
        $pdf->setPaper('Letter', 'portrait');
        $pdf = $pdf->stream($this->quote->lead . ".pdf");
        $path = "/storage/quotes/" . time() . $this->quote->lead . ".pdf";
        file_put_contents(public_path() . $path, $pdf);
        try {
            $url = 'https://api-promolife.vde-suite.com:5030/custom/Promolife/V2/crm-lead/create';
            $data =  [
                'Opportunities' => [
                    [
                        "CodeLead" => $this->quote->lead,
                        'Name' => $this->quote->latestQuotesUpdate->quotesInformation->oportunity,
                        'Partner' => [
                            'Name' => $this->quote->latestQuotesUpdate->quotesInformation->company,
                            'Email' => $this->quote->latestQuotesUpdate->quotesInformation->email,
                            'Phone' => $this->quote->latestQuotesUpdate->quotesInformation->cell_phone,
                            'Contact' => $this->quote->latestQuotesUpdate->quotesInformation->name,
                        ],
                        "Estimated" => (floatval($this->quote->latestQuotesUpdate->quoteProducts()->sum('precio_total'))),
                        "Rating" => (int) $this->quote->latestQuotesUpdate->quotesInformation->rank,
                        "UserID" => (int) auth()->user()->info->odoo_id,
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
                        dd('ENVIADo');
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
