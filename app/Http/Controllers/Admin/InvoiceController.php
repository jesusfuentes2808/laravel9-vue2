<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\In;
use function PHPUnit\Framework\throwException;

class InvoiceController extends Controller
{
    //
    public function getIVA(){
        $response['status'] = "OK";
        $response['message'] = "datos listadas";
        $response['data'] = ["iva" => env("IVA", 16)];
        return response()->json($response, 201);
    }

    public function get(Request $request){
        try {
            $response = [];
            $invoices = Invoice::with("saleUser")->orderBy("id", "DESC")
                        ->skip(0)->limit(10)->get()->toArray();
            //dd($invoices);
            $invoices = array_map(function($item){
                return [...$item,
                            'created_at'=> Carbon::parse($item["created_at"])->format("Y-m-d H:i:s"),
                            'invoice_number' => str_pad($item["id"], 10, "0", STR_PAD_LEFT)];
            }, $invoices);

            $response['status'] = "OK";
            $response['message'] = "Facturas listadas";
            $response['data'] = $invoices;

            return response()->json($response, 201);
        } catch (Exception $e) {
            $response['status'] = "FAIL";
            $response['message'] = $e->getMessage();
            $response['data'] = "";

            return response()->json($response, 400);
        }
    }

    public function store(Request $request){
        DB::beginTransaction();
        try {
            $response = [];

            $validator = Validator::make($request->all(),[
                'client_name' => 'required|string|regex:/^[\pL\s\- ]+$/u',
                'client_nit' => 'required|numeric|digits:8',
                'business_name' => 'required|string|regex:/^[\pL\s\- ]+$/u',
                'business_nit' => 'required|numeric|digits:8',
                'invoice_details' => 'array',
                'invoice_details.*.sku' => 'required|exists:products,sku',
                'invoice_details.*.qty' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                $response['status'] = "FAIL";
                $response['message'] = "Validar datos";
                $response['data'] = $validator->errors();

                return response()->json($response, 400);
            }

            $invoices = new Invoice();

            $invoices->client_name = $request->client_name;
            $invoices->client_nit = $request->client_nit;
            $invoices->business_name = $request->business_name;
            $invoices->business_nit = $request->business_nit;
            $invoices->total = 0.00;
            $invoices->iva = 0.00;
            $invoices->total_without_iva = 0.00;
            $invoices->sale_user_id = $request->user()->id;

            $invoices->save();

            $invoiceTotal = 0;

            foreach ($request->invoice_details as $item){
                $product = Product::where("sku", $item["sku"])->first();
                $invoiceDetails = New InvoiceDetail();

                $invoiceDetails->qty = $item["qty"];
                $invoiceDetails->product_description = $product->name;
                $invoiceDetails->product_price_sale = $product->price;
                $invoiceDetails->product_price_total = ($item["qty"] * $product->price);
                $invoiceDetails->product_id = 1;
                $invoiceDetails->invoice_id = $invoices->id;

                $invoiceDetails->save();

                if($product->stock >= $item["qty"]){
                    $product->stock = $product->stock - $item["qty"];
                    $product->save();
                } else {
                    throw new Exception("No existe stock suficiente de productos");
                }

                $invoiceTotal += ($item["qty"] * $product->price);
            }

            $invoice = Invoice::find($invoices->id);

            $invoice->total = $invoiceTotal;
            $invoice->iva = (env('IVA', 16) / 100) * $invoiceTotal;
            $invoice->total_without_iva = $invoice->total - $invoice->iva;

            $invoice->save();

            $invoices = Invoice::with('invoiceDetails')->where('id', $invoice->id)->get();



            $response['status'] = "OK";
            $response['message'] = "Factura creada";
            $response['data'] = $invoices[0];
            DB::commit();
            return response()->json($response, 201);
        } catch (Exception $e) {

            DB::rollBack();
            $response['status'] = "FAIL";
            $response['message'] = $e->getMessage();
            $response['data'] = "";

            return response()->json($response, 400);
        }
    }

    public function getById($id){
        try {
            $response = [];

            $validator = Validator::make(["id" => $id],[
                'id' => 'required|numeric|exists:invoices,id',
            ]);

            if ($validator->fails()) {
                $response['status'] = "FAIL";
                $response['message'] = "Validar datos";
                $response['data'] = $validator->errors();

                return response()->json($response, 400);
            }


            $invoices = Invoice::with(['invoiceDetails.product'])->where('id', $id)->first();

            $invoices->created_at = Carbon::parse($invoices->created_at )->format('Y-m-d H:i:s');

            $response['status'] = "OK";
            $response['message'] = "Factura creada";
            $response['data'] = $invoices;

            return response()->json($response, 201);
        } catch (Exception $e) {

            $response['status'] = "FAIL";
            $response['message'] = $e->getMessage();
            $response['data'] = "";

            return response()->json($response, 400);
        }
    }


    public function update(Request $request){
        DB::beginTransaction();
        try {
            //dd($request->all());
            $response = [];

            $validator = Validator::make($request->all(),[
                'client_name' => 'required|string|regex:/^[\pL\s\- ]+$/u',
                'client_nit' => 'required|numeric|digits:8',
                'business_name' => 'required|string|regex:/^[\pL\s\- ]+$/u',
                'business_nit' => 'required|numeric|digits:8',
                'invoice_details' => 'array',
                'invoice_details.*.sku' => 'required|exists:products,sku',
                'invoice_details.*.qty' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                $response['status'] = "FAIL";
                $response['message'] = "Validar datos";
                $response['data'] = $validator->errors();

                return response()->json($response, 400);
            }

            $invoices = Invoice::find($request->id);

            $invoices->client_name = $request->client_name;
            $invoices->client_nit = $request->client_nit;
            $invoices->business_name = $request->business_name;
            $invoices->business_nit = $request->business_nit;
            $invoices->total = 0.00;
            $invoices->iva = 0.00;
            $invoices->total_without_iva = 0.00;

            $invoices->save();

            $invoiceTotal = 0;

            InvoiceDetail::where("invoice_id", $request->id)->delete();

            foreach ($request->invoice_details as $item){
                $product = Product::where("sku", $item["sku"])->first();
                $invoiceDetails = New InvoiceDetail();

                $invoiceDetails->qty = $item["qty"];
                $invoiceDetails->product_description = $product->name;
                $invoiceDetails->product_price_sale = $product->price;
                $invoiceDetails->product_price_total = ($item["qty"] * $product->price);
                $invoiceDetails->product_id = 1;
                $invoiceDetails->invoice_id = $invoices->id;

                $invoiceDetails->save();

                if($product->stock >= $item["qty"]){
                    $product->stock = $product->stock - $item["qty"];
                    $product->save();
                } else {
                    throw new Exception("No existe stock suficiente de productos");
                }

                $invoiceTotal += ($item["qty"] * $product->price);
            }

            $invoice = Invoice::find($invoices->id);

            $invoice->total = $invoiceTotal;
            $invoice->iva = (env('IVA', 16) / 100) * $invoiceTotal;
            $invoice->total_without_iva = $invoice->total - $invoice->iva;

            $invoice->save();

            $invoices = Invoice::with('invoiceDetails')->where('id', $invoice->id)->get();



            $response['status'] = "OK";
            $response['message'] = "Factura creada";
            $response['data'] = $invoices[0];

            DB::commit();
            return response()->json($response, 201);
        } catch (Exception $e) {

            DB::rollBack();
            $response['status'] = "FAIL";
            $response['message'] = $e->getMessage();
            $response['data'] = "";

            return response()->json($response, 400);
        }
    }


    public function delete(){

    }
}
