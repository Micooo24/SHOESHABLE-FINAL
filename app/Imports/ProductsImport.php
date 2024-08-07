<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductsImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $imagePaths = [];

            if ($row['img_path']) {
                $imageNames = explode(',', $row['img_path']);

                foreach ($imageNames as $imageName) {
                    $imageName = trim($imageName);
                    $filePath = 'public/images/' . $imageName;

                    // Assuming you have the image files stored locally in the same path as the CSV/Excel references
                    if (Storage::exists($filePath)) {
                        $newFileName = $imageName;
                        Storage::copy($filePath, 'public/images/' . $newFileName);
                        $imagePaths[] = 'storage/images/' . $newFileName;
                    }
                }
            }

            // Create Product
            $product = Product::create([
                'name' => $row['name'],
                'brand_id' => $row['brand_id'],
                'supplier_id' => $row['supplier_id'],
                'description' => $row['description'],
                'cost' => $row['cost'],
                'img_path' => implode(',', $imagePaths),
            ]);

            // Create Stock
            if ($product) {
                Stock::create([
                    'product_id' => $product->id,
                    'quantity' => $row['quantity'], // Assuming 'quantity' column exists in your Excel file
                ]);
            }
        }
    }
}
