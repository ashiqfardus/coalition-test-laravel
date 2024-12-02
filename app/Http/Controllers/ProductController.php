<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        return view('products');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required',
            'quantity' => 'required|numeric',
            'product_price' => 'required|numeric',
        ]);
        if ($validator->fails()){
            return response()->json(['message' => $validator->errors()->first(), 'status'=>false], 400);
        }

        $product = [
            'id' => uniqid(),
            'product_name' => $request->product_name,
            'quantity_in_stock' => $request->quantity,
            'price_per_item' => $request->product_price,
            'total_value_number' => $request->quantity * $request->product_price,
            'created_at' => now(),
        ];

        try {
            $file = storage_path('products.json');

            if (!File::exists($file)) {
                File::put($file, json_encode([$product]));
            } else {
                $products = json_decode(File::get($file), true);
                $products[] = $product;
                File::put($file, json_encode($products));
            }

            return response()->json(['message' => 'Product added successfully', 'status'=>true], 200);
        }
        catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status'=>false], 500);
        }

    }
    public function fetch()
    {
        $file = storage_path('products.json');
        $products = [];
        if (File::exists($file)) {
            $products = json_decode(File::get($file), true);
        }

        if (empty($products)) {
            return response()->json(['message' => 'No products found', 'status'=>false], 404);
        }

        usort($products, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        return response()->json(['products' => $products, 'status'=>true], 200);
    }
}
