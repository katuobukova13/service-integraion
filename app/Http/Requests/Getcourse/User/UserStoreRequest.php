<?php

namespace App\Http\Requests\Getcourse\User;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class UserStoreRequest extends FormRequest
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
      'email' => 'email|required',
      'phone' => 'string',
      'first_name' => 'string',
      'last_name' => 'string',
      'city' => 'string',
      'country' => 'string',
      'group' => 'array'
    ];
  }
}
