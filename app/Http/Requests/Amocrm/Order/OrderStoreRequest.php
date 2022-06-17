<?php

namespace App\Http\Requests\Amocrm\Order;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
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
      'last_name' => 'required|string',
      'first_name' => 'required|string',
      'emails' => 'required|array',
      'phones' => 'required|array',
      'city' => 'string|nullable',
      'country' => 'string|nullable',
      'position' => 'string|nullable',
      'partner' => 'string|nullable',

      //leads
      'title' => 'required|string',
      'price' => 'integer|nullable',
      'group_id' => 'integer|nullable',
      'responsible_user_id' => 'integer|nullable',
      'source_id' => 'integer|nullable',
      'pay_date' => 'string',
      'order_id' => 'integer|nullable',
      'order_num' => 'integer|nullable',
      'integrator' => 'string|nullable',
    ];
  }
}
