<?php

namespace App\Http\Requests;


use App\Enums\ProductStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'price'       => ['required', 'numeric', 'min:0'],
            'image'       => ['required', 'image', 'max:2048'],
            'status'      => ['sometimes', Rule::enum(ProductStatus::class)],
            'category_id' => ['required', 'exists:categories,id'],
        ];
    }
}
