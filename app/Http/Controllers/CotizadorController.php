<?php

namespace App\Http\Controllers;

use App\Models\Catalogo\Product;
use Illuminate\Http\Request;

class CotizadorController extends Controller
{
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
        $utilidad = 10;
        return view('pages.catalogo.product', compact('product', 'utilidad'));
    }

    public function cotizacion()
    {
    }

    public function cotizaciones()
    {
    }
}
