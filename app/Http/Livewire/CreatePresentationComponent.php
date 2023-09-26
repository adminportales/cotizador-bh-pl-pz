<?php

namespace App\Http\Livewire;

use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithFileUploads;
use iio\libmergepdf\Merger;
use iio\libmergepdf\Pages;


class CreatePresentationComponent extends Component
{
    use WithFileUploads;

    public $quote;

    public $portada;
    public $contraportada;
    public $fondo;
    public $logo;
    public $encabezado;
    public $pie_pagina;

    public $color_primario;
    public $color_secundario;
    public $productos_por_pagina = 1;
    public $mostrar_formato_de_tabla;
    public $generar_contraportada;

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
            $imagePortadaName = time() . '_' . $this->portada->getClientOriginalName();
            // Subir la imagen
            $this->portada->storeAs('public/ppt/' . $this->quote->id, $imagePortadaName);
        }

        $imageEncabezadoName = "";
        if ($this->encabezado) {
            // Renombrar la imagen
            $imageEncabezadoName = time() . '_' . $this->encabezado->getClientOriginalName();
            // Subir la imagen
            $this->encabezado->storeAs('public/ppt/' . $this->quote->id, $imageEncabezadoName);
        }

        $imagePiePaginaName = "";
        if ($this->pie_pagina) {
            // Renombrar la imagen
            $imagePiePaginaName = time() . '_' . $this->pie_pagina->getClientOriginalName();
            // Subir la imagen
            $this->pie_pagina->storeAs('public/ppt/' . $this->quote->id, $imagePiePaginaName);
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

        /* $fondo = "";
        if ($this->fondo) {
            // Renombrar la imagen
            $fondo = time() . '_' . $this->fondo->getClientOriginalName();
            // Subir la imagen
            $this->fondo->storeAs('public/ppt/' . $this->quote->id, $fondo);
        } */


        $dataInformation = [
            'portada' => $imagePortadaName ? asset('storage/ppt/' . $this->quote->id) . '/' . $imagePortadaName : '',
            'logo' => $imageLogoName ? asset('storage/ppt/' . $this->quote->id) . '/' . $imageLogoName : '',
            'encabezado' => $imageEncabezadoName != '' ? asset('storage/ppt/' . $this->quote->id) . '/' . $imageEncabezadoName : '',
            'pie_pagina' => $imagePiePaginaName != '' ?  asset('storage/ppt/' . $this->quote->id) . '/' . $imagePiePaginaName : '',
            'contraportada' => $contraportada != '' ?  asset('storage/ppt/' . $this->quote->id) . '/' . $contraportada : '',
            // 'fondo' => $fondo != '' ?  asset('storage/ppt/' . $this->quote->id) . '/' . $fondo : '',

            'color_primario' => $this->color_primario,
            'color_secundario' => $this->color_secundario,
            'productos_por_pagina' => $this->productos_por_pagina,
            'mostrar_formato_de_tabla' => $this->mostrar_formato_de_tabla,
            'generar_contraportada' => $this->generar_contraportada,
        ];
        // $contraportada = "https://img.freepik.com/vector-premium/fondo-material-moderno_643365-269.jpg";
        // $dataInformation = [
        //     'portada' => 'https://images.adsttc.com/media/images/5c6d/be46/284d/d1af/7400/0c89/large_jpg/portada_landscape_.jpg?1550695990',
        //     'logo' => "https://store-images.s-microsoft.com/image/apps.10546.13571498826857201.6603a5e2-631f-4f29-9b08-f96589723808.dc893fe0-ecbc-4846-9ac6-b13886604095",
        //     'encabezado' => "https://images.indianexpress.com/2023/03/spotify-featured-express-photo1.jpg",
        //     'pie_pagina' => "https://wearecolorblind.com/wp-content/uploads/2018/11/spotify-controls-simulated-all.jpg",
        //     'contraportada' => "https://img.freepik.com/vector-premium/fondo-material-moderno_643365-269.jpg",
        //     // 'fondo' => 'url(https://img.freepik.com/vector-premium/fondo-material-moderno_643365-269.jpg)',

        //     'color_primario' => $this->color_primario,
        //     'color_secundario' => $this->color_secundario,
        //     'productos_por_pagina' => $this->productos_por_pagina,
        //     'mostrar_formato_de_tabla' => $this->mostrar_formato_de_tabla,
        //     'generar_contraportada' => $this->generar_contraportada,
        // ];

        switch ($this->quote->company->name) {
            case 'PROMO LIFE':
                # code...
                $pdf = PDF::loadView('pages.pdf.promolifeppt', ['data' => $dataInformation, 'quote' => $this->quote]);
                break;
            case 'BH TRADEMARKET':
                # code...
                $pdf = PDF::loadView('pages.pdf.bhppt', ['data' => $dataInformation, 'quote' => $this->quote]);
                // $pdf = PDF::loadView('pages.pdf.promolifeppt', ['data' => $dataInformation, 'quote' => $this->quote]);
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


        $pdf2 = PDF::loadView('pages.pdf.lastpage', ['data' => $dataInformation, 'quote' => $this->quote]);
        $pdf2->setPaper('Letter', 'landscape');
        $pdf2 = $pdf2->stream("Preview " . $this->quote->id . "2.pdf");
        $path1 =  "/storage/quotes/tmp/" . time() . "Preview " . $this->quote->id  . "2.pdf";
        file_put_contents(public_path() . $path1, $pdf2);

        $dataMerge = [
            public_path() . $path
        ];
        if ($contraportada != '') {
            array_push($dataMerge, public_path() . $path1);
        }
        $merger = new Merger();
        $merger->addIterator($dataMerge);
        $createdPdf = $merger->merge();
        $pathPdf = "/storage/quotes/tmp/" . time() . "Preview " . $this->quote->id  . "3.pdf";
        file_put_contents(public_path() . $pathPdf, $createdPdf);

        $this->urlPDFPreview = url('') . $pathPdf;
    }
}
