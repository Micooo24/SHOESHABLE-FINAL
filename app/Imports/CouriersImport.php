<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Courier;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CouriersImport implements ToCollection, WithHeadingRow
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

            Courier::create([
                'courier_name'=> $row['courier_name'],
                'contact_number' => $row['contact_number'],
                'email' => $row['email'],
                'service_area' => $row['service_area'],
                'img_path' => implode(',', $imagePaths),
            ]);
        }
    }
}
