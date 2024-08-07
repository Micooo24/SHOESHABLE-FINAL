<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

use App\Imports\SuppliersImport;
use App\Imports\BrandsImport;
use App\Imports\CouriersImport;
use App\Imports\ProductsImport;
use App\Imports\MultipleSheetsImport;

class ImportController extends Controller
{
    public function importMultipleSheet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'importMultipleSheet' => 'required|file|mimes:xlsx,xls',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Excel::import(new MultipleSheetsImport, $request->file('importMultipleSheet'));

            return response()->json([
                'status' => 'success',
                'message' => 'Multiple Sheets imported successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to import sheets. Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function supplierImport(Request $request)
    {
        $request->validate([
            'importFile' => ['required', 'file', 'mimes:xlsx,xls']
        ]);

        try {
            Excel::import(new SuppliersImport, $request->file('importFile'));
            return response()->json(['message' => 'Suppliers imported successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to import suppliers', 'details' => $e->getMessage()], 500);
        }
    }

    public function productImport(Request $request)
    {
        $request->validate([
            'importFile' => ['required', 'file', 'mimes:xlsx,xls']
        ]);

        try {
            Excel::import(new ProductsImport, $request->file('importFile'));
            return response()->json(['message' => 'Products imported successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to import products', 'details' => $e->getMessage()], 500);
        }
    }


    public function courierImport(Request $request)
    {
        $request->validate([
            'importFile' => ['required', 'file', 'mimes:xlsx,xls']
        ]);

        try {
            Excel::import(new CouriersImport, $request->file('importFile'));
            return response()->json(['message' => 'Couriers imported successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to import couriers', 'details' => $e->getMessage()], 500);
        }
    }

    public function brandImport(Request $request)
    {
        $request->validate([
            'importFile' => ['required', 'file', 'mimes:xlsx,xls']
        ]);

        try {
            Excel::import(new BrandsImport, $request->file('importFile'));
            return response()->json(['message' => 'Brands imported successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to import brands', 'details' => $e->getMessage()], 500);
        }
    }
}
