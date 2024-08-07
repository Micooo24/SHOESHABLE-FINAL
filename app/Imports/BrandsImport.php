<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Brand;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandsImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $imagePaths = [];

            if ($row['logo']) {
                $imageNames = explode(',', $row['logo']);

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

            Brand::create([
                'brand_name'=> $row['brand_name'],
                'description' => $row['description'],
                'logo' => implode(',', $imagePaths),
            ]);
        }
    }
}
