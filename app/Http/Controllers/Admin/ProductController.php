<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    public function get(Request $request){
        try {
            $response = [];
            $products = Product::get();

            $response['status'] = "OK";
            $response['message'] = "Facturas listadas";
            $response['data'] = $products;

            return response()->json($response, 201);
        } catch (Exception $e) {
            $response['status'] = "FAIL";
            $response['message'] = $e->getMessage();
            $response['data'] = "";

            return response()->json($response, 400);
        }
    }
}
