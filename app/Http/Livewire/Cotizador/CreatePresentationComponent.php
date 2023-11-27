<?php

namespace App\Http\Livewire\Cotizador;

use App\Models\Client;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Livewire\Component;
use Livewire\WithFileUploads;
use iio\libmergepdf\Merger;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    public $urlPDFPreview;


    public $dataInformation;

    public function render()
    {
        return view('cotizador.ver_cotizacion.presentation.create-presentation-component');
    }

    public function previewPresentation()
    {
        $imagePortadaName = "";
        if ($this->portada) {
            // Renombrar la imagen
            $imagePortadaName = time() . '_' . $this->portada->getClientOriginalName();
            // Subir la imagen
            $this->portada->storeAs('public/ppt/tmp/' . $this->quote->id, $imagePortadaName);
        }

        $imageLogoName = "";
        if ($this->logo) {
            // Renombrar la imagen
            $imageLogoName = time() . '_' . $this->logo->getClientOriginalName();
            // Subir la imagen
            $this->logo->storeAs('public/ppt/tmp/' . $this->quote->id, $imageLogoName);
        }

        $contraportada = "";
        if ($this->contraportada) {
            // Renombrar la imagen
            $contraportada = time() . '_' . $this->contraportada->getClientOriginalName();
            // Subir la imagen
            $this->contraportada->storeAs('public/ppt/tmp/' . $this->quote->id, $contraportada);
        }

        $imageFondoName = "";
        if ($this->fondo) {
            // Renombrar la imagen
            $imageFondoName = time() . '_' . $this->fondo->getClientOriginalName();
            // Subir la imagen
            $this->fondo->storeAs('public/ppt/tmp/' . $this->quote->id, $imageFondoName);
        }


        $this->dataInformation = [
            'portada' => $imagePortadaName ? asset('storage/ppt/tmp/' . $this->quote->id) . '/' . $imagePortadaName : '',
            'logo' => $imageLogoName ? asset('storage/ppt/tmp/' . $this->quote->id) . '/' . $imageLogoName : '',
            'contraportada' => $contraportada != '' ?  asset('storage/ppt/tmp/' . $this->quote->id) . '/' . $contraportada : '',
            'fondo' => $imageFondoName != '' ?  asset('storage/ppt/tmp/' . $this->quote->id) . '/' . $imageFondoName : '',

            'color_primario' => $this->color_primario,
            'color_secundario' => $this->color_secundario,
            'productos_por_pagina' => $this->productos_por_pagina,
            'mostrar_formato_de_tabla' => $this->mostrar_formato_de_tabla,
            'generar_contraportada' => $this->tieneContraportada,
        ];

        $empresa = Client::where("name", $this->quote->latestQuotesUpdate->quotesInformation->company)->first();
        $nombreComercial = null;
        if ($empresa) {
            $nombreComercial = $empresa->firstTradename;
        }

        $dataToPPT = [
            'data' => $this->dataInformation,
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

        $pdfCuerpo->setPaper(array(0, 0, 872, 490));
        $pdfCuerpo = $pdfCuerpo->stream("Preview " . $this->quote->id . ".pdf");
        $pathCuerpo =  "/storage/quotes/tmp/" . time() . "Preview " . $this->quote->id  . ".pdf";
        file_put_contents(public_path() . $pathCuerpo, $pdfCuerpo);

        $pdfPortada->setPaper(array(0, 0, 872, 490));
        $pdfPortada = $pdfPortada->stream("Preview " . $this->quote->id . "2.pdf");
        $pathPortada =  "/storage/quotes/tmp/" . time() . "Preview " . $this->quote->id  . "2.pdf";
        file_put_contents(public_path() . $pathPortada, $pdfPortada);

        if ($this->tieneContraportada) {
            $pdfContraportada->setPaper(array(0, 0, 872, 490));
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

    public function savePPT()
    {
        if ($this->dataInformation === null) {
            return 2;
        }
        /* $dataUrl = [
            'portada' => Str::replaceFirst(url(''), '',  $this->dataInformation['portada']),
            'logo' => Str::replaceFirst(url(''), '', $this->dataInformation['logo']),
            'contraportada' => Str::replaceFirst(url(''), '', $this->dataInformation['contraportada']),
            'fondo' => Str::replaceFirst(url(''), '', $this->dataInformation['fondo']),
        ]; */

        $dataUrl = [
            'portada' =>  $this->dataInformation['portada'],
            'logo' => $this->dataInformation['logo'],
            'contraportada' => $this->dataInformation['contraportada'],
            'fondo' => $this->dataInformation['fondo'],
        ];

        foreach ($dataUrl as $value) {
            if ($value != '') {
                Storage::put(
                    'public/ppt/' . Str::slug($this->quote->company->name, '_') . '/' . $this->quote->id . '/' . explode('/', $value)[count(explode('/', $value)) - 1],
                    Storage::get(Str::replaceFirst(url('storage/'), 'public/', $value))
                );
                /*  Storage::move(
                    Storage::get(Str::replaceFirst('storage/', 'public/', $value)),
                    Str::replaceFirst('tmp/', Str::slug($this->quote->company->name, '_') . '/', $value)
                ); */
            }
        }

        // Obtener urls  de los archivos
        $this->quote->presentations()->create([
            'front_page' => $this->dataInformation['portada'],
            'back_page' => $this->dataInformation['contraportada'],
            'have_back_page' => $this->dataInformation['generar_contraportada'] ? 1 : 0,
            'logo' => $this->dataInformation['logo'],
            'background' => $this->dataInformation['fondo'],
        ]);

        return 1;
    }
}
