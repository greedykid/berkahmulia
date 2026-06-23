<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\ImageHelper;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display listing of products with filters
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Product::with(['category', 'images', 'variants']);

        // Search Filter
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('products.name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('products.sku', 'like', '%' . $searchTerm . '%');
            });
        }

        // Category Filter
        if ($request->has('category') && !empty($request->category)) {
            $query->where('products.category_id', $request->category);
        }

        // Status Filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('products.status', $request->status);
        }

        $totalProducts = Product::count();
        $readyProducts = Product::where('status', 'ready')->count();
        $poProducts = Product::where('status', 'po')->count();
        $soldOutProducts = Product::where('status', 'sold_out')->count();

        // Sorting
        $sortableColumns = ['name', 'category', 'price', 'status', 'created_at'];
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        if (!in_array($sort, $sortableColumns)) {
            $sort = 'created_at';
        }
        if (!in_array(strtolower($direction), ['asc', 'desc'])) {
            $direction = 'desc';
        }

        if ($sort === 'category') {
            $query->join('categories', 'products.category_id', '=', 'categories.id')
                  ->select('products.*')
                  ->orderBy('categories.name', $direction);
        } else {
            $query->select('products.*')->orderBy('products.' . $sort, $direction);
        }

        $products = $query->paginate(10)->withQueryString();

        return view('admin.products.index', compact(
            'products', 
            'categories', 
            'totalProducts', 
            'readyProducts', 
            'poProducts', 
            'soldOutProducts'
        ));
    }

    public function create()
    {
        return redirect()->route('admin.products.index', ['add' => 1]);
    }

    /**
     * Store new product
     */
    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();

        // Create product
        $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(), // Append time to make slug guaranteed unique
            'sku' => $request->sku,
            'price' => $request->price,
            'status' => $request->status,
            'description' => $request->description,
            'is_popular' => $request->boolean('is_popular', false),
        ]);

        // Upload images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                if ($imageFile->isValid()) {
                    $path = ImageHelper::storeCompressed($imageFile, 'products');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                    ]);
                }
            }
        }

        // Save variants
        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                if (!empty($variantData['size']) || !empty($variantData['color'])) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'size' => $variantData['size'],
                        'color' => $variantData['color'],
                        'stock' => $variantData['stock'],
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk baru berhasil dibuat!');
    }

    /**
     * Show edit form
     */
    public function edit(Product $product)
    {
        return redirect()->route('admin.products.index', ['edit' => $product->id]);
    }

    /**
     * Update product
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        // Update basic details
        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . $product->id, // Re-generate safe slug
            'sku' => $request->sku,
            'price' => $request->price,
            'status' => $request->status,
            'description' => $request->description,
            'is_popular' => $request->boolean('is_popular', false),
        ]);

        // Delete specific images if requested
        if ($request->has('delete_images') && is_array($request->delete_images)) {
            foreach ($request->delete_images as $imageId) {
                $image = ProductImage::find($imageId);
                if ($image && $image->product_id === $product->id) {
                    // Remove file from disk
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }
        }

        // Upload new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                if ($imageFile->isValid()) {
                    $path = ImageHelper::storeCompressed($imageFile, 'products');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                    ]);
                }
            }
        }

        // Re-sync variants: Delete existing ones and create the new set
        $product->variants()->delete();
        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                if (!empty($variantData['size']) || !empty($variantData['color'])) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'size' => $variantData['size'],
                        'color' => $variantData['color'],
                        'stock' => $variantData['stock'],
                    ]);
                }
            }
        }

        $redirectUrl = $request->input('redirect_url');
        if ($redirectUrl && filter_var($redirectUrl, FILTER_VALIDATE_URL)) {
            return redirect($redirectUrl)->with('success', 'Produk berhasil diperbarui!');
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Delete product
     */
    public function destroy(Request $request, Product $product)
    {
        // Delete product (SoftDeletes is handled by Eloquent; database cascade will trigger on permanent delete)
        $product->delete();

        $redirectUrl = $request->input('redirect_url');
        if ($redirectUrl && filter_var($redirectUrl, FILTER_VALIDATE_URL)) {
            return redirect($redirectUrl)->with('success', 'Produk berhasil dihapus!');
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }

    /**
     * Export products as printable PDF-ready HTML page
     */
    public function exportPdf()
    {
        $products = Product::with(['category', 'variants', 'images'])
            ->where('status', 'ready')
            ->orderBy('category_id')
            ->orderBy('name')
            ->get();

        return view('admin.products.export-pdf', compact('products'));
    }

    /**
     * Export products to CSV
     */
    public function exportCsv(Request $request)
    {
        $query = Product::with(['category', 'variants']);

        // Apply same filters as index
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('sku', 'like', '%' . $searchTerm . '%');
            });
        }
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category_id', $request->category);
        }
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $products = $query->orderBy('created_at', 'desc')->get();

        $filename = 'produk_berkah_mulia_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');
            // BOM for UTF-8 Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header row
            fputcsv($file, ['nama', 'sku', 'kategori', 'harga', 'status', 'deskripsi', 'ukuran', 'warna', 'stok']);

            foreach ($products as $product) {
                $sizes = $product->variants->pluck('size')->filter()->implode('|');
                $colors = $product->variants->pluck('color')->filter()->implode('|');
                $stocks = $product->variants->pluck('stock')->implode('|');

                fputcsv($file, [
                    $product->name,
                    $product->sku ?? '',
                    $product->category->name ?? '',
                    $product->price,
                    $product->status,
                    $product->description ?? '',
                    $sizes,
                    $colors,
                    $stocks,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import products from CSV
     */
    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();

        $handle = fopen($path, 'r');
        if (!$handle) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Gagal membaca file CSV.');
        }

        // Read header row
        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return redirect()->route('admin.products.index')
                ->with('error', 'File CSV kosong atau format tidak valid.');
        }

        // Normalize header (trim BOM and whitespace)
        $header = array_map(function($h) {
            return strtolower(trim(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $h)));
        }, $header);

        // Validate required columns
        $requiredColumns = ['nama', 'kategori', 'harga', 'status'];
        foreach ($requiredColumns as $col) {
            if (!in_array($col, $header)) {
                fclose($handle);
                return redirect()->route('admin.products.index')
                    ->with('error', "Kolom wajib \"$col\" tidak ditemukan di file CSV. Kolom yang dibutuhkan: nama, sku, kategori, harga, status, deskripsi, ukuran, warna, stok");
            }
        }

        $imported = 0;
        $skipped = 0;
        $errors = [];
        $rowNum = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;
            
            // Skip empty rows
            if (empty(array_filter($row))) continue;

            // Map row to associative array
            $data = [];
            foreach ($header as $index => $key) {
                $data[$key] = $row[$index] ?? '';
            }

            // Validate required fields
            if (empty($data['nama']) || empty($data['kategori']) || empty($data['harga']) || empty($data['status'])) {
                $skipped++;
                $errors[] = "Baris $rowNum: Data wajib (nama/kategori/harga/status) kosong.";
                continue;
            }

            // Validate status
            if (!in_array($data['status'], ['ready', 'po', 'sold_out'])) {
                $skipped++;
                $errors[] = "Baris $rowNum: Status \"{$data['status']}\" tidak valid. Gunakan: ready, po, sold_out.";
                continue;
            }

            // Find or skip category
            $category = Category::where('name', $data['kategori'])->first();
            if (!$category) {
                $skipped++;
                $errors[] = "Baris $rowNum: Kategori \"{$data['kategori']}\" tidak ditemukan.";
                continue;
            }

            // Check for duplicate SKU
            $sku = !empty($data['sku']) ? $data['sku'] : null;
            if ($sku) {
                $existingProduct = Product::where('sku', $sku)->first();
                if ($existingProduct) {
                    // Update existing product
                    $existingProduct->update([
                        'name' => $data['nama'],
                        'category_id' => $category->id,
                        'price' => (float) $data['harga'],
                        'status' => $data['status'],
                        'description' => $data['deskripsi'] ?? $existingProduct->description,
                    ]);

                    // Update variants if provided
                    if (!empty($data['ukuran']) || !empty($data['stok'])) {
                        $existingProduct->variants()->delete();
                        $this->createVariantsFromCsv($existingProduct, $data);
                    }

                    $imported++;
                    continue;
                }
            }

            // Create new product
            $product = Product::create([
                'category_id' => $category->id,
                'name' => $data['nama'],
                'slug' => Str::slug($data['nama']) . '-' . time() . '-' . $rowNum,
                'sku' => $sku,
                'price' => (float) $data['harga'],
                'status' => $data['status'],
                'description' => $data['deskripsi'] ?? null,
            ]);

            // Create variants
            if (!empty($data['ukuran']) || !empty($data['stok'])) {
                $this->createVariantsFromCsv($product, $data);
            }

            $imported++;
        }

        fclose($handle);

        $message = "Import selesai: $imported produk berhasil diproses.";
        if ($skipped > 0) {
            $message .= " $skipped baris dilewati.";
        }

        // Clear product cache
        \Illuminate\Support\Facades\Cache::forget('featured_products');

        if (!empty($errors)) {
            return redirect()->route('admin.products.index')
                ->with('success', $message)
                ->with('import_errors', array_slice($errors, 0, 10));
        }

        return redirect()->route('admin.products.index')
            ->with('success', $message);
    }

    /**
     * Bulk update status
     */
    public function bulkStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|in:ready,po,sold_out',
        ]);

        if ($request->input('select_all')) {
            $query = Product::query();
            if ($request->search) $query->where('name', 'like', '%'.$request->search.'%');
            if ($request->category) $query->where('category_id', $request->category);
            if ($request->filter_status) $query->where('status', $request->filter_status);
            $count = $query->update(['status' => $request->status]);
        } else {
            $request->validate(['ids' => 'required|array', 'ids.*' => 'integer']);
            Product::whereIn('id', $request->ids)->update(['status' => $request->status]);
            $count = count($request->ids);
        }

        \Illuminate\Support\Facades\Cache::forget('featured_products');

        return redirect()->route('admin.products.index')
            ->with('success', "$count produk berhasil diubah statusnya.");
    }

    /**
     * Bulk delete products
     */
    public function bulkDelete(Request $request)
    {
        if ($request->input('select_all')) {
            $products = Product::all();
        } else {
            $request->validate(['ids' => 'required|array', 'ids.*' => 'integer']);
            $products = Product::whereIn('id', $request->ids)->get();
        }

        $count = $products->count();
        foreach ($products as $product) {
            foreach ($product->images as $img) {
                Storage::disk('public')->delete($img->image_path);
            }
            $product->delete();
        }
        \Illuminate\Support\Facades\Cache::forget('featured_products');

        return redirect()->route('admin.products.index')
            ->with('success', "$count produk berhasil dihapus.");
    }

    /**
     * Toggle the popular status of a product
     */
    public function togglePopular(Product $product)
    {
        $product->update([
            'is_popular' => !$product->is_popular
        ]);

        return redirect()->back()
            ->with('success', 'Status populer produk berhasil diperbarui!');
    }

    /**
     * Create product variants from CSV row data
     */
    private function createVariantsFromCsv(Product $product, array $data)
    {
        $sizes = !empty($data['ukuran']) ? explode('|', $data['ukuran']) : [];
        $colors = !empty($data['warna']) ? explode('|', $data['warna']) : [];
        $stocks = !empty($data['stok']) ? explode('|', $data['stok']) : [];

        $maxCount = max(count($sizes), count($colors), count($stocks), 1);

        for ($i = 0; $i < $maxCount; $i++) {
            ProductVariant::create([
                'product_id' => $product->id,
                'size' => $sizes[$i] ?? ($sizes[0] ?? null),
                'color' => $colors[$i] ?? ($colors[0] ?? null),
                'stock' => (int) ($stocks[$i] ?? ($stocks[0] ?? 0)),
            ]);
        }
    }
}
