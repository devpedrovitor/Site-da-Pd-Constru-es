<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\CategoryCollection;

class CategoryController extends Controller
{
    public function index(): CategoryCollection | JsonResponse
    {
        try {
            $categories = Category::orderBy('name')->get();

            return new CategoryCollection($categories);
        } catch (Throwable $th) {
            report($th);

            return response()->json(['status' => 'error'], 500);
        }
    }
}
