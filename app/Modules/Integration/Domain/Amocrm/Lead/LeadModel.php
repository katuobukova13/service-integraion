<?php

namespace App\Modules\Integration\Domain\Amocrm\Lead;

use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Exceptions\AmoCRMApiErrorResponseException;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Models\LeadModel as LeadSDKModel;
use App\Modules\Integration\Core\Concerns\Crud;
use App\Modules\Integration\Core\Concerns\RequestBodyFormat;
use App\Modules\Integration\Core\Concerns\RequestMethod;
use App\Modules\Integration\Core\Facades\BaseModel;
use App\Modules\Integration\Core\Facades\RequestOptions;
use App\Modules\Integration\Domain\Amocrm\AmocrmAPIClient;
use App\Modules\Integration\Domain\Amocrm\AmocrmCustomField;
use App\Modules\Integration\Domain\Amocrm\AmocrmSamplingClause;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use JetBrains\PhpStorm\ArrayShape;

final class LeadModel extends BaseModel implements Crud
{
  /**
   * @throws Exception
   */
  public function __construct(
    public AmocrmAPIClient $apiClient,
    public LeadResource    $resource,
    public LeadSDKModel    $sdkModel
  )
  {
    if (empty(config('services.amocrm.advance.custom_fields.leads.city')))
      throw new Exception('Отсутствует id кастомного поля city');

    if (empty(config('services.amocrm.advance.custom_fields.leads.pay_date')))
      throw new Exception('Отсутствует id кастомного поля pay_date');

    if (empty(config('services.amocrm.advance.custom_fields.leads.order_id')))
      throw new Exception('Отсутствует id кастомного поля order_id');

    if (empty(config('services.amocrm.advance.custom_fields.leads.order_num')))
      throw new Exception('Отсутствует id кастомного поля order_num');

    if (empty(config('services.amocrm.advance.custom_fields.leads.integrator')))
      throw new Exception('Отсутствует id кастомного поля integrator');

    if (empty(config('services.amocrm.advance.custom_fields.leads.partner')))
      throw new Exception('Отсутствует id кастомного поля partner');
  }

  /**
   * @param int $id
   * @return LeadModel
   * @throws Exception
   */
  public static function find(int $id): self
  {
    $model = App::make(self::class);

    try {
      $leadService = $model->apiClient->client->leads();
    } catch (AmoCRMApiErrorResponseException $e) {
      dump($e->getValidationErrors());
      throw new Exception('Validation error');
    }

    $model->sdkModel = $leadService->getOne($id);

    $attributes = $model->getFields($model->sdkModel->toArray());

    $model->setAttributes($attributes);

    return $model;
  }

  /**
   * @param array $attributes
   * @return LeadModel
   * @throws Exception
   */
  public static function create(array $attributes): LeadModel
  {
    $model = App::make(self::class);

    $model->setNativeFieldsFromAttributes($attributes);
    $model->setCustomFieldsFromAttributes($attributes);

    try {
      $model->sdkModel = $model->apiClient->client->leads()->addOne($model->sdkModel);
    } catch (AmoCRMApiErrorResponseException $e) {
      dump($e->getValidationErrors());
      throw new Exception('Validation error');
    }

    $attributes = $model->getFields($model->sdkModel->toArray());

    $model->setAttributes($attributes);

    return $model;
  }

  /**
   * @param AmocrmSamplingClause $samplingClause
   * @return Collection
   * @throws Exception
   */
  public static function list(AmocrmSamplingClause $samplingClause): Collection
  {
    /**
     * @var LeadModel $blankModel
     */
    $blankModel = App::make(self::class);

    $rawSamplingClause = $blankModel->getRawSamplingClause($samplingClause);

    $response = $blankModel->resource->fetch('', new RequestOptions(
      method: RequestMethod::GET,
      body: $rawSamplingClause,
      bodyFormat: RequestBodyFormat::QUERY
    ));

    $collection = collect();

    foreach ($response["_embedded"]["leads"] ?? [] as $lead) {
      $model = App::make(self::class);
      $attributes = $model->getFields($lead);
      $model->setAttributes($attributes);
      $model->sdkModel = (new LeadSDKModel())->setId($lead['id']);
      $collection->add($model);
    }

    return $collection;
  }

