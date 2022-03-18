<?php

namespace App\Modules\Integration\Domain\Amocrm;

use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Models\CustomFieldsValues\DateCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\DateCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\TextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\DateCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\TextCustomFieldValueModel;

class AmocrmCustomField
{
  private $valuesModel;

  public static function textField(int $fieldId, string $value): static
  {
    $customField = new static;

    $customField->valuesModel = new TextCustomFieldValuesModel();

    $customField->valuesModel
      ->setFieldId($fieldId)
      ->setValues(
        (new TextCustomFieldValueCollection())
          ->add((new TextCustomFieldValueModel())->setValue($value))
      );

    return $customField;
  }

  /**
   * @throws InvalidArgumentException
   */
  public static function dateField($fieldId, $value): static
  {
    $customField = new static;

    $customField->valuesModel = new DateCustomFieldValuesModel();

    $customField->valuesModel
      ->setFieldId($fieldId)
      ->setValues(
        (new DateCustomFieldValueCollection())
          ->add((new DateCustomFieldValueModel())->setValue($value))
      );

    return $customField;
  }

  public function getValuesModel()
  {
    return $this->valuesModel;
  }
}
