<?php

namespace App\Http\Controllers;

use App\Models\Catalogo\Category;
use App\Models\Catalogo\Color;
use App\Models\Catalogo\Image;
use App\Models\Catalogo\Price;
use App\Models\Catalogo\Product;
use App\Models\Catalogo\ProductAttribute;
use App\Models\Catalogo\ProductCategory;
use App\Models\Catalogo\Provider;
use App\Models\Catalogo\Subcategory;
use App\Models\Catalogo\Type;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ActualizarCatalogoController extends Controller
{
    public function actualizarCatalogo()
    {
        $client = new Client(); //GuzzleHttp\Client
        $url = "http://127.0.0.1:8001/api/getAllProductos";


        $response = $client->request('GET', $url, [
            'verify'  => false,
        ]);

        $responseBody = json_decode($response->getBody());

        // Actualizar la informacion

        foreach ($responseBody->providers as $provider) {
            Provider::updateOrCreate([
                "id" => $provider->id,
                "company" => $provider->company
            ], [
                "email" => $provider->email,
                "phone" => $provider->phone,
                "contact" => $provider->contact,
                "discount" => $provider->discount,
            ]);
        }
        foreach ($responseBody->categories as $category) {
            Category::updateOrCreate([
                "id" => $category->id,
                "family" => $category->family,
                "slug" => $category->slug
            ]);
        }
        foreach ($responseBody->subcategories as $subcategory) {
            Subcategory::updateOrCreate([
                "id" => $subcategory->id,
                "subfamily" => $subcategory->subfamily,
                "slug" => $subcategory->slug,
                "category_id" => $subcategory->category_id
            ]);
        }
        foreach ($responseBody->types as $type) {
            Type::updateOrCreate([
                "id" => $type->id,
                "type" => $type->type,
                "slug" => $type->slug,
            ]);
        }
        foreach ($responseBody->colors as $color) {
            Color::updateOrCreate([
                "id" => $color->id,
                "color" => $color->color,
                "slug" => $color->slug,
            ]);
        }
        foreach ($responseBody->products as $product) {
            Product::updateOrCreate([
                "id" => $product->id,
                "internal_sku" => $product->internal_sku,
                "sku_parent" => $product->sku_parent,
                "sku" => $product->sku,
                'name' => $product->name,
                'description' => $product->description,
                'provider_id' => $product->provider_id,
            ], [
                'price' => $product->price,
                'producto_promocion' => $product->producto_promocion,
                'descuento' => $product->descuento,
                'producto_nuevo' => $product->producto_nuevo,
                'precio_unico' => $product->precio_unico,
                'stock' => $product->stock,
                'type_id' => $product->type_id,
                'color_id' => $product->color_id,
            ]);
        }
        foreach ($responseBody->productCategory as $productCategory) {
            ProductCategory::updateOrCreate([
                "id" => $productCategory->id,
                "category_id" => $productCategory->category_id,
                "subcategory_id" => $productCategory->subcategory_id,
                "product_id" => $productCategory->product_id,
            ]);
        }
        foreach ($responseBody->images as $image) {
            Image::updateOrCreate([
                "id" => $image->id,
                "image_url" => $image->image_url,
                "product_id" => $image->product_id,
            ]);
        }
        foreach ($responseBody->prices as $price) {
            Price::updateOrCreate([
                "id" => $price->id,
                "product_id" => $price->product_id,
            ], [
                "price" => $price->price,
                "escala" => $price->escala,
            ]);
        }
        foreach ($responseBody->productAttribute as $productAttribute) {
            ProductAttribute::updateOrCreate([
                "id" => $productAttribute->id,
                "product_id" => $productAttribute->product_id,
                "attribute" => $productAttribute->attribute,
                "slug" => $productAttribute->slug,
            ], [
                "value" => $productAttribute->value,
            ]);
        }

        // return $responseBody;
    }
}
