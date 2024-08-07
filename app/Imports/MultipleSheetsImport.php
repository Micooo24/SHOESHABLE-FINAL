<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Imports\BrandsImport;
use App\Imports\CouriersImport;
use App\Imports\SuppliersImport;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultipleSheetsImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Sheet1' => new BrandsImport(),
            'Sheet2'=> new CouriersImport(),
            'Sheet3'=> new SuppliersImport(),
            'Sheet4' => new ProductsImport(),
        ];
    }
}
