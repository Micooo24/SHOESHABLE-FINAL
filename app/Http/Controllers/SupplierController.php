<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DataTables;
use Storage;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::orderBy('id', 'DESC')->get();
        return response()->json($suppliers);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
   {
    $supplier = new Supplier;
    $supplier ->name = $request->name;
    $supplier ->email = $request->email;
    $supplier ->contact_number = $request->contact_number;
    $supplier ->img_path = ''; // Provide a default value

    if ($request->hasFile('uploads')) {
        foreach ($request->file('uploads') as $file) {
            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/images', $fileName);
            $supplier->img_path .= 'storage/images/' . $fileName . ','; // Append image path
        }
        $supplier ->img_path = rtrim($supplier->img_path, ','); // Remove trailing comma
   }

    $supplier ->save();

    return response()->json(["success" => "Supplier created successfully.", "supplier" => $supplier , "status" => 200]);
   }
    /**
     * Display the specified resource.
     */
   public function show(string $id)
   {
        $supplier = Supplier::where('id', $id)->first();
        return response()->json($supplier );
   }

    /**
     * Show the form for editing the specified resource.
     */
   public function edit(string $id)
   {
        //
   }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, string $id)
   {
   $supplier = Supplier::find($id);

   if (!$supplier ) {
       return response()->json(["error" => "Supplier not found.", "status" => 404]);
   }

    $supplier ->name = $request->name;
    $supplier ->email = $request->email;
    $supplier ->contact_number= $request->contact_number;

    // Handle multiple image uploads
    if ($request->hasFile('uploads')) {
        // Optionally: Remove old images if they should be replaced
        // $item->images()->delete();

        $imagePaths = [];

        foreach ($request->file('uploads') as $file) {
            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/images', $fileName);
            // Store the image path in the database
            $imagePaths[] = 'storage/images/' . $fileName;
        }

        // Store the concatenated image paths in the database
        $supplier ->img_path = implode(',', $imagePaths);
    }

    $supplier->save();

    return response()->json(["success" => "Supplier updated successfully.", "supplier " => $supplier, "status" => 200]);
   }

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(string $id)
   {


        if (Supplier::find($id)) {
            Supplier::destroy($id);
            $data = array('success' => 'deleted', 'code' => 200);
            return response()->json($data);
        }
        $data = array('error' => 'Supplier not deleted', 'code' => 400);
        return response()->json($data);
   }

   public function restoreSupplier($id)
   {
       $supplier = Supplier::onlyTrashed()->find($id);
       if ($supplier) {
           $supplier->restore();
           return response()->json(['message' => 'Supplier restored successfully']);
       }
       return response()->json(['message' => 'Supplier not found'], 404);
   }

   public function getDeletedSuppliers()
   {
       $suppliers = Supplier::onlyTrashed()->whereNotNull('deleted_at')->get();
       return response()->json($suppliers);
   }


}
