<?php

namespace App\Modules\Integration\Domain\Amocrm\Contact;

use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\ContactModel as SDKContactModel;
use App\Modules\Integration\Domain\Amocrm\AmocrmCustomField;
use App\Modules\Integration\Domain\Amocrm\AmocrmModel;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class ContactModel extends AmocrmModel
{
  public BaseApiModel $sdkModel;

  public function __construct(SDKContactModel $sdkModel, ContactResource $resource)
  {
    $this->sdkModel = $sdkModel;
    $this->resource = $resource;

    parent::__construct();
  }

  /**
   * @param int $id
   * @return ContactModel
   */
  public static function find(int $id): static
  {
    $model = App::make(static::class);

    $contactsService = $model->apiClient->apiClientSDK->contacts();

    $model->sdkModel = $contactsService->getOne($id);

    $model->setAttributes($model->sdkModel->toArray());

    return $model;
  }

  /**
   * @param array $attributes
   * @return ContactModel
   */
  public static function create(array $attributes): ContactModel
  {
    $model = App::make(static::class);

    $model->setCustomFields($attributes);

    foreach ($attributes as $key => $value) {
      $model->setNativeFields($key, $value);
    }

    $model->sdkModel = $model->apiClient->apiClientSDK->contacts()->addOne($model->sdkModel);

    $model->setAttributes($model->sdkModel->toArray());

    return $model;
  }

  /**
   * @throws AmoCRMApiException
   * @throws AmoCRMoAuthApiException
   * @throws AmoCRMMissedTokenException
   * @throws Exception
   */
  public function update(array $attributes)
  {
    $this->setCustomFields($attributes);

    $updatingFields = array_intersect_key($attributes, $this->attributes);

    foreach ($updatingFields as $key => $value) {
      $this->setNativeFields($key, $value);
    }

    $this->sdkModel = $this->apiClient->apiClientSDK->contacts()->updateOne($this->sdkModel);
  }

  /**
   * @param array|null $select
   * @param array|null $with
   * @param array|null $filter
   * @param int|null $page
   * @param int|null $limit
   * @return Collection
   * @throws Exception
   */
  public static function list(?array $select = [], ?array $with = [], ?array $filter = [], ?int $page = null, ?int $limit = null): Collection
  {
    $filterModified = null;

    if ($filter != []) {
      $filterModified = self::changeFilterQuery($filter);
    }

    $response = $filterModified != null ?
      parent::fetchList($select, $with, $filterModified, $page, $limit) :
      parent::fetchList($select, $with, $filter, $page, $limit);

    $collection = collect();

    if ($response != null) {
      foreach ($response["_embedded"]["contacts"] as $contact) {
        $model = App::make(static::class);
        $model->setAttributes($contact);
        $model->sdkModel = (new SDKContactModel())->setId($contact['id']);
        $collection->add($model);
      }
    }

    return $collection;
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

  public function setNativeFields($key, $value)
  {
    if ($value !== null) {
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

  public function setCustomFields($attributes)
  {
    $customUpdatingFields = array_filter($attributes, function ($attribute) {
      return str_starts_with($attribute, 'cf_');
    }, ARRAY_FILTER_USE_KEY);

    if ($customUpdatingFields) {
      foreach ($customUpdatingFields as $field => $value) {
        $fields[mb_substr($field, 3)] = $value;
      }

      $customFieldsCollection = new CustomFieldsValuesCollection();

      foreach ($fields as $key => $value) {
        if ($value != null) {
          switch ($key) {
            case 'city':
              $keyId = config('services.amocrm.advance.custom_fields.contacts.city');

              $customFieldsCollection->add(
                AmocrmCustomField::textField($keyId, $value)->getValuesModel()
              );

              break;
            case 'country':
              $keyId = config('services.amocrm.advance.custom_fields.contacts.country');

              $customFieldsCollection->add(
                AmocrmCustomField::textField($keyId, $value)->getValuesModel()
              );

              break;
            case 'position':
              $keyId = config('services.amocrm.advance.custom_fields.contacts.position');

              $customFieldsCollection->add(
                AmocrmCustomField::textField($keyId, $value)->getValuesModel()
              );

              break;
            case 'phone':
              $keyId = config('services.amocrm.advance.custom_fields.contacts.phone');

              $values = $this->updateOrCreateCustomPhoneOrEmail($keyId, $value);

              foreach ($values as $item) {
                $customFieldsCollection->add(
                  AmocrmCustomField::textField($keyId, $item)->getValuesModel()
                );
              }

              break;
            case 'email':
              $keyId = config('services.amocrm.advance.custom_fields.contacts.email');

              $values = $this->updateOrCreateCustomPhoneOrEmail($keyId, $value);

              foreach ($values as $item) {
                $customFieldsCollection->add(
                  AmocrmCustomField::textField($keyId, $item)->getValuesModel()
                );
              }

              break;
            case 'partner':
              $keyId = config('services.amocrm.advance.custom_fields.contacts.partner');

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
        case 'phone':
          $keyId = config('services.amocrm.advance.custom_fields.contacts.phone');

          if (is_array($value)) {
            foreach ($value as $item) {
              $filterModified['custom_fields'][$keyId][] = $item;
            }
          } else
            $filterModified['custom_fields'][$keyId] = $value;

          break;
        case 'email':
          $keyId = config('services.amocrm.advance.custom_fields.contacts.email');

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
}

