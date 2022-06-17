<?php

namespace App\Http\Requests\Amocrm\Contact;

use Illuminate\Foundation\Http\FormRequest;

class ContactIndexRequest extends FormRequest
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
      'page' => 'int|nullable',
      'limit' => 'int|nullable',
      'with.leads' => 'array|[]',
      'filter.id' => 'array|nullable',
      'filter.name' => 'array|nullable',
      'filter.emails' => 'array|nullable',
      'filter.phones' => 'array|nullable',
      'filter.city' => 'array|nullable',
      'filter.country' => 'array|nullable',
      'filter.position' => 'array|nullable',
      'filter.partner' => 'array|nullable',
      'filter.responsible_user_id' => 'array|nullable',
    ];
  }
}
