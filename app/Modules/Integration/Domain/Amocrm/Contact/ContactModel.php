<?php

namespace App\Modules\Integration\Domain\Amocrm\Contact;

use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Models\ContactModel as SDKContactModel;
use App\Modules\Integration\Domain\Amocrm\AmocrmCustomField;
use App\Modules\Integration\Domain\Amocrm\AmocrmModel;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class ContactModel extends AmocrmModel
{
  public \AmoCRM\Models\BaseApiModel $sdkModel;

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
   * @throws AmoCRMApiException
   * @throws AmoCRMMissedTokenException
   * @throws AmoCRMoAuthApiException
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
    /**
     * @var static $model
     */
    $response = parent::fetchList($select, $with, $filter, $page, $limit);

    $collection = collect();

    if ($response != null) {
      foreach ($response["_embedded"]["contacts"] as $contact) {
        $model = App::make(static::class);
        $model->setAttributes($contact);
        $model->sdkModel = (new SDKContactModel())->setId($contact['id']);
        $collection->add($model->attributes);
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
      $customFieldsCollection = new CustomFieldsValuesCollection();

      foreach ($customUpdatingFields as $key => $value) {
        if ($value != null) {
          switch ($key) {
            case 'cf_city':
              $keyId = config('services.amocrm.advance.custom_fields.contacts.city');

              $customFieldsCollection->add(
                AmocrmCustomField::textField($keyId, $value)->getValuesModel()
              );

              break;
            case 'cf_country':
              $keyId = config('services.amocrm.advance.custom_fields.contacts.country');

              $customFieldsCollection->add(
                AmocrmCustomField::textField($keyId, $value)->getValuesModel()
              );

              break;
            case 'cf_position':
              $keyId = config('services.amocrm.advance.custom_fields.contacts.position');

              $customFieldsCollection->add(
                AmocrmCustomField::textField($keyId, $value)->getValuesModel()
              );

              break;
            case 'cf_phone':
              $keyId = config('services.amocrm.advance.custom_fields.contacts.phone');

              $values = $this->updateOrCreateCustomPhoneOrEmail($keyId, $value);

              foreach ($values as $item) {
                $customFieldsCollection->add(
                  AmocrmCustomField::textField($keyId, $item)->getValuesModel()
                );
              }

              break;
            case 'cf_email':
              $keyId = config('services.amocrm.advance.custom_fields.contacts.email');

              $values = $this->updateOrCreateCustomPhoneOrEmail($keyId, $value);

              foreach ($values as $item) {
                $customFieldsCollection->add(
                  AmocrmCustomField::textField($keyId, $item)->getValuesModel()
                );
              }

              break;
            case 'cf_partner':
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
}

