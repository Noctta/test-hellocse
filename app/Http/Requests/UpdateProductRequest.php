<?php

namespace App\Http\Requests;

use App\Enums\ProductStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name'        => ['sometimes', 'string', 'max:255'],
            'price'       => ['sometimes', 'numeric', 'min:0'],
            'image'       => ['sometimes', 'image', 'max:2048'],
            'status'      => ['sometimes', Rule::enum(ProductStatus::class)],
            'category_id' => ['sometimes', 'exists:categories,id'],
        ];
    }
}
