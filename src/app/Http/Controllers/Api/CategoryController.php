<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index() { return response()->json(Category::all()); }
    public function store(Request $request) {
        $data = $request->validate([
            'name'=>'required|string|max:255',
            'type'=>'required|in:income,expense'
        ]);
        $category = Category::create($data);
        return response()->json($category,201);
    }
    public function show($id){ return response()->json(Category::findOrFail($id)); }
    public function update(Request $request, $id){
        $category = Category::findOrFail($id);
        $data = $request->validate([
            'name'=>'sometimes|string|max:255',
            'type'=>'sometimes|in:income,expense'
        ]);
        $category->update($data);
        return response()->json($category);
    }
    public function destroy($id){
        Category::findOrFail($id)->delete();
        return response()->json(['message'=>'Category deleted']);
    }
}
