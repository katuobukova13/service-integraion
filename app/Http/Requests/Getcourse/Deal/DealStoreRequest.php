<?php

namespace App\Http\Requests\Getcourse\Deal;

use Illuminate\Foundation\Http\FormRequest;

class DealStoreRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */

  public static array $paymentTypes = ['2CO', 'ALFA', 'BILL', 'CARD', 'CARD_TERMINAL', 'CASH', 'cloud_payments',
    'cloud_payments_kz', 'fondy', 'hutki_grosh', 'interkassa', 'INTERNAL', 'justclick', 'kvit', 'OTHER', 'payanyway',
    'PAYPAL', 'perfect_money', 'PERFECTMONEY', 'QIWI', 'qiwi_kassa', 'QUICKTRANSFER', 'RBK', 'rbkmoney', 'rbkmoney_new',
    'ROBOKASSA', 'SBER', 'sberbank', 'tinkoff', 'tinkoffcredit', 'VIRTUAL', 'walletone', 'wayforpay', 'WEBMONEY',
    'yandex_kassa', 'YANDEXMONEY', 'ZPAYMENT', 'prodamus', 'ebanx', 'swedbank'];

  public static array $paymentStatuses = ['expected', 'accepted', 'returned', 'tobalance', 'frombalance',
    'returned_to_balance'];

  public static array $currencies = ['RUB', 'USD', 'EUR', 'GBP', 'BYR', 'BYN', 'KZT', 'UAH', 'AUD', 'DKK', 'CHF',
    'SEK', 'ZAR', 'AMD', 'RON', 'BRL', 'ILS', 'MYR', 'SGD', 'KGS', 'CAD', 'MXN', 'JPY', 'UZS'];

  public static array $statuses = ['new', 'payed', 'cancelled', 'false', 'in_work', 'payment_waiting',
    'part_payed', 'waiting_for_return', 'not_confirmed', 'pending'];

  public static array $payedStatuses = ['да', 'нет'];

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
    /*
       * @OA\Schema(
       *   required={"email","title"},
       *   @OA\Property(property="title", type="string", example="Пакет онлайн-курсов «Интеллектуальное развитие детей»"),
       *   @OA\Property(property="quantity", type="number", example="1"),
       *   @OA\Property(property="cost", type="number", example="500"),
       *   @OA\Property(property="status", type="string", example="in_work"),
       *   @OA\Property(property="manager_email", type="string", example="mgr01@mail.ru"),
       *   @OA\Property(property="is_paid", type="string", example="нет"),
       *   @OA\Property(property="comment", type="string", example="Комментарий"),
       *   @OA\Property(property="payment_type", type="string", example="sberbank"),
       *   @OA\Property(property="payment_status", type="string", example="tobalance"),
       *   @OA\Property(property="currency", type="string", example="RUB"),
       * )
       */
    return [
      "email" => "email|required",
      "title" => "string|required",
      "number" => "integer",
      "quantity" => "integer",
      'cost' => 'numeric',
      'status' => 'in:' . implode(',', self::$statuses),
      'manager_email' => 'email:rfc',
      'is_paid' => 'in:' . implode(',', self::$payedStatuses),
      'comment' => 'string|nullable',
      'payment_type' => 'in:' . implode(',', self::$paymentTypes),
      'payment_status' => 'in:' . implode(',', self::$paymentStatuses),
      'currency' => 'in:' . implode(',', self::$currencies),
    ];
  }
}
