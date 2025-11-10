<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // ambil data kategori, kalau ada pencarian maka filter
        $categories = ProductCategory::when($search, function($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })
        ->orderBy('id', 'desc')
        ->get();

        return view('productcategory.index', compact('categories'));
    }

    public function create()
    {
        return view('productcategory.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        ProductCategory::create([
            'name' => $request->name
        ]);

        return redirect()->route('productcategory.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $category = ProductCategory::findOrFail($id);
        return view('productcategory.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category = ProductCategory::findOrFail($id);
        $category->update(['name' => $request->name]);

        return redirect()->route('productcategory.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $category = ProductCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('productcategory.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
