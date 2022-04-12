<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetcourseUserRequest extends FormRequest
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
    switch ($this->getMethod()) {
      case 'POST':
        /*
         * @OA\Schema(
         *   schema="GetcourseUsersPostRequestBody",
         *   required={"email", "source_id"},
         *   @OA\Property(property="email", type="string", example="test1235432@mail.ru"),
         *   @OA\Property(property="phone", type="string", example="+79991234567"),
         *   @OA\Property(property="source_id", type="number", example="6"),
         *   @OA\Property(property="first_name", type="string", example="Test"),
         *   @OA\Property(property="last_name", type="string", example="Testov"),
         * )
         */
        return [
          'email' => 'email:rfc|unique:users,email|required',
          'phone' => 'string|nullable',
          'source_id' => 'exists:users_sources,id|required',
          'first_name' => 'string|nullable',
          'last_name' => 'string|nullable',
          'city' => 'string',
          'country' => 'string',
        ];
      case 'PUT':
        /*
         * @OA\Schema(
         *   schema="GetcourseUsersPutRequestBody",
         *   @OA\Property(property="phone", type="string", example="+79999999999"),
         *   @OA\Property(property="source_id", type="number", example="6"),
         *   @OA\Property(property="first_name", type="string", example="Vasya"),
         *   @OA\Property(property="last_name", type="string", example="Pupkin"),
         * )
         */

        return [
          'phone' => 'string|nullable',
          'source_id' => 'exists:users_sources,id',
          'first_name' => 'string|nullable',
          'last_name' => 'string|nullable',
          'city' => 'string',
          'country' => 'string',
        ];
      default:
        return [];
    }
  }
}
