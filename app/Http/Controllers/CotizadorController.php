<?php

namespace App\Http\Controllers;

use App\Models\Catalogo\GlobalAttribute;
use App\Models\Catalogo\Product;
use App\Models\Quote;
use Illuminate\Http\Request;

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
        $quotes = auth()->user()->quotes;
        return view('pages.catalogo.cotizaciones', compact('quotes'));
    }
    public function verCotizacion(Quote $quote)
    {
        return view('pages.catalogo.ver-cotizacion', compact('quote'));
    }

    public function finalizar()
    {
        return view('pages.catalogo.finalizar');
    }
    public function previsualizar()
    {
        $pdf = \PDF::loadView('pages.pdf.promolife');
        return $pdf->stream('ejemplo.pdf');
    }
}
