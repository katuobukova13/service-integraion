<?php

namespace App\Modules\Integration\Domain\Amocrm\Contact;

use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Exceptions\AmoCRMApiErrorResponseException;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Models\ContactModel as ContactSDKModel;
use App\Modules\Integration\Core\Concerns\Crud;
use App\Modules\Integration\Core\Concerns\RequestBodyFormat;
use App\Modules\Integration\Core\Concerns\RequestMethod;
use App\Modules\Integration\Core\Facades\BaseModel;
use App\Modules\Integration\Core\Facades\RequestOptions;
use App\Modules\Integration\Domain\Amocrm\AmocrmAPIClient;
use App\Modules\Integration\Domain\Amocrm\AmocrmCustomField;
use App\Modules\Integration\Domain\Amocrm\AmocrmSamplingClause;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use JetBrains\PhpStorm\ArrayShape;

final class ContactModel extends BaseModel implements Crud
{
  /**
   * @throws Exception
   */
  public function __construct(
    public AmocrmAPIClient $apiClient,
    public ContactResource $resource,
    public ContactSDKModel $sdkModel
  )
  {
    if (empty(config('services.amocrm.advance.custom_fields.contacts.city')))
      throw new Exception('Отсутствует id кастомного поля city');

    if (empty(config('services.amocrm.advance.custom_fields.contacts.country')))
      throw new Exception('Отсутствует id кастомного поля country');

    if (empty(config('services.amocrm.advance.custom_fields.contacts.position')))
      throw new Exception('Отсутствует id кастомного поля position');

    if (empty(config('services.amocrm.advance.custom_fields.contacts.phones')))
      throw new Exception('Отсутствует id кастомного поля phones');

    if (empty(config('services.amocrm.advance.custom_fields.contacts.emails')))
      throw new Exception('Отсутствует id кастомного поля emails');

    if (empty(config('services.amocrm.advance.custom_fields.contacts.partner')))
      throw new Exception('Отсутствует id кастомного поля partner');
  }

  /**
   * @param int $id
   * @return ContactModel
   * @throws AmoCRMApiException
   * @throws Exception
   */
  public static function find(int $id): self
  {
    /**
     * @var ContactModel $model
     */

    $model = App::make(self::class);

    try {
      $contactsService = $model->apiClient->client->contacts();
    } catch (AmoCRMApiErrorResponseException $e) {
      dump($e->getValidationErrors());
      throw new Exception('Validation error');
    }

    $model->sdkModel = $contactsService->getOne($id);

    $attributes = $model->getFields($model->sdkModel->toArray());

    $model->setAttributes($attributes);

    return $model;
  }

