<?php

namespace App\Modules\Integration\Domain\Amocrm\Link;

use AmoCRM\Collections\LinksCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\InvalidArgumentException;
use App\Modules\Integration\Domain\Amocrm\Contact\ContactModel;
use App\Modules\Integration\Domain\Amocrm\Lead\LeadModel;
use App\Modules\Integration\Domain\Amocrm\AmocrmModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class LinkModel extends AmocrmModel
{
  /**
   *
   * @param AmocrmModel $baseLink
   * @param Collection|AmocrmModel $bindableLink
   * @return LinkModel
   * @throws AmoCRMApiException
   * @throws AmoCRMMissedTokenException
   * @throws AmoCRMoAuthApiException
   * @throws InvalidArgumentException
   */
  public static function link(AmocrmModel $baseLink, Collection|AmocrmModel $bindableLink): LinkModel
  {
    /**
     * @var static $model
     */
    $model = App::make(static::class);

    /**
     * @var AmocrmModel $instance
     */
    $baseLinkClass = get_class($baseLink);
    $links = new LinksCollection();

    switch ($baseLinkClass) {
      case ContactModel::class:
        if ($bindableLink instanceof Collection) {
          foreach ($bindableLink as $key => $instance) {
            $links->add($instance);
          }
        } else {
          $links->add($bindableLink->sdkModel);
        }
        $link = $baseLink->apiClient->apiClientSDK->contacts()->link($baseLink->sdkModel, $links);
        break;
      case LeadModel::class:
        if ($bindableLink instanceof Collection) {
          foreach ($bindableLink as $key => $instance) {
            $links->add($instance);
          }
        } else {
          $links->add($bindableLink->sdkModel);
        }
        $link = $baseLink->apiClient->apiClientSDK->leads()->link($baseLink->sdkModel, $links);
        break;
      default:
        break;
    }

    $model->setAttributes($link->toArray());

    return $model;
  }
}
