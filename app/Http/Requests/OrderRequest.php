<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
      'name' => 'string',
      'last_name' => 'string',
      'first_name' => 'string',
      'email' => 'array',
      'phone' => 'array',
      'city' => 'string|nullable',
      'country' => 'string|nullable',
      'position' => 'string|nullable',
      'partner' => 'string|nullable',

      //leads
      'title' => 'string',
      'price' => 'integer',
      'group_id' => 'integer|nullable',
      'responsible_user_id' => 'integer|nullable',
      'source_id' => 'integer|nullable',
      'pay_date' => 'string',
      'order' => 'integer|nullable',
      'integrator' => 'string|nullable',
    ];
  }
}
