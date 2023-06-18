<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('admin.categories.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_name' => 'required|unique:categories',

        ]);


        Category::create([
            'category_name' => $request->input('category_name')
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategoria została dodana.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $category->category_name = $request->input('category_name');
        $category->save();

        return redirect()->route('categories.index')->with('success', 'Kategoria została zaktualizowana.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategoria została usunięta.');
    }
}
