<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
      return [
        //lead
        'id' => 'int',
        'title' => 'string',
        'price' => 'integer',
        'group_id' => 'integer|nullable',
        'responsible_user_id' => 'integer|nullable',
        'source_id' => 'integer|nullable',
        'pay_date' => 'string',
        'order' => 'integer|nullable',
        'integrator' => 'string|nullable',

        //query
        'page' => 'int|nullable',
        'limit' => 'int|nullable',
        'with.leads' => 'array|[]',
        'filter.id' => 'integer|nullable',
        'filter.name' => 'string|nullable',
        'filter.email' => 'array|nullable',
        'filter.phone' => 'array|nullable',
        'filter.city' => 'string|nullable',
        'filter.country' => 'string|nullable',
        'filter.position' => 'string|nullable',
        'filter.partner' => 'string|nullable',
        'filter.responsible_user_id' => 'string|nullable',
      ];
    }
}