  public function getFields($sdkFields): array
  {
    $fields = [];

    foreach ($sdkFields as $key => $value) {
      if ($value !== null) {
        switch ($key) {
          case 'first_name':
          case 'last_name':
          case 'id':
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

    $fields['name'] = $fields['first_name'] . ' ' . $fields['last_name'];

    return $fields;
  }

  private function setCustomToSimpleField($customField, &$fields)
  {
    switch ($customField['field_id']) {
      case config('services.amocrm.advance.custom_fields.contacts.emails'):
        foreach ($customField['values'] as $key => $value) {
          $fields['emails'][] = $value['value'];
        }

        break;
      case config('services.amocrm.advance.custom_fields.contacts.phones'):
        foreach ($customField['values'] as $key => $value) {
          $fields['phones'][] = $value['value'];
        }
        break;
      case config('services.amocrm.advance.custom_fields.contacts.city'):
        $fields['city'] = $customField['values'][0]['value'];

        break;
      case config('services.amocrm.advance.custom_fields.contacts.country'):
        $fields['country'] = $customField['values'][0]['value'];

        break;
      case config('services.amocrm.advance.custom_fields.contacts.position'):
        $fields['position'] = $customField['values'][0]['value'];

        break;
      case config('services.amocrm.advance.custom_fields.contacts.partner'):
        $fields['partner'] = $customField['values'][0]['value'];

        break;
    }
  }

  /**
   * @param array $attributes
   * @return ContactModel
   * @throws AmoCRMApiException
   * @throws Exception
   */
  public static function create(array $attributes): ContactModel
  {
    /**
     * @var ContactModel $model
     */

    $model = App::make(self::class);

    $model->setNativeFieldsFromAttributes($attributes);
    $model->setCustomFieldsFromAttributes($attributes);

    try {
      $model->sdkModel = $model->apiClient->client->contacts()->addOne($model->sdkModel);
    } catch (AmoCRMApiErrorResponseException $e) {
      dump($e->getValidationErrors());
      throw new Exception('Validation error');
    }

    $attributes = $model->getFields($model->sdkModel->toArray());

    $model->setAttributes($attributes);

    return $model;
  }

  private function setNativeFieldsFromAttributes(array $attributes): void
  {
    foreach ($attributes as $key => $value) {
      if (!isset($value)) continue;

      switch ($key) {
        case 'first_name':
          $this->sdkModel->setFirstName($value);
          break;
        case 'last_name':
          $this->sdkModel->setLastName($value);
          break;
        case 'name':
          $this->sdkModel->setName($value);
          break;
        case 'responsible_user_id':
          $this->sdkModel->setResponsibleUserId($value);
          break;
        default:
          break;
      }
    }
  }

  private function setCustomFieldsFromAttributes(array $attributes): void
  {
    $customFieldsValuesCollection = new CustomFieldsValuesCollection();

    foreach ($attributes as $key => $value) {
      if (!isset($value)) continue;

      switch ($key) {
        case 'city':
          $keyId = config('services.amocrm.advance.custom_fields.contacts.city');

          $customFieldsValuesCollection->add(
            AmocrmCustomField::textField($keyId, $value)->getValuesModel()
          );

          break;
        case 'country':
          $keyId = config('services.amocrm.advance.custom_fields.contacts.country');

          $customFieldsValuesCollection->add(
            AmocrmCustomField::textField($keyId, $value)->getValuesModel()
          );

          break;
        case 'position':
          $keyId = config('services.amocrm.advance.custom_fields.contacts.position');

          $customFieldsValuesCollection->add(
            AmocrmCustomField::textField($keyId, $value)->getValuesModel()
          );

          break;
        case 'phones':
          $keyId = config('services.amocrm.advance.custom_fields.contacts.phones');

          $values = $this->updateOrCreateCustomPhoneOrEmail($keyId, $value);

          foreach ($values as $item) {
            $customFieldsValuesCollection->add(
              AmocrmCustomField::textField($keyId, $item)->getValuesModel()
            );
          }

          break;
        case 'emails':
          $keyId = config('services.amocrm.advance.custom_fields.contacts.emails');

          $values = $this->updateOrCreateCustomPhoneOrEmail($keyId, $value);

          foreach ($values as $item) {
            $customFieldsValuesCollection->add(
              AmocrmCustomField::textField($keyId, $item)->getValuesModel()
            );
          }

          break;
        case 'partner':
          $keyId = config('services.amocrm.advance.custom_fields.contacts.partner');

          $customFieldsValuesCollection->add(
            AmocrmCustomField::textField($keyId, $value)->getValuesModel()
          );
          break;
      }
    }

    $this->sdkModel->setCustomFieldsValues($customFieldsValuesCollection);
  }

  private function updateOrCreateCustomPhoneOrEmail($keyId, $value)
  {
    $customMergingFields = $this->sdkModel->getCustomFieldsValues();

    if ($customMergingFields != null) {
      $customValues = $customMergingFields->getBy('fieldId', $keyId);
      if ($customValues != null) {
        foreach ($customValues->getValues() as $customValue) {
          foreach ($value as $item) {
            if ($customValue->getValue() !== $item) {
              $value[] = $customValue->getValue();
            }
          }
        }
      }
    }
    return array_unique($value);
  }

  /**
   * @param AmocrmSamplingClause $samplingClause
   * @return Collection
   * @throws Exception
   */
  public static function list(AmocrmSamplingClause $samplingClause): Collection
  {
    /**
     * @var ContactModel $blankModel
     */

    $blankModel = App::make(self::class);

    $rawSamplingClause = $blankModel->getRawSamplingClause($samplingClause);

    $response = $blankModel->resource->fetch('', new RequestOptions(
      method: RequestMethod::GET,
      body: $rawSamplingClause,
      bodyFormat: RequestBodyFormat::QUERY
    ));

    $collection = collect();

    foreach ($response["_embedded"]["contacts"] ?? [] as $contact) {
      $model = App::make(self::class);
      $attributes = $model->getFields($contact);
      $model->setAttributes($attributes);
      $model->sdkModel = (new ContactSDKModel())->setId($contact['id']);
      $collection->add($model);
    }

    return $collection;
  }

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

  public static function changeFilterQuery($filter): array
  {
    $filterModified = [];

    foreach ($filter as $key => $value) {
      switch ($key) {
        case 'city':
          $keyId = config('services.amocrm.advance.custom_fields.contacts.city');

          if (is_array($value)) {
            foreach ($value as $item) {
              $filterModified['custom_fields'][$keyId][] = $item;
            }
          } else
            $filterModified['custom_fields'][$keyId] = $value;

          break;
        case 'country':
          $keyId = config('services.amocrm.advance.custom_fields.contacts.country');

          if (is_array($value)) {
            foreach ($value as $item) {
              $filterModified['custom_fields'][$keyId][] = $item;
            }
          } else
            $filterModified['custom_fields'][$keyId] = $value;

          break;
        case 'position':
          $keyId = config('services.amocrm.advance.custom_fields.contacts.position');

          if (is_array($value)) {
            foreach ($value as $item) {
              $filterModified['custom_fields'][$keyId][] = $item;
            }
          } else
            $filterModified['custom_fields'][$keyId] = $value;

          break;
        case 'phones':
          $keyId = config('services.amocrm.advance.custom_fields.contacts.phones');

          if (is_array($value)) {
            foreach ($value as $item) {
              $filterModified['custom_fields'][$keyId][] = $item;
            }
          } else
            $filterModified['custom_fields'][$keyId] = $value;

          break;
        case 'emails':
          $keyId = config('services.amocrm.advance.custom_fields.contacts.emails');

          if (is_array($value)) {
            foreach ($value as $item) {
              $filterModified['custom_fields'][$keyId][] = $item;
            }
          } else
            $filterModified['custom_fields'][$keyId] = $value;

          break;
        case 'partner':
          $keyId = config('services.amocrm.advance.custom_fields.contacts.partner');

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
   * @throws AmoCRMApiException
   * @throws AmoCRMoAuthApiException
   * @throws AmoCRMMissedTokenException
   * @throws Exception
   */
  public function update(array $attributes)
  {
    $this->setNativeFieldsFromAttributes($attributes);
    $this->setCustomFieldsFromAttributes($attributes);

    try {
      $this->sdkModel = $this->apiClient->client->contacts()->updateOne($this->sdkModel);
    } catch (AmoCRMApiErrorResponseException $e) {
      dump($e->getValidationErrors());
      throw new Exception('Validation error');
    }
  }
}

