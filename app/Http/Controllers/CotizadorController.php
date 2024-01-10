<?php

namespace App\Http\Controllers;

use App\Mail\SendDataErrorCreateQuote;
use App\Models\Catalogo\GlobalAttribute;
use App\Models\Catalogo\Product;
use App\Models\Client;
use App\Models\Presentation;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use iio\libmergepdf\Merger;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class CotizadorController extends Controller
{
    /**
     * Constructor de la clase CotizadorController.
     *
     * Este método se ejecuta automáticamente al crear una instancia de la clase.
     * Establece el middleware de autenticación para todos los métodos de la clase,
     * excepto el método 'enviarCotizacionesAOdoo'.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['enviarCotizacionesAOdoo']);
    }

    /**
     * Método que muestra la vista del catálogo del cotizador.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function catalogo()
    {
        return view('cotizador.catalogo.catalogo');
    }

    /**
     * Método para ver un producto.
     *
     * @param Product $product El producto a visualizar.
     * @return \Illuminate\View\View La vista del producto.
     */
    public function verProducto(Product $product)
    {
        $proveedores = auth()->user()->companySession->providers->pluck('id');
        $utilidad = GlobalAttribute::find(1);
        $utilidad = (float) $utilidad->value;
        // Revisar sin un id esta dentro de una array
        $disponiblidad = false;
        if (in_array($product->provider_id, $proveedores->toArray())) {
            $disponiblidad = true;
        }

        // Consultar las existencias de los productos en caso de ser de Doble Vela.
        $msg = '';
        try {
            if ($product->provider_id == 5) {
                $cliente = new \nusoap_client('http://srv-datos.dyndns.info/doblevela/service.asmx?wsdl', 'wsdl');
                $error = $cliente->getError();
                if ($error) {
                    echo 'Error' . $error;
                }
                //agregamos los parametros, en este caso solo es la llave de acceso
                $parametros = array('Key' => 't5jRODOUUIoytCPPk2Nd6Q==', 'codigo' => $product->sku_parent);
                //hacemos el llamado del metodo
                $resultado = $cliente->call('GetExistencia', $parametros);
                $msg = '';
                $productoEsEncontrado = false;
                if (array_key_exists('GetExistenciaResult', $resultado)) {
                    $informacionExistencias = json_decode(utf8_encode($resultado['GetExistenciaResult']))->Resultado;
                    if (count($informacionExistencias) > 1) {
                        foreach ($informacionExistencias as $productExistencia) {
                            if ($product->sku == $productExistencia->CLAVE && !$productoEsEncontrado) {
                                $product->stock = $productExistencia->EXISTENCIAS;
                                $product->save();
                                $productoEsEncontrado = true;
                                break;
                            }
                            $msg = "Este producto no se encuentra en el catalogo que esta enviado DV via Servicio WEB";
                        }
                    } else {
                        $msg = "Este producto no se encuentra en el catalogo que esta enviado DV via Servicio WEB";
                    }
                } else {
                    $msg = "No se obtuvo informacion acerca del Stock de este producto. Es posible que los datos sean incorrectos";
                }
                if ($productoEsEncontrado) {
                    $msg = '';
                }
            }
        } catch (Exception $e) {
            $msg = "No se obtuvo informacion acerca del Stock de este producto. Es posible que los datos sean incorrectos. Error: " . $e->getMessage();
        }
        return view('cotizador.producto.product', compact('product', 'utilidad', "msg", "disponiblidad"));
    }

    /**
     * Método para obtener la cotización actual del usuario.
     *
     * @return \Illuminate\View\View
     */
    public function cotizacion()
    {
        $cotizacionActual = [];
        $total = 0;
        if (auth()->user()->currentQuotes) {
            $cotizacionActual = auth()->user()->currentQuotes->where('active', true)->first();
        }
        return view('cotizador.cotizacion_actual.cotizacion-actual', compact('cotizacionActual'));
    }

    /**
     * Método que devuelve la vista de las cotizaciones del cotizador.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function cotizaciones()
    {
        return view('cotizador.mis_cotizaciones.cotizaciones');
    }

    /**
     * Muestra la cotización en la vista correspondiente.
     *
     * @param Quote $quote La cotización a mostrar.
     * @return \Illuminate\View\View La vista de la cotización.
     */
    public function verCotizacion(Quote $quote)
    {
        return view('cotizador.ver_cotizacion.page-ver-cotizacion', compact('quote'));
    }

    /**
     * Método que muestra la vista para finalizar la cotización.
     *
     * @return \Illuminate\View\View
     */
    public function finalizar()
    {
        return view('cotizador.finalizar_cotizacion.finalizar');
    }


    /**
     * Genera una vista previa del cotizador en formato PDF y lo devuelve como una respuesta HTTP.
     *
     * @param Quote $quote El objeto Quote para el cual se generará la vista previa.
     * @return \Illuminate\Http\Response La respuesta HTTP que contiene el archivo PDF de la vista previa.
     */
    public function previsualizar(Quote $quote)
    {
        $empresa = Client::where("name", $quote->latestQuotesUpdate->quotesInformation->company)->first();
        $nombreComercial = null;
        if ($empresa) {
            $nombreComercial = $empresa->firstTradename;
        }

        $pdf = '';
        $company = $quote->company->name;
        switch ($company) {
            case 'PROMO LIFE':
                # code...
                $pdf = \PDF::loadView('pdf.promolife', ['quote' => $quote, 'nombreComercial' => $nombreComercial]);
                break;
            case 'BH TRADEMARKET':
                # code...
                $pdf = \PDF::loadView('pdf.bh', ['quote' => $quote, 'nombreComercial' => $nombreComercial]);
                break;
            case 'PROMO ZALE':
                # code...
                $pdf = \PDF::loadView('pdf.promozale', ['quote' => $quote, 'nombreComercial' => $nombreComercial]);
                break;

            default:
                # code...
                break;
        }

        $pdf->setPaper('Letter', 'portrait');
        return $pdf->stream("QS-" . $quote->id . " " . $quote->latestQuotesUpdate->quotesInformation->oportunity . ' ' . $quote->updated_at->format('d/m/Y') . '.pdf');
    }

    /**
     * Previsualiza una presentación en formato PPT.
     *
     * @param Presentation $presentacion La presentación a previsualizar.
     * @return \Illuminate\Http\RedirectResponse Redirige a la URL de la previsualización en PDF.
     */
    public function previsualizarPPT(Presentation $presentacion)
    {
        $quote = $presentacion->quote;
        $dataInformation = [
            'portada' => $presentacion->front_page,
            'logo' => $presentacion->logo,
            'contraportada' => $presentacion->back_page,
            'fondo' => $presentacion->background,

            'color_primario' => null,
            'color_secundario' => null,
            'productos_por_pagina' => 1,
            'mostrar_formato_de_tabla' => null,
            'generar_contraportada' => $presentacion->have_back_page,
        ];

        $empresa = Client::where("name", $quote->latestQuotesUpdate->quotesInformation->company)->first();
        $nombreComercial = null;
        if ($empresa) {
            $nombreComercial = $empresa->firstTradename;
        }

        $dataToPPT = [
            'data' => $dataInformation,
            'quote' => $quote,
            'nombreComercial' => $nombreComercial
        ];

        $pdfCuerpo = '';
        $pdfContraportada = '';
        switch ($quote->company->name) {
            case 'PROMO LIFE':
                $pdfCuerpo = PDF::loadView('pdf.pptpl.body', $dataToPPT);
                $pdfPortada = PDF::loadView('pdf.pptpl.firstpage', $dataToPPT);
                if ($presentacion->have_back_page) {
                    $pdfContraportada = PDF::loadView('pdf.pptpl.lastpage', $dataToPPT);
                }
                break;
            case 'BH TRADEMARKET':
                $pdfCuerpo = PDF::loadView('pdf.pptbh.body', $dataToPPT);
                $pdfPortada = PDF::loadView('pdf.pptbh.firstpage', $dataToPPT);
                if ($presentacion->have_back_page) {
                    $pdfContraportada = PDF::loadView('pdf.pptbh.lastpage', $dataToPPT);
                }
                break;
            case 'PROMO ZALE':
                $pdfCuerpo = PDF::loadView('pdf.pptpz.body', $dataToPPT);
                $pdfPortada = PDF::loadView('pdf.pptpz.firstpage', $dataToPPT);
                if ($presentacion->have_back_page) {
                    $pdfContraportada = PDF::loadView('pdf.pptpz.lastpage', $dataToPPT);
                }
                break;
            default:
                break;
        }

        $pdfCuerpo->setPaper(array(0, 0, 872, 490));
        $pdfCuerpo = $pdfCuerpo->stream("Preview " . $quote->id . ".pdf");
        $pathCuerpo =  "/storage/quotes/tmp/" . time() . "Preview " . $quote->id  . ".pdf";
        file_put_contents(public_path() . $pathCuerpo, $pdfCuerpo);

        $pdfPortada->setPaper(array(0, 0, 872, 490));
        $pdfPortada = $pdfPortada->stream("Preview " . $quote->id . "2.pdf");
        $pathPortada =  "/storage/quotes/tmp/" . time() . "Preview " . $quote->id  . "2.pdf";
        file_put_contents(public_path() . $pathPortada, $pdfPortada);

        if ($presentacion->have_back_page) {
            $pdfContraportada->setPaper(array(0, 0, 872, 490));
            $pdfContraportada = $pdfContraportada->stream("Preview " . $quote->id . "2.pdf");
            $pathContraportada =  "/storage/quotes/tmp/" . time() . "Preview " . $quote->id  . "3.pdf";
            file_put_contents(public_path() . $pathContraportada, $pdfContraportada);
        }
        $dataMerge = [
            public_path() . $pathPortada,
            public_path() . $pathCuerpo

        ];
        if ($presentacion->have_back_page) {
            array_push($dataMerge, public_path() . $pathContraportada);
        }
        $merger = new Merger();
        $merger->addIterator($dataMerge);
        $createdPdf = $merger->merge();
        $pathPdf = "/storage/quotes/tmp/" . time() . "Preview " . $quote->id  . "3.pdf";
        file_put_contents(public_path() . $pathPdf, $createdPdf);

        return redirect(url('') . $pathPdf);
    }

    /**
     * Método que devuelve la vista de todas las cotizaciones.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function all()
    {
        return view('admin.cotizaciones.cotizaciones-all');
    }

    /**
     * Método que muestra el panel de control del administrador.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function dashboard()
    {
        return view('admin.dashboard.dashboard');
    }

    /**
     * Cambia la compañía de sesión del usuario.
     *
     * @param string $company La compañía a la que se desea cambiar.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeCompany($company)
    {
        $user = User::find(auth()->user()->id);
        $user->company_session = $company;
        $user->save();
        return back();
    }

    /**
     * Cambia el tipo de moneda de la sesión y obtiene el tipo de cambio actual.
     * SIN USO ACTUALMENTE
     *
     * @param string $currency_type El tipo de moneda a establecer (USD o cualquier otro valor).
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeCurrencyType($currency_type)
    {
        session()->put('currency_type', $currency_type);
        $currency = 0;
        if ($currency_type == "USD") {
            try {
                // Consumir api para el tipo de cambio con curl
                $curl = curl_init(config('settings.url_api_banxico'));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Bmx-Token: ' . 'd01cf1306eced862bc6eece145a3599bb1a62e5276009872139970849a93cf17',
                ]);
                $response = curl_exec($curl);
                // Convertir la respuesta de string a json
                $response = json_decode($response, true);
                $currency = number_format($response['bmx']['series'][0]['datos'][0]['dato'], 2, '.', '');
                session()->put('currency', $currency);
                session()->put('date_update', now());
            } catch (Exception $e) {
            }
        } else {
            // Eliminar la session
            session()->forget('currency');
            session()->forget('date_update');
            $currency = 1;
        }
        return back();
    }

    /**
     * Método que devuelve la vista para agregar un nuevo producto.
     *
     * @return \Illuminate\View\View
     */
    public function addProductCreate()
    {
        return view('cotizador.mis_productos.addProduct');
    }

    /**
     * Método que devuelve la vista para listar los productos.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listProducts()
    {
        return view('cotizador.mis_productos.listProducts');
    }

    /**
     * Envia las cotizaciones a Odoo.
     *
     * Este método recupera las cotizaciones pendientes de enviar a Odoo y las envía una por una.
     * Para cada cotización, se obtiene la información necesaria, como el descuento, la empresa, el nombre comercial, etc.
     * Luego se genera un archivo PDF correspondiente a la cotización y se guarda en el servidor.
     * A continuación, se realiza una solicitud a la API de Odoo para crear una oportunidad con los datos de la cotización.
     * Si la solicitud es exitosa, se actualiza el número de lead de la cotización y se marca como no pendiente de enviar a Odoo.
     * Si ocurre algún error durante el proceso, se registra en un array de errores.
     * Al finalizar, si hay errores registrados, se guarda un archivo de texto con los errores y se envía un correo electrónico al administrador.
     *
     * @return void
     */
    public function enviarCotizacionesAOdoo()
    {
        $cotizacionesAEnviar = Quote::where("pending_odoo", true)->whereBetween("created_at", [now()->subWeek(), now()->subMinutes(3)])->orderBy('created_at', "DESC")->limit(10)->get();
        $erroresAlCotizar = [];
        foreach ($cotizacionesAEnviar as $cotizacion) {
            if ($cotizacion->latestQuotesUpdate) {
                $odoo_id_user = null;
                if (count($cotizacion->user->info) > 0) {
                    foreach ($cotizacion->user->info as $infoOdoo) {
                        if ($infoOdoo->company_id == $cotizacion->company_id) {
                            $odoo_id_user = $infoOdoo->odoo_id;
                        }
                    }
                }

                $type = 'Fijo';
                $value = 0;
                if ($cotizacion->latestQuotesUpdate->quoteDiscount) {
                    $type = $cotizacion->latestQuotesUpdate->quoteDiscount->type;
                    $value = $cotizacion->latestQuotesUpdate->quoteDiscount->value;
                }
                $empresa = Client::where("name", $cotizacion->latestQuotesUpdate->quotesInformation->company)->first();
                $nombreComercial = null;
                if ($empresa) {
                    $nombreComercial = $empresa->firstTradename;
                }

                $pdf = '';
                $company = $cotizacion->company->name;

                $keyOdoo = '';
                $errors = false;
                $message = '';
                switch ($company) {
                    case 'PROMO LIFE':
                        # code...
                        $keyOdoo = 'cd78567e59e016e964cdcc1bd99367c6';
                        $pdf = PDF::loadView('pdf.promolife', ['quote' => $cotizacion, 'nombreComercial' => $nombreComercial]);
                        break;
                    case 'BH TRADEMARKET':
                        # code...
                        $keyOdoo = 'e877f47a2a844ded99004e444c5a9797';
                        $pdf = PDF::loadView('pdf.bh', ['quote' => $cotizacion, 'nombreComercial' => $nombreComercial]);
                        break;
                    case 'PROMO ZALE':
                        # code...
                        $keyOdoo = '0e31683a8597606123ff4fcfab772ed7';
                        $pdf = PDF::loadView('pdf.promozale', ['quote' => $cotizacion, 'nombreComercial' => $nombreComercial]);
                        break;
                    default:
                        # code...
                        break;
                }
                $pdf->setPaper('Letter', 'portrait');
                $pdf = $pdf->stream($cotizacion->lead . ".pdf");
                $path =  "/storage/quotes/" . time() . $cotizacion->lead . ".pdf";
                file_put_contents(public_path() . $path, $pdf);
                $newPath = "";
                $subtotal = 0;
                foreach ($cotizacion->latestQuotesUpdate->quoteProducts as $productToSum) {
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
                $taxFee = round($subtotal * ($cotizacion->latestQuotesUpdate->quotesInformation->tax_fee / 100), 2);
                $subtotal = $subtotal + $taxFee;
                $discountValue = 0;
                if ($type == 'Fijo') {
                    $discountValue = floatval($value);
                } else {
                    $discountValue = floatval(round(($subtotal / 100) * $value, 2));
                }
                $estimated = floatval($subtotal - $discountValue);
                $responseOdoo = '';
                try {
                    $url = config('settings.api_odoo');
                    $data =  [
                        'Opportunities' => [
                            [
                                "CodeLead" => "",
                                'Name' => $cotizacion->latestQuotesUpdate->quotesInformation->oportunity,
                                'Partner' => [
                                    'Name' => $cotizacion->latestQuotesUpdate->quotesInformation->company,
                                    'Email' => $cotizacion->latestQuotesUpdate->quotesInformation->email,
                                    'Phone' => $cotizacion->latestQuotesUpdate->quotesInformation->cell_phone,
                                    'Contact' => $cotizacion->latestQuotesUpdate->quotesInformation->name,
                                ],
                                "Estimated" => (floatval($estimated)),
                                "Rating" => (int) $cotizacion->latestQuotesUpdate->quotesInformation->rank,
                                "UserID" => (int) $odoo_id_user,
                                "File" => [
                                    'Name' => $cotizacion->latestQuotesUpdate->quotesInformation->oportunity,
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
                                $cotizacion->lead = $codeLead;
                                $cotizacion->pending_odoo = false;
                                $cotizacion->save();
                                // $newPath = "/storage/quotes/" . time() . $cotizacion->lead . ".pdf";
                                // rename(public_path() . $path, public_path() . $newPath);
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
                if ($errors) {
                    array_push($erroresAlCotizar, [$message, $responseOdoo, $cotizacion]);
                }
            }
        }

        if (count($erroresAlCotizar) > 0) {
            Storage::put('/public/dataErrorToTrySendQuote.txt',   json_encode($erroresAlCotizar));
            Mail::to('adminportales@promolife.com.mx')->send(new SendDataErrorCreateQuote('adminportales@promolife.com.mx', '/storage/dataErrorToTrySendQuote.txt'));
        }
    }

    /**
     * Exporta los usuarios a un archivo Excel.
     *
     * Crea un archivo Excel con los datos de los usuarios registrados en el sistema.
     * El archivo se descarga automáticamente al ser accedido.
     */
    public function exportUsuarios()
    {

        $documento = new Spreadsheet();
        $documento
            ->getProperties()
            ->setCreator("Aquí va el creador, como cadena")
            ->setLastModifiedBy('Parzibyte') // última vez modificado por
            ->setTitle('Mi primer documento creado con PhpSpreadSheet')
            ->setSubject('El asunto')
            ->setDescription('Este documento fue generado para parzibyte.me')
            ->setKeywords('etiquetas o palabras clave separadas por espacios')
            ->setCategory('La categoría');

        $nombreDelDocumento = "Reporte de Usuarios con corte al " . now()->format('d-m-Y') . ".xlsx";

        $hoja = $documento->getActiveSheet();
        $hoja->setTitle("Usuarios");
        $users = User::where('visible', 1)->get();
        $i = 2;
        $hoja->setCellValueByColumnAndRow(1, 1,  'Nombre');
        $hoja->setCellValueByColumnAndRow(2, 1,  'Apellido');
        $hoja->setCellValueByColumnAndRow(3, 1,  'Correo');
        $hoja->setCellValueByColumnAndRow(4, 1,  'Ultimo Inicio de Sesion');

        foreach ($users as $user) {
            $hoja->setCellValueByColumnAndRow(1, $i,  $user->name);
            $hoja->setCellValueByColumnAndRow(2, $i,  $user->lastname);
            $hoja->setCellValueByColumnAndRow(3, $i,  $user->email);
            $hoja->setCellValueByColumnAndRow(4, $i,  $user->last_login != null ? $user->last_login : "No hay Registro");
            $i++;
        }

        /**
         * Los siguientes encabezados son necesarios para que
         * el navegador entienda que no le estamos mandando
         * simple HTML
         * Por cierto: no hagas ningún echo ni cosas de esas; es decir, no imprimas nada
         */

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($documento, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
}
