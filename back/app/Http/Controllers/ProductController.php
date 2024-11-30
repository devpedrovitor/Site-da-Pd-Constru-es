<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ProductCollection;

class ProductController extends Controller
{
    public function index(Request $request): ProductCollection | JsonResponse
    {
        try {
            $products = Product::query()
                ->whereHas('categories', function ($query) use ($request) {
                    $query->when($request->query('category_id'), function ($query) use ($request) {
                        $query->where('categories.id', $request->query('category_id'));
                    });
                })
                ->with(['categories'])
                ->withCount(['evaluations'])
                ->orderBy('name')
                ->get();

            return new ProductCollection($products);
        } catch (Throwable $th) {
            report($th);

            return response()->json(['status' => 'error'], 500);
        }
    }
}
