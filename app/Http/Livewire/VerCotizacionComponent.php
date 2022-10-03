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
        file_put_contents(public_path() . "/storage/quotes/" . $this->quote->lead . ".pdf", $pdf);
        Mail::to($this->quote->latestQuotesUpdate->quotesInformation->email)->send(new SendQuote(auth()->user()->name, $this->quote->latestQuotesUpdate->quotesInformation->name, '/storage/quotes/' . $this->quote->lead . ".pdf"));
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
        $pdf->setPaper('Letter', 'portrait');
        $pdf = $pdf->stream($this->quote->lead . ".pdf");
        file_put_contents(public_path() . "/storage/quotes/" . time() . $this->quote->lead . ".pdf", $pdf);
        try {
            $url = 'https://api-promolife.vde-suite.com:5030/custom/Promolife/V2/crm-lead/create';
            $data =  [
                'Opportunities' => [
                    [
                        "CodeLead"=>"",
                        'Name' => $this->quote->latestQuotesUpdate->quotesInformation->oportunity,
                        'Partner' => [
                            'Name' => $this->quote->latestQuotesUpdate->quotesInformation->company,
                            'Email' => $this->quote->latestQuotesUpdate->quotesInformation->email,
                            'Phone' => $this->quote->latestQuotesUpdate->quotesInformation->cell_phone,
                            'Contact' => $this->quote->latestQuotesUpdate->quotesInformation->name,
                        ],
                        "Estimated" => (floatval($this->quote->latestQuotesUpdate->quoteProducts()->sum('precio_total'))),
                        "Rating" => 1,
                        "UserID" => 12,
                        "File" => [
                            'Name' => 'Lead',
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
                'X-VDE-APIKEY: cd78567e59e016e964cdcc1bd99367c6',
                'X-VDE-TYPE: Ambos',
            ]);
            $response = curl_exec($curl);
        } catch (Exception $exception) {
            dd(1, $exception->getMessage());
        }
        return;
    }
}
