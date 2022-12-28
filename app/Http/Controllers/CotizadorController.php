<?php

namespace App\Http\Controllers;

use App\Mail\SendDataErrorCreateQuote;
use App\Models\Catalogo\Color;
use App\Models\Catalogo\GlobalAttribute;
use App\Models\Catalogo\Product;
use App\Models\Catalogo\Provider;
use App\Models\Client;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class CotizadorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('home');
    }

    public function catalogo()
    {
        return view('pages.catalogo.catalogo');
    }

    public function verProducto(Product $product)
    {
        $utilidad = GlobalAttribute::find(1);
        $utilidad = (float) $utilidad->value;
        return view('pages.catalogo.product', compact('product', 'utilidad'));
    }

    public function cotizacion()
    {
        $cotizacionActual = [];
        $total = 0;
        if (auth()->user()->currentQuote) {
            $cotizacionActual = auth()->user()->currentQuote->currentQuoteDetails;
            $total = $cotizacionActual->sum('precio_total');
        }
        return view('pages.catalogo.cotizacion-actual', compact('cotizacionActual', 'total'));
    }

    public function cotizaciones()
    {
        return view('pages.catalogo.cotizaciones');
    }
    public function verCotizacion(Quote $quote)
    {
        return view('pages.catalogo.ver-cotizacion', compact('quote'));
    }

    public function finalizar()
    {
        return view('pages.catalogo.finalizar');
    }
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
                $pdf = \PDF::loadView('pages.pdf.promolife', ['quote' => $quote, 'nombreComercial' => $nombreComercial]);
                break;
            case 'BH TRADEMARKET':
                # code...
                $pdf = \PDF::loadView('pages.pdf.bh', ['quote' => $quote, 'nombreComercial' => $nombreComercial]);
                break;
            case 'PROMO ZALE':
                # code...
                $pdf = \PDF::loadView('pages.pdf.promozale', ['quote' => $quote, 'nombreComercial' => $nombreComercial]);
                break;

            default:
                # code...
                break;
        }

        $pdf->setPaper('Letter', 'portrait');
        return $pdf->stream("QS-" . $quote->id . " " . $quote->latestQuotesUpdate->quotesInformation->oportunity . ' ' . $quote->updated_at->format('d/m/Y') . '.pdf');
    }

    public function all()
    {
        return view('pages.catalogo.cotizaciones-all');
    }
    public function dashboard()
    {
        return view('pages.catalogo.dashboard');
    }

    public function changeCompany($company)
    {
        $user = User::find(auth()->user()->id);
        $user->company_session = $company;
        $user->save();
        return back();
    }

    public function addProductCreate()
    {
        return view('pages.catalogo.addProduct');
    }
    public function listProducts()
    {
        $products = auth()->user()->listProducts()->paginate(30);
        return view('pages.catalogo.listProducts', compact('products'));
    }

    public function enviarCotizacionesAOdoo()
    {
        $cotizacionesAEnviar = Quote::where("pending_odoo", true)->get();
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
                        $pdf = PDF::loadView('pages.pdf.promolife', ['quote' => $cotizacion, 'nombreComercial' => $nombreComercial]);
                        break;
                    case 'BH TRADEMARKET':
                        # code...
                        $keyOdoo = 'e877f47a2a844ded99004e444c5a9797';
                        $pdf = PDF::loadView('pages.pdf.bh', ['quote' => $cotizacion, 'nombreComercial' => $nombreComercial]);
                        break;
                    case 'PROMO ZALE':
                        # code...
                        $keyOdoo = '0e31683a8597606123ff4fcfab772ed7';
                        $pdf = PDF::loadView('pages.pdf.promozale', ['quote' => $cotizacion, 'nombreComercial' => $nombreComercial]);
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
                $subtotal = floatval($cotizacion->latestQuotesUpdate->quoteProducts()->sum('precio_total'));
                $taxFee = round($subtotal * ($cotizacion->latestQuotesUpdate->quotesInformation->tax_fee / 100), 2);
                $subtotal = floatval($cotizacion->latestQuotesUpdate->quoteProducts()->sum('precio_total')) + $taxFee;
                $discountValue = 0;
                if ($type == 'Fijo') {
                    $discountValue = floatval($value);
                } else {
                    $discountValue = floatval(round(($subtotal / 100) * $value, 2));
                }
                $estimated = floatval($subtotal - $discountValue);
                $responseOdoo = '';
                try {
                    $url = 'https://api-promolife.vde-suite.com:5030/custom/Promolife/V2/crm-lead/create';
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
                                $newPath = "/storage/quotes/" . time() . $cotizacion->lead . ".pdf";
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
