<?php

namespace App\Http\Requests\Amocrm\Lead;

use Illuminate\Foundation\Http\FormRequest;

class LeadIndexRequest extends FormRequest
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
      'page' => 'int',
      'limit' => 'int',
      'with.contacts' => 'array|[]',
      'filter.id' => 'array|nullable',
      'filter.name' => 'array|nullable',
      'filter.pay_date' => 'array|nullable',
      'filter.price' => 'array|nullable',
      'filter.city' => 'array|nullable',
      'filter.country' => 'array|nullable',
      'filter.partner' => 'array|nullable',
      'filter.responsible_user_id' => 'array|nullable',
    ];
  }
}
