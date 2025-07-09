<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:1,2'],

            'channelId' => ['nullable', 'integer'],
            'channelName' => ['nullable', 'string', 'max:255'],
            'programId' => ['nullable', 'integer'],
            'categoryName' => ['nullable', 'string', 'max:255'],
            'categoryId' => ['nullable', 'integer'],
            'productFeedId' => ['nullable', 'integer'],
        ];
    }
}
