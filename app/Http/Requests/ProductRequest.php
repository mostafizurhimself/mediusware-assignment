<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                    return [];
                }
            case 'POST': {
                    return [
                        'title'       => ['required', 'string', 'max:250', Rule::unique('products', 'title')],
                        'sku'         => ['required', 'string', 'max:100', Rule::unique('products', 'sku')],
                        'description' => ['required', 'string', 'max:5000'],
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    return [
                        'title'       => ['required', 'string', 'max:250', Rule::unique('products', 'title')->ignore($this->product->id)],
                        'sku'         => ['required', 'string', 'max:100', Rule::unique('products', 'sku')->ignore($this->product->id)],
                        'description' => ['required', 'string', 'max:5000'],
                    ];
                }
            default:
                break;
        }
    }
}