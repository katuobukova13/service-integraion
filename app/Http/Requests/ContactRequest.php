<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
        //contacts
        'id' => 'int',
        'name' => 'string',
        'last_name' => 'string',
        'first_name' => 'string',
        'email' => 'array',
        'phone' => 'array',
        'city' => 'string|nullable',
        'country' => 'string|nullable',
        'position' => 'string|nullable',
        'responsible_user_id' => 'integer|nullable',
        'partner' => 'string|nullable',

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
