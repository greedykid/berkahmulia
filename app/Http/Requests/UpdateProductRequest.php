<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')->id;

        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $productId,
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:ready,sold_out,po',
            'description' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer|exists:product_images,id',
            'variants.*.size' => 'nullable|string|max:100',
            'variants.*.color' => 'nullable|string|max:100',
            'variants.*.stock' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'name.required' => 'Nama produk wajib diisi.',
            'price.required' => 'Harga produk wajib diisi.',
            'status.required' => 'Status produk wajib dipilih.',
            'sku.unique' => 'SKU sudah digunakan oleh produk lain.',
            'images.*.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
