<?php

namespace App\Services\Integration;

use App\Models\GetcourseUser;
use App\Modules\Integration\Core\Concerns\ResourceRequestBodyFormat;
use App\Modules\Integration\Core\Concerns\ResourceRequestOptions;
use App\Modules\Integration\Domain\Getcourse\GetcourseModel;
use App\Modules\Integration\Domain\Getcourse\GetcourseResource;
use Illuminate\Support\Facades\App;
use Mosquitto\Exception;

class GetcourseUserImportService extends GetcourseModel
{
  private int $exportCheckInterval = 60;
  private int $exportAttempt = 0;

  public function __construct(GetcourseResource $getcourseResource)
  {
    parent::__construct($getcourseResource);
  }

  public static function get($filters)
  {
    $model = App::make(static::class);

    $result = $model->resource->fetch("account/users", new ResourceRequestOptions(
      body: $filters,
      bodyFormat: ResourceRequestBodyFormat::QUERY
    ));

    if (!$result['success'])
      throw new Exception($result['error_message']);

    $rawItems = $model->checkExport($result['info']['export_id']);

    array_reduce($rawItems, function ($array, $item) {
      [$id, $email, $regType, $createdAt, $lastActivityAt, $name, $lastName,
        $phone, $birthDate, $age, $country, $city, $partner, $age1, $city1,
        $needTrainingForSchoolChildren, $emailCheckFrequency, $purposeOfParticipation,
        $bill, $needTrainingForChildren, $english, $memoryTraining, $otherLangs, $time,
        $location, $sessionId, $term, $gcId, $buyingTermsAccepted, $number, $ymcId, $text,
        $ct, $dataHandlingTermsAccepted, $webinarEntranceDate, $sum, $fbp, $fbc, $social,
        $newLocation, $roistat, $firstGroupName, $medium, $admitad, $cameFrom, $utmSource,
        $utmMedium, $utmCampaign, $utmTerm, $utmContent, $utmGroup, $partnerId, $partnerEmail,
        $partnerName, $managerName, $vkId] = $item;

      GetcourseUser::insertOrIgnore([
        'email' => $email,
        'id_getcourse' => $id,
        'name' => $name ?? '',
        'city' => $city ?? '',
        'country' => $country ?? '',
        'phone' => $phone ?? '',
        'created_at' => $createdAt
      ]);

    }, []);

    return "export done";
  }

  protected function checkExport($exportId)
  {
    $model = App::make(static::class);

    sleep($this->exportCheckInterval);

    $checkExport = $model->resource->fetch("account/exports/{$exportId}", new ResourceRequestOptions(
      bodyFormat: ResourceRequestBodyFormat::QUERY
    ));

    if ($checkExport['success']) {
      return $checkExport['info']['items'];
    } else {
      $this->exportAttempt++;

      return $this->checkExport($exportId);
    }
  }
}
