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
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;

/**
 * Clase CreatePresentationComponent.
 *
 * Componente de Livewire para crear una presentación.
 */
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
    public $urlPWPreview;


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
            'portada' =>  [
                'temp' => $this->dataInformation['portada'],
                'final' => ''
            ],
            'logo' => [
                'temp' => $this->dataInformation['logo'],
                'final' => ''
            ],
            'contraportada' => [
                'temp' => $this->dataInformation['contraportada'],
                'final' => ''

            ],
            'fondo' => [
                'temp' => $this->dataInformation['fondo'],
                'final' => ''
            ],
        ];

        foreach ($dataUrl as $key => $value) {
            if ($value['temp'] != '') {
                $urlPath = 'ppt/' . Str::slug($this->quote->company->name, '_') . '/' . $this->quote->id . '/' . explode('/', $value['temp'])[count(explode('/', $value['temp'])) - 1];
                Storage::put(
                    'public/' . $urlPath,
                    Storage::get(Str::replaceFirst(url('storage/'), 'public/', $value['temp']))
                );
                $dataUrl[$key]['final'] = url('storage/' . $urlPath);
                /*  Storage::move(
                    Storage::get(Str::replaceFirst('storage/', 'public/', $value)),
                    Str::replaceFirst('tmp/', Str::slug($this->quote->company->name, '_') . '/', $value)
                ); */
            }
        }

        // Obtener urls  de los archivos
        $this->quote->presentations()->create([
            'front_page' => $dataUrl['portada']['final'],
            'back_page' =>  $dataUrl['contraportada']['final'],
            'have_back_page' => $this->dataInformation['generar_contraportada'] ? 1 : 0,
            'logo' => $dataUrl['logo']['final'],
            'background' => $dataUrl['fondo']['final'],
        ]);

        return 1;
    }

    public function previewPresentationPW()
    {
        // Procesar la imagen de portada
        $imagePortadaName = "";
        if ($this->portada) {
            $imagePortadaName = time() . '_' . $this->portada->getClientOriginalName();
            $this->portada->storeAs('public/ppt/tmp/' . $this->quote->id, $imagePortadaName);
        }

        // Procesar la imagen del logo
        $imageLogoName = "";
        if ($this->logo) {
            $imageLogoName = time() . '_' . $this->logo->getClientOriginalName();
            $this->logo->storeAs('public/ppt/tmp/' . $this->quote->id, $imageLogoName);
        }

        // Procesar la imagen de la contraportada
        $contraportada = "";
        if ($this->contraportada) {
            $contraportada = time() . '_' . $this->contraportada->getClientOriginalName();
            $this->contraportada->storeAs('public/ppt/tmp/' . $this->quote->id, $contraportada);
        }

        // Procesar la imagen del fondo
        $imageFondoName = "";
        if ($this->fondo) {
            $imageFondoName = time() . '_' . $this->fondo->getClientOriginalName();
            $this->fondo->storeAs('public/ppt/tmp/' . $this->quote->id, $imageFondoName);
        }

        // Definir la información de los datos procesados
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


        // Obtener información de la empresa
        $empresa = Client::where("name", $this->quote->latestQuotesUpdate->quotesInformation->company)->first();
        $nombreComercial = null;
        if ($empresa) {
            $nombreComercial = $empresa->firstTradename;
        }

        // Crear datos para la presentación
        $presentationData  = [
            'data' => $this->dataInformation,
            'quote' => $this->quote,
            'nombreComercial' => $nombreComercial
        ];

        // Crear una nueva presentación
        $objPHPPresentation = new PhpPresentation();
        $objPHPPresentation->removeSlideByIndex(0);


        // Crear una nueva diapositiva para mostrar el Company
        $slideQuoteCompany = $objPHPPresentation->createSlide();
        $textShapeQuoteCompany = $slideQuoteCompany->createRichTextShape();
        $textShapeQuoteCompany->setHeight(200);
        $textShapeQuoteCompany->setWidth(800);
        $textShapeQuoteCompany->setOffsetX(50);
        $textShapeQuoteCompany->setOffsetY(300);

        $quote = $presentationData['quote'];



        // Acceder a las propiedades de Company
        $companyName = $presentationData['quote']['company']['name'];
        $companyImage = $presentationData['quote']['company']['image'];
        $companyManager = $presentationData['quote']['company']['manager'];
        $companyEmail = $presentationData['quote']['company']['email'];
        $companyPhone = $presentationData['quote']['company']['phone'];

        // Acceder a las propiedades de User
        $userName = $presentationData['quote']['user']['name'];
        $userEmail = $presentationData['quote']['user']['email'];
        $userPhone = $presentationData['quote']['user']['phone'];

        // Acceder a la propiedad nombreComercial
        $nombreComercial = $presentationData['nombreComercial'];

        $latestQuotesUpdate = $presentationData['quote']->latestQuotesUpdate;

        $quote_id = $latestQuotesUpdate->quote_id;

        // Construir el texto con los datos de latestQuotesUpdate
        $latestQuotesUpdateText = "Latest Quotes Update Information:\n";
        $latestQuotesUpdateText .= "Quote ID: QS$quote_id\n";

        // Construir el texto con el nombreComercial
        $nombreComercialText = "Nombre Comercial:\n";
        $nombreComercialText .= "Empresa: {$nombreComercial['name']}\n";

        // Texto para Company
        $textCompany = "Información de la Empresa:\n";
        $textCompany .= "Nombre: $companyName\n";
        $textCompany .= "Gerente: $companyManager\n";
        $textCompany .= "Email: $companyEmail\n";
        $textCompany .= "Teléfono: $companyPhone\n";
        // $textCompany .= "Imagen de la Empresa: $companyImage\n";

        // Texto para User
        $textUser = "Información del Usuario:\n";
        $textUser .= "Nombre: $userName\n";
        $textUser .= "Email: $userEmail\n";
        $textUser .= "Teléfono: $userPhone\n";


        // Agregar el texto a la diapositiva 
        $textRunDataCompany = $textShapeQuoteCompany->createTextRun($latestQuotesUpdateText . $textCompany . $textUser . $nombreComercialText);
        $textRunDataCompany->getFont()->setBold(true);
        $textRunDataCompany->getFont()->setSize(20);
        $textRunDataCompany->getFont()->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF000000'));

        $imgPortadaPath = public_path('storage/ppt/tmp/' . $quote->id . '/' . $imagePortadaName);
        $shape = $slideQuoteCompany->createDrawingShape();
        $shape->setName('Portada')->setDescription('Portada')->setPath($imgPortadaPath)->setHeight(250)->setOffsetX(10)->setOffsetY(10);

        // Crear una nueva diapositiva para mostrar los datos de quotesInformation
        $slideQuote3 = $objPHPPresentation->createSlide();
        $textShapeQuote = $slideQuote3->createRichTextShape();
        $textShapeQuote->setHeight(200);
        $textShapeQuote->setWidth(800);
        $textShapeQuote->setOffsetX(10);
        $textShapeQuote->setOffsetY(100);

        $quotesInformation = $presentationData['quote']['latestQuotesUpdate']['quotesInformation'];

        // $id = $quotesInformation['id'];
        $name = $quotesInformation['name'];
        $company = $quotesInformation['company'];
        $email = $quotesInformation['email'];
        $landline = $quotesInformation['landline'];
        $cell_phone = $quotesInformation['cell_phone'];

        // Construir el texto con los datos de quotesInformation
        $quotesInformationText = "Quotes Information:\n";
        // $quotesInformationText .= "ID: $id\n";
        $quotesInformationText .= "Name: $name\n";
        $quotesInformationText .= "Company: $company\n";
        $quotesInformationText .= "Email: $email\n";
        $quotesInformationText .= "Landline: $landline\n";
        $quotesInformationText .= "Cell Phone: $cell_phone\n";

        // Agregar el texto a la diapositiva de quotesInformation
        $textRunQuote = $textShapeQuote->createTextRun($quotesInformationText);
        $textRunQuote->getFont()->setBold(true);
        $textRunQuote->getFont()->setSize(20);
        $textRunQuote->getFont()->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF000000'));



        // Supongamos que $quoteProducts es tu array de objetos QuoteProducts
        $quoteProducts = $presentationData['quote']['latestQuotesUpdate']['quoteProducts'];
        // dd($quoteProducts);
        // Iterar sobre cada elemento de quoteProducts
        foreach ($quoteProducts as $quoteProduct) {

            // $totalPrice = $quoteProduct['total_price']; // Definir totalPrice dentro del bucle
            // $totalIva = ($totalPrice * $tax_fee * 0.16);

            // Agregar el texto a la diapositiva de qouteProducts
            $slideQuote = $objPHPPresentation->createSlide();
            $textShapeQuote = $slideQuote->createRichTextShape();
            $textShapeQuote->setHeight(200);
            $textShapeQuote->setWidth(800);
            $textShapeQuote->setOffsetX(10);
            $textShapeQuote->setOffsetY(10);

            $imgFondoPath = public_path('storage/ppt/tmp/' . $quote->id . '/' . $imageFondoName);
            $shape = $slideQuote->createDrawingShape();
            $shape->setName('Portada')->setDescription('Portada')->setPath($imgFondoPath)->setHeight(250)->setWidth(250)->setOffsetX(10)->setOffsetY(70);

            $imgLogoPath = public_path('storage/ppt/tmp/' . $quote->id . '/' . $imageLogoName);
            $shape = $slideQuote->createDrawingShape();
            $shape->setName('Portada')->setDescription('Portada')->setPath($imgLogoPath)->setHeight(250)->setWidth(250)->setOffsetX(80)->setOffsetY(50);


            // Decodificar las cadenas JSON en objetos PHP
            $product = json_decode($quoteProduct->product, true);
            $technique = json_decode($quoteProduct->technique, true);

            $scalesInfo = json_decode($quoteProduct->scales_info, true);
            // Inicializar las variables para almacenar la información de cada escala
            $quantityText = "";
            $techniquePriceText = "";
            $utilityText = "";
            $unitPriceText = "";
            $operationText = "";
            $totalPriceText = "";

            if ($scalesInfo !== null) {
                foreach ($scalesInfo as $scale) {
                    // Acceder a las propiedades de cada escala
                    $quantity = $scale['quantity'];
                    $techniquePrice = $scale['tecniquePrice'];
                    $utility = $scale['utility'];
                    $unitPrice = $scale['unit_price'];
                    $operation = $scale['operacion'];
                    $totalPrice = $scale['total_price'];

                    // Construir el texto para cada escala
                    $quantityText .= "\n Cantidad: $quantity" . "\t Precio Unitario: $unitPrice \t " . "\t Precio Total: $totalPrice \t ";
                    // $techniquePriceText .= "Precio de la Técnica: $techniquePrice\n ";
                    // $utilityText .= "Utilidad: $utility%\n";
                    // $unitPriceText .= "Precio Unitario: $unitPrice \t";
                    // $operationText .= "Operación: $operation\n";
                    // $totalPriceText .= "Precio Total: $totalPrice \t";
                }
            }

            // Concatenar todos los textos para formar el texto final
            $finalText =  $unitPriceText . $totalPriceText . $quantityText;

            $diasEntrega = $quoteProduct['dias_entrega'];
            $tipoDias = $quoteProduct['type_days'];
            // if ($tipoDias == 0) {
            //     echo "Días hábiles";
            // } elseif ($tipoDias == 1) {
            //     echo "Días naturales";
            // } else {
            //     echo "Valor no reconocido";
            // }
            $total = $quoteProduct['cantidad'];
            $precioUnitario = $quoteProduct['precio_unitario'];
            $precioTotal = $quoteProduct['precio_total'];
            // $escalasInfo = $quoteProduct['scales_info'];

            // Ahora puedes acceder a las propiedades de estos objetos
            $name = $product['name'];
            $description = $product['description'];
            $price = $product['price'];
            $discount = $product['descuento'];
            $image = $product['image'];


            $material = $technique['material'];
            $techniqueName = $technique['tecnica'];

            // Construir el texto para este producto y técnica
            $qouteProductAll = "\n Informacion del Producto: ";
            $qouteProductAll .= "\n Descripción: $description \n";
            // $qouteProductAll .= "Iva: $totalIva\n";

            // Construir el texto para esta técnica
            $qouteTechniqueAll = "\n Tecnica de Personalizacion: \n";
            $qouteTechniqueAll .= "Técnicas: $techniqueName \n";


            $qouteProductTotal = "\n Info Tecnica: ";
            $qouteProductTotal .= "\n Dias de Enetrega: $diasEntrega";
            $qouteProductTotal .= "\n Tipo de Dias: $tipoDias";
            $qouteProductTotal .= "\n Cantidad: $total";
            $qouteProductTotal .= "\n Precio Unitario: $precioUnitario";
            $qouteProductTotal .= "\n Precio Total: $precioTotal";

            $imageUrl = $product['image']; // Asegúrate de que esta es la URL de la imagen

            $ch = curl_init($imageUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $imageData = curl_exec($ch);
            curl_close($ch);

            $imagePath = public_path('storage/ppt/tmp/') . uniqid() . '_imagen.jpg';
            // Especifica la ruta donde quieres guardar la imagen

            file_put_contents($imagePath, $imageData);

            $shape = $slideQuote->createDrawingShape();
            $shape->setName('Imagen');
            $shape->setDescription('Descripción de la imagen');
            $shape->setPath($imagePath); // Ahora puedes usar la ruta de la imagen descargada
            $shape->setWidth(500);
            $shape->setHeight(300);
            $shape->setOffsetX(500);
            $shape->setOffsetY(400);



            // Agregar el texto a la diapositiva de qouteProducts
            $textRunData = $textShapeQuote->createTextRun($qouteProductAll . $qouteTechniqueAll . $finalText . $qouteProductTotal);
            $textRunData->getFont()->setBold(true);
            $textRunData->getFont()->setSize(16);
            $textRunData->getFont()->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF000000'));
        }

        $icontraportadaPath = public_path('storage/ppt/tmp/' . $quote->id . '/' . $contraportada);

        // Crear una nueva diapositiva
        $currentSlide = $objPHPPresentation->createSlide();

        $shape = $currentSlide->createDrawingShape();
        $shape->setName('PHPPresentation logo')
            ->setDescription('PHPPresentation logo')
            ->setPath($icontraportadaPath)
            ->setWidth(500) // 10 pulgadas en píxeles (96 ppp)
            ->setHeight(720)
            ->setOffsetX(10)
            ->setOffsetY(10);
        $shape->getShadow()->setVisible(true)
            ->setDirection(45)
            ->setDistance(10);

        $textShape = $currentSlide->createRichTextShape()
            ->setHeight(300)
            ->setWidth(600)
            ->setOffsetX(100)
            ->setOffsetY(300);
        $textRun = $textShape->createTextRun('Condiciones de pago acordadas con el vendedor
        Precios unitarios mostrados antes de IVA
        Precios mostrados en pesos mexicanos (MXP).
        El importe cotizado corresponde a la cantidad de piezas y al número de tintas arriba mencionadas. Si se modifica el número de piezas, el precio
        cambiaría.
        El tiempo de entrega empieza a correr una vez recibida la orden de compra y autorizada la muestra física o virtual a solicitud del cliente.
        Vigencia de la cotización 30 días hábiles.
        Producto cotizado de fabricación nacional o importación puede afinarse la fecha de entrega previo a la emisión de orden de compra.
        El producto cotizado, disponible en stock a la fecha de esta cotización, puede modificarse con el paso de los días sin previo aviso. Solo se
        bloquea el inventario al recibir la orden de compra.');
        $textRun->getFont()->setBold(true)
            ->setSize(14)
            ->setColor(new \PhpOffice\PhpPresentation\Style\Color('FFFFFF'));


        // Guardar la presentación
        $pptxFilePath = public_path('storage/quotes/tmp/') . 'Preview_' . $this->quote->id . '.pptx';
        $writer = IOFactory::createWriter($objPHPPresentation, 'PowerPoint2007');
        $writer->save($pptxFilePath);

        // Establecer la URL de vista previa para descargar o visualizar la presentación generada
        $this->urlPWPreview = url('storage/quotes/tmp/Preview_' . $this->quote->id . '.pptx');
    }
}
