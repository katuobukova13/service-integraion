<?php

namespace App\Http\Requests\Amocrm\Contact;

use Illuminate\Foundation\Http\FormRequest;

class ContactStoreRequest extends FormRequest
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
      'name' => 'string',
      'last_name' => 'required|string',
      'first_name' => 'required|string',
      'emails' => 'required|array',
      'phones' => 'required|array',
      'city' => 'string|nullable',
      'country' => 'string|nullable',
      'position' => 'string|nullable',
      'responsible_user_id' => 'integer|nullable',
      'partner' => 'string|nullable',
    ];
  }
}
