<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Storage;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::orderBy('id', 'DESC')->get();
        return response()->json($brands);
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
        $brand = new Brand;
        $brand->brand_name = $request->brand_name;
        $brand->logo = ''; // Provide a default value
        $brand->description = $request->description;

        if ($request->hasFile('uploads')) {
            foreach ($request->file('uploads') as $file) {
                $fileName = $file->getClientOriginalName();
                $file->storeAs('public/images', $fileName);
                $brand->logo .= 'storage/images/' . $fileName . ','; // Append image path
            }
            $brand->logo = rtrim($brand->logo, ','); // Remove trailing comma
        }
        $brand->save();
        return response()->json(["success" => "Brand created successfully.", "brand" => $brand, "status" => 200]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $brand = Brand::where('id', $id)->first();
        return response()->json($brand);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json(["error" => "Brand not found.", "status" => 404]);
        }

        $brand->brand_name = $request->brand_name;
        $brand->logo = ''; // Provide a default value
        $brand->description = $request->description;

        // Handle multiple image uploads
        if ($request->hasFile('uploads')) {
            $imagePaths = [];

            foreach ($request->file('uploads') as $file) {
                $fileName = $file->getClientOriginalName();
                $file->storeAs('public/images', $fileName);
                $imagePaths[] = 'storage/images/' . $fileName;
            }

            $brand->logo = implode(',', $imagePaths);
        }

        $brand->save();

        return response()->json(["success" => "Brand updated successfully.", "brand" => $brand, "status" => 200]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brand::find($id);

        if ($brand) {
            $brand->delete();
            return response()->json(['success' => 'Brand soft deleted.', 'status' => 200]);
        }

        return response()->json(['error' => 'Brand not found.', 'status' => 404]);
    }

    public function restoreBrand($id)
    {
        $brand = Brand::onlyTrashed()->find($id);
        if ($brand) {
            $brand->restore();
            return response()->json(['message' => 'Brand restored successfully']);
        }
        return response()->json(['message' => 'Brand not found'], 404);
    }

    public function getDeletedBrands()
    {
        $brands = Brand::onlyTrashed()->get();
        \Log::info('Deleted Brands:', $brands->toArray()); // Log data for debugging
        return response()->json($brands);
    }
}
