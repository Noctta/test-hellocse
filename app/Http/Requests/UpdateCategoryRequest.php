<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\CategoryStatus;

class UpdateCategoryRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name'   => ['sometimes', 'string', 'max:255'],
            'image'  => ['sometimes', 'image', 'max:2048'],
            'status' => ['sometimes', Rule::enum(CategoryStatus::class)],
        ];
    }
}
