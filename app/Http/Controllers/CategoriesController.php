<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    public function getCategories()
    {
        $categories = Category::all();
        return view('admin.categories', ['categories' => $categories]);
    }
    public function createCategoryView()
    {
        $categories = Category::all();
        return view('admin.category-create', ['categories' => $categories]);
    }
    public function createCategory(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
        ]);

        Category::create([
            'name' => $request->input('name'),
            'product_type' => $request->input('product_type', 'other'),
            'slug' => Str::slug($request->input('name'), '-'),
            'parent_id' => $request->input('parent_id'),
        ]);

        return redirect()->route('admin.categories');
    }
    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $productsForDelete = $category->products();

        foreach ($productsForDelete->pluck('id') as $value) {
            DB::table('cart')->where('pid', $value)->delete();
            DB::table('orders')->where('pid', $value)->delete();
        }
        $productsForDelete->delete();
        $category->delete();

        return redirect()->route('admin.categories');
    }
    public function editCategoryById($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::all();

        return view('admin.category-edit', ['category' => $category, 'categories' => $categories]);
    }
    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($id)],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->input('name'),
            'parent_id' => $request->input('parent_id'),
        ]);

        return redirect()->route('admin.categories');
    }
}
