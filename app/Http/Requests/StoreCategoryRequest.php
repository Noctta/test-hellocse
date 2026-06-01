<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\CategoryStatus;


class StoreCategoryRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name'   => ['required', 'string', 'max:255'],
            'image'  => ['required', 'image', 'max:2048'],
            'status' => ['sometimes', Rule::enum(CategoryStatus::class)],
        ];
    }
}
