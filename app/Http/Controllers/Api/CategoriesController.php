<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Transformers\CategoryTransformer;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return $this->response->collection($categories, new CategoryTransformer());
    }
}
