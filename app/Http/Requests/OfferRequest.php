<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'store_id' => ['required', 'exists:stores,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'product_url' => ['nullable', 'string', 'max:255'],
            'image_url' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'code' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'link' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['boolean'],
            'is_exclusive' => ['boolean'],
            'is_deal' => ['boolean'],
            'path' => ['nullable', 'string', 'max:255'],
            'thumbnail' => ['nullable', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255'],
            'product_name' => ['nullable', 'string', 'max:255'],
            'product_price' => ['nullable', 'numeric', 'min:0'],
            'old_price' => ['nullable', 'numeric', 'min:0'],
            'source' => ['nullable', 'string', 'max:255'],
            'external_id' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'boolean'],
        ];
    }
}
