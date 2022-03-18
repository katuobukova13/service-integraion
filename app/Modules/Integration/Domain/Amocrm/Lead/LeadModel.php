<?php

namespace App\Modules\Integration\Domain\Amocrm\Lead;

use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Models\LeadModel as SDKLeadModel;
use App\Modules\Integration\Domain\Amocrm\AmocrmCustomField;
use App\Modules\Integration\Domain\Amocrm\AmocrmModel;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class LeadModel extends AmocrmModel
{
  public \AmoCRM\Models\BaseApiModel $sdkModel;

  public function __construct(SDKLeadModel $sdkModel, LeadResource $resource)
  {
    $this->sdkModel = $sdkModel;
    $this->resource = $resource;

    parent::__construct();
  }

  /**
   * @param int $id
   * @return LeadModel
   */
  public static function find(int $id): static
  {
    $model = App::make(static::class);

    $leadService = $model->apiClient->apiClientSDK->leads();

    $model->sdkModel = $leadService->getOne($id);

    $model->setAttributes($model->sdkModel->toArray());

    return $model;
  }

  /**
   * @param array $attributes
   * @return LeadModel
   */
  public static function create(array $attributes): static
  {
    $model = App::make(static::class);

    $model->setCustomFields($attributes);

    foreach ($attributes as $key => $value) {
      $model->setNativeFields($key, $value);
    }

    $model->sdkModel = $model->apiClient->apiClientSDK->leads()->addOne($model->sdkModel);

    $model->setAttributes($model->sdkModel->toArray());

    return $model;
  }

  /**
   * @throws AmoCRMoAuthApiException
   * @throws AmoCRMApiException
   * @throws AmoCRMMissedTokenException
   */
  public function update(array $attributes)
  {
    $this->setCustomFields($attributes);

    $updatingFields = array_intersect_key($attributes, $this->attributes);

    foreach ($updatingFields as $key => $value) {
      $this->setNativeFields($key, $value);
    }

    $this->sdkModel = $this->apiClient->apiClientSDK->leads()->updateOne($this->sdkModel);
  }

  /**
   * @param array|null $select
   * @param array|null $with
   * @param array|null $filter
   * @param int|null $page
   * @param int|null $limit
   * @return LeadModel[]
   * @throws Exception
   */
  public static function list(?array $select = [], ?array $with = [], ?array $filter = [], ?int $page = null, ?int $limit = null): Collection
  {
    $response = parent::fetchList($select, $with, $filter, $page, $limit);

    $collection = collect();

    if ($response != null) {
      foreach ($response["_embedded"]["leads"] as $lead) {
        $model = App::make(static::class);
        $model->setAttributes($lead);
        $model->sdkModel = (new SDKLeadModel())->setId($lead['id']);
        $collection->add($model->attributes);
      }
    }

    return $collection;
  }

  public function setNativeFields($key, $value)
  {
    if ($value !== null) {
      switch ($key) {
        case 'name':
          $this->sdkModel->setName($value);
          break;
        case 'price':
          $this->sdkModel->setPrice($value);
          break;
        case 'group_id':
          $this->sdkModel->setGroupId($value);
          break;
        case 'responsible_user_id':
          $this->sdkModel->setResponsibleUserId($value);
          break;
        case 'source_id':
          $this->sdkModel->setSourceId($value);
          break;
        default:
          break;
      }
    }
  }

  /**
   * @throws \AmoCRM\Exceptions\InvalidArgumentException
   */
  public function setCustomFields($attributes)
  {
    $customUpdatingFields = array_filter($attributes, function ($attribute) {
      return str_starts_with($attribute, 'cf_');
    }, ARRAY_FILTER_USE_KEY);

    if ($customUpdatingFields) {
      $customFieldsCollection = new CustomFieldsValuesCollection();

      foreach ($customUpdatingFields as $key => $value) {
        if ($value !== null) {
          switch ($key) {
            case 'cf_city':
              $keyId = config('services.amocrm.advance.custom_fields.leads.city');

              $customFieldsCollection->add(
                AmocrmCustomField::textField($keyId, $value)->getValuesModel()
              );

              break;
            case 'cf_pay_date':
              $keyId = config('services.amocrm.advance.custom_fields.leads.pay_date');

              $customFieldsCollection->add(
                AmocrmCustomField::dateField($keyId, $value)->getValuesModel()
              );

              break;
            case 'cf_order':
              $keyId = config('services.amocrm.advance.custom_fields.leads.order');

              $customFieldsCollection->add(
                AmocrmCustomField::textField($keyId, $value)->getValuesModel()
              );

              break;
            case 'cf_integrator':
              $keyId = config('services.amocrm.advance.custom_fields.leads.integrator');

              $customFieldsCollection->add(
                AmocrmCustomField::textField($keyId, $value)->getValuesModel()
              );

              break;
            case 'cf_partner':
              $keyId = config('services.amocrm.advance.custom_fields.leads.partner');

              $customFieldsCollection->add(
                AmocrmCustomField::textField($keyId, $value)->getValuesModel()
              );

              break;
          }
        }
      }

      $this->sdkModel->setCustomFieldsValues($customFieldsCollection);
    }
  }
}
