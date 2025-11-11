<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Tampilkan semua produk (dengan pencarian & kategori)
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');

        $query = Product::with('category');

        if ($search) {
            $query->where('prd_name', 'like', "%{$search}%");
        }

        if ($category) {
            $query->where('pdc_id', $category);
        }

        $products = $query->paginate(10);
        $categories = ProductCategory::all();

        return view('products.index', compact('products', 'categories', 'search', 'category'));
    }

    /**
     * Form tambah produk baru
     */
    public function create()
    {
        $categories = ProductCategory::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Simpan produk baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'prd_name' => 'required|string|max:255',
            'prd_price' => 'required|numeric',
            'prd_stock' => 'required|integer',
            'prd_color' => 'nullable|string|max:50',
            'prd_size' => 'nullable|string|max:10',
            'pdc_id' => 'required|exists:product_categories,id',
            'prd_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('prd_photo')) {
            $photoPath = $request->file('prd_photo')->store('products', 'public');
        }

        Product::create([
            'prd_name' => $request->prd_name,
            'prd_price' => $request->prd_price,
            'prd_stock' => $request->prd_stock,
            'prd_color' => $request->prd_color,
            'prd_size' => $request->prd_size,
            'pdc_id' => $request->pdc_id,
            'prd_photo' => $photoPath,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Form edit produk
     */
    public function edit(Product $product)
    {
        $categories = ProductCategory::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update produk
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'prd_name' => 'required|string|max:255',
            'prd_price' => 'required|numeric',
            'prd_stock' => 'required|integer',
            'prd_color' => 'nullable|string|max:50',
            'prd_size' => 'nullable|string|max:10',
            'pdc_id' => 'required|exists:product_categories,id',
            'prd_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $photoPath = $product->prd_photo;
        if ($request->hasFile('prd_photo')) {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('prd_photo')->store('products', 'public');
        }

        $product->update([
            'prd_name' => $request->prd_name,
            'prd_price' => $request->prd_price,
            'prd_stock' => $request->prd_stock,
            'prd_color' => $request->prd_color,
            'prd_size' => $request->prd_size,
            'pdc_id' => $request->pdc_id,
            'prd_photo' => $photoPath,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Hapus produk
     */
    public function destroy(Product $product)
    {
        if ($product->prd_photo && Storage::disk('public')->exists($product->prd_photo)) {
            Storage::disk('public')->delete($product->prd_photo);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
