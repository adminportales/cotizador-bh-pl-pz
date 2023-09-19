<?php

namespace App\Http\Livewire;

use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePresentationComponent extends Component
{
    use WithFileUploads;

    public $quote;

    public $portada, $logo, $encabezado, $pie_pagina;

    public $urlPDFPreview;

    public function render()
    {
        return view('livewire.create-presentation-component');
    }

    public function previewPresentation()
    {
        $imagePortadaName = "";
        if ($this->portada) {
            // Renombrar la imagen
            $imagePortadaName = time() . '.' . $this->portada->getClientOriginalName() . "." . $this->portada->getClientOriginalExtension();
            // Subir la imagen
            $this->portada->storeAs('public/ppt/' . $this->quote->id, $imagePortadaName);
        }

        $imageEncabezadoName = "";
        if ($this->encabezado) {
            // Renombrar la imagen
            $imageEncabezadoName = time() . '.' . $this->encabezado->getClientOriginalName() . "." . $this->encabezado->getClientOriginalExtension();
            // Subir la imagen
            $this->encabezado->storeAs('public/ppt/' . $this->quote->id, $imageEncabezadoName);
        }

        $imagePiePaginaName = "";
        if ($this->pie_pagina) {
            // Renombrar la imagen
            $imagePiePaginaName = time() . '.' . $this->pie_pagina->getClientOriginalName() . "." . $this->pie_pagina->getClientOriginalExtension();
            // Subir la imagen
            $this->pie_pagina->storeAs('public/ppt/' . $this->quote->id, $imagePiePaginaName);
        }

        $imageLogoName = "";
        if ($this->logo) {
            // Renombrar la imagen
            $imageLogoName = time() . '.' . $this->logo->getClientOriginalName() . '.' . $this->logo->getClientOriginalExtension();
            // Subir la imagen
            $this->logo->storeAs('public/ppt/' . $this->quote->id, $imageLogoName);
        }

        $dataInformation = [
            'portada' => $imagePortadaName,
            'logo' => $imageLogoName,
            'encabezado' => $imageEncabezadoName,
            'pie_pagina' => $imagePiePaginaName,
        ];
        // $dataInformation = [
        //     'portada' => 'https://images.adsttc.com/media/images/5c6d/be46/284d/d1af/7400/0c89/large_jpg/portada_landscape_.jpg?1550695990',
        //     'logo' => "https://store-images.s-microsoft.com/image/apps.10546.13571498826857201.6603a5e2-631f-4f29-9b08-f96589723808.dc893fe0-ecbc-4846-9ac6-b13886604095",
        //     'encabezado' => "https://images.indianexpress.com/2023/03/spotify-featured-express-photo1.jpg",
        //     'pie_pagina' => "https://wearecolorblind.com/wp-content/uploads/2018/11/spotify-controls-simulated-all.jpg",
        // ];

        switch ($this->quote->company->name) {
            case 'PROMO LIFE':
                # code...
                $pdf = PDF::loadView('pages.pdf.promolifeppt', ['data' => $dataInformation, 'quote' => $this->quote]);
                break;
            case 'BH TRADEMARKET':
                # code...
                $pdf = PDF::loadView('pages.pdf.bhppt', ['data' => $dataInformation, 'quote' => $this->quote]);
                break;
            case 'PROMO ZALE':
                # code...
                $pdf = PDF::loadView('pages.pdf.promozaleppt', ['data' => $dataInformation, 'quote' => $this->quote]);
                break;
            default:
                # code...
                break;
        }

        $pdf->setPaper('Letter', 'landscape');
        $pdf = $pdf->stream("Preview " . $this->quote->id . ".pdf");
        $path =  "/storage/quotes/tmp/" . time() . "Preview " . $this->quote->id  . ".pdf";
        file_put_contents(public_path() . $path, $pdf);
        $this->urlPDFPreview = url('') . $path;
    }
}
