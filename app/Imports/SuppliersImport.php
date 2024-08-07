<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Supplier;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SuppliersImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            $supplier = new Supplier;
            $supplier->name = $row['name'];
            $supplier->email = $row['email'];
            $supplier->contact_number = $row['contact_number'];
            $supplier->img_path = '';

            if ($row['img_path']) {
                $imageNames = explode(',', $row['img_path']);
                $imagePaths = [];

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

                $supplier->img_path = implode(',', $imagePaths);
            }

            $supplier->save();
        }
    }
}
