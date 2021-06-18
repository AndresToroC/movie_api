<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Category;

class CategoryController extends Controller
{
    public function __construct() {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $categories = Category::paginate(20);

        return response()->json([
            'categories' => $categories,
            'message' => 'Lists categories'
        ], 206);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255|unique:categories'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $category = Category::create($request->all());

        return response()->json([
            'category' => $category,
            'message' => 'Category created successfully'
        ], 201);
    }

    public function show(Category $category)
    {
        return response()->json([
            'category' => $category,
            'message' => 'Show category'
        ], 200);
    }

    public function edit(Category $category)
    {
        return response()->json([
            'category' => $category,
            'message' => 'Edit category'
        ], 200);
    }

    public function update(Request $request, Category $category)
    {
        $rules = [
            'name' => 'required|max:255|unique:categories,name,'.$category->id
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $category->update($request->all());

        return response()->json([
            'category' => $category,
            'message' => 'Category edited successfully'
        ], 200);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully'
        ], 204);
    }
}
