<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::whereNull('parent_id')->get();
        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.category.create', compact('categories'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $category = new Category([
            'name' => $request->input('name'),
        ]);

        if ($request->input('parent_id')) {
            $parentCategory = Category::findOrFail($request->input('parent_id'));
            $category->parent()->associate($parentCategory);
        }

        $category->save();

        return redirect()->route('admin.category.index')->with('success', 'Category created successfully');
    }
    
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::where('id', '!=', $id)->get();
        return view('admin.category.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->input('name');

        if ($request->input('parent_id')) {
            $parentCategory = Category::findOrFail($request->input('parent_id'));
            $category->parent()->associate($parentCategory);
        } else {
            $category->parent()->dissociate();
        }

        $category->save();

        return redirect()->route('admin.category.index')->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Check if the category has children
        if ($category->children->count() > 0) {
            return redirect()->route('admin.category.index')->with('error', 'Cannot delete category with child categories');
        }

        // Delete the category
        $category->delete();

        return redirect()->route('admin.category.index')->with('success', 'Category deleted successfully');
    }
}
