<?php

namespace App\Http\Controllers;

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
        return $pdf->stream('ejemplo.pdf');
    }

    public function all()
    {
        $quotes = Quote::orderBy('created_at', 'ASC')->get();
        return view('pages.catalogo.cotizaciones-all', compact('quotes'));
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

    public function addProductStore(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
            'precio' => 'required',
            'stock' => 'required',
            'color' => 'required',
            'proveedor' => 'required',
            'imagen' => 'required|image|max:512',
        ]);

        $maxSKU = Product::max('internal_sku');
        $idSku = null;
        if (!$maxSKU) {
            $idSku = 1;
        } else {
            $idSku = (int) explode('-', $maxSKU)[1];
            $idSku++;
        }
        $color = null;
        $slug = mb_strtolower(str_replace(' ', '-', $request->color));
        $color = Color::where("slug", $slug)->first();
        if (!$color) {
            $color = Color::create([
                'color' => ucfirst($request->color), 'slug' => $slug,
            ]);
        }

        $proveedor = null;
        $proveedor = Provider::create([
            'company' => $request->proveedor, 'email' => "no-mail",
            'phone' => 000,
            'contact' => "False",
            'discount' => 0
        ]);
        if (!$proveedor) {
            $proveedor = Color::create([
                'proveedor' => ucfirst($request->proveedor), 'slug' => $slug,
            ]);
        }

        $newProduct = Product::create([
            'internal_sku' => "PROM-" . str_pad($idSku, 7, "0", STR_PAD_LEFT),
            'sku' => 0000,
            'name' => $request->nombre,
            'price' =>   $request->precio,
            'description' =>  $request->descripcion,
            'stock' => $request->stock,
            'producto_promocion' => false,
            'descuento' => 0,
            'producto_nuevo' =>  false,
            'precio_unico' => true,
            'type_id' => 3,
            'color_id' => $color->id,
            'provider_id' => $proveedor->id,
            'visible' => false,
        ]);


        $file = $request->file('imagen');
        $imageName = time() .  $file->getClientOriginalName();

        Storage::put('public/media/' . $imageName, File::get($file));
        $url = url('') .  Storage::url('public/media/' . $imageName);
        $newProduct->images()->create([
            'image_url' => $url
        ]);

        return redirect()->action([CotizadorController::class, 'verProducto'], ['product' => $newProduct->id]);
    }
}
