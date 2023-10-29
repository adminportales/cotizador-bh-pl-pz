<?php

namespace App\Http\Livewire;

use App\Models\Client;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithFileUploads;
use iio\libmergepdf\Merger;

class CreatePresentationComponent extends Component
{
    use WithFileUploads;

    public $quote;

    public $portada;
    public $contraportada, $tieneContraportada;
    public $fondo;
    public $logo;

    public $color_primario;
    public $color_secundario;
    public $productos_por_pagina = 1;
    public $mostrar_formato_de_tabla;
    public $generar_contraportada;

    public $urlPDFPreview;

    public function render()
    {
        return view('cotizador.ver_cotizacion.create-presentation-component');
    }

    public function previewPresentation()
    {
        $imagePortadaName = "";
        if ($this->portada) {
            // Renombrar la imagen
            $imagePortadaName = time() . '_' . $this->portada->getClientOriginalName();
            // Subir la imagen
            $this->portada->storeAs('public/ppt/' . $this->quote->id, $imagePortadaName);
        }

        $imageLogoName = "";
        if ($this->logo) {
            // Renombrar la imagen
            $imageLogoName = time() . '_' . $this->logo->getClientOriginalName();
            // Subir la imagen
            $this->logo->storeAs('public/ppt/' . $this->quote->id, $imageLogoName);
        }

        $contraportada = "";
        if ($this->contraportada) {
            // Renombrar la imagen
            $contraportada = time() . '_' . $this->contraportada->getClientOriginalName();
            // Subir la imagen
            $this->contraportada->storeAs('public/ppt/' . $this->quote->id, $contraportada);
        }

        $imageFondoName = "";
        if ($this->fondo) {
            // Renombrar la imagen
            $imageFondoName = time() . '_' . $this->fondo->getClientOriginalName();
            // Subir la imagen
            $this->fondo->storeAs('public/ppt/' . $this->quote->id, $imageFondoName);
        }


        $dataInformation = [
            'portada' => $imagePortadaName ? asset('storage/ppt/' . $this->quote->id) . '/' . $imagePortadaName : '',
            'logo' => $imageLogoName ? asset('storage/ppt/' . $this->quote->id) . '/' . $imageLogoName : '',
            'contraportada' => $contraportada != '' ?  asset('storage/ppt/' . $this->quote->id) . '/' . $contraportada : '',
            'fondo' => $imageFondoName != '' ?  asset('storage/ppt/' . $this->quote->id) . '/' . $imageFondoName : '',

            'color_primario' => $this->color_primario,
            'color_secundario' => $this->color_secundario,
            'productos_por_pagina' => $this->productos_por_pagina,
            'mostrar_formato_de_tabla' => $this->mostrar_formato_de_tabla,
            'generar_contraportada' => $this->generar_contraportada,
        ];

        // $dataInformation = [
        //     // 'portada' => 'https://png.pngtree.com/png-slide/20220812/ourmid/0-pngtree-ancient-brown-simple-and-elegant-pattern-ppt-cover-google-slides-and-powerpoint-template-background_8735.jpg',
        //     'portada' => '',
        //     // 'logo' => "https://store-images.s-microsoft.com/image/apps.10546.13571498826857201.6603a5e2-631f-4f29-9b08-f96589723808.dc893fe0-ecbc-4846-9ac6-b13886604095",
        //     'logo' => '',
        //     // 'contraportada' => "https://img.freepik.com/vector-premium/fondo-material-moderno_643365-269.jpg",
        //     'contraportada' => "",
        //     // 'fondo' => 'https://img.freepik.com/vector-premium/fondo-material-moderno_643365-269.jpg',
        //     'fondo' => '',
        //     'color_primario' => $this->color_primario,
        //     'color_secundario' => $this->color_secundario,
        //     'productos_por_pagina' => $this->productos_por_pagina,
        //     'mostrar_formato_de_tabla' => $this->mostrar_formato_de_tabla,
        //     'generar_contraportada' => $this->generar_contraportada,
        // ];

        $empresa = Client::where("name", $this->quote->latestQuotesUpdate->quotesInformation->company)->first();
        $nombreComercial = null;
        if ($empresa) {
            $nombreComercial = $empresa->firstTradename;
        }

        $dataToPPT = [
            'data' => $dataInformation,
            'quote' => $this->quote,
            'nombreComercial' => $nombreComercial
        ];

        $pdfCuerpo = '';
        $pdfContraportada = '';
        switch ($this->quote->company->name) {
            case 'PROMO LIFE':
                $pdfCuerpo = PDF::loadView('pdf.pptpl.body', $dataToPPT);
                $pdfPortada = PDF::loadView('pdf.pptpl.firstpage', $dataToPPT);
                if ($this->tieneContraportada) {
                    $pdfContraportada = PDF::loadView('pdf.pptpl.lastpage', $dataToPPT);
                }
                break;
            case 'BH TRADEMARKET':
                $pdfCuerpo = PDF::loadView('pdf.pptbh.body', $dataToPPT);
                $pdfPortada = PDF::loadView('pdf.pptbh.firstpage', $dataToPPT);
                if ($this->tieneContraportada) {
                    $pdfContraportada = PDF::loadView('pdf.pptbh.lastpage', $dataToPPT);
                }
                break;
            case 'PROMO ZALE':
                $pdfCuerpo = PDF::loadView('pdf.pptpz.body', $dataToPPT);
                $pdfPortada = PDF::loadView('pdf.pptpz.firstpage', $dataToPPT);
                if ($this->tieneContraportada) {
                    $pdfContraportada = PDF::loadView('pdf.pptpz.lastpage', $dataToPPT);
                }
                break;
            default:
                break;
        }

        $pdfCuerpo->setPaper('Letter', 'landscape');
        $pdfCuerpo = $pdfCuerpo->stream("Preview " . $this->quote->id . ".pdf");
        $pathCuerpo =  "/storage/quotes/tmp/" . time() . "Preview " . $this->quote->id  . ".pdf";
        file_put_contents(public_path() . $pathCuerpo, $pdfCuerpo);

        $pdfPortada->setPaper('Letter', 'landscape');
        $pdfPortada = $pdfPortada->stream("Preview " . $this->quote->id . "2.pdf");
        $pathPortada =  "/storage/quotes/tmp/" . time() . "Preview " . $this->quote->id  . "2.pdf";
        file_put_contents(public_path() . $pathPortada, $pdfPortada);

        if ($this->tieneContraportada) {
            $pdfContraportada->setPaper('Letter', 'landscape');
            $pdfContraportada = $pdfContraportada->stream("Preview " . $this->quote->id . "2.pdf");
            $pathContraportada =  "/storage/quotes/tmp/" . time() . "Preview " . $this->quote->id  . "3.pdf";
            file_put_contents(public_path() . $pathContraportada, $pdfContraportada);
        }
        $dataMerge = [
            public_path() . $pathPortada,
            public_path() . $pathCuerpo

        ];
        if ($this->tieneContraportada) {
            array_push($dataMerge, public_path() . $pathContraportada);
        }
        $merger = new Merger();
        $merger->addIterator($dataMerge);
        $createdPdf = $merger->merge();
        $pathPdf = "/storage/quotes/tmp/" . time() . "Preview " . $this->quote->id  . "3.pdf";
        file_put_contents(public_path() . $pathPdf, $createdPdf);

        $this->urlPDFPreview = url('') . $pathPdf;
    }
}
