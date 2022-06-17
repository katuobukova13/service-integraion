<?php

namespace App\Modules\Integration\Domain\Amocrm\Link;

use AmoCRM\Collections\LinksCollection;
use App\Modules\Integration\Core\Facades\BaseModel;
use App\Modules\Integration\Domain\Amocrm\Contact\ContactModel;
use App\Modules\Integration\Domain\Amocrm\Lead\LeadModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

final class LinkModel extends BaseModel
{
  public static function link(BaseModel $baseLink, Collection|BaseModel $bindableLink): LinkModel
  {
    /**
     * @var static $model
     */
    $model = App::make(self::class);

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
        $link = $baseLink->apiClient->client->contacts()->link($baseLink->sdkModel, $links);
        break;
      case LeadModel::class:
        if ($bindableLink instanceof Collection) {
          foreach ($bindableLink as $key => $instance) {
            $links->add($instance);
          }
        } else {
          $links->add($bindableLink->sdkModel);
        }
        $link = $baseLink->apiClient->client->leads()->link($baseLink->sdkModel, $links);
        break;
      default:
        break;
    }

    $model->setAttributes($link->toArray());

    return $model;
  }
}