  /**
   * @throws Exception
   */
  #[ArrayShape([
    'with' => "array",
    'page' => "int",
    'limit' => "int",
    'query' => "string",
    'filter' => "array",
    'order' => "array"
  ])]
  public static function getRawSamplingClause(AmocrmSamplingClause $samplingClause): array
  {
    $filterModified = null;

    if ($samplingClause->filter != []) {
      $filterModified = self::changeFilterQuery($samplingClause->filter);
    }

    $samplingClause = new AmocrmSamplingClause(
      with: $samplingClause->with,
      page: $samplingClause->page,
      limit: $samplingClause->limit,
      filter: $filterModified != null ? $filterModified : $samplingClause->filter
    );

    return $samplingClause->toArray();
  }

  /**
   * @throws Exception
   */
  public static function changeFilterQuery($filter): array
  {
    $filterModified = [];

    foreach ($filter as $key => $value) {
      switch ($key) {
        case 'city':
          $keyId = config('services.amocrm.advance.custom_fields.leads.city');

          if (is_array($value)) {
            foreach ($value as $item) {
              $filterModified['custom_fields'][$keyId][] = $item;
            }
          } else
            $filterModified['custom_fields'][$keyId] = $value;

          break;
        case 'pay_date':
          $keyId = config('services.amocrm.advance.custom_fields.leads.pay_date');

          if ($value != []) {
            $filterModified['custom_fields'][$keyId]['from'] = strtotime($value['from']);
            $filterModified['custom_fields'][$keyId]['to'] = strtotime($value['to']);
          }

          break;
        case 'order_id':
          $keyId = config('services.amocrm.advance.custom_fields.leads.order_id');

          if (is_array($value)) {
            foreach ($value as $item) {
              $filterModified['custom_fields'][$keyId][] = $item;
            }
          } else
            $filterModified['custom_fields'][$keyId] = $value;

          break;
        case 'order_num':
          $keyId = config('services.amocrm.advance.custom_fields.leads.order_num');

          if (is_array($value)) {
            foreach ($value as $item) {
              $filterModified['custom_fields'][$keyId][] = $item;
            }
          } else
            $filterModified['custom_fields'][$keyId] = $value;

          break;
        case 'integrator':
          $keyId = config('services.amocrm.advance.custom_fields.leads.integrator');

          if (is_array($value)) {
            foreach ($value as $item) {
              $filterModified['custom_fields'][$keyId][] = $item;
            }
          } else
            $filterModified['custom_fields'][$keyId] = $value;

          break;
        case 'partner':
          $keyId = config('services.amocrm.advance.custom_fields.leads.partner');

          if (is_array($value)) {
            foreach ($value as $item) {
              $filterModified['custom_fields'][$keyId][] = $item;
            }
          } else
            $filterModified['custom_fields'][$keyId] = $value;

          break;
      }
    }
    return $filterModified;
  }

  /**
   * @throws AmoCRMoAuthApiException
   * @throws AmoCRMApiException
   * @throws AmoCRMMissedTokenException
   * @throws Exception
   */
  public function update(array $attributes)
  {
    $this->setNativeFieldsFromAttributes($attributes);
    $this->setCustomFieldsFromAttributes($attributes);

    try {
      $this->sdkModel = $this->apiClient->client->leads()->updateOne($this->sdkModel);
    } catch (AmoCRMApiErrorResponseException $e) {
      dump($e->getValidationErrors());
      throw new Exception('Validation error');
    }
  }

  public function setNativeFieldsFromAttributes(array $attributes): void
  {
    foreach ($attributes as $key => $value) {
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
  }

  /**
   * @throws InvalidArgumentException
   * @throws Exception
   */
  public function setCustomFieldsFromAttributes($attributes): void
  {
    $customFieldsCollection = new CustomFieldsValuesCollection();

    foreach ($attributes as $key => $value) {
      if ($value !== null) {
        switch ($key) {
          case 'city':
            $keyId = config('services.amocrm.advance.custom_fields.leads.city');

            $customFieldsCollection->add(
              AmocrmCustomField::textField($keyId, $value)->getValuesModel()
            );

            break;
          case 'pay_date':
            $keyId = config('services.amocrm.advance.custom_fields.leads.pay_date');

            $customFieldsCollection->add(
              AmocrmCustomField::dateField($keyId, $value)->getValuesModel()
            );

            break;
          case 'order_id':
            $keyId = config('services.amocrm.advance.custom_fields.leads.order_id');

            $customFieldsCollection->add(
              AmocrmCustomField::textField($keyId, $value)->getValuesModel()
            );

            break;
          case 'order_num':
            $keyId = config('services.amocrm.advance.custom_fields.leads.order_num');

            $customFieldsCollection->add(
              AmocrmCustomField::textField($keyId, $value)->getValuesModel()
            );

            break;
          case 'integrator':
            $keyId = config('services.amocrm.advance.custom_fields.leads.integrator');

            $customFieldsCollection->add(
              AmocrmCustomField::textField($keyId, $value)->getValuesModel()
            );

            break;
          case 'partner':
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

  public function getFields($sdkFields): array
  {
    $fields = [];

    foreach ($sdkFields as $key => $value) {
      if ($value !== null) {
        switch ($key) {
          case 'id':
          case 'name':
          case 'price':
          case 'group_id':
          case 'source_id':
            $fields[$key] = $value;
            break;
          case "custom_fields_values":
            foreach ($sdkFields["custom_fields_values"] as $cfKey => $cfValue) {
              $this->setCustomToSimpleField($cfValue, $fields);
            }
            break;
          default:
            break;
        }
      }
    }

    return $fields;
  }

  private function setCustomToSimpleField($customField, &$fields)
  {
    switch ($customField['field_id']) {
      case config('services.amocrm.advance.custom_fields.leads.pay_date'):
        $fields['pay_date'] = Carbon::parse($customField['values'][0]['value'])
          ->timezone('Europe/Moscow')
          ->format('d.m.Y');

        break;
      case config('services.amocrm.advance.custom_fields.leads.order_id'):
        $fields['order_id'] = $customField['values'][0]['value'];

        break;
      case config('services.amocrm.advance.custom_fields.leads.order_num'):
        $fields['order_num'] = $customField['values'][0]['value'];

        break;
      case config('services.amocrm.advance.custom_fields.leads.partner'):
        $fields['partner'] = $customField['values'][0]['value'];

        break;
      case config('services.amocrm.advance.custom_fields.leads.city'):
        $fields['city'] = $customField['values'][0]['value'];

        break;
      case  config('services.amocrm.advance.custom_fields.leads.integrator'):
        $fields['integrator'] = $customField['values'][0]['value'];

        break;
    }
  }
}
