<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Spatie\Searchable\Search;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $searchResults = (new Search())
            ->registerModel(Product::class, ['name', 'description'])
            ->perform($query);
        $products = $searchResults->map(function ($result) {
            return [
                'id' => $result->searchable->id,
                'name' => $result->searchable->name,
                'description' => $result->searchable->description,
            ];
        });
        return response()->json(['data' => $products]);
    }
}
