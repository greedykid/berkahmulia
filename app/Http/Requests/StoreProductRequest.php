<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100|unique:products,sku,NULL,id,deleted_at,NULL',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:ready,sold_out,po',
            'description' => 'nullable|string',
            'is_popular' => 'nullable|boolean',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,bmp,tiff,heic,heif|max:10240',
            'variants.*.size' => 'nullable|string|max:100',
            'variants.*.color' => 'nullable|string|max:100',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,bmp,tiff,heic,heif|max:10240',
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
            'images.*.max' => 'Ukuran gambar maksimal 10MB.',
            'images.*.mimes' => 'Format gambar harus jpeg, png, jpg, webp, gif, bmp, tiff, heic, heif.',
        ];
    }
}
