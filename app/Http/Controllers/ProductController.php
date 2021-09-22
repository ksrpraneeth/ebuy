<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function create(ProductRequest $request)
    {
        $description = $request->get('description');
        $price = $request->get('price');
        $product = new Product();
        $product->description = $description;
        $product->price = $price;
        $product->save();
    }
}
