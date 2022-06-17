<?php

namespace App\Services\Integration;

use App\Modules\Integration\Domain\Getcourse\Deal\DealModel;
use Throwable;

class GetcourseDealService
{
  /**
   * @param string $email
   * @param string $title
   * @param int|null $number
   * @param int|null $quantity
   * @param int|null $cost
   * @param string $status
   * @param string $dealPaid
   * @param string $managerEmail
   * @param string $comment
   * @param string $paymentType
   * @param string $paymentStatus
   * @param string $currency
   * @return array
   * @throws Throwable
   */
  public function createOrUpdate(
    string $email,
    string $title,
    int    $number = null,
    int    $quantity = null,
    int    $cost = null,
    string $status = "",
    string $dealPaid = "",
    string $managerEmail = "",
    string $comment = "",
    string $paymentType = "",
    string $paymentStatus = "",
    string $currency = 'RUB'
  ): array
  {
    $deal = DealModel::createOrUpdate([
      "email" => $email,
      "title" => $title,
      "number" => $number,
      "quantity" => $quantity,
      "cost" => $cost,
      "status" => $status,
      "is_paid" => $dealPaid,
      "manager_email" => $managerEmail,
      "comment" => $comment,
      "payment_type" => $paymentType,
      "payment_status" => $paymentStatus,
      "currency" => $currency
    ]);

    return $deal->attributes;
  }
}
