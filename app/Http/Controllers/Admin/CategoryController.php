<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount('products');
        
        // Sorting
        $sortableColumns = ['name', 'products_count', 'created_at'];
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');

        if (!in_array($sort, $sortableColumns)) {
            $sort = 'created_at';
        }
        if (!in_array(strtolower($direction), ['asc', 'desc'])) {
            $direction = 'desc';
        }

        $categories = $query->orderBy($sort, $direction)->get();
        
        $totalCategories = Category::count();
        $activeCategories = Category::has('products')->count();
        
        $mostActiveCategory = Category::withCount('products')->orderBy('products_count', 'desc')->first();
        $mostActiveName = $mostActiveCategory && $mostActiveCategory->products_count > 0 
            ? $mostActiveCategory->name . ' (' . $mostActiveCategory->products_count . ')' 
            : '-';
            
        $totalProducts = \App\Models\Product::count();

        return view('admin.categories.index', compact(
            'categories', 
            'totalCategories', 
            'activeCategories', 
            'mostActiveName', 
            'totalProducts'
        ));
    }

    /**
     * Store new category
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imagePath = \App\Helpers\ImageHelper::storeCompressed($request->file('image'), 'categories', 500, 80);
        }

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image_path' => $imagePath,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    /**
     * Update existing category
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = $category->image_path;

        if ($request->has('delete_image') && $request->delete_image) {
            if ($category->image_path) {
                Storage::disk('public')->delete($category->image_path);
                $imagePath = null;
            }
        }

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Delete old file if exists
            if ($category->image_path) {
                Storage::disk('public')->delete($category->image_path);
            }
            $imagePath = \App\Helpers\ImageHelper::storeCompressed($request->file('image'), 'categories', 500, 80);
        }

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image_path' => $imagePath,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Delete category
     */
    public function destroy(Category $category)
    {
        // Check if there are products linked to this category
        $productsCount = $category->products()->count();
        if ($productsCount > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena memiliki ' . $productsCount . ' produk terkait. Silakan hapus atau pindahkan produk tersebut terlebih dahulu.');
        }

        // Delete image file if exists
        if ($category->image_path) {
            Storage::disk('public')->delete($category->image_path);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}
