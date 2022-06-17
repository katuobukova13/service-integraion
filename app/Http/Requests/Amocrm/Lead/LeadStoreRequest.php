<?php

namespace App\Http\Requests\Amocrm\Lead;

use Illuminate\Foundation\Http\FormRequest;

class LeadStoreRequest extends FormRequest
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
      'title' => 'required|string',
      'price' => 'integer|nullable',
      'group_id' => 'integer|nullable',
      'city' => 'string|nullable',
      'responsible_user_id' => 'integer|nullable',
      'source_id' => 'integer|nullable',
      'pay_date' => 'string',
      'order_id' => 'integer|nullable',
      'order_num' => 'integer|nullable',
      'integrator' => 'string|nullable',
    ];
  }
}
