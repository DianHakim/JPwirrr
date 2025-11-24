<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\StockLog;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
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

        // === URUTKAN PRODUK TERBARU DI PALING ATAS ===
        $query->orderBy('created_at', 'desc');
        // atau pakai id:
        // $query->orderBy('id', 'desc');

        $products = $query->paginate(10);
        $categories = ProductCategory::all();

        return view('products.index', compact('products', 'categories', 'search', 'category'));
    }

    public function create()
    {
        $categories = ProductCategory::all();
        return view('products.create', compact('categories'));
    }

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

        // ================================
        // CEK DUPLIKAT NAMA + UKURAN
        // ================================
        $duplicate = Product::where('prd_name', $request->prd_name)
            ->where('prd_size', $request->prd_size)
            ->where('prd_price', $request->prd_price)
            ->exists();

        if ($duplicate) {
            return back()
                ->withErrors(['duplicate' => 'Produk dengan nama, ukuran, dan harga tersebut sudah ada!'])
                ->withInput();
        }

        // Foto
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

    public function edit(Product $product)
    {
        $categories = ProductCategory::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'prd_name' => 'required|string|max:255',
            'prd_price' => 'required|numeric',
            'prd_color' => 'nullable|string|max:50',
            'prd_size' => 'nullable|string|max:10',
            'pdc_id' => 'required|exists:product_categories,id',
            'prd_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // ================================
        // CEK DUPLIKAT (Kecuali dirinya sendiri)
        // ================================
        $duplicate = Product::where('prd_name', $request->prd_name)
            ->where('prd_size', $request->prd_size)
            ->where('prd_price', $request->prd_price)
            ->where('id', '!=', $product->id)
            ->exists();

        if ($duplicate) {
            return back()
                ->withErrors(['duplicate' => 'Produk dengan nama, ukuran, dan harga tersebut sudah ada!'])
                ->withInput();
        }

        // Foto
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
            'prd_color' => $request->prd_color,
            'prd_size' => $request->prd_size,
            'pdc_id' => $request->pdc_id,
            'prd_photo' => $photoPath,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function addStockPage()
    {
        $products = Product::all();
        return view('products.addstock', compact('products'));
    }

    public function addStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'amount' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        $before = $product->prd_stock;
        $after = $before + $request->amount;

        $product->prd_stock = $after;
        $product->save();

        StockLog::create([
            'product_id' => $product->id,
            'before' => $before,
            'after' => $after,
            'description' => 'Penambahan stok ' . $request->amount . ' pcs',
        ]);

        return redirect()->route('products.index')->with('success', 'Stok berhasil ditambahkan!');
    }

    public function stockHistory()
    {
        $logs = StockLog::with('product')->orderBy('id', 'desc')->paginate(20);

        return view('products.stock_history', compact('logs'));
    }

    public function destroy(Product $product)
    {
        if ($product->prd_photo && Storage::disk('public')->exists($product->prd_photo)) {
            Storage::disk('public')->delete($product->prd_photo);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
